<?php
	include_once __DIR__ . '/../admin/IdentificatorBot.php';

	class BotApi
	{
		public function processMessage($data) {

			//region JSON ENCODE

			$arrData       = $this->decodeJson($data);

			$message       = $arrData['message']['text'];
			$chat_id       = $arrData['message']['chat']['id'];

			$chat_id_in    = isset($arrData['callback_query']['message']['chat']['id']);
			$callback_data = isset($arrData['callback_query']['data']);

			$fl_name       = $this->getFirstLastName($arrData);
			$username      = $this->getUsername($arrData);

			//endregion JSON ENCODE

			//region LINGUAGE SWITCH

			$language = IdentificatorBot::$linguage_default;
			$language_code = $this->getLiguage($arrData);
			if($language_code != $language) $language = 'en';
			require_once __DIR__ . "/../resources/Message." . $language . ".php";

			//endregion LINGUAGE SWITCH

			//region USER INPUT '/' COMMAND PROCESSING

			if ($this->isCommandBot($message, "/start")) {
				$str = $this->replaceText("{User}", $arrData, Message::$hi);
                $this->sendUser($str, $chat_id, true);
                $this->sendUser(Message::$start, $chat_id, true);
			}

			else if ($this->isCommandBot($message, "/help")) {
				$this->sendUser(Message::$help, $chat_id, true);
			}

			//endregion USER INPUT '/' COMMAND PROCESSING

			return  0;
		}

		//region SHARED METHODS

		private function decodeJson($data) {
			return json_decode(file_get_contents($data), true);
		}

		private function isCommandBot($message, $command): bool {
			return strtolower(trim($message)) == strtolower(trim($command));
		}

		private function replaceText($search, $arrData, $content){
			$firstname = $this->getFirstLastName($arrData);
			$username = $this->getUsername($arrData);
			if ($firstname == null) {
				if ($username != null) $outname = $username;
				else $outname = Message::$anon;
			}else $outname = $firstname;
			return str_replace($search, $outname, $content);
		}

		//endregion SHARED METHODS

		//region DETECT METHODS

		private function isAdmin($char_id): bool {
			return IdentificatorBot::$admin_id == $char_id;
		}

		private function isBot($data){
			$str = $data['message']['from']['is_bot'];
			return !empty($str) ? $str : 'false';
		}

		public function getFirstLastName($data) {
			$last_name = $data['message']['chat']['last_name'] ?? '';
			$str = $data['message']['chat']['first_name'] . " " . $last_name;
			return !empty($str) ? trim($str) : null;
		}

		private function getUsername($data) {
			$str = $data['message']['chat']['username'];
			return !empty($str) ? '@' . $str : null;
		}

		private function getLiguage($data) {
			$str = $data['message']['from']['language_code'];
			return !empty($str) ? $str : "ru";
		}

		//endregion DETECT METHODS

		//region CURL REQUEST METHODS

		public function sendUser($message, $chat_id, $html = false, $keyboard = null) {
			$data['text'] = $message;
			$data['chat_id'] = $chat_id;
			if ($html) $data['parse_mode'] = "html";
			if ($keyboard) $data['reply_markup'] = $keyboard;
			if (is_array($data))
				return $this->curlRequest($data, "sendMessage");
			return null;
		}

		public function curlRequest($data, $type){
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, IdentificatorBot::$bot_url . "/" . $type);
			curl_setopt($ch, CURLOPT_POST, count($data));
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_HEADER, false);
			$result = curl_exec($ch);
			curl_close($ch);
			return $result;
		}

		//endregion CURL REQUEST METHODS
	}