-- MySQL dump 10.13  Distrib 8.0.24, for Linux (x86_64)
--
-- Host: localhost    Database: wrzs
-- ------------------------------------------------------
-- Server version	8.0.24

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `kg_ad`
--

DROP TABLE IF EXISTS `kg_ad`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kg_ad` (
  `ad_id` int NOT NULL AUTO_INCREMENT,
  `ad_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `updated_at` int DEFAULT NULL,
  `src` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `created_at` int DEFAULT NULL,
  `deleted_at` int DEFAULT '0',
  PRIMARY KEY (`ad_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kg_ad`
--

LOCK TABLES `kg_ad` WRITE;
/*!40000 ALTER TABLE `kg_ad` DISABLE KEYS */;
INSERT INTO `kg_ad` VALUES (2,NULL,1689135116,'/storage/file/20230712/4e4dba5aa4cb087f4774a32b44526b96.png',0,'<p><img class=\"wscnph\" src=\"https://was.weishequ.com/storage/file/20230712/58ac1df89fc25964c2114f7934ac3ba3.png\" /></p>',1689134089,1689135140);
/*!40000 ALTER TABLE `kg_ad` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kg_admin_user`
--

DROP TABLE IF EXISTS `kg_admin_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kg_admin_user` (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `user_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `status` tinyint DEFAULT '0',
  `account` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `created_at` int DEFAULT NULL,
  `deleted_at` int DEFAULT '0',
  `pid` int DEFAULT NULL,
  PRIMARY KEY (`user_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kg_admin_user`
--

LOCK TABLES `kg_admin_user` WRITE;
/*!40000 ALTER TABLE `kg_admin_user` DISABLE KEYS */;
INSERT INTO `kg_admin_user` VALUES (2,'admin',1,'admin','$2y$12$48dtCxHOObkAgVdyqN0/HubcpddwRMR4EnUEFKoIlBH7sakhcCtuC',NULL,0,NULL);
/*!40000 ALTER TABLE `kg_admin_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kg_cabinet`
--

DROP TABLE IF EXISTS `kg_cabinet`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kg_cabinet` (
  `cabinet_id` bigint NOT NULL AUTO_INCREMENT,
  `cabinet_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `room_id` int DEFAULT '0',
  `store_id` int DEFAULT NULL,
  `updated_at` int DEFAULT NULL,
  `created_at` int DEFAULT NULL,
  `deleted_at` int DEFAULT '0',
  `device_sn` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `join_id` int DEFAULT NULL,
  `on_line` int DEFAULT '0',
  PRIMARY KEY (`cabinet_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kg_cabinet`
--

LOCK TABLES `kg_cabinet` WRITE;
/*!40000 ALTER TABLE `kg_cabinet` DISABLE KEYS */;
/*!40000 ALTER TABLE `kg_cabinet` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kg_cabinet_goods`
--

DROP TABLE IF EXISTS `kg_cabinet_goods`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kg_cabinet_goods` (
  `goods_id` int NOT NULL AUTO_INCREMENT,
  `goods_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `goods_price` decimal(10,2) DEFAULT '0.00',
  `goods_about` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `cabinet_id` int DEFAULT NULL,
  `created_at` int DEFAULT NULL,
  `updated_at` int DEFAULT NULL,
  `deleted_at` int DEFAULT '0',
  `stock` int DEFAULT '0',
  `lock_status` int DEFAULT '1',
  `cabinet_number` int DEFAULT NULL,
  `status` tinyint DEFAULT '1',
  `goods_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `label` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT '新品',
  PRIMARY KEY (`goods_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kg_cabinet_goods`
--

LOCK TABLES `kg_cabinet_goods` WRITE;
/*!40000 ALTER TABLE `kg_cabinet_goods` DISABLE KEYS */;
/*!40000 ALTER TABLE `kg_cabinet_goods` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kg_city`
--

DROP TABLE IF EXISTS `kg_city`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kg_city` (
  `city_id` int NOT NULL AUTO_INCREMENT,
  `city_name` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `longitude` double(12,6) DEFAULT NULL,
  `latitude` double(12,6) DEFAULT NULL,
  `is_default` tinyint DEFAULT '0',
  `deleted_at` int DEFAULT '0',
  `sort` int DEFAULT '0',
  `created_at` int DEFAULT NULL,
  `updated_at` int DEFAULT NULL,
  PRIMARY KEY (`city_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kg_city`
--

LOCK TABLES `kg_city` WRITE;
/*!40000 ALTER TABLE `kg_city` DISABLE KEYS */;
INSERT INTO `kg_city` VALUES (9,'贵阳市',106.689205,26.592721,0,0,0,1686896966,NULL),(10,'北京市',116.311034,39.992752,0,0,0,1689138602,NULL);
/*!40000 ALTER TABLE `kg_city` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kg_config_miniprogram_menu`
--

DROP TABLE IF EXISTS `kg_config_miniprogram_menu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kg_config_miniprogram_menu` (
  `id` int NOT NULL AUTO_INCREMENT,
  `menu` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT '[\n"首页",\n"管理",\n"我的"\n]',
  `wxapp_id` int DEFAULT NULL,
  `created_at` int DEFAULT NULL,
  `updated_at` int DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kg_config_miniprogram_menu`
--

LOCK TABLES `kg_config_miniprogram_menu` WRITE;
/*!40000 ALTER TABLE `kg_config_miniprogram_menu` DISABLE KEYS */;
/*!40000 ALTER TABLE `kg_config_miniprogram_menu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kg_device`
--

DROP TABLE IF EXISTS `kg_device`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kg_device` (
  `device_id` bigint NOT NULL AUTO_INCREMENT,
  `device_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `device_sn` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `created_at` int DEFAULT NULL,
  `deleted_at` int DEFAULT '0',
  `updated_at` int DEFAULT NULL,
  `device_type` tinyint DEFAULT '1',
  `room_id` int DEFAULT NULL,
  `voice` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `volume` tinyint DEFAULT '1',
  `join_id` int DEFAULT NULL,
  `gw_sn` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `on_line` int DEFAULT '0',
  `store_id` int DEFAULT NULL,
  `qr_status` tinyint DEFAULT '0',
  `qr_time` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `qr` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`device_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kg_device`
--

LOCK TABLES `kg_device` WRITE;
/*!40000 ALTER TABLE `kg_device` DISABLE KEYS */;
INSERT INTO `kg_device` VALUES (40,'csd','123',NULL,1689167247,NULL,1,39,NULL,1,11,'123',0,18,0,NULL,'https://rrt-server.seekr.cc/qr/?device_id=40&state='),(41,' 演示云喇叭','W7022621597',NULL,1689092274,NULL,4,39,'您的订单,还有15分钟结束，请及时续费，以免断电影响使用，谢',5,11,NULL,0,18,0,NULL,'https://rrt-server.seekr.cc/qr/?device_id=41&state='),(42,'W89601EC6A7','W89601EC6A7',NULL,0,NULL,1,39,'W89601EC6A7',1,11,NULL,0,18,0,NULL,NULL),(43,'W89601EC6A7','W89601EC6A7',NULL,0,NULL,1,40,NULL,1,11,NULL,0,18,0,NULL,NULL),(44,'W71F9783520','W71F9783520',NULL,0,NULL,3,39,NULL,1,11,NULL,0,18,0,NULL,NULL),(45,'W7022621597','W7022621597',NULL,0,NULL,4,39,'W7022621597',1,11,NULL,0,18,0,NULL,NULL);
/*!40000 ALTER TABLE `kg_device` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kg_device_card`
--

DROP TABLE IF EXISTS `kg_device_card`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kg_device_card` (
  `card_id` int NOT NULL AUTO_INCREMENT,
  `card_sn` varchar(18) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `end_time` int DEFAULT '0',
  `device_id` int DEFAULT '0',
  `deleted_at` int DEFAULT '0',
  `updated_at` int DEFAULT NULL,
  `device_sn` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `created_at` int DEFAULT NULL,
  PRIMARY KEY (`card_id`) USING BTREE,
  KEY `idx_cardSn_deviceSn` (`card_sn`,`device_sn`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kg_device_card`
--

LOCK TABLES `kg_device_card` WRITE;
/*!40000 ALTER TABLE `kg_device_card` DISABLE KEYS */;
/*!40000 ALTER TABLE `kg_device_card` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kg_device_record`
--

DROP TABLE IF EXISTS `kg_device_record`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kg_device_record` (
  `record_id` bigint NOT NULL AUTO_INCREMENT,
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `device_sn` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `room_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `join_id` int DEFAULT NULL,
  `created_at` int DEFAULT NULL,
  `device_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`record_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1378 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kg_device_record`
--

LOCK TABLES `kg_device_record` WRITE;
/*!40000 ALTER TABLE `kg_device_record` DISABLE KEYS */;
INSERT INTO `kg_device_record` VALUES (1358,'管理端控制','控制门锁','W89601EC6A7','111',11,1689169204,'W89601EC6A7'),(1359,'管理端控制','控制门锁','W89601EC6A7','111',11,1689169416,'W89601EC6A7'),(1360,'管理端控制','控制门锁','W89601EC6A7','111',11,1689169481,'W89601EC6A7'),(1361,'微信用户419-用户开门','开门成功','W89601EC6A7','111',11,1689169936,'W89601EC6A7'),(1362,'微信用户419-用户开门','开门成功','W89601EC6A7','111',11,1689170251,'W89601EC6A7'),(1363,'微信用户415-用户开门','开门成功','W89601EC6A7','111',11,1689178819,'W89601EC6A7'),(1364,'微信用户415-用户开门','开门成功','W89601EC6A7','111',11,1689178875,'W89601EC6A7'),(1365,'微信用户415-用户开门','开门成功','W89601EC6A7','111',11,1689179109,'W89601EC6A7'),(1366,'微信用户415-用户开门','开门成功','W89601EC6A7','666',11,1689180053,'W89601EC6A7'),(1367,'微信用户419-用户开门','开门成功','W89601EC6A7','222',11,1689180520,'W89601EC6A7'),(1368,'微信用户415-用户开门','开门成功','W89601EC6A7','666',11,1689180832,'W89601EC6A7'),(1369,'微信用户419-用户开门','开门成功','W89601EC6A7','222',11,1689181015,'W89601EC6A7'),(1370,'管理端控制','启动电源','W71F9783520','666',11,1689241483,'W71F9783520'),(1371,'管理端控制','启动电源','W71F9783520','666',11,1689241495,'W71F9783520'),(1372,'管理端控制','启动电源','W71F9783520','666',11,1689241687,'W71F9783520'),(1373,'管理端控制','关闭电源','W71F9783520','666',11,1689241692,'W71F9783520'),(1374,'微信用户415-用户开门','开门成功','W89601EC6A7','888',11,1689261402,'W89601EC6A7'),(1375,'系统定时关电','关电成功','W71F9783520','666',11,1689312601,'W71F9783520'),(1376,'微信用户415-用户开门','开门成功','W89601EC6A7','666',11,1689350580,'W89601EC6A7'),(1377,'系统定时关电','关电成功','W71F9783520','666',11,1689364802,'W71F9783520');
/*!40000 ALTER TABLE `kg_device_record` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kg_discount_coupons`
--

DROP TABLE IF EXISTS `kg_discount_coupons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kg_discount_coupons` (
  `coupons_id` bigint NOT NULL AUTO_INCREMENT,
  `money` double(10,2) DEFAULT '0.00' COMMENT '金额',
  `title` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT '0.00' COMMENT '减少',
  `created_at` int DEFAULT '0',
  `updated_at` int DEFAULT '0',
  `new_user_status` tinyint DEFAULT '0',
  `deleted_at` int DEFAULT '0',
  `day_number` int DEFAULT '1',
  PRIMARY KEY (`coupons_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kg_discount_coupons`
--

LOCK TABLES `kg_discount_coupons` WRITE;
/*!40000 ALTER TABLE `kg_discount_coupons` DISABLE KEYS */;
/*!40000 ALTER TABLE `kg_discount_coupons` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kg_discount_reduce`
--

DROP TABLE IF EXISTS `kg_discount_reduce`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kg_discount_reduce` (
  `reduce_id` bigint NOT NULL AUTO_INCREMENT,
  `room_id` int DEFAULT NULL,
  `full` double(10,2) DEFAULT '0.00' COMMENT '满足价格',
  `reduce` double(10,2) DEFAULT '0.00' COMMENT '减少',
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `created_at` int DEFAULT '0',
  `deleted_at` int DEFAULT '0',
  `updated_at` int DEFAULT '0',
  `wxapp_id` int DEFAULT NULL,
  PRIMARY KEY (`reduce_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kg_discount_reduce`
--

LOCK TABLES `kg_discount_reduce` WRITE;
/*!40000 ALTER TABLE `kg_discount_reduce` DISABLE KEYS */;
/*!40000 ALTER TABLE `kg_discount_reduce` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kg_file`
--

DROP TABLE IF EXISTS `kg_file`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kg_file` (
  `file_id` bigint NOT NULL AUTO_INCREMENT,
  `hash` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`file_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kg_file`
--

LOCK TABLES `kg_file` WRITE;
/*!40000 ALTER TABLE `kg_file` DISABLE KEYS */;
/*!40000 ALTER TABLE `kg_file` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kg_goods`
--

DROP TABLE IF EXISTS `kg_goods`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kg_goods` (
  `goods_id` int NOT NULL AUTO_INCREMENT,
  `goods_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `goods_price` double(10,2) DEFAULT NULL,
  `goods_stock` int DEFAULT '0',
  `goods_sold` int DEFAULT '0',
  `goods_about` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `created_at` int DEFAULT '0',
  `updated_at` int DEFAULT '0',
  `deleted_at` int DEFAULT '0',
  `sort` int DEFAULT '0',
  `status` tinyint(1) DEFAULT '0',
  `goods_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `type_id` tinyint DEFAULT '1',
  `recommend_status` tinyint DEFAULT '0',
  `shipping_remarks` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`goods_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kg_goods`
--

LOCK TABLES `kg_goods` WRITE;
/*!40000 ALTER TABLE `kg_goods` DISABLE KEYS */;
INSERT INTO `kg_goods` VALUES (15,'龙井茶叶',3350.00,55,6,'<p><img class=\"wscnph\" src=\"https://was.weishequ.com/storage/file/20230712/e970b6de5e6072a771d3c2a9187948dc.png\" /></p>\n<p><img class=\"wscnph\" src=\"https://was.weishequ.com/storage/file/20230712/b50810b25f0e9ffb8b489c1826fd22d3.png\" /></p>\n<p><img class=\"wscnph\" src=\"https://was.weishequ.com/storage/file/20230712/7937aee50a58e8be7a7e3508682138f1.png\" /></p>',1689133214,0,0,1,1,'/storage/file/20230712/a6a84d56580a4a518e9f38df94d8700b.png',10,0,''),(16,'铁观音',3680.00,60,11,'<p><img class=\"wscnph\" src=\"https://was.weishequ.com/storage/file/20230712/50cf20c7d95d404f3793cebe04321c35.png\" /></p>\n<p><img class=\"wscnph\" src=\"https://was.weishequ.com/storage/file/20230712/07d1f9c6c9ce135da08c797996a47806.png\" /></p>\n<p><img class=\"wscnph\" src=\"https://was.weishequ.com/storage/file/20230712/1cefda0d188b9786bcdceeb8d154164d.png\" /></p>',1689133538,1689134676,0,0,1,'/storage/file/20230712/7ae32a70d2dfdc681e4231ce506cd195.png',10,0,''),(17,'毛尖',2860.00,88,7,'<p><img class=\"wscnph\" src=\"https://was.weishequ.com/storage/file/20230712/380906a65269ffd96ca1ef19d5136bca.png\" /></p>\n<p><img class=\"wscnph\" src=\"https://was.weishequ.com/storage/file/20230712/18a52f74f4f8102d9af653acdd2dbec6.png\" /></p>\n<p><img class=\"wscnph\" src=\"https://was.weishequ.com/storage/file/20230712/3de08f10633510efc69ab3faf4ba02a9.png\" /></p>',1689133912,1689134670,0,0,1,'/storage/file/20230712/6fbeeda0fe4d7209c28b441dd5ac9f7d.png',10,0,''),(18,'茅台酱香',3688.00,4,2,'<p><img class=\"wscnph\" src=\"https://was.weishequ.com/storage/file/20230712/1fb5772fac1c9a5a35bccfecd8be633c.png\" /></p>\n<p><img class=\"wscnph\" src=\"https://was.weishequ.com/storage/file/20230712/97d1a7264151a1389491b9db8f618561.png\" /></p>',1689134524,0,0,0,1,'/storage/file/20230712/1962e2f9086b57a1b7289c1c8fcf08aa.png',11,1,'');
/*!40000 ALTER TABLE `kg_goods` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kg_goods_type`
--

DROP TABLE IF EXISTS `kg_goods_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kg_goods_type` (
  `type_id` int NOT NULL AUTO_INCREMENT,
  `deleted_at` int DEFAULT '0',
  `type_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` int DEFAULT NULL,
  `updated_at` int DEFAULT NULL,
  `status` tinyint DEFAULT '1',
  `sort` int DEFAULT NULL,
  PRIMARY KEY (`type_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kg_goods_type`
--

LOCK TABLES `kg_goods_type` WRITE;
/*!40000 ALTER TABLE `kg_goods_type` DISABLE KEYS */;
INSERT INTO `kg_goods_type` VALUES (10,0,'茶叶',1689133144,NULL,1,0),(11,0,'美酒',1689133610,NULL,1,0);
/*!40000 ALTER TABLE `kg_goods_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kg_join_apply`
--

DROP TABLE IF EXISTS `kg_join_apply`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kg_join_apply` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `mobile` char(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `city` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `remarks` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `created_at` int DEFAULT '0',
  `deleted_at` int DEFAULT '0',
  `member_id` int DEFAULT NULL,
  `status` tinyint DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kg_join_apply`
--

LOCK TABLES `kg_join_apply` WRITE;
/*!40000 ALTER TABLE `kg_join_apply` DISABLE KEYS */;
INSERT INTO `kg_join_apply` VALUES (1,'李先生','13985125158','贵阳市','离线沟通一下',1689138325,0,415,1);
/*!40000 ALTER TABLE `kg_join_apply` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kg_join_user`
--

DROP TABLE IF EXISTS `kg_join_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kg_join_user` (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `user_name` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `status` tinyint DEFAULT '0',
  `account` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `created_at` int DEFAULT NULL,
  `deleted_at` int DEFAULT '0',
  `pid` int DEFAULT NULL,
  `store_number` int DEFAULT '0',
  `updated_at` int DEFAULT '0',
  `deleted_uid` int DEFAULT NULL,
  `level` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`user_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kg_join_user`
--

LOCK TABLES `kg_join_user` WRITE;
/*!40000 ALTER TABLE `kg_join_user` DISABLE KEYS */;
INSERT INTO `kg_join_user` VALUES (11,'欧拉茶室',1,'wsmadmin','$2y$12$bMmWJ.lMyVHxkQD0SIaeT.qPr4ztYP9BPxbqE2V.kDZ2OXHUNlds6',1686899238,0,2,2,1686936514,NULL,0),(12,'wsmadmin1',1,'wsmadmin1','$2y$12$JSW8RsPPn9Gt7jPKau5xPu0pwhs97qyqMQOpVr4xJRI1ZQejoHXCy',1687019833,0,2,0,0,NULL,0);
/*!40000 ALTER TABLE `kg_join_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kg_join_wallet`
--

DROP TABLE IF EXISTS `kg_join_wallet`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kg_join_wallet` (
  `join_id` int NOT NULL,
  `money` double(11,2) DEFAULT '0.00',
  `created_at` int NOT NULL,
  `updated_at` int DEFAULT '0',
  `deleted_at` int DEFAULT '0',
  `money_total` double(11,2) DEFAULT '0.00',
  `profit_time` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`join_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kg_join_wallet`
--

LOCK TABLES `kg_join_wallet` WRITE;
/*!40000 ALTER TABLE `kg_join_wallet` DISABLE KEYS */;
INSERT INTO `kg_join_wallet` VALUES (11,0.00,1686899238,0,0,0.00,NULL),(12,0.00,1687019833,0,0,0.00,NULL);
/*!40000 ALTER TABLE `kg_join_wallet` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kg_join_withdrawal`
--

DROP TABLE IF EXISTS `kg_join_withdrawal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kg_join_withdrawal` (
  `withdrawal_id` int NOT NULL AUTO_INCREMENT,
  `money` double(10,2) DEFAULT NULL,
  `created_at` int DEFAULT NULL,
  `updated_at` int DEFAULT NULL,
  `join_id` int DEFAULT NULL,
  `remarks` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` tinyint DEFAULT '0',
  `join_remarks` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`withdrawal_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kg_join_withdrawal`
--

LOCK TABLES `kg_join_withdrawal` WRITE;
/*!40000 ALTER TABLE `kg_join_withdrawal` DISABLE KEYS */;
/*!40000 ALTER TABLE `kg_join_withdrawal` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kg_member`
--

DROP TABLE IF EXISTS `kg_member`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kg_member` (
  `member_id` bigint NOT NULL AUTO_INCREMENT,
  `created_at` int DEFAULT NULL,
  `updated_at` int DEFAULT NULL,
  `deleted_at` int DEFAULT '0',
  PRIMARY KEY (`member_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=85 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kg_member`
--

LOCK TABLES `kg_member` WRITE;
/*!40000 ALTER TABLE `kg_member` DISABLE KEYS */;
/*!40000 ALTER TABLE `kg_member` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kg_member_coupons`
--

DROP TABLE IF EXISTS `kg_member_coupons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kg_member_coupons` (
  `member_coupons_id` bigint NOT NULL AUTO_INCREMENT,
  `money` double(10,2) DEFAULT '0.00' COMMENT '金额',
  `title` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT '0.00' COMMENT '减少',
  `created_at` int DEFAULT '0',
  `coupons_id` int DEFAULT NULL,
  `use_time` int DEFAULT '0',
  `member_id` int DEFAULT NULL,
  `updated_at` int DEFAULT '0',
  `end_time` int DEFAULT NULL,
  PRIMARY KEY (`member_coupons_id`) USING BTREE,
  KEY `idx_member_id_use_time` (`use_time`,`member_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kg_member_coupons`
--

LOCK TABLES `kg_member_coupons` WRITE;
/*!40000 ALTER TABLE `kg_member_coupons` DISABLE KEYS */;
/*!40000 ALTER TABLE `kg_member_coupons` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kg_member_integral_record`
--

DROP TABLE IF EXISTS `kg_member_integral_record`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kg_member_integral_record` (
  `member_id` int NOT NULL,
  `created_at` int DEFAULT NULL,
  `order_id` int DEFAULT NULL,
  `record_id` int NOT NULL AUTO_INCREMENT,
  `number` int DEFAULT NULL,
  `type` int DEFAULT '1',
  `record_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`record_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kg_member_integral_record`
--

LOCK TABLES `kg_member_integral_record` WRITE;
/*!40000 ALTER TABLE `kg_member_integral_record` DISABLE KEYS */;
INSERT INTO `kg_member_integral_record` VALUES (415,1687005129,762,9,3,1,'购买房间'),(415,1687005418,763,10,0,1,'购买房间'),(415,1687025893,764,11,0,1,'购买房间'),(418,1687100017,766,12,0,1,'购买房间'),(415,1689179132,776,13,1,1,'购买重置');
/*!40000 ALTER TABLE `kg_member_integral_record` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kg_member_pay_record`
--

DROP TABLE IF EXISTS `kg_member_pay_record`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kg_member_pay_record` (
  `record_id` bigint NOT NULL AUTO_INCREMENT,
  `title` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `created_at` int DEFAULT NULL,
  `price` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `order_id` bigint DEFAULT NULL,
  `member_id` int DEFAULT NULL,
  `type` int DEFAULT '1',
  PRIMARY KEY (`record_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=118 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kg_member_pay_record`
--

LOCK TABLES `kg_member_pay_record` WRITE;
/*!40000 ALTER TABLE `kg_member_pay_record` DISABLE KEYS */;
INSERT INTO `kg_member_pay_record` VALUES (99,'微信购买房间',1687018474,'3',762,415,2),(100,'微信购买房间',1687018474,'0.4',763,415,2),(101,'微信购买房间',1687025893,'0',764,415,2),(102,'微信购买房间',1687100017,'0.3',766,418,2),(103,'余额购买房间',1687140908,'0.2',769,419,2),(104,'余额购买房间',1687143811,'0.3',770,419,2),(105,'余额购买房间',1689169922,'0.2',772,419,2),(106,'后台充值',1689178980,'100',775,415,1),(107,'用户购买套餐',1689179132,'1',776,415,1),(108,'余额购买房间',1689179810,'0.25',778,419,2),(109,'余额购买房间',1689179873,'0.25',779,419,2),(110,'余额购买房间',1689179971,'0.05',780,419,2),(111,'余额购买房间',1689180284,'0.01',781,415,2),(112,'后台充值',1689181164,'100',790,418,1),(113,'余额购买房间',1689181179,'0.6',791,418,2),(114,'余额购买房间',1689181493,'1.5',793,418,2),(115,'余额购买房间',1689261382,'1.1',796,415,2),(116,'余额购买房间',1689262801,'1.7',797,415,2),(117,'余额购买房间',1689309930,'0.75',798,419,2);
/*!40000 ALTER TABLE `kg_member_pay_record` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kg_member_wallet`
--

DROP TABLE IF EXISTS `kg_member_wallet`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kg_member_wallet` (
  `member_id` bigint NOT NULL,
  `created_at` int DEFAULT NULL,
  `updated_at` int DEFAULT NULL,
  `money` double(10,2) DEFAULT '0.00' COMMENT '当前余额',
  `total` double(10,2) NOT NULL DEFAULT '0.00' COMMENT '总消费',
  `integral` double(12,2) DEFAULT '0.00',
  `wallet_id` bigint NOT NULL AUTO_INCREMENT,
  `store_id` int DEFAULT NULL,
  `total_consumption` double(12,2) DEFAULT '0.00',
  PRIMARY KEY (`wallet_id`) USING BTREE,
  KEY `idx_store_id_member_id` (`member_id`,`store_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=156 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kg_member_wallet`
--

LOCK TABLES `kg_member_wallet` WRITE;
/*!40000 ALTER TABLE `kg_member_wallet` DISABLE KEYS */;
INSERT INTO `kg_member_wallet` VALUES (415,NULL,NULL,98.19,1.00,1.00,127,NULL,0.00),(418,NULL,NULL,97.90,0.00,0.00,128,NULL,0.00),(419,NULL,NULL,97.00,0.00,0.00,129,NULL,0.00),(422,NULL,NULL,0.00,0.00,0.00,130,NULL,0.00),(423,NULL,NULL,0.00,0.00,0.00,131,NULL,0.00),(424,NULL,NULL,0.00,0.00,0.00,132,NULL,0.00),(426,NULL,NULL,0.00,0.00,0.00,133,NULL,0.00),(427,NULL,NULL,0.00,0.00,0.00,134,NULL,0.00),(428,NULL,NULL,0.00,0.00,0.00,135,NULL,0.00),(430,NULL,NULL,0.00,0.00,0.00,136,NULL,0.00),(431,NULL,NULL,0.00,0.00,0.00,137,NULL,0.00),(433,NULL,NULL,0.00,0.00,0.00,138,NULL,0.00),(434,NULL,NULL,0.00,0.00,0.00,139,NULL,0.00),(436,NULL,NULL,0.00,0.00,0.00,140,NULL,0.00),(439,NULL,NULL,0.00,0.00,0.00,141,NULL,0.00),(440,NULL,NULL,0.00,0.00,0.00,142,NULL,0.00),(444,NULL,NULL,0.00,0.00,0.00,143,NULL,0.00),(445,NULL,NULL,0.00,0.00,0.00,144,NULL,0.00),(446,NULL,NULL,0.00,0.00,0.00,145,NULL,0.00),(449,NULL,NULL,0.00,0.00,0.00,146,NULL,0.00),(450,NULL,NULL,0.00,0.00,0.00,147,NULL,0.00),(451,NULL,NULL,0.00,0.00,0.00,148,NULL,0.00),(453,NULL,NULL,0.00,0.00,0.00,149,NULL,0.00),(455,NULL,NULL,0.00,0.00,0.00,150,NULL,0.00),(456,NULL,NULL,0.00,0.00,0.00,151,NULL,0.00),(457,NULL,NULL,0.00,0.00,0.00,152,NULL,0.00),(459,NULL,NULL,0.00,0.00,0.00,153,NULL,0.00),(461,NULL,NULL,0.00,0.00,0.00,154,NULL,0.00),(462,NULL,NULL,0.00,0.00,0.00,155,NULL,0.00);
/*!40000 ALTER TABLE `kg_member_wallet` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kg_member_wechat`
--

DROP TABLE IF EXISTS `kg_member_wechat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kg_member_wechat` (
  `member_id` bigint NOT NULL AUTO_INCREMENT,
  `openid` varchar(56) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `nick_name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `province` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `avatar_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `gender` tinyint DEFAULT NULL,
  `mobile` char(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `created_at` int DEFAULT NULL,
  `updated_at` int DEFAULT NULL,
  `wxapp_id` bigint DEFAULT NULL,
  `integral` int DEFAULT '0',
  PRIMARY KEY (`member_id`) USING BTREE,
  UNIQUE KEY `uniqx_openid` (`openid`) USING BTREE,
  KEY `idx_nick_name_mobile` (`nick_name`,`mobile`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=463 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kg_member_wechat`
--

LOCK TABLES `kg_member_wechat` WRITE;
/*!40000 ALTER TABLE `kg_member_wechat` DISABLE KEYS */;
INSERT INTO `kg_member_wechat` VALUES (415,'oZ3Q-5Ez5H712aDeaFRC_YVjy-xw','微信用户',NULL,'https://thirdwx.qlogo.cn/mmopen/vi_32/POgEwh4mIHO4nibH0KlMECNjjGxQUq24ZEaGT4poC6icRiccVGKSyXwibcPq4BWmiaIGuG1icwxaQX6grC9VemZoJ8rg/132',NULL,'13885111171',NULL,1689179132,NULL,4),(416,'oZ3Q-5E-p8aRtUAssYg9Q48RPerA',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0),(417,'oZ3Q-5KbQDL39TOiebWOOwDfnZlk',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0),(418,'oZ3Q-5FrcCIKG2uUgZ3LnNaQ42Gs','微信用户',NULL,'https://thirdwx.qlogo.cn/mmopen/vi_32/POgEwh4mIHO4nibH0KlMECNjjGxQUq24ZEaGT4poC6icRiccVGKSyXwibcPq4BWmiaIGuG1icwxaQX6grC9VemZoJ8rg/132',NULL,'18985111181',NULL,1687100017,NULL,0),(419,'oZ3Q-5Dy6LgQrrKs5HGoQzGU7qdA','微信用户',NULL,'https://thirdwx.qlogo.cn/mmopen/vi_32/POgEwh4mIHO4nibH0KlMECNjjGxQUq24ZEaGT4poC6icRiccVGKSyXwibcPq4BWmiaIGuG1icwxaQX6grC9VemZoJ8rg/132',NULL,'13885429239',NULL,NULL,NULL,0),(420,'oZ3Q-5PvbEPaMmiw8hC-RLVef9GY',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0),(421,'oZ3Q-5H2-NfIPMLoB0RCfv_e8uAM',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0),(422,'oZ3Q-5FP3i_o4ZMEVvbwflqUcCNY',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0),(423,'oZ3Q-5KQZVz0C3ggh3rt_6ZApIw0','微信用户',NULL,'https://thirdwx.qlogo.cn/mmopen/vi_32/POgEwh4mIHO4nibH0KlMECNjjGxQUq24ZEaGT4poC6icRiccVGKSyXwibcPq4BWmiaIGuG1icwxaQX6grC9VemZoJ8rg/132',NULL,NULL,NULL,NULL,NULL,0),(424,'oZ3Q-5DOrDHmHnbTO-m78RW1Bygk',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0),(425,'oZ3Q-5MikdPsU9_XuaxzLOuzxQmc',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0),(426,'oZ3Q-5MPH_1EsfNn454fX8TjXqrk','微信用户',NULL,'https://thirdwx.qlogo.cn/mmopen/vi_32/POgEwh4mIHO4nibH0KlMECNjjGxQUq24ZEaGT4poC6icRiccVGKSyXwibcPq4BWmiaIGuG1icwxaQX6grC9VemZoJ8rg/132',NULL,NULL,NULL,NULL,NULL,0),(427,'oZ3Q-5GKJtYr0wAHB4h-aYER2LC4','微信用户',NULL,'https://thirdwx.qlogo.cn/mmopen/vi_32/POgEwh4mIHO4nibH0KlMECNjjGxQUq24ZEaGT4poC6icRiccVGKSyXwibcPq4BWmiaIGuG1icwxaQX6grC9VemZoJ8rg/132',NULL,NULL,NULL,NULL,NULL,0),(428,'oZ3Q-5Fe-6mXOKbpxyS577ZK_-yM','微信用户',NULL,'https://thirdwx.qlogo.cn/mmopen/vi_32/POgEwh4mIHO4nibH0KlMECNjjGxQUq24ZEaGT4poC6icRiccVGKSyXwibcPq4BWmiaIGuG1icwxaQX6grC9VemZoJ8rg/132',NULL,NULL,NULL,NULL,NULL,0),(429,'oZ3Q-5D7YxiBTwh9yUcS6pSRnk_E',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0),(430,'oZ3Q-5JYX3zT35SW5eyBtBcMvT0s',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0),(431,'oZ3Q-5BM0y8n9r6Wk4vwxyjA_9pk',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0),(432,'oZ3Q-5HMbPJOUWngtPOa31SFkVSw',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0),(433,'oZ3Q-5EiS7bmez6U4m7R4Kw9pfFM','微信用户',NULL,'https://thirdwx.qlogo.cn/mmopen/vi_32/POgEwh4mIHO4nibH0KlMECNjjGxQUq24ZEaGT4poC6icRiccVGKSyXwibcPq4BWmiaIGuG1icwxaQX6grC9VemZoJ8rg/132',NULL,NULL,NULL,NULL,NULL,0),(434,'oZ3Q-5OP9JyElEhwakyhq0kkXh9k','微信用户',NULL,'https://thirdwx.qlogo.cn/mmopen/vi_32/POgEwh4mIHO4nibH0KlMECNjjGxQUq24ZEaGT4poC6icRiccVGKSyXwibcPq4BWmiaIGuG1icwxaQX6grC9VemZoJ8rg/132',NULL,NULL,NULL,NULL,NULL,0),(435,'oZ3Q-5ESi4c_iTrUxENvUXKc6BsU',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0),(436,'oZ3Q-5NKVtrUte5Yp_1IDxP2u9yk',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0),(437,'oZ3Q-5PxW3SK0ANZW2khzmsdwsUU',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0),(438,'oZ3Q-5FnIrp1sBdne736hhzJy5Ns',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0),(439,'oZ3Q-5Hu4cV45QbK9cnkdIJnIPZA','微信用户',NULL,'https://thirdwx.qlogo.cn/mmopen/vi_32/POgEwh4mIHO4nibH0KlMECNjjGxQUq24ZEaGT4poC6icRiccVGKSyXwibcPq4BWmiaIGuG1icwxaQX6grC9VemZoJ8rg/132',NULL,NULL,NULL,NULL,NULL,0),(440,'oZ3Q-5DNpX6ghMcRthz9YqiMXvAE','微信用户',NULL,'https://thirdwx.qlogo.cn/mmopen/vi_32/POgEwh4mIHO4nibH0KlMECNjjGxQUq24ZEaGT4poC6icRiccVGKSyXwibcPq4BWmiaIGuG1icwxaQX6grC9VemZoJ8rg/132',NULL,'18621976181',NULL,NULL,NULL,0),(441,'oZ3Q-5N09mRd1HI8CNbw8Lujgxew',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0),(442,'oZ3Q-5PlueRgNrSbsxa5ieNhYkJM',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0),(443,'oZ3Q-5LKM0MHUz00MYX2pP6iWRVc',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0),(444,'oZ3Q-5O2DozhrUrAE6K-zap1muTU',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0),(445,'oZ3Q-5Bv-ZuhCh_flz1UEspXUYEI',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0),(446,'oZ3Q-5BB5qiB1XG3gZ1XydBIMEIM',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0),(447,'oZ3Q-5E_uef5PqIOhiElMfT5zdVA',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0),(448,'oZ3Q-5PFvpRm7eCyjW9Gz2g70GB8',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0),(449,'oZ3Q-5OyHFjoKyong6jfH3HW8Ujo','微信用户',NULL,'https://thirdwx.qlogo.cn/mmopen/vi_32/POgEwh4mIHO4nibH0KlMECNjjGxQUq24ZEaGT4poC6icRiccVGKSyXwibcPq4BWmiaIGuG1icwxaQX6grC9VemZoJ8rg/132',NULL,NULL,NULL,NULL,NULL,0),(450,'oZ3Q-5NB69_SDmK6Ne_BV5_O2c3E','微信用户',NULL,'https://thirdwx.qlogo.cn/mmopen/vi_32/POgEwh4mIHO4nibH0KlMECNjjGxQUq24ZEaGT4poC6icRiccVGKSyXwibcPq4BWmiaIGuG1icwxaQX6grC9VemZoJ8rg/132',NULL,NULL,NULL,NULL,NULL,0),(451,'oZ3Q-5KVgxeIzXMSH4TJL4Bhc2ik',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0),(452,'oZ3Q-5HUiN63s9XGYSWmOLOdNISY',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0),(453,'oZ3Q-5Jpfsz22chsGZYlWnG3Slk0','微信用户',NULL,'https://thirdwx.qlogo.cn/mmopen/vi_32/POgEwh4mIHO4nibH0KlMECNjjGxQUq24ZEaGT4poC6icRiccVGKSyXwibcPq4BWmiaIGuG1icwxaQX6grC9VemZoJ8rg/132',NULL,NULL,NULL,NULL,NULL,0),(454,'oZ3Q-5C9dbZuIKqKDoYG3PvfstcQ',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0),(455,'oZ3Q-5EESS44y0IT1Z9e8fSl90P4',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0),(456,'oZ3Q-5PYNDJvissWSPfshHVbhsF8','刘道光',NULL,'https://thirdwx.qlogo.cn/mmopen/vi_32/DYAIOgq83erjYtpBzdppsgFFicp26vTmsY78FMnZVqhFprERJSa6gGjMyx3Z09f4nxIRbbzACdwOmIKOpPQOiawg/132',NULL,NULL,NULL,NULL,NULL,0),(457,'oZ3Q-5M44jkDBfXM-ig-vkMFCkb8','微信用户',NULL,'https://thirdwx.qlogo.cn/mmopen/vi_32/POgEwh4mIHO4nibH0KlMECNjjGxQUq24ZEaGT4poC6icRiccVGKSyXwibcPq4BWmiaIGuG1icwxaQX6grC9VemZoJ8rg/132',NULL,'18618474353',NULL,NULL,NULL,0),(458,'oZ3Q-5PB9oiFlKe0GbIpXA9-CnCE',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0),(459,'oZ3Q-5NtZUwGsFHfSqZ-7uGTHRWo','微信用户',NULL,'https://thirdwx.qlogo.cn/mmopen/vi_32/POgEwh4mIHO4nibH0KlMECNjjGxQUq24ZEaGT4poC6icRiccVGKSyXwibcPq4BWmiaIGuG1icwxaQX6grC9VemZoJ8rg/132',NULL,'15302376460',NULL,NULL,NULL,0),(460,'oZ3Q-5JZWv6ypTVtEealRSCWya5g',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0),(461,'oZ3Q-5PWdQuujkRg81calxAiDFHY',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0),(462,'oZ3Q-5OgLXiNmCQxcF9u1E6j9XfY','微信用户',NULL,'https://thirdwx.qlogo.cn/mmopen/vi_32/POgEwh4mIHO4nibH0KlMECNjjGxQUq24ZEaGT4poC6icRiccVGKSyXwibcPq4BWmiaIGuG1icwxaQX6grC9VemZoJ8rg/132',NULL,NULL,NULL,NULL,NULL,0);
/*!40000 ALTER TABLE `kg_member_wechat` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kg_order`
--

DROP TABLE IF EXISTS `kg_order`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kg_order` (
  `order_id` bigint NOT NULL AUTO_INCREMENT,
  `order_sn` char(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL COMMENT '订单号',
  `order_type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL COMMENT '订单类型',
  `created_at` int DEFAULT '0' COMMENT '添加时间',
  `updated_at` int DEFAULT '0' COMMENT '更新时间',
  `pay_time` int DEFAULT '0' COMMENT '支付时间',
  `cancel_time` int DEFAULT '0' COMMENT '取消时间',
  `member_id` int DEFAULT NULL COMMENT '下单人',
  `room_id` int DEFAULT NULL,
  `store_id` int DEFAULT NULL,
  `order_status` tinyint DEFAULT '0' COMMENT '0|未开始，1|待处理，2处理中，3|已完成，4|已取消',
  `order_price` double(10,2) DEFAULT '0.00',
  `order_profit` double(10,2) DEFAULT '0.00',
  `deposit` double(10,2) DEFAULT '0.00',
  `deposit_status` tinyint DEFAULT '0' COMMENT '0|无押金，1|押金待退，2|完整退还，3|押金扣除退还',
  `deposit_deduct` double(10,2) DEFAULT '0.00' COMMENT '押金扣除',
  `pay_status` enum('1','0') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT '0',
  `pay_type` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `remarks` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `join_id` bigint DEFAULT '0',
  `transaction_id` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `deposit_remarks` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `depositd__at` int DEFAULT '0',
  `order_total` double(10,2) DEFAULT '0.00',
  `finish_time` int DEFAULT NULL,
  `js_status` tinyint DEFAULT '0',
  PRIMARY KEY (`order_id`) USING BTREE,
  UNIQUE KEY `uniqx_order_sn` (`order_sn`) USING BTREE,
  KEY `idx_room_id` (`room_id`) USING BTREE,
  KEY `idx_store_id` (`store_id`) USING BTREE,
  KEY `idx_wxapp_id` (`join_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=803 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kg_order`
--

LOCK TABLES `kg_order` WRITE;
/*!40000 ALTER TABLE `kg_order` DISABLE KEYS */;
INSERT INTO `kg_order` VALUES (760,'202306172002599397064473272101','room',1687003379,0,0,0,415,39,18,0,2.50,2.50,1.00,1,0.00,'0',NULL,NULL,11,NULL,NULL,0,2.50,NULL,0),(761,'202306172024282827888037690619','room',1687004668,0,0,0,415,39,18,0,2.50,2.50,1.00,1,0.00,'0',NULL,NULL,11,NULL,NULL,0,2.50,NULL,0),(762,'202306172031518268393428725005','room',1687005111,0,1687018473,0,415,39,18,3,3.00,3.00,1.00,1,0.00,'1','wechat',NULL,11,'4200001874202306176991765641',NULL,0,3.00,1687144663,0),(763,'202306172036481996764328883655','room',1687005408,0,1687018474,0,415,39,18,3,0.40,0.40,0.10,1,0.00,'1','wechat',NULL,11,'4200001870202306170892283921',NULL,0,0.40,1687144663,0),(764,'202306180218044624791862910936','room',1687025884,0,1687025893,0,415,39,18,3,0.00,0.00,0.10,1,0.00,'1','wechat',NULL,11,'4200001853202306187909339040',NULL,0,0.00,1687144663,0),(765,'202306182253219158299327832851','room',1687100001,0,0,0,418,39,18,0,0.25,0.25,0.10,1,0.00,'0',NULL,NULL,11,NULL,NULL,0,0.25,NULL,0),(766,'202306182253295830850055437047','room',1687100009,0,1687100017,0,418,39,18,3,0.30,0.30,0.10,1,0.00,'1','wechat',NULL,11,'4200001881202306180871149162',NULL,0,0.30,1687144663,0),(767,'202306182338255234827362870543','room',1687102705,0,0,0,419,39,18,0,0.20,0.20,0.10,1,0.00,'0',NULL,NULL,11,NULL,NULL,0,0.20,NULL,0),(768,'202306191012529094106242900247','room',1687140772,0,0,0,419,39,18,0,0.20,0.20,0.10,1,0.00,'0',NULL,NULL,11,NULL,NULL,0,0.20,NULL,0),(769,'202306191015061295402024123551','room',1687140906,0,1687140908,0,419,39,18,3,0.20,0.20,0.10,1,0.00,'1','balance',NULL,11,NULL,NULL,0,0.20,1687144663,0),(770,'202306191103301419711594364722','room',1687143810,0,1687143811,0,419,39,18,3,0.30,0.30,0.10,1,0.00,'1','balance',NULL,11,NULL,NULL,0,0.30,1687150800,0),(771,'202307121615413275581281489669','room',1689149741,0,0,0,440,39,18,0,0.20,0.20,0.10,1,0.00,'0',NULL,NULL,11,NULL,NULL,0,0.20,NULL,0),(772,'202307122152001907609863034091','room',1689169920,0,1689169922,0,419,39,18,3,0.20,0.20,0.10,1,0.00,'1','balance',NULL,11,NULL,NULL,0,0.20,1689170400,0),(773,'202307130018441461817433091492','room',1689178724,0,0,0,415,39,18,0,0.00,0.00,0.10,1,0.00,'0',NULL,NULL,11,NULL,NULL,0,0.00,NULL,0),(774,'202307130019209152850848682060','room',1689178760,0,1689178805,0,415,39,18,3,0.00,0.01,0.10,1,0.00,'1','balance',NULL,11,NULL,NULL,0,0.00,1689183000,0),(775,'202307130023009215376892250840','adminRecharge',0,0,1689178980,0,415,NULL,NULL,3,100.00,100.00,0.00,0,0.00,'1',NULL,NULL,0,NULL,NULL,0,0.00,NULL,0),(776,'202307130025278763604443953593','recharge',1689179127,0,1689179132,0,415,NULL,NULL,3,1.00,1.00,0.00,0,0.00,'1','wechat',NULL,0,'4200001870202307132201717731',NULL,0,0.00,1689179132,0),(777,'202307130031416703967116050530','room',1689179501,0,0,0,419,39,18,0,0.25,0.25,0.10,1,0.00,'0',NULL,NULL,11,NULL,NULL,0,0.25,NULL,0),(778,'202307130036444026910869086774','room',1689179804,0,1689179810,0,419,39,18,3,0.25,0.25,0.10,1,0.00,'1','balance',NULL,11,NULL,NULL,0,0.25,1689190200,0),(779,'202307130037529129523423905607','room',1689179872,0,1689179873,0,419,40,18,3,0.25,0.30,0.10,1,0.00,'1','balance',NULL,11,NULL,NULL,0,0.25,1689184800,0),(780,'202307130039316223310427924949','roomRenew',1689179971,0,1689179971,0,419,40,18,3,0.05,0.00,0.00,0,0.00,'1','balance',NULL,0,NULL,NULL,0,0.05,NULL,0),(781,'202307130044447759640924150471','roomRenew',1689180284,0,1689180284,0,415,39,18,3,0.01,0.00,0.00,0,0.00,'1','balance',NULL,0,NULL,NULL,0,0.01,NULL,0),(782,'202307130053261109803522733403','room',1689180806,0,1689180808,0,415,40,18,3,0.00,0.00,0.10,1,0.00,'1','balance',NULL,11,NULL,NULL,0,0.00,1689190200,0),(783,'202307130054337454580680770156','room',1689180873,0,0,0,415,40,18,0,0.00,0.00,0.10,1,0.00,'0',NULL,NULL,11,NULL,NULL,0,0.00,NULL,0),(784,'202307130054472528019138003000','room',1689180887,0,0,0,415,40,18,0,0.00,0.00,0.10,1,0.00,'0',NULL,NULL,11,NULL,NULL,0,0.00,NULL,0),(785,'202307130054526449412564586311','room',1689180892,0,0,0,415,40,18,0,0.00,0.00,0.10,1,0.00,'0',NULL,NULL,11,NULL,NULL,0,0.00,NULL,0),(786,'202307130056163983030940886290','room',1689180976,0,0,0,415,39,18,0,0.00,0.00,0.30,1,0.00,'0',NULL,NULL,11,NULL,NULL,0,0.00,NULL,0),(787,'202307130056507970638753281409','room',1689181010,0,0,0,415,39,18,0,0.00,0.00,0.30,1,0.00,'0',NULL,NULL,11,NULL,NULL,0,0.00,NULL,0),(788,'202307130057102348917553257901','room',1689181031,0,0,0,419,39,18,0,0.75,0.75,0.30,1,0.00,'0',NULL,NULL,11,NULL,NULL,0,0.75,NULL,0),(789,'202307130058485116609454817277','room',1689181128,0,0,0,418,40,18,0,0.60,0.60,0.20,1,0.00,'0',NULL,NULL,11,NULL,NULL,0,0.60,NULL,0),(790,'202307130059247692562967916612','adminRecharge',0,0,1689181164,0,418,NULL,NULL,3,100.00,100.00,0.00,0,0.00,'1',NULL,NULL,0,NULL,NULL,0,0.00,NULL,0),(791,'202307130059335905772163483173','room',1689181173,0,1689181179,0,418,40,18,3,0.60,0.60,0.20,1,0.00,'1','balance',NULL,11,NULL,NULL,0,0.60,1689199200,0),(792,'202307130104223103824553120213','room',1689181462,0,0,0,419,39,18,0,0.75,0.75,0.30,1,0.00,'0',NULL,NULL,11,NULL,NULL,0,0.75,NULL,0),(793,'202307130104512690296758929314','room',1689181491,0,1689181493,0,418,39,18,3,1.50,1.50,0.30,1,0.00,'1','balance',NULL,11,NULL,NULL,0,1.50,1689206400,0),(794,'202307130829374114202365277584','room',1689208177,0,0,0,415,39,18,0,0.00,0.00,0.30,1,0.00,'0',NULL,NULL,11,NULL,NULL,0,0.00,NULL,0),(795,'202307132315504669825730175288','room',1689261350,0,1689261351,0,415,40,18,3,0.00,2.80,0.20,1,0.00,'1','balance',NULL,11,NULL,NULL,0,0.00,1689312602,0),(796,'202307132316224232633761998029','roomRenew',1689261382,0,1689261382,0,415,40,18,3,1.10,0.00,0.00,0,0.00,'1','balance',NULL,0,NULL,NULL,0,1.10,NULL,0),(797,'202307132340011020688439098985','roomRenew',1689262801,0,1689262801,0,415,40,18,3,1.70,0.00,0.00,0,0.00,'1','balance',NULL,0,NULL,NULL,0,1.70,NULL,0),(798,'202307141245296994871118883458','room',1689309929,0,1689309930,0,419,39,18,3,0.75,0.75,0.30,1,0.00,'1','balance',NULL,11,NULL,NULL,0,0.75,1689312600,0),(799,'202307150002367446283441626685','room',1689350556,0,1689350564,0,415,39,18,3,0.00,0.00,0.30,1,0.00,'1','balance',NULL,11,NULL,NULL,0,0.00,1689364800,0),(800,'202307231402224851205853787454','room',1690092142,0,0,0,457,40,18,0,1.10,1.10,0.20,1,0.00,'0',NULL,NULL,11,NULL,NULL,0,1.10,NULL,0),(801,'202307231715204165512891802832','room',1690103720,0,0,0,457,40,18,0,0.40,0.40,0.20,1,0.00,'0',NULL,NULL,11,NULL,NULL,0,0.40,NULL,0),(802,'202307241508415449467120276446','room',1690182521,0,0,0,459,41,19,0,1.50,1.50,1.00,1,0.00,'0',NULL,NULL,11,NULL,NULL,0,1.50,NULL,0);
/*!40000 ALTER TABLE `kg_order` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kg_order_address`
--

DROP TABLE IF EXISTS `kg_order_address`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kg_order_address` (
  `order_id` bigint NOT NULL,
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `mobile` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `user_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `deliver_sn` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `deliver_remarks` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  PRIMARY KEY (`order_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kg_order_address`
--

LOCK TABLES `kg_order_address` WRITE;
/*!40000 ALTER TABLE `kg_order_address` DISABLE KEYS */;
/*!40000 ALTER TABLE `kg_order_address` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kg_order_cabinet`
--

DROP TABLE IF EXISTS `kg_order_cabinet`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kg_order_cabinet` (
  `order_id` bigint NOT NULL,
  `device_sn` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `goods_info` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `cabinet_number` int DEFAULT NULL,
  `lock_status` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`order_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kg_order_cabinet`
--

LOCK TABLES `kg_order_cabinet` WRITE;
/*!40000 ALTER TABLE `kg_order_cabinet` DISABLE KEYS */;
/*!40000 ALTER TABLE `kg_order_cabinet` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kg_order_coupons`
--

DROP TABLE IF EXISTS `kg_order_coupons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kg_order_coupons` (
  `member_coupons_id` bigint DEFAULT NULL,
  `money` double(10,2) DEFAULT '0.00' COMMENT '金额',
  `title` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT '0.00' COMMENT '减少',
  `created_at` int DEFAULT '0',
  `coupons_id` int DEFAULT NULL,
  `member_id` int DEFAULT NULL,
  `order_id` bigint NOT NULL AUTO_INCREMENT,
  `end_time` int DEFAULT NULL,
  PRIMARY KEY (`order_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kg_order_coupons`
--

LOCK TABLES `kg_order_coupons` WRITE;
/*!40000 ALTER TABLE `kg_order_coupons` DISABLE KEYS */;
/*!40000 ALTER TABLE `kg_order_coupons` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kg_order_goods`
--

DROP TABLE IF EXISTS `kg_order_goods`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kg_order_goods` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `order_id` bigint DEFAULT NULL,
  `goods_number` int DEFAULT NULL,
  `goods_info` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `created_at` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kg_order_goods`
--

LOCK TABLES `kg_order_goods` WRITE;
/*!40000 ALTER TABLE `kg_order_goods` DISABLE KEYS */;
/*!40000 ALTER TABLE `kg_order_goods` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kg_order_recharge_package`
--

DROP TABLE IF EXISTS `kg_order_recharge_package`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kg_order_recharge_package` (
  `order_id` int NOT NULL,
  `profit` double(10,2) DEFAULT '0.00',
  `store_id` int DEFAULT NULL,
  PRIMARY KEY (`order_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kg_order_recharge_package`
--

LOCK TABLES `kg_order_recharge_package` WRITE;
/*!40000 ALTER TABLE `kg_order_recharge_package` DISABLE KEYS */;
INSERT INTO `kg_order_recharge_package` VALUES (776,1.00,NULL);
/*!40000 ALTER TABLE `kg_order_recharge_package` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kg_order_reduce`
--

DROP TABLE IF EXISTS `kg_order_reduce`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kg_order_reduce` (
  `order_id` bigint NOT NULL,
  `room_id` int DEFAULT NULL,
  `full` double(10,2) DEFAULT '0.00' COMMENT '满足价格',
  `reduce` double(10,2) DEFAULT '0.00' COMMENT '减少',
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`order_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kg_order_reduce`
--

LOCK TABLES `kg_order_reduce` WRITE;
/*!40000 ALTER TABLE `kg_order_reduce` DISABLE KEYS */;
/*!40000 ALTER TABLE `kg_order_reduce` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kg_order_room`
--

DROP TABLE IF EXISTS `kg_order_room`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kg_order_room` (
  `order_id` int NOT NULL,
  `start_time` int DEFAULT NULL,
  `end_time` int DEFAULT NULL,
  `created_at` int DEFAULT NULL,
  `updated_at` int DEFAULT NULL,
  `room_info` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `room_status` enum('3','2','1','4','0') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT '0',
  `member_id` int DEFAULT NULL,
  `room_id` int DEFAULT NULL,
  `store_id` int DEFAULT NULL,
  `electric_number` int DEFAULT '0',
  `join_id` int DEFAULT NULL,
  PRIMARY KEY (`order_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kg_order_room`
--

LOCK TABLES `kg_order_room` WRITE;
/*!40000 ALTER TABLE `kg_order_room` DISABLE KEYS */;
INSERT INTO `kg_order_room` VALUES (760,1687001400,1687006800,1687003379,NULL,'{\"room_id\":39,\"room_name\":\"111\",\"room_images\":\"[\\\"\\\\\\/storage\\\\\\/file\\\\\\/1686936755483698.jpg\\\"]\",\"created_at\":null,\"updated_at\":null,\"deleted_at\":0,\"room_image\":\"\\/storage\\/file\\/1686936745170382.jpg\",\"room_price\":1,\"room_deposit\":\"1.00\",\"join_id\":11,\"status\":1,\"sort\":0,\"room_people\":7,\"room_size\":10,\"store_id\":18,\"room_type\":1,\"duration\":1,\"reserve_status\":0,\"room_label\":\"\\u5178\\u96c5\\u8336\\u5ba4\",\"electric_status\":0,\"starting_time\":1,\"room_sold\":0,\"work_time_end\":null,\"work_time_start\":null,\"work_week\":\"[]\",\"room_status\":1,\"electric_stop_status\":1,\"cancel_status\":1,\"dm_status\":1}','0',415,39,18,0,11),(761,1687001400,1687006800,1687004668,NULL,'{\"room_id\":39,\"room_name\":\"111\",\"room_images\":\"[\\\"\\\\\\/storage\\\\\\/file\\\\\\/1686936755483698.jpg\\\"]\",\"created_at\":null,\"updated_at\":null,\"deleted_at\":0,\"room_image\":\"\\/storage\\/file\\/1686936745170382.jpg\",\"room_price\":1,\"room_deposit\":\"1.00\",\"join_id\":11,\"status\":1,\"sort\":0,\"room_people\":7,\"room_size\":10,\"store_id\":18,\"room_type\":1,\"duration\":1,\"reserve_status\":0,\"room_label\":\"\\u5178\\u96c5\\u8336\\u5ba4\",\"electric_status\":0,\"starting_time\":1,\"room_sold\":0,\"work_time_end\":null,\"work_time_start\":null,\"work_week\":\"[]\",\"room_status\":1,\"electric_stop_status\":1,\"cancel_status\":1,\"dm_status\":1}','0',415,39,18,0,11),(762,1687003200,1687010400,1687005111,NULL,'{\"room_id\":39,\"room_name\":\"111\",\"room_images\":\"[\\\"\\\\\\/storage\\\\\\/file\\\\\\/1686936755483698.jpg\\\"]\",\"created_at\":null,\"updated_at\":null,\"deleted_at\":0,\"room_image\":\"\\/storage\\/file\\/1686936745170382.jpg\",\"room_price\":1,\"room_deposit\":\"1.00\",\"join_id\":11,\"status\":1,\"sort\":0,\"room_people\":7,\"room_size\":10,\"store_id\":18,\"room_type\":1,\"duration\":1,\"reserve_status\":0,\"room_label\":\"\\u5178\\u96c5\\u8336\\u5ba4\",\"electric_status\":0,\"starting_time\":1,\"room_sold\":0,\"work_time_end\":null,\"work_time_start\":null,\"work_week\":\"[]\",\"room_status\":1,\"electric_stop_status\":1,\"cancel_status\":1,\"dm_status\":1}','3',415,39,18,1,11),(763,1687005000,1687015800,1687005408,NULL,'{\"room_id\":39,\"room_name\":\"111\",\"room_images\":\"[\\\"\\\\\\/storage\\\\\\/file\\\\\\/1686936755483698.jpg\\\"]\",\"created_at\":null,\"updated_at\":null,\"deleted_at\":0,\"room_image\":\"\\/storage\\/file\\/1686936745170382.jpg\",\"room_price\":0.1,\"room_deposit\":\"0.10\",\"join_id\":11,\"status\":1,\"sort\":0,\"room_people\":7,\"room_size\":10,\"store_id\":18,\"room_type\":1,\"duration\":1,\"reserve_status\":0,\"room_label\":\"\\u5178\\u96c5\\u8336\\u5ba4\",\"electric_status\":0,\"starting_time\":1,\"room_sold\":0,\"work_time_end\":null,\"work_time_start\":null,\"work_week\":\"[]\",\"room_status\":1,\"electric_stop_status\":1,\"cancel_status\":1,\"dm_status\":1}','3',415,39,18,1,11),(764,1687023000,1687030200,1687025884,NULL,'{\"room_id\":39,\"room_name\":\"111\",\"room_images\":\"[\\\"\\\\\\/storage\\\\\\/file\\\\\\/1686936755483698.jpg\\\"]\",\"created_at\":null,\"updated_at\":null,\"deleted_at\":0,\"room_image\":\"\\/storage\\/file\\/1686936745170382.jpg\",\"room_price\":0.1,\"room_deposit\":\"0.10\",\"join_id\":11,\"status\":1,\"sort\":0,\"room_people\":7,\"room_size\":10,\"store_id\":18,\"room_type\":1,\"duration\":1,\"reserve_status\":0,\"room_label\":\"\\u5178\\u96c5\\u8336\\u5ba4\",\"electric_status\":0,\"starting_time\":1,\"room_sold\":2,\"work_time_end\":null,\"work_time_start\":null,\"work_week\":\"[]\",\"room_status\":1,\"electric_stop_status\":1,\"cancel_status\":1,\"dm_status\":1}','3',415,39,18,1,11),(765,1687096800,1687102200,1687100001,NULL,'{\"room_id\":39,\"room_name\":\"111\",\"room_images\":\"[\\\"\\\\\\/storage\\\\\\/file\\\\\\/1686936755483698.jpg\\\"]\",\"created_at\":null,\"updated_at\":null,\"deleted_at\":0,\"room_image\":\"\\/storage\\/file\\/1686936745170382.jpg\",\"room_price\":0.1,\"room_deposit\":\"0.10\",\"join_id\":11,\"status\":1,\"sort\":0,\"room_people\":7,\"room_size\":10,\"store_id\":18,\"room_type\":1,\"duration\":1,\"reserve_status\":0,\"room_label\":\"\\u5178\\u96c5\\u8336\\u5ba4\",\"electric_status\":0,\"starting_time\":1,\"room_sold\":3,\"work_time_end\":null,\"work_time_start\":null,\"work_week\":\"[]\",\"room_status\":1,\"electric_stop_status\":1,\"cancel_status\":1,\"dm_status\":1}','0',418,39,18,0,11),(766,1687096800,1687104000,1687100009,NULL,'{\"room_id\":39,\"room_name\":\"111\",\"room_images\":\"[\\\"\\\\\\/storage\\\\\\/file\\\\\\/1686936755483698.jpg\\\"]\",\"created_at\":null,\"updated_at\":null,\"deleted_at\":0,\"room_image\":\"\\/storage\\/file\\/1686936745170382.jpg\",\"room_price\":0.1,\"room_deposit\":\"0.10\",\"join_id\":11,\"status\":1,\"sort\":0,\"room_people\":7,\"room_size\":10,\"store_id\":18,\"room_type\":1,\"duration\":1,\"reserve_status\":0,\"room_label\":\"\\u5178\\u96c5\\u8336\\u5ba4\",\"electric_status\":0,\"starting_time\":1,\"room_sold\":3,\"work_time_end\":null,\"work_time_start\":null,\"work_week\":\"[]\",\"room_status\":1,\"electric_stop_status\":1,\"cancel_status\":1,\"dm_status\":1}','3',418,39,18,1,11),(767,1687105800,1687109400,1687102705,NULL,'{\"room_id\":39,\"room_name\":\"111\",\"room_images\":\"[\\\"\\\\\\/storage\\\\\\/file\\\\\\/1686936755483698.jpg\\\"]\",\"created_at\":null,\"updated_at\":null,\"deleted_at\":0,\"room_image\":\"\\/storage\\/file\\/1686936745170382.jpg\",\"room_price\":0.1,\"room_deposit\":\"0.10\",\"join_id\":11,\"status\":1,\"sort\":0,\"room_people\":7,\"room_size\":10,\"store_id\":18,\"room_type\":1,\"duration\":1,\"reserve_status\":0,\"room_label\":\"\\u5178\\u96c5\\u8336\\u5ba4\",\"electric_status\":0,\"starting_time\":1,\"room_sold\":4,\"work_time_end\":null,\"work_time_start\":null,\"work_week\":\"[]\",\"room_status\":1,\"electric_stop_status\":1,\"cancel_status\":1,\"dm_status\":1}','0',419,39,18,0,11),(768,1687138200,1687141800,1687140772,NULL,'{\"room_id\":39,\"room_name\":\"111\",\"room_images\":\"[\\\"\\\\\\/storage\\\\\\/file\\\\\\/1686936755483698.jpg\\\"]\",\"created_at\":null,\"updated_at\":null,\"deleted_at\":0,\"room_image\":\"\\/storage\\/file\\/1686936745170382.jpg\",\"room_price\":0.1,\"room_deposit\":\"0.10\",\"join_id\":11,\"status\":1,\"sort\":0,\"room_people\":7,\"room_size\":10,\"store_id\":18,\"room_type\":1,\"duration\":1,\"reserve_status\":0,\"room_label\":\"\\u5178\\u96c5\\u8336\\u5ba4\",\"electric_status\":0,\"starting_time\":1,\"room_sold\":4,\"work_time_end\":null,\"work_time_start\":null,\"work_week\":\"[]\",\"room_status\":1,\"electric_stop_status\":1,\"cancel_status\":1,\"dm_status\":1}','0',419,39,18,0,11),(769,1687138200,1687141800,1687140906,NULL,'{\"room_id\":39,\"room_name\":\"111\",\"room_images\":\"[\\\"\\\\\\/storage\\\\\\/file\\\\\\/1686936755483698.jpg\\\"]\",\"created_at\":null,\"updated_at\":null,\"deleted_at\":0,\"room_image\":\"\\/storage\\/file\\/1686936745170382.jpg\",\"room_price\":0.1,\"room_deposit\":\"0.10\",\"join_id\":11,\"status\":1,\"sort\":0,\"room_people\":7,\"room_size\":10,\"store_id\":18,\"room_type\":1,\"duration\":1,\"reserve_status\":0,\"room_label\":\"\\u5178\\u96c5\\u8336\\u5ba4\",\"electric_status\":0,\"starting_time\":1,\"room_sold\":4,\"work_time_end\":null,\"work_time_start\":null,\"work_week\":\"[]\",\"room_status\":1,\"electric_stop_status\":1,\"cancel_status\":1,\"dm_status\":1}','3',419,39,18,1,11),(770,1687143600,1687150800,1687143810,NULL,'{\"room_id\":39,\"room_name\":\"111\",\"room_images\":\"[\\\"\\\\\\/storage\\\\\\/file\\\\\\/1686936755483698.jpg\\\"]\",\"created_at\":null,\"updated_at\":null,\"deleted_at\":0,\"room_image\":\"\\/storage\\/file\\/1686936745170382.jpg\",\"room_price\":0.1,\"room_deposit\":\"0.10\",\"join_id\":11,\"status\":1,\"sort\":0,\"room_people\":7,\"room_size\":10,\"store_id\":18,\"room_type\":1,\"duration\":1,\"reserve_status\":0,\"room_label\":\"\\u5178\\u96c5\\u8336\\u5ba4\",\"electric_status\":0,\"starting_time\":1,\"room_sold\":5,\"work_time_end\":null,\"work_time_start\":null,\"work_week\":\"[]\",\"room_status\":1,\"electric_stop_status\":1,\"cancel_status\":1,\"dm_status\":1}','3',419,39,18,1,11),(771,1689148800,1689152400,1689149741,NULL,'{\"room_id\":39,\"room_name\":\"111\",\"room_images\":\"[\\\"\\\\\\/storage\\\\\\/file\\\\\\/1686936755483698.jpg\\\"]\",\"created_at\":null,\"updated_at\":null,\"deleted_at\":0,\"room_image\":\"\\/storage\\/file\\/1686936745170382.jpg\",\"room_price\":0.1,\"room_deposit\":\"0.10\",\"join_id\":11,\"status\":1,\"sort\":0,\"room_people\":7,\"room_size\":10,\"store_id\":18,\"room_type\":1,\"duration\":1,\"reserve_status\":0,\"room_label\":\"\\u5178\\u96c5\\u8336\\u5ba4\",\"electric_status\":0,\"starting_time\":1,\"room_sold\":6,\"work_time_end\":null,\"work_time_start\":null,\"work_week\":\"[]\",\"room_status\":1,\"electric_stop_status\":1,\"cancel_status\":1,\"dm_status\":1}','0',440,39,18,0,11),(772,1689166800,1689170400,1689169920,NULL,'{\"room_id\":39,\"room_name\":\"111\",\"room_images\":\"[\\\"\\\\\\/storage\\\\\\/file\\\\\\/1686936755483698.jpg\\\"]\",\"created_at\":null,\"updated_at\":null,\"deleted_at\":0,\"room_image\":\"\\/storage\\/file\\/1686936745170382.jpg\",\"room_price\":0.1,\"room_deposit\":\"0.10\",\"join_id\":11,\"status\":1,\"sort\":0,\"room_people\":7,\"room_size\":10,\"store_id\":18,\"room_type\":1,\"duration\":1,\"reserve_status\":0,\"room_label\":\"\\u5178\\u96c5\\u8336\\u5ba4\",\"electric_status\":0,\"starting_time\":1,\"room_sold\":6,\"work_time_end\":null,\"work_time_start\":null,\"work_week\":\"[]\",\"room_status\":1,\"electric_stop_status\":1,\"cancel_status\":1,\"dm_status\":1}','3',419,39,18,1,11),(773,1689177600,1689183000,1689178724,NULL,'{\"room_id\":39,\"room_name\":\"111\",\"room_images\":\"[\\\"\\\\\\/storage\\\\\\/file\\\\\\/1686936755483698.jpg\\\"]\",\"created_at\":null,\"updated_at\":null,\"deleted_at\":0,\"room_image\":\"\\/storage\\/file\\/1686936745170382.jpg\",\"room_price\":0.1,\"room_deposit\":\"0.10\",\"join_id\":11,\"status\":1,\"sort\":0,\"room_people\":7,\"room_size\":10,\"store_id\":18,\"room_type\":1,\"duration\":1,\"reserve_status\":0,\"room_label\":\"\\u5178\\u96c5\\u8336\\u5ba4\",\"electric_status\":0,\"starting_time\":1,\"room_sold\":7,\"work_time_end\":null,\"work_time_start\":null,\"work_week\":\"[]\",\"room_status\":1,\"electric_stop_status\":1,\"cancel_status\":1,\"dm_status\":1}','0',415,39,18,0,11),(774,1689177600,1689181200,1689178760,NULL,'{\"room_id\":39,\"room_name\":\"111\",\"room_images\":\"[\\\"\\\\\\/storage\\\\\\/file\\\\\\/1686936755483698.jpg\\\"]\",\"created_at\":null,\"updated_at\":null,\"deleted_at\":0,\"room_image\":\"\\/storage\\/file\\/1686936745170382.jpg\",\"room_price\":0.1,\"room_deposit\":\"0.10\",\"join_id\":11,\"status\":1,\"sort\":0,\"room_people\":7,\"room_size\":10,\"store_id\":18,\"room_type\":1,\"duration\":1,\"reserve_status\":0,\"room_label\":\"\\u5178\\u96c5\\u8336\\u5ba4\",\"electric_status\":0,\"starting_time\":1,\"room_sold\":7,\"work_time_end\":null,\"work_time_start\":null,\"work_week\":\"[]\",\"room_status\":1,\"electric_stop_status\":1,\"cancel_status\":1,\"dm_status\":1}','3',415,39,18,1,11),(777,1689184800,1689190200,1689179501,NULL,'{\"room_id\":39,\"room_name\":\"111\",\"room_images\":\"[\\\"\\\\\\/storage\\\\\\/file\\\\\\/1686936755483698.jpg\\\"]\",\"created_at\":null,\"updated_at\":null,\"deleted_at\":0,\"room_image\":\"\\/storage\\/file\\/1686936745170382.jpg\",\"room_price\":0.1,\"room_deposit\":\"0.10\",\"join_id\":11,\"status\":1,\"sort\":0,\"room_people\":7,\"room_size\":10,\"store_id\":18,\"room_type\":1,\"duration\":1,\"reserve_status\":0,\"room_label\":\"\\u5178\\u96c5\\u8336\\u5ba4\",\"electric_status\":0,\"starting_time\":1,\"room_sold\":8,\"work_time_end\":null,\"work_time_start\":null,\"work_week\":\"[]\",\"room_status\":1,\"electric_stop_status\":1,\"cancel_status\":1,\"dm_status\":1}','0',419,39,18,0,11),(778,1689184800,1689190200,1689179804,NULL,'{\"room_id\":39,\"room_name\":\"111\",\"room_images\":\"[\\\"\\\\\\/storage\\\\\\/file\\\\\\/1686936755483698.jpg\\\"]\",\"created_at\":null,\"updated_at\":null,\"deleted_at\":0,\"room_image\":\"\\/storage\\/file\\/1686936745170382.jpg\",\"room_price\":0.1,\"room_deposit\":\"0.10\",\"join_id\":11,\"status\":1,\"sort\":0,\"room_people\":7,\"room_size\":10,\"store_id\":18,\"room_type\":1,\"duration\":1,\"reserve_status\":0,\"room_label\":\"\\u5178\\u96c5\\u8336\\u5ba4\",\"electric_status\":0,\"starting_time\":1,\"room_sold\":8,\"work_time_end\":null,\"work_time_start\":null,\"work_week\":\"[]\",\"room_status\":1,\"electric_stop_status\":1,\"cancel_status\":1,\"dm_status\":1}','3',419,39,18,1,11),(779,1689177600,1689184800,1689179872,NULL,'{\"room_id\":40,\"room_name\":\"111\",\"room_images\":\"[\\\"\\\\\\/storage\\\\\\/file\\\\\\/1686936755483698.jpg\\\"]\",\"created_at\":null,\"updated_at\":null,\"deleted_at\":0,\"room_image\":\"\\/storage\\/file\\/1686936745170382.jpg\",\"room_price\":0.1,\"room_deposit\":\"0.10\",\"join_id\":11,\"status\":1,\"sort\":0,\"room_people\":7,\"room_size\":10,\"store_id\":18,\"room_type\":1,\"duration\":1,\"reserve_status\":0,\"room_label\":\"\\u5178\\u96c5\\u8336\\u5ba4\",\"electric_status\":0,\"starting_time\":1,\"room_sold\":9,\"work_time_end\":null,\"work_time_start\":null,\"work_week\":\"[]\",\"room_status\":1,\"electric_stop_status\":1,\"cancel_status\":1,\"dm_status\":1}','3',419,40,18,1,11),(782,1689186600,1689190200,1689180806,NULL,'{\"room_id\":40,\"room_name\":\"222\",\"room_images\":\"[\\\"\\\\\\/storage\\\\\\/file\\\\\\/1686936755483698.jpg\\\"]\",\"created_at\":null,\"updated_at\":null,\"deleted_at\":0,\"room_image\":\"\\/storage\\/file\\/1686936745170382_1200_800.jpg\",\"room_price\":0.1,\"room_deposit\":\"0.10\",\"join_id\":11,\"status\":1,\"sort\":0,\"room_people\":7,\"room_size\":10,\"store_id\":18,\"room_type\":1,\"duration\":1,\"reserve_status\":0,\"room_label\":\"\\u5178\\u96c5\\u8336\\u5ba4\",\"electric_status\":0,\"starting_time\":1,\"room_sold\":9,\"work_time_end\":null,\"work_time_start\":null,\"work_week\":\"[]\",\"room_status\":1,\"electric_stop_status\":1,\"cancel_status\":1,\"dm_status\":1}','3',415,40,18,1,11),(783,1689192000,1689197400,1689180873,NULL,'{\"room_id\":40,\"room_name\":\"222\",\"room_images\":\"[\\\"\\\\\\/storage\\\\\\/file\\\\\\/1686936755483698.jpg\\\"]\",\"created_at\":null,\"updated_at\":null,\"deleted_at\":0,\"room_image\":\"\\/storage\\/file\\/1686936745170382_1200_800.jpg\",\"room_price\":0.1,\"room_deposit\":\"0.10\",\"join_id\":11,\"status\":1,\"sort\":0,\"room_people\":7,\"room_size\":10,\"store_id\":18,\"room_type\":1,\"duration\":1,\"reserve_status\":0,\"room_label\":\"\\u5178\\u96c5\\u8336\\u5ba4\",\"electric_status\":0,\"starting_time\":1,\"room_sold\":10,\"work_time_end\":null,\"work_time_start\":null,\"work_week\":\"[]\",\"room_status\":1,\"electric_stop_status\":1,\"cancel_status\":1,\"dm_status\":1}','0',415,40,18,0,11),(784,1689192000,1689197400,1689180887,NULL,'{\"room_id\":40,\"room_name\":\"222\",\"room_images\":\"[\\\"\\\\\\/storage\\\\\\/file\\\\\\/1686936755483698.jpg\\\"]\",\"created_at\":null,\"updated_at\":null,\"deleted_at\":0,\"room_image\":\"\\/storage\\/file\\/1686936745170382_1200_800.jpg\",\"room_price\":0.1,\"room_deposit\":\"0.10\",\"join_id\":11,\"status\":1,\"sort\":0,\"room_people\":7,\"room_size\":10,\"store_id\":18,\"room_type\":1,\"duration\":1,\"reserve_status\":0,\"room_label\":\"\\u5178\\u96c5\\u8336\\u5ba4\",\"electric_status\":0,\"starting_time\":1,\"room_sold\":10,\"work_time_end\":null,\"work_time_start\":null,\"work_week\":\"[]\",\"room_status\":1,\"electric_stop_status\":1,\"cancel_status\":1,\"dm_status\":1}','0',415,40,18,0,11),(785,1689192000,1689197400,1689180892,NULL,'{\"room_id\":40,\"room_name\":\"222\",\"room_images\":\"[\\\"\\\\\\/storage\\\\\\/file\\\\\\/1686936755483698.jpg\\\"]\",\"created_at\":null,\"updated_at\":null,\"deleted_at\":0,\"room_image\":\"\\/storage\\/file\\/1686936745170382_1200_800.jpg\",\"room_price\":0.1,\"room_deposit\":\"0.10\",\"join_id\":11,\"status\":1,\"sort\":0,\"room_people\":7,\"room_size\":10,\"store_id\":18,\"room_type\":1,\"duration\":1,\"reserve_status\":0,\"room_label\":\"\\u5178\\u96c5\\u8336\\u5ba4\",\"electric_status\":0,\"starting_time\":1,\"room_sold\":10,\"work_time_end\":null,\"work_time_start\":null,\"work_week\":\"[]\",\"room_status\":1,\"electric_stop_status\":1,\"cancel_status\":1,\"dm_status\":1}','0',415,40,18,0,11),(786,1689192000,1689211800,1689180976,NULL,'{\"room_id\":39,\"room_name\":\"666\",\"room_images\":\"[\\\"\\\\\\/storage\\\\\\/file\\\\\\/1686936755483698.jpg\\\"]\",\"created_at\":null,\"updated_at\":null,\"deleted_at\":0,\"room_image\":\"\\/storage\\/file\\/1686936745170382_1200_800_1200_800.jpg\",\"room_price\":0.3,\"room_deposit\":\"0.30\",\"join_id\":11,\"status\":1,\"sort\":0,\"room_people\":7,\"room_size\":10,\"store_id\":18,\"room_type\":1,\"duration\":1,\"reserve_status\":0,\"room_label\":\"\\u5178\\u96c5\\u8336\\u5ba4\",\"electric_status\":0,\"starting_time\":1,\"room_sold\":9,\"work_time_end\":null,\"work_time_start\":null,\"work_week\":\"[]\",\"room_status\":1,\"electric_stop_status\":1,\"cancel_status\":1,\"dm_status\":1}','0',415,39,18,0,11),(787,1689192000,1689204600,1689181010,NULL,'{\"room_id\":39,\"room_name\":\"666\",\"room_images\":\"[\\\"\\\\\\/storage\\\\\\/file\\\\\\/1686936755483698.jpg\\\"]\",\"created_at\":null,\"updated_at\":null,\"deleted_at\":0,\"room_image\":\"\\/storage\\/file\\/1686936745170382_1200_800_1200_800.jpg\",\"room_price\":0.3,\"room_deposit\":\"0.30\",\"join_id\":11,\"status\":1,\"sort\":0,\"room_people\":7,\"room_size\":10,\"store_id\":18,\"room_type\":1,\"duration\":1,\"reserve_status\":0,\"room_label\":\"\\u5178\\u96c5\\u8336\\u5ba4\",\"electric_status\":0,\"starting_time\":1,\"room_sold\":9,\"work_time_end\":null,\"work_time_start\":null,\"work_week\":\"[]\",\"room_status\":1,\"electric_stop_status\":1,\"cancel_status\":1,\"dm_status\":1}','0',415,39,18,0,11),(788,1689192000,1689197400,1689181031,NULL,'{\"room_id\":39,\"room_name\":\"666\",\"room_images\":\"[\\\"\\\\\\/storage\\\\\\/file\\\\\\/1686936755483698.jpg\\\"]\",\"created_at\":null,\"updated_at\":null,\"deleted_at\":0,\"room_image\":\"\\/storage\\/file\\/1686936745170382_1200_800_1200_800.jpg\",\"room_price\":0.3,\"room_deposit\":\"0.30\",\"join_id\":11,\"status\":1,\"sort\":0,\"room_people\":7,\"room_size\":10,\"store_id\":18,\"room_type\":1,\"duration\":1,\"reserve_status\":0,\"room_label\":\"\\u5178\\u96c5\\u8336\\u5ba4\",\"electric_status\":0,\"starting_time\":1,\"room_sold\":9,\"work_time_end\":null,\"work_time_start\":null,\"work_week\":\"[]\",\"room_status\":1,\"electric_stop_status\":1,\"cancel_status\":1,\"dm_status\":1}','0',419,39,18,0,11),(789,1689192000,1689199200,1689181128,NULL,'{\"room_id\":40,\"room_name\":\"888\",\"room_images\":\"[\\\"\\\\\\/storage\\\\\\/file\\\\\\/1686936755483698.jpg\\\"]\",\"created_at\":null,\"updated_at\":null,\"deleted_at\":0,\"room_image\":\"\\/storage\\/file\\/1686936745170382_1200_800_1200_800_1200_800.jpg\",\"room_price\":0.2,\"room_deposit\":\"0.20\",\"join_id\":11,\"status\":1,\"sort\":0,\"room_people\":7,\"room_size\":10,\"store_id\":18,\"room_type\":1,\"duration\":1,\"reserve_status\":0,\"room_label\":\"\\u5178\\u96c5\\u8336\\u5ba4\",\"electric_status\":0,\"starting_time\":1,\"room_sold\":10,\"work_time_end\":null,\"work_time_start\":null,\"work_week\":\"[]\",\"room_status\":1,\"electric_stop_status\":1,\"cancel_status\":1,\"dm_status\":1}','0',418,40,18,0,11),(791,1689192000,1689199200,1689181173,NULL,'{\"room_id\":40,\"room_name\":\"888\",\"room_images\":\"[\\\"\\\\\\/storage\\\\\\/file\\\\\\/1686936755483698.jpg\\\"]\",\"created_at\":null,\"updated_at\":null,\"deleted_at\":0,\"room_image\":\"\\/storage\\/file\\/1686936745170382_1200_800_1200_800_1200_800.jpg\",\"room_price\":0.2,\"room_deposit\":\"0.20\",\"join_id\":11,\"status\":1,\"sort\":0,\"room_people\":7,\"room_size\":10,\"store_id\":18,\"room_type\":1,\"duration\":1,\"reserve_status\":0,\"room_label\":\"\\u5178\\u96c5\\u8336\\u5ba4\",\"electric_status\":0,\"starting_time\":1,\"room_sold\":10,\"work_time_end\":null,\"work_time_start\":null,\"work_week\":\"[]\",\"room_status\":1,\"electric_stop_status\":1,\"cancel_status\":1,\"dm_status\":1}','3',418,40,18,1,11),(792,1689193800,1689199200,1689181462,NULL,'{\"room_id\":39,\"room_name\":\"666\",\"room_images\":\"[\\\"\\\\\\/storage\\\\\\/file\\\\\\/1686936755483698.jpg\\\"]\",\"created_at\":null,\"updated_at\":null,\"deleted_at\":0,\"room_image\":\"\\/storage\\/file\\/1686936745170382_1200_800_1200_800.jpg\",\"room_price\":0.3,\"room_deposit\":\"0.30\",\"join_id\":11,\"status\":1,\"sort\":0,\"room_people\":7,\"room_size\":10,\"store_id\":18,\"room_type\":1,\"duration\":1,\"reserve_status\":0,\"room_label\":\"\\u5178\\u96c5\\u8336\\u5ba4\",\"electric_status\":0,\"starting_time\":1,\"room_sold\":9,\"work_time_end\":null,\"work_time_start\":null,\"work_week\":\"[]\",\"room_status\":1,\"electric_stop_status\":1,\"cancel_status\":1,\"dm_status\":1}','0',419,39,18,0,11),(793,1689192000,1689206400,1689181491,NULL,'{\"room_id\":39,\"room_name\":\"666\",\"room_images\":\"[\\\"\\\\\\/storage\\\\\\/file\\\\\\/1686936755483698.jpg\\\"]\",\"created_at\":null,\"updated_at\":null,\"deleted_at\":0,\"room_image\":\"\\/storage\\/file\\/1686936745170382_1200_800_1200_800.jpg\",\"room_price\":0.3,\"room_deposit\":\"0.30\",\"join_id\":11,\"status\":1,\"sort\":0,\"room_people\":7,\"room_size\":10,\"store_id\":18,\"room_type\":1,\"duration\":1,\"reserve_status\":0,\"room_label\":\"\\u5178\\u96c5\\u8336\\u5ba4\",\"electric_status\":0,\"starting_time\":1,\"room_sold\":9,\"work_time_end\":null,\"work_time_start\":null,\"work_week\":\"[]\",\"room_status\":1,\"electric_stop_status\":1,\"cancel_status\":1,\"dm_status\":1}','3',418,39,18,1,11),(794,1689208200,1689222600,1689208177,NULL,'{\"room_id\":39,\"room_name\":\"666\",\"room_images\":\"[\\\"\\\\\\/storage\\\\\\/file\\\\\\/1686936755483698.jpg\\\"]\",\"created_at\":null,\"updated_at\":null,\"deleted_at\":0,\"room_image\":\"\\/storage\\/file\\/1686936745170382_1200_800_1200_800.jpg\",\"room_price\":0.3,\"room_deposit\":\"0.30\",\"join_id\":11,\"status\":1,\"sort\":0,\"room_people\":7,\"room_size\":10,\"store_id\":18,\"room_type\":1,\"duration\":1,\"reserve_status\":0,\"room_label\":\"\\u5178\\u96c5\\u8336\\u5ba4\",\"electric_status\":0,\"starting_time\":1,\"room_sold\":10,\"work_time_end\":null,\"work_time_start\":null,\"work_week\":\"[]\",\"room_status\":1,\"electric_stop_status\":1,\"cancel_status\":1,\"dm_status\":1}','0',415,39,18,0,11),(795,1689258600,1689312600,1689261350,NULL,'{\"room_id\":40,\"room_name\":\"888\",\"room_images\":\"[\\\"\\\\\\/storage\\\\\\/file\\\\\\/1686936755483698.jpg\\\"]\",\"created_at\":null,\"updated_at\":null,\"deleted_at\":0,\"room_image\":\"\\/storage\\/file\\/1686936745170382_1200_800_1200_800_1200_800.jpg\",\"room_price\":0.2,\"room_deposit\":\"0.20\",\"join_id\":11,\"status\":1,\"sort\":0,\"room_people\":7,\"room_size\":10,\"store_id\":18,\"room_type\":1,\"duration\":1,\"reserve_status\":0,\"room_label\":\"\\u5178\\u96c5\\u8336\\u5ba4\",\"electric_status\":0,\"starting_time\":1,\"room_sold\":11,\"work_time_end\":null,\"work_time_start\":null,\"work_week\":\"[]\",\"room_status\":1,\"electric_stop_status\":1,\"cancel_status\":1,\"dm_status\":1}','3',415,40,18,1,11),(798,1689307200,1689312600,1689309929,NULL,'{\"room_id\":39,\"room_name\":\"666\",\"room_images\":\"[\\\"\\\\\\/storage\\\\\\/file\\\\\\/1686936755483698.jpg\\\"]\",\"created_at\":null,\"updated_at\":null,\"deleted_at\":0,\"room_image\":\"\\/storage\\/file\\/1686936745170382_1200_800_1200_800.jpg\",\"room_price\":0.3,\"room_deposit\":\"0.30\",\"join_id\":11,\"status\":1,\"sort\":0,\"room_people\":7,\"room_size\":10,\"store_id\":18,\"room_type\":1,\"duration\":1,\"reserve_status\":0,\"room_label\":\"\\u5178\\u96c5\\u8336\\u5ba4\",\"electric_status\":0,\"starting_time\":1,\"room_sold\":10,\"work_time_end\":null,\"work_time_start\":null,\"work_week\":\"[]\",\"room_status\":1,\"electric_stop_status\":1,\"cancel_status\":1,\"dm_status\":1}','3',419,39,18,1,11),(799,1689350400,1689364800,1689350556,NULL,'{\"room_id\":39,\"room_name\":\"666\",\"room_images\":\"[\\\"\\\\\\/storage\\\\\\/file\\\\\\/1686936755483698.jpg\\\"]\",\"created_at\":null,\"updated_at\":null,\"deleted_at\":0,\"room_image\":\"\\/storage\\/file\\/1686936745170382_1200_800_1200_800.jpg\",\"room_price\":0.3,\"room_deposit\":\"0.30\",\"join_id\":11,\"status\":1,\"sort\":0,\"room_people\":7,\"room_size\":10,\"store_id\":18,\"room_type\":1,\"duration\":1,\"reserve_status\":0,\"room_label\":\"\\u5178\\u96c5\\u8336\\u5ba4\",\"electric_status\":0,\"starting_time\":1,\"room_sold\":11,\"work_time_end\":null,\"work_time_start\":null,\"work_week\":\"[]\",\"room_status\":1,\"electric_stop_status\":1,\"cancel_status\":1,\"dm_status\":1}','3',415,39,18,1,11),(800,1690099200,1690115400,1690092142,NULL,'{\"room_id\":40,\"room_name\":\"888\",\"room_images\":\"[\\\"\\\\\\/storage\\\\\\/file\\\\\\/1686936755483698.jpg\\\"]\",\"created_at\":null,\"updated_at\":null,\"deleted_at\":0,\"room_image\":\"\\/storage\\/file\\/1686936745170382_1200_800_1200_800_1200_800.jpg\",\"room_price\":0.2,\"room_deposit\":\"0.20\",\"join_id\":11,\"status\":1,\"sort\":0,\"room_people\":7,\"room_size\":10,\"store_id\":18,\"room_type\":1,\"duration\":1,\"reserve_status\":0,\"room_label\":\"\\u5178\\u96c5\\u8336\\u5ba4\",\"electric_status\":0,\"starting_time\":1,\"room_sold\":12,\"work_time_end\":null,\"work_time_start\":null,\"work_week\":\"[]\",\"room_status\":1,\"electric_stop_status\":1,\"cancel_status\":1,\"dm_status\":1}','0',457,40,18,0,11),(801,1690252200,1690255800,1690103720,NULL,'{\"room_id\":40,\"room_name\":\"888\",\"room_images\":\"[\\\"\\\\\\/storage\\\\\\/file\\\\\\/1686936755483698.jpg\\\"]\",\"created_at\":null,\"updated_at\":null,\"deleted_at\":0,\"room_image\":\"\\/storage\\/file\\/1686936745170382_1200_800_1200_800_1200_800.jpg\",\"room_price\":0.2,\"room_deposit\":\"0.20\",\"join_id\":11,\"status\":1,\"sort\":0,\"room_people\":7,\"room_size\":10,\"store_id\":18,\"room_type\":1,\"duration\":1,\"reserve_status\":0,\"room_label\":\"\\u5178\\u96c5\\u8336\\u5ba4\",\"electric_status\":0,\"starting_time\":1,\"room_sold\":12,\"work_time_end\":null,\"work_time_start\":null,\"work_week\":\"[]\",\"room_status\":1,\"electric_stop_status\":1,\"cancel_status\":1,\"dm_status\":1}','0',457,40,18,0,11),(802,1690180200,1690183800,1690182521,NULL,'{\"room_id\":41,\"room_name\":\"999\",\"room_images\":\"[]\",\"created_at\":null,\"updated_at\":null,\"deleted_at\":0,\"room_image\":\"\\/storage\\/file\\/1689350728951427.jpg\",\"room_price\":0.5,\"room_deposit\":\"1.00\",\"join_id\":11,\"status\":1,\"sort\":0,\"room_people\":10,\"room_size\":100,\"store_id\":19,\"room_type\":1,\"duration\":1,\"reserve_status\":0,\"room_label\":\"\\u68cb\\u724c\\u5ba4\",\"electric_status\":1,\"starting_time\":1,\"room_sold\":7,\"work_time_end\":null,\"work_time_start\":null,\"work_week\":\"[]\",\"room_status\":1,\"electric_stop_status\":1,\"cancel_status\":1,\"dm_status\":0}','0',459,41,19,0,11);
/*!40000 ALTER TABLE `kg_order_room` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kg_order_room_renew`
--

DROP TABLE IF EXISTS `kg_order_room_renew`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kg_order_room_renew` (
  `order_id` bigint NOT NULL,
  `pid` bigint DEFAULT NULL,
  `created_at` int DEFAULT '0',
  `order_status` enum('1','3','4','0') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT '0',
  `number` int DEFAULT '0',
  `renew_time` int DEFAULT '0',
  `updated_at` int DEFAULT '0',
  PRIMARY KEY (`order_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kg_order_room_renew`
--

LOCK TABLES `kg_order_room_renew` WRITE;
/*!40000 ALTER TABLE `kg_order_room_renew` DISABLE KEYS */;
INSERT INTO `kg_order_room_renew` VALUES (780,779,1689179971,'3',0,1800,0),(781,774,1689180284,'3',0,-1800,0),(796,795,1689261382,'3',6,19800,0),(797,795,1689262801,'3',8,30600,0);
/*!40000 ALTER TABLE `kg_order_room_renew` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kg_pay_config`
--

DROP TABLE IF EXISTS `kg_pay_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kg_pay_config` (
  `wxapp_id` int NOT NULL,
  `app_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `mch_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `updated_at` int DEFAULT NULL,
  `created_at` int DEFAULT NULL,
  PRIMARY KEY (`wxapp_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kg_pay_config`
--

LOCK TABLES `kg_pay_config` WRITE;
/*!40000 ALTER TABLE `kg_pay_config` DISABLE KEYS */;
/*!40000 ALTER TABLE `kg_pay_config` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kg_proposal`
--

DROP TABLE IF EXISTS `kg_proposal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kg_proposal` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `member_id` int DEFAULT NULL,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `created_at` int DEFAULT NULL,
  `deleted_at` int DEFAULT '0',
  `status` tinyint DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kg_proposal`
--

LOCK TABLES `kg_proposal` WRITE;
/*!40000 ALTER TABLE `kg_proposal` DISABLE KEYS */;
/*!40000 ALTER TABLE `kg_proposal` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kg_recharge_package`
--

DROP TABLE IF EXISTS `kg_recharge_package`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kg_recharge_package` (
  `package_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT '0',
  `price` double(10,2) DEFAULT '0.00',
  `profit` double(10,2) DEFAULT NULL,
  `created_at` int DEFAULT NULL,
  `updated_at` int DEFAULT NULL,
  `deleted_at` int DEFAULT '0',
  `status` tinyint DEFAULT '0',
  PRIMARY KEY (`package_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kg_recharge_package`
--

LOCK TABLES `kg_recharge_package` WRITE;
/*!40000 ALTER TABLE `kg_recharge_package` DISABLE KEYS */;
INSERT INTO `kg_recharge_package` VALUES (1,2,'充200送100',200.00,100.00,1689180548,NULL,0,0);
/*!40000 ALTER TABLE `kg_recharge_package` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kg_room`
--

DROP TABLE IF EXISTS `kg_room`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kg_room` (
  `room_id` bigint NOT NULL AUTO_INCREMENT,
  `room_name` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `room_images` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `created_at` int DEFAULT NULL,
  `updated_at` int DEFAULT NULL,
  `deleted_at` int DEFAULT '0',
  `room_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `room_price` float(10,2) DEFAULT '0.00',
  `room_about` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `room_deposit` decimal(10,2) DEFAULT '0.00',
  `join_id` int DEFAULT NULL,
  `status` tinyint DEFAULT '1',
  `sort` int DEFAULT '0',
  `room_people` int DEFAULT '1',
  `room_size` int DEFAULT NULL,
  `store_id` int DEFAULT '0',
  `room_type` tinyint DEFAULT '1',
  `duration` tinyint DEFAULT '1' COMMENT '起订时长',
  `reserve_status` tinyint DEFAULT '0',
  `room_label` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL COMMENT '房间标签',
  `electric_status` tinyint DEFAULT '1',
  `starting_time` int DEFAULT '1',
  `room_sold` int DEFAULT '0',
  `work_time_end` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `work_time_start` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `work_week` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `room_status` tinyint DEFAULT '1',
  `electric_stop_status` tinyint DEFAULT '1',
  `cancel_status` tinyint DEFAULT '0',
  `dm_status` tinyint DEFAULT '1',
  PRIMARY KEY (`room_id`) USING BTREE,
  KEY `idx_store` (`store_id`) USING BTREE,
  KEY `idx_wxapp_id_deleted_at` (`deleted_at`,`join_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kg_room`
--

LOCK TABLES `kg_room` WRITE;
/*!40000 ALTER TABLE `kg_room` DISABLE KEYS */;
INSERT INTO `kg_room` VALUES (39,'666','[\"\\/storage\\/file\\/1686936755483698.jpg\"]',NULL,NULL,0,'/storage/file/1686936745170382_1200_800_1200_800.jpg',0.30,'<p>茶室的环境通常以简洁、雅致为主，营造出舒适放松的氛围。一些茶室还设计了传统的装饰元素，如竹子、花卉和中国字画等，以增添一种传统文化的气息。在茶室中，你可以选择坐在舒适的座位上，慢慢品味茶水的香气和口感。茶室也可能提供小吃和点心作为茶的伴随，以增加品茶时的愉悦感。茶室通常也会举办茶艺表演和茶道体验活动，让客人更深入地了解茶文化的精髓。这些活动可以包括制茶过程的演示、茶艺师的技艺展示以及茶道的仪式。</p>',0.30,11,1,0,7,10,18,1,1,0,'典雅茶室',0,1,12,NULL,NULL,'[]',1,1,1,1),(40,'888','[\"\\/storage\\/file\\/1686936755483698.jpg\"]',NULL,NULL,0,'/storage/file/1686936745170382_1200_800_1200_800_1200_800.jpg',0.20,'<p>茶室的环境通常以简洁、雅致为主，营造出舒适放松的氛围。一些茶室还设计了传统的装饰元素，如竹子、花卉和中国字画等，以增添一种传统文化的气息。在茶室中，你可以选择坐在舒适的座位上，慢慢品味茶水的香气和口感。茶室也可能提供小吃和点心作为茶的伴随，以增加品茶时的愉悦感。茶室通常也会举办茶艺表演和茶道体验活动，让客人更深入地了解茶文化的精髓。这些活动可以包括制茶过程的演示、茶艺师的技艺展示以及茶道的仪式。</p>',0.20,11,1,0,7,10,18,1,1,0,'典雅茶室',0,1,12,NULL,NULL,'[]',1,1,1,1),(41,'999','[]',NULL,NULL,0,'/storage/file/1689350728951427.jpg',0.50,'<p><img class=\"wscnph\" src=\"https://was.weishequ.com/storage/file/1689350776216567.jpg\" /></p>',1.00,11,1,0,10,100,19,1,1,0,'棋牌室',1,1,7,NULL,NULL,'[]',1,1,1,0);
/*!40000 ALTER TABLE `kg_room` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kg_room_extend`
--

DROP TABLE IF EXISTS `kg_room_extend`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kg_room_extend` (
  `room_id` bigint NOT NULL,
  `updated_at` int DEFAULT '0',
  `start_time_slot` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT '00:00',
  `end_time_slot` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT '23:30',
  PRIMARY KEY (`room_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kg_room_extend`
--

LOCK TABLES `kg_room_extend` WRITE;
/*!40000 ALTER TABLE `kg_room_extend` DISABLE KEYS */;
INSERT INTO `kg_room_extend` VALUES (39,0,'',''),(41,0,'','');
/*!40000 ALTER TABLE `kg_room_extend` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kg_room_key`
--

DROP TABLE IF EXISTS `kg_room_key`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kg_room_key` (
  `key_id` bigint NOT NULL AUTO_INCREMENT,
  `order_id` int DEFAULT NULL,
  `member_id` int DEFAULT NULL,
  `created_at` int DEFAULT '0',
  `updated_at` int DEFAULT '0',
  `deleted_at` int DEFAULT '0',
  `room_id` bigint DEFAULT '0',
  `store_id` int DEFAULT NULL,
  `join_id` int DEFAULT '0',
  PRIMARY KEY (`key_id`) USING BTREE,
  KEY `idx_memberId_deletedAt` (`member_id`,`deleted_at`) USING BTREE,
  KEY `idx_orderId` (`order_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=585 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kg_room_key`
--

LOCK TABLES `kg_room_key` WRITE;
/*!40000 ALTER TABLE `kg_room_key` DISABLE KEYS */;
INSERT INTO `kg_room_key` VALUES (569,762,415,1687018473,0,1687144663,39,18,11),(570,763,415,1687018474,0,1687144663,39,18,11),(571,764,415,1687025893,0,1687144663,39,18,11),(572,766,418,1687100017,0,1687144663,39,18,11),(573,769,419,1687140908,0,1687144663,39,18,11),(574,770,419,1687143811,0,1687150800,39,18,11),(575,772,419,1689169922,0,1689170400,39,18,11),(576,774,415,1689178805,0,1689183000,39,18,11),(577,778,419,1689179810,0,1689190200,39,18,11),(578,779,419,1689179873,0,1689184800,40,18,11),(579,782,415,1689180808,0,1689190200,40,18,11),(580,791,418,1689181179,0,1689199200,40,18,11),(581,793,418,1689181493,0,1689206400,39,18,11),(582,795,415,1689261351,0,1689312602,40,18,11),(583,798,419,1689309930,0,1689312600,39,18,11),(584,799,415,1689350564,0,1689364800,39,18,11);
/*!40000 ALTER TABLE `kg_room_key` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kg_shopping_car`
--

DROP TABLE IF EXISTS `kg_shopping_car`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kg_shopping_car` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `goods_id` int DEFAULT NULL,
  `member_id` int DEFAULT NULL,
  `deleted_at` int DEFAULT '0',
  `created_at` int DEFAULT NULL,
  `updated_at` int DEFAULT NULL,
  `number` int DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kg_shopping_car`
--

LOCK TABLES `kg_shopping_car` WRITE;
/*!40000 ALTER TABLE `kg_shopping_car` DISABLE KEYS */;
INSERT INTO `kg_shopping_car` VALUES (6,15,459,0,1690182239,NULL,1);
/*!40000 ALTER TABLE `kg_shopping_car` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kg_store`
--

DROP TABLE IF EXISTS `kg_store`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kg_store` (
  `store_id` bigint NOT NULL AUTO_INCREMENT,
  `store_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `contact` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `district` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `province` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `city` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `longitude` double(12,6) DEFAULT NULL,
  `latitude` double(12,6) DEFAULT NULL,
  `store_about` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `store_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `created_at` int DEFAULT NULL,
  `updated_at` int DEFAULT NULL,
  `deleted_at` int DEFAULT '0',
  `sort` int DEFAULT '0' COMMENT '排序',
  `join_id` int DEFAULT NULL,
  `status` tinyint DEFAULT '1',
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `store_sold` int DEFAULT '0',
  `label` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT '24小时营业',
  `store_type` tinyint DEFAULT '1',
  `vid` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`store_id`) USING BTREE,
  KEY `idx_city` (`city`(191)) USING BTREE,
  KEY `idx_store_name` (`store_name`(191)) USING BTREE,
  KEY `idx_wxapp_id_deleted_at` (`deleted_at`,`join_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kg_store`
--

LOCK TABLES `kg_store` WRITE;
/*!40000 ALTER TABLE `kg_store` DISABLE KEYS */;
INSERT INTO `kg_store` VALUES (18,'观山湖店','085182218221','观山湖区','贵州省','贵阳市',106.649186,26.618842,NULL,'/storage/file/1689348695173616.jpg',NULL,NULL,0,0,11,1,'贵州省贵阳市观山湖区观山街道维也纳国际酒店贵阳高铁北站店大堂维也纳国际酒店(贵阳高铁北站店)',24,'共享茶室',1,NULL),(19,'高新区店','085182218221','观山湖区','贵州省','贵阳市',106.646611,26.615697,'棋牌室','/storage/file/1689348591172255.jpg',NULL,NULL,0,0,11,1,'贵州省贵阳市观山湖区观山街道茅台商务中心',0,'棋牌室',1,NULL);
/*!40000 ALTER TABLE `kg_store` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kg_store_admin`
--

DROP TABLE IF EXISTS `kg_store_admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kg_store_admin` (
  `store_admin_id` int NOT NULL AUTO_INCREMENT,
  `mobile` char(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `pw` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `created_at` int DEFAULT NULL,
  `updated_at` int DEFAULT '0',
  `deleted_at` int DEFAULT '0',
  `status` tinyint DEFAULT '0',
  `sms_status` tinyint DEFAULT '0',
  `store_id_arr` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT '[]',
  `join_id` int DEFAULT '0',
  `user_name` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `level` tinyint DEFAULT '0',
  PRIMARY KEY (`store_admin_id`) USING BTREE,
  KEY `uniq_mobile` (`mobile`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kg_store_admin`
--

LOCK TABLES `kg_store_admin` WRITE;
/*!40000 ALTER TABLE `kg_store_admin` DISABLE KEYS */;
INSERT INTO `kg_store_admin` VALUES (25,'13885111171','$2y$12$HcTyykqaMAqAoNnlbByb2ux67gABoiGnzJ.8x6jRY9ar1jhHet1V6',1687020430,0,0,1,0,'[18]',11,'极客师傅',0);
/*!40000 ALTER TABLE `kg_store_admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kg_store_copies`
--

DROP TABLE IF EXISTS `kg_store_copies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kg_store_copies` (
  `store_id` bigint NOT NULL AUTO_INCREMENT,
  `store_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `contact` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `district` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `province` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `city` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `longitude` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `latitude` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `store_about` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `store_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `work_time_start` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `work_time_end` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `work_week` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `created_at` int DEFAULT NULL,
  `updated_at` int DEFAULT NULL,
  `deleted_at` int DEFAULT '0',
  `sort` int DEFAULT '0' COMMENT '排序',
  `wxapp_id` int DEFAULT NULL,
  `status` tinyint DEFAULT '1',
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `store_sold` int DEFAULT '0',
  PRIMARY KEY (`store_id`) USING BTREE,
  KEY `idx_city` (`city`(191)) USING BTREE,
  KEY `idx_store_name` (`store_name`(191)) USING BTREE,
  KEY `idx_wxapp_id_deleted_at` (`deleted_at`,`wxapp_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kg_store_copies`
--

LOCK TABLES `kg_store_copies` WRITE;
/*!40000 ALTER TABLE `kg_store_copies` DISABLE KEYS */;
/*!40000 ALTER TABLE `kg_store_copies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `store_copies`
--

DROP TABLE IF EXISTS `store_copies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `store_copies` (
  `store_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `store_copies`
--

LOCK TABLES `store_copies` WRITE;
/*!40000 ALTER TABLE `store_copies` DISABLE KEYS */;
/*!40000 ALTER TABLE `store_copies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_infos`
--

DROP TABLE IF EXISTS `user_infos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_infos` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `gender` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `hobby` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `hobby1` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `hobby2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_infos`
--

LOCK TABLES `user_infos` WRITE;
/*!40000 ALTER TABLE `user_infos` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_infos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping events for database 'wrzs'
--

--
-- Dumping routines for database 'wrzs'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-07-26  1:30:59
