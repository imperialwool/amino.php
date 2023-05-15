<?php
	class Amino
	{
		///////////////////
			protected $email = "";
			protected $password = "";
			protected $socket = "";
			protected $sid = "";
		/////////////////////
			protected $thread_id;
			protected $community_id;
			protected $message_id;
		/////////////////////
			protected $PREFIX = hex2bin("19");
			protected $SIG_KEY = hex2bin("DFA5ED192DDA6E88A12FE12130DC6206B1251E44");
			protected $DEVICE_KEY = hex2bin("E7309ECC0953C6FA60005B2765F99DBBC965C8E9");

		public function __construct($email, $password){
			$this->email = $email;
			$this->password = $password;
		}

		public function auth(){
			$request = $this->request("g/s/auth/login", ["email"=>$this->email,"secret"=>"0 ".$this->password,"deviceID"=>"015051B67B8D59D0A86E0F4A78F47367B749357048DD5F23DF275F05016B74605AAB0D7A6127287D9C","clientType"=>100,"action"=>"normal","timestamp"=>(time()*100)]);
			$this.$sid = $request["sid"]
			return $request
		}

		public function generateHeaders($device_id = null, $sid = null)
		{
			$headers = [
				"Accept-Language" => "en-US",
				"Content-Type" => "application/json; charset=utf-8",
				"User-Agent" => "Apple iPhone12,1 iOS v15.5 Main/3.12.2",
				"Host" => "service.narvii.com",
				"Accept-Encoding" => "gzip",
				"Connection" => "Upgrade"
			];

			if ($device_id) {
				$headers["NDCDEVICEID"] = $device_id;
			}
			if ($sid) {
				$headers["NDCAUTH"] = $sid;
			}
		}
		
		function signature($data) {
			global $PREFIX, $SIG_KEY;
			$data = is_string($data) ? utf8_encode($data) : $data;
			$hashed_data = hash_hmac('sha1', $data, $SIG_KEY, true);
			return base64_encode($PREFIX . $hashed_data);
		}

		function gen_deviceId($data = null) {
			global $PREFIX, $DEVICE_KEY;
			$identifier = $PREFIX . ($data ?? random_bytes(20));
			$mac = hash_hmac('sha1', $identifier, $DEVICE_KEY, true);
			return strtoupper(bin2hex($identifier) . bin2hex($mac));
		}

		public function request($method, $params = array()){
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, "https://service.aminoapps.com/api/v1/".$method);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
			$out = curl_exec($ch);
			curl_close($ch);
			$base = json_decode($out,true);
			return $base;
		}
	}
?>
