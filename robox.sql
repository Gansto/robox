# Host: localhost  (Version: 5.5.53)
# Date: 2018-06-06 08:58:45
# Generator: MySQL-Front 5.3  (Build 4.234)

/*!40101 SET NAMES utf8 */;

#
# Structure for table "admin"
#

CREATE TABLE `admin` (
  `id` int(10) unsigned NOT NULL DEFAULT '0',
  `name` varchar(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `pass` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `realname` varchar(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `grade` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `state` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `create_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modify_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `login_count` int(10) unsigned NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# Data for table "admin"
#

INSERT INTO `admin` VALUES (1,'admin','f6fdffe48c908deb0f4c3bd36c032e72','admin',8,1,'2017-03-03 06:37:43','2018-05-02 09:16:50',8);

#
# Structure for table "admin_advanced"
#

CREATE TABLE `admin_advanced` (
  `admin_id` int(10) unsigned NOT NULL DEFAULT '0',
  `advanced_id` int(10) unsigned NOT NULL DEFAULT '0',
  KEY `admin_id` (`admin_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# Data for table "admin_advanced"
#


#
# Structure for table "admin_login"
#

CREATE TABLE `admin_login` (
  `admin_id` int(10) unsigned NOT NULL DEFAULT '0',
  `login_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `login_ip` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  KEY `admin_id` (`admin_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# Data for table "admin_login"
#

INSERT INTO `admin_login` VALUES (1,'2018-01-25 17:01:05','::1'),(1,'2018-01-25 18:01:15','::1'),(1,'2018-01-26 17:01:30','::1'),(1,'2018-05-02 09:05:26','::1');

#
# Structure for table "admin_popedom"
#

CREATE TABLE `admin_popedom` (
  `admin_id` int(10) unsigned NOT NULL DEFAULT '0',
  `class_id` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  KEY `admin_id` (`admin_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# Data for table "admin_popedom"
#

INSERT INTO `admin_popedom` VALUES (1,'101101');

#
# Structure for table "advanced"
#

CREATE TABLE `advanced` (
  `id` int(10) unsigned NOT NULL DEFAULT '0',
  `sortnum` int(10) unsigned NOT NULL DEFAULT '0',
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `default_file` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `state` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# Data for table "advanced"
#

INSERT INTO `advanced` VALUES (2,20,'广告管理','adver_list.php',1),(4,40,'联系我们','contact_list.php',1),(5,50,'联系留言','contact_msg_list.php',2),(6,60,'在线留言','message_list.php',2),(8,80,'链接分类管理','link_class_list.php',2),(9,90,'链接管理','link_list.php',2),(10,100,'Banner分类管理','banner_class_list.php',2),(11,110,'Banner管理','banner_list.php',1),(12,120,'人才招聘','job_list.php',2),(13,130,'应聘信息','job_apply_list.php',2),(18,180,'会员管理','member_list.php',2),(19,190,'信息批量操作','info_multi.php',2),(20,200,'图片批量上传','manage_batch_form.php',1),(21,210,'栏目分类管理','catalog_class_list.php',2),(22,220,'栏目分类','catalog_list.php',2),(23,230,'订单管理','cart_list.php',2),(24,240,'访问统计','counter_list.php',2);

#
# Structure for table "adver"
#

CREATE TABLE `adver` (
  `id` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `mode` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `url` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `width` int(10) unsigned NOT NULL DEFAULT '0',
  `height` int(10) unsigned NOT NULL DEFAULT '0',
  `time` int(11) NOT NULL DEFAULT '0',
  `pic` varchar(200) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `state` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# Data for table "adver"
#


#
# Structure for table "banner"
#

CREATE TABLE `banner` (
  `id` int(10) NOT NULL,
  `class_id` int(10) NOT NULL,
  `sortnum` int(20) NOT NULL,
  `title` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `url` varchar(200) CHARACTER SET utf8 DEFAULT NULL,
  `pic` varchar(200) CHARACTER SET utf8 DEFAULT NULL,
  `width` int(10) DEFAULT NULL,
  `height` int(10) DEFAULT NULL,
  `state` int(10) NOT NULL,
  `content` text CHARACTER SET utf8,
  `pic1` varchar(200) CHARACTER SET utf8 DEFAULT NULL,
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk;

#
# Data for table "banner"
#

INSERT INTO `banner` VALUES (1,3,10,'顶部logo','','2018-06/152810014143614900.png',175,58,1,'',''),(2,1,10,'首页banner','','2018-06/152816817987962700.jpg',1920,750,1,'',''),(3,1,20,'banner1','','2018-06/152824586744810100.jpg',1920,750,1,'',''),(4,4,10,'QQ二维码','','2018-06/152819103438935200.jpg',110,110,1,'',''),(5,4,20,'微信二维码','','2018-06/152819195119463900.jpg',110,110,1,'',''),(6,4,30,'新浪二维码','','2018-06/152819173685687800.jpg',110,110,1,'',''),(7,5,10,'企业logo图','','2018-06/152819218589813900.jpg',1200,180,1,'','');

#
# Structure for table "banner_class"
#

CREATE TABLE `banner_class` (
  `id` int(10) NOT NULL,
  `sortnum` int(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `add_deny` int(10) NOT NULL,
  `delete_deny` int(10) NOT NULL,
  `hasPic1` int(10) NOT NULL,
  `hasCon` int(10) NOT NULL,
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk;

#
# Data for table "banner_class"
#

INSERT INTO `banner_class` VALUES (1,10,'首页-BANNER',0,0,0,0),(2,20,'内页-BANNER',0,0,0,0),(3,30,'顶部logo',0,0,0,0),(4,40,'底部二维码',0,0,0,0),(5,50,'合作企业logo',0,0,0,0);

#
# Structure for table "catalog"
#

CREATE TABLE `catalog` (
  `id` int(10) NOT NULL,
  `class_id` int(10) NOT NULL,
  `sortnum` int(20) NOT NULL,
  `title` varchar(100) DEFAULT NULL,
  `state` int(10) NOT NULL,
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk;

#
# Data for table "catalog"
#


#
# Structure for table "catalog_class"
#

CREATE TABLE `catalog_class` (
  `id` int(10) NOT NULL,
  `sortnum` int(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `add_deny` int(10) NOT NULL,
  `delete_deny` int(10) NOT NULL,
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk;

#
# Data for table "catalog_class"
#

INSERT INTO `catalog_class` VALUES (1,10,'风格',0,0);

#
# Structure for table "config_base"
#

CREATE TABLE `config_base` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `title` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `icp` varchar(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `keyword` varchar(200) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `description` varchar(200) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `contact` text COLLATE utf8_unicode_ci,
  `copyright` text COLLATE utf8_unicode_ci,
  `webcopyright` text COLLATE utf8_unicode_ci,
  `javascriptFoot` text COLLATE utf8_unicode_ci,
  `javascriptHead` text COLLATE utf8_unicode_ci,
  `webJavascriptHead` text COLLATE utf8_unicode_ci,
  `webJavascriptFoot` text COLLATE utf8_unicode_ci,
  `hotline` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `map` text COLLATE utf8_unicode_ci,
  `rightButton` int(1) DEFAULT '0',
  `mobilejump` int(1) DEFAULT '1',
  `watermark` tinyint(1) DEFAULT '0',
  `waterpic` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `waterpos` int(10) DEFAULT '5',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# Data for table "config_base"
#

INSERT INTO `config_base` VALUES (1,'瑞博思（芜湖）智能控制系统有限公司','瑞博思（芜湖）智能控制系统有限公司','皖ICP备000000号','瑞博思','瑞博思','<p>\r\n\t商务合作联系方式\r\n</p>\r\n<p>\r\n\t对外合作/BD负责人：ZHANG YAN\r\n</p>\r\n<p>\r\n\t电话：+86 135-0000-0000\r\n</p>\r\n<p>\r\n\t合作咨询邮箱：ROBOX@IT.com\r\n</p>','安徽省芜湖市鸠江区鸠江电子产业园综合楼座10楼1009室','','','','','','123-456-7890','','',0,0,0,'',0);

#
# Structure for table "contact"
#

CREATE TABLE `contact` (
  `id` int(11) NOT NULL DEFAULT '0',
  `sortnum` int(10) NOT NULL DEFAULT '0',
  `name` varchar(100) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `email` varchar(50) DEFAULT NULL,
  `showForm` int(1) NOT NULL DEFAULT '0',
  `state` int(1) NOT NULL DEFAULT '0',
  `map` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#
# Data for table "contact"
#


#
# Structure for table "contact_msg"
#

CREATE TABLE `contact_msg` (
  `id` int(11) NOT NULL DEFAULT '0',
  `sortnum` int(10) NOT NULL,
  `dept_name` varchar(100) DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `company` varchar(100) DEFAULT NULL,
  `phone` varchar(100) DEFAULT NULL,
  `fax` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL,
  `content` text,
  `create_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `state` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#
# Data for table "contact_msg"
#


#
# Structure for table "hit_counter"
#

CREATE TABLE `hit_counter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `page` varchar(255) NOT NULL,
  `counter` int(11) NOT NULL,
  `ip` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

#
# Data for table "hit_counter"
#


#
# Structure for table "info"
#

CREATE TABLE `info` (
  `id` int(10) unsigned NOT NULL DEFAULT '0',
  `sortnum` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `admin_id` int(10) unsigned NOT NULL DEFAULT '0',
  `class_id` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `author` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `source` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `website` text COLLATE utf8_unicode_ci,
  `pic` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pic2` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `annex` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `keyword` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `intro` text COLLATE utf8_unicode_ci,
  `content` mediumtext COLLATE utf8_unicode_ci,
  `content2` text COLLATE utf8_unicode_ci,
  `content3` text COLLATE utf8_unicode_ci,
  `content4` text COLLATE utf8_unicode_ci,
  `content5` text COLLATE utf8_unicode_ci,
  `content6` text COLLATE utf8_unicode_ci,
  `webcontent` text COLLATE utf8_unicode_ci,
  `files` text COLLATE utf8_unicode_ci,
  `views` int(10) unsigned NOT NULL DEFAULT '0',
  `create_time` date NOT NULL DEFAULT '0000-00-00',
  `modify_time` date NOT NULL DEFAULT '0000-00-00',
  `state` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `price` varchar(50) COLLATE utf8_unicode_ci DEFAULT '' COMMENT '参考价格',
  `description` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `hot` tinyint(1) NOT NULL DEFAULT '0' COMMENT '热点标识',
  `actual` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '实际价格',
  PRIMARY KEY (`id`),
  KEY `class_id` (`class_id`),
  KEY `admin_id` (`admin_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# Data for table "info"
#

INSERT INTO `info` VALUES (1,10,'q',0,'101101','','','','2018-06/152819025576182500.jpg',NULL,'','ROBOX S.P.A','','<p>\r\n\t瑞博思（芜湖）智能控制系统有限公司由意大利瑞博思公司和中国埃夫特智能装备股份有限公司共同创立。\r\n</p>\r\n<p>\r\n\t瑞博思（芜湖）智能控制系统有限公司致力于为中国持续增长的运动控制市场提供解决方案。\r\n</p>\r\n<p>\r\n\t瑞博思智能运动控制器能够完美实现任何机械装备在精度、平滑度及速度的控制需求并且能够根据任务要求实现从单轴到多轴的同步控制...\r\n</p>','','','','','','','',21,'2018-06-04','2018-06-05',1,'','',1,''),(2,10,'123',0,'101103','','','','2018-06/152810754333721600.jpg',NULL,'','清扬','','沙发','','','','','','','',1,'2018-06-04','2018-06-04',1,'','',0,''),(3,10,'123',0,'101104','','','','2018-06/152816216977403800.jpg',NULL,'','清扬','','暗室逢灯深V出采访稿','','','','','','','',0,'2018-06-04','2018-06-05',1,'','',0,''),(4,20,'RP1',0,'101103','','','','2018-06/152816012226727800.jpg',NULL,'','','','','','','','','','','',2,'2018-06-05','2018-06-05',1,'','',0,''),(5,30,'RP1',0,'101103','','','','2018-06/152816014563034600.jpg',NULL,'','','','','','','','','','','',9,'2018-06-05','2018-06-05',1,'','',0,''),(6,40,'RP1',0,'101103','','','','2018-06/152816016031444700.jpg',NULL,'','','','','','','','','','','',6,'2018-06-05','2018-06-05',1,'','',0,''),(7,50,'RP1',0,'101103','','','','2018-06/152816017987556700.jpg',NULL,'','','','','','','','','','','',2,'2018-06-05','2018-06-05',1,'','',0,''),(8,20,'wee',0,'101104','','','','2018-06/152816218999155300.jpg',NULL,'','','','','','','','','','','',2,'2018-06-05','2018-06-05',1,'','',0,''),(9,30,'ere',0,'101104','','','','2018-06/152816220348297700.jpg',NULL,'','','','','','','','','','','',6,'2018-06-05','2018-06-05',1,'','',0,''),(10,40,'机器人',0,'101104','','','','2018-06/152816221556490400.jpg',NULL,'','','','','','','','','','','',4,'2018-06-05','2018-06-05',1,'','',0,''),(11,10,'奥迪',0,'101102','','','','2018-06/152816606556499500.jpg',NULL,'','','','水电费速度发斯蒂芬水电费速度','','','','','','','',5,'2018-06-05','2018-06-05',1,'','',0,''),(12,20,'erect',0,'101102','','','','',NULL,'','','','水电费水电费阿萨德深V从V型从VC','','','','','','','',2,'2018-06-05','2018-06-05',1,'','',0,''),(13,10,'12',0,'105101','','','','2018-06/152816844972518300.jpg',NULL,'','','','','','','','','','','',2,'2018-06-05','2018-06-05',1,'','',0,''),(14,20,'23',0,'105101','','','','2018-06/152816846805390200.jpg',NULL,'','','','','','','','','','','',2,'2018-06-05','2018-06-05',1,'','',0,''),(15,30,'34',0,'105101','','','','2018-06/152816848387359700.jpg',NULL,'','','','','','','','','','','',1,'2018-06-05','2018-06-05',1,'','',0,''),(16,40,'45发生的三发送到电视地方速度发送到速度',0,'105101','','','','2018-06/152816849764754000.jpg',NULL,'','','','','','','','','','','',0,'2018-06-05','2018-06-05',1,'','',0,''),(17,10,'center1',0,'102101','','','','2018-06/152816928598262400.jpg',NULL,'','','','','','','','','','','',0,'2018-06-05','2018-06-05',1,'','',0,''),(18,20,'center2',0,'102101','','','','2018-06/152816931166803700.jpg',NULL,'','','','','','','','','','','',0,'2018-06-05','2018-06-05',1,'','',0,''),(19,30,'center3',0,'102101','','','','2018-06/152816932428335500.jpg',NULL,'','','','','','','','','','','',3,'2018-06-05','2018-06-05',1,'','',0,''),(20,40,'center4',0,'102101','','','','2018-06/152816933690350300.jpg',NULL,'','','','','','','','','','','',3,'2018-06-05','2018-06-05',1,'','',0,''),(21,50,'center5',0,'102101','','','','2018-06/152816935151139500.jpg',NULL,'','','','','','','','','','','',0,'2018-06-05','2018-06-05',1,'','',0,''),(22,10,'robox',0,'104101','','','','2018-06/152818655311765300.jpg',NULL,'','','','沙发水电费水电费水电费水电费水电费速度反倒是','','','','','','','',2,'2018-06-05','2018-06-05',1,'','',1,''),(23,20,'robox2',0,'104101','','','','2018-06/152818660287660600.jpg',NULL,'','','','水电费阿萨德发必阿斯顿发送到发送到发送到电视速度水电费','','','','','','','',2,'2018-06-05','2018-06-05',1,'','',1,''),(24,30,'robox3',0,'104101','','','','','2018-06/152818845914600500.jpg','','','','查重必才出出出出出出出出出出出出出出出出出出出出出出v&nbsp;&nbsp;','','','','','','','',0,'2018-06-05','2018-06-05',1,'','',0,''),(25,40,'robox4',0,'104101','','','','','2018-06/152818847376799800.jpg','','','','阿斯顿发送到发的放大撒旦法撒旦法第三方电视的的的&nbsp; &nbsp; &nbsp; &nbsp;','','','','','','','',3,'2018-06-05','2018-06-05',2,'','',0,'');

#
# Structure for table "info_class"
#

CREATE TABLE `info_class` (
  `id` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `sortnum` int(10) unsigned NOT NULL DEFAULT '0',
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `pic` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `content` text COLLATE utf8_unicode_ci,
  `files` text COLLATE utf8_unicode_ci,
  `info_state` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `max_level` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `has_sub` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `sub_content` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `sub_pic` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `hasViews` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `hasState` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `hasPic` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `hasAnnex` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `hasIntro` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `hasContent` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `hasWebsite` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `hasAuthor` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `hasSource` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `hasKeyword` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `state` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `hasPics` tinyint(1) unsigned DEFAULT '0',
  `hasPic2` tinyint(1) unsigned DEFAULT '0',
  `hasContent2` tinyint(1) unsigned DEFAULT '0',
  `hasContent3` tinyint(1) unsigned DEFAULT '0',
  `hasContent4` tinyint(1) unsigned DEFAULT '0',
  `hasContent5` tinyint(1) unsigned DEFAULT '0',
  `hasContent6` tinyint(1) unsigned DEFAULT '0',
  `en_name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `hasDescription` tinyint(1) NOT NULL DEFAULT '0',
  `keyword` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sortnum` (`sortnum`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# Data for table "info_class"
#

INSERT INTO `info_class` VALUES ('101',10,'关于我们','','','','custom',3,1,0,1,1,1,1,1,0,1,0,0,0,1,1,0,0,0,0,0,0,0,NULL,1,NULL,NULL),('101101',10,'公司介绍','','','','content',0,0,0,1,1,1,1,1,0,1,0,0,0,1,1,0,0,0,0,0,0,0,'',1,'',''),('101102',20,'问','','','','list',0,0,0,0,1,1,1,1,0,1,0,0,0,1,1,0,0,0,0,0,0,0,'wer',1,'',''),('101103',30,'测试','','','','pictxt',0,0,0,0,1,1,1,1,0,1,0,0,0,1,1,0,0,0,0,0,0,0,'sd',1,'',''),('101104',40,'测试','','','','pic',0,0,0,0,1,1,1,1,0,1,0,0,0,1,1,0,0,0,0,0,0,0,'news',1,'',''),('102',20,'产品中心','','','','custom',3,1,0,0,1,1,1,1,0,1,0,0,0,1,1,0,0,0,0,0,0,0,NULL,1,NULL,NULL),('102101',10,'wer','','','','pictxt',0,0,0,0,1,1,1,1,0,1,0,0,0,1,1,0,0,0,0,0,0,0,'news',1,'',''),('103',30,'技术支持','','','','custom',3,1,0,0,1,1,1,1,0,1,0,0,0,1,1,0,0,0,0,0,0,0,NULL,1,NULL,NULL),('104',40,'资讯动态','','','','custom',3,1,0,0,1,1,1,1,0,1,0,0,0,1,1,0,1,0,0,0,0,0,NULL,1,NULL,NULL),('104101',10,'新闻','','','','list',0,0,0,0,1,1,1,1,0,1,0,0,0,1,1,0,0,0,0,0,0,0,'news',1,'',''),('105',50,'应用中心','','','','custom',3,1,0,0,1,1,1,1,0,1,0,0,0,1,1,0,0,0,0,0,0,0,NULL,1,NULL,NULL),('105101',10,'领域','','','','pic',0,0,0,0,1,1,1,1,0,1,0,0,0,1,1,0,0,0,0,0,0,0,'产品领域',1,'',''),('106',60,'联系我们','','','','custom',3,1,0,0,1,1,1,1,0,1,0,0,0,0,1,0,0,0,0,0,0,0,NULL,0,NULL,NULL);

#
# Structure for table "info_list"
#

CREATE TABLE `info_list` (
  `id` int(10) NOT NULL,
  `sortnum` int(10) DEFAULT NULL,
  `infoid` int(10) DEFAULT NULL,
  `title` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `Source` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `content` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `pic` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

#
# Data for table "info_list"
#


#
# Structure for table "job"
#

CREATE TABLE `job` (
  `id` int(10) unsigned NOT NULL,
  `sortnum` int(10) unsigned NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(50) DEFAULT NULL,
  `content` text,
  `showForm` tinyint(1) NOT NULL,
  `state` tinyint(1) NOT NULL,
  `publishdate` varchar(200) DEFAULT NULL,
  `qty` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

#
# Data for table "job"
#


#
# Structure for table "job_apply"
#

CREATE TABLE `job_apply` (
  `id` int(10) unsigned NOT NULL,
  `job_id` int(10) unsigned NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `sortnum` int(10) NOT NULL,
  `sex` varchar(50) DEFAULT NULL,
  `age` varchar(50) DEFAULT NULL,
  `major` varchar(50) DEFAULT NULL,
  `graduate_time` varchar(50) DEFAULT NULL,
  `college` varchar(50) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `resumes` text,
  `appraise` text,
  `create_time` varchar(50) NOT NULL,
  `state` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

#
# Data for table "job_apply"
#


#
# Structure for table "link"
#

CREATE TABLE `link` (
  `id` int(10) NOT NULL,
  `class_id` varchar(10) NOT NULL,
  `sortnum` int(10) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `url` varchar(200) DEFAULT NULL,
  `pic` varchar(200) DEFAULT NULL,
  `state` int(10) NOT NULL,
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk;

#
# Data for table "link"
#


#
# Structure for table "link_class"
#

CREATE TABLE `link_class` (
  `id` int(10) NOT NULL,
  `sortnum` int(10) NOT NULL,
  `name` varchar(50) NOT NULL,
  `haspic` int(10) NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk;

#
# Data for table "link_class"
#

INSERT INTO `link_class` VALUES (1,10,'友情链接',1);

#
# Structure for table "member"
#

CREATE TABLE `member` (
  `id` int(10) unsigned NOT NULL,
  `sortnum` int(10) unsigned NOT NULL,
  `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `pass` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '用户名',
  `phone` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '手机',
  `realname` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '真实姓名',
  `address` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '地址',
  `sex` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '性别',
  `state` tinyint(1) unsigned NOT NULL,
  `email` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `create_time` datetime NOT NULL,
  `modify_time` datetime DEFAULT NULL,
  `login_count` int(10) NOT NULL DEFAULT '0',
  `login_time` datetime DEFAULT NULL,
  `login_ip` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `memberGrade` int(10) DEFAULT '1',
  `company` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

#
# Data for table "member"
#


#
# Structure for table "message"
#

CREATE TABLE `message` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sortnum` int(10) unsigned DEFAULT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `content` text COLLATE utf8_unicode_ci,
  `create_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `reply` text COLLATE utf8_unicode_ci,
  `reply_time` datetime DEFAULT NULL,
  `ip` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `state` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=29 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# Data for table "message"
#


#
# Structure for table "order"
#

CREATE TABLE `order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `company` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `address` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `fax` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `content` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `sex` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `province` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `city` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `area` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `code` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `sj` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `orderid` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `total` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `create_time` datetime DEFAULT '0000-00-00 00:00:00',
  `state` tinyint(1) NOT NULL DEFAULT '0',
  `qty` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `userid` int(10) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=latin1;

#
# Data for table "order"
#


#
# Structure for table "order_list"
#

CREATE TABLE `order_list` (
  `cart_id` int(8) NOT NULL,
  `product_id` int(8) NOT NULL,
  `product_name` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `qty` int(8) NOT NULL,
  `price` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `bh` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

#
# Data for table "order_list"
#


#
# Structure for table "record"
#

CREATE TABLE `record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` datetime NOT NULL,
  `title` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `class` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `adminid` int(4) NOT NULL,
  `ip` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=67 DEFAULT CHARSET=latin1;

#
# Data for table "record"
#

INSERT INTO `record` VALUES (1,'2018-06-04 06:06:56','瑞博思','修改基本设置',0,'127.0.0.1'),(2,'2018-06-04 06:06:56','权威','新增分类',0,'127.0.0.1'),(3,'2018-06-04 06:06:18','问','新增分类',0,'127.0.0.1'),(4,'2018-06-04 06:06:33','测试','新增分类',0,'127.0.0.1'),(5,'2018-06-04 06:06:49','测试','新增分类',0,'127.0.0.1'),(6,'2018-06-04 07:06:20','123','新增信息',0,'127.0.0.1'),(7,'2018-06-04 08:06:29','wer','新增分类',0,'127.0.0.1'),(8,'2018-06-04 10:06:03','123','新增信息',0,'127.0.0.1'),(9,'2018-06-04 10:06:37','123','新增信息',0,'127.0.0.1'),(10,'2018-06-05 00:06:22','RP1','新增信息',0,'127.0.0.1'),(11,'2018-06-05 00:06:45','RP1','新增信息',0,'127.0.0.1'),(12,'2018-06-05 00:06:00','RP1','新增信息',0,'127.0.0.1'),(13,'2018-06-05 00:06:19','RP1','新增信息',0,'127.0.0.1'),(14,'2018-06-05 00:06:59','测试','修改分类',0,'127.0.0.1'),(15,'2018-06-05 01:06:16','测试','修改分类',0,'127.0.0.1'),(16,'2018-06-05 01:06:29','123','修改信息',0,'127.0.0.1'),(17,'2018-06-05 01:06:49','wee','新增信息',0,'127.0.0.1'),(18,'2018-06-05 01:06:03','ere','新增信息',0,'127.0.0.1'),(19,'2018-06-05 01:06:15','rtrt','新增信息',0,'127.0.0.1'),(20,'2018-06-05 01:06:55','机器人','修改信息',0,'127.0.0.1'),(21,'2018-06-05 02:06:25','奥迪','新增信息',0,'127.0.0.1'),(22,'2018-06-05 02:06:41','erect','新增信息',0,'127.0.0.1'),(23,'2018-06-05 03:06:40','领域','新增分类',0,'127.0.0.1'),(24,'2018-06-05 03:06:09','12','新增信息',0,'127.0.0.1'),(25,'2018-06-05 03:06:28','23','新增信息',0,'127.0.0.1'),(26,'2018-06-05 03:06:43','34','新增信息',0,'127.0.0.1'),(27,'2018-06-05 03:06:57','45','新增信息',0,'127.0.0.1'),(28,'2018-06-05 03:06:05','center1','新增信息',0,'127.0.0.1'),(29,'2018-06-05 03:06:31','center2','新增信息',0,'127.0.0.1'),(30,'2018-06-05 03:06:44','center3','新增信息',0,'127.0.0.1'),(31,'2018-06-05 03:06:56','center4','新增信息',0,'127.0.0.1'),(32,'2018-06-05 03:06:11','center5','新增信息',0,'127.0.0.1'),(33,'2018-06-05 03:06:08','wer','修改分类',0,'127.0.0.1'),(34,'2018-06-05 03:06:11','公司介绍','修改信息',0,'127.0.0.1'),(35,'2018-06-05 03:06:21','公司介绍','修改信息',0,'127.0.0.1'),(36,'2018-06-05 03:06:13','公司介绍','修改信息',0,'127.0.0.1'),(37,'2018-06-05 03:06:03','公司介绍','修改信息',0,'127.0.0.1'),(38,'2018-06-05 03:06:12','公司介绍','修改信息',0,'127.0.0.1'),(39,'2018-06-05 08:06:07','新闻','新增分类',0,'127.0.0.1'),(40,'2018-06-05 08:06:14','新闻','修改分类',0,'127.0.0.1'),(41,'2018-06-05 08:06:53','robox','新增信息',0,'127.0.0.1'),(42,'2018-06-05 08:06:42','robox2','新增信息',0,'127.0.0.1'),(43,'2018-06-05 08:06:21','robox3','新增信息',0,'127.0.0.1'),(44,'2018-06-05 08:06:47','robox4','新增信息',0,'127.0.0.1'),(45,'2018-06-05 08:06:39','robox3','修改信息',0,'127.0.0.1'),(46,'2018-06-05 08:06:53','robox4','修改信息',0,'127.0.0.1'),(47,'2018-06-05 08:06:21','robox4','修改信息',0,'127.0.0.1'),(48,'2018-06-05 08:06:58','robox4','修改信息',0,'127.0.0.1'),(49,'2018-06-05 08:06:07','robox3','修改信息',0,'127.0.0.1'),(50,'2018-06-05 09:06:20','robox2','修改信息',0,'127.0.0.1'),(51,'2018-06-05 09:06:28','robox','修改信息',0,'127.0.0.1'),(52,'2018-06-05 09:06:30','公司介绍','修改分类',0,'127.0.0.1'),(53,'2018-06-05 09:06:58','公司介绍','修改分类',0,'127.0.0.1'),(54,'2018-06-05 09:06:07','公司介绍','修改分类',0,'127.0.0.1'),(55,'2018-06-05 09:06:12','公司介绍','修改分类',0,'127.0.0.1'),(56,'2018-06-05 09:06:35','公司介绍','修改信息',0,'127.0.0.1'),(57,'2018-06-05 09:06:11','公司介绍','修改信息',0,'127.0.0.1'),(58,'2018-06-05 09:06:10','公司介绍','修改信息',0,'127.0.0.1'),(59,'2018-06-05 09:06:31','q','修改信息',0,'127.0.0.1'),(60,'2018-06-05 09:06:26','q','修改信息',0,'127.0.0.1'),(61,'2018-06-05 09:06:10','q','修改信息',0,'127.0.0.1'),(62,'2018-06-05 09:06:55','瑞博思（芜湖）智能控制系统有限公司','修改基本设置',0,'127.0.0.1'),(63,'2018-06-05 10:06:42','瑞博思（芜湖）智能控制系统有限公司','修改基本设置',0,'127.0.0.1'),(64,'2018-06-05 10:06:27','瑞博思（芜湖）智能控制系统有限公司','修改基本设置',0,'127.0.0.1'),(65,'2018-06-05 10:06:05','瑞博思（芜湖）智能控制系统有限公司','修改基本设置',0,'127.0.0.1'),(66,'2018-06-05 10:06:39','45发生的三发送到电视地方速度发送到速度','修改信息',0,'127.0.0.1');
