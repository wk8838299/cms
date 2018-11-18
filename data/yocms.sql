-- phpMyAdmin SQL Dump
-- version 4.8.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: 2018-11-18 21:01:07
-- 服务器版本： 5.6.40-log
-- PHP Version: 7.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `yocms`
--

-- --------------------------------------------------------

--
-- 表的结构 `yo_addon`
--

CREATE TABLE `yo_addon` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(32) NOT NULL DEFAULT '' COMMENT '插件名称',
  `title` varchar(32) NOT NULL DEFAULT '' COMMENT '插件标题',
  `icon` varchar(64) NOT NULL DEFAULT '' COMMENT '图标',
  `description` text NOT NULL COMMENT '插件描述',
  `author` varchar(32) NOT NULL DEFAULT '' COMMENT '作者',
  `author_url` varchar(255) NOT NULL DEFAULT '' COMMENT '作者主页',
  `config` text NOT NULL COMMENT '配置信息',
  `admin_actions` text NOT NULL COMMENT '管理操作',
  `version` varchar(16) NOT NULL DEFAULT '' COMMENT '版本号',
  `identifier` varchar(64) NOT NULL DEFAULT '' COMMENT '插件唯一标识符',
  `admin` tinyint(4) UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否有后台管理',
  `create_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '安装时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `sort` int(11) NOT NULL DEFAULT '100' COMMENT '排序',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '状态'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='插件表';

--
-- 转存表中的数据 `yo_addon`
--

INSERT INTO `yo_addon` (`id`, `name`, `title`, `icon`, `description`, `author`, `author_url`, `config`, `admin_actions`, `version`, `identifier`, `admin`, `create_time`, `update_time`, `sort`, `status`) VALUES
(20, 'Team', '团队&贡献者', '', '后台首页团队&贡献者显示', 'rainfer', '', '{\"display\":\"1\"}', '', '0.1', '', 0, 1542545276, 1542545276, 100, 1);

-- --------------------------------------------------------

--
-- 表的结构 `yo_admin`
--

CREATE TABLE `yo_admin` (
  `id` int(11) NOT NULL COMMENT 'ID',
  `username` char(20) NOT NULL DEFAULT '' COMMENT '用户名',
  `nickname` char(30) NOT NULL DEFAULT '' COMMENT '昵称',
  `mobile` varchar(11) NOT NULL DEFAULT '' COMMENT '手机号码',
  `email` varchar(80) DEFAULT NULL COMMENT '邮箱',
  `password` varchar(32) NOT NULL DEFAULT '' COMMENT '密码',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `last_login_time` int(11) NOT NULL DEFAULT '0' COMMENT '最近登录时间',
  `last_login_ip` int(10) DEFAULT '0' COMMENT '最近登录 IP',
  `endtime` int(11) NOT NULL DEFAULT '0' COMMENT '最后编辑时间',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `yo_admin`
--

INSERT INTO `yo_admin` (`id`, `username`, `nickname`, `mobile`, `email`, `password`, `addtime`, `last_login_time`, `last_login_ip`, `endtime`, `status`) VALUES
(1, 'admin', 'hao122', '17600362551', '4123@qq.com', '943f8fe4f52cb7c92556cbf2fe819bce', 0, 1542537122, 0, 0, 1),
(2, 'hao123', 'hao123', '18626118944', 'hao122@qq.com', '928e127092bc096e8448cd74750b1149', 0, 1542471879, 0, 0, 1);

-- --------------------------------------------------------

--
-- 表的结构 `yo_auth_extend`
--

CREATE TABLE `yo_auth_extend` (
  `group_id` mediumint(10) UNSIGNED NOT NULL COMMENT '用户id',
  `extend_id` mediumint(8) UNSIGNED NOT NULL COMMENT '扩展表中数据的id',
  `type` tinyint(1) UNSIGNED NOT NULL COMMENT '扩展类型标识 1:栏目分类权限;2:模型权限'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `yo_auth_extend`
--

INSERT INTO `yo_auth_extend` (`group_id`, `extend_id`, `type`) VALUES
(1, 1, 1),
(1, 1, 2),
(1, 2, 1),
(1, 2, 2),
(1, 3, 1),
(1, 3, 2),
(1, 4, 1),
(1, 37, 1);

-- --------------------------------------------------------

--
-- 表的结构 `yo_auth_group`
--

CREATE TABLE `yo_auth_group` (
  `id` mediumint(8) UNSIGNED NOT NULL COMMENT '用户组id,自增主键',
  `module` varchar(20) NOT NULL COMMENT '用户组所属模块',
  `type` tinyint(4) NOT NULL COMMENT '组类型',
  `title` char(20) NOT NULL DEFAULT '' COMMENT '用户组中文名称',
  `description` varchar(80) NOT NULL DEFAULT '' COMMENT '描述信息',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '用户组状态：为1正常，为0禁用,-1为删除',
  `rules` varchar(5000) NOT NULL DEFAULT '' COMMENT '用户组拥有的规则id，多个规则 , 隔开'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `yo_auth_group`
--

INSERT INTO `yo_auth_group` (`id`, `module`, `type`, `title`, `description`, `status`, `rules`) VALUES
(1, 'admin', 1, '超级管理员', '超级管理员组,拥有系统所有权限1', 1, '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16'),
(2, 'admin', 1, '财务管理组', '拥有网站资金相关的权限', 1, '2075,2245'),
(3, 'admin', 1, '资讯管理员', '拥有网站文章资讯相关权限', 1, '2066,2076,2077,2078,2079,2080,2092,2113,2114,2115,2132,2145,2165,2193,2238,2241,2242,2243,2244'),
(4, 'admin', 1, '客服', '客服人员', 1, '1');

-- --------------------------------------------------------

--
-- 表的结构 `yo_auth_group_access`
--

CREATE TABLE `yo_auth_group_access` (
  `uid` int(10) UNSIGNED NOT NULL COMMENT '用户id',
  `group_id` mediumint(8) UNSIGNED NOT NULL COMMENT '用户组id'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- 转存表中的数据 `yo_auth_group_access`
--

INSERT INTO `yo_auth_group_access` (`uid`, `group_id`) VALUES
(1, 1),
(2, 4);

-- --------------------------------------------------------

--
-- 表的结构 `yo_auth_rule`
--

CREATE TABLE `yo_auth_rule` (
  `id` mediumint(8) UNSIGNED NOT NULL COMMENT '规则id,自增主键',
  `module` varchar(20) NOT NULL COMMENT '规则所属module',
  `type` tinyint(2) NOT NULL DEFAULT '1' COMMENT '1-url;2-主菜单',
  `name` char(80) NOT NULL DEFAULT '' COMMENT '规则唯一英文标识',
  `title` char(20) NOT NULL DEFAULT '' COMMENT '规则中文描述',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否有效(0:无效,1:有效)',
  `condition` varchar(300) NOT NULL DEFAULT '' COMMENT '规则附加条件'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `yo_auth_rule`
--

INSERT INTO `yo_auth_rule` (`id`, `module`, `type`, `name`, `title`, `status`, `condition`) VALUES
(1, 'admin', 2, 'admin/index/index', '首页', 1, ''),
(2, 'admin', 2, 'admin/article/index', '内容', 1, ''),
(3, 'admin', 2, 'admin/user/admin', '用户', 1, ''),
(4, 'admin', 2, 'admin/config/index', '设置', 1, ''),
(5, 'admin', 2, 'admin/cloud/index', '扩展', 1, ''),
(6, 'admin', 1, 'admin/menu/index', '菜单管理', 1, ''),
(7, 'admin', 1, 'admin/user/admin', '管理员管理', 1, ''),
(8, 'admin', 1, 'admin/user/auth', '权限列表', 1, ''),
(9, 'admin', 1, 'admin/menu/sort', '排序', 1, ''),
(10, 'admin', 1, 'admin/menu/add', '添加', 1, ''),
(11, 'admin', 1, 'admin/menu/edit', '编辑', 1, ''),
(12, 'admin', 1, 'admin/menu/del', '删除', 1, ''),
(13, 'admin', 1, 'admin/menu/tooglehide', '是否隐藏', 1, ''),
(14, 'admin', 1, 'admin/menu/toogledev', '是否开发', 1, ''),
(15, 'admin', 1, 'admin/menu/importfile', '导入文件', 1, ''),
(16, 'admin', 1, 'admin/menu/import', '导入', 1, '');

-- --------------------------------------------------------

--
-- 表的结构 `yo_config`
--

CREATE TABLE `yo_config` (
  `id` int(11) UNSIGNED NOT NULL,
  `web_name` varchar(200) NOT NULL DEFAULT '' COMMENT '网站名称 ',
  `web_title` varchar(200) NOT NULL DEFAULT '' COMMENT '网站标题',
  `web_logo` varchar(200) NOT NULL DEFAULT '' COMMENT '网站logo',
  `web_llogo_small` varchar(200) NOT NULL DEFAULT '' COMMENT '小logo',
  `web_keywords` text COMMENT '关键词',
  `web_description` text COMMENT '描述',
  `web_close` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否关闭',
  `web_close_cause` text COMMENT '关闭原因',
  `user_reg` tinyint(1) DEFAULT '1' COMMENT '是否允许注册',
  `web_icp` text COMMENT '备案信息',
  `web_cnzz` text COMMENT '统计代码',
  `web_reg` text COMMENT '统计代码'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='系统配置表';

--
-- 转存表中的数据 `yo_config`
--

INSERT INTO `yo_config` (`id`, `web_name`, `web_title`, `web_logo`, `web_llogo_small`, `web_keywords`, `web_description`, `web_close`, `web_close_cause`, `user_reg`, `web_icp`, `web_cnzz`, `web_reg`) VALUES
(1, '官网', '官网', '20181118/ccb0dcec45abacecc873005055951119.png', '20181118/d21aa7cd914d5020a88fb8e47c5a3987.png', '官网', '官网', 1, '官网', 1, '官网', '官网', '官网');

-- --------------------------------------------------------

--
-- 表的结构 `yo_hook`
--

CREATE TABLE `yo_hook` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(32) NOT NULL DEFAULT '' COMMENT '钩子名称',
  `addon` varchar(32) NOT NULL DEFAULT '' COMMENT '钩子来自哪个插件',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '钩子描述',
  `system` tinyint(4) UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否为系统钩子',
  `create_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '状态'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='钩子表';

