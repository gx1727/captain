-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 2018-02-27 02:06:45
-- 服务器版本： 5.6.16
-- PHP Version: 7.1.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Database: `captain`
--

-- --------------------------------------------------------

--
-- 表的结构 `ayeer_notepaper`
--

DROP TABLE IF EXISTS `ayeer_notepaper`;
CREATE TABLE `ayeer_notepaper` (
  `notepaper_id` int(10) UNSIGNED NOT NULL,
  `user_code` varchar(32) NOT NULL DEFAULT '' COMMENT '用户编码',
  `notepaper_title` varchar(512) NOT NULL DEFAULT '' COMMENT '标题',
  `notepaper_content` text COMMENT '内容',
  `notepaper_atime` int(10) DEFAULT '0' COMMENT '创建时间',
  `notepaper_pid` int(10) DEFAULT '0' COMMENT '父ID',
  `notepaper_display` tinyint(1) DEFAULT '0' COMMENT '0:正常显示 1:特殊显示',
  `notepaper_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否删除,默认为0 0:未删除 1:已删除'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='便签';

--
-- 转存表中的数据 `ayeer_notepaper`
--

INSERT INTO `ayeer_notepaper` (`notepaper_id`, `user_code`, `notepaper_title`, `notepaper_content`, `notepaper_atime`, `notepaper_pid`, `notepaper_display`, `notepaper_status`) VALUES
(1, 'U0000000004', '20161212', '[]', 1481509858, 0, 0, 0),
(7, 'U0000000004', '分销系统', '[{\"id\":\"1481709957\",\"time\":1481710094,\"data\":\"* ~~~\\u4ea7\\u54c1\\u5185\\u9875\\u52a0\\u540c\\u7c7b\\u63a8\\u8350\\u3002\\u4ea7\\u54c1\\u6700\\u4e0b\\u65b9 \\u5c31\\u662f\\u540c\\u76ee\\u5f55\\u5185\\u4ea7\\u54c1\\u968f\\u673a\\u51fa\\u73b0\\u5c31\\u597d ~~~\\n\\n* ~~~\\u641c\\u7d22\\u529f\\u80fd~~~\\n\\n* ~~~\\u6536\\u85cf\\u529f\\u80fd~~~\\n\\n* \\u5730\\u5740\\u7ba1\\u7406\\n\\n* \\u5c0f\\u6807\\u9898\\u30001 \\u540e\\u53f0\\u5c0f\\u6807\\u9898\\u8fd9\\u4e2a\\u5730\\u65b9\\uff0c\\u5b57\\u6570\\u6bd4\\u8f83\\u591a\\uff0c\\u80fd\\u4e0d\\u80fd\\u5e2e\\u6211\\u628a\\u4f4d\\u7f6e\\u6269\\u5927\\u70b9\\u3000\\u597d\\u591a\\u5b57\\u90fd\\u9690\\u85cf\\u5728\\u540e\\u9762\\uff0c\\u4e0d\\u663e\\u793a\\n\\n* \\u4e0d\\u77e5\\u9053\\u4ec0\\u4e48\\u95ee\\u9898\\u540e\\u53f0\\u79d1\\u989c\\u6c0f\\u7cfb\\u5217\\u4ea7\\u54c1\\uff0c\\u5927\\u6807\\u9898\\u5185\\u5bb9\\u586b\\u5199\\u5b8c\\u6574\\u540e\\uff0c\\u8fd8\\u662f\\u4f1a\\u4e22\\u5931\\n\\n* \\u540e\\u53f0\\u80fd\\u4e0d\\u80fd\\u589e\\u52a0\\u8bbe\\u7f6e\\uff0c\\u53ef\\u4ee5\\u81ea\\u7531\\u8c03\\u6574\\u4ea7\\u54c1\\u987a\\u5e8f\\uff0c\\u56e0\\u4e3a\\u540e\\u671f\\uff0c\\u540c\\u4e00\\u4e2a\\u54c1\\u724c\\u73b0\\u5728\\u90fd\\u6709\\u65b0\\u7684\\u4ea7\\u54c1\\u589e\\u52a0\\uff0c\\u60f3\\u628a\\u540c\\u4e00\\u4e2a\\u54c1\\u724c\\u4ea7\\u54c1\\u6309\\u987a\\u5e8f\\u653e\\u5728\\u4e00\\u8d77\\n\\n* \\u5e0c\\u671b\\u65b0\\u589e\\u4e00\\u4e2a\\u529f\\u80fd\\uff0c\\u628a\\u6682\\u65f6\\u4e0d\\u60f3\\u663e\\u793a\\u5728\\u524d\\u53f0\\u7684\\u4ea7\\u54c1\\u7ed9\\u9690\\u85cf\\u8d77\\u6765\\n\\n* \\u540c\\u4e00\\u4e2a\\u4ea7\\u54c1\\u7f16\\u53f7\\u80fd\\u8bbe\\u7f6e\\u6210\\u7528\\u4e8e2\\u4e2a\\u4ea7\\u54c1\\u5417\\uff0c\\u6bd4\\u5982\\u60a6\\u8bd7\\u98ce\\u541f\\u9762\\u971c\\uff0c\\u4e00\\u79cd\\u65b9\\u5f0f\\u662f\\u4e702\\u4e2a\\u51cf10\\u5143\\uff0c\\u8fd8\\u6709\\u4e00\\u79cd\\u662f\\u6807\\u9898\\u4e2d\\u5c31\\u5199\\u67092\\u4e2a\\u4e00\\u7ec4\\uff0c2\\u4ef6\\u4e00\\u8d77\\u5356\\u3002\\n\\n* \\u7f8e\\u5986\\u6bcf\\u6b3e\\u4ea7\\u54c1\\u7684\\u53f3\\u4e0b\\u89d2\\u90fd\\u4f1a\\u6709\\u97e9\\u56fd\\u76f4\\u90ae\\uff0c\\u9700\\u8981\\u6539\\u6210\\u9999\\u6e2f\\u76f4\\u90ae\\n\\n* \\u5728\\u6bcf\\u4e2a\\u7c7b\\u76ee\\u4e0b\\u9762\\u7684\\u4ea7\\u54c1\\uff0c\\u5e0c\\u671b\\u53ef\\u4ee5\\u81ea\\u7531\\u7684\\u628a\\u7545\\u9500\\u4ea7\\u54c1\\u653e\\u5728\\u6700\\u524d\\u9762\\uff0c\",\"mode\":0,\"pwd\":\"\"},{\"id\":\"1482470953\",\"time\":1482471042,\"data\":\"*  \\u5730\\u5740\\u7ba1\\u7406\\u5458\\uff0c\\u8eab\\u4efd\\u8bc1\\u5fc5\\u586b\\uff0c\\u3000\\u7167\\u7247\\u4e0d\\u9700\\u8981\\u4e86\\n\\n* \\u4ed3\\u5e93\\u72ec\\u7acb\\u9700\\u6c42\\n* \\u6279\\u91cf\\u5bfc\\u5165\\u3001\\u5bfc\\u51fa\\n* \\u6709\\u4e00\\u4e2a\\u5c0f\\u6279\\u53d1\\u91cf\\u7684\\u89d2\\u8272\\uff0c\\u8981\\u6c42\\u5bfc\\u5165\\u6279\\u91cf\\u529f\\u80fd\\n* \\u6709\\u7684\\u5546\\u54c1\\u662f\\u6709\\u90ae\\u8d39\\uff0c\\u3000\\u4e24\\u4ef6\\u5305\\u90ae\\u3000\\uff0c\\u53ea\\u9488\\u5bf9\\u9999\\u6e2f\\u4ed3\\u5e93\\uff0c\\u3000\\u6ee188\\u5305\\u90ae\\n\",\"mode\":0,\"pwd\":\"\"}]', 1481700997, 0, 1, 0),
(5, 'U0000000004', '20161213', '[]', 1481620198, 0, 0, 0),
(6, 'U0000000004', 'ayeer笔记', '[{\"id\":\"1481700541\",\"time\":1481700936,\"data\":\"##\\u529f\\u80fd\\u4ecb\\u7ecd\\n\\u7ec8\\u4e8e\\u7b97\\u662f\\u5b8c\\u6210\\u4e86\\u4e00\\u4e2a\\u96cf\\u5f62\\uff0c\\u53ef\\u4ee5\\u8bb0\\u4e00\\u4e9b\\u4e1c\\u897f\\uff0c\\u57fa\\u672c\\u5b8c\\u6210\\u4e86\\u589e\\u3001\\u5220\\u3001\\u6539\\u7684\\u529f\\u80fd\\u3002\\u5230\\u76ee\\u524d\\u4e3a\\u6b62\\uff0c\\u6bd4\\u7ecf\\u8d39\\u4e8b\\u7684\\u662f\\u628a[editormd](https:\\/\\/pandao.github.io\\/editor.md\\/examples\\/full.html)\\u52a8\\u6001\\u7684\\u5e94\\u8be5\\u5728\\u5c55\\u793a\\u9875\\u9762\\u4e0a\\uff0c\\u5b9e\\u73b0\\u7b80\\u5355\\u7684\\u6240\\u89c1\\u5373\\u6240\\u5f97\\uff0c\\u629b\\u5f03\\u4e86\\u540e\\u53f0\\u7f16\\u8f91\\u3001\\u524d\\u53f0\\u5c55\\u793a\\u7684\\u505a\\u6cd5\\u3002\\n\\n\\u6570\\u636e\\u7684\\u5b58\\u50a8\\u4e5f\\u6709\\u4e00\\u70b9\\u70b9\\u8bbe\\u8ba1\\uff0c\\u5728\\u4f20\\u7edf\\u7684 \\u6807\\u9898 + \\u5185\\u5bb9\\u7684\\u57fa\\u7840\\uff0c\\u5c06 \\u201c\\u5185\\u5bb9\\u201d \\u8bbe\\u7f6e\\u4e3a\\u4e00\\u4e2ajson\\u6570\\u636e\\u7ed3\\u6784\\uff0c\\u8fd9\\u6837\\u6709\\u51e0\\u70b9\\u597d\\u5904\\uff1a\\n1. \\u53ef\\u4ee5\\u6309\\u5c0f\\u8282\\u6765\\u7ba1\\u7406\\u6587\\u7ae0\\u5185\\u5bb9\\uff0c\\u8fd9\\u5728\\u5177\\u4f53\\u4f7f\\u7528\\u65f6\\uff0c\\u4f53\\u73b0\\u4e3a\\u8981\\u5427\\u968f\\u610f\\u589e\\u52a0N\\u4e2a\\u5c0f\\u8282\\n2. \\u53ef\\u4ee5\\u4fee\\u6539\\u6216\\u662f\\u5220\\u6389\\u5176\\u4e2d\\u6709\\u7684\\u67d0\\u4e00\\u5c0f\\u8282\\n3. \\u53ef\\u80fd\\u5c0f\\u8282\\u7684\\u5185\\u5bb9\\u8fdb\\u884c\\u6743\\u9650\\u63a7\\u5236\\uff0c\\u5982\\u53ea\\u6709\\u8f93\\u5165\\u67e5\\u770b\\u5bc6\\u7801\\u540e\\uff0c\\u624d\\u663e\\u793a\\u5c0f\\u8282\\u5185\\u5bb9\\u3002\\n\\n\\u6bcf\\u4e00\\u5c0f\\u8282\\u4e3a\\u4e00\\u4e2ajson\\u8282\\u70b9\\uff0c\\u6570\\u636e\\u7ed3\\u6784\\u5982\\u4e0b\\uff1a\\n~~~~\\n{\\n    \\\"id\\\": \\\"1481549495\\\",\\n    \\\"time\\\": 1481549502,\\n    \\\"data\\\": \\\"\\u5185\\u5bb9\\\",\\n    \\\"mode\\\": 0,\\n    \\\"pwd\\\": \\\"\\\"\\n}\\n~~~~\\n\\n| \\u5b57\\u6bb5 | \\u8bf4\\u660e |\\n| ------ | ------ |\\n|`id`|\\u5c0f\\u8282\\u7684\\u552f\\u4e00\\u67b3\\u8bc6 |\\n|`time`|\\u5c0f\\u8282\\u7684\\u521b\\u5efa\\u65f6\\u95f4 |\\n|`data`|\\u5177\\u4f53\\u5185\\u5bb9|\\n|`mod`|\\u6a21\\u5f0f|\\n|`pwd`|\\u5bc6\\u7801|\\n\\n\",\"mode\":0,\"pwd\":\"\"}]', 1481675792, 0, 1, 0),
(4, 'U0000000004', 'captain 2.0', '[{\"id\":\"1482307770\",\"time\":1482307897,\"data\":\"## \\u6982\\u8ff0\\n\\ncaptain\\u662f\\u4e00\\u4e2a\\u57fa\\u4e8ePHP \\u7684 CI \\u6846\\u67b6\\u5f00\\u53d1\\u7684\\u540e\\u53f0\\u7ba1\\u7406\\u7cfb\\u7edf\\n\\n\",\"mode\":0,\"pwd\":\"\"},{\"id\":\"1482401046\",\"time\":1482401231,\"data\":\"## \\u5bc6\\u7801\\u7f16\\u7801\\u65b9\\u5f0f\\n\\n> pwd = \\u7528\\u6237\\u7684\\u771f\\u5b9e\\u5bc6\\u7801\\n> pwd = md5(pwd)\\n> pwd = \\u53cd\\u8f6c(pwd)\\n> pwd = md5(user_code + pwd)\\n\\n\\u6700\\u7ec8\\u5165\\u5e93\\u7684pwd\",\"mode\":0,\"pwd\":\"\"},{\"id\":\"1482802564\",\"time\":1482802585,\"data\":\"## \\u663e\\u793a\\u6a21\\u677f\\n\\n\\u7cfb\\u7edf\\u8bbe\\u5b9a\\u6bcf\\u4e2a\\u89d2\\u8272\\u8981\\u6709\\u4e00\\u4e2a\\u6a21\\u677f\\u540d\\uff0c\\u9ed8\\u8ba4\\u60c5\\u51b5\\u7ea6\\u5b9a\\uff1a\\n\\u540e\\u53f0\\u7684\\u6a21\\u677f\\u540d\\u4e3a`admin`,\\u5bf9\\u5e94\\u7684\\u76ee\\u5f55\\u4e3a`application\\\\views\\\\admin`\\n\\u524d\\u53f0\\u7684\\u6a21\\u677f\\u540d\\u4e3a`default`,\\u5bf9\\u5e94\\u7684\\u76ee\\u5f55\\u4e3a`application\\\\views\\\\default`\\n\\n\\u6a21\\u677f\\u540d\\u662f\\u653e\\u5728\\u89d2\\u8272\\u8868\\u4e2d\\u7684\\uff0c\\u4e3a\\u4e86\\u65b9\\u4fbf\\u4f7f\\u7528\\uff0c\\u5165\\u5e93\\u7684\\u5b57\\u6bb5\\u5185\\u5bb9\\u662f\\u6a21\\u677f\\u540d\\u540e\\u52a0\\u4e86\\u4e00\\u4e2a`\\/` \\u7684\\n\\n\\u663e\\u793a\\u9875\\u9762\\u7684\\u6d41\\u7a0b\\u662f\\uff1a\\n1. Controller \\u5c42\\u8c03\\u7528\\n\\n````\\n$this->_show(\'admin\\/admin_index\', \'ADMIN\');\\n````\\n\\n\\u7b2c\\u4e00\\u4e2a\\u53c2\\u6570\\u4e3a\\u6a21\\u677f\\u76f8\\u5bf9\\u5730\\u5740\\uff0c\\u4e0d\\u8003\\u8651\\u7528\\u6237\\u6a21\\u677f\\u76ee\\u5f55\\u95ee\\u9898\\n\\n\\u7b2c\\u4e8c\\u4e2a\\u53c2\\u6570\\u662f\\u6a21\\u677f\\u5206\\u7ec4\\uff0c\\u5206\\u7ec4\\u7684\\u4e0d\\u540c\\uff0c\\u5f71\\u54cd\\u5230\\u4f20\\u5165\\u524d\\u53f0\\u7684\\u56fa\\u5b9a\\u53c2\\u6570\\n> ADMIIN : \\u540e\\u53f0\\u6a21\\u677f\\n> FRONT: \\u524d\\u53f0\\u6a21\\u677f\\n> MODULAR: \\u6a21\\u7ec4\\n\\n2. _show \\u662f\\u5b9a\\u4e49\\u5728 `XX_Controller`\\u4e2d\\u7684\\u4e00\\u4e2aController \\u5c42\\u7684\\u5171\\u540c\\u65b9\\u6cd5\\uff0c\\u8be5\\u65b9\\u6cd5\\u7ed9\\u9875\\u9762\\u4f20\\u5165\\u9ed8\\u8ba4\\u7684\\u56fa\\u5b9a\\u53c2\\u6570\\uff0c\\u8c03\\u7528 \\n\\n````\\n $this->load_view($view);\\n````\\n\\n3. load_view\\u65b9\\u6cd5\\u662f\\u5b9a\\u4e49\\u5728`XX_Controller`\\u4e2d\\u7684\\u4e00\\u4e2a\\u79c1\\u6709\\u65b9\\u6cd5\\uff0c\\u8be5\\u65b9\\u6cd5\\u81ea\\u52a8\\u5224\\u65ad\\u7528\\u6237\\u76ee\\u5f55\\u53c2\\u6570`$this->template`, \\u663e\\u793a\\u6700\\u7ec8\\u6a21\\u677f\\u3002\\n\",\"mode\":0,\"pwd\":\"\"}]', 1481613671, 0, 1, 0),
(8, 'U0000000004', '20161214', '[]', 1481710032, 0, 0, 0),
(9, 'U0000000004', '20161215', '[]', 1481749790, 0, 0, 0),
(10, 'U0000000004', '20161221', '[]', 1482307716, 0, 0, 0),
(12, 'U0000000004', 'MarkDown学习', '[{\"id\":\"1482380152\",\"time\":1482380161,\"data\":\"[ TOC ]\\n\\n# \\u5927\\u6807\\u9898\\n\\n## \\u4e8c\\u53f7\\u6807\\u9898\\n\\n###\\u3000\\u4e09\\u53f7\\u6807\\u9898\\n\\n\\n\\n\\u6b63\\u6587\\u524d\\u9762\\u8981\\u6709\\u4e00\\u4e2a\\u7a7a\\u884c\\uff0c\\u7ed3\\u5c3e\\u4e5f\\u8981\\u6709\\u4e00\\u4e2a\\u7a7a\\u884c\\n\\u5982\\u679c\\u8981 **\\u7c97\\u4f53**\\uff0c\\u5c31\\u5728\\u524d\\u540e\\u90fd\\u52a0\\u4e0a\\u4e24\\u4e2a\\u661f\\u53f7(\\\\*)\\uff0c\\u4e5f\\u53ef\\u4ee5\\u662f\\u4e24\\u4e0b\\u5212\\u753b __\\u7c97\\u4f53__\\n\\u5982\\u679c\\u8981 *\\u659c\\u4f53*\\uff0c\\u5c31\\u5728\\u524d\\u540e\\u90fd\\u52a0\\u4e0a\\u4e00\\u4e2a\\u661f\\u53f7(\\\\*), \\u4e5f\\u53ef\\u4ee5\\u662f\\u4e00\\u4e2a\\u4e0b\\u5212\\u753b\\u3000_\\u659c\\u4f53_\\n\\u5982\\u679c\\u8981 ~~\\u5220\\u9664\\u7ebf~~\\uff0c\\u5c31\\u5728\\u524d\\u540e\\u90fd\\u52a0\\u4e0a\\u4e24\\u4e2a\\u6ce2\\u6d6a\\u53f7(\\\\~), \\n\\u5982\\u679c\\u8981 `\\u8fd9\\u6837\\u7684\\u6837\\u5f0f`\\uff0c\\u5c31\\u5728\\u524d\\u540e\\u90fd\\u52a0\\u4e0a\\u4e00\\u4e2a\\u5c0f\\u70b9\\u70b9(\\\\`)\\n\\u8fd9\\u91cc\\u53ef\\u4ee5\\u53d1\\u73b0\\uff0c\\\\ \\u53ef\\u4ee5\\u7528\\u4f5c\\u8f6c\\u610f\\u7528\\n\\n\\n\\u53ef\\u4ee5\\u7528\\u4e09\\u4e2a\\u5c0f\\u51cf\\u53f7\\u753b\\u4e00\\u6761\\u6a2a\\u7ebf, \\u4f46\\u4e00\\u5b9a\\u8981\\u6ce8\\u610f\\uff0c\\u6a2a\\u7ebf\\u7684\\u524d\\u540e\\u90fd\\u8981\\u6709\\u7a7a\\u884c\\n\\n---\\n\\n\\u4e0b\\u9762\\u7684\\u6392\\u7248\\u6211\\u5f88\\u559c\\u6b22\\n\\n> \\u4ee5\\u4e00\\u4e2a >\\u3000\\u5f00\\u5934\\uff0c\\u7a7a\\u4e00\\u4e2a\\u7a7a\\u683c\\u540e\\u9762\\u7684\\u6587\\u5b9a\\u5c31\\u662f\\u8fd9\\u6837\\u7684\\u4e86\\n\\u540c\\u6837\\uff0c\\u4e5f\\u662f\\u8981\\u4e00\\u4e2a\\u7a7a\\u884c\\u5f00\\u5934\\n\\u76f4\\u5230\\u4e0b\\u4e00\\u4e2a\\u7a7a\\u884c\\u7ed3\\u675f\\n\\n\\n\\u8fd8\\u53ef\\u4ee5\\u8fd9\\u6837\\uff1a\\n> * \\u7b2c\\u4e00\\u884c\\n> * \\u7b2c\\u4e8c\\u884c\\n> * \\u2026\\u2026\\n> * \\u7b2c\\uff2e\\u884c\\n\\n\\n    \\u8fd8\\u6709\\u8fd9\\u6837\\u7684\\n    \\u5f00\\u6237\\u6253\\u4e0a\\uff14\\u4e2a\\u7a7a\\u683c\\n\\n\\u4e5f\\u662f\\u8981\\u4e00\\u4e2a\\u7a7a\\u884c\\u7ed3\\u675f\\n\\n\\n```html\\n&lt;html&gt;\\n&lt;body&gt;\\n <div>\\n \\u8fd9\\u91cc\\u8fd8\\u53ef\\u4ee5\\u7ed9html\\u7740\\u8272\\n \\u4ee5\\u3000```html\\u3000\\u5f00\\u5934\\n \\u4ee5\\u3000```\\u3000\\u7ed3\\u5c3e\\n <\\/div>\\n&lt;\\/body&gt;\\n&lt;\\/html&gt;\\n```\\n\\n```js\\nfunction hello(){\\n console.log(\\\"\\u8fd9\\u91cc\\u8fd8\\u53ef\\u4ee5\\u7ed9html\\u7740\\u8272\\\");\\n console.log(\\\"\\u4ee5\\u3000```js\\u3000\\u5f00\\u5934\\\");\\n console.log(\\\"\\u4ee5\\u3000```\\u3000\\u7ed3\\u5c3e\\\");\\n}\\n```\\n\\n---\\n\\n\\u5982\\u4f55\\u663e\\u793a\\u56fe\\u7247\\uff1f\\n\\u4ee5!\\u5f00\\u59cb\\uff0c\\u540e\\u9762\\u6709\\u4e00\\u5bf9[] \\u518d\\u3000\\u8ddf\\u4e00\\u5bf9 ()\\n[]\\u4e2d\\u4e3a\\u56fe\\u7247\\u7684alt \\n()\\u4e2d\\u4e3a\\u56fe\\u7247\\u7684url\\u5730\\u5740\\n\\u5982\\uff1a ![ayeer]\\\\(http:\\/\\/www.gx1727.com\\/static\\/images\\/favicon.png)\\n![ayeer](http:\\/\\/www.gx1727.com\\/static\\/images\\/favicon.png)\\n\\n\\n\\n---\\n\\n\\u5982\\u4f55\\u663e\\u793a\\u94fe\\u63a5\\uff1f\\n\\u4e00\\u5bf9[] \\u518d\\u3000\\u8ddf\\u4e00\\u5bf9 ()\\n[]\\u4e2d\\u4e3a\\u56fe\\u7247\\u7684alt \\n()\\u4e2d\\u4e3a\\u94fe\\u63a5url\\n\\u5982\\uff1a [\\u8fd9\\u91cc\\u662f\\u4e00\\u4e2a\\u8d85\\u94fe\\u63a5]\\\\(http:\\/\\/www.gx1727.com\\/)\\n[\\u8fd9\\u91cc\\u662f\\u4e00\\u4e2a\\u8d85\\u94fe\\u63a5](http:\\/\\/www.gx1727.com\\/)\\n\\n[\\u666e\\u901a\\u94fe\\u63a5\\u5e26\\u6807\\u9898](http:\\/\\/www.gx1727.com\\/ \\\"\\u666e\\u901a\\u94fe\\u63a5\\u5e26\\u6807\\u9898\\\")\\n\\n---\\n\\n\\n### \\u5217\\u8868 Lists\\n\\n#### \\u65e0\\u5e8f\\u5217\\u8868\\uff08\\u51cf\\u53f7\\uff09Unordered Lists (-)\\n                \\n- \\u5217\\u8868\\u4e00\\n- \\u5217\\u8868\\u4e8c\\n- \\u5217\\u8868\\u4e09\\n     \\n#### \\u65e0\\u5e8f\\u5217\\u8868\\uff08\\u661f\\u53f7\\uff09Unordered Lists (*)\\n\\n* \\u5217\\u8868\\u4e00\\n* \\u5217\\u8868\\u4e8c\\n* \\u5217\\u8868\\u4e09\\n\\n#### \\u65e0\\u5e8f\\u5217\\u8868\\uff08\\u52a0\\u53f7\\u548c\\u5d4c\\u5957\\uff09Unordered Lists (+)\\n                \\n+ \\u5217\\u8868\\u4e00\\n+ \\u5217\\u8868\\u4e8c\\n    + \\u5217\\u8868\\u4e8c-1\\n    + \\u5217\\u8868\\u4e8c-2\\n    + \\u5217\\u8868\\u4e8c-3\\n+ \\u5217\\u8868\\u4e09\\n    * \\u5217\\u8868\\u4e00\\n    * \\u5217\\u8868\\u4e8c\\n    * \\u5217\\u8868\\u4e09\\n\\n#### \\u6709\\u5e8f\\u5217\\u8868 Ordered Lists (-)\\n                \\n1. \\u7b2c\\u4e00\\u884c\\n2. \\u7b2c\\u4e8c\\u884c\\n3. \\u7b2c\\u4e09\\u884c\\n\\n---\\n\\n### \\u7ed8\\u5236\\u8868\\u683c Tables\\n\\n| \\u9879\\u76ee        | \\u4ef7\\u683c   |  \\u6570\\u91cf  |\\n| --------   | -----:  | :----:  |\\n| \\u8ba1\\u7b97\\u673a      | $1600   |   5     |\\n| \\u624b\\u673a        |   $12   |   12   |\\n| \\u7ba1\\u7ebf        |    $1    |  234  |\\n\",\"mode\":0,\"pwd\":\"\"}]', 1482380058, 0, 1, 0),
(13, 'U0000000004', '20161222', '[]', 1482392739, 0, 0, 0),
(14, 'U0000000004', '常用命令', '[{\"id\":\"1482476689\",\"time\":1482476736,\"data\":\"#\\u3000php\\n\\n## \\u542f\\u52a8php Web Server\\n> __php -S localhost:8080 -t \\/www___\\n\\n* `8080` \\u662f\\u670d\\u52a1\\u7aef\\u53e3\\n* `-t`  \\u540e\\u9762\\u8ddf\\u7684\\u662f\\u7f51\\u7ad9\\u6839\\u76ee\\u5f55\\n\\n\\u5982\\u679c\\u652f\\u6301\\u8fdc\\u7a0b\\u8bbf\\u95ee\\uff0c\\u8bf7\\u7528\\uff1a\\n> __php -S 0.0.0.0:8080 -t \\/www__\\n\\n## \\u6267\\u884c\\u4ee3\\u7801\\n\\u6267\\u884c\\u4e00\\u6709\\u4e2aphp\\u811a\\u672c\\u6587\\u4ef6\\n```\\nphp my_script.php\\nphp -f  \\\"my_script.php\\\"\\n```\\n\\n\\u6267\\u884c\\u4e00\\u4e2a\\u547d\\u4ee4\\n```\\nphp -r \\\"print_r(get_defined_constants());\\\"\\n```\\n\\n## \\u6307\\u5b9a\\u914d\\u5236\\u6587\\u4ef6\\n```\\n$ php -c \\/custom\\/directory\\/ my_script.php\\n$ php -c \\/custom\\/directory\\/custom-file.ini my_script.php\\n```\",\"mode\":0,\"pwd\":\"\"}]', 1482469903, 0, 1, 0),
(18, 'U0000000004', '20161223', '[]', 1482477673, 0, 0, 0),
(19, 'U0000000004', '20161226', '[]', 1482754734, 0, 0, 0),
(20, 'U0000000004', '20161227', '[]', 1482831376, 0, 0, 0);

