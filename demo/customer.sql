/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 100212
Source Host           : localhost:3306
Source Database       : customer

Target Server Type    : MYSQL
Target Server Version : 100212
File Encoding         : 65001

Date: 2021-01-03 20:53:17
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
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of admin
-- ----------------------------
INSERT INTO `admin` VALUES ('1', '2', 'admin', '管理员1', '1', '$2y$10$N8u95uOksNOu5cZGg.qWEOuQcJrJXdm3jyFCQdJzUTwkC6oqBMDQm', '2', '0', '2014-05-14 05:29:22', '2020-12-26 16:46:56');
INSERT INTO `admin` VALUES ('3', '2', 'admin3', '管理员3', '1', '$2y$10$N8u95uOksNOu5cZGg.qWEOuQcJrJXdm3jyFCQdJzUTwkC6oqBMDQm', '1', '0', '2014-05-14 05:29:22', '2020-12-26 16:46:53');
INSERT INTO `admin` VALUES ('20', '2', '1', '2', '', '$2y$10$Lu0s34v1XUFBnAFQr6UT4e43dbnySimQ2959qMg7l8dvJhPsmQJ66', '', '0', '2021-01-03 15:00:01', '2021-01-03 15:15:15');
INSERT INTO `admin` VALUES ('21', '2', '12', '2', '', '$2y$10$NKFk8efearTpTCx5vVdvB.LRf40GuYPnsFI6OgpnCVBH0u3JFNocC', '', '0', '2021-01-03 17:29:52', '2021-01-03 17:37:25');
INSERT INTO `admin` VALUES ('25', '2', '121', '333', '', '$2y$10$yJipINnX3vAaIgohlB.YduFriqnjNDgZJfdRn786XfEab0mzbMzaW', '', '0', '2021-01-03 17:37:49', null);
INSERT INTO `admin` VALUES ('27', '2', 'admin4', '管理员4', '', '$2y$10$5rWdOckiMTymkrsH04h7TOiJX2HWbuHBA/Y.pRnrzwORiV9IZY4Yq', '', '0', '2021-01-03 17:38:14', null);

-- ----------------------------
-- Table structure for customer
-- ----------------------------
DROP TABLE IF EXISTS `customer`;
CREATE TABLE `customer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userId` int(11) NOT NULL,
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
  KEY `userId` (`userId`),
  KEY `mobile` (`mobile`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of customer
-- ----------------------------
INSERT INTO `customer` VALUES ('5', '2', '3', '8', '9', '7', '6', '5', '4', '2021-01-03 13:51:24', '2020-12-06 17:33:09');
INSERT INTO `customer` VALUES ('6', '0', '2', '62', '61', '63', '64', '65', '66', '2021-01-03 14:15:44', '2021-01-03 13:31:26');
INSERT INTO `customer` VALUES ('7', '0', '1', '5', '6', '4', '32', '1', '22', '2021-01-03 14:15:18', '2021-01-03 13:31:33');

-- ----------------------------
-- Table structure for customer_follow
-- ----------------------------
DROP TABLE IF EXISTS `customer_follow`;
CREATE TABLE `customer_follow` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customerId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `note` varchar(255) NOT NULL DEFAULT '',
  `updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `userId` (`userId`),
  KEY `customerId` (`customerId`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of customer_follow
-- ----------------------------
INSERT INTO `customer_follow` VALUES ('1', '0', '0', '1111', '2020-12-02 21:25:50', '2020-12-02 21:24:24');

-- ----------------------------
-- Table structure for customer_order
-- ----------------------------
DROP TABLE IF EXISTS `customer_order`;
CREATE TABLE `customer_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customerId` int(11) NOT NULL DEFAULT 0,
  `userId` int(11) NOT NULL,
  `productName` varchar(255) NOT NULL DEFAULT '',
  `productQuantity` int(11) NOT NULL DEFAULT 0 COMMENT '数量',
  `price` decimal(11,2) NOT NULL DEFAULT 0.00 COMMENT '价格',
  `cost1` decimal(11,2) NOT NULL DEFAULT 0.00 COMMENT '成本',
  `cost2` decimal(11,2) NOT NULL DEFAULT 0.00,
  `cost3` decimal(11,2) NOT NULL DEFAULT 0.00,
  `cost4` decimal(11,2) NOT NULL DEFAULT 0.00,
  `expressConsignee` varchar(50) NOT NULL DEFAULT '' COMMENT '收货人',
  `expressCode` varchar(10) NOT NULL DEFAULT '' COMMENT '物流企业',
  `expressNumber` varchar(50) DEFAULT '' COMMENT '物流单号',
  `note` varchar(255) NOT NULL DEFAULT '',
  `updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `userId` (`userId`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of customer_order
-- ----------------------------
INSERT INTO `customer_order` VALUES ('1', '0', '0', '1111', '0', '0.00', '0.00', '0.00', '0.00', '0.00', '', '', '', '', '2020-12-02 21:25:50', '2020-12-02 21:24:24');

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
