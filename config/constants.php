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
        'ERROR_BLOCKED_USER' => ["error" => 9,'message' => 'You are a blocked user.'],
        'ERROR_REQUESTED_EXIT_USER' => ["error" => 10,'message' => 'You are a requested exit user.'],
        'ERROR_NOT_VERIFIED_USER' => ["error" => 11,'message' => 'Not verified user.'],
        'ERROR_FAILED_PURCHASE' => ["error" => 12,'message' => 'Failed Purchase Product.'],
        'ERROR_ALARM' => ["error" => 13,'message' => 'Failed Send Alarm.'],
        'ERROR_FORBIDDEN_WORD' => ["error" => 14,'message' => 'Include Forbidden word.'],
        'ERROR_STOPEED_USER' => ["error" => 15,'message' => 'You are are stoped user.'],

        'TEST_MODE_LOCAL' => 0,
        'TEST_MODE_GLOBAL' => 1,
        'TEST_MODE_DELIVERY' => 2,

        'testmode' => 1,

        'PUSH_MODE_FCM' => 0,
        'PUSH_MODE_XMPP' => 1,

        'pushmode' => 0,

        'DEFAULT_ADMIN_NO' => 1,

        'USER_FIRST_POINT' => 300,
        'TALK_CONSULTING' =>0,
        'TALK_NORMAL' =>1,
        'TALK_VOICE_TYPE' => ["일반 목소리", "귀여운 목소리", "중후한 목소리","통통목소리","애교목소리"],

        'SMS_TEXT' =>"VoiceTalk인증코드는 %s 입니다.",

        'POINT_ADD_RULE'=>[30, -20, 0, 0, -200, 300, 0, 0, 0, 0],
        'POINT_HISTORY_TYPE_ROLL_CHECK'=> 0,
        'POINT_HISTORY_TYPE_SEND_ENVELOPE'=> 1,
        'POINT_HISTORY_TYPE_SEND_PRESENT'=> 2,
        'POINT_HISTORY_TYPE_RECEIVE_PRESENT'=> 3,
        'POINT_HISTORY_TYPE_CHAT'=> 4,
        'POINT_HISTORY_TYPE_SIGN_UP'=> 5,
        'POINT_HISTORY_TYPE_NORMAL'=> 6,
        'POINT_HISTORY_TYPE_INAPP'=> 7,
        'POINT_HISTORY_TYPE_FREE_CHARGE'=> 8,
        'POINT_HISTORY_TYPE_GIFTICON'=> 9,

        'FREE_CHARGE_TYPE_ADSYNC'=> 0,
        'FREE_CHARGE_TYPE_NAS'=> 1,
        'FREE_CHARGE_TYPE_IGAWORKS'=> 2,

        'FREE_CHARGE_RATIO_ADSYNC'=> 1,
        'FREE_CHARGE_RATIO_NAS'=> 1,
        'FREE_CHARGE_RATIO_IGAWORKS'=> 1,

        'INAPP_ITEMS' => [
                            ["name" => 'point_1','price' => 700, 'value' => 700],
                            ["name" => 'point_2','price' => 2100, 'value' => 2100],
                            ["name" => 'point_3','price' => 3500, 'value' => 3500],
                            ["name" => 'point_4','price' => 7000, 'value' => 7000],
                            ["name" => 'point_5','price' => 21000, 'value' => 21000],
                            ["name" => 'point_6','price' => 35000, 'value' => 35000],
                            ["name" => 'point_7','price' => 70000, 'value' => 70000],
                        ],

        'USER_RELATION_FLAG_BLOCK_FRIEND'=> 0,
        'USER_RELATION_FLAG_UNBLOCK_FRIEND'=> 1,
        'USER_RELATION_FLAG_ENABLE_ALARM'=> 2,
        'USER_RELATION_FLAG_DISABLE_ALARM'=> 3,

        'NOTI_TYPE_CHATMESSAGE'   => 0,
        'NOTI_TYPE_REQUEST_CONSULTING'   => 1,
        'NOTI_TYPE_REQUEST_ACCEPT_CONSULTING'   => 2,
        'NOTI_TYPE_REQUEST_PRESENT'   => 3,
        'NOTI_TYPE_SEND_ENVELOP'   => 4,
        'NOTI_TYPE_ADD_FRIEND'   => 5,
        'NOTI_TYPE_SEND_PRESENT'  =>6,
        'NOTI_TYPE_CASH_QA'  =>7,
        'NOTI_TYPE_ADMIN_NORMAL_PUSH'  =>8,
        'NOTI_TYPE_REFUSE_IMAGE'  =>9,
        'NOTI_TYPE_SUCCESS_FREE_CHARGE'  =>10,
        'NOTI_TYPE_ADMIN_WARING'  =>11,
        'NOTI_TYPE_ADMIN_APP_STOP'  =>12,

        'NOTI_TITLE_CONTENT' => [
                                            ["title"=>"일반채팅","content"=>"%s님으로부터 메시지가 도착했습니다."],            // NOTI_TYPE_CHATMESSAGE
                                            ["title"=>"상담요청","content"=>"%s님으로부터 상담신청이 들어왔습니다."],          // NOTI_TYPE_REQUEST_CONSULTING
                                            ["title"=>"상담요청승인","content"=>"%s님이 상담신청을 수락했습니다."],            // NOTI_TYPE_REQUEST_ACCEPT_CONSULTING
                                            ["title"=>"선물조르기","content"=>"%s님으로부터 %uP 조르기가 들어왔습니다."],      // NOTI_TYPE_REQUEST_PRESENT
                                            ["title"=>"쪽지전송","content"=>"%s님으로부터 쪽지가 도착했습니다."],              // NOTI_TYPE_SEND_ENVELOP
                                            ["title"=>"친구추가","content"=>"%s님이 당신을 친구추가했습니다."],                // NOTI_TYPE_ADD_FRIEND
                                            ["title"=>"선물보내기","content"=>"%s님이 당신에게 %uP를 선물했습니다."],          // NOTI_TYPE_SEND_PRESENT
                                            ["title"=>"결제문의","content"=>"%s님이 구글문의답변을 보냈습니다."],              // NOTI_TYPE_CASH_QA
                                            ["title"=>"관리자","content"=>"%s님으로부터 쪽지가 도착했습니다."],                // NOTI_TYPE_ADMIN_NORMAL_PUSH
                                            ["title"=>"이미지거절","content"=>"회원님이 등록하신 사진이 게시에 부적합하다 판단되어 연락드립니다. 변경 부탁 드립니다."],  // NOTI_TYPE_REFUSE_IMAGE
                                            ["title"=>"관리자","content"=>"무료충전이 완료되었습니다."],                       // NOTI_TYPE_SUCCESS_FREE_CHARGE
                                            ["title"=>"관리자","content"=>"관리자님이 경고메시지를 보냈습니다. 경고회수[%d]입니다."],               // NOTI_TYPE_ADMIN_WARING
                                            ["title"=>"관리자","content"=>"관리자님에 앱사용중지 되었습니다."],                 // NOTI_TYPE_ADMIN_APP_STOP
                                        ],
        'NOTI_GROUP_LIMIT'=>50,
        'INVALID_MODEL_NO'=>0,
        'CHECK_ROLL_POINT'   => 30,
        'POINT_PER_MIN'=>200,

        'NOTI_SEARCH_ALL'  =>-1,
        'NOTI_SEARCH_FRIEND' => -2,
        'NOTI_SEARCH_CHAT' => -3,

		'INVALID_EMAIL'   => 1,
		'INVALID_PASSWORD'   => 2,

		'SUCCESS'   => 'success',
		'FAIL'   => 'fail',

        'TRUE'   => 1,
        'FALSE'   => 0,

		'WAIT'   => 2,
		'AGREE'   => 1,
		'DISAGREE'   => 0,

        'ORDER_DISTANCE'   => 0,
        'ORDER_RANK'   => 1,
        'ORDER_DATE'   => 2,
		
		'TALK_POSSIBLE' => 0,
		'AWAY'	=> 1,
		'TALKING'	=> 2,

		'MALE'	=> 0,
		'FEMALE'	=> 1,

        'IMAGE'	=> 0,
        'VOICE'	=> 1,

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

        'NO_ADMIN' => 0,
		'TALK_ADMIN' => 1,
		'TALK_POLICE' => 2,

		'TOP_NOTICE' => 1,
		'FIXED_NOTICE' => 2,

		'SPECIAL_USER' => 1,
		'COMMON_USER' => 2,
		'TALK_USER' => 3,
		'ALL_USER' => 4,

		'NO_PASSBOOK_GUIDE' => 1,
		'LOST_PW' => 2,
		'DECLARE_RECEP' => 3,

		//talk type
		'TOPIC_TALK' => 0,
		'NOMAL_TALK' => 1,

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

		'GIFTICON_NOMAL'=>'1',
		'GIFTICON_CANCEL'=>'0',

        'MOBILE_SERVICE_PAGE'=> 0,
        'MOBILE_PRIVACY_PAGE'=> 1,
        'MOBILE_GPS_PAGE'=> 2,
        'MOBILE_GOOGLE_PAY_PAGE'=> 3,
        'MOBILE_USE_GUIDE_PAGE'=> 4,
        'MOBILE_NOTIFY_PAGE'=> 5,

		//user exit status
		'USER_NORMAL'=> 0,
		'USER_REQUEST_EXIT'=> 1,
		'USER_EXIT'=> 2,

        //user exit status
        'NO_ADMIN'=> 0,
        'ADMIN_NORMAL'=> 1,
        'ADMIN_POLICE'=> 2,
        'ADMIN_CUSTOMER_CENTER'=> 3,
];