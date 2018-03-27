-- --------------------------------------------------------

--
-- 表的结构 `cms_sort`
--

DROP TABLE IF EXISTS `cms_sort`;
CREATE TABLE `cms_sort` (
  `cs_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `cs_name` varchar(32) NOT NULL DEFAULT '' COMMENT '分类名',
  `cs_title` varchar(128) NOT NULL DEFAULT '' COMMENT '分类标题',
  `cs_template` varchar(32) NOT NULL DEFAULT '' COMMENT '分类模板',
  `cs_subindex` int(10) NOT NULL DEFAULT '0' COMMENT '分类下级最小索引',
  `cs_index` int(10) NOT NULL DEFAULT '0' COMMENT '分类自身索引',
  `cs_parent` int(10) NOT NULL DEFAULT '0' COMMENT '分类父级id',
  `cs_order` int(10) NOT NULL DEFAULT '0' COMMENT '分类排序',
  `cs_img` varchar(256) DEFAULT '' COMMENT '分类图片',
  `cs_atime` int(10) DEFAULT NULL COMMENT '创建时间',
  `cs_etime` int(10) DEFAULT NULL COMMENT '修改时间',
  `cs_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否删除,默认为0 0:未删除 1:已删除',
   PRIMARY KEY (`cs_id`),
    KEY `cs_name` (`cs_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='cms分类管理'  AUTO_INCREMENT=1;


DROP TABLE IF EXISTS `cms_tag_group`;
CREATE TABLE `cms_tag_group` (
  `ctg_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ctg_name` varchar(32) NOT NULL DEFAULT '' COMMENT 'TAG分组名',
  `ctg_title` varchar(128) NOT NULL DEFAULT '' COMMENT 'TAG分组标题',
  `ctg_img` varchar(256) DEFAULT '' COMMENT 'TAG分组图片',
  `ctg_atime` int(10) DEFAULT NULL COMMENT '创建时间',
  `ctg_etime` int(10) DEFAULT NULL COMMENT '修改时间',
  `ctg_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否删除,默认为0 0:未删除 1:已删除',
   PRIMARY KEY (`ctg_id`),
    KEY `cs_name` (`ctg_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='TAG分组管理'  AUTO_INCREMENT=1;



DROP TABLE IF EXISTS `cms_tag`;
CREATE TABLE `cms_tag` (
  `ct_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ctg_name` varchar(32) NOT NULL DEFAULT '' COMMENT 'TAG分组名',
  `ct_name` varchar(32) NOT NULL DEFAULT '' COMMENT 'TAG名',
  `ct_title` varchar(128) NOT NULL DEFAULT '' COMMENT 'TAG标题',
  `ct_template` varchar(32) NOT NULL DEFAULT '' COMMENT 'TAG模板',
  `ct_order` int(10) NOT NULL DEFAULT '0' COMMENT 'TAG排序',
  `ct_img` varchar(256) DEFAULT '' COMMENT 'TAG图片',
  `ct_atime` int(10) DEFAULT NULL COMMENT '创建时间',
  `ct_etime` int(10) DEFAULT NULL COMMENT '修改时间',
  `ct_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否删除,默认为0 0:未删除 1:已删除',
   PRIMARY KEY (`ct_id`),
    KEY `cs_name` (`ct_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='TAG管理'  AUTO_INCREMENT=1;