--
-- 转存表中的数据 `yo_hook`
--

INSERT INTO `yo_hook` (`id`, `name`, `addon`, `description`, `system`, `create_time`, `update_time`, `status`) VALUES
(7, 'team', 'Team', '团队钩子', 0, 1542539379, 1542539379, 1);

-- --------------------------------------------------------

--
-- 表的结构 `yo_hook_addon`
--

CREATE TABLE `yo_hook_addon` (
  `id` int(11) UNSIGNED NOT NULL,
  `hook` varchar(32) NOT NULL DEFAULT '' COMMENT '钩子id',
  `addon` varchar(32) NOT NULL DEFAULT '' COMMENT '插件标识',
  `create_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '添加时间',
  `update_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '更新时间',
  `sort` int(11) UNSIGNED NOT NULL DEFAULT '100' COMMENT '排序',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '状态'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='钩子-插件对应表';

--
-- 转存表中的数据 `yo_hook_addon`
--

INSERT INTO `yo_hook_addon` (`id`, `hook`, `addon`, `create_time`, `update_time`, `sort`, `status`) VALUES
(19, 'team', 'Team', 1542539443, 1542539443, 100, 1),
(14, 'team', 'Team', 1542539425, 1542539425, 100, 1),
(15, 'team', 'Team', 1542539425, 1542539425, 100, 1),
(16, 'team', 'Team', 1542539426, 1542539426, 100, 1),
(17, 'team', 'Team', 1542539426, 1542539426, 100, 1),
(13, 'team', 'Team', 1542539379, 1542539379, 100, 1),
(41, 'team', 'Team', 1542545276, 1542545276, 100, 1),
(40, 'team', 'Team', 1542545246, 1542545246, 100, 1),
(39, 'team', 'Team', 1542545020, 1542545020, 100, 1);

-- --------------------------------------------------------

--
-- 表的结构 `yo_menu`
--

CREATE TABLE `yo_menu` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '文档ID',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '标题',
  `pid` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '上级分类ID',
  `sort` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '排序（同级有效）',
  `url` char(255) NOT NULL DEFAULT '' COMMENT '链接地址',
  `hide` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否隐藏',
  `tip` varchar(255) NOT NULL DEFAULT '' COMMENT '提示',
  `group` varchar(50) DEFAULT '' COMMENT '分组',
  `is_dev` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否仅开发者模式可见',
  `ico_name` varchar(50) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `yo_menu`
--

INSERT INTO `yo_menu` (`id`, `title`, `pid`, `sort`, `url`, `hide`, `tip`, `group`, `is_dev`, `ico_name`) VALUES
(1, '首页', 0, 1, 'index/index', 0, '', '', 0, 'home'),
(2, '内容', 0, 1, 'article/index', 0, '', '', 0, 'list-alt'),
(3, '用户', 0, 1, 'user/admin', 0, '', '', 0, 'user'),
(4, '设置', 0, 1, 'config/index', 0, '', '', 0, 'cog'),
(5, '扩展', 0, 1, 'addons/addons_list', 0, '', '', 0, 'tasks'),
(6, '菜单管理', 4, 2, 'menu/index', 0, '', '设置', 1, 'cog'),
(7, '排序', 6, 5, 'menu/sort', 0, '', '开发组', 0, '0'),
(8, '添加', 6, 5, 'menu/add', 0, '', '开发组', 0, '0'),
(9, '编辑', 6, 5, 'menu/edit', 0, '', '开发组', 0, '0'),
(10, '删除', 6, 5, 'menu/del', 0, '', '开发组', 0, '0'),
(11, '是否隐藏', 6, 5, 'menu/tooglehide', 0, '', '开发组', 0, '0'),
(12, '是否开发', 6, 5, 'menu/toogledev', 0, '', '开发组', 0, '0'),
(13, '导入文件', 4, 5, 'menu/importfile', 1, '', '开发组', 0, 'log-in'),
(14, '导入', 4, 5, 'menu/import', 1, '', '开发组', 0, 'log-in'),
(15, '管理员管理', 3, 1, 'user/admin', 0, '', '后台用户', 0, 'user'),
(16, '权限列表', 3, 2, 'user/auth', 0, '后台用户权限', '后台用户', 0, 'user'),
(17, '用户管理', 3, 2, 'user/index', 0, '', '前台用户', 0, 'user'),
(18, '登录日志', 3, 2, 'user/log', 0, '', '前台用户', 0, 'user'),
(19, '基本配置', 4, 1, 'config/index', 0, '', '设置', 0, 'cog'),
(20, '应用管理', 5, 1, 'addons/addons_list', 0, '', '插件', 0, 'cloud');

-- --------------------------------------------------------

--
-- 表的结构 `yo_user`
--

CREATE TABLE `yo_user` (
  `userid` int(11) NOT NULL COMMENT '用户ID',
  `username` varchar(40) NOT NULL DEFAULT '' COMMENT '用户名',
  `mobile` varchar(30) NOT NULL DEFAULT '' COMMENT '手机号码',
  `c_code` varchar(10) NOT NULL DEFAULT '86' COMMENT '国码',
  `password` varchar(32) NOT NULL DEFAULT '' COMMENT '密码',
  `reg_time` int(11) NOT NULL DEFAULT '0' COMMENT '注册时间',
  `last_time` int(11) NOT NULL DEFAULT '0' COMMENT '最近登录时间',
  `reg_ip` int(10) NOT NULL DEFAULT '0' COMMENT '注册ip',
  `last_ip` int(10) NOT NULL DEFAULT '0' COMMENT '最近ip',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `yo_user`
--

INSERT INTO `yo_user` (`userid`, `username`, `mobile`, `c_code`, `password`, `reg_time`, `last_time`, `reg_ip`, `last_ip`, `status`) VALUES
(1, 'hao123', '17600362558', '86', '420ded34a7c40a4917bde6d09499adc7', 1542519602, 0, 0, 0, 1);

-- --------------------------------------------------------

--
-- 表的结构 `yo_user_info`
--

CREATE TABLE `yo_user_info` (
  `user_info_id` int(11) NOT NULL,
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `realname` varchar(25) NOT NULL DEFAULT '' COMMENT '真实姓名',
  `sex` tinyint(1) NOT NULL DEFAULT '1' COMMENT '性别',
  `icard` varchar(20) NOT NULL DEFAULT '' COMMENT '身份证号码',
  `qq` varchar(15) NOT NULL DEFAULT '0' COMMENT 'QQ号码',
  `wx` varchar(30) NOT NULL DEFAULT '' COMMENT '微信号码',
  `alipay` varchar(50) NOT NULL DEFAULT '' COMMENT '支付宝',
  `question` varchar(100) NOT NULL DEFAULT '' COMMENT '密保问题',
  `answer` varchar(100) NOT NULL DEFAULT '' COMMENT '密保答案',
  `front_card` varchar(200) NOT NULL DEFAULT '' COMMENT '身份证正面',
  `back_card` varchar(200) NOT NULL DEFAULT '' COMMENT '身份证背面'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `yo_user_info`
--

INSERT INTO `yo_user_info` (`user_info_id`, `uid`, `realname`, `sex`, `icard`, `qq`, `wx`, `alipay`, `question`, `answer`, `front_card`, `back_card`) VALUES
(1, 1, '123123123123123', 1, '123', '0', '', '', '', '', '', '');

-- --------------------------------------------------------

--
-- 表的结构 `yo_user_token`
--

CREATE TABLE `yo_user_token` (
  `token_id` int(11) NOT NULL,
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `openid` varchar(60) NOT NULL DEFAULT '' COMMENT 'openid'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `yo_addon`
--
ALTER TABLE `yo_addon`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `yo_admin`
--
ALTER TABLE `yo_admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `yo_auth_extend`
--
ALTER TABLE `yo_auth_extend`
  ADD UNIQUE KEY `group_extend_type` (`group_id`,`extend_id`,`type`),
  ADD KEY `uid` (`group_id`),
  ADD KEY `group_id` (`extend_id`);

--
-- Indexes for table `yo_auth_group`
--
ALTER TABLE `yo_auth_group`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `yo_auth_group_access`
--
ALTER TABLE `yo_auth_group_access`
  ADD UNIQUE KEY `uid_group_id` (`uid`,`group_id`),
  ADD KEY `uid` (`uid`),
  ADD KEY `group_id` (`group_id`);

--
-- Indexes for table `yo_auth_rule`
--
ALTER TABLE `yo_auth_rule`
  ADD PRIMARY KEY (`id`),
  ADD KEY `module` (`module`,`status`,`type`);

--
-- Indexes for table `yo_config`
--
ALTER TABLE `yo_config`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `yo_hook`
--
ALTER TABLE `yo_hook`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `yo_hook_addon`
--
ALTER TABLE `yo_hook_addon`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `yo_menu`
--
ALTER TABLE `yo_menu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `yo_user`
--
ALTER TABLE `yo_user`
  ADD PRIMARY KEY (`userid`);

--
-- Indexes for table `yo_user_info`
--
ALTER TABLE `yo_user_info`
  ADD PRIMARY KEY (`user_info_id`);

--
-- Indexes for table `yo_user_token`
--
ALTER TABLE `yo_user_token`
  ADD PRIMARY KEY (`token_id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `yo_addon`
--
ALTER TABLE `yo_addon`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- 使用表AUTO_INCREMENT `yo_admin`
--
ALTER TABLE `yo_admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=3;

--
-- 使用表AUTO_INCREMENT `yo_auth_group`
--
ALTER TABLE `yo_auth_group`
  MODIFY `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '用户组id,自增主键', AUTO_INCREMENT=5;

--
-- 使用表AUTO_INCREMENT `yo_auth_rule`
--
ALTER TABLE `yo_auth_rule`
  MODIFY `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '规则id,自增主键', AUTO_INCREMENT=17;

--
-- 使用表AUTO_INCREMENT `yo_config`
--
ALTER TABLE `yo_config`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- 使用表AUTO_INCREMENT `yo_hook`
--
ALTER TABLE `yo_hook`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- 使用表AUTO_INCREMENT `yo_hook_addon`
--
ALTER TABLE `yo_hook_addon`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- 使用表AUTO_INCREMENT `yo_menu`
--
ALTER TABLE `yo_menu`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '文档ID', AUTO_INCREMENT=21;

--
-- 使用表AUTO_INCREMENT `yo_user`
--
ALTER TABLE `yo_user`
  MODIFY `userid` int(11) NOT NULL AUTO_INCREMENT COMMENT '用户ID', AUTO_INCREMENT=2;

--
-- 使用表AUTO_INCREMENT `yo_user_info`
--
ALTER TABLE `yo_user_info`
  MODIFY `user_info_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- 使用表AUTO_INCREMENT `yo_user_token`
--
ALTER TABLE `yo_user_token`
  MODIFY `token_id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
