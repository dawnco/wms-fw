/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 100212
Source Host           : localhost:3306
Source Database       : customer

Target Server Type    : MYSQL
Target Server Version : 100212
File Encoding         : 65001

Date: 2021-01-15 21:43:11
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for admin
-- ----------------------------
DROP TABLE IF EXISTS `admin`;
CREATE TABLE `admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `enabled` tinyint(1) NOT NULL DEFAULT 2,
  `username` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `phone` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `password` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `mobile` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `groupId` int(11) NOT NULL DEFAULT 0,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=74 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of admin
-- ----------------------------
INSERT INTO `admin` VALUES ('1', '2', 'admin', '管理员1', '1', '$2y$10$N8u95uOksNOu5cZGg.qWEOuQcJrJXdm3jyFCQdJzUTwkC6oqBMDQm', '2', '0', '2014-05-14 05:29:22', '2020-12-26 16:46:56');
INSERT INTO `admin` VALUES ('3', '0', 'test', 'Tester', '', '', '', '0', '2014-05-14 05:29:22', '2021-01-10 19:35:44');
INSERT INTO `admin` VALUES ('20', '2', '1', '管理员20', '', '$2y$10$Lu0s34v1XUFBnAFQr6UT4e43dbnySimQ2959qMg7l8dvJhPsmQJ66', '', '0', '2021-01-03 15:00:01', '2021-01-10 13:29:11');
INSERT INTO `admin` VALUES ('21', '2', '12', '管理员21', '', '$2y$10$NKFk8efearTpTCx5vVdvB.LRf40GuYPnsFI6OgpnCVBH0u3JFNocC', '', '0', '2021-01-03 17:29:52', '2021-01-10 13:29:14');
INSERT INTO `admin` VALUES ('25', '2', '121', '管理员25', '', '$2y$10$yJipINnX3vAaIgohlB.YduFriqnjNDgZJfdRn786XfEab0mzbMzaW', '', '0', '2021-01-03 17:37:49', '2021-01-10 13:29:17');
INSERT INTO `admin` VALUES ('27', '2', 'admin4', '管理员27', '', '$2y$10$5rWdOckiMTymkrsH04h7TOiJX2HWbuHBA/Y.pRnrzwORiV9IZY4Yq', '', '0', '2021-01-03 17:38:14', '2021-01-10 13:29:24');
INSERT INTO `admin` VALUES ('28', '2', '12222', '2', '', '$2y$10$IHxor/lMFBAp6oW/L2b2quOYH1XQG2lS8KpKAMYmacYFVGrrSAQgW', '', '0', '2021-01-08 21:58:09', '2021-01-10 16:18:35');

-- ----------------------------
-- Table structure for customer
-- ----------------------------
DROP TABLE IF EXISTS `customer`;
CREATE TABLE `customer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `adminId` int(11) NOT NULL,
  `group` varchar(10) NOT NULL DEFAULT '' COMMENT '客户组',
  `name` varchar(255) NOT NULL DEFAULT '',
  `company` varchar(255) NOT NULL DEFAULT '',
  `mobile` varchar(20) NOT NULL DEFAULT '',
  `mobile2` varchar(20) NOT NULL DEFAULT '',
  `area` varchar(30) NOT NULL DEFAULT '',
  `note` varchar(255) NOT NULL DEFAULT '',
  `updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `userId` (`adminId`),
  KEY `mobile` (`mobile`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of customer
-- ----------------------------
INSERT INTO `customer` VALUES ('5', '1', '3', '8', '9', '7', '6', '5', '4', '2021-01-10 13:29:35', '2020-12-06 17:33:09');
INSERT INTO `customer` VALUES ('6', '3', '2', '62', '61', '63', '64', '65', '66', '2021-01-10 13:29:40', '2021-01-03 13:31:26');
INSERT INTO `customer` VALUES ('7', '20', '1', '5', '6', '4', '32', '1', '22', '2021-01-10 13:29:43', '2021-01-03 13:31:33');
INSERT INTO `customer` VALUES ('8', '21', '2', '7', '8', '6', '5', '4', '3', '2021-01-15 21:12:18', '2021-01-08 21:44:34');
INSERT INTO `customer` VALUES ('9', '21', '', '', '', '', '', '', '', '2021-01-10 13:44:42', '2021-01-08 21:56:58');
INSERT INTO `customer` VALUES ('10', '20', '', '', '', '', '', '', '', '2021-01-10 13:30:18', '2021-01-09 13:14:09');
INSERT INTO `customer` VALUES ('11', '3', '', '对对对', '', '', '', '', '', '2021-01-10 13:30:16', '2021-01-09 13:15:20');
INSERT INTO `customer` VALUES ('12', '1', '3', '老李', '测试企业', '13312341234', '', '重庆', '', '2021-01-15 21:12:12', '2021-01-09 14:48:06');
INSERT INTO `customer` VALUES ('15', '1', '2', '测试121211111111111', '1212', '', '', '', '', '2021-01-10 19:28:03', '2021-01-10 19:27:57');
INSERT INTO `customer` VALUES ('16', '3', '', '老李', '企业名称', '1331234', '19911111111', '重庆', '是是是', '2021-01-10 20:43:02', '2021-01-10 19:35:21');

-- ----------------------------
-- Table structure for customer_follow
-- ----------------------------
DROP TABLE IF EXISTS `customer_follow`;
CREATE TABLE `customer_follow` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customerId` int(11) NOT NULL,
  `adminId` int(11) NOT NULL,
  `note` varchar(255) NOT NULL DEFAULT '',
  `updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `userId` (`adminId`),
  KEY `customerId` (`customerId`)
) ENGINE=InnoDB AUTO_INCREMENT=77 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of customer_follow
-- ----------------------------
INSERT INTO `customer_follow` VALUES ('12', '11', '1', '22222', '2021-01-09 14:05:28', '2021-01-09 14:05:28');
INSERT INTO `customer_follow` VALUES ('13', '11', '1', '2222', '2021-01-09 14:05:34', '2021-01-09 14:05:34');
INSERT INTO `customer_follow` VALUES ('14', '11', '1', '32132', '2021-01-09 14:44:33', '2021-01-09 14:44:33');
INSERT INTO `customer_follow` VALUES ('15', '11', '1', '55', '2021-01-09 14:44:46', '2021-01-09 14:44:46');
INSERT INTO `customer_follow` VALUES ('16', '12', '1', '222', '2021-01-09 14:48:10', '2021-01-09 14:48:10');
INSERT INTO `customer_follow` VALUES ('17', '12', '1', '222', '2021-01-09 14:48:14', '2021-01-09 14:48:14');
INSERT INTO `customer_follow` VALUES ('18', '12', '1', '2222', '2021-01-09 14:48:42', '2021-01-09 14:48:42');
INSERT INTO `customer_follow` VALUES ('19', '12', '1', '222', '2021-01-09 14:52:08', '2021-01-09 14:52:08');
INSERT INTO `customer_follow` VALUES ('20', '12', '1', '222222', '2021-01-09 14:55:44', '2021-01-09 14:55:44');
INSERT INTO `customer_follow` VALUES ('21', '12', '1', '的实打实的', '2021-01-09 14:55:49', '2021-01-09 14:55:49');
INSERT INTO `customer_follow` VALUES ('22', '12', '1', '很好 的', '2021-01-09 14:57:47', '2021-01-09 14:57:47');
INSERT INTO `customer_follow` VALUES ('23', '12', '1', '很好的222222', '2021-01-09 14:58:13', '2021-01-09 14:58:13');
INSERT INTO `customer_follow` VALUES ('24', '12', '1', '很好的222222111', '2021-01-09 14:58:16', '2021-01-09 14:58:16');
INSERT INTO `customer_follow` VALUES ('25', '12', '1', 'dddd顶顶顶', '2021-01-09 14:58:24', '2021-01-09 14:58:24');
INSERT INTO `customer_follow` VALUES ('26', '12', '1', 'dddd顶顶顶', '2021-01-09 14:58:30', '2021-01-09 14:58:30');
INSERT INTO `customer_follow` VALUES ('27', '12', '1', 'dddd顶顶顶', '2021-01-09 14:58:31', '2021-01-09 14:58:31');
INSERT INTO `customer_follow` VALUES ('28', '12', '1', 'dddd顶顶顶', '2021-01-09 14:58:32', '2021-01-09 14:58:32');
INSERT INTO `customer_follow` VALUES ('29', '12', '1', 'dddd顶顶顶', '2021-01-09 14:58:33', '2021-01-09 14:58:33');
INSERT INTO `customer_follow` VALUES ('30', '12', '1', '2222', '2021-01-09 14:59:20', '2021-01-09 14:59:20');
INSERT INTO `customer_follow` VALUES ('31', '12', '1', '2222', '2021-01-09 14:59:21', '2021-01-09 14:59:21');
INSERT INTO `customer_follow` VALUES ('32', '12', '1', '2222', '2021-01-09 14:59:22', '2021-01-09 14:59:22');
INSERT INTO `customer_follow` VALUES ('33', '12', '1', '2222', '2021-01-09 14:59:23', '2021-01-09 14:59:23');
INSERT INTO `customer_follow` VALUES ('34', '12', '1', '2222', '2021-01-09 14:59:24', '2021-01-09 14:59:24');
INSERT INTO `customer_follow` VALUES ('35', '12', '1', '2222', '2021-01-09 14:59:25', '2021-01-09 14:59:25');
INSERT INTO `customer_follow` VALUES ('36', '12', '1', '2222', '2021-01-09 14:59:26', '2021-01-09 14:59:26');
INSERT INTO `customer_follow` VALUES ('37', '12', '1', '2222', '2021-01-09 14:59:26', '2021-01-09 14:59:26');
INSERT INTO `customer_follow` VALUES ('38', '12', '1', '2222', '2021-01-09 14:59:27', '2021-01-09 14:59:27');
INSERT INTO `customer_follow` VALUES ('39', '12', '1', '2222', '2021-01-09 14:59:28', '2021-01-09 14:59:28');
INSERT INTO `customer_follow` VALUES ('40', '12', '1', '2222', '2021-01-09 14:59:29', '2021-01-09 14:59:29');
INSERT INTO `customer_follow` VALUES ('41', '12', '1', '2222', '2021-01-09 14:59:30', '2021-01-09 14:59:30');
INSERT INTO `customer_follow` VALUES ('42', '12', '1', '2222', '2021-01-09 14:59:43', '2021-01-09 14:59:43');
INSERT INTO `customer_follow` VALUES ('43', '12', '1', '2222', '2021-01-09 14:59:44', '2021-01-09 14:59:44');
INSERT INTO `customer_follow` VALUES ('44', '12', '1', '2121212111111111', '2021-01-09 15:00:16', '2021-01-09 15:00:16');
INSERT INTO `customer_follow` VALUES ('45', '0', '1', '2222', '2021-01-09 15:30:13', '2021-01-09 15:30:13');
INSERT INTO `customer_follow` VALUES ('46', '0', '1', '3333', '2021-01-09 15:30:16', '2021-01-09 15:30:16');
INSERT INTO `customer_follow` VALUES ('47', '0', '1', '2222', '2021-01-09 15:31:04', '2021-01-09 15:31:04');
INSERT INTO `customer_follow` VALUES ('48', '12', '1', '12121', '2021-01-09 15:32:30', '2021-01-09 15:32:30');
INSERT INTO `customer_follow` VALUES ('49', '11', '1', '11', '2021-01-09 15:32:41', '2021-01-09 15:32:41');
INSERT INTO `customer_follow` VALUES ('50', '10', '1', '1010', '2021-01-09 15:32:51', '2021-01-09 15:32:51');
INSERT INTO `customer_follow` VALUES ('51', '10', '1', '1111', '2021-01-09 15:32:57', '2021-01-09 15:32:57');
INSERT INTO `customer_follow` VALUES ('52', '9', '1', '999', '2021-01-09 15:34:00', '2021-01-09 15:34:00');
INSERT INTO `customer_follow` VALUES ('53', '12', '1', '很好不错', '2021-01-09 15:44:54', '2021-01-09 15:44:54');
INSERT INTO `customer_follow` VALUES ('54', '12', '1', '顶顶顶', '2021-01-09 15:49:19', '2021-01-09 15:49:19');
INSERT INTO `customer_follow` VALUES ('55', '12', '1', '3333', '2021-01-09 15:49:44', '2021-01-09 15:49:44');
INSERT INTO `customer_follow` VALUES ('56', '12', '1', '22222', '2021-01-09 15:50:18', '2021-01-09 15:50:18');
INSERT INTO `customer_follow` VALUES ('57', '12', '1', '呃呃呃呃呃呃', '2021-01-09 15:50:56', '2021-01-09 15:50:56');
INSERT INTO `customer_follow` VALUES ('58', '12', '1', '222', '2021-01-09 15:51:46', '2021-01-09 15:51:46');
INSERT INTO `customer_follow` VALUES ('59', '12', '1', '踩踩踩', '2021-01-09 15:52:21', '2021-01-09 15:52:21');
INSERT INTO `customer_follow` VALUES ('60', '12', '1', '333333', '2021-01-09 15:59:37', '2021-01-09 15:59:37');
INSERT INTO `customer_follow` VALUES ('61', '12', '1', '3333', '2021-01-09 15:59:43', '2021-01-09 15:59:43');
INSERT INTO `customer_follow` VALUES ('62', '12', '1', '222', '2021-01-09 16:02:16', '2021-01-09 16:02:16');
INSERT INTO `customer_follow` VALUES ('63', '12', '1', '顶顶顶顶顶', '2021-01-09 16:47:47', '2021-01-09 16:47:47');
INSERT INTO `customer_follow` VALUES ('64', '12', '1', '测试', '2021-01-10 14:39:51', '2021-01-10 14:39:51');
INSERT INTO `customer_follow` VALUES ('65', '12', '0', '萨达第三方', '2021-01-10 19:26:19', '2021-01-10 19:26:19');
INSERT INTO `customer_follow` VALUES ('66', '12', '0', '2222', '2021-01-10 19:26:32', '2021-01-10 19:26:32');
INSERT INTO `customer_follow` VALUES ('67', '12', '1', '3333', '2021-01-10 19:27:16', '2021-01-10 19:27:16');
INSERT INTO `customer_follow` VALUES ('68', '15', '1', '15', '2021-01-10 19:32:00', '2021-01-10 19:32:00');
INSERT INTO `customer_follow` VALUES ('69', '15', '1', '杀', '2021-01-10 19:32:04', '2021-01-10 19:32:04');
INSERT INTO `customer_follow` VALUES ('70', '15', '1', '的点点滴滴', '2021-01-10 19:32:07', '2021-01-10 19:32:07');
INSERT INTO `customer_follow` VALUES ('71', '16', '3', '对对对', '2021-01-10 19:36:13', '2021-01-10 19:36:13');
INSERT INTO `customer_follow` VALUES ('72', '16', '1', 'ccc', '2021-01-10 19:36:30', '2021-01-10 19:36:30');
INSERT INTO `customer_follow` VALUES ('73', '16', '1', '不好弄', '2021-01-10 19:38:39', '2021-01-10 19:38:39');
INSERT INTO `customer_follow` VALUES ('74', '16', '1', '回车', '2021-01-10 19:41:51', '2021-01-10 19:41:51');
INSERT INTO `customer_follow` VALUES ('75', '16', '1', '很好', '2021-01-10 19:41:54', '2021-01-10 19:41:54');
INSERT INTO `customer_follow` VALUES ('76', '16', '1', 'good', '2021-01-10 20:04:51', '2021-01-10 20:04:51');

-- ----------------------------
-- Table structure for customer_order
-- ----------------------------
DROP TABLE IF EXISTS `customer_order`;
CREATE TABLE `customer_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customerId` int(11) NOT NULL DEFAULT 0,
  `adminId` int(11) NOT NULL,
  `productName` varchar(255) NOT NULL DEFAULT '',
  `productQuantity` int(11) NOT NULL DEFAULT 0 COMMENT '数量',
  `price` decimal(11,2) NOT NULL DEFAULT 0.00 COMMENT '价格',
  `cost1` decimal(11,2) NOT NULL DEFAULT 0.00 COMMENT '成本',
  `cost2` decimal(11,2) NOT NULL DEFAULT 0.00,
  `cost3` decimal(11,2) NOT NULL DEFAULT 0.00,
  `cost4` decimal(11,2) NOT NULL DEFAULT 0.00,
  `expressConsignee` varchar(50) NOT NULL DEFAULT '' COMMENT '收货人',
  `expressCode` varchar(10) NOT NULL DEFAULT '' COMMENT '物流企业',
  `expressNumber` varchar(50) NOT NULL DEFAULT '' COMMENT '物流单号',
  `expressDate` date DEFAULT NULL COMMENT '发货时间',
  `consigneeDate` date DEFAULT NULL COMMENT '收货时间',
  `note` varchar(255) NOT NULL DEFAULT '',
  `updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `userId` (`adminId`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of customer_order
-- ----------------------------
INSERT INTO `customer_order` VALUES ('11', '16', '1', '1', '0', '2.00', '0.00', '0.00', '0.00', '0.00', '', 'STO', '', null, null, '', '2021-01-10 20:36:51', '2021-01-10 20:36:51');
INSERT INTO `customer_order` VALUES ('12', '16', '1', '16', '0', '100.00', '12.00', '12.00', '6.00', '0.00', '利息', 'SF', '111111111', null, null, '很好', '2021-01-14 20:17:19', '2021-01-14 20:17:19');
INSERT INTO `customer_order` VALUES ('13', '16', '1', '1', '0', '2.00', '0.00', '0.00', '0.00', '0.00', '111', 'STO', '', null, null, '', '2021-01-14 21:35:26', '2021-01-14 21:35:26');
INSERT INTO `customer_order` VALUES ('14', '16', '1', '16', '0', '100.00', '12.00', '12.00', '6.00', '0.00', '利息', 'SF', '111111111', null, null, '很好', '2021-01-14 21:35:47', '2021-01-14 21:35:47');
INSERT INTO `customer_order` VALUES ('15', '16', '1', '16', '0', '100.00', '12.00', '12.00', '6.00', '0.00', '利息', 'SF', '111111111', null, null, '很好', '2021-01-14 21:37:34', '2021-01-14 21:37:34');
INSERT INTO `customer_order` VALUES ('16', '16', '1', '', '0', '0.00', '0.00', '0.00', '0.00', '0.00', '', '', '', null, null, '', '2021-01-14 21:37:47', '2021-01-14 21:37:47');
INSERT INTO `customer_order` VALUES ('17', '16', '1', '', '0', '0.00', '0.00', '0.00', '0.00', '0.00', '', '', '', null, null, '', '2021-01-14 21:37:59', '2021-01-14 21:37:59');
INSERT INTO `customer_order` VALUES ('18', '16', '1', '', '0', '0.00', '0.00', '0.00', '0.00', '0.00', '', '', '', null, null, '', '2021-01-14 21:38:09', '2021-01-14 21:38:09');
INSERT INTO `customer_order` VALUES ('19', '16', '1', '1212', '1111', '0.00', '0.00', '0.00', '0.00', '0.00', '', '', '', '0000-00-00', '0000-00-00', '', '2021-01-15 20:59:50', '2021-01-14 21:38:11');
INSERT INTO `customer_order` VALUES ('20', '16', '1', '20202020201', '0', '0.00', '0.00', '0.00', '0.00', '0.00', '', '', '', '0000-00-00', '0000-00-00', '', '2021-01-15 20:59:31', '2021-01-14 21:38:13');
INSERT INTO `customer_order` VALUES ('21', '16', '1', 'ddddd', '0', '0.00', '0.00', '0.00', '0.00', '0.00', '', '', '', '0000-00-00', '0000-00-00', '', '2021-01-15 20:59:22', '2021-01-14 21:38:15');
INSERT INTO `customer_order` VALUES ('22', '16', '1', '11111212', '0', '0.00', '0.00', '0.00', '0.00', '0.00', '', '', '', '0000-00-00', '0000-00-00', '', '2021-01-15 20:59:18', '2021-01-14 21:38:17');
INSERT INTO `customer_order` VALUES ('23', '16', '1', '23231', '111', '100.00', '12.00', '12.00', '6.00', '0.00', '利息', 'SF', '111111111', '2021-01-04', '2021-01-15', '很好1', '2021-01-15 20:59:00', '2021-01-14 21:38:21');

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL DEFAULT '',
  `name` varchar(255) NOT NULL DEFAULT '',
  `password` varchar(255) NOT NULL DEFAULT '',
  `updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES ('1', 'admin', 'admin', '$2y$10$QJlSPm.NG0ZOnEDYpgiblOtijlUl/FTuyLnSosGDvyRhkyHiSTrPy', '2020-12-06 17:07:20', '2020-12-02 21:24:24');
INSERT INTO `user` VALUES ('2', '11', '22', '33', '2021-01-03 14:42:08', '2021-01-03 14:41:24');
