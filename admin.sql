-- MySQL dump 10.13  Distrib 5.6.35, for Win64 (x86_64)
--
-- Host: localhost    Database: phpfetcher
-- ------------------------------------------------------
-- Server version	5.6.35

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `fetch_task`
--

DROP TABLE IF EXISTS `fetch_task`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fetch_task` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) DEFAULT NULL COMMENT '标题',
  `host` varchar(30) DEFAULT NULL COMMENT '主机',
  `db` varchar(30) DEFAULT NULL COMMENT '数据库',
  `user` varchar(30) DEFAULT NULL COMMENT '用户',
  `pwd` varchar(30) DEFAULT NULL COMMENT '密码',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='fetch任务';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fetch_task`
--

LOCK TABLES `fetch_task` WRITE;
/*!40000 ALTER TABLE `fetch_task` DISABLE KEYS */;
/*!40000 ALTER TABLE `fetch_task` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fetch_task_item`
--

DROP TABLE IF EXISTS `fetch_task_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fetch_task_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `task_id` int(11) DEFAULT NULL COMMENT '任务ID',
  `parent_id` int(11) DEFAULT '0' COMMENT '任务父ID',
  `title` varchar(100) DEFAULT NULL COMMENT '标题',
  `tb` varchar(100) DEFAULT '' COMMENT '表名',
  `tb_create` text COMMENT '建表SQL',
  `field` text COMMENT '字段',
  `field_global_var` text COMMENT '全局变量',
  `field_attribute_label` text COMMENT '字段标签',
  `field_attribute_labels_config` text,
  `field_rules` text COMMENT '字段规则',
  `field_search` text COMMENT ' 字段搜索配置',
  `field_search_box` text COMMENT '字段搜索框',
  `field_table` text COMMENT '显示字段列',
  `field_edit` text COMMENT '字段编辑样式',
  `field_data` text COMMENT '字段数据匹配设置',
  PRIMARY KEY (`id`),
  KEY `task_id` (`task_id`),
  KEY `parent_id` (`parent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='fetch任务项目';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fetch_task_item`
--

LOCK TABLES `fetch_task_item` WRITE;
/*!40000 ALTER TABLE `fetch_task_item` DISABLE KEYS */;
/*!40000 ALTER TABLE `fetch_task_item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `log_admin`
--

DROP TABLE IF EXISTS `log_admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `log_admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `log_type` char(50) DEFAULT '' COMMENT '类型',
  `log_time` int(11) DEFAULT '0' COMMENT '日志时间',
  `member_id` int(11) DEFAULT '0' COMMENT '用户ID',
  `title` varchar(1000) DEFAULT '' COMMENT '标题',
  `content` text COMMENT '内容',
  `url` varchar(1000) DEFAULT NULL COMMENT 'URL入口',
  `url_referer` varchar(1000) DEFAULT NULL COMMENT 'URL来路',
  PRIMARY KEY (`id`),
  KEY `idx_log_type` (`log_type`) USING BTREE,
  KEY `idx_user_id` (`member_id`) USING BTREE,
  KEY `idx_log_time` (`log_time`) USING HASH
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `log_admin`
--

LOCK TABLES `log_admin` WRITE;
/*!40000 ALTER TABLE `log_admin` DISABLE KEYS */;
/*!40000 ALTER TABLE `log_admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `log_sys`
--

DROP TABLE IF EXISTS `log_sys`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `log_sys` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `log_type` char(50) DEFAULT '' COMMENT '类型',
  `log_time` int(11) DEFAULT '0' COMMENT '日志时间',
  `title` varchar(100) DEFAULT '' COMMENT '标题',
  `content` varchar(1000) DEFAULT '' COMMENT '内容',
  PRIMARY KEY (`id`),
  KEY `idx_log_type` (`log_type`) USING BTREE,
  KEY `idx_log_time` (`log_time`) USING HASH
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `log_sys`
--

LOCK TABLES `log_sys` WRITE;
/*!40000 ALTER TABLE `log_sys` DISABLE KEYS */;
/*!40000 ALTER TABLE `log_sys` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sys_user`
--

DROP TABLE IF EXISTS `sys_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sys_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` varchar(50) DEFAULT '' COMMENT '管理员',
  `pwd` char(32) DEFAULT '' COMMENT '密码',
  `group_id` int(11) NOT NULL DEFAULT '0' COMMENT '权限组ID',
  `enable` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否启用，1：true,0:false',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uidx_name` (`name`),
  KEY `idx_group_id` (`group_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sys_user`
--

LOCK TABLES `sys_user` WRITE;
/*!40000 ALTER TABLE `sys_user` DISABLE KEYS */;
INSERT INTO `sys_user` VALUES (1,'root','202cb962ac59075b964b07152d234b70',1,1);
/*!40000 ALTER TABLE `sys_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sys_user_authority`
--

DROP TABLE IF EXISTS `sys_user_authority`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sys_user_authority` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `group_id` int(11) DEFAULT '0' COMMENT '权限组ID',
  `sys_menu_id` int(11) DEFAULT '0',
  `enable` int(1) DEFAULT '1' COMMENT '是否启用，1：true,0:false',
  PRIMARY KEY (`id`),
  KEY `idx_group_id` (`group_id`) USING BTREE,
  KEY `idx_sys_menu_id` (`sys_menu_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sys_user_authority`
--

LOCK TABLES `sys_user_authority` WRITE;
/*!40000 ALTER TABLE `sys_user_authority` DISABLE KEYS */;
INSERT INTO `sys_user_authority` VALUES (1,1,1,1),(2,1,2,1),(3,1,3,1),(4,1,4,1),(5,1,5,1),(6,1,6,1),(7,1,7,1),(8,1,8,1),(9,1,9,1),(10,1,10,1),(11,1,11,1),(12,1,12,1),(13,1,13,1),(14,1,14,1),(15,1,15,1),(16,1,16,1),(17,1,17,1),(18,1,18,1),(19,1,19,1),(20,1,20,1),(21,1,21,1),(22,1,22,1),(23,1,23,1),(24,1,24,1),(25,1,25,1),(26,1,26,1),(27,1,27,1),(28,1,28,1),(29,1,29,1),(30,1,30,1),(31,1,31,1),(32,1,32,1),(33,1,33,1),(34,1,34,1),(35,1,35,1),(36,1,36,1),(37,1,37,1),(38,1,38,1),(39,1,39,1),(40,1,40,1),(41,1,41,1),(42,1,42,1),(43,1,43,1),(44,1,44,1),(45,1,45,1),(46,1,46,1),(47,1,47,1),(48,1,48,1),(49,1,49,1),(50,1,50,1);
/*!40000 ALTER TABLE `sys_user_authority` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sys_user_group`
--

DROP TABLE IF EXISTS `sys_user_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sys_user_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `group_name` varchar(100) DEFAULT '' COMMENT '组别名称',
  `enable` tinyint(1) DEFAULT '1' COMMENT '是否启用，1：true,0:false',
  `descr` varchar(300) DEFAULT '' COMMENT '描述',
  PRIMARY KEY (`id`),
  UNIQUE KEY `group_name` (`group_name`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sys_user_group`
--

LOCK TABLES `sys_user_group` WRITE;
/*!40000 ALTER TABLE `sys_user_group` DISABLE KEYS */;
INSERT INTO `sys_user_group` VALUES (1,'超级管理员',1,'');
/*!40000 ALTER TABLE `sys_user_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sys_user_menu`
--

DROP TABLE IF EXISTS `sys_user_menu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sys_user_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` varchar(30) DEFAULT NULL COMMENT '菜单名',
  `parent_id` int(11) DEFAULT '0' COMMENT '父ID，为0是顶级菜单',
  `url` varchar(300) DEFAULT '' COMMENT '访问地址',
  `enable` tinyint(1) DEFAULT '1' COMMENT '是否启用，1：true,0:false',
  `sort` int(5) DEFAULT '1' COMMENT '排序',
  `descr` varchar(300) DEFAULT '' COMMENT '描述',
  PRIMARY KEY (`id`),
  KEY `idx_parent_id` (`parent_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sys_user_menu`
--

LOCK TABLES `sys_user_menu` WRITE;
/*!40000 ALTER TABLE `sys_user_menu` DISABLE KEYS */;
INSERT INTO `sys_user_menu` VALUES (1,'系统管理',0,'系统管理',1,1,''),(2,'日志',0,'日志',1,1,''),(3,'上传',0,'上传',2,1,''),(4,'个人设置',0,'个人设置',1,1,''),(5,'权限管理',1,'sys-user-menu/index',1,1,''),(6,'查看',5,'sys-user-menu/view',1,1,''),(7,'获取单个权限信息',5,'sys-user-menu/one',1,1,''),(8,'添加',5,'sys-user-menu/create',1,1,''),(9,'更新',5,'sys-user-menu/update',1,1,''),(10,'删除',5,'sys-user-menu/delete',1,1,''),(11,'缓存管理',1,'cache/index',1,NULL,''),(12,'清除缓存',11,'cache/clean-cache',1,NULL,''),(13,'管理员组',1,'sys-user-group/index',1,NULL,''),(14,'查看',13,'sys-user-group/view',1,NULL,''),(15,'添加',13,'sys-user-group/create',1,NULL,''),(16,'更新',13,'sys-user-group/update',1,NULL,''),(17,'删除',13,'sys-user-group/delete',1,NULL,''),(18,'权限查看',13,'sys-user-group/authority',1,NULL,''),(19,'权限修改',13,'sys-user-group/authority-modify',1,NULL,''),(20,'管理员',1,'sys-user/index',1,NULL,''),(21,'查看',20,'sys-user/view',1,NULL,''),(22,'添加',20,'sys-user/create',1,NULL,''),(23,'更新',20,'sys-user/update',1,NULL,''),(24,'删除',20,'sys-user/delete',1,NULL,''),(25,'管理日志',2,'log-admin/index',1,NULL,''),(26,'查看',25,'log-admin/view',1,NULL,''),(27,'系统日志',2,'log-sys/index',1,NULL,''),(28,'查看',27,'log-sys/view',1,NULL,''),(29,'上传文件',3,'upload/index',1,NULL,''),(30,'编译器上传',3,'upload/ueditor-img',1,NULL,''),(31,'修改资料',4,'sys-user-config/modify-info',1,NULL,''),(32,'爬虫',0,'爬虫',1,NULL,''),(33,'爬虫任务',32,'fetch-task/index',1,NULL,''),(34,'查看',33,'fetch-task/view',1,NULL,''),(35,'添加',33,'fetch-task/create',1,NULL,''),(36,'更新',33,'fetch-task/update',1,NULL,''),(37,'删除',33,'fetch-task/delete',1,NULL,''),(38,'爬虫任务项',32,'fetch-task-item/index',1,NULL,''),(39,'查看',38,'fetch-task-item/view',1,NULL,''),(40,'添加',38,'fetch-task-item/create',1,NULL,''),(41,'更新',38,'fetch-task-item/update',1,NULL,''),(42,'删除',38,'fetch-task-item/delete',1,NULL,''),(43,'爬虫数据模型',32,'fetch-auto-model/index',2,NULL,''),(44,'查看',43,'fetch-auto-model/view',1,NULL,''),(45,'添加',43,'fetch-auto-model/create',1,NULL,''),(46,'更新',43,'fetch-auto-model/update',1,NULL,''),(47,'删除',43,'fetch-auto-model/delete',1,NULL,''),(48,'安装库',33,'fetch-task/install-base',1,NULL,''),(49,'搜索',43,'fetch-auto-model/search-result',1,NULL,''),(50,'配置表',33,'fetch-task/config-table',1,NULL,'');
/*!40000 ALTER TABLE `sys_user_menu` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-02-10 15:58:52
