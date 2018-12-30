SET NAMES utf8mb4;

DROP TABLE IF EXISTS `factory`;
CREATE TABLE `factory` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '编号',
  `name` varchar(50) NOT NULL COMMENT '厂名称',
  `addr` varchar(255) NOT NULL DEFAULT '' COMMENT '通信地址',
  `tel` varchar(20) NOT NULL DEFAULT '' COMMENT '联系电话',
  `person` varchar(50) NOT NULL DEFAULT '' COMMENT '责任人',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='厂表';


DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL DEFAULT '' COMMENT '姓名',
  `sex` tinyint(4) DEFAULT NULL COMMENT '性别',
  `username` varchar(32) NOT NULL COMMENT '用户名',
  `auth_key` varchar(32) NOT NULL,
  `password_hash` varchar(256) NOT NULL COMMENT '密码',
  `password_reset_token` varchar(256) DEFAULT NULL,
  `email` varchar(256) NOT NULL COMMENT '邮箱',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态',
  `created_at` int(11) NOT NULL COMMENT '创建时间',
  `updated_at` int(11) NOT NULL COMMENT '更新时间',
  `dept_id` int(11) NOT NULL DEFAULT '0' COMMENT '工厂编号',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `version`;
CREATE TABLE `version` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `version_code` varchar(20) NOT NULL COMMENT '版本号',
  `version_content` varchar(500) DEFAULT '' COMMENT '升级日志',
  `download_url` varchar(100) NOT NULL COMMENT '下载地址',
  `file_size` int(11) NOT NULL DEFAULT '0' COMMENT '文件大小',
  `is_latest` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否最新版本',
  `is_force` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否强制安装',
  `release_time` int(11) NOT NULL DEFAULT '0' COMMENT '发布时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='app升级表';