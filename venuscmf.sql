-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: 2017-09-27 09:09:00
-- 服务器版本： 5.7.18-0ubuntu0.16.04.1
-- PHP Version: 7.0.22-0ubuntu0.16.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `venuscmf`
--

-- --------------------------------------------------------

--
-- 表的结构 `ven_article`
--

CREATE TABLE `ven_article` (
  `id` bigint(18) UNSIGNED NOT NULL,
  `cid` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '分类 ID',
  `title` varchar(100) NOT NULL DEFAULT '' COMMENT '标题',
  `faceimg` varchar(300) NOT NULL DEFAULT '' COMMENT '缩略图',
  `seo_title` varchar(100) NOT NULL DEFAULT '' COMMENT 'SEO 标题',
  `seo_keys` varchar(200) NOT NULL DEFAULT '' COMMENT 'SEO 关键词',
  `seo_desc` varchar(300) NOT NULL DEFAULT '' COMMENT 'SEO 描述',
  `author` varchar(30) NOT NULL DEFAULT '' COMMENT '作者',
  `source` varchar(100) NOT NULL DEFAULT '' COMMENT '来源',
  `resume` varchar(300) NOT NULL DEFAULT '' COMMENT '摘要',
  `content` text NOT NULL COMMENT '内容',
  `time_create` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间',
  `time_report` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '发布时间',
  `time_update` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '更新时间',
  `uid` int(6) UNSIGNED NOT NULL DEFAULT '0' COMMENT '用户 ID',
  `status` tinyint(2) NOT NULL DEFAULT '0' COMMENT '状态，默认 0 待审核，1 已审核，-1 已删除',
  `click` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '阅读量'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='文章表';

-- --------------------------------------------------------

--
-- 表的结构 `ven_authaccess`
--

CREATE TABLE `ven_authaccess` (
  `id` int(10) UNSIGNED NOT NULL,
  `role_id` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '角色 ID',
  `rule_name` varchar(255) NOT NULL DEFAULT '' COMMENT '规则唯一英文标识,全小写'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='后台权限授权表';

-- --------------------------------------------------------

--
-- 表的结构 `ven_category`
--

CREATE TABLE `ven_category` (
  `id` int(10) UNSIGNED NOT NULL,
  `pid` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '上级分类 ID',
  `path` varchar(100) NOT NULL DEFAULT '' COMMENT '分类层次',
  `name` varchar(100) NOT NULL DEFAULT '' COMMENT '分类名称',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '状态，默认 0 隐藏 1 显示',
  `seo_title` varchar(100) NOT NULL DEFAULT '' COMMENT 'SEO 标题',
  `seo_keys` varchar(200) NOT NULL DEFAULT '' COMMENT 'SEO 关键词',
  `seo_desc` varchar(300) NOT NULL DEFAULT '' COMMENT 'SEO 描述'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='文章分类表';

--
-- 转存表中的数据 `ven_category`
--

INSERT INTO `ven_category` (`id`, `pid`, `path`, `name`, `status`, `seo_title`, `seo_keys`, `seo_desc`) VALUES
(1, 0, '0-1', '文学文摘', 0, '', '文学，文摘', '文学文摘'),
(2, 1, '0-1-2', '儒学', 0, '', '儒学', '儒学儒学'),
(3, 1, '0-1-3', '道学', 0, '', '道学', '道学道学'),
(4, 1, '0-1-4', '佛学', 0, '', '佛学', '佛学'),
(5, 0, '0-5', '网络技术', 1, '', '', ''),
(6, 5, '0-5-6', 'PHP', 0, '', 'PHP', 'PHPPHP'),
(7, 5, '0-5-7', 'Linux', 0, '', 'Linux', 'LinuxLinux'),
(8, 5, '0-5-8', 'jQuery', 0, '', 'jQuery', 'jQueryjQuery'),
(9, 0, '0-9', 'NutsPHP', 0, '', '', ''),
(10, 9, '0-9-10', '基础', 0, '', '', ''),
(11, 9, '0-9-11', '数据库', 1, '', '', ''),
(12, 6, '0-5-6-12', 'PHP基础', 0, '', '', ''),
(13, 7, '0-5-7-13', 'Linux基础', 0, '', '', ''),
(14, 8, '0-5-8-14', 'JavaScript基础', 0, '', '', ''),
(15, 2, '0-1-2-15', '腾讯儒学', 1, '6666', 'yyyyyyy', 'ttttttt'),
(16, 3, '0-1-3-16', '腾讯道学', 0, '', '', ''),
(17, 4, '0-1-4-17', '腾讯佛学', 0, '', '', ''),
(18, 12, '0-5-6-12-18', 'PHP数组', 0, '', '', ''),
(19, 12, '0-5-6-12-19', 'PHP函数', 1, '', '', ''),
(20, 13, '0-5-7-13-20', 'Linux内核', 0, '', '', ''),
(21, 13, '0-5-7-13-21', 'Linux分区', 1, '', '', '');

-- --------------------------------------------------------

--
-- 表的结构 `ven_menu`
--

CREATE TABLE `ven_menu` (
  `id` int(10) UNSIGNED NOT NULL,
  `pid` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '上级菜单 ID',
  `topid` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '所属顶级 ID',
  `module` varchar(30) NOT NULL DEFAULT '' COMMENT '模块名称',
  `control` varchar(30) NOT NULL DEFAULT '' COMMENT '控制器名称',
  `actions` varchar(30) NOT NULL DEFAULT '' COMMENT '方法名称',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '状态，默认 0 隐藏 1 显示',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '菜单名称',
  `icon` varchar(50) DEFAULT '' COMMENT '菜单图标',
  `path` varchar(100) NOT NULL DEFAULT '' COMMENT '层级关系'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='后台菜单表';

--
-- 转存表中的数据 `ven_menu`
--

INSERT INTO `ven_menu` (`id`, `pid`, `topid`, `module`, `control`, `actions`, `status`, `name`, `icon`, `path`) VALUES
(1, 0, 0, 'admin', 'common', 'default', 0, '公共项目', '', '0-1'),
(2, 1, 1, 'admin', 'setting', 'recache', 0, '刷新缓存', '', '0-1-2'),
(3, 1, 1, 'admin', 'myinfo', 'repasswd', 0, '修改密码', '', '0-1-3'),
(4, 0, 0, 'admin', 'manager', 'default', 1, '权限管理', '', '0-4'),
(5, 4, 4, 'admin', 'menu', 'index', 1, '菜单管理', '', '0-4-5'),
(6, 5, 4, 'admin', 'menu', 'add', 0, '新增菜单', '', '0-4-5-6'),
(7, 6, 4, 'admin', 'menu', 'addsave', 0, '保存新增菜单', '', '0-4-5-6-7'),
(8, 5, 4, 'admin', 'menu', 'edit', 0, '编辑菜单', '', '0-4-5-8'),
(9, 8, 4, 'admin', 'menu', 'editsave', 0, '保存编辑菜单', '', '0-4-5-8-9'),
(10, 5, 4, 'admin', 'menu', 'deletes', 0, '删除菜单', '', '0-4-5-10'),
(11, 4, 4, 'admin', 'rbac', 'index', 1, '角色管理', '', '0-4-11'),
(12, 11, 4, 'admin', 'rbac', 'add', 0, '新增角色', '', '0-4-11-12'),
(13, 12, 4, 'admin', 'rbac', 'addsave', 0, '保存新增角色', '', '0-4-11-12-13'),
(14, 11, 4, 'admin', 'rbac', 'edit', 0, '编辑角色', '', '0-4-11-14'),
(15, 14, 4, 'admin', 'rbac', 'editsave', 0, '保存编辑角色', '', '0-4-11-14-15'),
(16, 11, 4, 'admin', 'rbac', 'deletes', 0, '删除角色', '', '0-4-11-16'),
(17, 11, 4, 'admin', 'rbac', 'authorize', 0, '权限设置', '', '0-4-11-17'),
(18, 17, 4, 'admin', 'rbac', 'authorizesave', 0, '保存权限设置', '', '0-4-11-17-18'),
(19, 4, 4, 'admin', 'users', 'index', 1, '用户管理', '', '0-4-19'),
(20, 19, 4, 'admin', 'users', 'add', 0, '新增用户', '', '0-4-19-20'),
(21, 20, 4, 'admin', 'users', 'addsave', 0, '保存新增用户', '', '0-4-19-20-21'),
(22, 19, 4, 'admin', 'users', 'edit', 0, '编辑用户', '', '0-4-19-22'),
(23, 22, 4, 'admin', 'users', 'editsave', 0, '保存编辑用户', '', '0-4-19-22-23'),
(24, 19, 4, 'admin', 'users', 'deletes', 0, '删除用户', '', '0-4-19-24'),
(25, 0, 0, 'admin', 'setting', 'default', 1, '网站设置', '', '0-25'),
(26, 25, 25, 'admin', 'setting', 'siteinfo', 1, '网站信息', '', '0-25-26'),
(27, 26, 25, 'admin', 'setting', 'siteinfosave', 0, '保存网站信息', '', '0-25-26-27'),
(28, 25, 25, 'admin', 'setting', 'imgset', 1, '图片设置', '', '0-25-28'),
(29, 28, 25, 'admin', 'setting', 'imgsetsave', 0, '保存图片设置', '', '0-25-28-29'),
(30, 0, 0, 'admin', 'content', 'default', 1, '内容管理', '', '0-30'),
(31, 30, 30, 'admin', 'category', 'index', 1, '分类管理', '', '0-30-31'),
(32, 31, 30, 'admin', 'category', 'add', 0, '新增分类', '', '0-30-31-32'),
(33, 32, 30, 'admin', 'category', 'addsave', 0, '保存新增分类', '', '0-30-31-32-33'),
(34, 31, 30, 'admin', 'category', 'edit', 0, '编辑分类', '', '0-30-31-34'),
(35, 34, 30, 'admin', 'category', 'editsave', 0, '保存编辑分类', '', '0-30-31-34-35'),
(36, 31, 30, 'admin', 'category', 'deletes', 0, '删除分类', '', '0-30-31-36'),
(37, 30, 30, 'admin', 'article', 'index', 1, '文章管理', '', '0-30-37'),
(38, 37, 30, 'admin', 'article', 'add', 0, '新增文章', '', '0-30-37-38'),
(39, 38, 30, 'admin', 'article', 'addsave', 0, '保存新增文章', '', '0-30-37-38-39'),
(40, 37, 30, 'admin', 'article', 'edit', 0, '编辑文章', '', '0-30-37-40'),
(41, 40, 30, 'admin', 'article', 'editsave', 0, '保存编辑文章', '', '0-30-37-40-41'),
(42, 37, 30, 'admin', 'article', 'deletes', 0, '删除文章', '', '0-30-37-42'),
(43, 37, 30, 'admin', 'article', 'editstatus', 0, '编辑文章状态', '', '0-30-37-43'),
(44, 37, 30, 'admin', 'article', 'upimage', 0, '上传文章缩略图', '', '0-30-37-44'),
(45, 37, 30, 'admin', 'ueditor', 'doupload', 0, '上传文章内容图片', '', '0-30-37-45'),
(46, 0, 0, 'admin', 'tools', 'default', 1, '扩展工具', '', '0-46'),
(47, 46, 46, 'admin', 'slide', 'default', 1, '幻灯片', '', '0-46-47'),
(48, 47, 46, 'admin', 'slideseat', 'index', 1, '幻灯片分类', '', '0-46-47-48'),
(49, 48, 46, 'admin', 'slideseat', 'add', 0, '新增幻灯片分类', '', '0-46-47-48-49'),
(50, 49, 46, 'admin', 'slideseat', 'addsave', 0, '保存新增幻灯片分类', '', '0-46-47-48-49-50'),
(51, 48, 46, 'admin', 'slideseat', 'edit', 0, '编辑幻灯片分类', '', '0-46-47-48-51'),
(52, 51, 46, 'admin', 'slideseat', 'editsave', 0, '保存编辑幻灯片分类', '', '0-46-47-48-51-52'),
(53, 48, 46, 'admin', 'slideseat', 'deletes', 0, '删除幻灯片分类', '', '0-46-47-48-53'),
(54, 47, 46, 'admin', 'slide', 'index', 1, '幻灯片管理', '', '0-46-47-54'),
(55, 54, 46, 'admin', 'slide', 'add', 0, '新增幻灯片', '', '0-46-47-54-55'),
(56, 55, 46, 'admin', 'slide', 'addsave', 0, '保存新增幻灯片', '', '0-46-47-54-55-56'),
(57, 54, 46, 'admin', 'slide', 'edit', 0, '编辑幻灯片', '', '0-46-47-54-57'),
(58, 57, 46, 'admin', 'slide', 'editsave', 0, '保存编辑幻灯片', '', '0-46-47-54-57-58'),
(59, 54, 46, 'admin', 'slide', 'deletes', 0, '删除幻灯片', '', '0-46-47-54-59'),
(60, 54, 46, 'admin', 'slide', 'editstatus', 0, '编辑幻灯片状态', '', '0-46-47-54-60'),
(61, 54, 46, 'admin', 'slide', 'upimage', 0, '上传幻灯片图片', '', '0-46-47-54-61');

-- --------------------------------------------------------

--
-- 表的结构 `ven_options`
--

CREATE TABLE `ven_options` (
  `id` int(6) UNSIGNED NOT NULL,
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '参数标题',
  `content` varchar(5000) NOT NULL DEFAULT '' COMMENT '参数内容，json 格式',
  `remark` varchar(200) NOT NULL DEFAULT '' COMMENT '备注'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='基础参数表';

--
-- 转存表中的数据 `ven_options`
--

INSERT INTO `ven_options` (`id`, `title`, `content`, `remark`) VALUES
(1, 'siteinfo', '{"sitename":"\\u5c0f\\u67f4\\u72d0\\u7b14\\u8bb0","sitehost":"caihucmf.com","copyright":"\\u67f4\\u80e1\\u7f51\\u7edc","siteicp":"\\u4eacICP\\u590788888888\\u53f7","sitecyber":"\\u4eac\\u516c\\u7f51\\u5b89\\u5907999999999\\u53f7","seo_title":"\\u5c0f\\u67f4\\u72d0\\u7b14\\u8bb0","seo_keys":"\\u6280\\u672f\\uff0c\\u535a\\u5ba2\\uff0c\\u6587\\u6458","seo_desc":"\\u8fd9\\u662f\\u4e00\\u4e2a\\u5173\\u4e8e\\u7f51\\u7edc\\u6280\\u672f\\u7684\\u7f51\\u7ad9\\uff0c\\u8bb0\\u5f55\\u7ad9\\u957f\\u7684\\u6280\\u672f\\u603b\\u7ed3\\u53ca\\u5fc3\\u5f97\\u3002","statis_code":"\\u8fd9\\u662f\\u7edf\\u8ba1\\u4ee3\\u7801","sitestatus":1}', '网站基本信息');

-- --------------------------------------------------------

--
-- 表的结构 `ven_role`
--

CREATE TABLE `ven_role` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '角色名称',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '状态，默认 0 禁用 1 启用',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='后台角色表';

--
-- 转存表中的数据 `ven_role`
--

INSERT INTO `ven_role` (`id`, `name`, `status`, `remark`) VALUES
(1, '超级管理员', 1, '拥有网站最高管理员权限！'),
(2, '编辑', 1, ''),
(3, '合作商', 1, '合作合作');

-- --------------------------------------------------------

--
-- 表的结构 `ven_roleuser`
--

CREATE TABLE `ven_roleuser` (
  `id` int(10) UNSIGNED NOT NULL,
  `role_id` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '角色 ID',
  `uid` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '管理员 ID'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='后台用户角色对应表';

--
-- 转存表中的数据 `ven_roleuser`
--

INSERT INTO `ven_roleuser` (`id`, `role_id`, `uid`) VALUES
(6, 2, 501);

-- --------------------------------------------------------

--
-- 表的结构 `ven_slide`
--

CREATE TABLE `ven_slide` (
  `id` bigint(18) UNSIGNED NOT NULL,
  `cid` int(6) UNSIGNED NOT NULL DEFAULT '0' COMMENT '幻灯片分类 ID',
  `title` varchar(100) NOT NULL DEFAULT '' COMMENT '标题',
  `faceimg` varchar(300) NOT NULL DEFAULT '' COMMENT '图片',
  `url` varchar(300) NOT NULL DEFAULT '' COMMENT '幻灯片链接',
  `time_create` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间',
  `time_update` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态，默认 0 隐藏，1 显示'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='幻灯片内容表';

--
-- 转存表中的数据 `ven_slide`
--

INSERT INTO `ven_slide` (`id`, `cid`, `title`, `faceimg`, `url`, `time_create`, `time_update`, `status`) VALUES
(1, 2, '金条大甩卖', '/uploads/article/201709/1505100994210.jpg', 'http://www.hao123.com', 1505100609, 1506324856, 0);

-- --------------------------------------------------------

--
-- 表的结构 `ven_slideseat`
--

CREATE TABLE `ven_slideseat` (
  `id` int(6) UNSIGNED NOT NULL,
  `title` varchar(200) NOT NULL DEFAULT '' COMMENT '分类名称'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='幻灯片分类表';

--
-- 转存表中的数据 `ven_slideseat`
--

INSERT INTO `ven_slideseat` (`id`, `title`) VALUES
(1, '首页'),
(2, '详情');

-- --------------------------------------------------------

--
-- 表的结构 `ven_users`
--

CREATE TABLE `ven_users` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '用户名',
  `nickname` varchar(20) NOT NULL DEFAULT '' COMMENT '昵称',
  `passwd` varchar(64) NOT NULL DEFAULT '' COMMENT '密码',
  `encrypt_salt` varchar(64) NOT NULL DEFAULT '' COMMENT '加密密钥',
  `login_ip` bigint(20) UNSIGNED NOT NULL DEFAULT '0' COMMENT '最后登录ip',
  `login_count` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '登陆次数',
  `time_login` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `time_create` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间',
  `time_update` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '状态，默认 0 禁用 1 启用'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='后台管理员表';

--
-- 转存表中的数据 `ven_users`
--

INSERT INTO `ven_users` (`id`, `name`, `nickname`, `passwd`, `encrypt_salt`, `login_ip`, `login_count`, `time_login`, `time_create`, `time_update`, `status`) VALUES
(1, 'admins', '阿飞', '7574b81c71f76753a85fb9f93ab7dc44', 'JXYQ8dAvL3ECG5f7r9uS', 167772674, 25, 1506390010, 1462541400, 1502787011, 1),
(501, 'libai', '李白', 'd31f00db118c3a3c16097ca4ad0014d1', '09be8091bfc64d60adc8e8d1e7671ce7', 167772674, 1, 1504852663, 1504852587, 1505092908, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ven_article`
--
ALTER TABLE `ven_article`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ven_authaccess`
--
ALTER TABLE `ven_authaccess`
  ADD PRIMARY KEY (`id`),
  ADD KEY `role_id` (`role_id`),
  ADD KEY `rule_name` (`rule_name`);

--
-- Indexes for table `ven_category`
--
ALTER TABLE `ven_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ven_menu`
--
ALTER TABLE `ven_menu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ven_options`
--
ALTER TABLE `ven_options`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ven_role`
--
ALTER TABLE `ven_role`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ven_roleuser`
--
ALTER TABLE `ven_roleuser`
  ADD PRIMARY KEY (`id`),
  ADD KEY `role_id` (`role_id`),
  ADD KEY `uid` (`uid`);

--
-- Indexes for table `ven_slide`
--
ALTER TABLE `ven_slide`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ven_slideseat`
--
ALTER TABLE `ven_slideseat`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ven_users`
--
ALTER TABLE `ven_users`
  ADD PRIMARY KEY (`id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `ven_article`
--
ALTER TABLE `ven_article`
  MODIFY `id` bigint(18) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `ven_authaccess`
--
ALTER TABLE `ven_authaccess`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `ven_category`
--
ALTER TABLE `ven_category`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
--
-- 使用表AUTO_INCREMENT `ven_menu`
--
ALTER TABLE `ven_menu`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;
--
-- 使用表AUTO_INCREMENT `ven_options`
--
ALTER TABLE `ven_options`
  MODIFY `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- 使用表AUTO_INCREMENT `ven_role`
--
ALTER TABLE `ven_role`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- 使用表AUTO_INCREMENT `ven_roleuser`
--
ALTER TABLE `ven_roleuser`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- 使用表AUTO_INCREMENT `ven_slide`
--
ALTER TABLE `ven_slide`
  MODIFY `id` bigint(18) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- 使用表AUTO_INCREMENT `ven_slideseat`
--
ALTER TABLE `ven_slideseat`
  MODIFY `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- 使用表AUTO_INCREMENT `ven_users`
--
ALTER TABLE `ven_users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=502;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
