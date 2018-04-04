DROP TABLE IF EXISTS `weixin_conf`;
CREATE TABLE `weixin_conf` (
  `weixin_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `weixin_code` varchar(32) NOT NULL COMMENT 'wxcode',
  `weixin_appid` varchar(128) NOT NULL COMMENT 'AppID',
  `weixin_appsecret` varchar(128) DEFAULT NULL COMMENT 'AppSecret',
  `weixin_token` varchar(128) DEFAULT NULL COMMENT 'Token',
  `weixin_encodingaeskey` varchar(128) DEFAULT NULL COMMENT 'EncodingAESKey',
  `weixin_access_token` varchar(512) DEFAULT NULL COMMENT 'access_token',
  `weixin_jsapi_ticket` varchar(512) DEFAULT NULL COMMENT 'JsApiTicket',
  `expire_time` int(10) DEFAULT '0' COMMENT 'expire_time',
  `jsapi_expire_time` int(10) DEFAULT '0' COMMENT 'jsapi_ticket失效时间',
   PRIMARY KEY (`weixin_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='微信表' AUTO_INCREMENT=1;

--
-- 转存表中的数据 `weixin_conf`
--

INSERT INTO `weixin_conf` (`weixin_id`, `weixin_code`, `weixin_appid`, `weixin_appsecret`, `weixin_token`, `weixin_encodingaeskey`, `weixin_access_token`, `weixin_jsapi_ticket`, `expire_time`, `jsapi_expire_time`) VALUES
(1, 'alanbeibei', 'wxa54aa84375925ca3', '705579f9adb736b0af9d3c0313ac23e5', 'alanbeibei', 'oyYj6t6jr1rSfG88Gb87tNTTahkAQ4531AY6z1MPVfo', '', NULL, 0, 0);

-- --------------------------------------------------------

--
-- 表的结构 `weixin_menu`
--

DROP TABLE IF EXISTS `weixin_oauth2`;
CREATE TABLE `weixin_oauth2` (
  `wo_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `weixin_code` varchar(32) NOT NULL COMMENT 'wxcode',
  `wo_state` int(10) UNSIGNED DEFAULT '0' COMMENT '微信鉴权state',
  `sortwo_redirect_url` varchar(256) DEFAULT NULL COMMENT '回调地址',
  `wo_scope` varchar(64) DEFAULT 'snsapi_base' COMMENT 'snsapi_base snsapi_userinfo',
  `wo_back_url` varchar(256) DEFAULT NULL COMMENT '回调处理后的返回地址',
  `wo_desc` varchar(512) DEFAULT NULL COMMENT '描述',
   PRIMARY KEY (`wo_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='微信鉴权配制表' AUTO_INCREMENT=1;

INSERT INTO `weixin_oauth2` (`weixin_code`, `wo_state`, `sortwo_redirect_url`, `wo_scope`, `wo_back_url`, `wo_desc`) VALUES
('alanbeibei', 1, 'http://www.alanbeibei.com/weixin/oauth2', 'snsapi_base', 'http://www.alanbeibei.com/case', '获到用户openid');
INSERT INTO `weixin_oauth2` (`weixin_code`, `wo_state`, `sortwo_redirect_url`, `wo_scope`, `wo_back_url`, `wo_desc`) VALUES
('alanbeibei', 2, 'http://www.alanbeibei.com/weixin/oauth2', 'snsapi_userinfo', 'http://www.alanbeibei.com/case', '获到用户详细信息');

-- --------------------------------------------------------

--
-- 表的结构 `weixin_menu`
--

DROP TABLE IF EXISTS `weixin_menu`;
CREATE TABLE `weixin_menu` (
  `wm_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `weixin_id` int(10) UNSIGNED DEFAULT '0' COMMENT '所属微信ID',
  `wm_title` varchar(128) NOT NULL COMMENT '菜单标题',
  `wm_type` tinyint(4) DEFAULT '1' COMMENT '0:不启用 1:自动回复　2:跳转 3:关键词',
  `wm_expand` varchar(1024) DEFAULT '' COMMENT '自动回复ID, 自动回复key, 跳转页面, 关键词',
  `wm_order` int(10) DEFAULT '0' COMMENT '菜单排序',
  `wm_parentid` int(10) DEFAULT '0' COMMENT '所属菜单',
  PRIMARY KEY (`wm_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='微信自定义菜单' AUTO_INCREMENT=1;

--
-- 转存表中的数据 `weixin_menu`
--


-- --------------------------------------------------------

--
-- 表的结构 `weixin_responder`
--

DROP TABLE IF EXISTS `weixin_responder`;
CREATE TABLE `weixin_responder` (
  `responder_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `responder_name` varchar(1024) DEFAULT '' COMMENT '应答资源名称',
  `responder_key` varchar(2048) NOT NULL COMMENT '关键词',
  `responder_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '应答类型 0：文本 1:单图文 2:多图文 3:素材 ',
  `responder_content` text COMMENT '应答内容 0：文本',
  `responder_title` varchar(1024) DEFAULT '' COMMENT '应答标题',
  `responder_description` varchar(1024) DEFAULT '' COMMENT '应答描述',
  `responder_picurl` varchar(1024) DEFAULT '' COMMENT '应答图片',
  `responder_url` varchar(1024) DEFAULT '' COMMENT '应答URL',
  `responder_more` text COMMENT '更多数据 2:多图文',
  `responder_materialid` varchar(256) DEFAULT '' COMMENT '素材',
  `responder_count` int(10) DEFAULT '0' COMMENT '应答计数',
  PRIMARY KEY (`responder_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='应答表' AUTO_INCREMENT=1;

--
-- 转存表中的数据 `weixin_responder`
--

-- --------------------------------------------------------

--
-- 表的结构 `weixin_scene`
--

DROP TABLE IF EXISTS `weixin_scene`;
CREATE TABLE `weixin_scene` (
  `scene_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `responder_id` int(10) UNSIGNED DEFAULT '0' COMMENT '回复ID',
  `scene_name` varchar(256) DEFAULT '' COMMENT '标题',
  `scene_sn` int(10) DEFAULT '0' COMMENT '1-10000',
  `qrcode_token` varchar(128) DEFAULT '' COMMENT 'token',
  `scene_count` int(10) DEFAULT '0' COMMENT '关注次数',
  `scene_scan` int(10) DEFAULT '0' COMMENT '扫码次数',
  `scene_people` int(10) NOT NULL DEFAULT '0' COMMENT '扫码人数',
  `scene_logo` varchar(256) DEFAULT NULL COMMENT 'logo地址',
  `scene_qrcode` varchar(256) DEFAULT NULL COMMENT '自定义二维码地址',
  PRIMARY KEY (`scene_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='场景表' AUTO_INCREMENT=1;

-- --------------------------------------------------------

--
-- 表的结构 `weixin_scene_log`
--

DROP TABLE IF EXISTS `weixin_scene_log`;
CREATE TABLE `weixin_scene_log` (
  `sl_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `scene_sn` int(10) DEFAULT '0' COMMENT '1-10000',
  `sl_type` tinyint(1) DEFAULT '1' COMMENT '1:关注  2:扫码',
  `open_id` varchar(128) DEFAULT '' COMMENT '用户ID',
  `sl_year` char(4) DEFAULT '' COMMENT '年',
  `sl_month` char(2) DEFAULT '' COMMENT '月',
  `sl_week` char(6) DEFAULT NULL COMMENT '周',
  `sl_day` char(2) DEFAULT '' COMMENT '日',
  `sl_time` int(10) DEFAULT '0' COMMENT '扫码时间',
  PRIMARY KEY (`sl_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='场景扫码日志表' AUTO_INCREMENT=1;
