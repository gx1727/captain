-- --------------------------------------------------------

--
-- 表的结构 `xx_user`
--

DROP TABLE IF EXISTS `xx_user`;
CREATE TABLE `xx_user` (
  `user_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_code` varchar(32) NOT NULL COMMENT '用户编码',
  `user_name` varchar(64) DEFAULT '' COMMENT '用户名',
  `user_title` varchar(64) DEFAULT '' COMMENT '用户显示名',
  `user_true_name` varchar(64) DEFAULT NULL COMMENT '用户真实姓名',
  `user_phone` varchar(32) DEFAULT NULL COMMENT '用户手机号',
  `user_email` varchar(64) DEFAULT NULL COMMENT '用户邮箱',
  `user_wxopenid` varchar(64) DEFAULT NULL COMMENT '微信openid',
  `user_photo` varchar(128) DEFAULT NULL COMMENT '用户头像',
  `user_pwd` varchar(64) DEFAULT '' COMMENT '密码',
  `user_atime` int(10) DEFAULT NULL COMMENT '创建时间',
  `user_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否删除,默认为0 0:未删除 1:已删除',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `user_code_2` (`user_code`),
  KEY `user_code` (`user_code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户表' AUTO_INCREMENT=1;

--
-- 转存表中的数据 `xx_user`
--

INSERT INTO `xx_user` (`user_code`, `user_name`, `user_title`, `user_true_name`, `user_phone`, `user_email`, `user_wxopenid`, `user_photo`, `user_pwd`, `user_atime`, `user_status`) VALUES
('U0000000001', 'admin', 'admin', NULL, '', '', 'admin', '', '26a3f53a27128edb1e28ad3047102461', 0, 0);


DROP TABLE IF EXISTS `xx_role`;
CREATE TABLE `xx_role` (
  `role_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `role_code` varchar(32) NOT NULL COMMENT '角色编码',
  `role_name` varchar(64) NOT NULL COMMENT '角色名称',
  `role_title` varchar(64) NOT NULL COMMENT '角色标题',
  `role_extend` varchar(32) DEFAULT NULL COMMENT '父级角色',
  `role_menu` int(10) DEFAULT '0' COMMENT '角色使用的菜单',
  `role_homeurl` varchar(128) NOT NULL DEFAULT '' COMMENT '角色主页',
  `role_template` varchar(32) DEFAULT NULL COMMENT ' 角色显示模板',
  `role_remark` varchar(256) DEFAULT NULL COMMENT '角色说明',
  PRIMARY KEY (`role_id`),
  UNIQUE KEY `role_code` (`role_code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='角色表' AUTO_INCREMENT=1;

--
-- 转存表中的数据 `xx_role`
--

INSERT INTO `xx_role` (`role_id`, `role_code`, `role_name`, role_title,`role_extend`, `role_menu`, `role_homeurl`, `role_template`, `role_remark`) VALUES
(1, 'ROLE00001', 'admin', 'admin权限', NULL, 2, 'admin_home', 'admin/', 'admin权限'),
(2, 'ROLE00002', 'default', '游客角色', NULL, 3, 'default_home', 'default/', '最基础的访问权限'),
(3, 'ROLE00003', 'manager', '管理员', NULL, 4, 'manager_home', 'admin/', '系统管理员'),
(4, 'ROLE00004', 'user', '普通会员角色', NULL, 5, 'user_home', 'default/', '普通会员');



-- --------------------------------------------------------

--
-- 表的结构 `xx_user_role`
--

DROP TABLE IF EXISTS `xx_user_role`;
CREATE TABLE `xx_user_role` (
  `ur_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_code` varchar(32) NOT NULL COMMENT '用户编码',
  `ur_role_code` varchar(32) NOT NULL COMMENT '角色编码',
  `ur_failure_time` int(10) DEFAULT NULL COMMENT '失效时间',
  `ur_bench_role` varchar(32) NOT NULL COMMENT '后补角色编码',
  `ur_remark` varchar(256) DEFAULT NULL COMMENT '角色说明',
  `ur_atime` int(10) DEFAULT NULL COMMENT '创建时间',
  `ur_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否删除,默认为0 0:未删除 1:已删除',
  PRIMARY KEY (`ur_id`),
  KEY `user_code` (`user_code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户角色表' AUTO_INCREMENT=1;

--
-- 转存表中的数据 `xx_user_role`
--

INSERT INTO `xx_user_role` (`user_code`, `ur_role_code`, `ur_failure_time`, `ur_bench_role`, `ur_remark`, `ur_atime`, `ur_status`) VALUES
('U0000000001', 'ROLE00001', 0, 'ROLE00001', NULL, 1445417117, 0);


-- --------------------------------------------------------

--
-- 表的结构 `xx_user_weixin`
--

DROP TABLE IF EXISTS `xx_user_weixin`;
CREATE TABLE `xx_user_weixin` (
  `uw_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_code` varchar(32) NOT NULL COMMENT '用户编码',
  `subscribe` tinyint(2) DEFAULT '0' COMMENT '是否观注',
  `openid` varchar(64) DEFAULT NULL COMMENT '公众号ID',
  `nickname` varchar(256) DEFAULT NULL COMMENT '名称',
  `sex` tinyint(2) DEFAULT '0' COMMENT '性别',
  `city` varchar(64) DEFAULT NULL COMMENT '市',
  `province` varchar(64) DEFAULT NULL COMMENT '省',
  `country` varchar(64) DEFAULT NULL COMMENT '国家',
  `headimgurl` varchar(1024) DEFAULT NULL COMMENT '头像',
  `subscribe_time` int(10) DEFAULT NULL COMMENT '关注时间',
  `unionid` varchar(64) DEFAULT NULL COMMENT '市',
  `uw_atime` int(10) DEFAULT NULL COMMENT '创建时间',
  `uw_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否删除,默认为0 0:未删除 1:已删除',
  PRIMARY KEY (`uw_id`),
  KEY `user_code` (`user_code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户观注微信公众号表' AUTO_INCREMENT=1;


-- --------------------------------------------------------

--
-- 帐户类型表
-- 表的结构 `xx_account`
--

DROP TABLE IF EXISTS `xx_account`;
CREATE TABLE `xx_account` (
  `account_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `account_name` varchar(32) DEFAULT '' COMMENT '帐户类型名',
  `account_code` varchar(32) DEFAULT '' COMMENT '帐户类型编号',
  `account_type` int(10) DEFAULT 1 COMMENT '1:int(11)  2:decimal(10,2)',
  `account_atime` int(10) DEFAULT NULL COMMENT '创建时间',
  `account_etime` int(10) DEFAULT NULL COMMENT '删除时间',
  `account_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否删除,默认为0 0:未删除 1:已删除',
  PRIMARY KEY (`account_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='帐户类型表' AUTO_INCREMENT=1;



-- --------------------------------------------------------



--
-- 表的结构 `xx_menu`
--

DROP TABLE IF EXISTS `xx_menu`;
CREATE TABLE `xx_menu` (
  `menu_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `menu_title` varchar(64) NOT NULL COMMENT '目录标题',
  `menu_pinyin` varchar(64) DEFAULT NULL COMMENT '目录标题拼音',
  `menu_href` varchar(128) NOT NULL DEFAULT '/' COMMENT '目录URL',
  `menu_subs` tinyint(1) DEFAULT '0' COMMENT '是否有子菜单 0:没有 >1:子菜单个数',
  `menu_icon` varchar(32) DEFAULT NULL COMMENT '样式',
  `menu_parent` int(11) DEFAULT NULL COMMENT '父菜单',
  `menu_subindex` int(11) NOT NULL DEFAULT '0' COMMENT '最小序号',
  `menu_index` int(11) NOT NULL DEFAULT '0' COMMENT '自身序号',
  `menu_order` int(11) NOT NULL DEFAULT '1024' COMMENT '排序',
  PRIMARY KEY (`menu_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='目录表' AUTO_INCREMENT=1;


INSERT INTO `xx_menu` (`menu_id`, `menu_title`, `menu_pinyin`, `menu_href`, `menu_subs`, `menu_icon`, `menu_parent`, `menu_subindex`, `menu_index`, `menu_order`) VALUES
(1, 'root', 'root', '/', 0, '', 0, 1, 1, 1),
(2, 'admin权限', '', '', 0, '', 1, 0, 0, 99999),
(3, '游客角色', '', '', 0, '', 1, 0, 0, 99999),
(4, '管理员', '', '', 0, '', 1, 0, 0, 99999),
(5, '普通会员角色', '', '', 0, '', 1, 0, 0, 99999),
(6, '系统设置', '', 'sys', 0, '', 2, 0, 0, 0),
(7, '菜单管理', '', 'auth_menu', 0, '', 6, 0, 0, 0),
(8, '角色管理', '', 'auth_role', 0, '', 6, 0, 0, 0),
(9, '文章管理', '', 'cms', 0, '', 2, 0, 0, 0),
(10, '文章分类', '', 'cms_sort', 0, '', 9, 0, 0, 0),
(11, '文章TAG分组', '', 'cms_tag_group', 0, '', 9, 0, 0, 0),
(12, '文章TAG', '', 'cms_tag', 0, '', 9, 0, 0, 0),
(13, '文章列表', '', 'cms_article_list', 0, '', 9, 0, 0, 0);




-- --------------------------------------------------------



--
-- 附件
-- 表的结构 `xx_attachment`
--

DROP TABLE IF EXISTS `xx_attachment`;
CREATE TABLE `xx_attachment` (
  `att_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_code` varchar(32) DEFAULT '' COMMENT '附件所属用户编码',
  `att_filename` varchar(64) NOT NULL COMMENT '附件标题',
  `att_originalname` varchar(256) DEFAULT NULL COMMENT '附件原始名',
  `att_class` tinyint(1) NOT NULL DEFAULT '1' COMMENT '附件分类 1：图片 2：txt文本 ',
  `att_type` varchar(32) NOT NULL DEFAULT '' COMMENT '附件类型 ',
  `att_size` int(11) NOT NULL DEFAULT '0' COMMENT '附件大小',
  `att_domain` varchar(128) DEFAULT NULL COMMENT '附件所在域名',
  `att_filepath` varchar(128) DEFAULT NULL COMMENT '附件全路径',
  `att_cdn` varchar(128) DEFAULT NULL COMMENT '附件cdn地址',
  `att_thumbnail` varchar(128) DEFAULT NULL COMMENT '附件缩略图地址',
  `att_tag` varchar(62) DEFAULT NULL COMMENT '附件tag',
  `att_atime` int(10) DEFAULT NULL COMMENT '创建时间',
  `att_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否删除,默认为0 0:未删除 1:已删除',
  PRIMARY KEY (`att_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='附件表' AUTO_INCREMENT=1;