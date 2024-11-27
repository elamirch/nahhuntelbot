<?php
    class User {
        public function create(string $telegram_username, int $telegram_user_id,
        string $default_bot, array $chat_sessions, string $current_session) {
            global $pdo;
            $stmt = $pdo->prepare("INSERT INTO `users` (`telegram_username`," .
            "`telegram_user_id`, `default_bot`, `chat_sessions`, `current_session`) VALUES (:tun, :ui, :db," .
            " :css, :cs);");
            $stmt->execute(array(':tun' => $telegram_username,
            ':ui' => $telegram_user_id, ':db' => $default_bot, ':css' => json_encode($chat_sessions),
            ':cs' => $current_session));
        }

        public function update($column, $value, $variable_name, $assigned_value) {
            global $pdo;
            $stmt = $pdo->prepare("UPDATE `users` 
                       SET `$variable_name` = :asv 
                       WHERE `$column` = :ui");
            $stmt->execute(array(':ui' => $value,
                        ':asv' => $assigned_value));
        }

        public function read($column, $value) {
            global $pdo;
            return $pdo->query("SELECT * FROM `users` WHERE $column=$value")->fetchAll();
        }

        public function default_bot() {
            
        }
    }