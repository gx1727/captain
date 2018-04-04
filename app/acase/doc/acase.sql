DROP TABLE IF EXISTS `case_vote`;
CREATE TABLE `case_vote` (
  `cv_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `cv_code` varchar(32) NOT NULL COMMENT '活动code',
  `cv_title` varchar(256) DEFAULT NULL COMMENT '活动标题',
  `cv_img` varchar(128) DEFAULT NULL COMMENT '活动头像',
  `cv_abstract` varchar(256) DEFAULT NULL COMMENT '活动简介',
  `cv_imglist` text DEFAULT '' COMMENT '活动图集',
  `cv_info` text DEFAULT '' COMMENT '活动内容',
  `cv_atime` int(10) DEFAULT '0' COMMENT '开始时间',
  `cv_etime` int(10) DEFAULT '0' COMMENT '结束时间',
  `cv_state` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态,默认为0 1:正常 2:暂停',
  `cv_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否删除,默认为0 0:未删除 1:已删除',
   PRIMARY KEY (`cv_id`),
   KEY case_vote_cv_code (`cv_code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='投票活动表' AUTO_INCREMENT=1;


DROP TABLE IF EXISTS `case_vote_candidate`;
CREATE TABLE `case_vote_candidate` (
  `cvc_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `cvc_title` varchar(256) DEFAULT NULL COMMENT '候选人名称',
  `cvc_img` varchar(128) DEFAULT NULL COMMENT '候选人头像',
  `cvc_abstract` varchar(256) DEFAULT NULL COMMENT '候选人简介',
  `cvc_imglist` text DEFAULT '' COMMENT '候选人图集',
  `cvc_info` text DEFAULT '' COMMENT '候选人内容',
  `cvc_atime` int(10) DEFAULT '0' COMMENT '加入时间',
  `cvc_ballot` int(10) DEFAULT '0' COMMENT '选票数',
  `cvc_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否删除,默认为0 0:未删除 1:已删除',
   PRIMARY KEY (`cvc_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='候选人表' AUTO_INCREMENT=1;


DROP TABLE IF EXISTS `case_vote_ballot`;
CREATE TABLE `case_vote_ballot` (
  `cvb_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_code` varchar(32) NOT NULL COMMENT '用户编码',
  `user_name` varchar(64) DEFAULT '' COMMENT '用户名',
  `cvb_atime` int(10) DEFAULT '0' COMMENT '选票时间',
  `cvb_year` int(10) DEFAULT '0' COMMENT '选票年',
  `cvb_month` int(10) DEFAULT '0' COMMENT '选票月',
  `cvb_week` int(10) DEFAULT '0' COMMENT '选票周',
  `cvb_day` int(10) DEFAULT '0' COMMENT '选票日',
  `cvb_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否删除,默认为0 0:未删除 1:已删除',
   PRIMARY KEY (`cvb_id`),
   KEY case_vote_ballot_user_code (`user_code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='选票表' AUTO_INCREMENT=1;

