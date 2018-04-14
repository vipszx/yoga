/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 50553
 Source Host           : localhost:3306
 Source Schema         : leacmf

 Target Server Type    : MySQL
 Target Server Version : 50553
 File Encoding         : 65001

 Date: 14/04/2018 16:53:47
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for admin
-- ----------------------------
DROP TABLE IF EXISTS `admin`;
CREATE TABLE `admin`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `username` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '用户名',
  `nickname` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '昵称',
  `password` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '密码',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '0-禁用 1-正常',
  `create_time` int(11) NOT NULL COMMENT '注册时间',
  `delete_time` int(11) NULL DEFAULT NULL COMMENT '删除时间',
  `remember_token` char(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '后台管理员表' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of admin
-- ----------------------------
INSERT INTO `admin` VALUES (1, 'admin', 'admin', '21232f297a57a5a743894a0e4a801fc3', 1, 1, NULL, '15aa0edc34bd1292371adc8212f89288');

-- ----------------------------
-- Table structure for auth_group
-- ----------------------------
DROP TABLE IF EXISTS `auth_group`;
CREATE TABLE `auth_group`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `title` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '组名',
  `rules` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '规则ID',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '状态',
  `sort` smallint(5) NOT NULL DEFAULT 0,
  `remark` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '分组表' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of auth_group
-- ----------------------------
INSERT INTO `auth_group` VALUES (1, 'admin', '超级管理员', '*', 1, 0, '');

-- ----------------------------
-- Table structure for auth_group_access
-- ----------------------------
DROP TABLE IF EXISTS `auth_group_access`;
CREATE TABLE `auth_group_access`  (
  `uid` int(10) UNSIGNED NOT NULL COMMENT '会员ID',
  `group_id` int(10) UNSIGNED NOT NULL COMMENT '组ID',
  UNIQUE INDEX `uid_group_id`(`uid`, `group_id`) USING BTREE,
  INDEX `uid`(`uid`) USING BTREE,
  INDEX `group_id`(`group_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '权限分组表' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of auth_group_access
-- ----------------------------
INSERT INTO `auth_group_access` VALUES (1, 1);

-- ----------------------------
-- Table structure for auth_rule
-- ----------------------------
DROP TABLE IF EXISTS `auth_rule`;
CREATE TABLE `auth_rule`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `pid` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '规则名称',
  `title` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '规则标题',
  `icon` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '图标',
  `condition` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '条件',
  `remark` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '备注',
  `is_menu` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否为菜单',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `sort` int(10) NOT NULL COMMENT '权重',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '状态',
  `type` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `name`(`name`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 22 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '节点表' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of auth_rule
-- ----------------------------
INSERT INTO `auth_rule` VALUES (1, 0, '/admin/index/index', '控制台', 'fa-dashboard', '', '', 0, 0, 0, 0, 1, 0);
INSERT INTO `auth_rule` VALUES (2, 0, '', '权限管理', 'fa-sitemap', '', '', 1, 0, 0, 0, 1, 0);
INSERT INTO `auth_rule` VALUES (4, 2, '/admin/auth.rule/index', '规则&菜单', '', '', '查看全部规则', 0, 0, 0, 0, 1, 0);
INSERT INTO `auth_rule` VALUES (5, 4, '/admin/auth.rule/create', '创建规则', '', '', '', 0, 0, 0, 0, 1, 0);
INSERT INTO `auth_rule` VALUES (6, 4, '/admin/auth.rule/save', '保存规则', '', '', '', 0, 0, 0, 0, 1, 0);
INSERT INTO `auth_rule` VALUES (7, 4, '/admin/auth.rule/edit', '编辑规则', '', '', '', 0, 0, 0, 0, 1, 0);
INSERT INTO `auth_rule` VALUES (8, 4, '/admin/auth.rule/update', '修改规则', '', '', '', 0, 0, 0, 0, 1, 0);
INSERT INTO `auth_rule` VALUES (9, 4, '/admin/auth.rule/delete', '删除规则', '', '', '', 0, 0, 0, 0, 1, 0);
INSERT INTO `auth_rule` VALUES (10, 2, '/admin/auth.admin/index', '用户管理', '', '', '', 0, 0, 0, 0, 1, 0);
INSERT INTO `auth_rule` VALUES (11, 10, '/admin/auth.admin/create', '创建用户', '', '', '', 0, 0, 0, 0, 1, 0);
INSERT INTO `auth_rule` VALUES (12, 10, '/admin/auth.admin/save', '保存用户', '', '', '', 0, 0, 0, 0, 1, 0);
INSERT INTO `auth_rule` VALUES (13, 10, '/admin/auth.admin/edit', '编辑用户', '', '', '', 0, 0, 0, 0, 1, 0);
INSERT INTO `auth_rule` VALUES (14, 10, '/admin/auth.admin/update', '更新用户', '', '', '', 0, 0, 0, 0, 1, 0);
INSERT INTO `auth_rule` VALUES (15, 10, '/admin/auth.admin/delete', '删除用户', '', '', '', 0, 0, 0, 0, 1, 0);
INSERT INTO `auth_rule` VALUES (16, 2, '/admin/auth.group/index', '用户组管理', '', '', '', 0, 0, 0, 0, 1, 0);
INSERT INTO `auth_rule` VALUES (17, 16, '/admin/auth.group/create', '创建用户组', '', '', '', 0, 0, 0, 0, 1, 0);
INSERT INTO `auth_rule` VALUES (18, 16, '/admin/auth.group/save', '保存用户组', '', '', '', 0, 0, 0, 0, 1, 0);
INSERT INTO `auth_rule` VALUES (19, 16, '/admin/auth.group/edit', '编辑用户组', '', '', '', 0, 0, 0, 0, 1, 0);
INSERT INTO `auth_rule` VALUES (20, 16, '/admin/auth.group/update', '更新用户组', '', '', '', 0, 0, 0, 0, 1, 0);
INSERT INTO `auth_rule` VALUES (21, 16, '/admin/auth.group/delete', '删除用户组', '', '', '', 0, 0, 0, 0, 1, 0);

SET FOREIGN_KEY_CHECKS = 1;
