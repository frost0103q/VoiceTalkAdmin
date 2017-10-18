<?php
return [
		'ERROR_NO'=> ["error" => 0, 'message' => '성공되었습니다.'],
		'ERROR_NO_PARMA' => ["error" => 1,'message' => '유효한 파라미터가 아닙니다.'],
		'ERROR_DUPLICATE_ACCOUNT' => ["error" => 2,'message' => '중복된 계정입니다.'],
		'ERROR_NO_INFORMATION' => ["error" => 3,'message' => '정보가 존재하지 않습니다.'],
		'ERROR_NO_MATCH_PASSWORD' => ["error" => 4,'message' => '비밀번호가 맞지 않습니다.'],
        'ERROR_UPLOAD_FAILED' => ["error" => 5,'message' => '파일 업로드 실패되었습니다.'],
        'ERROR_NOT_ENOUGH_POINT' => ["error" => 6,'message' => '포인트가 부족합니다.'],
        'ERROR_BLOCKED_USER' => ["error" => 7,'message' => '알림차단된 유저입니다.'],
        'ERROR_REQUESTED_EXIT_USER' => ["error" => 8,'message' => '탈퇴신청한 유저입니다.'],
        'ERROR_NOT_VERIFIED_USER' => ["error" => 9,'message' => '인증되지 않은 유저입니다.'],
        'ERROR_FAILED_PURCHASE' => ["error" => 10,'message' => '구입 실패되었습니다.'],
        'ERROR_FORBIDDEN_WORD' => ["error" => 11,'message' => '금지어가 포함되어 있습니다.'],
        'ERROR_STOPEED_USER' => ["error" => 12,'message' => '강퇴처리된 유저입니다.'],
        'ERROR_BLOCK_USER' => ["error" => 13,'message' => '알림차단한 유저입니다.'],
        'ERROR_CALL_BLOCK_USER' => ["error" => 14,'message' => '전화신청 차단한 유저입니다.'],
        'ERROR_ADD_FRIEND_BLOCK_USER' => ["error" => 15,'message' => '친구추가 차단한 유저입니다.'],
        'ERROR_NOW_CONSULTING_USER' => ["error" => 16,'message' => '상담중인 유저입니다.'],

        'TEST_MODE_LOCAL' => 0,
        'TEST_MODE_GLOBAL' => 1,
        'TEST_MODE_DELIVERY' => 2,

        'testmode' => 2,

        'PUSH_MODE_FCM' => 0,
        'PUSH_MODE_XMPP' => 1,

        'pushmode' => 0,

        'DEFAULT_ADMIN_NO' => 1,

        'TALK_CONSULTING' =>0,
        'TALK_NORMAL' =>1,
        'TALK_VOICE_TYPE' => ["일반 목소리", "귀여운 목소리", "중후한 목소리","통통목소리","애교목소리"],

        'SMS_TEXT' =>"VoiceTalk인증코드는 %s 입니다.",

        'CONSULTING_PROFIT' => 0.3,                             // 30%를 회사가 가진다.
        'SEND_ENVELOP_PROFIT' => 0.3,                           // 30%를 회사가 가진다.
        'GIFT_PROFIT' => 0.2,                                   // 20%를 회사가 가진다.
        'SEND_POINT_PROFIT' => 0.2,                             // 20%를 회사가 가진다.

        'WITHDRAW_PROFIT' => 0.03,                             // 10%를 회사가 가진다.
        'WITHDRAW_TAX' => 0.1,                                  // 10%를 회사가 가진다.

        'POINT_ADD_RULE'=>[30, -20, 1, 12, 300, 1, 1, 1, 1],   // 9가지 종류만 가능
        'POINT_HISTORY_TYPE_ROLL_CHECK'=> 0,
        'POINT_HISTORY_TYPE_SEND_ENVELOPE'=> 1,
        'POINT_HISTORY_TYPE_PRESENT'=> 2,            // -이면 전송, +이면 받기
        'POINT_HISTORY_TYPE_CHAT'=> 3,               // -이면 대화차감, +이면 대화적립
        'POINT_HISTORY_TYPE_SIGN_UP'=> 4,
        'POINT_HISTORY_TYPE_INAPP'=> 5,
        'POINT_HISTORY_TYPE_FREE_CHARGE'=> 6,
        'POINT_HISTORY_TYPE_GIFTICON'=> 7,
        'POINT_HISTORY_TYPE_WITDDRAW'=> 8,

        'FREE_CHARGE_TYPE_ADSYNC'=> 0,
        'FREE_CHARGE_TYPE_NAS'=> 1,
        'FREE_CHARGE_TYPE_IGAWORKS'=> 2,

        'FREE_CHARGE_RATIO_ADSYNC'=> 1,
        'FREE_CHARGE_RATIO_NAS'=> 1,
        'FREE_CHARGE_RATIO_IGAWORKS'=> 1,

        'INAPP_ITEMS' => [
                            ["name" => 'inapp_point_1','price' => 700, 'value' => 700],
                            ["name" => 'inapp_point_2','price' => 2100, 'value' => 2100],
                            ["name" => 'inapp_point_3','price' => 3500, 'value' => 3500],
                            ["name" => 'inapp_point_4','price' => 7000, 'value' => 7000],
                            ["name" => 'inapp_point_5','price' => 21000, 'value' => 21000],
                            ["name" => 'inapp_point_6','price' => 35000, 'value' => 35000],
                            ["name" => 'inapp_point_7','price' => 70000, 'value' => 70000],
                        ],

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
        'NOTI_TYPE_ADMIN_REFUSE_IMAGE'  =>9,
        'NOTI_TYPE_SUCCESS_FREE_CHARGE'  =>10,
        'NOTI_TYPE_ADMIN_WARING'  =>11,
        'NOTI_TYPE_ADMIN_APP_STOP'  =>12,
        'NOTI_TYPE_ADMIN_IMAGE_AGREE'  =>13,
        'NOTI_TYPE_ADMIN_VOICE_REFUSE'  =>14,
        'NOTI_TYPE_ADMIN_VOICE_AGREE'  =>15,
        'NOTI_TYPE_ADMIN_APP_STOP_REMOVE'  =>16,
        'NOTI_TYPE_REQUEST_CONSULTING_REFUSE'  =>17,
        'NOTI_TYPE_ADMIN_WITHDRAW_COMPLETE'  =>18,
        'NOTI_TYPE_COMPLETE_CONSULTING'  =>19,
        'NOTI_TYPE_ALARM_ENABLE'  =>20,
        'NOTI_TYPE_ALARM_DISABLE'  =>21,

        'NOTI_TITLE_CONTENT' => [
                                    ["title"=>"일반채팅","content"=>"%s님으로부터 메시지가 도착했습니다."],                    // NOTI_TYPE_CHATMESSAGE
                                    ["title"=>"상담요청","content"=>"%s님으로부터 상담신청이 들어왔습니다."],                  // NOTI_TYPE_REQUEST_CONSULTING
                                    ["title"=>"상담요청승인","content"=>"%s님이 상담신청을 수락했습니다."],                    // NOTI_TYPE_REQUEST_ACCEPT_CONSULTING
                                    ["title"=>"선물조르기","content"=>"%s님으로부터 %uP 조르기가 들어왔습니다."],              // NOTI_TYPE_REQUEST_PRESENT
                                    ["title"=>"쪽지전송","content"=>"%s님으로부터 쪽지가 도착했습니다."],                      // NOTI_TYPE_SEND_ENVELOP
                                    ["title"=>"친구추가","content"=>"%s님이 당신을 친구추가했습니다."],                        // NOTI_TYPE_ADD_FRIEND
                                    ["title"=>"선물보내기","content"=>"%s님이 당신에게 %uP를 선물했습니다."],                  // NOTI_TYPE_SEND_PRESENT
                                    ["title"=>"결제문의","content"=>"%s님이 구글문의답변을 보냈습니다."],                      // NOTI_TYPE_CASH_QA
                                    ["title"=>"관리자","content"=>"%s님으로부터 쪽지가 도착했습니다."],                        // NOTI_TYPE_ADMIN_NORMAL_PUSH
                                    ["title"=>"이미지거절","content"=>"등록하신 이미지가 승인거절되었습니다."],                // NOTI_TYPE_ADMIN_REFUSE_IMAGE
                                    ["title"=>"관리자","content"=>"무료충전이 완료되었습니다."],                              // NOTI_TYPE_SUCCESS_FREE_CHARGE
                                    ["title"=>"관리자","content"=>"[경고%d회] 부적절한 활동에 대해 경고를 보냅니다."],         // NOTI_TYPE_ADMIN_WARING
                                    ["title"=>"관리자","content"=>"관리자에 의해 앱사용중지 되었습니다."],                     // NOTI_TYPE_ADMIN_APP_STOP
                                    ["title"=>"관리자","content"=>"등록하신 이미지가 승인되었습니다."],                       // NOTI_TYPE_ADMIN_IMAGE_AGREE
                                    ["title"=>"관리자","content"=>"등록하신 음성이 승인거절되었습니다."],                     // NOTI_TYPE_ADMIN_VOICE_REFUSE
                                    ["title"=>"관리자","content"=>"등록하신 음성이 승인되었습니다."],                         // NOTI_TYPE_ADMIN_VOICE_AGREE
                                    ["title"=>"관리자","content"=>"앱사용중지해제를 알려드립니다."],                          // NOTI_TYPE_ADMIN_APP_STOP_REMOVE
                                    ["title"=>"상담요청승인","content"=>"%s님이 상담신청을 거절했습니다."],                   // NOTI_TYPE_REQUEST_CONSULTING_REFUSE
                                    ["title"=>"관리자","content"=>"축하합니다!! 출금이 완료 되었습니다."],                   // NOTI_TYPE_ADMIN_WITHDRAW_COMPLETE
                                    ["title"=>"상담통화","content"=>"상담통화가 종료되었습니다."],                           // NOTI_TYPE_COMPLETE_CONSULTING
                                    ["title"=>"차단","content"=>"%s님이 당신을 차단해제했습니다."],                          // NOTI_TYPE_ALARM_ENABLE
                                    ["title"=>"차단","content"=>"%s님이 당신을 차단했습니다."],                             // NOTI_TYPE_ALARM_DISABLE
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

        'DISABLE'   => 0,
        'ENABLE'   => 1,

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

        'UNREAD' => 0,
        'READ' => 1,

		'ANDROID' => 0,
		'IOS'	=> 1,

		'DB_USER' => 'root',
		'DB_PW'	=> 'votalk!@12fca',
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

		//free charge type
		'FREE_CHARGE_ADSYNC'=> 0,
		'FREE_CHARGE_NAS'=> 1,
		'FREE_CHARGE_IGAWORKS'=> 2,

        //user status
        'USER_STATUS_ENABLE'=> 0,
        'USER_STATUS_AWAY'=> 1,
        'USER_STATUS_CONSULTING'=> 2,
];