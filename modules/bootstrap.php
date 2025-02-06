<?php
    echo "Running\n";
    
    //Reading variables from .env file
    $env = parse_ini_file('.env');

    $BOT_TOKEN = $env['BOT_TOKEN'];
    $MODEL_PROVIDER_API_KEY = $env['MODEL_PROVIDER_API_KEY'];
    
    $USE_PROXY = $env['USE_PROXY'];
    
    $DB_HOST = $env['DB_HOST'];
    $DB_PORT = $env['DB_PORT'];
    $DB_NAME = $env['DB_NAME'];
    $DB_USER = $env['DB_USER'];
    $DB_PASS = $env['DB_PASS'];
    
    $DISPLAY_ERRORS = $env['DISPLAY_ERRORS'];

    $ADMIN_CHAT_ID = $env['ADMIN_CHAT_ID'];
    $ADMIN_USER_NAME = $env['ADMIN_USER_NAME'];
    $ASSOCIATED_CHANNEL = $env['ASSOCIATED_CHANNEL'];

    //Setting wether to display errors
    if($DISPLAY_ERRORS) {
        ini_set('display_errors', "on");
        error_reporting(error_level: E_ALL);
    }

    include_once("./modules/variables.php");
    include_once("./modules/curl.php");
    include_once("./modules/db.php");
    include_once("./classes/bot.php");
    include_once("./classes/chat.php");
    include_once("./classes/telegram.php");
    include_once("./classes/user.php");
    include_once("./modules/buttons.php");
    
    //Create the needed objects
    $bot = new Bot;
    $chat = new Chat;
    $user = new User;
    $telegram = new Telegram;

    //Create providerConfigs
    //OpenAI
    $gpt4 = new ProviderConfig("openai_chat_completion", "gpt-4");
    $gpt_4_turbo = new ProviderConfig("openai_chat_completion", "gpt-4-turbo");
    $gpt4o = new ProviderConfig("openai_chat_completion", "gpt-4o");
    $gpt4o_mini = new ProviderConfig("openai_chat_completion", "gpt-4o-mini");

    //Anthropic
    $clause3_opus = new ProviderConfig("anthropic", "claude-3-opus");
    $clause3_sonnet = new ProviderConfig("anthropic", "claude-3-sonnet");
    $clause3_5_sonnet = new ProviderConfig("anthropic", "claude-3-5-sonnet");
    $clause3_haiku = new ProviderConfig("anthropic", "claude-3-haiku");

    //Mistral
    $mistral7b = new ProviderConfig("mistral", "mixtral-8x7b-instruct-v0.1");

    //Goolge
    $gemini_1_5_pro = new ProviderConfig("google", "gemini-1.5-pro");
    $gemini_1_5_flash = new ProviderConfig("google", "gemini-1.5-flash");
    $gemini_1_5_flash_8b = new ProviderConfig("google", "gemini-1.5-flash-8b");
    $gemini_1_pro = new ProviderConfig("google", "gemini-1.0-pro");
    $gemini_1_5_pro = new ProviderConfig("google", "gemini-1.5-pro");

    //Grok
    $grok_2 = new ProviderConfig("grok", "grok-2");

    //Deepseek
    $deepseek_chat = new ProviderConfig("deepseek", "deepseek-chat");
    $deepseek_reasoner = new ProviderConfig("deepseek", "deepseek-reasoner");