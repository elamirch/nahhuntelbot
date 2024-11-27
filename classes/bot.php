<?php
class Bot {

    //Construct function
    public function create(string $name, ProviderConfig $providerConfig,
    string $instructions = '', string $description = '', bool $enabled = True,
    $summarizer = null, array $functions = [], array $corpusIds = []) {
        $url = "https://api.metisai.ir/api/v1/bots";
        $data = array(
            "name" => $name,
            "description" => $description,
            "instructions" => $instructions,
            "enabled" => true,
            "providerConfig" => $providerConfig,
            "summarizer" => $summarizer,
            "functions" => $functions,
            "corpusIds" => $corpusIds
        );
        $data = json_encode($data);

        global $MODEL_PROVIDER_API_KEY;
        $header = array(
            "Authorization: Bearer $MODEL_PROVIDER_API_KEY",
            'Content-Type: application/json',
        );
        return curl($url, $data, $header);
    }

    public function read(string $bot_id){
        global $MODEL_PROVIDER_API_KEY;
        //Setting header
        $header = array(
            "Authorization: Bearer $MODEL_PROVIDER_API_KEY",
            'Content-Type: application/json',
        );
        $url = "https://api.metisai.ir/api/v1/bots/$bot_id";
        return curl($url, header: $header);
    }

    // public function update(){

    // }

    public function delete(string $bot_id){
        global $MODEL_PROVIDER_API_KEY;
        //Setting header
        $header = array(
            "Authorization: Bearer $MODEL_PROVIDER_API_KEY",
            'Content-Type: application/json',
        );
        $url = "https://api.metisai.ir/api/v1/bots/$bot_id";
        return curl($url, header: $header, request: "DELETE");
    }
    
    //Create main bot if does not exist
    //Main bot is the bot users will chat with by default
    public function main_bot_init() {
        global $pdo;
        global $bot;
        global $gpt4o_mini;

        $result = $pdo->query("SELECT 1 FROM bots WHERE main = '1';")->fetchColumn();
        if(!$result) {
            $metis_response = json_decode($bot->create("Nahhuntel Main Bot", $gpt4o_mini));
            $main_bot_id = $metis_response->id;
            if ($main_bot_id == null) {
                die("Bot creation failed!\nMessage: " . $metis_response->message);
            }
            $stmt = $pdo->prepare("INSERT INTO bots (metis_bot_id, providerConfig, name, main) VALUES
            (:mbi, :pc, :nm, :mn)");
            $stmt->execute([':mbi' => $main_bot_id, ':pc' => json_encode($gpt4o_mini),
            ':nm' => "Nahhuntel Main Bot", ':mn' => '1']);
        } else {
            $main_bot_id = $pdo->query("SELECT metis_bot_id FROM bots WHERE
            main = '1';")->fetchAll()[0]['metis_bot_id'];
        }
        return $main_bot_id;
    }
}

class ProviderConfig {
    public array $provider;
    public array $args;

    //Construct function
    public function __construct(string $providerName, string $providerModel, array $args = [
        'temperature' => 1,
        'topP' => 1,
        'frequencyPenalty' => 0,
        'presencePenalty' => 0,
        'maxTokens' => 1000,
    ]) {
        $this->provider = [
            'name' => $providerName,
            'model' => $providerModel,
        ];
        $this->args = $args;
    }
}