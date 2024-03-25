<?php
    include_once __DIR__ . '/bot_api/BotApi.php';
    $bot = new BotApi();
    $bot->processMessage('php://input');
