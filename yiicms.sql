-- --------------------------------------------------------
-- 主机:                           127.0.0.1
-- 服务器版本:                        5.5.53 - MySQL Community Server (GPL)
-- 服务器操作系统:                      Win32
-- HeidiSQL 版本:                  9.5.0.5196
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- 导出 yiicms 的数据库结构
CREATE DATABASE IF NOT EXISTS `yiicms` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `yiicms`;

-- 导出  表 yiicms.tk_article 结构
CREATE TABLE IF NOT EXISTS `tk_article` (
  `article_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '文章id',
  `dateline` int(11) NOT NULL COMMENT '发布时间',
  `title` varchar(255) NOT NULL COMMENT '发布标题',
  `content` text NOT NULL COMMENT '发布内容',
  `view` int(11) NOT NULL COMMENT '阅读量',
  PRIMARY KEY (`article_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='文章表';

-- 正在导出表  yiicms.tk_article 的数据：~1 rows (大约)
/*!40000 ALTER TABLE `tk_article` DISABLE KEYS */;
INSERT INTO `tk_article` (`article_id`, `dateline`, `title`, `content`, `view`) VALUES
	(1, 0, '221', 'cnotent', 0);
/*!40000 ALTER TABLE `tk_article` ENABLE KEYS */;

-- 导出  表 yiicms.tk_article_category 结构
CREATE TABLE IF NOT EXISTS `tk_article_category` (
  `article_id` int(11) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  KEY `FK_tk_article_category_tk_article` (`article_id`),
  KEY `FK_tk_article_category_tk_category` (`category_id`),
  CONSTRAINT `FK_tk_article_category_tk_article` FOREIGN KEY (`article_id`) REFERENCES `tk_article` (`article_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_tk_article_category_tk_category` FOREIGN KEY (`category_id`) REFERENCES `tk_category` (`category_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='文章分类关联表';

-- 正在导出表  yiicms.tk_article_category 的数据：~0 rows (大约)
/*!40000 ALTER TABLE `tk_article_category` DISABLE KEYS */;
/*!40000 ALTER TABLE `tk_article_category` ENABLE KEYS */;

-- 导出  表 yiicms.tk_auth 结构
CREATE TABLE IF NOT EXISTS `tk_auth` (
  `auth_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `auth_name` varchar(32) NOT NULL COMMENT '权限名称',
  `module_name` varchar(32) NOT NULL DEFAULT '' COMMENT '模块名',
  `auth_c` varchar(32) NOT NULL DEFAULT '' COMMENT '控制器名称',
  `auth_a` varchar(32) NOT NULL DEFAULT '' COMMENT '方法名称',
  `sort_order` tinyint(3) unsigned NOT NULL DEFAULT '50' COMMENT '排序',
  PRIMARY KEY (`auth_id`)
) ENGINE=InnoDB AUTO_INCREMENT=344 DEFAULT CHARSET=utf8 COMMENT='权限表';

-- 正在导出表  yiicms.tk_auth 的数据：~187 rows (大约)
/*!40000 ALTER TABLE `tk_auth` DISABLE KEYS */;
INSERT INTO `tk_auth` (`auth_id`, `auth_name`, `module_name`, `auth_c`, `auth_a`, `sort_order`) VALUES
	(34, '运营管理', '', 'franchis', '', 41),
	(35, '合作伙伴列表', '', 'franchis', 'index', 50),
	(36, '合作伙伴添加', '', 'franchis', 'add', 50),
	(37, '编辑合作伙伴', '', 'franchis', 'edit', 50),
	(39, '更新帐号状态', '', 'franchis', 'update-account', 50),
	(40, '终止合作伙伴合作', '', 'franchis', 'end-cooperation', 50),
	(41, '合作伙伴流水', '', 'franchis', 'trans', 51),
	(42, '合作伙伴流水下载', '', 'franchis', 'download', 50),
	(43, '合作伙伴下属商户', '', 'franchis', 'get-business-list', 50),
	(44, '商户管理', '', 'business', '', 42),
	(45, '商户列表', '', 'business', 'index', 50),
	(46, '添加商户', '', 'business', 'add', 40),
	(47, '编辑商户', '', 'business', 'edit', 41),
	(49, '更新商户状态', '', 'business', 'update-status', 43),
	(50, '终止商户合作', '', 'business', 'end-cooperation', 44),
	(52, '微信支付', '', 'business', '', 46),
	(53, '开通微信支付', '', 'business', 'open-wechat', 50),
	(54, '修改微信支付', '', 'business', 'edit-wechat', 50),
	(56, '注销微信支付', '', 'business', 'close-wechat', 50),
	(57, '民生银行', '', 'business', '', 48),
	(58, '开通民生银行', '', 'business', 'open-msyh', 50),
	(60, '修改民生银行', '', 'business', 'edit-msyh', 50),
	(61, '打开和禁用民生银行', '', 'business', 'start-and-forbidden-msyh', 50),
	(62, '修改民生银行商户模式', '', 'business', 'switch-mode-by-bus-id', 50),
	(63, '平安银行', '', 'business', '', 49),
	(64, '开通平安银行', '', 'business', 'open-payh', 50),
	(65, '修改平安银行', '', 'business', 'edit-payh', 50),
	(67, '打开和禁用平安银行', '', 'business', 'start-and-forbidden-payh', 50),
	(68, '威付通', '', 'business', '', 50),
	(69, '开通威付通', '', 'business', 'open-wft', 50),
	(70, '修改威付通', '', 'business', 'edit-wft', 50),
	(72, '关闭威付通', '', 'business', 'close-wft', 50),
	(73, '支付宝', '', 'business', '', 47),
	(74, '开通支付宝', '', 'business', 'open-alipay', 50),
	(75, '编辑支付宝', '', 'business', 'edit-alipay', 50),
	(77, '关闭支付宝', '', 'business', 'close-alipay', 50),
	(78, '门店列表', '', 'store', 'index', 50),
	(80, '添加门店', '', 'store', 'add', 50),
	(82, '编辑门店', '', 'store', 'edit', 50),
	(83, '更新门店状态', '', 'store', 'update-status', 50),
	(84, '删除门店', '', 'store', 'delete', 50),
	(85, '设备管理', '', 'machine', '', 50),
	(86, '设备列表', '', 'machine', 'index', 50),
	(87, '添加设备', '', 'machine', 'add', 50),
	(88, '编辑设备', '', 'machine', 'edit', 50),
	(90, '删除设备', '', 'machine', 'delete', 50),
	(91, '更新设备状态', '', 'machine', 'update-status', 50),
	(92, '通知人列表查询', '', 'machine', 'get-machine-notify-by-mach-id', 50),
	(93, '添加通知人', '', 'machine', 'add-notify', 50),
	(94, '移除通知人', '', 'machine', 'remove-notify', 50),
	(95, '添加打印机', '', 'machine', 'add-print', 50),
	(96, '移除打印机', '', 'machine', 'remove-print', 50),
	(98, '微信支付', '', 'wechattrans', 'index', 50),
	(99, '下载报表', '', 'wechattrans', 'download', 50),
	(101, '支付宝', '', 'alipay', 'index', 50),
	(102, '下载报表', '', 'alipay', 'download', 50),
	(104, '民生银行', '', 'msyh', 'index', 50),
	(105, '提现对账单', '', 'msyh', 'settle', 50),
	(106, '下载报表', '', 'msyh', 'download', 50),
	(108, '平安银行', '', 'payhtrans', 'index', 50),
	(109, '下载报表', '', 'payhtrans', 'download', 50),
	(111, '中信银行', '', 'wfttrans', 'index', 51),
	(112, '下载报表', '', 'wfttrans', 'download', 50),
	(116, '系统管理', '', '', '', 40),
	(121, '角色管理', '', 'role', 'index', 46),
	(122, '添加角色', '', 'role', 'add', 50),
	(123, '编辑角色', '', 'role', 'edit', 50),
	(124, '删除角色', '', 'role', 'delete', 50),
	(125, '用户管理', '', 'user', 'index', 47),
	(126, '添加用户', '', 'user', 'add', 50),
	(127, '编辑用户', '', 'user', 'edit', 50),
	(128, '删除用户', '', 'user', 'delete', 50),
	(129, '微信解绑', '', 'user', 'wechat-unbind', 50),
	(130, '冻结和解冻用户', '', 'user', 'update-enabled-by-id', 50),
	(131, '分类管理', '', 'category', 'index', 48),
	(132, '添加分类', '', 'category', 'add', 50),
	(133, '编辑分类', '', 'category', 'edit', 50),
	(134, '删除分类', '', 'category', 'delete', 50),
	(135, '地区管理', '', 'region', 'index', 49),
	(136, '添加地区', '', 'region', 'add', 50),
	(137, '编辑地区', '', 'region', 'edit', 50),
	(138, '删除地区', '', 'region', 'delete', 50),
	(139, '权限管理', '', 'auth', 'index', 45),
	(140, '添加权限', '', 'auth', 'add', 50),
	(141, '编辑权限', '', 'auth', 'edit', 50),
	(142, '删除权限', '', 'auth', 'delete', 50),
	(144, '系统设置', '', 'home', '', 50),
	(145, '绑定微信', '', 'home', 'wechat-bind', 50),
	(146, '微信解绑', '', 'home', 'wechat-unbind', 50),
	(147, '修改密码', '', 'home', 'password', 50),
	(148, '实时数据', '', 'home', 'real-date', 50),
	(149, '查询商户所有信息', '', 'business', 'get-business-info-by-bus-no', 45),
	(150, '打印机', '', 'machine', '', 50),
	(151, '查看打印机', '', 'machine', 'get-print-by-mach-id', 50),
	(152, '通知人', '', 'machine', '', 50),
	(153, '渠道统计', '', '', '', 50),
	(154, '交易统计', '', 'channel', 'trade-statis', 50),
	(241, '修改金额', '', 'business', 'set-draw', 50),
	(242, '更新资料', '', 'business', 'sync-msyh-info', 50),
	(243, '上传审核', '', 'business', 'repeat-report', 50),
	(244, '关联账单', '', 'msyh', 'logs-by-draw-id', 50),
	(245, '绑定公众号', '', 'business', 'bind-mp-appid', 50),
	(246, '添加授权目录', '', 'business', 'add-pay-auth-dir', 50),
	(247, '绑定APP', '', 'business', 'bind-mobile-appid', 50),
	(248, '下载民生未通过商户', '', 'msyh', 'not-pass-bus-list', 50),
	(251, '京东支付', '', 'jdtrans', 'index', 50),
	(252, '下载报表', '', 'jdtrans', 'download', 50),
	(253, '清算查询', '', 'msyh', 'query-qing-suan-info', 50),
	(254, '审核管理', '', 'report', '', 42),
	(255, '审核划拨', '', 'report', 'alloc', 50),
	(257, '分配任务', '', 'report', 'batch-task', 50),
	(259, '配置支付信息', '', 'report', 'create-and-config', 50),
	(260, '审核日志', '', 'report', 'verify-log', 50),
	(261, '工单审核', '', 'report', 'verify-list', 50),
	(265, '驳回审核', '', 'report', 'refuse', 50),
	(266, '中止审核', '', 'report', 'end', 50),
	(268, '是否上线', '', 'report', 'is-allow-visit', 50),
	(269, '返回审核列表', '', 'report', 'verify-list', 50),
	(270, '审核', '', 'report', 'examine', 50),
	(271, '获取审核信息', '', 'report', 'verify-info-by-id', 50),
	(272, '获取开通信息', '', 'report', 'open-channel', 50),
	(273, '完成工单', '', 'report', 'pass', 50),
	(274, '开通微信', '', 'config', 'wechat', 50),
	(275, '开通民生银行', '', 'config', 'msyh', 50),
	(276, '开通威付通', '', 'config', 'wft', 50),
	(277, '开通京东', '', 'config', 'jd', 50),
	(278, '绑定公众号', '', 'wft', 'bind-mp-appid', 50),
	(279, '添加授权目录', '', 'wft', 'add-pay-auth-dir', 50),
	(280, '绑定APP', '', 'wft', 'bind-mobile-appid', 50),
	(281, '推荐关注', '', 'wft', 'add-recommend-attention', 50),
	(282, '推荐关注', '', 'business', 'add-recommend-attention', 50),
	(283, '交易记录', '', '', '', 50),
	(284, '民生银行', '', '', '', 50),
	(285, '银联快捷支付', '', 'sf', 'trans', 50),
	(286, '扫呗支付', '', 'sb', '', 50),
	(287, '开通扫呗', '', 'sb', 'open', 50),
	(288, '更新商户资料', '', 'sb', 'update', 50),
	(289, '打开和禁用', '', 'sb', 'start-and-forbidden', 50),
	(291, '交易记录', '', 'sb', 'trans', 50),
	(292, '快捷支付', '', 'sf', '', 50),
	(293, '开通快捷支付', '', 'sf', 'open', 50),
	(294, '审核开通快捷支付', '', 'sf', 'report-open', 50),
	(295, '打开或禁用快捷支付', '', 'sf', 'start-and-forbidden', 50),
	(296, '京东', '', '', '', 50),
	(297, '开通京东', '', 'business', 'open-jd', 50),
	(298, '修改京东支付', '', 'business', 'edit-jd', 50),
	(299, '根据商户ID查询京东信息', '', 'business', 'get-jd-config-by-busId', 50),
	(300, '关闭京东支付', '', 'business', 'close-jd', 50),
	(301, '平台开通快捷支付', '', 'sf', 'platform-open', 50),
	(302, '根据商户ID查询支付配置信息', '', 'sb', 'get-config-by-bus-id', 50),
	(303, '富友扫呗', '', '', '', 50),
	(304, '提现记录', '', 'sb', 'draw-log', 50),
	(305, '新大陆', '', 'xdl', '', 50),
	(306, '开通新大陆', '', 'xdl', 'open', 50),
	(307, '更新新大陆', '', 'xdl', 'update', 50),
	(308, '根据商户ID查询支付配置信息', '', 'xdl', 'get-config-by-bus-id', 50),
	(309, '打开和禁用', '', 'xdl', 'start-and-forbidden', 50),
	(310, '模糊查询支行', '', 'xdl', 'search-bank', 50),
	(311, '星驿付', '', 'xdl', 'trans', 50),
	(312, '开通快捷支付', '', 'config', 'sf', 50),
	(314, '清分下载', '', 'sf', 'qing-fen', 50),
	(315, '修改快捷支付', '', 'sf', 'update', 50),
	(316, '根据商户ID查询支付配置信息', '', 'sf', 'get-config-by-bus-id', 50),
	(317, '获取商户开通快捷支付的参数', '', 'sf', 'get-quick-params', 50),
	(318, '特殊渠道', '', 'rm', 'index', 50),
	(319, '设置默认支付渠道', '', 'business', 'default-channel', 50),
	(321, '华兴银行', '', 'hxyh', '', 50),
	(322, '开通渠道', '', 'hxyh', 'open', 50),
	(323, '更新商户资料', '', 'hxyh', 'update', 50),
	(324, '开通支付产品', '', 'hxyh', 'openProduct', 50),
	(325, '绑定公众号', '', 'hxyh', 'bind-mp-appid', 50),
	(326, '解绑公众号', '', 'hxyh', 'unbind-mp-appid', 50),
	(327, '添加支付授权目录', '', 'hxyh', 'add-pay-auth-dir', 50),
	(328, '添加推荐关注', '', 'hxyh', 'add-recommend-attention', 50),
	(329, '华兴银行', '', 'hxyh', 'trans', 50),
	(330, '更新支付产品', '', 'hxyh', 'update-product', 50),
	(331, '易宝快捷支付', '', 'yb', 'trans', 51),
	(332, '易宝快捷支付', '', 'yb', '', 50),
	(333, '开通易宝快捷支付', '', 'yb', 'open', 50),
	(334, '审核开通易宝快捷支付', '', 'yb', 'report-open', 50),
	(335, '打开或禁用易宝快捷支付', '', 'yb', 'start-and-forbidden', 50),
	(336, '平台开通易宝快捷支付', '', 'yb', 'platform-open', 50),
	(337, '修改易宝快捷支付', '', 'yb', 'update', 50),
	(338, '根据商户ID查询支付配', '', 'yb', 'get-config-by-bus-id', 50),
	(340, '获取商户开通快捷支付的参数', '', 'yb', 'get-quick-params', 50),
	(342, '资料打包', '', 'report', 'img-download', 50),
	(343, '测试权限', 'v1', 'group', 'auth', 50);
/*!40000 ALTER TABLE `tk_auth` ENABLE KEYS */;

-- 导出  表 yiicms.tk_category 结构
CREATE TABLE IF NOT EXISTS `tk_category` (
  `category_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '分类id',
  `name` varchar(50) NOT NULL COMMENT '分类名称',
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='文章分类';

-- 正在导出表  yiicms.tk_category 的数据：~0 rows (大约)
/*!40000 ALTER TABLE `tk_category` DISABLE KEYS */;
/*!40000 ALTER TABLE `tk_category` ENABLE KEYS */;

-- 导出  表 yiicms.tk_group 结构
CREATE TABLE IF NOT EXISTS `tk_group` (
  `group_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '管理组id',
  `name` varchar(50) NOT NULL COMMENT '组名称',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1表示启用,0表示关闭的',
  PRIMARY KEY (`group_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='后天管理组';

-- 正在导出表  yiicms.tk_group 的数据：~4 rows (大约)
/*!40000 ALTER TABLE `tk_group` DISABLE KEYS */;
INSERT INTO `tk_group` (`group_id`, `name`, `status`) VALUES
	(1, '超级 管理员', 1),
	(3, '下拍下哦', 1),
	(4, '下拍下哦', 1),
	(5, '会计', 1);
/*!40000 ALTER TABLE `tk_group` ENABLE KEYS */;

-- 导出  表 yiicms.tk_group_auth 结构
CREATE TABLE IF NOT EXISTS `tk_group_auth` (
  `group_id` int(11) NOT NULL COMMENT '管理组id',
  `auth_id` int(10) unsigned NOT NULL COMMENT '权限id',
  KEY `group_id` (`group_id`),
  KEY `auth_id` (`auth_id`),
  CONSTRAINT `FK_tk_group_auth_tk_auth` FOREIGN KEY (`auth_id`) REFERENCES `tk_auth` (`auth_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_tk_group_auth_tk_group` FOREIGN KEY (`group_id`) REFERENCES `tk_group` (`group_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='管理组和权限关联表';

-- 正在导出表  yiicms.tk_group_auth 的数据：~1 rows (大约)
/*!40000 ALTER TABLE `tk_group_auth` DISABLE KEYS */;
INSERT INTO `tk_group_auth` (`group_id`, `auth_id`) VALUES
	(1, 343);
/*!40000 ALTER TABLE `tk_group_auth` ENABLE KEYS */;

-- 导出  表 yiicms.tk_menu 结构
CREATE TABLE IF NOT EXISTS `tk_menu` (
  `menu_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL COMMENT '菜单名',
  `router` varchar(50) NOT NULL COMMENT '路由',
  `pid` int(11) NOT NULL COMMENT '父id',
  `sort` int(11) NOT NULL DEFAULT '1' COMMENT '排序id',
  `moudule` varchar(50) NOT NULL COMMENT '模块名',
  `controller` varchar(50) NOT NULL COMMENT '控制器',
  `action` varchar(50) NOT NULL COMMENT '方法',
  PRIMARY KEY (`menu_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='后台菜单表';

-- 正在导出表  yiicms.tk_menu 的数据：~5 rows (大约)
/*!40000 ALTER TABLE `tk_menu` DISABLE KEYS */;
INSERT INTO `tk_menu` (`menu_id`, `name`, `router`, `pid`, `sort`, `moudule`, `controller`, `action`) VALUES
	(1, '系统管理', '', 0, 1, '', '', ''),
	(2, '权限管理', 'system/auth', 1, 1, 'v1', 'system', 'auth'),
	(3, '用户组管理', 'system/group', 1, 1, 'v1', 'system', 'group'),
	(4, '菜单管理', 'system/menu', 1, 1, 'v1', 'system', 'menu'),
	(5, '用户管理', 'system/user', 1, 1, 'v1', 'system', 'user');
/*!40000 ALTER TABLE `tk_menu` ENABLE KEYS */;

-- 导出  表 yiicms.tk_menu_group 结构
CREATE TABLE IF NOT EXISTS `tk_menu_group` (
  `menu_id` int(11) NOT NULL COMMENT '菜单id',
  `group_id` int(11) NOT NULL COMMENT '用户组id',
  KEY `FK_tk_menu_group_tk_menu` (`menu_id`),
  KEY `FK_tk_menu_group_tk_group` (`group_id`),
  CONSTRAINT `FK_tk_menu_group_tk_group` FOREIGN KEY (`group_id`) REFERENCES `tk_group` (`group_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_tk_menu_group_tk_menu` FOREIGN KEY (`menu_id`) REFERENCES `tk_menu` (`menu_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='后台菜单用户组关联表';

-- 正在导出表  yiicms.tk_menu_group 的数据：~4 rows (大约)
/*!40000 ALTER TABLE `tk_menu_group` DISABLE KEYS */;
INSERT INTO `tk_menu_group` (`menu_id`, `group_id`) VALUES
	(1, 1),
	(4, 1),
	(5, 1),
	(2, 1);
/*!40000 ALTER TABLE `tk_menu_group` ENABLE KEYS */;

-- 导出  表 yiicms.tk_user 结构
CREATE TABLE IF NOT EXISTS `tk_user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '管理员id',
  `username` varchar(50) NOT NULL COMMENT '管理员账号名',
  `password` varchar(255) NOT NULL COMMENT '管理员密码',
  `nickname` varchar(255) NOT NULL COMMENT '昵称',
  `phone` varchar(11) NOT NULL COMMENT '手机号',
  `group_id` int(11) NOT NULL COMMENT '管理组id',
  `auth_key` varchar(50) NOT NULL COMMENT '密钥',
  `status` tinyint(4) DEFAULT '1' COMMENT '1表示启用,0表示不启用',
  PRIMARY KEY (`user_id`),
  KEY `username` (`username`),
  KEY `tk_admin_user_group_id` (`group_id`),
  KEY `auth_key` (`auth_key`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='后台管理员';

-- 正在导出表  yiicms.tk_user 的数据：~2 rows (大约)
/*!40000 ALTER TABLE `tk_user` DISABLE KEYS */;
INSERT INTO `tk_user` (`user_id`, `username`, `password`, `nickname`, `phone`, `group_id`, `auth_key`, `status`) VALUES
	(1, 'admin', 'e10adc3949ba59abbe56e057f20f883e', '', '', 1, 'abegjlmnoqrsuvwyzEFJMNPRSUWXYZ23', 1),
	(8, 'admin1112', 'b6fece1516497bab9a1f6a727af2a0fb', '', '', 1, '123', 1);
/*!40000 ALTER TABLE `tk_user` ENABLE KEYS */;

-- 导出  表 yiicms.tk_user_group 结构
CREATE TABLE IF NOT EXISTS `tk_user_group` (
  `user_id` int(11) DEFAULT NULL,
  `group_id` int(11) DEFAULT NULL,
  KEY `FK_tk_user_group_tk_group` (`group_id`),
  KEY `FK_tk_user_group_tk_user` (`user_id`),
  CONSTRAINT `FK_tk_user_group_tk_group` FOREIGN KEY (`group_id`) REFERENCES `tk_group` (`group_id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `FK_tk_user_group_tk_user` FOREIGN KEY (`user_id`) REFERENCES `tk_user` (`user_id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='管理员和管理组关联表';

-- 正在导出表  yiicms.tk_user_group 的数据：~1 rows (大约)
/*!40000 ALTER TABLE `tk_user_group` DISABLE KEYS */;
INSERT INTO `tk_user_group` (`user_id`, `group_id`) VALUES
	(8, 1);
/*!40000 ALTER TABLE `tk_user_group` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
