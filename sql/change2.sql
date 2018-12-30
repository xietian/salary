SET NAMES utf8mb4;

DROP TABLE IF EXISTS `resources`;
CREATE TABLE `resources` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL COMMENT '名称',
  `desc` varchar(500) NOT NULL DEFAULT '' COMMENT '资源描述',
  `suffix` varchar(8) NOT NULL DEFAULT '' COMMENT '文件后缀',
  `thumb` varchar(500) NOT NULL DEFAULT '' COMMENT '缩略图',
  `size` int(5) NOT NULL DEFAULT '0' COMMENT '文件大小(kb)',
  `duration` float NOT NULL DEFAULT '0' COMMENT '时长(秒)',
  `url` varchar(500) NOT NULL DEFAULT '' COMMENT '文件路径',
  `third_resource_id` varchar(32) NOT NULL DEFAULT '' COMMENT '第三方video编号',
  `convert_status` varchar(10) NOT NULL COMMENT '转码状态',
  `created_at` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `creator_id` int(11) NOT NULL DEFAULT '0' COMMENT '创建者',
  `visit_num` int(11) NOT NULL DEFAULT '0' COMMENT '播放量',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='视频资源表';


DROP TABLE IF EXISTS `resources_factory`;
CREATE TABLE `resources_factory` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `resource_id` int(11) NOT NULL COMMENT '资源编号',
  `dept_id` int(11) NOT NULL COMMENT '工厂编号',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='资源所属的工厂';