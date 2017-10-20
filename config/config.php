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
        'google' =>[
            'google_iab_public_key' => 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAhwlgX25ZvA0Kfd8YsVKEXbmL9X05i7nU4yGicusFtISZl48OImWNiS5FRVlgH23jCiwRWcF0yYZfqI8xmrFoCrzOEiliV8LlJF8tZ4vO33zSybBQ91jn+lHn5hAWigKooq936nEolVibxKpX2r7TPBf/UBggfOW0MdlNSTYAa3D+elJN4JvwllBUBVY1h/Ed9+Z1cyx0mJLYaW1isFssfiZEnCgsf0ZZfz6liRsoiGl7GDzZ7Ay4H5RvYxCd2a/8iKY4+fag0Eulp0ETc7QYJGCmdh+h0pjXt1EC0jatWZ4skQIe225s5T4w5KAV7ToB1OPGmFACYN/L8vu2IUpn4QIDAQAB',
        ],
		'encSaltKey' => env('ENC_SALT_KEY','platformaes256ctrsecure'), // Only keys of sizes 16, 24 or 32 supported
        'giftN' => [
            'cid' => 'GFN0360',
            'ckey' => 'CK12437531',
        ],
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
        'chatLocalServerIp' => '192.168.0.121',
        'chatServerIp' => '175.126.38.49',
        'chatServerPort' => '5222',
        'defaultSender' => '01042807907',//'010-4280-7907',
        'chatAppPrefix' => 'voicetalk',
];
