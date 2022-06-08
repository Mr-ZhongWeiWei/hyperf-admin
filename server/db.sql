/*
 Navicat Premium Data Transfer

 Source Server         : 117.169.96.165_18733
 Source Server Type    : MySQL
 Source Server Version : 80020
 Source Host           : 117.169.96.165:18733
 Source Schema         : newadmin

 Target Server Type    : MySQL
 Target Server Version : 80020
 File Encoding         : 65001

 Date: 08/06/2022 09:15:25
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for n_access_rule
-- ----------------------------
DROP TABLE IF EXISTS `n_access_rule`;
CREATE TABLE `n_access_rule`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '权限中文名称',
  `rule` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '权限唯一标识',
  `type` tinyint(1) NOT NULL COMMENT '类型 1权限 2权限分组',
  `parent_id` int NOT NULL DEFAULT 0 COMMENT '上级ID',
  `created_at` datetime NOT NULL COMMENT '创建时间',
  `updated_at` datetime NULL DEFAULT NULL,
  `sort` int NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 37 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '权限' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of n_access_rule
-- ----------------------------
INSERT INTO `n_access_rule` VALUES (1, '菜单管理', '', 2, 0, '2022-03-23 12:02:43', '2022-03-23 21:53:53', 99);
INSERT INTO `n_access_rule` VALUES (2, '菜单列表', 'menu@index', 1, 1, '2022-03-23 15:28:55', '2022-03-23 15:28:55', 99);
INSERT INTO `n_access_rule` VALUES (3, '编辑菜单', 'menu@update', 1, 1, '2022-03-23 15:44:07', '2022-03-23 15:44:07', 99);
INSERT INTO `n_access_rule` VALUES (4, '添加菜单', 'menu@create', 1, 1, '2022-03-23 15:44:53', '2022-04-04 14:10:09', 99);
INSERT INTO `n_access_rule` VALUES (5, '删除菜单', 'menu@delete', 1, 1, '2022-03-23 15:45:16', '2022-03-23 15:45:16', 99);
INSERT INTO `n_access_rule` VALUES (6, '权限管理', '', 2, 0, '2022-03-23 15:46:04', '2022-03-23 15:46:04', 99);
INSERT INTO `n_access_rule` VALUES (7, '权限列表', 'access@index', 1, 6, '2022-03-23 15:46:29', '2022-03-23 15:46:29', 99);
INSERT INTO `n_access_rule` VALUES (12, '编辑权限', 'access@update', 1, 6, '2022-03-23 15:48:57', '2022-03-23 15:48:57', 99);
INSERT INTO `n_access_rule` VALUES (13, '添加权限', 'access@create', 1, 6, '2022-03-23 15:49:20', '2022-03-23 15:49:20', 99);
INSERT INTO `n_access_rule` VALUES (14, '删除权限', 'access@delete', 1, 6, '2022-03-23 15:50:08', '2022-03-23 15:50:08', 99);
INSERT INTO `n_access_rule` VALUES (15, '用户管理', '', 2, 0, '2022-03-23 15:51:10', '2022-03-23 15:51:10', 99);
INSERT INTO `n_access_rule` VALUES (16, '管理员列表', 'user@index', 1, 15, '2022-03-23 15:51:52', '2022-03-23 15:51:52', 99);
INSERT INTO `n_access_rule` VALUES (17, '编辑管理员', 'user@update', 1, 15, '2022-03-23 15:54:17', '2022-03-23 15:54:17', 99);
INSERT INTO `n_access_rule` VALUES (18, '添加管理员', 'user@create', 1, 15, '2022-03-23 15:54:39', '2022-03-23 15:54:39', 99);
INSERT INTO `n_access_rule` VALUES (19, '删除管理员', 'user@delete', 1, 15, '2022-03-23 15:55:44', '2022-03-23 15:55:44', 99);
INSERT INTO `n_access_rule` VALUES (24, '角色管理', '', 2, 0, '2022-03-23 21:54:15', '2022-03-23 21:54:15', 99);
INSERT INTO `n_access_rule` VALUES (25, '角色列表', 'role@index', 1, 24, '2022-03-23 21:54:48', '2022-03-23 21:54:48', 99);
INSERT INTO `n_access_rule` VALUES (26, '编辑角色', 'role@update', 1, 24, '2022-03-23 21:56:11', '2022-03-23 21:56:11', 99);
INSERT INTO `n_access_rule` VALUES (27, '添加角色', 'role@create', 1, 24, '2022-03-23 21:56:44', '2022-03-23 21:56:44', 99);
INSERT INTO `n_access_rule` VALUES (28, '删除角色', 'role@delete', 1, 24, '2022-03-23 21:57:01', '2022-03-23 21:57:01', 99);
INSERT INTO `n_access_rule` VALUES (29, '设置权限', 'role@setAccess', 1, 24, '2022-04-04 13:04:06', '2022-04-04 13:04:06', 99);
INSERT INTO `n_access_rule` VALUES (31, '系统日志', '', 2, 0, '2022-04-05 19:09:04', '2022-04-05 19:09:04', 99);
INSERT INTO `n_access_rule` VALUES (32, '日志列表', 'logs@index', 1, 31, '2022-04-05 19:13:55', '2022-04-05 19:13:55', 99);
INSERT INTO `n_access_rule` VALUES (33, '清空日志', 'logs@clear', 1, 31, '2022-04-05 19:14:49', '2022-04-05 19:14:49', 99);
INSERT INTO `n_access_rule` VALUES (34, '网站设置', '', 2, 0, '2022-04-16 13:45:32', '2022-04-16 13:45:32', 99);
INSERT INTO `n_access_rule` VALUES (35, '查看设置', 'config@index', 1, 34, '2022-04-16 13:46:00', '2022-04-16 13:46:00', 99);
INSERT INTO `n_access_rule` VALUES (36, '保存设置', 'config@save', 1, 34, '2022-04-16 13:46:24', '2022-04-16 13:46:24', 99);

-- ----------------------------
-- Table structure for n_config
-- ----------------------------
DROP TABLE IF EXISTS `n_config`;
CREATE TABLE `n_config`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '参数名(英文)',
  `label` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '参数名(中文)',
  `type` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '显示类型',
  `group_id` smallint NOT NULL DEFAULT 0 COMMENT '分组ID',
  `data` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'json数据 配置管理页面',
  `value` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '参数值',
  `extra` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '提示信息',
  `sort` smallint NOT NULL DEFAULT 99 COMMENT '排序',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 23 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of n_config
-- ----------------------------
INSERT INTO `n_config` VALUES (1, 'sso_line', 'SSO配置', 'line', 1, '', '', '', 99);
INSERT INTO `n_config` VALUES (2, 'sso.is_sso', '开启SSO登录', 'radio', 1, '[{\"value\":\"0\",\"label\":\"关闭\"},{\"value\":\"1\",\"label\":\"开启\"}]', '0', '', 99);
INSERT INTO `n_config` VALUES (3, 'sso.oauth_client_id', 'oauth_client_id', 'text', 1, '', 'dlfb95e2e2c4f8ef1b', '', 99);
INSERT INTO `n_config` VALUES (4, 'sso.oauth_client_secret', 'oauth_client_secret', 'text', 1, '', '123456', '', 99);
INSERT INTO `n_config` VALUES (5, 'sso.oauth_scope', 'oauth_scope', 'text', 1, '', 'openid profile roles BaseInfo', '', 99);
INSERT INTO `n_config` VALUES (6, 'sso.oauth_return_url', 'oauth_return_url', 'text', 1, '', 'http://www.51mdzz.top/accesslogin', '', 99);
INSERT INTO `n_config` VALUES (7, 'sso.server_sso_url', 'server_sso_url', 'text', 1, '', 'http://devsso6.dledc.com', '', 99);
INSERT INTO `n_config` VALUES (8, 'sso_api_line', 'SSO接口', 'line', 1, '', '', '', 99);
INSERT INTO `n_config` VALUES (9, 'sso.depart_url', '通过organizedcode获取部门', 'text', 1, '', '/baseInfo/base/users/GetDepartmentListByCode', '', 99);
INSERT INTO `n_config` VALUES (10, 'sso.user_url', '通过组织code获取用户列表', 'text', 1, '', '/baseInfo/base/users/getAllUserList', '', 99);
INSERT INTO `n_config` VALUES (11, 'sso.token_url', '通过code获取token', 'text', 1, '', '/core/connect/token', '', 99);
INSERT INTO `n_config` VALUES (12, 'sso.user_info_url', '获取登录用户信息', 'text', 1, '', '/baseInfo/base/users/getuserinfo', '', 99);
INSERT INTO `n_config` VALUES (13, 'sso.organize_info_url', '通过组织id获取组织信息', 'text', 1, '', '/api/api/Organize/GetOrganizeInfo', '', 99);
INSERT INTO `n_config` VALUES (14, 'sso.group_url', '获取机构分组组内用户', 'text', 1, '', '/baseInfo/base/usergroup/GetUserListByDepart', '', 99);
INSERT INTO `n_config` VALUES (15, 'sso.user_type', '同步用户信息的类型', 'text', 1, '', 'teacher|staff|org', '多个用 | 分开', 99);
INSERT INTO `n_config` VALUES (16, 'sso.sync_scope', 'sync_scope', 'text', 1, '', 'BaseInfo', '', 99);
INSERT INTO `n_config` VALUES (17, 'wxapp.AppID', 'AppID', 'text', 2, '', 'wx016f2300f7a30ff6', '', 99);
INSERT INTO `n_config` VALUES (18, 'wxapp.AppSecret', 'AppSecret', 'text', 2, '', '4b96e85615172e69947d22e2fb11ef03', '', 99);
INSERT INTO `n_config` VALUES (19, 'testimgconfig', '图片', 'multipleimg', 2, '2', '[{\"uid\":\"vc-upload-1650087002658-8\",\"name\":\"sex-1.png\",\"status\":\"done\",\"url\":\"http:\\/\\/127.0.0.1:9501\\/uploads\\/20220416\\/61e8c87896f8ceb606fbbe01a2ea7ef9.png\"},{\"uid\":\"vc-upload-1650087002658-10\",\"name\":\"sex-2.png\",\"status\":\"done\",\"url\":\"http:\\/\\/127.0.0.1:9501\\/uploads\\/20220416\\/9282370a21e33b74e4b8af31cb9bf1d3.png\"}]', '', 99);
INSERT INTO `n_config` VALUES (20, 'onimage', '单张图片', 'singleimg', 2, '', '[{\"uid\":\"vc-upload-1650087378548-3\",\"name\":\"sex-3.jpg\",\"status\":\"done\",\"url\":\"http:\\/\\/127.0.0.1:9501\\/uploads\\/20220416\\/1b4e28a47716ca6f25da3b919c3bb11e.jpg\"}]', '', 99);
INSERT INTO `n_config` VALUES (21, 'setup.title', '网站名称', 'text', 1, '', '后台管理系统', '', 2);
INSERT INTO `n_config` VALUES (22, 'site_line', '网站设置', 'line', 1, '', NULL, '', 1);

-- ----------------------------
-- Table structure for n_logs
-- ----------------------------
DROP TABLE IF EXISTS `n_logs`;
CREATE TABLE `n_logs`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `message` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '日志信息',
  `context` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '日志上下文',
  `level` smallint NOT NULL COMMENT '日志级别',
  `level_name` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '日志级别名称',
  `channel` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '管道名称',
  `writetime` datetime NULL DEFAULT NULL COMMENT '写入时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `message`(`message`) USING BTREE,
  INDEX `level`(`level`) USING BTREE,
  INDEX `channel`(`channel`) USING BTREE,
  INDEX `datetime`(`writetime`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 10 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '日志表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of n_logs
-- ----------------------------
INSERT INTO `n_logs` VALUES (1, '管理员【admin】清空了日志', '', 200, 'INFO', 'app', '2022-05-23 16:12:28');
INSERT INTO `n_logs` VALUES (2, '管理员【admin】退出了系统', '', 200, 'INFO', 'app', '2022-05-23 16:12:32');
INSERT INTO `n_logs` VALUES (3, '管理员【admin】登录了系统', '', 200, 'INFO', 'app', '2022-05-23 16:12:35');
INSERT INTO `n_logs` VALUES (4, '管理员【admin】登录了系统', '', 200, 'INFO', 'app', '2022-06-06 16:50:32');
INSERT INTO `n_logs` VALUES (5, '管理员【admin】退出了系统', '', 200, 'INFO', 'app', '2022-06-06 16:51:00');
INSERT INTO `n_logs` VALUES (6, '管理员【test】登录了系统', '', 200, 'INFO', 'app', '2022-06-06 16:51:09');
INSERT INTO `n_logs` VALUES (7, '管理员【admin】登录了系统', '', 200, 'INFO', 'app', '2022-06-07 17:52:47');
INSERT INTO `n_logs` VALUES (8, '管理员【test】退出了系统', '', 200, 'INFO', 'app', '2022-06-07 17:53:40');
INSERT INTO `n_logs` VALUES (9, '管理员【admin】登录了系统', '', 200, 'INFO', 'app', '2022-06-07 17:53:44');

-- ----------------------------
-- Table structure for n_menu
-- ----------------------------
DROP TABLE IF EXISTS `n_menu`;
CREATE TABLE `n_menu`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '路由名称',
  `parent_id` int NOT NULL DEFAULT 0 COMMENT '上级菜单ID',
  `path` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '路由规则',
  `component` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '页面组件真实路径',
  `redirect` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '重定向地址',
  `meta` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '菜单属性',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '是否启用（1启用 0禁用）',
  `is_show` tinyint(1) NOT NULL DEFAULT 1 COMMENT '是否左侧菜单显示（1显示 0不显示）',
  `access` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '权限值',
  `logic` enum('AND','OR') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'OR' COMMENT '预留字段：权限判断逻辑(OR满足其中一个 AND需要同时满足)',
  `created_at` datetime NOT NULL COMMENT '创建时间',
  `updated_at` datetime NULL DEFAULT NULL,
  `sort` int NOT NULL DEFAULT 0 COMMENT '排序',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `status`(`status`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 20 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '菜单' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of n_menu
-- ----------------------------
INSERT INTO `n_menu` VALUES (2, 'idnex', 8, '/dashboard/analysis', 'dashboard/Analysis', '', '{\"title\":\"\\u4e3b\\u9875\",\"icon\":\"HomeOutlined\"}', 1, 1, '', 'OR', '2022-03-19 21:04:45', '2022-03-22 20:26:22', 1);
INSERT INTO `n_menu` VALUES (8, 'index', 0, '/', 'BasicLayout', '/dashboard/analysis', '{\"title\":\"\\/\",\"icon\":\"\"}', 1, 0, '', 'OR', '2022-03-19 23:30:39', '2022-03-20 11:50:42', 0);
INSERT INTO `n_menu` VALUES (9, 'base-form', 8, '/form', 'RouteView', '/form/base-form', '{\"title\":\"\\u8868\\u5355\",\"icon\":\"FormOutlined\"}', 1, 1, '', 'OR', '2022-03-20 09:49:58', '2022-03-20 10:59:46', 3);
INSERT INTO `n_menu` VALUES (10, 'BaseForm', 9, '/form/base-form', 'form/base-form', '', '{\"title\":\"\\u57fa\\u7840\\u8868\\u5355\",\"icon\":\"\"}', 1, 1, '', 'OR', '2022-03-20 09:50:50', '2022-03-20 11:01:14', 4);
INSERT INTO `n_menu` VALUES (11, 'StepForm', 9, '/form/step-form', 'form/stepForm/StepForm', '', '{\"title\":\"\\u5206\\u6b65\\u8868\\u5355\",\"icon\":\"\"}', 1, 1, '', 'OR', '2022-03-20 09:51:48', '2022-03-20 11:04:42', 4);
INSERT INTO `n_menu` VALUES (12, 'system', 8, '/system', 'RouteView', '', '{\"title\":\"\\u7cfb\\u7edf\\u8bbe\\u7f6e\",\"icon\":\"SettingOutlined\"}', 1, 1, '14,19,5,28,7,13,18,4,27,16,12,17,3,26,2,25,29', 'OR', '2022-03-20 09:52:50', '2022-04-04 14:31:44', 2);
INSERT INTO `n_menu` VALUES (13, 'menu', 12, '/system/menu', 'system/menu', '', '{\"title\":\"\\u83dc\\u5355\\u7ba1\\u7406\",\"icon\":\"\"}', 1, 1, '2,3,4,5', 'OR', '2022-03-20 09:54:04', '2022-04-10 21:48:44', 5);
INSERT INTO `n_menu` VALUES (14, 'access', 12, '/access', 'system/access', '', '{\"title\":\"\\u6743\\u9650\\u7ba1\\u7406\",\"icon\":\"\"}', 1, 1, '7,12,13,14', 'OR', '2022-03-21 22:49:04', '2022-04-10 21:48:44', 7);
INSERT INTO `n_menu` VALUES (15, 'user', 12, '/user/list', 'user/index', '', '{\"title\":\"\\u7528\\u6237\\u7ba1\\u7406\",\"icon\":\"\"}', 1, 1, '16,17,18,19', 'OR', '2022-03-21 23:07:19', '2022-04-10 21:48:44', 8);
INSERT INTO `n_menu` VALUES (16, 'role', 12, '/role', 'role/index', '', '{\"title\":\"\\u89d2\\u8272\\u7ba1\\u7406\",\"icon\":\"\"}', 1, 1, '25,26,27,28', 'OR', '2022-03-21 23:07:54', '2022-04-10 21:48:44', 9);
INSERT INTO `n_menu` VALUES (18, 'systemLogs', 12, '/logs', 'system/logs', '', '{\"title\":\"\\u7cfb\\u7edf\\u65e5\\u5fd7\",\"icon\":\"\"}', 1, 1, '', 'OR', '2022-04-05 19:07:00', '2022-04-10 21:48:44', 10);
INSERT INTO `n_menu` VALUES (19, 'config', 12, '/config', 'system/config/index', '', '{\"title\":\"\\u7f51\\u7ad9\\u8bbe\\u7f6e\",\"icon\":\"\"}', 1, 1, '35,36', 'OR', '2022-04-08 21:55:16', '2022-04-16 13:58:23', 4);

-- ----------------------------
-- Table structure for n_organize
-- ----------------------------
DROP TABLE IF EXISTS `n_organize`;
CREATE TABLE `n_organize`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `organizeid` char(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `parentid` char(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `organizename` char(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `shortname` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL COMMENT '组织简称',
  `organizemail` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL COMMENT '组织邮箱',
  `organizecode` char(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `status` tinyint(1) NULL DEFAULT 1 COMMENT '状态（0禁用，1启用，2删除）',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci COMMENT = '机构表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of n_organize
-- ----------------------------
INSERT INTO `n_organize` VALUES (1, '360000000000', '0', '江西新华云教育', '福建省厦门市', '1553117570@qq.com', '4', 0);

-- ----------------------------
-- Table structure for n_role
-- ----------------------------
DROP TABLE IF EXISTS `n_role`;
CREATE TABLE `n_role`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '角色名称',
  `created_at` datetime NOT NULL COMMENT '创建时间',
  `updated_at` datetime NULL DEFAULT NULL COMMENT '更新时间',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '状态 1启用 0禁用',
  `remark` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '备注说明',
  `access` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '权限id逗号隔开',
  `role_identity` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '角色标识',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `status`(`status`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '角色' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of n_role
-- ----------------------------
INSERT INTO `n_role` VALUES (1, '超级管理员', '2022-03-19 14:47:00', '2022-04-16 13:59:49', 1, '这是超级管理员', '1,2,3,4,5,6,7,12,13,14,15,16,17,18,19,24,25,26,27,28,29,31,32,33,34,35,36', 'Administrator');
INSERT INTO `n_role` VALUES (2, '老师', '2022-04-03 21:56:58', '2022-04-03 21:56:58', 1, '这是一个老师', '', '');
INSERT INTO `n_role` VALUES (3, '行政人员', '2022-04-03 22:06:36', '2022-04-05 18:03:13', 1, '行政人员', '', '');
INSERT INTO `n_role` VALUES (5, '演示账号', '2022-05-17 14:29:14', '2022-05-17 14:30:26', 1, '', '2,7,16,25,32,35', '');

-- ----------------------------
-- Table structure for n_user
-- ----------------------------
DROP TABLE IF EXISTS `n_user`;
CREATE TABLE `n_user`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_name` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '用户名',
  `nickname` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '用户昵称',
  `login_name` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '登录账号',
  `password` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '登录密码',
  `sex` enum('1','2','3') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '3' COMMENT '性别 1男 2女 3保密',
  `mobile` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '手机号',
  `photo` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '用户头像',
  `created_at` datetime NOT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` datetime NULL DEFAULT NULL COMMENT '最后一次修改时间',
  `last_login_time` datetime NULL DEFAULT NULL COMMENT '最后一次登录时间',
  `last_login_ip` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '最后一次登录ip',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '状态 0禁用 1启用',
  `user_type` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '用户类型',
  `uuid` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'sso用户ID',
  `organizeid` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'sso用户所属机构ID',
  `source` enum('system','sso') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'system' COMMENT '用户来源 system:系统用户 sso:底层用户',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `login_name`(`login_name`, `password`) USING BTREE,
  INDEX `create_time`(`created_at`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 12 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '用户表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of n_user
-- ----------------------------
INSERT INTO `n_user` VALUES (1, 'admin', '我叫张三', 'admin', '$2y$10$y/4/xIobBt1xykucwmkGTebE0QGe.t8I0AIXsls2INH86aMw91aeO', '1', '', '', '2022-06-07 17:53:42', '2022-06-07 17:53:43', '2022-06-07 17:53:43', '59.52.37.45', 1, '', '', '', 'system');
INSERT INTO `n_user` VALUES (10, '系统管理员', '系统管理员2', 'admin1', '$2y$10$.ENrjR0F4uUw6uIqOBfcPe6sUeIJEqcm8cfzBBHX8iXv2zoc5fxm6', '3', '18070172700', '', '2022-05-13 14:20:33', '2022-05-13 14:20:33', '2022-05-08 20:25:07', '127.0.0.1', 1, 'org', '2268f9c9-f80f-49c1-b872-cc321867e2aa', '360000000000', 'sso');
INSERT INTO `n_user` VALUES (11, '张三', '演示账号1', 'test', '$2y$10$sQgvt0jDxYSrXK8WXHre4.a4AYd0T7rEeg5Dv/i.AsB.skTYGp7.u', '1', '', '', '2022-06-06 16:51:08', '2022-06-06 16:51:09', '2022-06-06 16:51:09', '59.55.155.244', 1, '', '', '', 'system');

-- ----------------------------
-- Table structure for n_user_role
-- ----------------------------
DROP TABLE IF EXISTS `n_user_role`;
CREATE TABLE `n_user_role`  (
  `uid` int NOT NULL,
  `role_id` int NOT NULL,
  PRIMARY KEY (`uid`, `role_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '用户角色' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of n_user_role
-- ----------------------------
INSERT INTO `n_user_role` VALUES (1, 1);
INSERT INTO `n_user_role` VALUES (1, 2);
INSERT INTO `n_user_role` VALUES (9, 1);
INSERT INTO `n_user_role` VALUES (10, 1);
INSERT INTO `n_user_role` VALUES (11, 5);

SET FOREIGN_KEY_CHECKS = 1;
