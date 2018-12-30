/*
Navicat MySQL Data Transfer

Source Server         : pc_jiangfw
Source Server Version : 50617
Source Host           : localhost:3306
Source Database       : hengxi

Target Server Type    : MYSQL
Target Server Version : 50617
File Encoding         : 65001

Date: 2018-11-10 22:24:48
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for auth_assignment
-- ----------------------------
DROP TABLE IF EXISTS `auth_assignment`;
CREATE TABLE `auth_assignment` (
  `item_name` varchar(64) NOT NULL,
  `user_id` varchar(64) NOT NULL,
  `created_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`item_name`,`user_id`),
  CONSTRAINT `auth_assignment_ibfk_1` FOREIGN KEY (`item_name`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of auth_assignment
-- ----------------------------
INSERT INTO `auth_assignment` VALUES ('超级管理员', '2', '1482908075');

-- ----------------------------
-- Table structure for auth_item
-- ----------------------------
DROP TABLE IF EXISTS `auth_item`;
CREATE TABLE `auth_item` (
  `name` varchar(64) NOT NULL,
  `type` int(11) NOT NULL,
  `description` text,
  `rule_name` varchar(64) DEFAULT NULL,
  `data` text,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`),
  KEY `rule_name` (`rule_name`),
  KEY `type` (`type`),
  CONSTRAINT `auth_item_ibfk_1` FOREIGN KEY (`rule_name`) REFERENCES `auth_rule` (`name`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of auth_item
-- ----------------------------
INSERT INTO `auth_item` VALUES ('/*', '2', null, null, null, '1482800318', '1482800318');
INSERT INTO `auth_item` VALUES ('/admin/*', '2', null, null, null, '1482800316', '1482800316');
INSERT INTO `auth_item` VALUES ('/admin/assignment/*', '2', null, null, null, '1482800314', '1482800314');
INSERT INTO `auth_item` VALUES ('/admin/assignment/assign', '2', null, null, null, '1482800314', '1482800314');
INSERT INTO `auth_item` VALUES ('/admin/assignment/index', '2', null, null, null, '1482800314', '1482800314');
INSERT INTO `auth_item` VALUES ('/admin/assignment/revoke', '2', null, null, null, '1482800314', '1482800314');
INSERT INTO `auth_item` VALUES ('/admin/assignment/view', '2', null, null, null, '1482800314', '1482800314');
INSERT INTO `auth_item` VALUES ('/admin/create', '2', null, null, null, '1482800317', '1482800317');
INSERT INTO `auth_item` VALUES ('/admin/default/*', '2', null, null, null, '1482800314', '1482800314');
INSERT INTO `auth_item` VALUES ('/admin/default/index', '2', null, null, null, '1482800314', '1482800314');
INSERT INTO `auth_item` VALUES ('/admin/delete', '2', null, null, null, '1482800317', '1482800317');
INSERT INTO `auth_item` VALUES ('/admin/index', '2', null, null, null, '1482800317', '1482800317');
INSERT INTO `auth_item` VALUES ('/admin/menu/*', '2', null, null, null, '1482800315', '1482800315');
INSERT INTO `auth_item` VALUES ('/admin/menu/create', '2', null, null, null, '1482800314', '1482800314');
INSERT INTO `auth_item` VALUES ('/admin/menu/delete', '2', null, null, null, '1482800315', '1482800315');
INSERT INTO `auth_item` VALUES ('/admin/menu/index', '2', null, null, null, '1482800314', '1482800314');
INSERT INTO `auth_item` VALUES ('/admin/menu/update', '2', null, null, null, '1482800314', '1482800314');
INSERT INTO `auth_item` VALUES ('/admin/menu/view', '2', null, null, null, '1482800314', '1482800314');
INSERT INTO `auth_item` VALUES ('/admin/permission/*', '2', null, null, null, '1482800315', '1482800315');
INSERT INTO `auth_item` VALUES ('/admin/permission/assign', '2', null, null, null, '1482800315', '1482800315');
INSERT INTO `auth_item` VALUES ('/admin/permission/create', '2', null, null, null, '1482800315', '1482800315');
INSERT INTO `auth_item` VALUES ('/admin/permission/delete', '2', null, null, null, '1482800315', '1482800315');
INSERT INTO `auth_item` VALUES ('/admin/permission/index', '2', null, null, null, '1482800315', '1482800315');
INSERT INTO `auth_item` VALUES ('/admin/permission/remove', '2', null, null, null, '1482800315', '1482800315');
INSERT INTO `auth_item` VALUES ('/admin/permission/update', '2', null, null, null, '1482800315', '1482800315');
INSERT INTO `auth_item` VALUES ('/admin/permission/view', '2', null, null, null, '1482800315', '1482800315');
INSERT INTO `auth_item` VALUES ('/admin/role/*', '2', null, null, null, '1482800315', '1482800315');
INSERT INTO `auth_item` VALUES ('/admin/role/assign', '2', null, null, null, '1482800315', '1482800315');
INSERT INTO `auth_item` VALUES ('/admin/role/create', '2', null, null, null, '1482800315', '1482800315');
INSERT INTO `auth_item` VALUES ('/admin/role/delete', '2', null, null, null, '1482800315', '1482800315');
INSERT INTO `auth_item` VALUES ('/admin/role/index', '2', null, null, null, '1482800315', '1482800315');
INSERT INTO `auth_item` VALUES ('/admin/role/remove', '2', null, null, null, '1482800315', '1482800315');
INSERT INTO `auth_item` VALUES ('/admin/role/update', '2', null, null, null, '1482800315', '1482800315');
INSERT INTO `auth_item` VALUES ('/admin/role/view', '2', null, null, null, '1482800315', '1482800315');
INSERT INTO `auth_item` VALUES ('/admin/route/*', '2', null, null, null, '1482800315', '1482800315');
INSERT INTO `auth_item` VALUES ('/admin/route/assign', '2', null, null, null, '1482800315', '1482800315');
INSERT INTO `auth_item` VALUES ('/admin/route/create', '2', null, null, null, '1482800315', '1482800315');
INSERT INTO `auth_item` VALUES ('/admin/route/index', '2', null, null, null, '1482800315', '1482800315');
INSERT INTO `auth_item` VALUES ('/admin/route/refresh', '2', null, null, null, '1482800315', '1482800315');
INSERT INTO `auth_item` VALUES ('/admin/route/remove', '2', null, null, null, '1482800315', '1482800315');
INSERT INTO `auth_item` VALUES ('/admin/rule/*', '2', null, null, null, '1482800316', '1482800316');
INSERT INTO `auth_item` VALUES ('/admin/rule/create', '2', null, null, null, '1482800316', '1482800316');
INSERT INTO `auth_item` VALUES ('/admin/rule/delete', '2', null, null, null, '1482800316', '1482800316');
INSERT INTO `auth_item` VALUES ('/admin/rule/index', '2', null, null, null, '1482800315', '1482800315');
INSERT INTO `auth_item` VALUES ('/admin/rule/update', '2', null, null, null, '1482800316', '1482800316');
INSERT INTO `auth_item` VALUES ('/admin/rule/view', '2', null, null, null, '1482800315', '1482800315');
INSERT INTO `auth_item` VALUES ('/admin/update', '2', null, null, null, '1482800317', '1482800317');
INSERT INTO `auth_item` VALUES ('/admin/user/*', '2', null, null, null, '1482800316', '1482800316');
INSERT INTO `auth_item` VALUES ('/admin/user/activate', '2', null, null, null, '1482800316', '1482800316');
INSERT INTO `auth_item` VALUES ('/admin/user/change-password', '2', null, null, null, '1482800316', '1482800316');
INSERT INTO `auth_item` VALUES ('/admin/user/delete', '2', null, null, null, '1482800316', '1482800316');
INSERT INTO `auth_item` VALUES ('/admin/user/index', '2', null, null, null, '1482800316', '1482800316');
INSERT INTO `auth_item` VALUES ('/admin/user/login', '2', null, null, null, '1482800316', '1482800316');
INSERT INTO `auth_item` VALUES ('/admin/user/logout', '2', null, null, null, '1482800316', '1482800316');
INSERT INTO `auth_item` VALUES ('/admin/user/request-password-reset', '2', null, null, null, '1482800316', '1482800316');
INSERT INTO `auth_item` VALUES ('/admin/user/reset-password', '2', null, null, null, '1482800316', '1482800316');
INSERT INTO `auth_item` VALUES ('/admin/user/signup', '2', null, null, null, '1482800316', '1482800316');
INSERT INTO `auth_item` VALUES ('/admin/user/view', '2', null, null, null, '1482800316', '1482800316');
INSERT INTO `auth_item` VALUES ('/admin/view', '2', null, null, null, '1482800317', '1482800317');
INSERT INTO `auth_item` VALUES ('/common/*', '2', null, null, null, '1482800317', '1482800317');
INSERT INTO `auth_item` VALUES ('/debug/*', '2', null, null, null, '1482800316', '1482800316');
INSERT INTO `auth_item` VALUES ('/debug/default/*', '2', null, null, null, '1482800316', '1482800316');
INSERT INTO `auth_item` VALUES ('/debug/default/db-explain', '2', null, null, null, '1482800316', '1482800316');
INSERT INTO `auth_item` VALUES ('/debug/default/download-mail', '2', null, null, null, '1482800316', '1482800316');
INSERT INTO `auth_item` VALUES ('/debug/default/index', '2', null, null, null, '1482800316', '1482800316');
INSERT INTO `auth_item` VALUES ('/debug/default/toolbar', '2', null, null, null, '1482800316', '1482800316');
INSERT INTO `auth_item` VALUES ('/debug/default/view', '2', null, null, null, '1482800316', '1482800316');
INSERT INTO `auth_item` VALUES ('/gii/*', '2', null, null, null, '1482800316', '1482800316');
INSERT INTO `auth_item` VALUES ('/gii/default/*', '2', null, null, null, '1482800316', '1482800316');
INSERT INTO `auth_item` VALUES ('/gii/default/action', '2', null, null, null, '1482800316', '1482800316');
INSERT INTO `auth_item` VALUES ('/gii/default/diff', '2', null, null, null, '1482800316', '1482800316');
INSERT INTO `auth_item` VALUES ('/gii/default/index', '2', null, null, null, '1482800316', '1482800316');
INSERT INTO `auth_item` VALUES ('/gii/default/preview', '2', null, null, null, '1482800316', '1482800316');
INSERT INTO `auth_item` VALUES ('/gii/default/view', '2', null, null, null, '1482800316', '1482800316');
INSERT INTO `auth_item` VALUES ('/site/*', '2', null, null, null, '1482713637', '1482713637');
INSERT INTO `auth_item` VALUES ('/site/about', '2', null, null, null, '1482800318', '1482800318');
INSERT INTO `auth_item` VALUES ('/site/captcha', '2', null, null, null, '1482800318', '1482800318');
INSERT INTO `auth_item` VALUES ('/site/contact', '2', null, null, null, '1482800318', '1482800318');
INSERT INTO `auth_item` VALUES ('/site/error', '2', null, null, null, '1482800318', '1482800318');
INSERT INTO `auth_item` VALUES ('/site/index', '2', null, null, null, '1482717789', '1482717789');
INSERT INTO `auth_item` VALUES ('/site/login', '2', null, null, null, '1482800318', '1482800318');
INSERT INTO `auth_item` VALUES ('/site/logout', '2', null, null, null, '1482717789', '1482717789');
INSERT INTO `auth_item` VALUES ('/user/*', '2', null, null, null, '1482804358', '1482804358');
INSERT INTO `auth_item` VALUES ('/user/create', '2', null, null, null, '1482804433', '1482804433');
INSERT INTO `auth_item` VALUES ('/user/delete', '2', null, null, null, '1482804433', '1482804433');
INSERT INTO `auth_item` VALUES ('/user/index', '2', null, null, null, '1482804433', '1482804433');
INSERT INTO `auth_item` VALUES ('/user/signup', '2', null, null, null, '1508575705', '1508575705');
INSERT INTO `auth_item` VALUES ('/user/update', '2', null, null, null, '1482804433', '1482804433');
INSERT INTO `auth_item` VALUES ('/user/view', '2', null, null, null, '1482804433', '1482804433');
INSERT INTO `auth_item` VALUES ('权限管理-分配', '2', '权限管理-分配', null, null, '1482807128', '1482807128');
INSERT INTO `auth_item` VALUES ('权限管理-权限', '2', '权限管理-权限', null, null, '1482806754', '1482806899');
INSERT INTO `auth_item` VALUES ('权限管理-菜单', '2', '权限管理-菜单', null, null, '1482806774', '1482806881');
INSERT INTO `auth_item` VALUES ('权限管理-角色', '2', '权限管理-角色', null, null, '1482806575', '1482806858');
INSERT INTO `auth_item` VALUES ('权限管理-路由', '2', '权限管理-路由', null, null, '1482806734', '1482806914');
INSERT INTO `auth_item` VALUES ('用户管理', '2', '用户管理', null, null, '1482835089', '1482835089');
INSERT INTO `auth_item` VALUES ('超级管理员', '1', '创建了 admin角色、部门、权限组', null, null, '1482479247', '1508575263');

-- ----------------------------
-- Table structure for auth_item_child
-- ----------------------------
DROP TABLE IF EXISTS `auth_item_child`;
CREATE TABLE `auth_item_child` (
  `parent` varchar(64) NOT NULL,
  `child` varchar(64) NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`),
  CONSTRAINT `auth_item_child_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `auth_item_child_ibfk_2` FOREIGN KEY (`child`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of auth_item_child
-- ----------------------------
INSERT INTO `auth_item_child` VALUES ('超级管理员', '/*');
INSERT INTO `auth_item_child` VALUES ('权限管理-分配', '/admin/assignment/*');
INSERT INTO `auth_item_child` VALUES ('权限管理-菜单', '/admin/menu/*');
INSERT INTO `auth_item_child` VALUES ('权限管理-权限', '/admin/permission/*');
INSERT INTO `auth_item_child` VALUES ('权限管理-角色', '/admin/role/*');
INSERT INTO `auth_item_child` VALUES ('权限管理-路由', '/admin/route/*');
INSERT INTO `auth_item_child` VALUES ('基本权限', '/debug/*');
INSERT INTO `auth_item_child` VALUES ('基本权限', '/site/*');
INSERT INTO `auth_item_child` VALUES ('用户管理', '/user/*');
INSERT INTO `auth_item_child` VALUES ('超级管理员', '权限管理-分配');
INSERT INTO `auth_item_child` VALUES ('超级管理员', '权限管理-权限');
INSERT INTO `auth_item_child` VALUES ('超级管理员', '权限管理-菜单');
INSERT INTO `auth_item_child` VALUES ('超级管理员', '权限管理-角色');
INSERT INTO `auth_item_child` VALUES ('超级管理员', '权限管理-路由');

-- ----------------------------
-- Table structure for auth_rule
-- ----------------------------
DROP TABLE IF EXISTS `auth_rule`;
CREATE TABLE `auth_rule` (
  `name` varchar(64) NOT NULL,
  `data` text,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of auth_rule
-- ----------------------------

-- ----------------------------
-- Table structure for menu
-- ----------------------------
DROP TABLE IF EXISTS `menu`;
CREATE TABLE `menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `parent` int(11) DEFAULT NULL,
  `route` varchar(256) DEFAULT NULL,
  `order` int(11) DEFAULT NULL,
  `data` text,
  PRIMARY KEY (`id`),
  KEY `parent` (`parent`),
  CONSTRAINT `menu_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `menu` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of menu
-- ----------------------------
INSERT INTO `menu` VALUES ('9', '权限管理', null, null, '8', '{\"icon\": \"fa fa-folder\", \"visible\": true}');
INSERT INTO `menu` VALUES ('10', '路由', '9', '/admin/route/index', '5', null);
INSERT INTO `menu` VALUES ('11', '权限', '9', '/admin/permission/index', '1', null);
INSERT INTO `menu` VALUES ('12', '角色', '9', '/admin/role/index', '2', null);
INSERT INTO `menu` VALUES ('13', '分配', '9', '/admin/assignment/index', '3', null);
INSERT INTO `menu` VALUES ('14', '菜单', '9', '/admin/menu/index', '4', null);
INSERT INTO `menu` VALUES ('15', '用户管理', '9', '/user/index', null, '{\"icon\": \"fa fa-user\", \"visible\": true}');

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) DEFAULT NULL COMMENT '真实姓名',
  `username` varchar(32) NOT NULL,
  `auth_key` varchar(32) NOT NULL,
  `password_hash` varchar(256) NOT NULL,
  `password_reset_token` varchar(256) DEFAULT NULL,
  `email` varchar(256) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '10',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES ('2', 'admin', 'admin', 'im8kCm12TXRHHJZtMoc7_5z5cT_yDdr8', '$2y$13$YuFSSmVJOkTbVRMnFh.R9egV.TIBAOJ8UJ..GZ5H6q9SdP7qFIFZi', null, 'admin@163.com', '10', '2016', '2016');
INSERT INTO `user` VALUES ('4', 'test12', 'test', 'yn5XZj3cjTJhRBHcackkLhMC1tQopUx1', '$2y$13$t/Bf/QViuKtE9PhyfuV4u.bALlBgldX80BrVvH9Aehe4ClLaaRw5C', null, '70962450@qq.com', '10', '1509807397', '1509807397');


-- ----------------------------
-- 业务表
-- ----------------------------
DROP TABLE IF EXISTS `factory`;
CREATE TABLE `factory` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL COMMENT '厂名称',
  `addr` varchar(255) DEFAULT NULL COMMENT '通信地址',
  `tel` varchar(20) DEFAULT NULL COMMENT '联系电话',
  `person` varchar(50) DEFAULT NULL COMMENT '责任人',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='厂表';

-- 名称（编号） 描述 文档文件（doc pdf ppt） 缩略图 视频文件（mp4）文件大小（50M） 时长  | 时间 上传者
DROP TABLE IF EXISTS `resources`;
CREATE TABLE `resources` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `fid` bigint(20) unsigned NOT NULL COMMENT '厂表id',
  `name` varchar(50) DEFAULT NULL COMMENT '名称',
  `desc` varchar(255) DEFAULT NULL COMMENT '资源描述',
  `suffix` varchar(8) DEFAULT NULL COMMENT '文件后缀',
  `thumb` varchar(255) DEFAULT NULL COMMENT '缩略图',
  `size` integer(5) DEFAULT NULL COMMENT '文件大小(kb)',
  `times` integer(5) DEFAULT NULL COMMENT '时长(秒)',
  `url` varchar(255) DEFAULT NULL COMMENT '文件路径',
  `createTime` int(11) DEFAULT NULL COMMENT '创建时间',
  `createUser` varchar(50) DEFAULT NULL COMMENT '创建者',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='视频资源表';

DROP TABLE IF EXISTS `attachment`;
CREATE TABLE `attachment` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `rid` bigint(20) unsigned NOT NULL COMMENT '资源id',
  `name` varchar(50) DEFAULT NULL COMMENT '名称',
  `desc` varchar(255) DEFAULT NULL COMMENT '资源描述',
  `suffix` varchar(8) DEFAULT NULL COMMENT '文件后缀',
  `size` integer(5) DEFAULT NULL COMMENT '文件大小(kb)',
  `url` varchar(255) DEFAULT NULL COMMENT '文件路径',
  `createTime` int(11) DEFAULT NULL COMMENT '创建时间',
  `createUser` varchar(50) DEFAULT NULL COMMENT '创建者',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='附件表';


DROP TABLE IF EXISTS `checkversion`;
CREATE TABLE `checkversion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `app_id` varchar(5) NOT NULL COMMENT '各产品APP_ID',
  `app_name` varchar(20) NOT NULL COMMENT '各产品APP_名称',
  `client_version` varchar(20) NOT NULL COMMENT '客户端版本号',
  `download_url` varchar(100) NOT NULL COMMENT '升级下载网址',
  `update_id` tinyint(1) NOT NULL DEFAULT '0' COMMENT '版本状态  1:最新版本，0：之前老版本',
  `update_log` varchar(500) DEFAULT '' COMMENT '升级日志',
  `update_install` int(11) NOT NULL DEFAULT '0' COMMENT '是否强制安装',
  `release_time` int(11) NOT NULL DEFAULT '0' COMMENT '发布时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='app升级表';
