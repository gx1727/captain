
--
-- 表的结构 `xx_sessions`
--

DROP TABLE IF EXISTS `xx_sessions`;
CREATE TABLE `xx_sessions` (
  `session_id` varchar(40) NOT NULL DEFAULT '0',
  `ip_address` varchar(45) NOT NULL DEFAULT '0',
  `create_time` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `last_activity` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `user_data` varchar(1024) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='系统sexxion表';


--
-- Indexes for table `xx_sessions`
--
ALTER TABLE `xx_sessions`
  ADD PRIMARY KEY (`session_id`),
  ADD KEY `last_activity_idx` (`last_activity`);



-- --------------------------------------------------------

--
-- 表的结构 `xx_code_definition`
-- [{"index":"1","type":"PREFIX","value":"TEST"},{"index":"2","type":"SYMBOL","value":"_"},{"index":"3","type":"USERNDATA"},{"index":"4","type":"SYMBOL","value":"-"},{"index":"5","type":"USERNDATA"},{"index":"6","type":"SYMBOL","value":"_"},{"index":"7","type":"TIMES","value":"YYYYMMDD"},{"index":"8","type":"SYMBOL","value":"_"},{"index":"9","type":"SERIALNUMBER","loop":"NONE","length":"4"},{"index":"10","type":"SYMBOL","value":"-"},{"index":"11","type":"SERIALNUMBER","loop":"YYYYMMDD","length":"5"}]
--

DROP TABLE IF EXISTS `xx_code_definition`;
CREATE TABLE `xx_code_definition` (
  `cd_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `cd_name` varchar(64) NOT NULL COMMENT '编码名称',
  `cd_title` varchar(64) NOT NULL COMMENT '编码标题',
  `cd_type` tinyint(2) DEFAULT NULL COMMENT '编码类型 1:流水编码 2:检索编码',
  `cd_level` tinyint(2) DEFAULT '0' COMMENT '编码等级 0:系统编码 其它：用户编码',
  `cd_template` varchar(128) DEFAULT NULL COMMENT '编码模块 直观显示',
  `cd_attributeset` varchar(2048) DEFAULT NULL COMMENT '子属性值位，json数据型式',
  `cd_remark` varchar(256) DEFAULT NULL COMMENT '说明',
  `cd_status` tinyint(2) DEFAULT '0' COMMENT '状态 0:未使用  1:已使用',
  `cd_auto_create_flag` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否自动生成编码',
  PRIMARY KEY (`cd_id`),
  KEY `cd_name` (`cd_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='编码定义表' AUTO_INCREMENT=1 ;

INSERT INTO `xx_code_definition` (`cd_name`, `cd_title`, `cd_type`, `cd_level`, `cd_template`, `cd_attributeset`, `cd_remark`, `cd_status`, `cd_auto_create_flag`) VALUES
( 'USERCODE', '用户编码', 1, 0, 'U0000000001', '[{\"index\":\"1\",\"type\":\"PREFIX\",\"value\":\"U\"},{\"index\":\"2\",\"type\":\"SERIALNUMBER\",\"loop\":\"NONE\",\"length\":\"10\"}]', '系统中用户的唯一编码', 1, 1);


INSERT INTO `xx_code_definition` (`cd_name`, `cd_title`, `cd_type`, `cd_level`, `cd_template`, `cd_attributeset`, `cd_remark`, `cd_status`, `cd_auto_create_flag`) VALUES
( 'USERCODE2', '用户编码2', 1, 0, 'U0000000001', '[{\"index\":\"1\",\"type\":\"PREFIX\",\"value\":\"U\"},{\"index\":\"2\",\"type\":\"ENCODEID\",\"loop\":\"NONE\",\"length\":\"10\"}]', '系统中用户的唯一编码', 1, 1);

-- --------------------------------------------------------

--
-- 表的结构 `xx_code_info`
--

DROP TABLE IF EXISTS `xx_code_info`;
CREATE TABLE `xx_code_info` (
  `ci_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `cd_name` varchar(64) NOT NULL COMMENT '编码名称',
  `ci_usercode` varchar(64) NOT NULL COMMENT '用户编码',
  `ci_serial_flag` varchar(1024) DEFAULT NULL COMMENT '流水回归标识 json数组',
  `ci_serial_value` varchar(1024) DEFAULT NULL COMMENT '流水当前值 json数组',
  PRIMARY KEY (`ci_id`),
  KEY `cd_name` (`cd_name`,`ci_usercode`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='编码值表' AUTO_INCREMENT=1 ;




-- --------------------------------------------------------

--
-- 表的结构 `xx_extend`
--

DROP TABLE IF EXISTS `xx_extend`;
CREATE TABLE `xx_extend` (
  `e_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `foreign_id` int(10) NOT NULL COMMENT '关联ID',
  `e_class` varchar(128) NOT NULL COMMENT '扩展信息分类',
  `e_sort` varchar(128) NOT NULL COMMENT '扩展信息分组',
  `e_title` varchar(128) NOT NULL COMMENT '扩展名称',
  `e_atime` int(10) DEFAULT '0' COMMENT '插入时间',
  `e_etime` int(10) DEFAULT '0' COMMENT '修改时间',
  `e_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否删除,默认为0 0:未删除 1:已删除',
  `e_data` text COMMENT '内容',
  PRIMARY KEY (`e_id`),
  KEY `e_class` (`e_class`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='扩展信息表' AUTO_INCREMENT=1 ;