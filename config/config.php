<?php
return [
		'name' => env('APP_NAME','XDevelopment'),
        'uploadDirectory' => 'uploads',
        'publicDirectory' => 'public',
		'parse' => [
				'parse_application_id' => env('PARSE_APPLICATION_ID','CS34rkn2JTcBR2GXaYjtN22WovoETZP6lBa7o9mh'), //X Development = CS34rkn2JTcBR2GXaYjtN22WovoETZP6lBa7o9mh
				'parse_rest_api_key' => env('PARSE_REST_API_KEY','No2BFRuV8DrfMRXoxpbRft7jE3GOpu0HOfjvBWNZ'), //X Development = No2BFRuV8DrfMRXoxpbRft7jE3GOpu0HOfjvBWNZ
				'parse_master_key' => env('PARSE_MASTER_KEY','VsyYRFVoGJxop8TDiJKD5TP672PloHW1gzflmW9y'), //X Development = VsyYRFVoGJxop8TDiJKD5TP672PloHW1gzflmW9y
		],
		'encSaltKey' => env('ENC_SALT_KEY','platformaes256ctrsecure'), // Only keys of sizes 16, 24 or 32 supported
		'recordPerPage' => 6,
		'itemsPerPage' => [
				'default' => 10,
				'license' => 12,
				'notifications' => 12,
				'explore' => 12,
				'forumCategory' => 12,
				'searchpeople' => 12,
				'searchhashtag' => 1000,
				'taggedmedia' => 12,
				'threadresponse' => 10
		],
		'toReportProblemEmail' => 'tyler@plus11.com',
        'chatServerIp' => '192.168.0.119',
        'chatServerPort' => '5222',
        'chatAppPrefix' => 'voicetalk',
];
