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
  `ctg_type` int(10) NOT NULL DEFAULT 0 COMMENT 'TAG分组类型 0:常规 1:文章TAG 2:车型库 3:营地 4:功能性',
  `ctg_name` varchar(32) NOT NULL DEFAULT '' COMMENT 'TAG分组名',
  `ctg_title` varchar(128) NOT NULL DEFAULT '' COMMENT 'TAG分组标题',
  `ctg_img` varchar(256) DEFAULT '' COMMENT 'TAG分组图片',
  `ctg_atime` int(10) DEFAULT 0 COMMENT '创建时间',
  `ctg_etime` int(10) DEFAULT 0 COMMENT '修改时间',
  `ctg_order` int(10) DEFAULT 0 COMMENT '排序',
  `ctg_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否删除,默认为0 0:未删除 1:已删除',
   PRIMARY KEY (`ctg_id`),
   KEY `cms_tag_group_ctg_type` (`ctg_type`),
    KEY `cms_tag_group_ctg_name` (`ctg_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='TAG分组管理'  AUTO_INCREMENT=1;

INSERT INTO `cms_tag_group` (`ctg_id`, `ctg_type`, `ctg_name`, `ctg_title`, `ctg_img`, `ctg_atime`, `ctg_etime`, `ctg_status`) VALUES
(null, 0, 'common', '常规', '', 0, 0, 0);



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
   KEY `cms_tag_ctg_name` (`ctg_name`),
    KEY `cms_tag_ct_name` (`ct_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='TAG管理'  AUTO_INCREMENT=1;



--
-- 表的结构 `cms_content`
--

DROP TABLE IF EXISTS `cms_article`;
CREATE TABLE `cms_article` (
  `a_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_code` varchar(32) NOT NULL DEFAULT '' COMMENT '用户编码',
  `a_title` varchar(512) NOT NULL DEFAULT '' COMMENT '标题',
  `a_img` varchar(128) NOT NULL DEFAULT '' COMMENT '文章图',
  `a_abstract` varchar(1024) NOT NULL DEFAULT '' COMMENT '摘要',
  `a_content` text COMMENT '内容',
  `a_atime` int(10) DEFAULT '0' COMMENT '创建时间',
  `a_etime` int(10) DEFAULT '0' COMMENT '修改时间',
  `a_count` int(10) DEFAULT '0' COMMENT '查看次数',
  `a_extended` varchar(256) DEFAULT '' COMMENT '扩展参数',
  `a_publish_time` int(10) DEFAULT '0' COMMENT '发布时间',
  `a_recommend` tinyint(1) DEFAULT '0' COMMENT '是否推荐',
  `a_template` varchar(32) NOT NULL DEFAULT '' COMMENT '文章模板',
  `a_status` tinyint(1) NOT NULL DEFAULT '3' COMMENT '是否删除,默认为0 0:删除 1:显示 2:未发布，不显示 3:草稿中',
   PRIMARY KEY (`a_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='文章表';

DROP TABLE IF EXISTS `cms_article_draft`;
CREATE TABLE `cms_article_draft` (
  `a_id` int(10) UNSIGNED NOT NULL,
  `user_code` varchar(32) NOT NULL DEFAULT '' COMMENT '用户编码',
  `a_title` varchar(512) NOT NULL DEFAULT '' COMMENT '标题',
  `a_img` varchar(128) NOT NULL DEFAULT '' COMMENT '文章图',
  `a_abstract` varchar(1024) NOT NULL DEFAULT '' COMMENT '摘要',
  `a_content` text COMMENT '内容',
  `a_atime` int(10) DEFAULT '0' COMMENT '创建时间',
  `a_etime` int(10) DEFAULT '0' COMMENT '修改时间',
  `a_extended` varchar(256) DEFAULT '' COMMENT '扩展参数',
  `a_recommend` tinyint(1) DEFAULT '0' COMMENT '是否推荐',
  `a_template` varchar(32) NOT NULL DEFAULT '' COMMENT '文章模板',
   PRIMARY KEY (`a_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='文章草稿表';


DROP TABLE IF EXISTS `cms_article_tag`;
CREATE TABLE `cms_article_tag` (
  `at_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ct_title` varchar(128) NOT NULL DEFAULT '' COMMENT 'tag标题',
  `ct_name` varchar(64) NOT NULL DEFAULT '' COMMENT 'tag名称',
  `a_id` int(10) NOT NULL DEFAULT '0' COMMENT '文章ID',
  `at_order` int(10) NOT NULL DEFAULT '0' COMMENT '排序',
   PRIMARY KEY (`at_id`),
   KEY cms_article_tag_ct_name (`ct_name`),
   KEY cms_article_tag_a_id (`a_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='文章tag关系';


DROP TABLE IF EXISTS `cms_article_sort`;
CREATE TABLE `cms_article_sort` (
  `as_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `cs_title` varchar(128) NOT NULL DEFAULT '' COMMENT '分类标题',
  `cs_name` varchar(64)  NOT NULL DEFAULT '' COMMENT '分类名称',
  `a_id` int(10) NOT NULL DEFAULT '0' COMMENT '文章ID',
  `as_order` int(10) NOT NULL DEFAULT '0' COMMENT '排序',
   PRIMARY KEY (`as_id`),
   KEY cms_article_sort_cs_name (`cs_name`),
   KEY cms_article_sort_a_id (`a_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='文章分类关系';