<?php

	class Message
	{
		static string $hi = "Привет, <b>{User}</b>!";

		static string $help =
			  "\xE2\x9C\x85 <b>Список команд бота</b>:\n\n" .
		      "\xE2\x97\xBE /start - стартовое приветствие.\n" .
		      "\xE2\x97\xBE /help - список команд.\n";

		static string $start =
			  "\xE2\x9C\x85 <b>Правила использования бота</b>:\n\n" .
		      "\xE2\x97\xBE /start - стартовое приветствие.\n" .
		      "\xE2\x97\xBE /help - список команд.\n";
	}