-- --------------------------------------------------------

--
-- 表的结构 `cms_content`
--

DROP TABLE IF EXISTS `cms_content`;
CREATE TABLE `cms_content` (
  `cn_id` int(10) UNSIGNED NOT NULL,
  `user_code` varchar(32) NOT NULL DEFAULT '' COMMENT '用户编码',
  `cn_title` varchar(512) NOT NULL DEFAULT '' COMMENT '标题',
  `cn_img` varchar(128) NOT NULL DEFAULT '' COMMENT '头像',
  `cn_abstract` varchar(2048) NOT NULL DEFAULT '' COMMENT '摘要',
  `cn_content` text COMMENT '内容',
  `cn_atime` int(10) DEFAULT '0' COMMENT '创建时间',
  `cn_etime` int(10) DEFAULT '0' COMMENT '修改时间',
  `cn_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否删除,默认为0 0:未删除 1:已删除'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='内容节点';

--
-- 转存表中的数据 `cms_content`
--

INSERT INTO `cms_content` (`cn_id`, `user_code`, `cn_title`, `cn_img`, `cn_abstract`, `cn_content`, `cn_atime`, `cn_etime`, `cn_status`) VALUES
(1, 'U0000000004', 'a1', '', 'b', 'c', 1454723168, 1466064966, 1),
(2, 'U0000000004', '赫卡忒', '', '赫卡忒(Hecate),基本功能就是监控文件状态', '这个项目的基本功能就是监控文件状态,所以就开始baidu搜索\"守护神\",想起一个\"神\"级的名子,结果发现了\n\n**赫卡忒(Hecate)**\n\n为什么第一个神是地狱的创造者,代表世界的黑暗面呢?不管了,就这样叫他了.', 1454733556, 1466063770, 0),
(4, 'U0000000004', 'helloworld', '', 'helloworld,又是一个新的开始，这次我又能走多远呢？', 'helloworld,又是一个新的开始，这次我又能走多远呢？\n', 1457417412, 1459464539, 0),
(5, 'U0000000004', '关于我', '', '经过一段时间的奋战，总到把这个站给搞上来了．小小兴奋之余，不写该写点什么描述．记忆中这已经是我建的第三个个人blog类站了，前两个最后都不知不觉的消失了，总结原因还是自己太懒，没有坚持．所以，希望这次不会．', '经过一段时间的奋战，总到把这个站给搞上来了．小小兴奋之余，不写该写点什么描述．记忆中这已经是我建的第三个个人blog类站了，前两个最后都不知不觉的消失了，总结原因还是自己太懒，没有坚持．所以，希望这次不会．\n\n**关于＂拼自己＂**\n\n给网站起这个名子，就是凭自己的努力做出点什么．\n\n**关于LOGO**\n\n拼自己的LOGO是一个握紧的拳头，代表着努力、奋斗\n\n**关于本站**\n\n其实对了一个技术人员来说，写代码要比写文章简单的多，所以，到现在也不知道该写点什么，我想应该是转载一些文章，再写点自己想写的东西吧．\n\n**关于我**\n\n一个平凡的程序，平凡的没什么可说的\n\n**关于改版**\n\n果然我只是一个程序员，只会也代码，不会写文章。上一版本的日志数都不到两位数，又改了一版。这次的blog程序看似更简单，其实是在测试自己做的cms系统。唉，不管了，随心吧！', 1457644123, 1459324567, 0),
(6, 'U0000000004', '又是4月1日', '', '又是4月1日，愚人节，本可以搞怪的一个欢乐的节日，老天爷却在和我们全家人开玩笑，都生病了。然望过了今天，大家都好起来，振备精神，还有好多的事要做呢！', '  又是4月1日，愚人节，本可以搞怪的一个欢乐的节日，老天爷却在和我们全家人开玩笑，都生病了。然望过了今天，大家都好起来，振备精神，还有好多的事要做呢！\n\n  本也想搞搞怪，也没了心情，今年来，感觉家里都很不顺，状况不断，是不是我们在不小心的时候得罪了某个神仙，也或是看我们过的太安逸了，来点刺激呢。\n\n  今天天气不错，也算是给自己找个开心的理由。', 1459463587, 1459464571, 0),
(7, 'U0000000004', '清晨 下雨 搜索引擎', '', '清晨、下雨、搜索引擎，不要问我是什么关系，其实就是今天早上下着一阵阵的大雨，我又起的比较早，没事可干，想想最近思考的问题。正好最近在想搜索引擎的事。好吧，三者毛关系也没有', '> 清晨、下雨、搜索引擎，不要问我是什么关系，其实就是今天早上下着一阵阵的大雨，我又起的比较早，没事可干，想想最近思考的问题。正好最近在想搜索引擎的事。好吧，三者毛关系也没有\n\n一直想自己完成一个网站，基础就是有内容性的，且不考虑现在是否已过时，只是想兑现自己的承诺。苦于技术出身的我，每每想到的都是些技术问题，而完成一个简单的内容性网站，并不需要太多的技术力量，最简单的，也是最广泛使用的，架一个类似织梦的CMS(发现好多站都是这么干的哦)，使劲的往里面录入文单就OK了。\n  \n可是，我想达到的目标，是可以快速的找到自己想要信息，这自然想到了“搜索引擎”，现在，我们能见到的搜索引擎最大的就是百度了，这里不是黑人家，百度的可用性越来越低，很简单，不论你搜索什么内容，第一页前其个是推广，后几个也是推广，就中间几条是真正搜索过来的，可是内容几乎一模一样', 1459897296, 1460367989, 0),
(8, 'U0000000004', '一颗糖，二颗糖', '', '我给你一颗糖，你很高兴，当你看到我给别人两颗，你就对我有看法了。但你不知道他也曾给我两颗糖，而你什么都没给过我。', '我给你一颗糖，你很高兴，当你看到我给别人两颗，你就对我有看法了。但你不知道他也曾给我两颗糖，而你什么都没给过我。\n\n生活中，太多这样的人，只会对别人有看法\n\n也因为要应付这样的事，希望在各方心理都平衡，所以，我们活的更累', 1461885288, 1461885392, 0),
(9, 'U0000000004', '放假 生病 卧床', '', '又到了五一小长期，没有旅游，没有集会。倒不是因为生病，但也庆幸这个时候生病，可以卧床休息', '又到了五一小长期，只感叹时间过的好快，从上次清明放假回来，就围绕着然然，先是结奶，结果两天后，她的喉咙发炎，高烧39度多。去医院看了两次，属于疱疹性咽颊炎。医生见意挂水，这样效果好，因为然妈就在那个医院上班，深知静脉滴注对身体的危害，我们还是坚持只吃药，不想这么小给她挂水。就这样，发烧，打架般的喂药，整天整夜的抱在怀里，只为她睡的安稳些，全家人坚持了一个星期，总算了好了。\n\n曾记得以前的51假，都是有机会出去玩一下，或是旅游，或是和同学朋友集个小会。这次因为然妈还在工作岗位上，没时候享受这美好的阳光我呢，也庆幸这个时候生病，可以卧床休息。也挺好', 1461980731, 1461980877, 0),
(10, 'U0000000004', '现在几点了', '', '坐在阳台边，迎着红玉般的在晨阳，安静的写写代码，其实，也挺爽的', '呵呵，好low哦，满怀热情的想写写blog，结果，忘了自己设计的系统是怎么发布文章了，看了一遍代码再把写的内容在前台展示出来。唉，者怪自己设计的太好，哈哈。\n\n**什么时候，都要保持一颗清醒的头脑做事，如果发现有点迷糊了，那就休息一下吧，没事的**\n\n**同一时候，只做一件事，如果突然想起来，还有另一件事要去处理，那就放下手上的事，专心处理它了，没事的**\n\n以上两句是昨晚跑步时想到了，给自己提个醒吧。不知道是因为后期动的少了，还是因为年龄大了，发现自己的身体已不能自如的活动起来了，连自发感觉跑步的势式也不那个好看了。当然了，这些都不是重点(其实就没有重点)，我想说的是，但然感觉的时间的存在，时间，人的一身，时间很不限，一不小心，我的时间就用去了一半，回到那个讨论半杯水的心态中，很客观的说，虽然很庆幸，我还有那半杯水，但是，以然我没有大把大把的时间去迷惘了。\n\n要学会管理自己的时间。\n\n找出来以前给自己买的一块表，哦，现在的智能手机，好象可以淘汰“手表”这样的东西了，所以，好久好久，我也没有带过表了。但其实呢，不知道有多少次，我从口袋里掏出手机，习惯性的看一见又放回去，结果还是不知道现在几点了，不得不再掏一次手机。也许，我们看手机的时候，根本看的不是时间，而是看有没有微信,ＱＱ上有没有人说话。所以，我觉得手机是手机，取代不了手表的。\n\n回到那块手表上，那是我上班的第二年吧，好象，买的一块电子表，好强啊，现在依然走时很准，也还有电。算算有八年了吧，只是很长一段时间来，我都忘记了它的存在。\n\n看到数字在跳动，想着自己有限的半杯水在流失，还不抓紧。', 1464643488, 1464646395, 0),
(11, 'U0000000004', '生日快乐', '', '过生日，又下雨了，没事，习惯了', '一不小心，又老了一岁。', 1464958697, 1464958712, 0),
(12, 'U0000000004', '你好，猴年马月', '', '你好，猴年马月', '', 1465130496, 1465130502, 0),
(13, 'U0000000004', '我要用正版，不不不，我要用免费版', '', '作为一个老码农（只有老），渐渐体会的用破解版或是盗版是不道德的。', '作为一个老码农（只有老），渐渐体会的用破解版或是盗版是不道德的。毕竟软件的作者是花了心血做出来的，直接下使用盗版是亵渎作者的劳动。所以，逐渐的我要替换到这种下来就用的习惯。\n首先，用360免费升级到win10，我不知道win10算不算免费，反正是不用输入序列号什么的， 接着下载了最新的免费的vs2015,IDEA也换成了免费版。其实对于个人用户来说，基础的功能已经完全够用了。今天发现在win10上，原来的ps也用不起来了，那正好，搜索一款替代产品，目前选择的是GIMP，正在下载中，还不知道用起来怎么样。\n但是，有些工具还是没找到替代产品，比如我大爱的phpstorm，用过其它有几款编辑器，没办法找到那种相同的快感，无奈还是找了个序列号先用一下（打脸）\n其实，想说明的是另一个问题，软件要怎么才能赚钱？\n', 1465894968, 1465894968, 0),
(14, 'U0000000004', '停水了', '', '这两天家里停水了，一大早拎着个桶去小区门口报安那里接水，大家排着队，也别有一番味道', '这两天家里停水了，因为物业没有及时的通知到，所以大家都没有准备。一大早起床，发现没水刷牙洗脸，也是件杯具的事。阳台上张望一下，发现有几个人已经拎首桶啊壶啊，反正是能装水的东西往小区门口那边走，我也赶紧拎上一个大的桶跟了下去。这个世界上，最大的幸福莫过于发现有人和你一样受苦。（其实就是拎个水而以，不苦，只是想到了这句牛B的话，一定要找个场合说出来而以。相对的一句话就是，这个世界上，最大的痛苦莫过于发现有人和你一样享乐）\n\n排队接水的感觉很熟悉，回想起高中阶段，寝室里都没有通水（其实都不能称之为寝室，因为就是在一个破坏的老教室里，摆了一二十张梯床，一个班的男同学都睡在一起的），全校的同学只能抢那么七八个水龙头，而有一半时间都是没有水的水的。然因，唯一的水源就是一口井了，  一到夏天的时候，那场面，那气势，那是里三圈外三圈，都抢着可以拉一桶水上来。现在想想，真是苦并快乐着，哪有学校是从井里打水的啊，呵呵。\n\n这又让我想到另一个问题，停水、停电、停气、断网，在现代生活中，都离我们越来越远的，人们的生活水平在提高，快乐的起点也越来越高，所以幸福感却是在降低。就连“人生最大的痛苦莫过于断网”这样的话都久见不鲜。停煤气了，可以用电磁炉做饭烧水。停电了，还是可以正常的做饭烧水。断网了， 其实什么也不算。最难的是停水，如果没有水，就什么也不用干了。\n\n感谢这个停水，让我有机会回来最根本的需求上思考问题，生活的久了，就忘了很多初忠\n\n', 1465945188, 1465946812, 0),
(15, 'U0000000004', '成功就是三步', '', '成功就是三步\n一、动起来\n二、向前进\n三、重得第二步', '成功就是三步\n- 一、动起来\n- 二、向前进\n- 三、重得第二步\n\n脑子里有好几个想法，结果都死在第一步上\n\n很久以前动工的几个小项目，还都在第二步上睡着了，没有动静\n\n现在感觉勉强可以算是第三步的，除了工作中的经验外，就只有这个算是自己一直在改动的程序框架，和这个blog般的CMS系统了。\n\n晚了，先睡觉，明天整理下想做的和要做的事。', 1466083463, 1466083905, 0),
(16, 'U0000000004', '下一个学习目标', '', '下一个学习目标　openresty', '下一个学习目标　openresty', 1471916687, 1471916716, 0),
(17, 'U0000000004', '苦劳从来就不是谈判的资本', '', '苦劳从来就不是谈判的资本', '苦劳从来就不是谈判的资本', 1478664440, 1478664440, 0),
(18, 'U0000000004', '终于', '', '终于', '终于要开始了', 1481323831, 1481323831, 0),
(19, 'U0000000004', '办公桌', '', '人们都说，很多创意灵感都是在厕所里迸发出来的，所以，我把我平生的第一张写字桌安排在厕所里', '今天是终于要开始了的第一天，因为是周六，上周就和家人约好的今天的安排，去哥哥家玩，顺道把我们车开去保养一个。\n\n今天还如愿的买到了给我自己的办公桌，从二市家具家里，淘到了一张老式木制小桌子，60块钱，大小正好可以放的进我们的小车子带回来。人们都说，很多好的创意灵感都是在厕所里迸发出来的，所以，我把我平生的第一张写字桌安排在厕所里。租的房子是两卫的，我们只用到了一个，另一个当成了杂物间。稍稍整理了一下，腾出了靠窗的一半空间，摆上我的小桌子，搬上一把椅子，从杂货里找到一个旧台灯，老婆还特意在窗台上为我摆上了一盆吊兰。我的办公室就算完成了，试了一下，感觉更是极好了！！！\n\n我相信，一个伟大的软件项目将从这个办公室里诞生了\n\n', 1481375187, 1482823639, 0);

-- --------------------------------------------------------

--
-- 表的结构 `cms_content_tags`
--

DROP TABLE IF EXISTS `cms_content_tags`;
CREATE TABLE `cms_content_tags` (
  `ct_id` int(10) UNSIGNED NOT NULL,
  `t_title` varchar(128) NOT NULL DEFAULT '' COMMENT 'tag标题',
  `t_id` int(10) NOT NULL DEFAULT '0' COMMENT 'tagID',
  `cn_id` int(10) NOT NULL DEFAULT '0' COMMENT '内容节点ID',
  `ct_weight` int(10) NOT NULL DEFAULT '0' COMMENT '权重',
  `ct_order` int(10) NOT NULL DEFAULT '0' COMMENT '排序'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='内容tags关系';

--
-- 转存表中的数据 `cms_content_tags`
--

INSERT INTO `cms_content_tags` (`ct_id`, `t_title`, `t_id`, `cn_id`, `ct_weight`, `ct_order`) VALUES
(9, 'blog', 7, 4, 1, 0),
(5, 'Hecate', 5, 2, 1, 0),
(6, '开发日志', 6, 2, 1, 0),
(7, 'Hecate', 5, 1, 1, 0),
(8, '开发日志', 6, 1, 1, 0),
(10, 'blog', 7, 6, 1, 0),
(11, 'blog', 7, 7, 1, 0),
(12, 'blog', 7, 8, 1, 0),
(13, 'blog', 7, 9, 1, 0),
(14, 'blog', 7, 10, 1, 0),
(15, 'blog', 7, 11, 1, 0),
(16, 'blog', 7, 12, 1, 0),
(17, 'blog', 7, 13, 1, 0),
(18, 'blog', 7, 14, 1, 0),
(19, 'blog', 7, 15, 1, 0),
(20, 'blog', 7, 16, 1, 0),
(21, 'blog', 7, 17, 1, 0),
(22, 'blog', 7, 18, 1, 0),
(23, 'blog', 7, 19, 1, 0);

-- --------------------------------------------------------

--
-- 表的结构 `cms_tags`
--

DROP TABLE IF EXISTS `cms_tags`;
CREATE TABLE `cms_tags` (
  `t_id` int(10) UNSIGNED NOT NULL,
  `t_key` varchar(32) DEFAULT NULL,
  `t_title` varchar(128) NOT NULL COMMENT 'tag标题',
  `t_parent` int(10) NOT NULL DEFAULT '0' COMMENT '父tagID',
  `t_subindex` int(10) NOT NULL DEFAULT '0' COMMENT '最小的index',
  `t_index` int(10) NOT NULL DEFAULT '0' COMMENT '自身的index',
  `t_children` varchar(2048) DEFAULT '' COMMENT '下级tag',
  `t_order` int(10) NOT NULL DEFAULT '0' COMMENT 'tag排序'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='tags';

--
-- 转存表中的数据 `cms_tags`
--

INSERT INTO `cms_tags` (`t_id`, `t_key`, `t_title`, `t_parent`, `t_subindex`, `t_index`, `t_children`, `t_order`) VALUES
(5, 'hecate', 'Hecate', 0, 0, 0, '', 0),
(6, 'readme', '开发日志', 0, 0, 0, '', 0),
(7, 'blog', 'blog', 0, 0, 0, '', 0);

-- --------------------------------------------------------

--
-- 表的结构 `cms_views`
--

DROP TABLE IF EXISTS `cms_views`;
CREATE TABLE `cms_views` (
  `v_id` int(10) UNSIGNED NOT NULL,
  `user_code` varchar(32) NOT NULL COMMENT '用户编码',
  `v_key` varchar(32) DEFAULT NULL COMMENT 'views key',
  `v_title` varchar(128) NOT NULL COMMENT 'views标题',
  `v_path` varchar(128) NOT NULL DEFAULT '0' COMMENT '模板路径',
  `v_fixed_param` text COMMENT '固定参数',
  `v_format_param` text COMMENT '参数格式',
  `v_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否删除,默认为0 0:未删除 1:已删除'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='显示表';

--
-- 转存表中的数据 `cms_views`
--

INSERT INTO `cms_views` (`v_id`, `user_code`, `v_key`, `v_title`, `v_path`, `v_fixed_param`, `v_format_param`, `v_status`) VALUES
(1, 'U0000000004', 'index', '首页', 'cms/index', '{\"tags\":[{\"tagname\":\"blog\",\"intersection\":1,\"children\":0}]}', '', 0),
(2, 'U0000000004', 'page', '列表页', 'cms/page', '', '', 0),
(3, 'U0000000004', 'article', '文章正文', 'cms/article', '', '', 0),
(4, 'U0000000004', 'aboutme', '关于', 'cms/article', '{\"article\":5}', '', 0);

-- --------------------------------------------------------

--
-- 表的结构 `xx_account`
--

DROP TABLE IF EXISTS `xx_account`;
CREATE TABLE `xx_account` (
  `account_id` int(10) UNSIGNED NOT NULL,
  `account_name` varchar(32) DEFAULT '' COMMENT '帐户类型名',
  `account_code` varchar(32) DEFAULT '' COMMENT '帐户类型编号'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='帐户类型表';

--
-- 转存表中的数据 `xx_account`
--

INSERT INTO `xx_account` (`account_id`, `account_name`, `account_code`) VALUES
(1, '金币', 'rmb'),
(2, '我的世界-点券', 'mc');

-- --------------------------------------------------------

--
-- 表的结构 `xx_account_frozen`
--

DROP TABLE IF EXISTS `xx_account_frozen`;
CREATE TABLE `xx_account_frozen` (
  `af_id` int(10) UNSIGNED NOT NULL,
  `ac_id` int(10) NOT NULL COMMENT '用户资金帐户ID',
  `af_frozen` decimal(15,2) DEFAULT '0.00' COMMENT '冻结资金额',
  `op_code` varchar(32) NOT NULL COMMENT '操作人编码',
  `af_type` int(10) DEFAULT '1' COMMENT '操作类型',
  `af_info` varchar(2048) DEFAULT NULL COMMENT '操作说明',
  `al_time` int(10) DEFAULT NULL COMMENT '操作时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户冰结资金表';

-- --------------------------------------------------------

--
-- 表的结构 `xx_account_frozen_log`
--

DROP TABLE IF EXISTS `xx_account_frozen_log`;
CREATE TABLE `xx_account_frozen_log` (
  `afl_id` int(10) UNSIGNED NOT NULL,
  `ac_id` int(10) NOT NULL COMMENT '用户资金帐户ID',
  `op_code` varchar(32) NOT NULL COMMENT '操作人编码',
  `afl_type` int(10) DEFAULT '1' COMMENT '操作类型 1：冻结 2：解冻',
  `afl_info` varchar(2048) DEFAULT NULL COMMENT '操作说明',
  `afl_time` int(10) DEFAULT NULL COMMENT '操作时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户资金帐号冰结日志表';

-- --------------------------------------------------------

--
-- 表的结构 `xx_account_log`
--

DROP TABLE IF EXISTS `xx_account_log`;
CREATE TABLE `xx_account_log` (
  `al_id` int(10) UNSIGNED NOT NULL,
  `ac_id` int(10) NOT NULL COMMENT '用户资金帐户ID',
  `al_amount` decimal(15,2) DEFAULT '0.00' COMMENT '变更金额',
  `al_type` int(11) DEFAULT '0' COMMENT '变更类型',
  `foreign_key` int(11) DEFAULT '0' COMMENT '关联ID',
  `op_code` varchar(32) NOT NULL COMMENT '操作人编码',
  `al_mark` varchar(32) NOT NULL COMMENT '标识串 系统自动生成的6位随机串',
  `al_time` int(10) DEFAULT NULL COMMENT '操作时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户资源变更表';

-- --------------------------------------------------------

--
-- 表的结构 `xx_account_mc`
--

DROP TABLE IF EXISTS `xx_account_mc`;
CREATE TABLE `xx_account_mc` (
  `ac_id` int(10) UNSIGNED NOT NULL,
  `user_code` varchar(32) NOT NULL COMMENT '用户编码',
  `ac_capital` decimal(10,2) DEFAULT '0.00' COMMENT '资金额',
  `ac_frozen` decimal(10,2) DEFAULT '0.00' COMMENT '冻结资金额',
  `ac_time` int(10) DEFAULT NULL COMMENT '最后操作时间',
  `al_id` int(10) NOT NULL COMMENT '最后修改记录ID',
  `ac_lock` char(1) DEFAULT '0' COMMENT '互斥锁 0:未锁定 1:已锁定',
  `ac_mark` varchar(32) NOT NULL COMMENT '记录修改标识',
  `ac_flag` int(11) DEFAULT '1' COMMENT '0:删除 1:正常 2:冰结'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户资金表';

-- --------------------------------------------------------

--
-- 表的结构 `xx_account_mc_log`
--

DROP TABLE IF EXISTS `xx_account_mc_log`;
CREATE TABLE `xx_account_mc_log` (
  `al_id` int(10) UNSIGNED NOT NULL,
  `ac_id` int(10) NOT NULL COMMENT '用户资金帐户ID',
  `al_amount` decimal(15,2) DEFAULT '0.00' COMMENT '变更金额',
  `al_type` int(11) DEFAULT '0' COMMENT '变更类型',
  `foreign_key` int(11) DEFAULT '0' COMMENT '关联ID',
  `al_mark` varchar(32) NOT NULL COMMENT '标识串 系统自动生成的6位随机串',
  `al_time` int(10) DEFAULT NULL COMMENT '操作时间',
  `al_capital` decimal(10,2) DEFAULT '0.00' COMMENT '变更当前资金额',
  `al_log` varchar(256) DEFAULT '' COMMENT '日志内容'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户资源变更表';

-- --------------------------------------------------------

--
-- 表的结构 `xx_account_recharge`
--

DROP TABLE IF EXISTS `xx_account_recharge`;
CREATE TABLE `xx_account_recharge` (
  `ar_id` int(12) UNSIGNED NOT NULL,
  `user_code` varchar(32) NOT NULL COMMENT '用户',
  `ar_sn` varchar(32) NOT NULL COMMENT '订单编码',
  `ar_money` decimal(15,2) DEFAULT '0.00' COMMENT '资金数',
  `ar_channel` varchar(32) NOT NULL COMMENT '第三方支付的渠道名',
  `ar_extra_param` varchar(8) DEFAULT NULL COMMENT '标识符',
  `ar_trans_id` varchar(32) NOT NULL COMMENT '第三方交易流水ID',
  `ar_remark` varchar(1024) NOT NULL COMMENT '备注,记录失败原因等',
  `ar_add_time` int(10) DEFAULT '0' COMMENT '生成时间',
  `ar_submit_time` int(10) DEFAULT '0' COMMENT '提交时间',
  `ar_end_time` int(10) DEFAULT '0' COMMENT '完成时间',
  `ar_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0:未提交　1:已提交 2:已完成 3:失败'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='资金充帐表';

--
-- 转存表中的数据 `xx_account_recharge`
--

INSERT INTO `xx_account_recharge` (`ar_id`, `user_code`, `ar_sn`, `ar_money`, `ar_channel`, `ar_extra_param`, `ar_trans_id`, `ar_remark`, `ar_add_time`, `ar_submit_time`, `ar_end_time`, `ar_status`) VALUES
(2, 'U0000000003', 'C151027298516527', '0.10', 'alipay', 'uGJiFJN0', '', '', 1445939171, 0, 0, 0),
(3, 'U0000000003', 'C151027721844115', '0.10', 'alipay', 'p1hdfk6V', '', '', 1445939173, 0, 0, 0),
(4, 'U0000000003', 'C151027487556487', '0.10', 'alipay', 'U0enTf4Q', '', '', 1445939590, 0, 0, 0),
(5, 'U0000000003', 'C151027788732659', '0.10', 'alipay', 'QAiLgl4C', '', '', 1445939621, 0, 0, 0),
(6, 'U0000000003', 'C151027626509497', '0.10', 'alipay', 'YqP974Ln', '', '', 1445939662, 0, 0, 0),
(7, 'U0000000003', 'C151027672745767', '0.10', 'alipay', 'HizbksIc', '', '', 1445939717, 0, 0, 0),
(8, 'U0000000003', 'C151027025393587', '0.10', 'alipay', 'P2jJ4Ifv', '', '', 1445939730, 0, 0, 0),
(9, 'U0000000003', 'C151027423681367', '0.10', 'alipay', 'RjWHv8HV', '', '', 1445939852, 0, 0, 0),
(10, 'U0000000003', 'C151027112521617', '0.10', 'alipay', 'LmDi9z00', '', '', 1445940462, 0, 0, 0),
(11, 'U0000000003', 'C151027825302341', '0.10', 'alipay', 'Gmw17hU5', '', '', 1445940473, 0, 0, 0),
(12, 'U0000000003', 'C151027035391251', '0.10', 'alipay', '7B3xvq69', '', '', 1445940476, 0, 0, 0),
(13, 'U0000000003', 'C151027039911051', '0.10', 'alipay', 'MMZoc2xp', '', '', 1445940478, 0, 0, 0),
(14, 'U0000000003', 'C151028150994439', '0.10', 'alipay', '077Q6NHF', '', '', 1446017564, 0, 0, 0),
(15, 'U0000000003', 'C151028878198131', '0.10', 'alipay', 't6l5pAnm', '', '', 1446017638, 0, 0, 0),
(16, 'U0000000003', 'C151028433987491', '0.10', 'alipay', 'UZga2EBz', '', '', 1446017666, 0, 0, 0),
(17, 'U0000000003', 'C151028214634741', '0.10', 'alipay', '1MwKXexI', '', '', 1446017681, 0, 0, 0),
(18, 'U0000000003', 'C151028190733959', '0.10', 'alipay', 'wtcJxk1r', '', '', 1446017828, 0, 0, 0),
(19, 'U0000000003', 'C151028449650371', '0.10', 'alipay', 'Ln4xdkrU', '', '', 1446018018, 0, 0, 0),
(20, 'U0000000003', 'C151028027690911', '0.10', 'alipay', 'aa', '11', 'bb', 1446019622, 1446019622, 1446021550, 2),
(21, 'U0000000003', 'C151028128534637', '0.10', 'alipay', 'Fo9qsi4X', '', '', 1446024314, 1446024314, 0, 1),
(22, 'U0000000003', 'C151028172463059', '0.10', 'alipay', 'f83k00Hb', '', '', 1446025107, 1446025107, 0, 1);

-- --------------------------------------------------------

--
-- 表的结构 `xx_account_rmb`
--

DROP TABLE IF EXISTS `xx_account_rmb`;
CREATE TABLE `xx_account_rmb` (
  `ac_id` int(10) UNSIGNED NOT NULL,
  `user_code` varchar(32) NOT NULL COMMENT '用户编码',
  `ac_capital` decimal(10,2) DEFAULT '0.00' COMMENT '资金额',
  `ac_frozen` decimal(10,2) DEFAULT '0.00' COMMENT '冻结资金额',
  `ac_time` int(10) DEFAULT NULL COMMENT '最后操作时间',
  `al_id` int(10) NOT NULL COMMENT '最后修改记录ID',
  `ac_lock` char(1) DEFAULT '0' COMMENT '互斥锁 0:未锁定 1:已锁定',
  `ac_mark` varchar(32) NOT NULL COMMENT '记录修改标识',
  `ac_flag` int(11) DEFAULT '1' COMMENT '0:删除 1:正常 2:冰结'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户资金表';

--
-- 转存表中的数据 `xx_account_rmb`
--

INSERT INTO `xx_account_rmb` (`ac_id`, `user_code`, `ac_capital`, `ac_frozen`, `ac_time`, `al_id`, `ac_lock`, `ac_mark`, `ac_flag`) VALUES
(1, 'U0000000003', '100.50', '0.00', 1446443677, 5, '0', '13c33addfc5414cef2d657ff776eb4b4', 1),
(2, 'U0000000008', '0.00', '0.00', 1446109850, 2, '0', 'e9cf75da7f18db2aa056adbdda3c6be2', 1);

-- --------------------------------------------------------

--
-- 表的结构 `xx_account_rmb_log`
--

DROP TABLE IF EXISTS `xx_account_rmb_log`;
CREATE TABLE `xx_account_rmb_log` (
  `al_id` int(10) UNSIGNED NOT NULL,
  `ac_id` int(10) NOT NULL COMMENT '用户资金帐户ID',
  `al_amount` decimal(15,2) DEFAULT '0.00' COMMENT '变更金额',
  `al_type` int(11) DEFAULT '0' COMMENT '变更类型',
  `foreign_key` int(11) DEFAULT '0' COMMENT '关联ID',
  `al_mark` varchar(32) NOT NULL COMMENT '标识串 系统自动生成的6位随机串',
  `al_time` int(10) DEFAULT NULL COMMENT '操作时间',
  `al_capital` decimal(10,2) DEFAULT '0.00' COMMENT '变更当前资金额',
  `al_log` varchar(256) DEFAULT '' COMMENT '日志内容'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户资源变更表';

--
-- 转存表中的数据 `xx_account_rmb_log`
--

INSERT INTO `xx_account_rmb_log` (`al_id`, `ac_id`, `al_amount`, `al_type`, `foreign_key`, `al_mark`, `al_time`, `al_capital`, `al_log`) VALUES
(1, 1, '0.00', 0, 0, 'bPlWGw', 1446099769, '0.00', ''),
(2, 2, '0.00', 0, 0, 'QH16iL', 1446109850, '0.00', ''),
(3, 1, '1.00', 7, 0, '7DIOlI', 1446443598, '0.00', 'U0000000003'),
(4, 1, '0.50', 105, 0, '7LrYro', 1446443614, '1.00', 'U0000000003'),
(5, 1, '100.00', 7, 0, '1Em9U6', 1446443677, '0.50', 'U0000000003');

-- --------------------------------------------------------

--
-- 表的结构 `xx_account_withdrawscash`
--

DROP TABLE IF EXISTS `xx_account_withdrawscash`;
CREATE TABLE `xx_account_withdrawscash` (
  `aw_id` int(12) UNSIGNED NOT NULL,
  `user_code` varchar(32) NOT NULL COMMENT '用户',
  `aw_check_code` varchar(32) NOT NULL COMMENT '审核人code',
  `aw_money` decimal(15,2) DEFAULT '0.00' COMMENT '资金数',
  `aw_cash_counter_fee` decimal(10,2) DEFAULT '0.00' COMMENT '手续费',
  `aw_channel` varchar(32) NOT NULL COMMENT '第三方支付的渠道名',
  `aw_trans_id` varchar(32) NOT NULL COMMENT '第三方交易流水ID',
  `aw_remark` varchar(1024) NOT NULL COMMENT '备注,记录失败原因等',
  `aw_add_time` int(10) DEFAULT '0' COMMENT '生成时间',
  `aw_check_time` int(10) DEFAULT '0' COMMENT '提交审核时间',
  `aw_submit_time` int(10) DEFAULT '0' COMMENT '提交转账时间',
  `aw_end_time` int(10) DEFAULT '0' COMMENT '完成时间',
  `aw_bankName` varchar(256) NOT NULL COMMENT '银行名称',
  `aw_cardNo` varchar(32) NOT NULL COMMENT '银行卡号',
  `aw_provice` varchar(256) NOT NULL COMMENT '开户省份',
  `aw_city` varchar(256) NOT NULL COMMENT '开户市',
  `aw_branchName` varchar(256) NOT NULL COMMENT '开户支付名称',
  `aw_accountName` varchar(256) NOT NULL COMMENT '开户人名称',
  `aw_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0:未提交　1:已提交审核 2:已提交转账 3:已完成 4:审核失败 5:转账失败'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='资金取现表';

-- --------------------------------------------------------

--
-- 表的结构 `xx_code_definition`
--

DROP TABLE IF EXISTS `xx_code_definition`;
CREATE TABLE `xx_code_definition` (
  `cd_id` int(10) UNSIGNED NOT NULL,
  `cd_name` varchar(64) NOT NULL COMMENT '编码名称',
  `cd_title` varchar(64) NOT NULL COMMENT '编码标题',
  `cd_type` tinyint(2) DEFAULT NULL COMMENT '编码类型 1:流水编码 2:检索编码',
  `cd_level` tinyint(2) DEFAULT '0' COMMENT '编码等级 0:系统编码 其它：用户编码',
  `cd_template` varchar(128) DEFAULT NULL COMMENT '编码模块 直观显示',
  `cd_attributeset` varchar(2048) DEFAULT NULL COMMENT '子属性值位，json数据型式',
  `cd_remark` varchar(256) DEFAULT NULL COMMENT '说明',
  `cd_status` tinyint(2) DEFAULT '0' COMMENT '状态 0:未使用  1:已使用',
  `cd_auto_create_flag` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否自动生成编码'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='编码定义表';

--
-- 转存表中的数据 `xx_code_definition`
--

INSERT INTO `xx_code_definition` (`cd_id`, `cd_name`, `cd_title`, `cd_type`, `cd_level`, `cd_template`, `cd_attributeset`, `cd_remark`, `cd_status`, `cd_auto_create_flag`) VALUES
(2, 'USERCODE', '用户编码', 1, 0, 'U0000000001', '[{\"index\":\"1\",\"type\":\"PREFIX\",\"value\":\"U\"},{\"index\":\"2\",\"type\":\"SERIALNUMBER\",\"loop\":\"NONE\",\"length\":\"10\"}]', '系统中用户的唯一编码', 1, 1),
(3, 'TESTCODE', '测试编码', 1, 0, 'TEST_USERDATA1-USERDATA2_YYYYMMDD_SERIALNUMBER-4-SERIALNUMBER-5', '[{\"index\":\"1\",\"type\":\"PREFIX\",\"value\":\"TEST\"},{\"index\":\"2\",\"type\":\"SYMBOL\",\"value\":\"_\"},{\"index\":\"3\",\"type\":\"USERNDATA\"},{\"index\":\"4\",\"type\":\"SYMBOL\",\"value\":\"-\"},{\"index\":\"5\",\"type\":\"USERNDATA\"},{\"index\":\"6\",\"type\":\"SYMBOL\",\"value\":\"_\"},{\"index\":\"7\",\"type\":\"TIMES\",\"value\":\"YYYYMMDD\"},{\"index\":\"8\",\"type\":\"SYMBOL\",\"value\":\"_\"},{\"index\":\"9\",\"type\":\"SERIALNUMBER\",\"loop\":\"NONE\",\"length\":\"4\"},{\"index\":\"10\",\"type\":\"SYMBOL\",\"value\":\"-\"},{\"index\":\"11\",\"type\":\"SERIALNUMBER\",\"loop\":\"YYYYMMDD\",\"length\":\"5\"}]', '测试编码', 1, 1),
(4, 'ROLECODE', '角色编码', 1, 0, 'R00001', '[{\"index\":\"1\",\"type\":\"PREFIX\",\"value\":\"ROLE\"},{\"index\":\"2\",\"type\":\"SERIALNUMBER\",\"loop\":\"NONE\",\"length\":\"5\"}]', '系统中角色的唯一编码', 1, 1),
(5, 'RESOURCESCODE', '资源编码', 1, 0, 'RES0000001', '[{\"index\":\"1\",\"type\":\"PREFIX\",\"value\":\"RES\"},{\"index\":\"2\",\"type\":\"SERIALNUMBER\",\"loop\":\"NONE\",\"length\":\"7\"}]', '系统中资源的唯一编码', 1, 1),
(7, 'URLCODE', '功能路径编码', 2, 0, 'URL01001', '[{\"index\":\"1\",\"type\":\"PREFIX\",\"value\":\"URL\"},{\"index\":\"2\",\"name\":\"app\",\"length\":\"2\",\"type\":\"SEQUENCE\",\"base\":\"1\"},{\"index\":\"3\",\"name\":\"act\",\"length\":\"2\",\"type\":\"HASH\",\"base\":\"ABCDEFGHIJKLMN\"}]', '功能路径编码', 1, 1),
(10, 'ORDERCODE', '订单编号', 1, 0, 'D201501050001', '[{\"index\":\"1\",\"type\":\"PREFIX\",\"value\":\"D\"},{\"index\":\"2\",\"type\":\"TIMES\",\"value\":\"YYYYMMDD\"},{\"index\":\"3\",\"type\":\"SERIALNUMBER\",\"loop\":\"YYYYMMDD\",\"length\":\"4\"},{\"index\":\"4\",\"type\":\"PREFIX\",\"value\":\"8\"}]', '订单编号', 1, 1),
(11, 'MENUCODE', '目录编码', 1, 0, 'MENU00001', '[{\"index\":\"1\",\"type\":\"PREFIX\",\"value\":\"MENU\"},{\"index\":\"2\",\"type\":\"SERIALNUMBER\",\"loop\":\"NONE\",\"length\":\"5\"}]', '系统中菜单的唯一编码', 1, 1),
(12, 'RECHARGECODE', '充值定单编码', 1, 0, 'C151027123456789', '[{\"index\":\"1\",\"type\":\"PREFIX\",\"value\":\"C\"},{\"index\":\"2\",\"type\":\"TIMES\",\"value\":\"YYMMDD\"},\r\n{\"index\":\"3\",\"type\":\"ENCODEID\",\"length\":\"9\"}]', '充值定单编码', 1, 1),
(13, 'GAMESERVERCODE', '游戏服编码', 1, 0, 'GS0000001', '[{\"index\":\"1\",\"type\":\"PREFIX\",\"value\":\"GS\"},{\"index\":\"2\",\"type\":\"SERIALNUMBER\",\"loop\":\"NONE\",\"length\":\"7\"}]', '游戏服务器的唯一编码', 1, 1);

-- --------------------------------------------------------

--
-- 表的结构 `xx_code_info`
--

DROP TABLE IF EXISTS `xx_code_info`;
CREATE TABLE `xx_code_info` (
  `ci_id` int(10) UNSIGNED NOT NULL,
  `cd_name` varchar(64) NOT NULL COMMENT '编码名称',
  `ci_usercode` varchar(64) NOT NULL COMMENT '用户编码',
  `ci_serial_flag` varchar(1024) DEFAULT NULL COMMENT '流水回归标识 json数组',
  `ci_serial_value` varchar(1024) DEFAULT NULL COMMENT '流水当前值 json数组'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='编码值表';

--
-- 转存表中的数据 `xx_code_info`
--

INSERT INTO `xx_code_info` (`ci_id`, `cd_name`, `ci_usercode`, `ci_serial_flag`, `ci_serial_value`) VALUES
(1, 'USERCODE', 'SYS', '{\"1\":\"NONE\"}', '{\"1\":8}'),
(2, 'ORDERCODE', 'SYS', '{\"2\":\"20150510\"}', '{\"2\":3}'),
(3, 'MENUCODE', 'SYS', '{\"1\":\"NONE\"}', '{\"1\":52}'),
(4, 'RESOURCESCODE', 'SYS', '{\"1\":\"NONE\"}', '{\"1\":68}'),
(5, 'ROLECODE', 'SYS', '{\"1\":\"NONE\"}', '{\"1\":5}'),
(6, 'GAMESERVERCODE', 'SYS', '{\"1\":\"NONE\"}', '{\"1\":4}');

-- --------------------------------------------------------

--
-- 表的结构 `xx_crontab`
--

DROP TABLE IF EXISTS `xx_crontab`;
CREATE TABLE `xx_crontab` (
  `crontab_id` int(10) UNSIGNED NOT NULL COMMENT '定时执行任务ID',
  `crontab_name` varchar(100) NOT NULL DEFAULT '' COMMENT '定时执行任务名称',
  `function` varchar(100) NOT NULL DEFAULT '' COMMENT '功能函数名',
  `param` varchar(100) NOT NULL DEFAULT '' COMMENT '功能函数执行参数',
  `count` int(10) NOT NULL DEFAULT '0' COMMENT '执行次数 -1:无限执行',
  `year` varchar(100) NOT NULL DEFAULT '*' COMMENT '年 *:代码全部',
  `month` varchar(100) NOT NULL DEFAULT '*' COMMENT '月 *:代码全部',
  `day` varchar(100) NOT NULL DEFAULT '*' COMMENT '日 *:代码全部',
  `hour` varchar(100) NOT NULL DEFAULT '*' COMMENT '时 *:代码全部',
  `minute` varchar(100) NOT NULL DEFAULT '*' COMMENT '分 *:代码全部',
  `second` varchar(100) NOT NULL DEFAULT '*' COMMENT '秒 *:代码全部',
  `week` varchar(100) NOT NULL DEFAULT '*' COMMENT '星期 *:代码全部',
  `remark` varchar(1024) NOT NULL DEFAULT '' COMMENT '定时执行任务描述'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='定时执行任务';

--
-- 转存表中的数据 `xx_crontab`
--

INSERT INTO `xx_crontab` (`crontab_id`, `crontab_name`, `function`, `param`, `count`, `year`, `month`, `day`, `hour`, `minute`, `second`, `week`, `remark`) VALUES
(5, '归位', 'homing', '6,12,13', 1, '*', '*', '*', '<1', '*', '*', '*', '晚上点执行　把其它任务的count置1, param为其它任务ID,以逗号分隔'),
(6, '复位归位功能', 'homing', '5', 0, '*', '*', '*', '>1', '*', '*', '*', '晚上执行,复位归位功能'),
(7, '发送邮件', 'send_email', '', -1, '*', '*', '*', '*', '*', '*', '*', '发送邮件'),
(8, '自动出借', 'auto_loan', '', -1, '*', '*', '*', '*', '*', '*', '*', '自动出借'),
(9, '发送短信', 'send_sms', '', -1, '*', '*', '*', '*', '*', '*', '*', '发送短信'),
(12, '处理还款状态', 'manage_order_repayment', '', 1, '*', '*', '*', '>1', '*', '*', '*', '处理还款状态'),
(13, '正常催收还款', 'normal_press', '', 0, '*', '*', '*', '>9', '*', '*', '*', '正常催收还款'),
(14, '自动还款', 'auto_repayment', '', -1, '*', '*', '*', '>23', '<10', '*', '*', '自动还款');

-- --------------------------------------------------------

--
-- 表的结构 `xx_mail_list`
--

DROP TABLE IF EXISTS `xx_mail_list`;
CREATE TABLE `xx_mail_list` (
  `maill_id` int(12) UNSIGNED NOT NULL,
  `mailt_key` varchar(32) NOT NULL COMMENT '邮件KEY',
  `maill_title` varchar(128) NOT NULL COMMENT '邮件标题',
  `maill_address` varchar(64) NOT NULL COMMENT '邮件地址',
  `maill_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0:未发送　1:已发送　2:马上发送',
  `maill_content` text NOT NULL COMMENT '邮件内容',
  `maill_add_time` int(10) DEFAULT '0' COMMENT '加入时间',
  `maill_send_time` int(10) DEFAULT '0' COMMENT '发送时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='邮件发送表';

-- --------------------------------------------------------

--
-- 表的结构 `xx_mail_template`
--

DROP TABLE IF EXISTS `xx_mail_template`;
CREATE TABLE `xx_mail_template` (
  `mailt_id` int(12) UNSIGNED NOT NULL,
  `mailt_key` varchar(32) NOT NULL COMMENT '邮件KEY',
  `mailt_title` varchar(128) NOT NULL COMMENT '邮件标题',
  `mailt_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0:不发送　1:发送',
  `mailt_content` text NOT NULL COMMENT '邮件内容',
  `mailt_dsc` varchar(1024) NOT NULL COMMENT '邮件内容描述'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='邮件模板表';

-- --------------------------------------------------------

--
-- 表的结构 `xx_menu`
--

DROP TABLE IF EXISTS `xx_menu`;
CREATE TABLE `xx_menu` (
  `menu_id` int(10) UNSIGNED NOT NULL,
  `menu_code` varchar(32) NOT NULL COMMENT '目录编码',
  `menu_title` varchar(64) NOT NULL COMMENT '目录标题',
  `menu_pinyin` varchar(64) NOT NULL COMMENT '目录标题拼音',
  `menu_href` varchar(128) NOT NULL DEFAULT '/' COMMENT '目录URL',
  `menu_subs` tinyint(1) DEFAULT '0' COMMENT '是否有子菜单 0:没有 >1:子菜单个数',
  `menu_icon` varchar(32) DEFAULT NULL COMMENT '样式',
  `menu_parent` int(11) DEFAULT NULL COMMENT '父菜单',
  `menu_parent_flag` varchar(1024) DEFAULT NULL COMMENT '父菜单标记,如 [1][45]',
  `menu_order` int(11) NOT NULL DEFAULT '1024' COMMENT '排序'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='目录表\r\n';

--
-- 转存表中的数据 `xx_menu`
--

INSERT INTO `xx_menu` (`menu_id`, `menu_code`, `menu_title`, `menu_pinyin`, `menu_href`, `menu_subs`, `menu_icon`, `menu_parent`, `menu_parent_flag`, `menu_order`) VALUES
(1, 'MENU00018', '系统菜单', '', '/', 0, NULL, 0, '[1]', 1024),
(2, 'MENU00019', '普通会员菜单', '', '/', 0, NULL, 0, '[2]', 1024),
(11, 'MENU00027', '工作台', '', '/admin', 0, 'icon icon-th-large', 1, '[1][11]', 1),
(12, 'MENU00028', '会员管理', '', '/user', 0, 'icon icon-user', 1, '[1][12]', 2),
(10, 'MENU00026', '用户中心', '', '/admin', 0, '', 2, '[2][10]', 1),
(13, 'MENU00029', '系统设置', '', '#', 0, 'icon icon-cog', 1, '[1][13]', 9),
(14, 'MENU00030', '菜单管理', '', '/menu', 0, 'icon icon-list-ul', 13, '[1][13][14]', 901),
(20, 'MENU00036', '帐户管理', '', '/account', 0, 'icon icon-yen', 1, '[1][20]', 3),
(19, 'MENU00035', 'test1', '', '', 0, 'icon icon-star', 18, '[18][19]', 0),
(21, 'MENU00037', '会员列表', '', '/user', 0, 'icon icon-table', 12, '[1][12][21]', 201),
(25, 'MENU00041', 'URL', '', '/url', 0, 'icon icon-hand-up', 13, '[1][13][25]', 902),
(26, 'MENU00042', '权限管理', '', '/auth/reslist', 0, 'icon icon-key', 13, '[1][13][26]', 903),
(31, 'MENU00047', '内容管理', '', '/cms/contentlist', 0, 'icon icon-stack', 1, '[1][11][31]', 3),
(32, 'MENU00048', '内容列表', '', '/cms/contentlist', 0, '', 31, '[1][11][31][32]', 21),
(33, 'MENU00049', '视图列表', '', '/cms/viewslist', 0, '', 31, '[1][11][31][33]', 22),
(34, 'MENU00050', 'TAG管理', '', '/cms/tagslist', 0, '', 31, '[1][11][31][34]', 23),
(36, 'MENU00052', '游戏管理', '', '', 0, '', 35, '[35][36]', 1);

-- --------------------------------------------------------

--
-- 表的结构 `xx_permission`
--

DROP TABLE IF EXISTS `xx_permission`;
CREATE TABLE `xx_permission` (
  `per_id` int(10) UNSIGNED NOT NULL,
  `role_id` int(10) NOT NULL COMMENT '角色id',
  `res_id` int(10) NOT NULL COMMENT '资源id',
  `per_type` tinyint(2) NOT NULL DEFAULT '0' COMMENT '权限类型 0:白名单 1:黑名单',
  `per_remark` varchar(256) DEFAULT NULL COMMENT '角色说明'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='权限表';

--
-- 转存表中的数据 `xx_permission`
--

INSERT INTO `xx_permission` (`per_id`, `role_id`, `res_id`, `per_type`, `per_remark`) VALUES
(62, 1, 63, 0, NULL),
(2, 1, 56, 0, NULL),
(3, 1, 52, 0, NULL),
(4, 1, 53, 0, NULL),
(5, 1, 57, 0, NULL),
(6, 1, 61, 0, NULL),
(63, 1, 62, 0, NULL),
(8, 1, 54, 0, NULL),
(9, 1, 59, 0, NULL),
(10, 1, 55, 0, NULL),
(11, 1, 50, 0, NULL),
(12, 1, 49, 0, NULL),
(13, 1, 48, 0, NULL),
(14, 1, 47, 0, NULL),
(15, 1, 51, 0, NULL),
(16, 1, 46, 0, NULL),
(17, 1, 42, 0, NULL),
(18, 1, 43, 0, NULL),
(19, 1, 44, 0, NULL),
(20, 1, 45, 0, NULL),
(21, 1, 39, 0, NULL),
(22, 1, 35, 0, NULL),
(23, 1, 31, 0, NULL),
(24, 1, 36, 0, NULL),
(25, 1, 32, 0, NULL),
(26, 1, 33, 0, NULL),
(27, 1, 37, 0, NULL),
(28, 1, 38, 0, NULL),
(29, 1, 34, 0, NULL),
(30, 1, 28, 0, NULL),
(31, 1, 24, 0, NULL),
(32, 1, 20, 0, NULL),
(33, 1, 21, 0, NULL),
(34, 1, 29, 0, NULL),
(35, 1, 25, 0, NULL),
(36, 1, 30, 0, NULL),
(37, 1, 26, 0, NULL),
(38, 1, 22, 0, NULL),
(39, 1, 23, 0, NULL),
(40, 1, 27, 0, NULL),
(41, 1, 19, 0, NULL),
(42, 1, 18, 0, NULL),
(43, 1, 16, 0, NULL),
(44, 1, 12, 0, NULL),
(45, 1, 8, 0, NULL),
(46, 1, 9, 0, NULL),
(47, 1, 13, 0, NULL),
(48, 1, 17, 0, NULL),
(49, 1, 14, 0, NULL),
(50, 1, 10, 0, NULL),
(51, 1, 11, 0, NULL),
(52, 1, 15, 0, NULL),
(65, 1, 68, 0, NULL),
(55, 1, 7, 0, NULL),
(56, 1, 6, 0, NULL),
(57, 1, 2, 0, NULL),
(58, 1, 5, 0, NULL),
(59, 1, 1, 0, NULL),
(60, 1, 3, 0, NULL),
(61, 1, 4, 0, NULL),
(66, 1, 67, 0, NULL),
(67, 1, 66, 0, NULL);

-- --------------------------------------------------------

--
-- 表的结构 `xx_resources`
--

DROP TABLE IF EXISTS `xx_resources`;
CREATE TABLE `xx_resources` (
  `res_id` int(10) UNSIGNED NOT NULL,
  `res_code` varchar(32) NOT NULL COMMENT '资源编码',
  `res_name` varchar(64) NOT NULL COMMENT '资源名称',
  `res_data` varchar(128) NOT NULL COMMENT '资源内容',
  `res_extend` varchar(32) DEFAULT NULL COMMENT '父级资源 NONE：顶级资源',
  `res_type` varchar(256) DEFAULT NULL COMMENT '资源类型 如：URL MENU DATA',
  `res_group` varchar(256) DEFAULT NULL COMMENT '资源分组 如：用户管理  资源管理',
  `res_remark` varchar(256) DEFAULT NULL COMMENT '资源说明'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='资源表';

--
-- 转存表中的数据 `xx_resources`
--

INSERT INTO `xx_resources` (`res_id`, `res_code`, `res_name`, `res_data`, `res_extend`, `res_type`, `res_group`, `res_remark`) VALUES
(1, 'RES0000001', '资金管理首页', 'account.index', NULL, 'URL', '资金管理', ''),
(2, 'RES0000002', '资金日志', 'account.get_accountlog', NULL, 'URL', '资金管理', ''),
(3, 'RES0000003', '重置帐户', 'account.reset_account', NULL, 'URL', '资金管理', ''),
(4, 'RES0000004', '锁定帐户', 'account.lock_account', NULL, 'URL', '资金管理', ''),
(5, 'RES0000005', '解锁帐户', 'account.unlock_account', NULL, 'URL', '资金管理', ''),
(6, 'RES0000006', '调整用户帐户余款', 'account.ajuster_accountcapital', NULL, 'URL', '资金管理', ''),
(7, 'RES0000007', '后台首页', 'admin.index', NULL, 'URL', '后台管理', ''),
(8, 'RES0000008', '资源列表', 'auth.reslist', NULL, 'URL', '权限管理', ''),
(9, 'RES0000009', '编辑资源', 'auth.edit_res', NULL, 'URL', '权限管理', ''),
(10, 'RES0000010', '删除资源', 'auth.del_res', NULL, 'URL', '权限管理', ''),
(11, 'RES0000011', '角色列表', 'auth.rolelist', NULL, 'URL', '权限管理', ''),
(12, 'RES0000012', '编辑角色', 'auth.edit_role', NULL, 'URL', '权限管理', ''),
(13, 'RES0000013', '删除角色', 'auth.del_role', NULL, 'URL', '权限管理', ''),
(14, 'RES0000014', '角色白名单', 'auth.whitelist', NULL, 'URL', '权限管理', ''),
(15, 'RES0000015', '角色黑名单', 'auth.blacklist', NULL, 'URL', '权限管理', ''),
(16, 'RES0000016', '设置角色权限', 'auth.manage_permission', NULL, 'URL', '权限管理', ''),
(17, 'RES0000017', '刷新权限缓存', 'auth.refresh_authcache', NULL, 'URL', '权限管理', ''),
(18, 'RES0000018', 'ayeer笔记', 'ayeer.index', NULL, 'URL', 'ayeer笔记', ''),
(19, 'RES0000019', 'ayeer笔记接口', 'ayeer.api', NULL, 'URL', 'ayeer笔记', ''),
(20, 'RES0000020', '内容管理首页', 'cms.index', NULL, 'URL', '内容管理', ''),
(21, 'RES0000021', '文章列表', 'cms.contentlist', NULL, 'URL', '内容管理', ''),
(22, 'RES0000022', '编辑文章', 'cms.form_content', NULL, 'URL', '内容管理', ''),
(23, 'RES0000023', '删除文章', 'cms.del_content', NULL, 'URL', '内容管理', ''),
(24, 'RES0000024', '视图列表', 'cms.viewslist', NULL, 'URL', '内容管理', ''),
(25, 'RES0000025', '编辑视图', 'cms.form_views', NULL, 'URL', '内容管理', ''),
(26, 'RES0000026', '删除视图', 'cms.del_views', NULL, 'URL', '内容管理', ''),
(27, 'RES0000027', '标签列表', 'cms.tagslist', NULL, 'URL', '内容管理', ''),
(28, 'RES0000028', '编辑标签', 'cms.form_tags', NULL, 'URL', '内容管理', ''),
(29, 'RES0000029', '删除标签', 'cms.del_tags', NULL, 'URL', '内容管理', ''),
(30, 'RES0000030', '刷新内容标签缓存', 'cms.refresh_tagscache', NULL, 'URL', '内容管理', ''),
(31, 'RES0000031', '目录管理首页', 'menu.index', NULL, 'URL', '目录管理', ''),
(32, 'RES0000032', '获取顶级目录', 'menu.get_allmenutree', NULL, 'URL', '目录管理', ''),
(33, 'RES0000033', '增加顶级目录', 'menu.add_menutree', NULL, 'URL', '目录管理', ''),
(34, 'RES0000034', '编辑项级目录', 'menu.form_menutree', NULL, 'URL', '目录管理', ''),
(35, 'RES0000035', '获取目录', 'menu.get_menu', NULL, 'URL', '目录管理', ''),
(36, 'RES0000036', '增加目录', 'menu.add_menu', NULL, 'URL', '目录管理', ''),
(37, 'RES0000037', '编辑目录', 'menu.form_menu', NULL, 'URL', '目录管理', ''),
(38, 'RES0000038', '删除目录', 'menu.del_menu', NULL, 'URL', '目录管理', ''),
(39, 'RES0000039', '支付首页', 'pay.index', NULL, 'URL', '支付管理', ''),
(68, 'RES0000068', '刷新系统设置缓存', 'setting.refresh_syscache', NULL, 'URL', '后台管理', ''),
(67, 'RES0000067', '编辑系统设置', 'setting.edit', NULL, 'URL', '后台管理', ''),
(42, 'RES0000042', 'ueditor辅助入口', 'ueditor.index', NULL, 'URL', 'ueditor辅助', ''),
(43, 'RES0000043', '上传控制', 'ueditor.action_upload', NULL, 'URL', 'ueditor辅助', ''),
(44, 'RES0000044', '	获取列表', 'ueditor.action_list', NULL, 'URL', 'ueditor辅助', ''),
(45, 'RES0000045', '	获取文件', 'ueditor.getfiles', NULL, 'URL', 'ueditor辅助', ''),
(46, 'RES0000046', '	操作', 'ueditor.action_crawler', NULL, 'URL', 'ueditor辅助', ''),
(47, 'RES0000047', '路径管理首页', 'url.index', NULL, 'URL', '路径管理', ''),
(48, 'RES0000048', '编辑路径', 'url.edit_url', NULL, 'URL', '路径管理', ''),
(49, 'RES0000049', '删除路径', 'url.del_url', NULL, 'URL', '路径管理', ''),
(50, 'RES0000050', '增加路径资源', 'url.add_urlres', NULL, 'URL', '路径管理', ''),
(51, 'RES0000051', '刷新缓存', 'url.refresh_urlcache', NULL, 'URL', '路径管理', ''),
(52, 'RES0000052', '用户管理首页', 'user.index', NULL, 'URL', '用户管理', ''),
(53, 'RES0000053', '用户详情', 'user.detailed', NULL, 'URL', '用户管理', ''),
(54, 'RES0000054', '用户个人信息', 'user.info', NULL, 'URL', '用户管理', ''),
(55, 'RES0000055', '用户角色', 'user.role', NULL, 'URL', '用户管理', ''),
(56, 'RES0000056', '用户资金', 'user.account', NULL, 'URL', '用户管理', ''),
(57, 'RES0000057', '用户安全', 'user.safe', NULL, 'URL', '用户管理', ''),
(63, 'RES0000063', '修改密码', 'login.changepwd', NULL, 'URL', '用户管理', ''),
(59, 'RES0000059', '删除用户', 'user.del_user', NULL, 'URL', '用户管理', ''),
(62, 'RES0000062', '重置密码', 'login.reset_pwd', NULL, 'URL', '用户管理', ''),
(61, 'RES0000061', '编辑用户', 'user.edit_user', NULL, 'URL', '用户管理', ''),
(66, 'RES0000066', '系统设置首页', 'setting.index', NULL, 'URL', '后台管理', '');

-- --------------------------------------------------------

--
-- 表的结构 `xx_role`
--

DROP TABLE IF EXISTS `xx_role`;
CREATE TABLE `xx_role` (
  `role_id` int(10) UNSIGNED NOT NULL,
  `role_code` varchar(32) NOT NULL COMMENT '角色编码',
  `role_name` varchar(64) NOT NULL COMMENT '角色名称',
  `role_extend` varchar(32) DEFAULT NULL COMMENT '父级角色',
  `role_menu` int(10) DEFAULT '0' COMMENT '角色使用的菜单',
  `role_homeurl` varchar(128) NOT NULL DEFAULT '' COMMENT '角色主页',
  `role_template` varchar(32) DEFAULT NULL COMMENT ' 角色显示模板',
  `role_remark` varchar(256) DEFAULT NULL COMMENT '角色说明'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='角色表';

--
-- 转存表中的数据 `xx_role`
--

INSERT INTO `xx_role` (`role_id`, `role_code`, `role_name`, `role_extend`, `role_menu`, `role_homeurl`, `role_template`, `role_remark`) VALUES
(1, 'ROLE00001', 'admin权限', NULL, 1, '/admin', 'admin/', 'admin权限'),
(2, 'ROLE00002', '游客角色', NULL, 0, '/', 'default/', '最基础的访问权限'),
(3, 'ROLE00003', '系统管理员', NULL, 1, '/admin', 'admin/', '系统管理员'),
(4, 'ROLE00004', '普通会员角色', NULL, 2, '/home', 'default/', '普通会员');

-- --------------------------------------------------------

--
-- 表的结构 `xx_sessions`
--

DROP TABLE IF EXISTS `xx_sessions`;
CREATE TABLE `xx_sessions` (
  `session_id` varchar(40) NOT NULL DEFAULT '0',
  `ip_address` varchar(45) NOT NULL DEFAULT '0',
  `user_agent` varchar(120) NOT NULL,
  `create_time` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `last_activity` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `user_data` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='系统sexxion表';

--
-- 转存表中的数据 `xx_sessions`
--

INSERT INTO `xx_sessions` (`session_id`, `ip_address`, `user_agent`, `create_time`, `last_activity`, `user_data`) VALUES
('179b36266b143ec13ca7275383c335df', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:50.0) Gecko/20100101 Firefox/50.0', 1482823002, 1482823002, 'a:2:{s:9:\"user_code\";s:11:\"U0000000004\";s:9:\"role_code\";s:9:\"ROLE00001\";}'),
('b1bc07622439ce056d26ab5e2b8c3121', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:50.0) Gecko/20100101 Firefox/50.0', 1483248319, 1483248319, 'a:4:{s:11:\"create_time\";i:1483248319;s:9:\"user_data\";s:0:\"\";s:6:\"tonken\";s:16:\"WTycQrCKTNoM429p\";s:15:\"token_lifecycle\";i:1;}');

-- --------------------------------------------------------

--
-- 表的结构 `xx_setting`
--

DROP TABLE IF EXISTS `xx_setting`;
CREATE TABLE `xx_setting` (
  `setting_id` int(12) UNSIGNED NOT NULL,
  `setting_title` varchar(128) NOT NULL COMMENT '配制名title',
  `setting_variable` varchar(32) NOT NULL COMMENT '配制参数名',
  `setting_value` varchar(1024) NOT NULL COMMENT '配制参数值',
  `setting_dsc` varchar(1024) NOT NULL COMMENT '配制参数描述',
  `setting_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0:不显示　1:显示'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='系统设置表';

--
-- 转存表中的数据 `xx_setting`
--

INSERT INTO `xx_setting` (`setting_id`, `setting_title`, `setting_variable`, `setting_value`, `setting_dsc`, `setting_type`) VALUES
(1, '登陆有效时间', 'cookies_time', '60000', '登陆有效时间', 1),
(23, '系统名称', 'sys_title', 'CAPTAIN', '系统显示的名称', 2),
(24, '系统简介', 'sys_dsc', 'captain', '系统简介', 2);

-- --------------------------------------------------------

--
-- 表的结构 `xx_sms_list`
--

DROP TABLE IF EXISTS `xx_sms_list`;
CREATE TABLE `xx_sms_list` (
  `smsl_id` int(12) UNSIGNED NOT NULL,
  `smst_key` varchar(32) NOT NULL COMMENT '短信KEY',
  `smsl_phonenum` varchar(32) NOT NULL COMMENT '电话号码',
  `smsl_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0:未发送　1:已发送 2:马上发送',
  `smsl_content` varchar(1024) NOT NULL COMMENT '短信内容',
  `send_status` varchar(128) DEFAULT NULL COMMENT '第三方状态',
  `smsl_add_time` int(10) DEFAULT '0' COMMENT '加入时间',
  `smsl_send_time` int(10) DEFAULT '0' COMMENT '发送时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='短信发送表';

-- --------------------------------------------------------

--
-- 表的结构 `xx_sms_template`
--

DROP TABLE IF EXISTS `xx_sms_template`;
CREATE TABLE `xx_sms_template` (
  `smst_id` int(12) UNSIGNED NOT NULL,
  `smst_key` varchar(32) NOT NULL COMMENT '短信KEY',
  `smst_title` varchar(128) NOT NULL COMMENT '短信标题',
  `smst_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0:不发送　1:发送',
  `smst_content` varchar(1024) NOT NULL COMMENT '短信内容',
  `smst_dsc` varchar(1024) NOT NULL COMMENT '短信内容描述'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='短信模板表';

-- --------------------------------------------------------

--
-- 表的结构 `xx_url`
--

DROP TABLE IF EXISTS `xx_url`;
CREATE TABLE `xx_url` (
  `url_id` int(10) UNSIGNED NOT NULL,
  `app` varchar(32) DEFAULT NULL COMMENT '类名',
  `act` varchar(32) DEFAULT NULL COMMENT '方式',
  `uri` varchar(128) DEFAULT NULL COMMENT '完成路径',
  `title` varchar(256) DEFAULT '' COMMENT '标题',
  `auth` varchar(32) DEFAULT '' COMMENT '受控类别　controlled:受控　none: 不受控　admin: 只有admin可以访问',
  `group` varchar(32) DEFAULT NULL COMMENT '分组'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='url表' ROW_FORMAT=DYNAMIC;

--
-- 转存表中的数据 `xx_url`
--

INSERT INTO `xx_url` (`url_id`, `app`, `act`, `uri`, `title`, `auth`, `group`) VALUES
(1, 'account', 'index', 'account.index', '资金管理首页', 'controlled', '资金管理'),
(2, 'account', 'get_accountlog', 'account.get_accountlog', '资金日志', 'controlled', '资金管理'),
(3, 'account', 'reset_account', 'account.reset_account', '重置帐户', 'controlled', '资金管理'),
(4, 'account', 'lock_account', 'account.lock_account', '锁定帐户', 'controlled', '资金管理'),
(5, 'account', 'unlock_account', 'account.unlock_account', '解锁帐户', 'controlled', '资金管理'),
(6, 'account', 'ajuster_accountcapital', 'account.ajuster_accountcapital', '调整用户帐户余款', 'controlled', '资金管理'),
(7, 'admin', 'index', 'admin.index', '后台首页', 'controlled', '后台管理'),
(8, 'api', 'index', 'api.index', 'API首页', 'none', 'API管理'),
(9, 'api', 'qqback', 'api.qqback', 'qq返回', 'none', 'API管理'),
(10, 'auth', 'reslist', 'auth.reslist', '资源列表', 'controlled', '权限管理'),
(11, 'auth', 'edit_res', 'auth.edit_res', '编辑资源', 'controlled', '权限管理'),
(12, 'auth', 'del_res', 'auth.del_res', '删除资源', 'controlled', '权限管理'),
(13, 'auth', 'rolelist', 'auth.rolelist', '角色列表', 'controlled', '权限管理'),
(14, 'auth', 'edit_role', 'auth.edit_role', '编辑角色', 'controlled', '权限管理'),
(15, 'auth', 'del_role', 'auth.del_role', '删除角色', 'controlled', '权限管理'),
(16, 'auth', 'whitelist', 'auth.whitelist', '角色白名单', 'controlled', '权限管理'),
(17, 'auth', 'blacklist', 'auth.blacklist', '角色黑名单', 'controlled', '权限管理'),
(18, 'auth', 'manage_permission', 'auth.manage_permission', '设置角色权限', 'controlled', '权限管理'),
(19, 'auth', 'refresh_authcache', 'auth.refresh_authcache', '刷新权限缓存', 'controlled', '权限管理'),
(20, 'ayeer', 'index', 'ayeer.index', 'ayeer笔记', 'controlled', 'ayeer笔记'),
(21, 'ayeer', 'api', 'ayeer.api', 'ayeer笔记接口', 'controlled', 'ayeer笔记'),
(22, 'cms', 'index', 'cms.index', '内容管理首页', 'controlled', '内容管理'),
(23, 'cms', 'contentlist', 'cms.contentlist', '文章列表', 'controlled', '内容管理'),
(24, 'cms', 'form_content', 'cms.form_content', '编辑文章', 'controlled', '内容管理'),
(25, 'cms', 'del_content', 'cms.del_content', '删除文章', 'controlled', '内容管理'),
(26, 'cms', 'viewslist', 'cms.viewslist', '视图列表', 'controlled', '内容管理'),
(27, 'cms', 'form_views', 'cms.form_views', '编辑视图', 'controlled', '内容管理'),
(28, 'cms', 'del_views', 'cms.del_views', '删除视图', 'controlled', '内容管理'),
(29, 'cms', 'tagslist', 'cms.tagslist', '标签列表', 'controlled', '内容管理'),
(30, 'cms', 'form_tags', 'cms.form_tags', '编辑标签', 'controlled', '内容管理'),
(31, 'cms', 'del_tags', 'cms.del_tags', '删除标签', 'controlled', '内容管理'),
(32, 'cms', 'refresh_tagscache', 'cms.refresh_tagscache', '刷新内容标签缓存', 'controlled', '内容管理'),
(33, 'login', 'index', 'login.index', '登录页', 'none', '登录'),
(34, 'login', 'login_form', 'login.login_form', 'login.login_form', 'none', '登录'),
(35, 'login', 'do_login', 'login.do_login', '登录操作', 'none', '登录'),
(36, 'login', 'ajax_login', 'login.ajax_login', 'ajax登录操作', 'none', '登录'),
(37, 'login', 'qq_login', 'login.qq_login', 'QQ号登录操作', 'none', '登录'),
(38, 'login', 'logout', 'login.logout', '登出', 'none', '登录'),
(39, 'login', 'reset_pwd', 'login.reset_pwd', '重置密码', 'controlled', '用户管理'),
(40, 'login', 'changepwd', 'login.changepwd', '修改密码', 'controlled', '用户管理'),
(41, 'menu', 'index', 'menu.index', '目录管理首页', 'controlled', '目录管理'),
(42, 'menu', 'get_allmenutree', 'menu.get_allmenutree', '获取顶级目录', 'controlled', '目录管理'),
(43, 'menu', 'add_menutree', 'menu.add_menutree', '增加顶级目录', 'controlled', '目录管理'),
(44, 'menu', 'form_menutree', 'menu.form_menutree', '编辑项级目录', 'controlled', '目录管理'),
(45, 'menu', 'get_menu', 'menu.get_menu', '获取目录', 'controlled', '目录管理'),
(46, 'menu', 'add_menu', 'menu.add_menu', '增加目录', 'controlled', '目录管理'),
(47, 'menu', 'form_menu', 'menu.form_menu', '编辑目录', 'controlled', '目录管理'),
(48, 'menu', 'del_menu', 'menu.del_menu', '删除目录', 'controlled', '目录管理'),
(49, 'pay', 'index', 'pay.index', '支付首页', 'controlled', '支付管理'),
(50, 'pay', 'notify', 'pay.notify', '支付通告', 'none', '支付管理'),
(51, 'pay', 'answer', 'pay.answer', '支付返回', 'none', '支付管理'),
(52, 'register', 'index', 'register.index', '注册用户', 'none', '注册'),
(53, 'register', 'do_register', 'register.do_register', '注册用户操作', 'none', '注册'),
(54, 'rss', 'index', 'rss.index', 'RSS首页', 'none', 'RSS页'),
(55, 'setting', 'index', 'setting.index', '系统设置首页', 'controlled', '后台管理'),
(56, 'setting', 'edit', 'setting.edit', '编辑系统设置', 'controlled', '后台管理'),
(57, 'setting', 'refresh_syscache', 'setting.refresh_syscache', '刷新系统设置缓存', 'controlled', '后台管理'),
(58, 'show', 'index_action', 'show.index_action', 'show.index_action', 'none', ''),
(59, 'show', 'article_action', 'show.article_action', 'show.article_action', 'none', ''),
(60, 'show', 'page_action', 'show.page_action', 'show.page_action', 'none', ''),
(61, 'show', 'base_action', 'show.base_action', 'show.base_action', 'none', ''),
(62, 'ueditor', 'index', 'ueditor.index', 'ueditor辅助入口', 'controlled', 'ueditor辅助'),
(63, 'ueditor', 'action_upload', 'ueditor.action_upload', '上传控制', 'controlled', 'ueditor辅助'),
(64, 'ueditor', 'action_list', 'ueditor.action_list', '	获取列表', 'controlled', 'ueditor辅助'),
(65, 'ueditor', 'getfiles', 'ueditor.getfiles', '	获取文件', 'controlled', 'ueditor辅助'),
(66, 'ueditor', 'action_crawler', 'ueditor.action_crawler', '	操作', 'controlled', 'ueditor辅助'),
(67, 'url', 'index', 'url.index', '路径管理首页', 'controlled', '路径管理'),
(68, 'url', 'edit_url', 'url.edit_url', '编辑路径', 'controlled', '路径管理'),
(69, 'url', 'del_url', 'url.del_url', '删除路径', 'controlled', '路径管理'),
(70, 'url', 'add_urlres', 'url.add_urlres', '增加路径资源', 'controlled', '路径管理'),
(71, 'url', 'refresh_urlcache', 'url.refresh_urlcache', '刷新缓存', 'controlled', '路径管理'),
(72, 'user', 'index', 'user.index', '用户管理首页', 'controlled', '用户管理'),
(73, 'user', 'detailed', 'user.detailed', '用户详情', 'controlled', '用户管理'),
(74, 'user', 'info', 'user.info', '用户个人信息', 'controlled', '用户管理'),
(75, 'user', 'role', 'user.role', '用户角色', 'controlled', '用户管理'),
(76, 'user', 'account', 'user.account', '用户资金', 'controlled', '用户管理'),
(77, 'user', 'safe', 'user.safe', '用户安全', 'controlled', '用户管理'),
(78, 'user', 'del_user', 'user.del_user', '删除用户', 'controlled', '用户管理'),
(79, 'user', 'edit_user', 'user.edit_user', '编辑用户', 'controlled', '用户管理'),
(80, 'weixin', 'index', 'weixin.index', '微信首页', 'none', '微信管理'),
(81, 'weixin', 'oauth2', 'weixin.oauth2', '微信oauth2鉴权', 'none', '微信管理'),
(82, 'welcome', 'index', 'welcome.index', '首页', 'none', '');

-- --------------------------------------------------------

--
-- 表的结构 `xx_user`
--

DROP TABLE IF EXISTS `xx_user`;
CREATE TABLE `xx_user` (
  `user_id` int(10) UNSIGNED NOT NULL,
  `user_code` varchar(32) NOT NULL COMMENT '用户编码',
  `user_name` varchar(64) NOT NULL COMMENT '用户名',
  `user_phone` varchar(32) NOT NULL COMMENT '用户手机号',
  `user_email` varchar(64) NOT NULL COMMENT '用户邮箱',
  `user_photo` varchar(128) DEFAULT NULL COMMENT '用户头像',
  `user_pwd` varchar(64) NOT NULL COMMENT '密码',
  `user_atime` int(10) DEFAULT NULL COMMENT '创建时间',
  `user_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否删除,默认为0 0:未删除 1:已删除'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户表';

--
-- 转存表中的数据 `xx_user`
--

INSERT INTO `xx_user` (`user_id`, `user_code`, `user_name`, `user_phone`, `user_email`, `user_photo`, `user_pwd`, `user_atime`, `user_status`) VALUES
(3, 'U0000000004', 'admin', '', '', '', '10adba0615c1187091b2f0d505ae60ee', 1445417117, 0),
(11, '0002', '', '', '', NULL, '', NULL, 0),
(10, '0001', '', '', '', NULL, '', NULL, 0),
(12, '0003', '', '', '', NULL, '', NULL, 0),
(13, '0004', '', '', '', NULL, '', NULL, 0),
(14, '0005', '', '', '', NULL, '', NULL, 0),
(15, '0006', '', '', '', NULL, '', NULL, 0),
(16, '0007', '', '', '', NULL, '', NULL, 0),
(17, '0008', '', '', '', NULL, '', NULL, 0),
(18, '0009', '', '', '', NULL, '', NULL, 0),
(19, '00010', '', '', '', NULL, '', NULL, 0),
(20, '00011', '', '', '', NULL, '', NULL, 0),
(21, '00012', '', '', '', NULL, '', NULL, 0),
(22, '00013', '', '', '', NULL, '', NULL, 0),
(23, '00014', '', '', '', NULL, '', NULL, 0),
(24, '00015', '', '', '', NULL, '', NULL, 0),
(25, '00016', '', '', '', NULL, '', NULL, 0),
(26, '00017', '', '', '', NULL, '', NULL, 0),
(27, '00018', '', '', '', NULL, '', NULL, 0),
(28, '00019', '', '', '', NULL, '', NULL, 0);

-- --------------------------------------------------------

--
-- 表的结构 `xx_user_base`
--

DROP TABLE IF EXISTS `xx_user_base`;
CREATE TABLE `xx_user_base` (
  `ub_id` int(10) UNSIGNED NOT NULL,
  `user_code` varchar(32) NOT NULL COMMENT '用户编码',
  `ub_true_name` varchar(64) DEFAULT NULL COMMENT '用户真实姓名',
  `ub_nickname` varchar(128) DEFAULT NULL COMMENT '昵称',
  `ub_idcard` varchar(32) DEFAULT NULL COMMENT '身份证号',
  `ub_student_number` varchar(32) DEFAULT NULL COMMENT '学号',
  `school_id` int(10) DEFAULT '0' COMMENT '所在学校ID',
  `ub_province` varchar(64) DEFAULT NULL COMMENT '省',
  `ub_city` varchar(64) DEFAULT NULL COMMENT '市',
  `ub_area` varchar(64) DEFAULT NULL,
  `ub_region_code` varchar(32) DEFAULT NULL COMMENT '省市code',
  `ub_school` varchar(128) DEFAULT NULL COMMENT '学校（附校区）',
  `ub_gender` varchar(32) DEFAULT NULL COMMENT '性别',
  `ub_professional` varchar(128) DEFAULT NULL COMMENT '专业',
  `ub_room` varchar(128) DEFAULT NULL COMMENT '寝室',
  `ub_enterschool_time` int(10) DEFAULT NULL COMMENT '入学年份（2011、2012、2013、2014、2015）',
  `ub_school_system` int(10) DEFAULT NULL COMMENT '学制（3、4、5）',
  `ub_idcard_picture1` varchar(128) DEFAULT NULL COMMENT '身份证1',
  `ub_idcard_picture2` varchar(128) DEFAULT NULL COMMENT '身份证2',
  `ub_certificate_picture1` varchar(128) DEFAULT NULL COMMENT '学生证1',
  `ub_certificate_picture2` varchar(128) DEFAULT NULL COMMENT '学生证2',
  `ub_certificate_picture3` varchar(128) DEFAULT NULL COMMENT '学生证3',
  `ub_photo` varchar(128) DEFAULT NULL COMMENT '个人照片',
  `ub_birthday` varchar(32) DEFAULT NULL COMMENT '生日'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户的基础信息';

--
-- 转存表中的数据 `xx_user_base`
--

INSERT INTO `xx_user_base` (`ub_id`, `user_code`, `ub_true_name`, `ub_nickname`, `ub_idcard`, `ub_student_number`, `school_id`, `ub_province`, `ub_city`, `ub_area`, `ub_region_code`, `ub_school`, `ub_gender`, `ub_professional`, `ub_room`, `ub_enterschool_time`, `ub_school_system`, `ub_idcard_picture1`, `ub_idcard_picture2`, `ub_certificate_picture1`, `ub_certificate_picture2`, `ub_certificate_picture3`, `ub_photo`, `ub_birthday`) VALUES
(2, 'U0000000004', NULL, '', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- 表的结构 `xx_user_login`
--

DROP TABLE IF EXISTS `xx_user_login`;
CREATE TABLE `xx_user_login` (
  `ul_id` int(10) UNSIGNED NOT NULL,
  `user_code` varchar(32) NOT NULL COMMENT '用户编码',
  `ul_name` varchar(64) DEFAULT NULL COMMENT '用户名',
  `ul_type` int(8) NOT NULL COMMENT '用户登录方式 0:用户名  1:手机号',
  `ul_atime` int(10) DEFAULT NULL COMMENT '创建时间',
  `ul_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否删除,默认为0 0:未删除 1:已删除'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户登录表';

--
-- 转存表中的数据 `xx_user_login`
--

INSERT INTO `xx_user_login` (`ul_id`, `user_code`, `ul_name`, `ul_type`, `ul_atime`, `ul_status`) VALUES
(3146, 'U0000000004', 'admin_opmetic', 0, 1445417117, 0);

-- --------------------------------------------------------

--
-- 表的结构 `xx_user_role`
--

DROP TABLE IF EXISTS `xx_user_role`;
CREATE TABLE `xx_user_role` (
  `ur_id` int(10) UNSIGNED NOT NULL,
  `user_code` varchar(32) NOT NULL COMMENT '用户编码',
  `ur_role_code` varchar(32) NOT NULL COMMENT '角色编码',
  `ur_failure_time` int(10) DEFAULT NULL COMMENT '失效时间',
  `ur_bench_role` varchar(32) NOT NULL COMMENT '后补角色编码',
  `ur_remark` varchar(256) DEFAULT NULL COMMENT '角色说明',
  `ur_atime` int(10) DEFAULT NULL COMMENT '创建时间',
  `ur_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否删除,默认为0 0:未删除 1:已删除'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户角色表';

--
-- 转存表中的数据 `xx_user_role`
--

INSERT INTO `xx_user_role` (`ur_id`, `user_code`, `ur_role_code`, `ur_failure_time`, `ur_bench_role`, `ur_remark`, `ur_atime`, `ur_status`) VALUES
(3140, 'U0000000004', 'ROLE00001', 0, 'ROLE00001', NULL, 1445417117, 0);

-- --------------------------------------------------------

--
-- 表的结构 `xx_user_weixin`
--

DROP TABLE IF EXISTS `xx_user_weixin`;
CREATE TABLE `xx_user_weixin` (
  `uw_id` int(10) UNSIGNED NOT NULL,
  `user_code` varchar(32) NOT NULL COMMENT '用户编码',
  `uw_openid` varchar(64) DEFAULT NULL COMMENT '公众号对应open_id',
  `uw_weixin_id` varchar(64) DEFAULT NULL COMMENT '公众号ID',
  `uw_weixin_name` varchar(64) DEFAULT NULL COMMENT '公众号名称',
  `uw_atime` int(10) DEFAULT NULL COMMENT '创建时间',
  `uw_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否删除,默认为0 0:未删除 1:已删除'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户观注微信公众号表';

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ayeer_notepaper`
--
ALTER TABLE `ayeer_notepaper`
  ADD PRIMARY KEY (`notepaper_id`);

--
-- Indexes for table `cms_content`
--
ALTER TABLE `cms_content`
  ADD PRIMARY KEY (`cn_id`);

--
-- Indexes for table `cms_content_tags`
--
ALTER TABLE `cms_content_tags`
  ADD PRIMARY KEY (`ct_id`),
  ADD KEY `cn_id` (`cn_id`),
  ADD KEY `t_id` (`t_id`);

--
-- Indexes for table `cms_tags`
--
ALTER TABLE `cms_tags`
  ADD PRIMARY KEY (`t_id`),
  ADD UNIQUE KEY `t_key` (`t_key`);

--
-- Indexes for table `cms_views`
--
ALTER TABLE `cms_views`
  ADD PRIMARY KEY (`v_id`),
  ADD UNIQUE KEY `v_key` (`v_key`);

--
-- Indexes for table `xx_account`
--
ALTER TABLE `xx_account`
  ADD PRIMARY KEY (`account_id`);

--
-- Indexes for table `xx_account_frozen`
--
ALTER TABLE `xx_account_frozen`
  ADD PRIMARY KEY (`af_id`);

--
-- Indexes for table `xx_account_frozen_log`
--
ALTER TABLE `xx_account_frozen_log`
  ADD PRIMARY KEY (`afl_id`);

--
-- Indexes for table `xx_account_log`
--
ALTER TABLE `xx_account_log`
  ADD PRIMARY KEY (`al_id`);

--
-- Indexes for table `xx_account_mc`
--
ALTER TABLE `xx_account_mc`
  ADD PRIMARY KEY (`ac_id`),
  ADD KEY `user_code` (`user_code`);

--
-- Indexes for table `xx_account_mc_log`
--
ALTER TABLE `xx_account_mc_log`
  ADD PRIMARY KEY (`al_id`);

--
-- Indexes for table `xx_account_recharge`
--
ALTER TABLE `xx_account_recharge`
  ADD PRIMARY KEY (`ar_id`),
  ADD UNIQUE KEY `ar_sn` (`ar_sn`);

--
-- Indexes for table `xx_account_rmb`
--
ALTER TABLE `xx_account_rmb`
  ADD PRIMARY KEY (`ac_id`),
  ADD KEY `user_code` (`user_code`);

--
-- Indexes for table `xx_account_rmb_log`
--
ALTER TABLE `xx_account_rmb_log`
  ADD PRIMARY KEY (`al_id`);

--
-- Indexes for table `xx_account_withdrawscash`
--
ALTER TABLE `xx_account_withdrawscash`
  ADD PRIMARY KEY (`aw_id`);

--
-- Indexes for table `xx_code_definition`
--
ALTER TABLE `xx_code_definition`
  ADD PRIMARY KEY (`cd_id`),
  ADD UNIQUE KEY `cd_name_2` (`cd_name`),
  ADD KEY `cd_name` (`cd_name`);

--
-- Indexes for table `xx_code_info`
--
ALTER TABLE `xx_code_info`
  ADD PRIMARY KEY (`ci_id`),
  ADD KEY `cd_name` (`cd_name`,`ci_usercode`);

--
-- Indexes for table `xx_crontab`
--
ALTER TABLE `xx_crontab`
  ADD PRIMARY KEY (`crontab_id`);

--
-- Indexes for table `xx_mail_list`
--
ALTER TABLE `xx_mail_list`
  ADD PRIMARY KEY (`maill_id`);

--
-- Indexes for table `xx_mail_template`
--
ALTER TABLE `xx_mail_template`
  ADD PRIMARY KEY (`mailt_id`);

--
-- Indexes for table `xx_menu`
--
ALTER TABLE `xx_menu`
  ADD PRIMARY KEY (`menu_id`),
  ADD KEY `menu_code` (`menu_code`);

--
-- Indexes for table `xx_permission`
--
ALTER TABLE `xx_permission`
  ADD PRIMARY KEY (`per_id`),
  ADD KEY `role_id` (`role_id`);

--
-- Indexes for table `xx_resources`
--
ALTER TABLE `xx_resources`
  ADD PRIMARY KEY (`res_id`),
  ADD KEY `res_code` (`res_code`);

--
-- Indexes for table `xx_role`
--
ALTER TABLE `xx_role`
  ADD PRIMARY KEY (`role_id`),
  ADD KEY `role_code` (`role_code`);

--
-- Indexes for table `xx_sessions`
--
ALTER TABLE `xx_sessions`
  ADD PRIMARY KEY (`session_id`),
  ADD KEY `last_activity_idx` (`last_activity`);

--
-- Indexes for table `xx_setting`
--
ALTER TABLE `xx_setting`
  ADD PRIMARY KEY (`setting_id`),
  ADD UNIQUE KEY `setting_variable` (`setting_variable`);

--
-- Indexes for table `xx_sms_list`
--
ALTER TABLE `xx_sms_list`
  ADD PRIMARY KEY (`smsl_id`);

--
-- Indexes for table `xx_sms_template`
--
ALTER TABLE `xx_sms_template`
  ADD PRIMARY KEY (`smst_id`);

--
-- Indexes for table `xx_url`
--
ALTER TABLE `xx_url`
  ADD PRIMARY KEY (`url_id`);

--
-- Indexes for table `xx_user`
--
ALTER TABLE `xx_user`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `user_code_2` (`user_code`),
  ADD KEY `user_code` (`user_code`);

--
-- Indexes for table `xx_user_base`
--
ALTER TABLE `xx_user_base`
  ADD PRIMARY KEY (`ub_id`),
  ADD KEY `user_code` (`user_code`);

--
-- Indexes for table `xx_user_login`
--
ALTER TABLE `xx_user_login`
  ADD PRIMARY KEY (`ul_id`),
  ADD KEY `user_code` (`user_code`);

--
-- Indexes for table `xx_user_role`
--
ALTER TABLE `xx_user_role`
  ADD PRIMARY KEY (`ur_id`),
  ADD KEY `user_code` (`user_code`);

--
-- Indexes for table `xx_user_weixin`
--
ALTER TABLE `xx_user_weixin`
  ADD PRIMARY KEY (`uw_id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `ayeer_notepaper`
--
ALTER TABLE `ayeer_notepaper`
  MODIFY `notepaper_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
--
-- 使用表AUTO_INCREMENT `cms_content`
--
ALTER TABLE `cms_content`
  MODIFY `cn_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
--
-- 使用表AUTO_INCREMENT `cms_content_tags`
--
ALTER TABLE `cms_content_tags`
  MODIFY `ct_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
--
-- 使用表AUTO_INCREMENT `cms_tags`
--
ALTER TABLE `cms_tags`
  MODIFY `t_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- 使用表AUTO_INCREMENT `cms_views`
--
ALTER TABLE `cms_views`
  MODIFY `v_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- 使用表AUTO_INCREMENT `xx_account`
--
ALTER TABLE `xx_account`
  MODIFY `account_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- 使用表AUTO_INCREMENT `xx_account_frozen`
--
ALTER TABLE `xx_account_frozen`
  MODIFY `af_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `xx_account_frozen_log`
--
ALTER TABLE `xx_account_frozen_log`
  MODIFY `afl_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `xx_account_log`
--
ALTER TABLE `xx_account_log`
  MODIFY `al_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `xx_account_mc`
--
ALTER TABLE `xx_account_mc`
  MODIFY `ac_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `xx_account_mc_log`
--
ALTER TABLE `xx_account_mc_log`
  MODIFY `al_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `xx_account_recharge`
--
ALTER TABLE `xx_account_recharge`
  MODIFY `ar_id` int(12) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
--
-- 使用表AUTO_INCREMENT `xx_account_rmb`
--
ALTER TABLE `xx_account_rmb`
  MODIFY `ac_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- 使用表AUTO_INCREMENT `xx_account_rmb_log`
--
ALTER TABLE `xx_account_rmb_log`
  MODIFY `al_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- 使用表AUTO_INCREMENT `xx_account_withdrawscash`
--
ALTER TABLE `xx_account_withdrawscash`
  MODIFY `aw_id` int(12) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `xx_code_definition`
--
ALTER TABLE `xx_code_definition`
  MODIFY `cd_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- 使用表AUTO_INCREMENT `xx_code_info`
--
ALTER TABLE `xx_code_info`
  MODIFY `ci_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- 使用表AUTO_INCREMENT `xx_crontab`
--
ALTER TABLE `xx_crontab`
  MODIFY `crontab_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '定时执行任务ID', AUTO_INCREMENT=15;
--
-- 使用表AUTO_INCREMENT `xx_mail_list`
--
ALTER TABLE `xx_mail_list`
  MODIFY `maill_id` int(12) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `xx_mail_template`
--
ALTER TABLE `xx_mail_template`
  MODIFY `mailt_id` int(12) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `xx_menu`
--
ALTER TABLE `xx_menu`
  MODIFY `menu_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;
--
-- 使用表AUTO_INCREMENT `xx_permission`
--
ALTER TABLE `xx_permission`
  MODIFY `per_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;
--
-- 使用表AUTO_INCREMENT `xx_resources`
--
ALTER TABLE `xx_resources`
  MODIFY `res_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;
--
-- 使用表AUTO_INCREMENT `xx_role`
--
ALTER TABLE `xx_role`
  MODIFY `role_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- 使用表AUTO_INCREMENT `xx_setting`
--
ALTER TABLE `xx_setting`
  MODIFY `setting_id` int(12) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
--
-- 使用表AUTO_INCREMENT `xx_sms_list`
--
ALTER TABLE `xx_sms_list`
  MODIFY `smsl_id` int(12) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `xx_sms_template`
--
ALTER TABLE `xx_sms_template`
  MODIFY `smst_id` int(12) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `xx_url`
--
ALTER TABLE `xx_url`
  MODIFY `url_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;
--
-- 使用表AUTO_INCREMENT `xx_user`
--
ALTER TABLE `xx_user`
  MODIFY `user_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;
--
-- 使用表AUTO_INCREMENT `xx_user_base`
--
ALTER TABLE `xx_user_base`
  MODIFY `ub_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- 使用表AUTO_INCREMENT `xx_user_login`
--
ALTER TABLE `xx_user_login`
  MODIFY `ul_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3151;
--
-- 使用表AUTO_INCREMENT `xx_user_role`
--
ALTER TABLE `xx_user_role`
  MODIFY `ur_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3145;
--
-- 使用表AUTO_INCREMENT `xx_user_weixin`
--
ALTER TABLE `xx_user_weixin`
  MODIFY `uw_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;COMMIT;
