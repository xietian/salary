ALTER TABLE `user`
ADD `sex` tinyint NULL default 0 COMMENT '性别' AFTER `name` ,
ADD `dept_id` int NOT NULL  default 0 COMMENT '工厂编号' ;