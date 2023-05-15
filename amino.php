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
