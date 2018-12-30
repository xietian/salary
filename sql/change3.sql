

SET NAMES utf8mb4;

DROP TABLE IF EXISTS `attachment`;
CREATE TABLE `attachment` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `rid` bigint(20) unsigned NOT NULL COMMENT '资源id',
  `name` varchar(50) DEFAULT NULL COMMENT '名称',
  `desc` varchar(255) DEFAULT NULL COMMENT '资源描述',
  `suffix` varchar(8) DEFAULT NULL COMMENT '文件后缀',
  `size` int(5) DEFAULT NULL COMMENT '文件大小(kb)',
  `url` varchar(255) DEFAULT NULL COMMENT '文件路径',
  `created_at` int(11) DEFAULT NULL COMMENT '创建时间',
  `creator_id` int(11) DEFAULT NULL COMMENT '创建者',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='附件表';


-- 2018-11-25 07:12:24