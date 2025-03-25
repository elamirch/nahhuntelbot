<?php
    class User {
        public function create(string $telegram_username, int $telegram_user_id,
        string $default_bot, array $chat_sessions, string $current_session) {
            global $pdo;

            //Log user creating try
            logMessage( "Creating user: ".
                        "1: " . $telegram_username .
                        "2: " . $telegram_user_id .
                        "3: " . $default_bot .
                        "4: " . json_encode($chat_sessions).
                        "5: " . $current_session);
            
            //Create user
            $stmt = $pdo->prepare(
                "INSERT INTO `users` (`telegram_username`," .
                "`telegram_user_id`, `default_bot`, `chat_sessions`,".
                " `current_session`, `credit`) VALUES (:tun, :ui, :db," .
                " :css, :cs, 25);"
            );
            $stmt->execute(
                array(
                    ':tun' => $telegram_username,
                    ':ui' => $telegram_user_id,
                    ':db' => $default_bot,
                    ':css' => json_encode($chat_sessions),
                    ':cs' => $current_session
                )
            );

            //Log success
            logMessage("User successfully created");
        }

        public function update($whereColumn, $whereValue, $updateColumn, $updateValue) {
            global $pdo;

            //Update user
            $stmt = $pdo->prepare(
                "UPDATE `users` 
                SET `$updateColumn` = ':wv' 
                WHERE `$whereColumn` = ':uv'"
            );

            $stmt->execute(
                array(
                    ':wv' => $whereValue,
                    ':uv' => $updateValue
                )
            );

            //Log success
            logMessage("User successfully updated: ");
        }

        public function read($whereColumn, $whereValue) {
            global $pdo;
            return $pdo->query("SELECT * FROM `users` WHERE `$whereColumn`='$whereValue'")->fetchAll()[0];
        }

        public function default_bot() {
            
        }
    }