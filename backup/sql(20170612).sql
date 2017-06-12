/*
SQLyog Ultimate v11.3 (64 bit)
MySQL - 5.5.35 : Database - voicetalk
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`voicetalk` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `voicetalk`;

/*Table structure for table `t_admin` */

DROP TABLE IF EXISTS `t_admin`;

CREATE TABLE `t_admin` (
  `no` int(1) NOT NULL AUTO_INCREMENT COMMENT 'admin table 유일식별자, 이 표는 admin페지를 관리하는 관리자정보를 보관 및 관리, 로그인등에 리용됩니다.',
  `email` varchar(255) NOT NULL DEFAULT '' COMMENT 'Admin관리자 email(Login때 리용), 관리자 유일식별자입니다.',
  `password` varchar(255) NOT NULL DEFAULT '' COMMENT 'Admin관리자 password(Login때 리용)',
  `updated_at` datetime NOT NULL COMMENT '행 창조 시간',
  `created_at` datetime NOT NULL COMMENT '행 Update 시간',
  PRIMARY KEY (`no`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `t_admin` */

insert  into `t_admin`(`no`,`email`,`password`,`updated_at`,`created_at`) values (1,'admin@gmail.com','21232f297a57a5a743894a0e4a801fc3','2017-05-16 19:15:01','2017-05-16 19:15:03');

/*Table structure for table `t_authcode` */

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

/*Data for the table `t_authcode` */

/*Table structure for table `t_declare` */

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

/*Data for the table `t_declare` */

/*Table structure for table `t_file` */

DROP TABLE IF EXISTS `t_file`;

CREATE TABLE `t_file` (
  `no` int(11) NOT NULL AUTO_INCREMENT COMMENT 't_file 유일식별자, 이표는 서버에 업로드되는 파일들을 관리합니다.',
  `path` varchar(2048) DEFAULT NULL COMMENT 'exported url',
  `type` int(3) DEFAULT NULL COMMENT '0:Image 1:Voice',
  `checked` int(1) NOT NULL DEFAULT '0' COMMENT '0:unchecked 1:checked',
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`no`)
) ENGINE=InnoDB AUTO_INCREMENT=74 DEFAULT CHARSET=utf8;

/*Data for the table `t_file` */

insert  into `t_file`(`no`,`path`,`type`,`checked`,`updated_at`,`created_at`) values (1,'https://avatars1.githubusercontent.com/u/1476232?v=3&s=400',0,0,'2017-05-16 23:36:04','2017-05-16 23:36:06'),(2,'https://soundcloud.com/user-107006161/dc-rpg-the-hero-points-podcast-episode-1/download.mp3',1,0,'2017-05-16 23:42:44','2017-05-16 23:42:46'),(65,'http://192.168.0.13:8000/uploads/1496943783.png',0,0,'2017-06-08 17:43:03','2017-06-08 17:43:03'),(70,'http://192.168.0.13:8000/uploads/1496994384.png',0,0,'2017-06-09 07:46:24','2017-06-09 07:46:24'),(71,'http://192.168.0.13:8000/uploads/1497002921.m4a',1,0,'2017-06-09 10:08:41','2017-06-09 10:08:41'),(72,'http://192.168.0.13:8000/uploads/1497030979.m4a',1,0,'2017-06-09 17:56:20','2017-06-09 17:56:20'),(73,'http://192.168.0.13:8000/uploads/1497030980.png',0,0,'2017-06-09 17:56:20','2017-06-09 17:56:20');

/*Table structure for table `t_notification` */

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
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8;

/*Data for the table `t_notification` */

insert  into `t_notification`(`no`,`type`,`created_at`,`updated_at`,`title`,`content`,`from_user_no`,`to_user_no`,`unread_count`) values (23,6,'2017-06-09 07:47:35','2017-06-09 07:47:35','선물','estset님이 당신에게 20포인트를 선물했습니다.',1,31,5),(28,6,'2017-06-09 08:06:18','2017-06-09 08:06:18','선물','estset님이 당신에게 10포인트를 선물했습니다.',1,31,7),(35,1,'2017-06-09 17:04:20','2017-06-09 17:04:20','상담신청','안녕 하세요님이 상담신청을 수락했습니다.',32,31,3),(38,1,'2017-06-09 18:01:42','2017-06-09 18:01:42','상담신청','안녕님이 상담신청을 수락했습니다.',33,32,2),(40,5,'2017-06-09 18:16:39','2017-06-09 18:16:39','친구추가','안녕님이 당신을 친구추가했습니다.',33,32,1),(41,1,'2017-06-09 18:16:55','2017-06-09 18:16:55','상담신청','안녕님이 상담신청을 수락했습니다.',33,32,1),(42,0,'2017-06-10 01:56:45','2017-06-10 01:56:45','쪽지전송','test',31,33,1),(43,0,'2017-06-10 01:56:45','2017-06-10 01:56:45','쪽지전송','test',31,1,1);

/*Table structure for table `t_pointhistory` */

DROP TABLE IF EXISTS `t_pointhistory`;

CREATE TABLE `t_pointhistory` (
  `no` int(11) NOT NULL AUTO_INCREMENT COMMENT 't_pointhistory 유일식별자, 유저 Point 이동리력을 보관합니다.',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `type` int(2) DEFAULT '0' COMMENT '리력형태(0:출석체크, 1:쪽지전송, 2:선물보내기, 3: 선물받기, 4:상담Chat, 5:가입선물)',
  `point` int(11) DEFAULT '0' COMMENT '포인트량(추가일때 정수, 감소일때 부수)',
  `user_no` int(11) NOT NULL COMMENT '포인트 조작된 유저',
  PRIMARY KEY (`no`)
) ENGINE=InnoDB AUTO_INCREMENT=100 DEFAULT CHARSET=utf8;

/*Data for the table `t_pointhistory` */

insert  into `t_pointhistory`(`no`,`created_at`,`updated_at`,`type`,`point`,`user_no`) values (47,'2017-06-09 07:46:24','2017-06-09 07:46:24',5,300,31),(48,'2017-06-09 07:47:34','2017-06-09 07:47:34',2,-20,1),(49,'2017-06-09 07:47:34','2017-06-09 07:47:34',3,20,31),(50,'2017-06-09 07:47:35','2017-06-09 07:47:35',1,-20,1),(51,'2017-06-09 07:48:21','2017-06-09 07:48:21',2,-20,1),(52,'2017-06-09 07:48:21','2017-06-09 07:48:21',3,20,31),(53,'2017-06-09 07:48:21','2017-06-09 07:48:21',1,-20,1),(54,'2017-06-09 07:50:17','2017-06-09 07:50:17',2,-20,1),(55,'2017-06-09 07:50:17','2017-06-09 07:50:17',3,20,31),(56,'2017-06-09 07:50:18','2017-06-09 07:50:18',1,-20,1),(57,'2017-06-09 07:53:35','2017-06-09 07:53:35',2,-20,1),(58,'2017-06-09 07:53:35','2017-06-09 07:53:35',3,20,31),(59,'2017-06-09 07:53:36','2017-06-09 07:53:36',1,-20,1),(60,'2017-06-09 08:04:29','2017-06-09 08:04:29',2,-20,1),(61,'2017-06-09 08:04:29','2017-06-09 08:04:29',3,20,31),(62,'2017-06-09 08:04:30','2017-06-09 08:04:30',1,-20,1),(63,'2017-06-09 08:06:18','2017-06-09 08:06:18',2,-10,1),(64,'2017-06-09 08:06:18','2017-06-09 08:06:18',3,10,31),(65,'2017-06-09 08:06:18','2017-06-09 08:06:18',1,-20,1),(66,'2017-06-09 08:08:17','2017-06-09 08:08:17',2,-10,1),(67,'2017-06-09 08:08:17','2017-06-09 08:08:17',3,10,31),(68,'2017-06-09 08:08:17','2017-06-09 08:08:17',1,-20,1),(69,'2017-06-09 08:09:18','2017-06-09 08:09:18',2,-10,1),(70,'2017-06-09 08:09:18','2017-06-09 08:09:18',3,10,31),(71,'2017-06-09 08:09:18','2017-06-09 08:09:18',1,-20,1),(72,'2017-06-09 08:11:15','2017-06-09 08:11:15',2,-10,1),(73,'2017-06-09 08:11:15','2017-06-09 08:11:15',3,10,31),(74,'2017-06-09 08:11:16','2017-06-09 08:11:16',1,-20,1),(75,'2017-06-09 08:22:03','2017-06-09 08:22:03',2,-10,1),(76,'2017-06-09 08:22:03','2017-06-09 08:22:03',3,10,31),(77,'2017-06-09 08:22:04','2017-06-09 08:22:04',1,-20,1),(78,'2017-06-09 08:32:54','2017-06-09 08:32:54',2,-10,1),(79,'2017-06-09 08:32:54','2017-06-09 08:32:54',3,10,31),(80,'2017-06-09 08:32:54','2017-06-09 08:32:54',1,-20,1),(81,'2017-06-09 09:03:05','2017-06-09 09:03:05',2,-10,1),(82,'2017-06-09 09:03:05','2017-06-09 09:03:05',3,10,31),(83,'2017-06-09 17:03:58','2017-06-09 17:03:58',5,300,32),(84,'2017-06-09 17:04:20','2017-06-09 17:04:20',1,-20,32),(85,'2017-06-09 17:14:46','2017-06-09 17:14:46',1,-20,32),(86,'2017-06-09 17:16:15','2017-06-09 17:16:15',1,-20,32),(87,'2017-06-09 18:01:20','2017-06-09 18:01:20',5,300,33),(88,'2017-06-09 18:01:42','2017-06-09 18:01:42',1,-20,33),(89,'2017-06-09 18:03:56','2017-06-09 18:03:56',6,-100,1),(90,'2017-06-09 18:03:56','2017-06-09 18:03:56',6,100,31),(91,'2017-06-09 18:11:13','2017-06-09 18:11:13',1,-20,33),(92,'2017-06-09 18:16:39','2017-06-09 18:16:39',1,-20,33),(93,'2017-06-09 18:16:55','2017-06-09 18:16:55',1,-20,33),(94,'2017-06-09 18:17:54','2017-06-09 18:17:54',6,-73,32),(95,'2017-06-09 18:17:54','2017-06-09 18:17:54',6,73,33),(96,'2017-06-09 18:18:13','2017-06-09 18:18:13',6,-73,32),(97,'2017-06-09 18:18:13','2017-06-09 18:18:13',6,73,33),(98,'2017-06-10 01:56:45','2017-06-10 01:56:45',1,-20,31),(99,'2017-06-10 01:56:45','2017-06-10 01:56:45',1,-20,31);

/*Table structure for table `t_talk` */

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
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;

/*Data for the table `t_talk` */

insert  into `t_talk`(`no`,`subject`,`greeting`,`voice_type`,`voice_no`,`created_at`,`updated_at`,`user_no`,`img_no`,`type`,`nickname`,`age`,`is_verified_voice`) values (25,'연애상담','강남구 역삼동 더 이상 미룰 수 없는',0,71,'2017-06-09 10:08:41','2017-06-09 10:08:41',31,-1,0,NULL,0,0),(26,'부부상담','안녕 하세요 빨리 보고 싶어요',1,72,'2017-06-09 17:56:20','2017-06-09 17:56:20',32,73,0,NULL,0,0),(27,'부부상담','안녕 하세요 빨리 보고 싶어요',1,72,'2017-06-09 17:56:20','2017-06-09 17:56:20',33,73,0,'',0,0),(28,'부부상담','안녕 하세요 빨리 보고 싶어요',1,72,'2017-06-09 17:56:20','2017-06-09 17:56:20',1,73,0,'',0,0);

/*Table structure for table `t_talkreview` */

DROP TABLE IF EXISTS `t_talkreview`;

CREATE TABLE `t_talkreview` (
  `no` int(11) NOT NULL AUTO_INCREMENT COMMENT 't_talkreview 유일식별자, 이 표는 talk에 대한 review를 저장',
  `talk_no` int(11) NOT NULL COMMENT 'talk 유일식별자',
  `user_no` int(11) NOT NULL COMMENT 'user 유일식별자',
  `mark` int(11) NOT NULL DEFAULT '0' COMMENT 'review 점수',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`no`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

/*Data for the table `t_talkreview` */

insert  into `t_talkreview`(`no`,`talk_no`,`user_no`,`mark`,`created_at`,`updated_at`) values (7,25,32,5,'2017-06-09 17:04:49','2017-06-09 17:04:49'),(8,26,33,5,'2017-06-09 18:16:43','2017-06-09 18:16:43');

/*Table structure for table `t_user` */

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
  PRIMARY KEY (`no`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8;

/*Data for the table `t_user` */

insert  into `t_user`(`no`,`email`,`img_no`,`status`,`nickname`,`sex`,`age`,`subject`,`latitude`,`longitude`,`updated_at`,`created_at`,`location_no`,`verified`,`deposite_time`,`point`,`phone_number`,`device_type`,`device_serial`,`device_token`,`enable_alarm_call_request`,`enable_alarm_add_friend`,`check_roll_time`) values (1,'test@gmail.com',1,1,'estset',0,22,'asdasd',37.458613,126.705728,'2017-06-09 18:03:56','2017-05-16 23:43:35',0,1,1,499800,'123123123',0,'1',NULL,1,1,NULL),(31,'',70,0,'yyyyyu',0,20,'안녕 하세요 언제나 그렇듯 좋은 하루 되세요',37.457088470458984,126.70548248291016,'2017-06-12 00:49:11','2017-06-09 07:46:24',0,1,1,1530,'111111',0,'SM-G900S_351932064129702_328e4b00',NULL,1,1,NULL),(32,'',-1,0,'안녕 하세요',1,20,'오늘 뭐하냥 있다 있다 있다',37.457088470458984,126.70548248291016,'2017-06-09 18:18:13','2017-06-09 17:03:58',0,1,1,94,'222333',0,'SM-G900L_351878060404666_8c404a54',NULL,1,1,NULL),(33,'',-1,0,'안녕',0,23,'안녕 하세요쉽게 수 있도록',37.457088470458984,126.70548248291016,'2017-06-09 19:04:25','2017-06-09 18:01:20',2,0,1,366,NULL,0,'SM-N910K_354888060378403_4100884ef29d819b',NULL,1,1,NULL);

/*Table structure for table `t_user_relation` */

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
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

/*Data for the table `t_user_relation` */

insert  into `t_user_relation`(`no`,`user_no`,`relation_user_no`,`is_block_friend`,`is_alarm`,`is_friend`,`created_at`,`updated_at`) values (12,33,32,0,1,1,'2017-06-09 18:16:39','2017-06-09 18:16:39');

/*Table structure for table `t_withdraw` */

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `t_withdraw` */

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
