<?php
	require 'lib/objects.php';
	
	class Amino
	{
		///////////////////
			protected $email = "";
			protected $password = "";
			protected $sid = "";
			protected $deviceId = "";
		/////////////////////
		
		/**
		  * Creating an object of the Amino class.
		  *
		  * @param string $email Email
		  * @param string $password Password
		  */
		public function __construct($email, $password)
		{
			$this->email = $email;
			$this->password = $password;
			$this->sid = "";
			$this->deviceId = $this->gen_deviceId();
		}

		/**
		  * Creating headers to work properly with Amino.
		  *
		  * @param string $device_id Device ID
		  * @param string $sid Session ID
		  * @param string[] $data POST data
		  * @param string $lang Language like "en-US"
		  * @return string[] Ready to use with cURL headers.
		  */
		public function bakeHeaders($device_id = null, $sid = null, $data = null, $lang = "en-US")
		{
			$headers = array(
				"Accept-Language: {$lang}",
				"Content-Type: application/json; charset=utf-8",
				"User-Agent: Apple iPhone12,1 iOS v15.5 Main/3.12.2",
				"Host: service.narvii.com",
				"Accept-Encoding: gzip",
				"Connection: Upgrade"
			);

			if ($device_id) {
				$headers[] = "NDCDEVICEID: ". $device_id;
			}
			if ($sid) {
				$headers[] = "NDCAUTH: ".$sid;
			}
			if ($data) {
				$headers[] = "Content-Length: ".strval(strlen($data));
				$headers[] = "NDC-MSG-SIG: " . $this->signature($data);
			}

			return $headers;
		}
		
		/**
		  * Creating signatures for POST data.
		  *
		  * @param string[] $data POST data
		  * @return string Ready to use ndc-msg-sig.
		  */
		function signature($data) {
			$PREFIX = hex2bin("19");
			$SIG_KEY = hex2bin("DFA5ED192DDA6E88A12FE12130DC6206B1251E44");
			$data = is_string($data) ? utf8_encode($data) : $data;
			$hashed_data = hash_hmac('sha1', $data, $SIG_KEY, true);
			return base64_encode($PREFIX . $hashed_data);
		}

		/**
		  * Creating headers to work properly with Amino.
		  *
		  * @param string $data POST data
		  * @return string Ready to use device_id.
		  */
		function gen_deviceId($data = null) {
			$PREFIX = hex2bin("19");
			$DEVICE_KEY = hex2bin("E7309ECC0953C6FA60005B2765F99DBBC965C8E9");
			$identifier = $PREFIX . ($data ?? random_bytes(20));
			$mac = hash_hmac('sha1', $identifier, $DEVICE_KEY, true);
			return strtoupper(bin2hex($identifier) . bin2hex($mac));
		}

		/**
		  * Login to the account.
		  *
		  * @return UserProfile The profile you have entered.
		  */
		public function auth()
		{
			$request = $this->request(
				"g/s/auth/login",
				"POST",
				[
					"email" => $this->email,
					"secret" => "0 ".$this->password,
					"deviceID" => $this->deviceId,
					"clientType" => 100,
					"action" => "normal",
					"v" => 2,
					"timestamp" => round(time()*1000)
				]
			);
			$this->sid = $request[0]["sid"];
			return new UserProfile($request[0]);
		}
		
		/**
		  * Obtaining communities of which you are already a member.
		  *
		  * @param int $start Which community to start with (0 - start).
		  * @param int $size How many communities to gather (maximum 100)
		  * @return CommunityList A list of the communities you belong to
		  */
		public function myCommunities($start = 0, $size = 25)
		{
			$request = $this->request(
				"g/s/community/joined?v=1&start={$start}&size={$size}",
				"GET"
			);
			return new CommunityList($request[0]);
		}

		/**
		  * The main function for creating queries to Amino.
		  *
		  * @param string $url The path to the request.
		  * @param int $method The method of the request.
		  * @param string[] $params Query parameters.
		  * @return array Status code and data returned by the server.
		  */
		public function request($url, $method = "POST", $params = null){
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, "https://service.aminoapps.com/api/v1/".$url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			if ($method == "POST") {
				curl_setopt($ch, CURLOPT_POST, true);
				curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
				$headers = $this->bakeHeaders($this->deviceId, $this->sid, json_encode($params));
			}
			else $headers = $this->bakeHeaders($this->deviceId, $this->sid);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');

			curl_setopt($ch, CURLOPT_VERBOSE, true);

			$out = curl_exec($ch);
			$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			if ($out === false)
				var_dump(curl_error($ch) . "\n");

			curl_close($ch);
			$base = json_decode($out, true, JSON_INVALID_UTF8_IGNORE);
			return [$base, $http_code];
		}
	}
?>
