# Nahhuntelbot

A lightweight Telegram AI chatbot using Metis AI as the backend. Supports multiple conversations, with plans to integrate additional AI models in the future.

## Features
- No external libraries required
- Supports multiple simultaneous conversations
- Written in PHP
- Future roadmap includes support for multiple AI models

## Installation
### 1. Clone the Repository
```sh
git clone https://github.com/elamirch/nahhuntelbot.git
```

### 2. Configure Your Telegram Bot
- Obtain a bot token from [BotFather](https://t.me/botfather).
- Set up your Metis AI API key.
- Create a Telegram channel and add the bot as an administrator.
- Configure the `.env` file with the following details:
  - Telegram Bot API key
  - Metis API key
  - Your Telegram username
  - Channel ID
  - MySQL/MariaDB credentials

### 3. Set Up the Database
- Install MySQL or MariaDB if not already installed.
- Create a new database and user, then grant necessary permissions.
- Add the database credentials to the `.env` file.

### 4. Deploy the Bot
- Set up a web server (Apache, Nginx, etc.) and move the bot files to the root directory:
```sh
mv nahhuntelbot/ /path/to/webserver/root
```
- Configure the bot's webhook by running:
```sh
curl https://api.telegram.org/bot{BOT_TOKEN}/setWebhook?url=https://yourdomain.com/nahhuntelbot/bot.php
```
> **Note:** Replace `{BOT_TOKEN}` with your actual bot token and `yourdomain.com` with your web server's domain.

## Roadmap
- [ ] Expand AI model support
- [ ] Implement message logging
- [ ] Add database import/export functionality
- [ ] Improve scalability and performance optimizations

## License
This project is licensed under the **Mozilla Public License 2.0 (MPL 2.0)**.

