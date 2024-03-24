<?php
	include_once __DIR__ . '/../IdentificatorBot.php';
	$url = IdentificatorBot::$telegram_url . IdentificatorBot::$bot_token . "/getWebHookInfo";
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$result = curl_exec($ch);
	curl_close($ch);
	echo "getWebHookInfo: " . $result;
