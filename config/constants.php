<?php
return [
		'ERROR_NO'=> ["error" => 0, 'message' => 'Success'],
		'ERROR_NO_PARMA' => ["error" => 1,'message' => 'No Parameters'],
		'ERROR_DUPLICATE_ACCOUNT' => ["error" => 2,'message' => 'Duplicate Account'],
		'ERROR_NO_INFORMATION' => ["error" => 3,'message' => 'No Match Information'],
		'ERROR_NO_MATCH_PASSWORD' => ["error" => 4,'message' => 'No Match Password'],
        'ERROR_UPLOAD_FAILED' => ["error" => 5,'message' => 'Upload Failed'],
        'ERROR_ALREADY_ADDED' => ["error" => 6,'message' => 'Already Added'],
        'ERROR_NOT_ENOUGH_POINT' => ["error" => 7,'message' => 'Not Enough Point'],
        'ERROR_NOT_ENABLE_SELF_REVIEW' => ["error" => 8,'message' => 'Cannot review self'],

        'TALK_CONSULTING' =>0,
        'TALK_NORMAL' =>1,
        'TALK_VOICE_TYPE' => ["일반 목소리", "귀여운 목소리", "중후한 목소리","통통목소리","애교목소리"],

        'POINT_ADD_RULE'=>[30, -20, 0, 0, -200, 300, 0],
        'POINT_HISTORY_TYPE_ROLL_CHECK'=> 0,
        'POINT_HISTORY_TYPE_SEND_ENVELOPE'=> 1,
        'POINT_HISTORY_TYPE_SEND_PRESENT'=> 2,
        'POINT_HISTORY_TYPE_RECEIVE_PRESENT'=> 3,
        'POINT_HISTORY_TYPE_CHAT'=> 4,
        'POINT_HISTORY_TYPE_SIGN_UP'=> 5,
        'POINT_HISTORY_TYPE_NORMAL'=> 6,

        'NOTI_TITLE_SEND_ENVELOPE'=> "쪽지전송",
        'NOTI_TYPE_SEND_ENVELOPE'=>0,
        'NOTI_GROUP_LIMIT'=>50,

        'USER_RELATION_FLAG_BLOCK_FRIEND'=> 0,
        'USER_RELATION_FLAG_UNBLOCK_FRIEND'=> 1,
        'USER_RELATION_FLAG_ENABLE_ALARM'=> 2,
        'USER_RELATION_FLAG_DISABLE_ALARM'=> 3,

        'CHATMESSAGE_TYPE_NORMAL'   => 0,
        'CHATMESSAGE_TYPE_REQUEST_CONSULTING'   => 1,
        'CHATMESSAGE_REQUEST_ACCEPT_CONSULTING'   => 2,
        'CHATMESSAGE_TYPE_REQUEST_PRESENT'   => 3,
        'CHATMESSAGE_TYPE_SEND_ENVELOP'   => 4,
        'CHATMESSAGE_TYPE_ADD_FRIEND'   => 5,
        'CHATMESSAGE_TYPE_SEND_PRESENT'  =>6,

        'CHECK_ROLL_POINT'   => 30,

		'INVALID_EMAIL'   => 1,
		'INVALID_PASSWORD'   => 2,

		'SUCCESS'   => 'success',
		'FAIL'   => 'fail',

		'WAIT'   => 2,
		'AGREE'   => 1,
		'DISAGREE'   => 0,
		
		'TALK_POSSIBLE' => 0,
		'AWAY'	=> 1,
		'TALKING'	=> 2,

		'MALE'	=> 0,
		'FEMALE'	=> 1,

		'UNVERIFIED' => 0,
		'VERIFIED' => 1,

		'ANDROID' => 0,
		'IOS'	=> 1,

		'DB_USER' => 'root',
		'DB_PW'	=> 'root',
		'DB_NAME' => 'voicetalk',
		'DB_HOST'	=> 'localhost',

		'SAVE_FLAG_ADD' => 1,
		'SAVE_FLAG_EDIT' => 2,

		'PUSH_SEND_MAIN' => 1,
		'PUSH_SEND_NOTICE' => 2,
		'PUSH_SEND_EVENT' => 3,

		'TALK_ADMIN' => 1,
		'TALK_POLICE' => 2,

		'SPECIAL_USER' => 1,
		'COMMON_USER' => 2,
		'TALK_USER' => 3,
		'ALL_USER' => 4,

		'NO_PASSBOOK_GUIDE' => 1,
		'LOST_PW' => 2,
		'DECLARE_RECEP' => 3,

		//warning sentences type
		'SALE_SCREEN'=>'1',
		'SALE_SEX'=>'2',
		'HONGBO_TARGET'=>'3',
		'ABUSIBE'=>'4',
		'OUTER_PICTURE'=>'5',
		'OTHER_USER_CALUMNY'=>'6',
		'POINT_LEAD'=>'7',
		'PHOTO_STEAL'=>'8',
		'UNLAWFULNESS_TRADE'=>'9',
		'LIE_THING'=>'10',
		'LIE_DECLARE'=>'11',
		'USER_REPRESENT'=>'12',
		'AD_OTHER_APP'=>'13',
		'OTHER'=>'14',
		'WRONG_WORD'=>'15',
		'SEX_BANTER'=>'16',
		'THREAT'=>'17',

		//Withdraw
		'CASH_FINISH'=>'1',
		'CASH_STOP'=>'2',

		'WITHDRAW_WAIT'=>'0',
		'WITHDRAW_FINISH'=>'1',
		'WITHDRAW_ERROR'=>'2',

		'IS_VERIFIED'=>'1',
		'NOT_VERIFIED'=>'0',
];