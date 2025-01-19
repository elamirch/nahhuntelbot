<?php
    class User {
        public function create(string $telegram_username, int $telegram_user_id,
        string $default_bot, array $chat_sessions, string $current_session) {
            global $pdo;
            for ($i=0; $i < 10; $i++) {
                try {
                    echo "\n1: " . $telegram_username . "\n2: " . $telegram_user_id . "\n3: " .
                                "\n4: " . $default_bot . "\n5: " . json_encode($chat_sessions).
                                "\n6: " . $current_session;
                    $stmt = $pdo->prepare("INSERT INTO `users` (`telegram_username`," .
                    "`telegram_user_id`, `default_bot`, `chat_sessions`, `current_session`, `credit`) VALUES (:tun, :ui, :db," .
                    " :css, :cs, 25);");
                    $stmt->execute(array(':tun' => $telegram_username,
                    ':ui' => $telegram_user_id, ':db' => $default_bot, ':css' => json_encode($chat_sessions),
                    ':cs' => $current_session));
                    break;
                } catch(Exception $e) {
                    echo $e->getMessage();
                    db_up();
                }
            }
        }

        public function update($column, $value, $variable_name, $assigned_value) {
            global $pdo;
            for ($i=0; $i < 10; $i++) {
                try {
                    $stmt = $pdo->prepare("UPDATE `users` 
                            SET `$variable_name` = :asv 
                            WHERE `$column` = :ui");
                    $stmt->execute(array(':ui' => $value,
                                ':asv' => $assigned_value));
                    break;
                } catch(Exception $e) {
                    echo $e->getMessage();
                    db_up();
                }
            }
        }

        public function read($column, $value) {
            global $pdo;
            for ($i=0; $i < 10; $i++) {
                try {
                    return $pdo->query("SELECT * FROM `users` WHERE $column=$value")->fetchAll();
                } catch(Exception $e) {
                    echo $e->getMessage();
                    db_up();
                }
            }
        }

        public function default_bot() {
            
        }
    }