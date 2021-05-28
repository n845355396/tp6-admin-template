/*
 Navicat Premium Data Transfer

 Source Server         : 华为云服务器
 Source Server Type    : MySQL
 Source Server Version : 50727
 Source Host           : 121.36.161.35:3306
 Source Schema         : tp_tmp

 Target Server Type    : MySQL
 Target Server Version : 50727
 File Encoding         : 65001

 Date: 28/05/2021 14:37:29
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for sms_log
-- ----------------------------
DROP TABLE IF EXISTS `sms_log`;
CREATE TABLE `sms_log`  (
  `sms_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'id',
  `type` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '短信类型',
  `type_name` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT '类型名称',
  `send_mode` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '短信发送模式',
  `mobile` varchar(15) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '手机号',
  `request_data` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '请求数据',
  `result_data` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL COMMENT '返回数据',
  `is_retry` int(10) NULL DEFAULT 0 COMMENT '是否已重发',
  `status` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'waiting' COMMENT '短信状态;waiting,success,failed',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `update_time` int(11) NOT NULL COMMENT '更新时间',
  `retry_time` int(11) NULL DEFAULT NULL COMMENT '重发时间',
  PRIMARY KEY (`sms_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 28 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci COMMENT = '短信发送日志表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of sms_log
-- ----------------------------
INSERT INTO `sms_log` VALUES (21, 'test_sms', '测试短信', 'diy', '17621860940', '{\"name\":\"小明\",\"result\":\"小明走队列\"}', '{\"code\":4000,\"msg\":\"balance not sufficient \",\"msgId\":\"162209586316423165441\",\"contNum\":0}', 0, 'failed', 1622095674, 1622095864, NULL);
INSERT INTO `sms_log` VALUES (22, 'test_sms', '测试短信', 'diy', '17621860940', '{\"name\":\"小明\",\"result\":\"小明走队列\"}', '{\"code\":4000,\"msg\":\"balance not sufficient \",\"msgId\":\"162209588427135048961\",\"contNum\":0}', 0, 'failed', 1622095883, 1622095886, NULL);
INSERT INTO `sms_log` VALUES (23, 'test_sms', '测试短信', 'diy', '15526222933', '{\"name\":\"小明\",\"result\":\"小明走队列\"}', '{\"code\":4000,\"msg\":\"balance not sufficient \",\"msgId\":\"162209596189243596801\",\"contNum\":0}', 0, 'failed', 1622095963, 1622095964, NULL);
INSERT INTO `sms_log` VALUES (24, 'test_sms', '测试短信', 'diy', '15526222933', '{\"name\":\"小明\",\"result\":\"小明走队列\"}', '{\"code\":4000,\"msg\":\"balance not sufficient \",\"msgId\":\"162209602270232058881\",\"contNum\":0}', 0, 'failed', 1622096020, 1622096024, NULL);
INSERT INTO `sms_log` VALUES (25, 'test_sms', '测试短信', 'diy', '15526222933', '{\"name\":\"小明\",\"result\":\"小明走队列\"}', '\"\"', 0, 'waiting', 1622096075, 1622096075, NULL);
INSERT INTO `sms_log` VALUES (26, 'test_sms', '测试短信', 'diy', '15526222933', '{\"name\":\"小明\",\"result\":\"小明走队列\"}', '{\"code\":4000,\"msg\":\"balance not sufficient \",\"msgId\":\"162209610430761638401\",\"contNum\":0}', 1, 'failed', 1622096105, 1622096105, 1622170020);
INSERT INTO `sms_log` VALUES (27, 'test_sms', '测试短信', 'diy', '15526222933', '{\"name\":\"小明\",\"result\":\"小明走队列\"}', '{\"code\":4007,\"msg\":\"user disabled\",\"msgId\":\"162217002173015731201\",\"contNum\":0}', 0, 'failed', 1622170020, 1622170020, NULL);

-- ----------------------------
-- Table structure for sys_admin
-- ----------------------------
DROP TABLE IF EXISTS `sys_admin`;
CREATE TABLE `sys_admin`  (
  `admin_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'id',
  `is_super` int(11) NULL DEFAULT 0 COMMENT '是否是超级管理员',
  `status` int(11) NULL DEFAULT 0 COMMENT '管理员状态，0启用，1禁用',
  `avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '头像',
  `login_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '登录账号',
  `nickname` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '昵称',
  `mobile` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '手机号',
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '密码',
  `update_time` int(11) NULL DEFAULT NULL COMMENT '更新时间',
  `create_time` int(11) NULL DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`admin_id`) USING BTREE,
  INDEX `login_name_index`(`login_name`) USING BTREE COMMENT '登录账号唯一索引'
) ENGINE = InnoDB AUTO_INCREMENT = 18 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '平台管理员表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of sys_admin
-- ----------------------------
INSERT INTO `sys_admin` VALUES (1, 1, 0, 'http://tp6-admin-template.local/storage/upload/img/20210523/e2837ed6daf3cebfab6c67a04c919f9c.gif', 'admin', 'admin', NULL, '$2y$10$MlzIJufYGonjzfThknj2Ze/GTIeA5IBuob1zI25xrd1MkNdOWzg4W', 1621781765, 1621239214);
INSERT INTO `sys_admin` VALUES (4, 0, 0, 'http://tp6-admin-template.local/storage/upload/img/20210522/49c927a0848b9991cfe39a6d3ec88925.jpg', 'admin254', 'admin254', NULL, '$2y$10$NPl2oVsmJPGyrIGTARaRQurELPaFyECHU5dx7/MmqVU8dfUjqqsLm', 1621617968, 1621312254);
INSERT INTO `sys_admin` VALUES (13, 0, 0, NULL, 'admin2', 'admin2', NULL, '$2y$10$S6MqE8L7evXN.agc5i2Tb.WMAI4.t4SlRdTQS2Oukr8DvLIw3Kzi.', 1621390490, 1621390490);
INSERT INTO `sys_admin` VALUES (14, 0, 1, 'http://tp6-admin-template.local/storage/upload/img/20210522/db1a6afe57f3f6f090bb5a3b80c0478c.jpg', 'sss', 'sss', NULL, '$2y$10$KCU1hfj2GyFfp9go5/QYKedjUBFKfQu3RBvkS1eePeMoSC0Pf8sNu', 1621616974, 1621616823);
INSERT INTO `sys_admin` VALUES (15, 0, 0, 'http://tp6-admin-template.local/storage/upload/img/20210522/540b6a3cab4b3bbf69ba3ad22e03c770.jpg', 'gggg', 'gggg', NULL, '$2y$10$ON0QL/pYpuzGZ6XnlP6NFuEtl8Eh/vSM3r61CTtFYxn25RzARavMC', 1621618003, 1621618003);
INSERT INTO `sys_admin` VALUES (17, 0, 0, 'http://tp6-admin-template.local/storage/upload/img/20210524/222bab5d1978a01bbf51afbb8750b789.png', 'admin12312', 'admin12312', NULL, '$2y$10$4952OStHKfDtIJSyw.OEdugCw69BECcUdp6En64Yt9WmDmndagWfG', 1621819903, 1621670325);

-- ----------------------------
-- Table structure for sys_admin_role
-- ----------------------------
DROP TABLE IF EXISTS `sys_admin_role`;
CREATE TABLE `sys_admin_role`  (
  `admin_id` bigint(20) NOT NULL COMMENT '管理员ID',
  `role_id` bigint(20) NOT NULL COMMENT '角色ID',
  PRIMARY KEY (`admin_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_bin COMMENT = '管理员和角色关联表' ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of sys_admin_role
-- ----------------------------
INSERT INTO `sys_admin_role` VALUES (1, 1);
INSERT INTO `sys_admin_role` VALUES (3, 4);
INSERT INTO `sys_admin_role` VALUES (4, 4);
INSERT INTO `sys_admin_role` VALUES (5, 4);
INSERT INTO `sys_admin_role` VALUES (6, 4);
INSERT INTO `sys_admin_role` VALUES (8, 4);
INSERT INTO `sys_admin_role` VALUES (11, 1111);
INSERT INTO `sys_admin_role` VALUES (13, 4);
INSERT INTO `sys_admin_role` VALUES (14, 26);
INSERT INTO `sys_admin_role` VALUES (15, 4);
INSERT INTO `sys_admin_role` VALUES (16, 26);
INSERT INTO `sys_admin_role` VALUES (17, 26);

-- ----------------------------
-- Table structure for sys_menu
-- ----------------------------
DROP TABLE IF EXISTS `sys_menu`;
CREATE TABLE `sys_menu`  (
  `menu_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `parent_id` bigint(20) NULL DEFAULT NULL COMMENT '父级ID',
  `title` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '菜单名称',
  `level` int(11) NULL DEFAULT 0 COMMENT '菜单级数,0表示根目录，如果这个根目录没有一个子集，当前系统前端不支持',
  `sort` int(11) NULL DEFAULT 100 COMMENT '菜单排序',
  `mark_name` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '前端唯一标识名称',
  `icon` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '前端图标',
  `hidden` int(11) NULL DEFAULT 0 COMMENT '前端隐藏',
  `create_time` int(11) NULL DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`menu_id`) USING BTREE,
  UNIQUE INDEX `mark_name_index`(`mark_name`) USING BTREE COMMENT '菜单唯一标识'
) ENGINE = InnoDB AUTO_INCREMENT = 40 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '后台菜单表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of sys_menu
-- ----------------------------
INSERT INTO `sys_menu` VALUES (1, 0, '系统管理', 0, 11, 'setting', NULL, 0, 1621405092);
INSERT INTO `sys_menu` VALUES (27, 1, '平台设置', 1, 999, 'platform-info', NULL, 0, 1621405092);
INSERT INTO `sys_menu` VALUES (29, 0, '菜单管理', 1, 1, 'menu', NULL, 0, 1621405092);
INSERT INTO `sys_menu` VALUES (30, 0, '管理员管理', 0, 0, 'admin', NULL, 0, 1621405092);
INSERT INTO `sys_menu` VALUES (31, 30, '角色列表', 1, 1, 'role-list', NULL, 0, 1621405092);
INSERT INTO `sys_menu` VALUES (32, 30, '管理员列表', 1, 0, 'admin-list', NULL, 0, 1621405092);
INSERT INTO `sys_menu` VALUES (33, 29, '菜单列表', 1, 0, 'menu-list', NULL, 0, 1621405092);
INSERT INTO `sys_menu` VALUES (38, 1, '队列列表', 1, 100, 'queue-list', NULL, 0, 1622097420);
INSERT INTO `sys_menu` VALUES (39, 1, '短信列表', 1, 100, 'sms-list', NULL, 0, 1622097499);

-- ----------------------------
-- Table structure for sys_role
-- ----------------------------
DROP TABLE IF EXISTS `sys_role`;
CREATE TABLE `sys_role`  (
  `role_id` int(11) NOT NULL AUTO_INCREMENT,
  `is_super_role` int(11) NULL DEFAULT 0 COMMENT '是否是超级管理员角色',
  `role_name` varchar(45) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '角色名称',
  `sort` int(11) NULL DEFAULT 100 COMMENT '排序',
  `status` int(11) NOT NULL DEFAULT 0 COMMENT '状态。0启用，1禁用',
  `create_by` int(11) NULL DEFAULT NULL COMMENT '创建者',
  `create_time` int(11) NULL DEFAULT NULL COMMENT '创建时间',
  `update_by` int(11) NULL DEFAULT NULL COMMENT '修改者',
  `update_time` int(11) NULL DEFAULT NULL COMMENT '修改时间',
  `remark` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '备注',
  PRIMARY KEY (`role_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 29 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '平台角色表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of sys_role
-- ----------------------------
INSERT INTO `sys_role` VALUES (1, 1, '超级管理员', 100, 0, 1, 1621239214, 1, 1621239214, NULL);
INSERT INTO `sys_role` VALUES (4, 0, '游客', 4, 0, 1, 1621239214, 1, 1621695045, NULL);
INSERT INTO `sys_role` VALUES (26, 0, '小角色', 1, 0, 1, 1621404556, 1, 1621741427, '我是小备注');

-- ----------------------------
-- Table structure for sys_role_menu
-- ----------------------------
DROP TABLE IF EXISTS `sys_role_menu`;
CREATE TABLE `sys_role_menu`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'id',
  `role_id` int(10) UNSIGNED NOT NULL COMMENT '角色ID',
  `menu_id` int(11) NOT NULL COMMENT '菜单id',
  `create_time` int(11) NULL DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 781 CHARACTER SET = utf8 COLLATE = utf8_bin COMMENT = '角色和菜单关联表' ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of sys_role_menu
-- ----------------------------
INSERT INTO `sys_role_menu` VALUES (66, 27, 1, 1621405092);
INSERT INTO `sys_role_menu` VALUES (67, 27, 27, 1621405092);
INSERT INTO `sys_role_menu` VALUES (68, 27, 28, 1621405092);
INSERT INTO `sys_role_menu` VALUES (761, 28, 30, 1621694367);
INSERT INTO `sys_role_menu` VALUES (762, 28, 32, 1621694367);
INSERT INTO `sys_role_menu` VALUES (763, 28, 31, 1621694367);
INSERT INTO `sys_role_menu` VALUES (764, 28, 27, 1621694367);
INSERT INTO `sys_role_menu` VALUES (771, 4, 1, 1621695045);
INSERT INTO `sys_role_menu` VALUES (772, 4, 28, 1621695045);
INSERT INTO `sys_role_menu` VALUES (773, 4, 27, 1621695045);
INSERT INTO `sys_role_menu` VALUES (774, 4, 29, 1621695045);
INSERT INTO `sys_role_menu` VALUES (775, 4, 33, 1621695045);
INSERT INTO `sys_role_menu` VALUES (778, 26, 28, 1621741427);
INSERT INTO `sys_role_menu` VALUES (779, 26, 32, 1621741427);
INSERT INTO `sys_role_menu` VALUES (780, 26, 30, 1621741427);

-- ----------------------------
-- Table structure for sys_role_permission
-- ----------------------------
DROP TABLE IF EXISTS `sys_role_permission`;
CREATE TABLE `sys_role_permission`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `role_id` int(10) UNSIGNED NOT NULL COMMENT '角色ID',
  `permission_name` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '权限名称',
  `route` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '控制器访问通道',
  `rule` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT 'url请求路由',
  `create_time` int(11) NULL DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 518 CHARACTER SET = utf8 COLLATE = utf8_bin COMMENT = '角色和权限关联表' ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of sys_role_permission
-- ----------------------------
INSERT INTO `sys_role_permission` VALUES (7, 27, '管理员列表', 'Admin/list', 'admin/list', 1621405092);
INSERT INTO `sys_role_permission` VALUES (8, 27, '管理员创建', 'Admin/create', 'admin/create', 1621405092);
INSERT INTO `sys_role_permission` VALUES (493, 28, '管理员创建', 'Admin/create', 'admin/create', 1621694367);
INSERT INTO `sys_role_permission` VALUES (494, 28, '管理员编辑', 'Admin/edit', 'admin/edit', 1621694367);
INSERT INTO `sys_role_permission` VALUES (495, 28, '管理员删除', 'Admin/delete', 'admin/delete', 1621694367);
INSERT INTO `sys_role_permission` VALUES (496, 28, '管理员禁用', 'Admin/disable', 'admin/disable', 1621694367);
INSERT INTO `sys_role_permission` VALUES (497, 28, '管理员密码修改', 'Admin/upPassword', 'admin/up_password', 1621694367);
INSERT INTO `sys_role_permission` VALUES (498, 28, '管理员信息', 'Admin/info', 'admin/info', 1621694367);
INSERT INTO `sys_role_permission` VALUES (499, 28, '管理员列表', 'Admin/list', 'admin/list', 1621694367);
INSERT INTO `sys_role_permission` VALUES (500, 28, '角色创建', 'Role/create', 'role/create', 1621694367);
INSERT INTO `sys_role_permission` VALUES (504, 4, '管理员创建', 'Admin/create', 'admin/create', 1621695045);
INSERT INTO `sys_role_permission` VALUES (505, 4, '管理员编辑', 'Admin/edit', 'admin/edit', 1621695045);
INSERT INTO `sys_role_permission` VALUES (506, 4, '角色创建', 'Role/create', 'role/create', 1621695045);
INSERT INTO `sys_role_permission` VALUES (507, 4, '角色信息', 'Role/info', 'role/info', 1621695045);
INSERT INTO `sys_role_permission` VALUES (508, 4, '角色删除', 'Role/delete', 'role/delete', 1621695045);
INSERT INTO `sys_role_permission` VALUES (509, 4, '角色禁用', 'Role/disable', 'role/disable', 1621695045);
INSERT INTO `sys_role_permission` VALUES (510, 4, '上传图片', 'Upload/image', 'upload/image', 1621695045);
INSERT INTO `sys_role_permission` VALUES (511, 4, '上传文件', 'Upload/file', 'upload/file', 1621695045);
INSERT INTO `sys_role_permission` VALUES (515, 26, '上传图片', 'Upload/image', 'upload/image', 1621741427);
INSERT INTO `sys_role_permission` VALUES (516, 26, '管理员编辑', 'Admin/edit', 'admin/edit', 1621741427);
INSERT INTO `sys_role_permission` VALUES (517, 26, '上传文件', 'Upload/file', 'upload/file', 1621741427);

-- ----------------------------
-- Table structure for sys_task
-- ----------------------------
DROP TABLE IF EXISTS `sys_task`;
CREATE TABLE `sys_task`  (
  `task_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '任务id',
  `unique_code` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT '队列唯一码',
  `queue_name` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT '队列通道名称',
  `task_name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT '任务名称',
  `request_data` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL COMMENT '请求数据json',
  `retry_num` int(11) UNSIGNED NULL DEFAULT 0 COMMENT '重试次数',
  `result` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT '任务结果;waiting,retrying,success,failed',
  `create_time` int(11) NULL DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) NULL DEFAULT NULL COMMENT '最后一次执行时间',
  PRIMARY KEY (`task_id`) USING BTREE,
  UNIQUE INDEX `idx_unique_code`(`unique_code`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 375 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci COMMENT = '队列任务表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of sys_task
-- ----------------------------
INSERT INTO `sys_task` VALUES (370, 'ff188f7ecd8c02af4c0998f0d4b89eb8', 'default_queue', 'app\\common\\task\\SendSmsTask', '{\"sms_log_id\":21,\"mobile\":17621860940,\"template\":\"test_sms\",\"data\":{\"name\":\"\\u5c0f\\u660e\",\"result\":\"\\u5c0f\\u660e\\u8d70\\u961f\\u5217\"},\"mode\":\"diy\"}', 0, 'failed', 1622095674, 1622095864);
INSERT INTO `sys_task` VALUES (371, 'f06c0198ad5baf166d2695c904dddc12', 'default_queue', 'app\\common\\task\\SendSmsTask', '{\"sms_log_id\":22,\"mobile\":17621860940,\"template\":\"test_sms\",\"data\":{\"name\":\"\\u5c0f\\u660e\",\"result\":\"\\u5c0f\\u660e\\u8d70\\u961f\\u5217\"},\"mode\":\"diy\"}', 0, 'failed', 1622095883, 1622095886);
INSERT INTO `sys_task` VALUES (372, '84ec59b6d5b5c89c540f3a6419d8f334', 'default_queue', 'app\\common\\task\\SendSmsTask', '{\"sms_log_id\":23,\"mobile\":15526222933,\"template\":\"test_sms\",\"data\":{\"name\":\"\\u5c0f\\u660e\",\"result\":\"\\u5c0f\\u660e\\u8d70\\u961f\\u5217\"},\"mode\":\"diy\"}', 0, 'failed', 1622095963, 1622095964);
INSERT INTO `sys_task` VALUES (373, '71fdf9975b1acb2b7c73f11ba8b085a1', 'default_queue', 'app\\common\\task\\SendSmsTask', '{\"sms_log_id\":24,\"mobile\":15526222933,\"template\":\"test_sms\",\"data\":{\"name\":\"\\u5c0f\\u660e\",\"result\":\"\\u5c0f\\u660e\\u8d70\\u961f\\u5217\"},\"mode\":\"diy\"}', 0, 'failed', 1622096020, 1622096024);
INSERT INTO `sys_task` VALUES (374, 'b04780e38dcf9d72eca3bd3c1a447f15', 'default_queue', 'app\\common\\task\\SendSmsTask', '{\"sms_log_id\":25,\"mobile\":15526222933,\"template\":\"test_sms\",\"data\":{\"name\":\"\\u5c0f\\u660e\",\"result\":\"\\u5c0f\\u660e\\u8d70\\u961f\\u5217\"},\"mode\":\"diy\"}', 0, 'failed', 1622096075, 1622096078);

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user`  (
  `user_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `level_id` bigint(20) NOT NULL DEFAULT 0 COMMENT '等级',
  `username` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '用户名',
  `password` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '密码',
  `nickname` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '昵称',
  `mobile` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '手机号码',
  `status` int(11) NOT NULL DEFAULT 1 COMMENT '帐号启用状态:0->禁用；1->启用',
  `avatar` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '头像',
  `gender` int(11) NOT NULL DEFAULT 0 COMMENT '性别：0->未知；1->男；2->女',
  `birthday` datetime(0) NULL DEFAULT NULL COMMENT '生日',
  `source_type` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '用户来源',
  `integration` int(11) NULL DEFAULT 0 COMMENT '积分',
  `create_time` int(11) NULL DEFAULT NULL COMMENT '注册时间',
  PRIMARY KEY (`user_id`) USING BTREE,
  UNIQUE INDEX `username_index`(`username`) USING BTREE COMMENT '用户名索引'
) ENGINE = InnoDB AUTO_INCREMENT = 11 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '会员表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES (10, 0, 'lpc', '$2y$10$wdFOW1Kg4.znsI..Os6jgu.JVJeXaAS6XLzFu0bh2qf/cvPBWeXM6', '昵称是个der', '13911111111', 1, NULL, 0, '2021-05-05 12:48:25', 'app', 100, 1621239214);

SET FOREIGN_KEY_CHECKS = 1;
