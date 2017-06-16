/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50535
Source Host           : localhost:3306
Source Database       : voicetalk

Target Server Type    : MYSQL
Target Server Version : 50535
File Encoding         : 65001

Date: 2017-06-16 11:11:44
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for t_admin
-- ----------------------------
DROP TABLE IF EXISTS `t_admin`;
CREATE TABLE `t_admin` (
  `no` int(1) NOT NULL AUTO_INCREMENT COMMENT 'admin table 유일식별자, 이 표는 admin페지를 관리하는 관리자정보를 보관 및 관리, 로그인등에 리용됩니다.',
  `email` varchar(255) NOT NULL DEFAULT '' COMMENT 'Admin관리자 email(Login때 리용), 관리자 유일식별자입니다.',
  `password` varchar(255) NOT NULL DEFAULT '' COMMENT 'Admin관리자 password(Login때 리용)',
  `updated_at` datetime NOT NULL COMMENT '행 창조 시간',
  `created_at` datetime NOT NULL COMMENT '행 Update 시간',
  PRIMARY KEY (`no`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of t_admin
-- ----------------------------
INSERT INTO `t_admin` VALUES ('1', 'admin@gmail.com', '21232f297a57a5a743894a0e4a801fc3', '2017-05-16 19:15:01', '2017-05-16 19:15:03');

-- ----------------------------
-- Table structure for t_authcode
-- ----------------------------
DROP TABLE IF EXISTS `t_authcode`;
CREATE TABLE `t_authcode` (
  `no` int(11) NOT NULL AUTO_INCREMENT COMMENT 't_authocode표 유일식별자, 이표는 User가 상담회원이 되기 위해 전화번호sms인증을 할때 리용됩니다.',
  `auth_code` varchar(255) NOT NULL COMMENT '회원에게 보낸 인증코드',
  `requested_time` datetime NOT NULL COMMENT '회원의 인증요청시간',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `user_no` int(11) NOT NULL COMMENT 't_user표에 등록되있는 User No',
  `user_phone_number` varchar(30) NOT NULL COMMENT '인증코드를 전송할 유저의 전화번호',
  PRIMARY KEY (`no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of t_authcode
-- ----------------------------

-- ----------------------------
-- Table structure for t_chathistory
-- ----------------------------
DROP TABLE IF EXISTS `t_chathistory`;
CREATE TABLE `t_chathistory` (
  `no` int(11) NOT NULL AUTO_INCREMENT,
  `from_user_no` int(11) DEFAULT NULL,
  `to_user_no` int(11) DEFAULT NULL,
  `content` varchar(2048) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`no`)
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of t_chathistory
-- ----------------------------

-- ----------------------------
-- Table structure for t_consultingreview
-- ----------------------------
DROP TABLE IF EXISTS `t_consultingreview`;
CREATE TABLE `t_consultingreview` (
  `no` int(11) NOT NULL AUTO_INCREMENT COMMENT 't_talkreview 유일식별자, 이 표는 talk에 대한 review를 저장',
  `from_user_no` int(11) NOT NULL COMMENT 'talk 유일식별자',
  `to_user_no` int(11) unsigned zerofill NOT NULL COMMENT 'user 유일식별자',
  `mark` int(11) NOT NULL DEFAULT '0' COMMENT 'review 점수',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of t_consultingreview
-- ----------------------------

-- ----------------------------
-- Table structure for t_declare
-- ----------------------------
DROP TABLE IF EXISTS `t_declare`;
CREATE TABLE `t_declare` (
  `no` int(11) NOT NULL AUTO_INCREMENT COMMENT 't_declare 유일식별자, 이표는 상담중 User가 신고한 내용을 관리합니다.',
  `from_user_no` int(11) NOT NULL COMMENT '신고를 진행한 User No (t_user의 유일식별자)',
  `to_user_no` int(11) NOT NULL COMMENT '신고를 당한 User No (t_user의 유일식별자)',
  `content` varchar(1024) DEFAULT NULL COMMENT '신고 내용',
  `reason` varchar(1024) DEFAULT NULL COMMENT '신고 리유(신고와 관련된 부차적인 정보들을 json형식으로 전달합니다. 실례로 talk_no를 저장합니다.)',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of t_declare
-- ----------------------------

-- ----------------------------
-- Table structure for t_file
-- ----------------------------
DROP TABLE IF EXISTS `t_file`;
CREATE TABLE `t_file` (
  `no` int(11) NOT NULL AUTO_INCREMENT COMMENT 't_file 유일식별자, 이표는 서버에 업로드되는 파일들을 관리합니다.',
  `path` varchar(2048) DEFAULT NULL COMMENT 'exported url',
  `type` int(3) DEFAULT NULL COMMENT '0:Image 1:Voice',
  `checked` int(1) NOT NULL DEFAULT '0' COMMENT '0:unchecked 1:checked',
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`no`)
) ENGINE=InnoDB AUTO_INCREMENT=80 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of t_file
-- ----------------------------

-- ----------------------------
-- Table structure for t_notification
-- ----------------------------
DROP TABLE IF EXISTS `t_notification`;
CREATE TABLE `t_notification` (
  `no` int(11) NOT NULL AUTO_INCREMENT COMMENT 't_notification 유일식별자, 이 표는 User가 받게 되는 각종 알림들을 저장합니다.',
  `type` int(11) DEFAULT NULL COMMENT '알림형태(0: 일반, 1: 친구추가 알림)',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `title` varchar(255) DEFAULT '' COMMENT '알림제목',
  `content` varchar(2048) DEFAULT '' COMMENT '알림 내용',
  `from_user_no` int(11) DEFAULT NULL COMMENT '알림을 보낸 User No',
  `to_user_no` int(11) DEFAULT NULL COMMENT '알림을 받은 User No',
  `unread_count` int(11) DEFAULT '1' COMMENT '중복되면서 읽지 않은 알림의 개수',
  PRIMARY KEY (`no`)
) ENGINE=InnoDB AUTO_INCREMENT=80 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of t_notification
-- ----------------------------

-- ----------------------------
-- Table structure for t_pointhistory
-- ----------------------------
DROP TABLE IF EXISTS `t_pointhistory`;
CREATE TABLE `t_pointhistory` (
  `no` int(11) NOT NULL AUTO_INCREMENT COMMENT 't_pointhistory 유일식별자, 유저 Point 이동리력을 보관합니다.',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `type` int(2) DEFAULT '0' COMMENT '리력형태(0:출석체크, 1:쪽지전송, 2:선물보내기, 3: 선물받기, 4:상담Chat, 5:가입선물)',
  `point` int(11) DEFAULT '0' COMMENT '포인트량(추가일때 정수, 감소일때 부수)',
  `user_no` int(11) NOT NULL COMMENT '포인트 조작된 유저',
  PRIMARY KEY (`no`)
) ENGINE=InnoDB AUTO_INCREMENT=217 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of t_pointhistory
-- ----------------------------

-- ----------------------------
-- Table structure for t_talk
-- ----------------------------
DROP TABLE IF EXISTS `t_talk`;
CREATE TABLE `t_talk` (
  `no` int(11) NOT NULL AUTO_INCREMENT COMMENT 't_talk 유일식별자, 상담 및 일반Talk를 관리합니다.',
  `subject` varchar(255) DEFAULT NULL COMMENT 'Talk주제',
  `greeting` varchar(1024) DEFAULT NULL COMMENT 'Talk인사말',
  `voice_type` int(11) NOT NULL DEFAULT '0' COMMENT '목소리형태(0:일반 목소리, 1:"귀여운 목소리",2:"중후한 목소리",3:"통통목소리",4:"애교목소리")',
  `voice_no` int(11) DEFAULT '-1' COMMENT 'Talk에 등록된 Voice No(t_file의 유일식별자)',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `user_no` int(11) DEFAULT '-1' COMMENT 'talk를 등록한 User No (t_user의 유일식별자)',
  `img_no` int(11) DEFAULT '-1' COMMENT 'talk의 Image No',
  `type` int(1) DEFAULT '0' COMMENT '0:상담 1:일반',
  `nickname` varchar(255) DEFAULT NULL COMMENT '일반Talk의 Nickname(일반Talk쓰기때만 리용됩니다.)',
  `age` int(11) DEFAULT '0' COMMENT '일반Talk의 age (일반Talk쓰기때만 리용됩니다.)',
  `is_verified_voice` int(1) NOT NULL DEFAULT '0' COMMENT '승인된 음성판정 기발(1: 승인된 인증음성, 0: 아님)',
  PRIMARY KEY (`no`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of t_talk
-- ----------------------------
INSERT INTO `t_talk` VALUES ('32', '연애상담', '테스트일정 iiiiiii', '1', '79', '2017-06-15 08:46:35', '2017-06-15 08:46:35', '37', '-1', '0', null, '0', '0');

-- ----------------------------
-- Table structure for t_user
-- ----------------------------
DROP TABLE IF EXISTS `t_user`;
CREATE TABLE `t_user` (
  `no` int(11) NOT NULL AUTO_INCREMENT COMMENT 't_user 유일식별자, 이표는 앱사용자정보를 저장한다.',
  `email` varchar(255) NOT NULL DEFAULT '' COMMENT 'Not used field now.',
  `img_no` int(11) NOT NULL DEFAULT '-1' COMMENT '사용자 Profile Image',
  `status` int(2) NOT NULL DEFAULT '0' COMMENT '0: 상담가능, 1: 부재중, 2:상담중',
  `nickname` varchar(255) NOT NULL DEFAULT '' COMMENT '사용자 nickname',
  `sex` int(1) DEFAULT '0' COMMENT '사용자 성별 0: 남 , 1:여',
  `age` int(3) DEFAULT '20' COMMENT '사용자 나이',
  `subject` varchar(1024) DEFAULT '' COMMENT '사용자 주제',
  `latitude` double DEFAULT '0' COMMENT '사용자 위치 경도',
  `longitude` double DEFAULT '0' COMMENT '사용자 위치 위도',
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `location_no` int(11) DEFAULT '0' COMMENT '사용자 위치 지역 식별자',
  `verified` int(1) NOT NULL DEFAULT '0' COMMENT '0: unverified, 1:verified',
  `deposite_time` int(11) NOT NULL DEFAULT '1' COMMENT '사용자 사용가능한 상담시간',
  `point` int(11) NOT NULL DEFAULT '0' COMMENT '사용자 Point',
  `phone_number` varchar(20) DEFAULT NULL COMMENT '사용자 전화번호',
  `device_type` int(1) NOT NULL DEFAULT '0' COMMENT '0:android 1:ios',
  `device_serial` varchar(255) DEFAULT NULL COMMENT '사용자 장치 식별자',
  `device_token` varchar(1024) DEFAULT NULL COMMENT '사용자 Push Token',
  `enable_alarm_call_request` int(1) NOT NULL DEFAULT '1' COMMENT '사용자 상담요청알림 가능 기발(0: disable, 1: enable)',
  `enable_alarm_add_friend` int(1) NOT NULL DEFAULT '1' COMMENT '사용자 친구추가알림기발 (0:disable, 1: enable)',
  `check_roll_time` datetime DEFAULT NULL COMMENT '사용자 출석체크시간',
  `enable_alarm` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`no`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of t_user
-- ----------------------------

-- ----------------------------
-- Table structure for t_user_relation
-- ----------------------------
DROP TABLE IF EXISTS `t_user_relation`;
CREATE TABLE `t_user_relation` (
  `no` int(11) NOT NULL AUTO_INCREMENT COMMENT 't_user_relation 유일식별자, 이 표는 사용자들사이의 기발을 설정합니다.',
  `user_no` int(11) NOT NULL COMMENT '사용자 유일식별자',
  `relation_user_no` int(11) NOT NULL COMMENT '상대 사용자 유일식별자',
  `is_block_friend` int(1) NOT NULL DEFAULT '0' COMMENT '친구 block 기발',
  `is_alarm` int(1) NOT NULL DEFAULT '1' COMMENT '알림 허용 기발',
  `is_friend` int(1) NOT NULL DEFAULT '0' COMMENT '친구인지 아닌지 판정하는 기발',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`no`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of t_user_relation
-- ----------------------------

-- ----------------------------
-- Table structure for t_withdraw
-- ----------------------------
DROP TABLE IF EXISTS `t_withdraw`;
CREATE TABLE `t_withdraw` (
  `no` int(11) NOT NULL AUTO_INCREMENT COMMENT 't_withdraw의 유일식별자, 이표는 출금요청정보를 저장관리합니다.',
  `user_no` int(11) NOT NULL COMMENT '요청사용자 유일식별자',
  `money` int(11) NOT NULL DEFAULT '53' COMMENT '신청요청한 액수',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `bank_name` varchar(255) NOT NULL COMMENT '출금은행이름',
  `account_number` varchar(30) DEFAULT NULL COMMENT '출금요청한 유저의 전화번호',
  `account_name` varchar(255) DEFAULT NULL COMMENT '출금요청한 유저의 이름',
  `account_realname` varchar(255) DEFAULT NULL COMMENT '출금요청한 유저의 실명',
  `account_birth` varchar(255) DEFAULT NULL COMMENT '출금요청한 유저 생일',
  `is_verified` int(1) NOT NULL DEFAULT '0' COMMENT '출금인증기발 (0:인증안됨, 1:인증됨)',
  `status` int(1) NOT NULL DEFAULT '0' COMMENT '출금상태(0:대기, 1: 완료, 2:오류)',
  PRIMARY KEY (`no`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of t_withdraw
-- ----------------------------
