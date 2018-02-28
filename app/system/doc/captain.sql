-- --------------------------------------------------------

--
-- 表的结构 `xx_user`
--

DROP TABLE IF EXISTS `xx_user`;
CREATE TABLE `xx_user` (
  `user_id` int(10) UNSIGNED NOT NULL,
  `user_code` varchar(32) NOT NULL COMMENT '用户编码',
  `user_name` varchar(64) NOT NULL COMMENT '用户名',
  `user_true_name` varchar(64) DEFAULT NULL COMMENT '用户真实姓名',
  `user_phone` varchar(32) DEFAULT NULL COMMENT '用户手机号',
  `user_email` varchar(64) DEFAULT NULL COMMENT '用户邮箱',
  `user_qq` varchar(32) DEFAULT NULL COMMENT '用户QQ号',
  `user_wxopenid` varchar(64) DEFAULT NULL COMMENT '微信openid',
  `user_photo` varchar(128) DEFAULT NULL COMMENT '用户头像',
  `user_pwd` varchar(64) NOT NULL COMMENT '密码',
  `user_level` int(10) DEFAULT '1' COMMENT '用户等级',
  `user_atime` int(10) DEFAULT NULL COMMENT '创建时间',
  `user_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否删除,默认为0 0:未删除 1:已删除'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户表';

--
-- 转存表中的数据 `xx_user`
--

INSERT INTO `xx_user` (`user_id`, `user_code`, `user_name`, `user_true_name`, `user_phone`, `user_email`, `user_qq`, `user_wxopenid`, `user_photo`, `user_pwd`, `user_level`, `user_atime`, `user_status`) VALUES
(1, 'U0000000004', 'admin', NULL, '', '', NULL, 'admin', '', '10adba0615c1187091b2f0d505ae60ee', 1, 1445417117, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `xx_user`
--
ALTER TABLE `xx_user`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `user_code_2` (`user_code`),
  ADD KEY `user_code` (`user_code`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `xx_user`
--
ALTER TABLE `xx_user`
  MODIFY `user_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
