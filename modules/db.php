<?php
    $pdo = new stdClass();

    //Variables are derived from .env in bootstrap.php
    function db_up() {
        global $DB_HOST, $DB_PORT, $DB_USER, $DB_PASS, $DB_NAME, $pdo;

        $pdo = new PDO("mysql:host=$DB_HOST;port=$DB_PORT", $DB_USER, password: $DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_PERSISTENT, True);
        
        $create_db = $pdo->prepare("CREATE DATABASE IF NOT EXISTS $DB_NAME");
        $create_db->execute();
    
        $pdo = new PDO("mysql:host=$DB_HOST;port=$DB_PORT;dbname=$DB_NAME", $DB_USER, $DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_PERSISTENT, True);
    }

    db_up();

    try {
        //SQL statements to create tables
        $createOffsetsTable = "CREATE TABLE IF NOT EXISTS offsets (
            id INT AUTO_INCREMENT PRIMARY KEY,
            value BIGINT(10) NOT NULL
        )";

        //If later we created a webapp and user was permitted not having telegram_user_id,
        //then it can be set to null
        $createUsersTable = "CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            telegram_username VARCHAR(32) NULL,
            telegram_user_id BIGINT(12) NOT NULL,
            chat_sessions JSON NULL,
            current_session VARCHAR(64) NOT NULL,
            default_bot VARCHAR(64) NOT NULL,
            credit DOUBLE(12, 5) DEFAULT 0.0,
            payment_transactions JSON NULL,
            bots JSON NULL,
            checkpoint TEXT(16) NULL
        )";
    
        $createBotsTable = "CREATE TABLE IF NOT EXISTS bots (
            id INT AUTO_INCREMENT PRIMARY KEY,
            metis_bot_id VARCHAR(64),
            name VARCHAR(255) NOT NULL,
            description TEXT NULL,
            instructions TEXT NULL,
            enabled TINYINT(1) DEFAULT 1,
            providerConfig JSON NOT NULL,
            summarizer JSON NULL,
            functions JSON NULL,
            corpusIds JSON NULL,
            main TINYINT(1) DEFAULT 0
        )";
    
        //Execute table creation
        $pdo->exec($createOffsetsTable);
        $pdo->exec($createUsersTable);
        $pdo->exec($createBotsTable);
        
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }