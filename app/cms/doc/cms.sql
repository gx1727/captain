-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 2018-05-03 15:20:10
-- 服务器版本： 5.6.16
-- PHP Version: 7.1.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Database: `01rv`
--

-- --------------------------------------------------------

--
-- 表的结构 `cms_article`
--

DROP TABLE IF EXISTS `cms_article`;
CREATE TABLE `cms_article` (
  `a_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_code` varchar(32) NOT NULL DEFAULT '' COMMENT '用户编码',
  `a_code` int(10) DEFAULT '0' COMMENT '文章编辑',
  `a_title` varchar(512) NOT NULL DEFAULT '' COMMENT '标题',
  `a_img` varchar(256) NOT NULL DEFAULT '' COMMENT '文章图',
  `a_abstract` varchar(1024) NOT NULL DEFAULT '' COMMENT '摘要',
  `a_content` text COMMENT '内容',
  `a_atime` int(10) DEFAULT '0' COMMENT '创建时间',
  `a_etime` int(10) DEFAULT '0' COMMENT '修改时间',
  `a_ptime` int(10) DEFAULT '0' COMMENT '文章第一次发布的时间',
  `a_count` int(10) DEFAULT '0' COMMENT '查看次数',
  `a_extended` varchar(256) DEFAULT '' COMMENT '扩展参数',
  `a_publish_time` int(10) DEFAULT '0' COMMENT '发布时间',
  `a_flag` int(10) DEFAULT '0' COMMENT '文章标志',
  `a_template` varchar(32) NOT NULL DEFAULT '' COMMENT '文章模板',
  `a_status` tinyint(1) NOT NULL DEFAULT '3' COMMENT '是否删除,默认为0 0:删除 1:显示 2:未发布，不显示 3:草稿中',
  PRIMARY KEY (`a_id`),
  KEY `a_code` (`a_code`),
  KEY `a_ptime` (`a_ptime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='文章表' AUTO_INCREMENT=1;

-- --------------------------------------------------------

--
-- 表的结构 `cms_article_draft`
--

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
  `a_flag` int(10) DEFAULT '0' COMMENT '文章标志',
  `a_template` varchar(32) NOT NULL DEFAULT '' COMMENT '文章模板',
   PRIMARY KEY (`a_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='文章草稿表';

-- --------------------------------------------------------

--
-- 表的结构 `cms_article_sort`
--

DROP TABLE IF EXISTS `cms_article_sort`;
CREATE TABLE `cms_article_sort` (
  `as_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `cs_title` varchar(128) NOT NULL DEFAULT '' COMMENT '分类标题',
  `cs_name` varchar(64) NOT NULL DEFAULT '' COMMENT '分类名称',
  `a_id` int(10) NOT NULL DEFAULT '0' COMMENT '文章ID',
  `as_order` int(10) NOT NULL DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`as_id`),
  KEY `cms_article_sort_cs_name` (`cs_name`),
  KEY `cms_article_sort_a_id` (`a_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='文章分类关系';

-- --------------------------------------------------------

--
-- 表的结构 `cms_article_tag`
--

DROP TABLE IF EXISTS `cms_article_tag`;
CREATE TABLE `cms_article_tag` (
  `at_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ct_title` varchar(128) NOT NULL DEFAULT '' COMMENT 'tag标题',
  `ct_name` varchar(64) NOT NULL DEFAULT '' COMMENT 'tag名称',
  `a_id` int(10) NOT NULL DEFAULT '0' COMMENT '文章ID',
  `at_order` int(10) NOT NULL DEFAULT '0' COMMENT '排序',
   PRIMARY KEY (`at_id`),
   KEY `cms_article_tag_ct_name` (`ct_name`),
   KEY `cms_article_tag_a_id` (`a_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='文章tag关系';

-- --------------------------------------------------------

--
-- 表的结构 `cms_sort`
--

DROP TABLE IF EXISTS `cms_sort`;
CREATE TABLE `cms_sort` (
  `cs_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `cs_name` varchar(32) NOT NULL DEFAULT '' COMMENT '分类名',
  `cs_title` varchar(128) NOT NULL DEFAULT '' COMMENT '分类标题',
  `cs_article_template` varchar(32) NOT NULL DEFAULT '' COMMENT '分类文章模板',
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='cms分类管理';

-- --------------------------------------------------------

--
-- 表的结构 `cms_special`
--

DROP TABLE IF EXISTS `cms_special`;
CREATE TABLE `cms_special` (
  `s_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `s_name` varchar(64) DEFAULT '' COMMENT '专题名',
  `s_title` varchar(128) DEFAULT '' COMMENT '专题标题',
  `s_img` varchar(256) DEFAULT '' COMMENT '专题图片路径',
  `s_abstract` varchar(1024) DEFAULT '' COMMENT '专题摘要',
  `s_content` text COMMENT '专题内容 ',
  `s_extended` varchar(256) DEFAULT '' COMMENT '专题扩展参数',
  `s_template` varchar(64) DEFAULT '' COMMENT '专题模板',
  `s_flag` int(10) DEFAULT '0' COMMENT '专题标志',
  `s_atime` int(10) DEFAULT '0' COMMENT '专题创建时间',
  `s_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否删除,默认为0 0:正常 1:已删除',
   PRIMARY KEY (`s_id`),
   KEY `s_name` (`s_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='模板表';

-- --------------------------------------------------------

--
-- 表的结构 `cms_tag`
--

DROP TABLE IF EXISTS `cms_tag`;
CREATE TABLE `cms_tag` (
  `ct_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ctg_name` varchar(32) NOT NULL DEFAULT '' COMMENT 'TAG分组名',
  `ct_name` varchar(32) NOT NULL DEFAULT '' COMMENT 'TAG名',
  `ct_title` varchar(128) NOT NULL DEFAULT '' COMMENT 'TAG标题',
  `ct_template` varchar(32) NOT NULL DEFAULT '' COMMENT 'TAG模板',
  `ct_order` int(10) NOT NULL DEFAULT '0' COMMENT 'TAG排序',
  `ct_img` varchar(256) DEFAULT '' COMMENT 'TAG图片',
  `ct_parent` varchar(32) DEFAULT '' COMMENT '上级TAG',
  `ct_child` int(10) DEFAULT '0' COMMENT '下级个数',
  `ct_atime` int(10) DEFAULT NULL COMMENT '创建时间',
  `ct_etime` int(10) DEFAULT NULL COMMENT '修改时间',
  `ct_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否删除,默认为0 0:未删除 1:已删除',
   PRIMARY KEY (`ct_id`),
   KEY `cms_tag_ctg_name` (`ctg_name`),
   KEY `cms_tag_ct_name` (`ct_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='TAG管理';

-- --------------------------------------------------------

--
-- 表的结构 `cms_tag_group`
--

DROP TABLE IF EXISTS `cms_tag_group`;
CREATE TABLE `cms_tag_group` (
  `ctg_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ctg_type` int(10) NOT NULL DEFAULT '0' COMMENT 'TAG分组类型 0:常规 1:文章TAG 2:车型库 3:营地 4:功能性',
  `ctg_name` varchar(32) NOT NULL DEFAULT '' COMMENT 'TAG分组名',
  `ctg_title` varchar(128) NOT NULL DEFAULT '' COMMENT 'TAG分组标题',
  `ctg_img` varchar(256) DEFAULT '' COMMENT 'TAG分组图片',
  `ctg_atime` int(10) DEFAULT '0' COMMENT '创建时间',
  `ctg_etime` int(10) DEFAULT '0' COMMENT '修改时间',
  `ctg_order` int(10) DEFAULT '0' COMMENT '排序',
  `ctg_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否删除,默认为0 0:未删除 1:已删除',
   PRIMARY KEY (`ctg_id`),
   KEY `cms_tag_group_ctg_type` (`ctg_type`),
   KEY `cms_tag_group_ctg_name` (`ctg_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='TAG分组管理';

-- --------------------------------------------------------

--
-- 表的结构 `cms_template`
--

DROP TABLE IF EXISTS `cms_template`;
CREATE TABLE `cms_template` (
  `t_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `t_name` varchar(128) DEFAULT '' COMMENT '模板名称',
  `t_title` varchar(128) DEFAULT '' COMMENT '模板标题',
  `t_type` char(1) DEFAULT 'T' COMMENT '模板种类 T:显示模板 J:js文件 S:style样式文件',
  `t_class` char(1) DEFAULT 'A' COMMENT '模板分类 A:文章 L:列表 S:专有',
  `t_path` varchar(256) DEFAULT '' COMMENT '模板路径',
  `t_des` varchar(1024) DEFAULT '' COMMENT '模板描述',
  `t_content` text COMMENT '模板内容',
  `t_atime` int(10) DEFAULT '0' COMMENT '模板时间',
  `t_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否删除,默认为0 0:正常 1:已删除 2:备份',
   PRIMARY KEY (`t_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='模板表';
