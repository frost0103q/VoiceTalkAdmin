/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50535
Source Host           : localhost:3306
Source Database       : voicetalk

Target Server Type    : MYSQL
Target Server Version : 50535
File Encoding         : 65001

Date: 2017-06-09 14:32:08
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for t_admin
-- ----------------------------
DROP TABLE IF EXISTS `t_admin`;
CREATE TABLE `t_admin` (
  `no` int(1) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL DEFAULT '',
  `password` varchar(255) NOT NULL DEFAULT '',
  `updated_at` datetime NOT NULL,
  `created_at` datetime NOT NULL,
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
  `no` int(11) NOT NULL AUTO_INCREMENT,
  `auth_code` varchar(255) NOT NULL,
  `requested_time` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `user_no` int(11) NOT NULL,
  `user_phone_number` varchar(30) NOT NULL,
  PRIMARY KEY (`no`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of t_authcode
-- ----------------------------

-- ----------------------------
-- Table structure for t_declare
-- ----------------------------
DROP TABLE IF EXISTS `t_declare`;
CREATE TABLE `t_declare` (
  `no` int(11) NOT NULL AUTO_INCREMENT,
  `from_user_no` int(11) NOT NULL,
  `to_user_no` int(11) NOT NULL,
  `content` varchar(1024) DEFAULT NULL,
  `reason` varchar(1024) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`no`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of t_declare
-- ----------------------------

-- ----------------------------
-- Table structure for t_file
-- ----------------------------
DROP TABLE IF EXISTS `t_file`;
CREATE TABLE `t_file` (
  `no` int(11) NOT NULL AUTO_INCREMENT,
  `path` varchar(2048) DEFAULT NULL,
  `type` int(3) DEFAULT NULL COMMENT '0:Image 1:Voice',
  `checked` int(1) NOT NULL DEFAULT '0' COMMENT '0:unchecked 1:checked',
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`no`)
) ENGINE=InnoDB AUTO_INCREMENT=70 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of t_file
-- ----------------------------
INSERT INTO `t_file` VALUES ('1', 'https://avatars1.githubusercontent.com/u/1476232?v=3&s=400', '0', '0', '2017-05-16 23:36:04', '2017-05-16 23:36:06');
INSERT INTO `t_file` VALUES ('2', 'https://soundcloud.com/user-107006161/dc-rpg-the-hero-points-podcast-episode-1/download.mp3', '1', '0', '2017-05-16 23:42:44', '2017-05-16 23:42:46');
INSERT INTO `t_file` VALUES ('65', 'http://192.168.0.13:8000/uploads/1496943783.png', '0', '0', '2017-06-08 17:43:03', '2017-06-08 17:43:03');

-- ----------------------------
-- Table structure for t_notification
-- ----------------------------
DROP TABLE IF EXISTS `t_notification`;
CREATE TABLE `t_notification` (
  `no` int(11) NOT NULL AUTO_INCREMENT,
  `type` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `title` varchar(255) DEFAULT '',
  `content` varchar(2048) DEFAULT '',
  `from_user_no` int(11) DEFAULT NULL,
  `to_user_no` int(11) DEFAULT NULL,
  `is_read` int(11) DEFAULT '0',
  PRIMARY KEY (`no`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of t_notification
-- ----------------------------

-- ----------------------------
-- Table structure for t_pointhistory
-- ----------------------------
DROP TABLE IF EXISTS `t_pointhistory`;
CREATE TABLE `t_pointhistory` (
  `no` int(11) NOT NULL AUTO_INCREMENT,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `type` int(2) DEFAULT '0',
  `point` int(11) DEFAULT '0',
  `user_no` int(11) NOT NULL COMMENT '    public final static int   POINT_HISTORY_TYPE_ROLL_CHECK = 0;\r\n    public final static int   POINT_HISTORY_TYPE_SEND_ENVELOPE = 1;\r\n    public final static int   POINT_HISTORY_TYPE_SEND_PRESENT = 2;\r\n    public final static int   POINT_HISTORY_TYPE_RECEIVE_PRESENT = 3;\r\n    public final static int   POINT_HISTORY_TYPE_CHAT = 4;\r\n    public final static int   POINT_HISTORY_TYPE_SIGN_UP = 5;',
  PRIMARY KEY (`no`)
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of t_pointhistory
-- ----------------------------

-- ----------------------------
-- Table structure for t_talk
-- ----------------------------
DROP TABLE IF EXISTS `t_talk`;
CREATE TABLE `t_talk` (
  `no` int(11) NOT NULL AUTO_INCREMENT,
  `subject` varchar(255) DEFAULT NULL,
  `greeting` varchar(1024) DEFAULT NULL,
  `voice_type` int(11) NOT NULL DEFAULT '0' COMMENT '0:normal["일반 목소리", "귀여운 목소리", "중후한 목소리","통통목소리","애교목소리"]',
  `voice_no` int(11) DEFAULT '-1',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `user_no` int(11) DEFAULT '-1',
  `img_no` int(11) DEFAULT '-1',
  `type` int(1) DEFAULT '0' COMMENT '0:Consulting 1:Normal',
  `nickname` varchar(255) DEFAULT NULL,
  `age` int(11) DEFAULT '0',
  `is_verified_voice` int(1) NOT NULL DEFAULT '0' COMMENT 'set by admin',
  PRIMARY KEY (`no`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of t_talk
-- ----------------------------

-- ----------------------------
-- Table structure for t_talkreview
-- ----------------------------
DROP TABLE IF EXISTS `t_talkreview`;
CREATE TABLE `t_talkreview` (
  `no` int(11) NOT NULL AUTO_INCREMENT,
  `talk_no` int(11) NOT NULL,
  `user_no` int(11) NOT NULL,
  `mark` int(11) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`no`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of t_talkreview
-- ----------------------------

-- ----------------------------
-- Table structure for t_user
-- ----------------------------
DROP TABLE IF EXISTS `t_user`;
CREATE TABLE `t_user` (
  `no` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL DEFAULT '' COMMENT 'Not used field now.',
  `img_no` int(11) NOT NULL DEFAULT '-1',
  `status` int(2) NOT NULL DEFAULT '0' COMMENT '0: 상담가능, 1: 부재중, 2:상담중',
  `nickname` varchar(255) NOT NULL DEFAULT '',
  `sex` int(1) DEFAULT '0' COMMENT ' // 0: 남 , 1:여',
  `age` int(3) DEFAULT '20',
  `subject` varchar(1024) DEFAULT '',
  `latitude` double DEFAULT '0',
  `longitude` double DEFAULT '0',
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `location_no` int(11) DEFAULT '0',
  `verified` int(1) NOT NULL DEFAULT '0' COMMENT '0: unverified, 1:verified',
  `deposite_time` int(11) NOT NULL DEFAULT '1',
  `point` int(11) NOT NULL DEFAULT '0',
  `phone_number` varchar(20) DEFAULT NULL,
  `device_type` int(1) NOT NULL DEFAULT '0' COMMENT '0:android 1:ios',
  `device_serial` varchar(255) DEFAULT NULL,
  `device_token` varchar(1024) DEFAULT NULL,
  `enable_alarm_call_request` int(1) NOT NULL DEFAULT '1',
  `enable_alarm_add_friend` int(1) NOT NULL DEFAULT '1',
  `check_roll_time` datetime DEFAULT NULL,
  PRIMARY KEY (`no`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of t_user
-- ----------------------------
INSERT INTO `t_user` VALUES ('28', 'test@gmail.com', '1', '1', 'estset', '0', '22', 'asdasd', '37.458613', '126.705728', '2017-05-25 07:15:12', '2017-05-16 23:43:35', '0', '1', '1', '280', '123123123', '0', '1', null, '1', '1', null);

-- ----------------------------
-- Table structure for t_user_relation
-- ----------------------------
DROP TABLE IF EXISTS `t_user_relation`;
CREATE TABLE `t_user_relation` (
  `no` int(11) NOT NULL AUTO_INCREMENT,
  `user_no` int(11) NOT NULL,
  `relation_user_no` int(11) NOT NULL,
  `is_block_friend` int(1) NOT NULL DEFAULT '0',
  `is_alarm` int(1) NOT NULL DEFAULT '1',
  `is_friend` int(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`no`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of t_user_relation
-- ----------------------------

-- ----------------------------
-- Table structure for t_withdraw
-- ----------------------------
DROP TABLE IF EXISTS `t_withdraw`;
CREATE TABLE `t_withdraw` (
  `no` int(11) NOT NULL AUTO_INCREMENT,
  `user_no` int(11) NOT NULL,
  `money` int(11) NOT NULL DEFAULT '53',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `bank_name` varchar(255) NOT NULL,
  `account_number` varchar(30) DEFAULT NULL,
  `account_name` varchar(255) DEFAULT NULL,
  `account_realname` varchar(255) DEFAULT NULL,
  `account_birth` varchar(255) DEFAULT NULL,
  `is_verified` int(1) NOT NULL DEFAULT '0',
  `status` int(1) NOT NULL DEFAULT '0' COMMENT '0:대기, 1: 완료, 2:오류',
  PRIMARY KEY (`no`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of t_withdraw
-- ----------------------------
