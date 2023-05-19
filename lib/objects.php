<?php
	//
	// BaseAnswer
	//
	// This is the class containing the basic data that Amino gives away.
	//
	class BaseAnswer
	{
		public $api_statuscode;
		public $api_duration;
		public $api_timestamp;
		public $api_message;

		public function __construct($data)
		{
			$this->api_statuscode = array_key_exists('api:statuscode', $data) ? $data['api:statuscode'] : null;
			$this->api_duration = array_key_exists('api:duration', $data) ? $data['api:duration'] : null;
			$this->api_timestamp = array_key_exists('api:timestamp', $data) ? $data['api:timestamp'] : null;
			$this->api_message = array_key_exists('api:message', $data) ? $data['api:message'] : null;
		}
	}

	//
	// UserProfile
	//
	// This is a class inherited from BaseAnswer containing user information.
	//
	class UserProfile extends BaseAnswer
	{
		public $auid;
		public $account;
		public $secret;
		public $sid;
		public $userProfile;

		public function __construct($data)
		{
			parent::__construct($data);

			$this->auid = array_key_exists('auid', $data) ? $data['auid'] : null;
			$this->account = array_key_exists('account', $data) ? $data['account'] : null;
			$this->secret = array_key_exists('secret', $data) ? $data['secret'] : null;
			$this->sid = array_key_exists('sid', $data) ? $data['sid'] : null;
			$this->userProfile = array_key_exists('userProfile', $data) ? $data['userProfile'] : null;
		}
	}
	
	//
	// Community
	//
	// This is a class, inherited from BaseAnswer, containing
	// information about one and only one community.
	//
	class Community extends BaseAnswer
	{
		public $userAddedTopicList;
		public $agent;
		public $listedStatus;
		public $probationStatus;
		public $themePack;
		public $membersCount;
		public $primaryLanguage;
		public $communityHeat;
		public $strategyInfo;
		public $tagline;
		public $joinType;
		public $status;
		public $launchPage;
		public $modifiedTime;
		public $ndcId;
		public $activeInfo;
		public $link;
		public $icon;
		public $updatedTime;
		public $endpoint;
		public $name;
		public $templateId;
		public $createdTime;
		public $promotionalMediaList;

		public function __construct(array $data)
		{
			parent::__construct($data);
			
			$this->userAddedTopicList = array_key_exists('userAddedTopicList', $data) ? $data['userAddedTopicList'] : null;
			$this->agent = array_key_exists('agent', $data) ? $data['agent'] : null;
			$this->listedStatus = array_key_exists('listedStatus', $data) ? $data['listedStatus'] : null;
			$this->probationStatus = array_key_exists('probationStatus', $data) ? $data['probationStatus'] : null;
			$this->themePack = array_key_exists('themePack', $data) ? $data['themePack'] : null;
			$this->membersCount = array_key_exists('membersCount', $data) ? $data['membersCount'] : null;
			$this->primaryLanguage = array_key_exists('primaryLanguage', $data) ? $data['primaryLanguage'] : null;
			$this->communityHeat = array_key_exists('communityHeat', $data) ? $data['communityHeat'] : null;
			$this->strategyInfo = array_key_exists('strategyInfo', $data) ? $data['strategyInfo'] : null;
			$this->tagline = array_key_exists('tagline', $data) ? $data['tagline'] : null;
			$this->joinType = array_key_exists('joinType', $data) ? $data['joinType'] : null;
			$this->status = array_key_exists('status', $data) ? $data['status'] : null;
			$this->launchPage = array_key_exists('launchPage', $data) ? $data['launchPage'] : null;
			$this->modifiedTime = array_key_exists('modifiedTime', $data) ? $data['modifiedTime'] : null;
			$this->ndcId = array_key_exists('ndcId', $data) ? $data['ndcId'] : null;
			$this->activeInfo = array_key_exists('activeInfo', $data) ? $data['activeInfo'] : null;
			$this->link = array_key_exists('link', $data) ? $data['link'] : null;
			$this->icon = array_key_exists('icon', $data) ? $data['icon'] : null;
			$this->updatedTime = array_key_exists('updatedTime', $data) ? $data['updatedTime'] : null;
			$this->endpoint = array_key_exists('endpoint', $data) ? $data['endpoint'] : null;
			$this->name = array_key_exists('name', $data) ? $data['name'] : null;
			$this->templateId = array_key_exists('templateId', $data) ? $data['templateId'] : null;
			$this->createdTime = array_key_exists('createdTime', $data) ? $data['createdTime'] : null;
			$this->promotionalMediaList = array_key_exists('promotionalMediaList', $data) ? $data['promotionalMediaList'] : null;
		}
	}
	
	//
	// CommunityList
	// 
	// This is a class, inherited from BaseAnswer, containing
	// information about several communities.
	// It is essentially a trivial array.
	//
	class CommunityList extends BaseAnswer
	{
		public $communities;

		public function __construct(array $data)
		{
			parent::__construct($data);
			
			$this->communities = [];
			foreach ($data as $communityData) {
				$this->communities[] = new Community($communityData);
			}
		}
	}
?>