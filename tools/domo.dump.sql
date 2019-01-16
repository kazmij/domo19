-- MySQL dump 10.13  Distrib 5.7.22, for Linux (x86_64)
--
-- Host: localhost    Database: domo_new
-- ------------------------------------------------------
-- Server version	5.7.22-0ubuntu0.17.10.1

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
-- Table structure for table `acl_classes`
--

DROP TABLE IF EXISTS `acl_classes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acl_classes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `class_type` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_69DD750638A36066` (`class_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acl_classes`
--

LOCK TABLES `acl_classes` WRITE;
/*!40000 ALTER TABLE `acl_classes` DISABLE KEYS */;
/*!40000 ALTER TABLE `acl_classes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `acl_entries`
--

DROP TABLE IF EXISTS `acl_entries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acl_entries` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `class_id` int(10) unsigned NOT NULL,
  `object_identity_id` int(10) unsigned DEFAULT NULL,
  `security_identity_id` int(10) unsigned NOT NULL,
  `field_name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ace_order` smallint(5) unsigned NOT NULL,
  `mask` int(11) NOT NULL,
  `granting` tinyint(1) NOT NULL,
  `granting_strategy` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `audit_success` tinyint(1) NOT NULL,
  `audit_failure` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_46C8B806EA000B103D9AB4A64DEF17BCE4289BF4` (`class_id`,`object_identity_id`,`field_name`,`ace_order`),
  KEY `IDX_46C8B806EA000B103D9AB4A6DF9183C9` (`class_id`,`object_identity_id`,`security_identity_id`),
  KEY `IDX_46C8B806EA000B10` (`class_id`),
  KEY `IDX_46C8B8063D9AB4A6` (`object_identity_id`),
  KEY `IDX_46C8B806DF9183C9` (`security_identity_id`),
  CONSTRAINT `FK_46C8B8063D9AB4A6` FOREIGN KEY (`object_identity_id`) REFERENCES `acl_object_identities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_46C8B806DF9183C9` FOREIGN KEY (`security_identity_id`) REFERENCES `acl_security_identities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_46C8B806EA000B10` FOREIGN KEY (`class_id`) REFERENCES `acl_classes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acl_entries`
--

LOCK TABLES `acl_entries` WRITE;
/*!40000 ALTER TABLE `acl_entries` DISABLE KEYS */;
/*!40000 ALTER TABLE `acl_entries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `acl_object_identities`
--

DROP TABLE IF EXISTS `acl_object_identities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acl_object_identities` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_object_identity_id` int(10) unsigned DEFAULT NULL,
  `class_id` int(10) unsigned NOT NULL,
  `object_identifier` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `entries_inheriting` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_9407E5494B12AD6EA000B10` (`object_identifier`,`class_id`),
  KEY `IDX_9407E54977FA751A` (`parent_object_identity_id`),
  CONSTRAINT `FK_9407E54977FA751A` FOREIGN KEY (`parent_object_identity_id`) REFERENCES `acl_object_identities` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acl_object_identities`
--

LOCK TABLES `acl_object_identities` WRITE;
/*!40000 ALTER TABLE `acl_object_identities` DISABLE KEYS */;
/*!40000 ALTER TABLE `acl_object_identities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `acl_object_identity_ancestors`
--

DROP TABLE IF EXISTS `acl_object_identity_ancestors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acl_object_identity_ancestors` (
  `object_identity_id` int(10) unsigned NOT NULL,
  `ancestor_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`object_identity_id`,`ancestor_id`),
  KEY `IDX_825DE2993D9AB4A6` (`object_identity_id`),
  KEY `IDX_825DE299C671CEA1` (`ancestor_id`),
  CONSTRAINT `FK_825DE2993D9AB4A6` FOREIGN KEY (`object_identity_id`) REFERENCES `acl_object_identities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_825DE299C671CEA1` FOREIGN KEY (`ancestor_id`) REFERENCES `acl_object_identities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acl_object_identity_ancestors`
--

LOCK TABLES `acl_object_identity_ancestors` WRITE;
/*!40000 ALTER TABLE `acl_object_identity_ancestors` DISABLE KEYS */;
/*!40000 ALTER TABLE `acl_object_identity_ancestors` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `acl_security_identities`
--

DROP TABLE IF EXISTS `acl_security_identities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acl_security_identities` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `identifier` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `username` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8835EE78772E836AF85E0677` (`identifier`,`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acl_security_identities`
--

LOCK TABLES `acl_security_identities` WRITE;
/*!40000 ALTER TABLE `acl_security_identities` DISABLE KEYS */;
/*!40000 ALTER TABLE `acl_security_identities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `classification__category`
--

DROP TABLE IF EXISTS `classification__category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `classification__category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) DEFAULT NULL,
  `context` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `media_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `position` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `custom_key` int(11) DEFAULT NULL,
  `custom_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `icon` longtext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `IDX_43629B36727ACA70` (`parent_id`),
  KEY `IDX_43629B36E25D857E` (`context`),
  KEY `IDX_43629B36EA9FDD75` (`media_id`),
  CONSTRAINT `FK_43629B36727ACA70` FOREIGN KEY (`parent_id`) REFERENCES `classification__category` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_43629B36E25D857E` FOREIGN KEY (`context`) REFERENCES `classification__context` (`id`),
  CONSTRAINT `FK_43629B36EA9FDD75` FOREIGN KEY (`media_id`) REFERENCES `media__media` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `classification__category`
--

LOCK TABLES `classification__category` WRITE;
/*!40000 ALTER TABLE `classification__category` DISABLE KEYS */;
INSERT INTO `classification__category` VALUES (15,NULL,'project_categories',6,'Parterowe',1,'parterowe',NULL,0,'2018-11-15 21:39:22','2018-11-20 22:50:51',NULL,NULL,NULL),(16,NULL,'project_categories',7,'Piętrowe',1,'pietrowe',NULL,0,'2018-11-15 21:39:22','2018-11-20 22:51:23',NULL,NULL,NULL),(17,NULL,'project_categories',8,'Z poddaszem',1,'z-poddaszem',NULL,0,'2018-11-15 21:39:22','2018-11-20 22:51:43',NULL,NULL,NULL),(18,NULL,'project_categories',9,'Tradycyjne',1,'tradycyjne',NULL,0,'2018-11-15 21:39:22','2018-11-20 22:51:59',NULL,NULL,NULL),(19,NULL,'project_categories',10,'Drewniane',1,'drewniane',NULL,0,'2018-11-15 21:39:22','2018-11-20 22:52:16',NULL,NULL,NULL),(20,NULL,'project_categories',11,'W promocji',1,'w-promocji',NULL,0,'2018-11-15 21:39:22','2018-11-20 22:52:33',NULL,NULL,NULL),(21,NULL,'project_categories',12,'Zabudowa bliźniacza',1,'zabudowa-blizniacza',NULL,0,'2018-11-15 21:39:22','2018-11-20 22:52:47',NULL,NULL,NULL),(22,NULL,'project_categories',13,'Garaże',1,'garaze',NULL,0,'2018-11-15 21:39:22','2018-11-20 22:53:05',NULL,NULL,NULL),(23,NULL,'project_categories',14,'Nowoczesne',1,'nowoczesne',NULL,0,'2018-11-15 21:39:22','2018-11-20 22:53:19',NULL,NULL,NULL),(24,NULL,'project_categories',15,'Małe',1,'male',NULL,0,'2018-11-15 21:39:22','2018-11-20 22:53:32',NULL,NULL,NULL),(25,NULL,'project_categories',16,'Średnie',1,'srednie',NULL,0,'2018-11-15 21:39:22','2018-11-20 22:53:46',NULL,NULL,NULL),(26,NULL,'project_categories',17,'Wille',1,'wille',NULL,0,'2018-11-15 21:39:22','2018-11-20 22:53:59',NULL,NULL,NULL),(27,NULL,'project_categories',18,'Tanie w budowie',1,'tanie-w-budowie',NULL,0,'2018-11-15 21:39:22','2018-11-20 22:54:13',NULL,NULL,NULL),(28,NULL,'project_categories',19,'Na wąską działkę',1,'na-waska-dzialke',NULL,0,'2018-11-15 21:39:22','2018-11-20 22:54:31',NULL,NULL,NULL),(43,NULL,'default',NULL,'default',1,'default','default',NULL,'2018-11-20 09:41:38','2018-11-20 09:41:38',NULL,NULL,NULL),(44,NULL,'menu',NULL,'menu',1,'menu','menu',NULL,'2018-11-20 09:41:56','2018-11-20 09:41:56',NULL,NULL,NULL),(45,NULL,'sonata_category',NULL,'sonata_category',1,'sonata-category','sonata_category',NULL,'2018-11-20 22:27:25','2018-11-20 22:27:25',NULL,NULL,NULL),(46,NULL,'offer',NULL,'offer',1,'offer','offer',NULL,'2018-11-20 22:27:45','2018-11-20 22:27:45',NULL,NULL,NULL);
/*!40000 ALTER TABLE `classification__category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `classification__collection`
--

DROP TABLE IF EXISTS `classification__collection`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `classification__collection` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `context` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `media_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tag_collection` (`slug`,`context`),
  KEY `IDX_A406B56AE25D857E` (`context`),
  KEY `IDX_A406B56AEA9FDD75` (`media_id`),
  CONSTRAINT `FK_A406B56AE25D857E` FOREIGN KEY (`context`) REFERENCES `classification__context` (`id`),
  CONSTRAINT `FK_A406B56AEA9FDD75` FOREIGN KEY (`media_id`) REFERENCES `media__media` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `classification__collection`
--

LOCK TABLES `classification__collection` WRITE;
/*!40000 ALTER TABLE `classification__collection` DISABLE KEYS */;
INSERT INTO `classification__collection` VALUES (4,'menu',NULL,'mainMenu',1,'mainmenu',NULL,'2018-11-15 21:39:22','2018-11-15 21:39:22'),(5,'menu',NULL,'ourServices',1,'ourservices','ourServices','2018-11-20 09:41:56','2018-11-20 09:41:56'),(6,'menu',NULL,'worthKnowing',1,'worthknowing','worthKnowing','2018-11-20 10:08:35','2018-11-20 10:08:35'),(7,'menu',NULL,'menuFooter',1,'menufooter','menuFooter','2018-11-25 21:18:59','2018-11-25 21:18:59');
/*!40000 ALTER TABLE `classification__collection` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `classification__context`
--

DROP TABLE IF EXISTS `classification__context`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `classification__context` (
  `id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `classification__context`
--

LOCK TABLES `classification__context` WRITE;
/*!40000 ALTER TABLE `classification__context` DISABLE KEYS */;
INSERT INTO `classification__context` VALUES ('default','default',1,'2018-11-15 21:39:22','2018-11-15 21:39:22'),('menu','menu',1,'2018-11-15 21:39:22','2018-11-15 21:39:22'),('offer','offer',1,'2018-11-15 21:39:22','2018-11-15 21:39:22'),('offer_definitions','offer_definitions',1,'2018-11-15 21:39:22','2018-11-15 21:39:22'),('page','page',1,'2018-11-15 21:39:22','2018-11-15 21:39:22'),('project_categories','project_categories',1,'2018-11-15 21:39:22','2018-11-15 21:39:22'),('sonata_category','sonata_category',1,'2018-11-20 22:27:25','2018-11-20 22:27:25');
/*!40000 ALTER TABLE `classification__context` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `classification__tag`
--

DROP TABLE IF EXISTS `classification__tag`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `classification__tag` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `context` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tag_context` (`slug`,`context`),
  KEY `IDX_CA57A1C7E25D857E` (`context`),
  CONSTRAINT `FK_CA57A1C7E25D857E` FOREIGN KEY (`context`) REFERENCES `classification__context` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `classification__tag`
--

LOCK TABLES `classification__tag` WRITE;
/*!40000 ALTER TABLE `classification__tag` DISABLE KEYS */;
/*!40000 ALTER TABLE `classification__tag` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ext_log_entries`
--

DROP TABLE IF EXISTS `ext_log_entries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ext_log_entries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `action` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
  `logged_at` datetime NOT NULL,
  `object_id` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `object_class` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `version` int(11) NOT NULL,
  `data` longtext COLLATE utf8_unicode_ci COMMENT '(DC2Type:array)',
  `username` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `log_class_lookup_idx` (`object_class`),
  KEY `log_date_lookup_idx` (`logged_at`),
  KEY `log_user_lookup_idx` (`username`),
  KEY `log_version_lookup_idx` (`object_id`,`object_class`,`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ext_log_entries`
--

LOCK TABLES `ext_log_entries` WRITE;
/*!40000 ALTER TABLE `ext_log_entries` DISABLE KEYS */;
/*!40000 ALTER TABLE `ext_log_entries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ext_translations`
--

DROP TABLE IF EXISTS `ext_translations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ext_translations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `locale` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
  `object_class` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `field` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `foreign_key` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `lookup_unique_idx` (`locale`,`object_class`,`field`,`foreign_key`),
  KEY `translations_lookup_idx` (`locale`,`object_class`,`foreign_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ext_translations`
--

LOCK TABLES `ext_translations` WRITE;
/*!40000 ALTER TABLE `ext_translations` DISABLE KEYS */;
/*!40000 ALTER TABLE `ext_translations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fos_user_group`
--

DROP TABLE IF EXISTS `fos_user_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fos_user_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `roles` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_583D1F3E5E237E06` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fos_user_group`
--

LOCK TABLES `fos_user_group` WRITE;
/*!40000 ALTER TABLE `fos_user_group` DISABLE KEYS */;
/*!40000 ALTER TABLE `fos_user_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fos_user_to_user`
--

DROP TABLE IF EXISTS `fos_user_to_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fos_user_to_user` (
  `user_id` int(11) NOT NULL,
  `related_user_id` int(11) NOT NULL,
  PRIMARY KEY (`user_id`,`related_user_id`),
  KEY `IDX_618C4271A76ED395` (`user_id`),
  KEY `IDX_618C427198771930` (`related_user_id`),
  CONSTRAINT `FK_618C427198771930` FOREIGN KEY (`related_user_id`) REFERENCES `fos_user_user` (`id`),
  CONSTRAINT `FK_618C4271A76ED395` FOREIGN KEY (`user_id`) REFERENCES `fos_user_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fos_user_to_user`
--

LOCK TABLES `fos_user_to_user` WRITE;
/*!40000 ALTER TABLE `fos_user_to_user` DISABLE KEYS */;
/*!40000 ALTER TABLE `fos_user_to_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fos_user_user`
--

DROP TABLE IF EXISTS `fos_user_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fos_user_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `media_id` int(11) DEFAULT NULL,
  `province_id` int(11) DEFAULT NULL,
  `username` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `username_canonical` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `email_canonical` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `salt` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `confirmation_token` varchar(180) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password_requested_at` datetime DEFAULT NULL,
  `roles` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `date_of_birth` datetime DEFAULT NULL,
  `firstname` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lastname` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `website` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `biography` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gender` varchar(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `locale` varchar(8) COLLATE utf8_unicode_ci DEFAULT NULL,
  `timezone` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `facebook_uid` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `facebook_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `facebook_data` longtext COLLATE utf8_unicode_ci COMMENT '(DC2Type:json)',
  `twitter_uid` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `twitter_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `twitter_data` longtext COLLATE utf8_unicode_ci COMMENT '(DC2Type:json)',
  `gplus_uid` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gplus_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gplus_data` longtext COLLATE utf8_unicode_ci COMMENT '(DC2Type:json)',
  `token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `two_step_code` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `city` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_C560D76192FC23A8` (`username_canonical`),
  UNIQUE KEY `UNIQ_C560D761A0D96FBF` (`email_canonical`),
  UNIQUE KEY `UNIQ_C560D761C05FB297` (`confirmation_token`),
  KEY `IDX_C560D761EA9FDD75` (`media_id`),
  KEY `IDX_C560D761E946114A` (`province_id`),
  CONSTRAINT `FK_C560D761E946114A` FOREIGN KEY (`province_id`) REFERENCES `province` (`id`) ON DELETE SET NULL,
  CONSTRAINT `FK_C560D761EA9FDD75` FOREIGN KEY (`media_id`) REFERENCES `media__media` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fos_user_user`
--

LOCK TABLES `fos_user_user` WRITE;
/*!40000 ALTER TABLE `fos_user_user` DISABLE KEYS */;
INSERT INTO `fos_user_user` VALUES (8,NULL,NULL,'admin','admin','pawel.kazmierczak@jaaqob.pl','pawel.kazmierczak@jaaqob.pl',1,'HCoQuhb7tmF1JXTUluS/WJ1qtTiDZoeG81W20/ng9fo','bB9rBgcOaSgqq1rwDHSZeG9OjFEmAsyiIfeIHngmF+01DkwUFq5dbDkVqn54Op2U3Z4IOGjTm+AZgm4JU5MS8A==','2019-01-15 14:36:39',NULL,NULL,'a:1:{i:0;s:16:\"ROLE_SUPER_ADMIN\";}','2018-11-15 21:39:22','2019-01-15 14:36:39',NULL,'Pawel','Kazmierczak',NULL,NULL,'u',NULL,NULL,NULL,NULL,NULL,'null',NULL,NULL,'null',NULL,NULL,'null',NULL,NULL,NULL);
/*!40000 ALTER TABLE `fos_user_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fos_user_user_group`
--

DROP TABLE IF EXISTS `fos_user_user_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fos_user_user_group` (
  `user_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  PRIMARY KEY (`user_id`,`group_id`),
  KEY `IDX_B3C77447A76ED395` (`user_id`),
  KEY `IDX_B3C77447FE54D947` (`group_id`),
  CONSTRAINT `FK_B3C77447A76ED395` FOREIGN KEY (`user_id`) REFERENCES `fos_user_user` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_B3C77447FE54D947` FOREIGN KEY (`group_id`) REFERENCES `fos_user_group` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fos_user_user_group`
--

LOCK TABLES `fos_user_user_group` WRITE;
/*!40000 ALTER TABLE `fos_user_user_group` DISABLE KEYS */;
/*!40000 ALTER TABLE `fos_user_user_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media__gallery`
--

DROP TABLE IF EXISTS `media__gallery`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `media__gallery` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `context` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `default_format` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media__gallery`
--

LOCK TABLES `media__gallery` WRITE;
/*!40000 ALTER TABLE `media__gallery` DISABLE KEYS */;
/*!40000 ALTER TABLE `media__gallery` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media__gallery_media`
--

DROP TABLE IF EXISTS `media__gallery_media`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `media__gallery_media` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gallery_id` int(11) DEFAULT NULL,
  `media_id` int(11) DEFAULT NULL,
  `position` int(11) NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_80D4C5414E7AF8F` (`gallery_id`),
  KEY `IDX_80D4C541EA9FDD75` (`media_id`),
  CONSTRAINT `FK_80D4C5414E7AF8F` FOREIGN KEY (`gallery_id`) REFERENCES `media__gallery` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_80D4C541EA9FDD75` FOREIGN KEY (`media_id`) REFERENCES `media__media` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media__gallery_media`
--

LOCK TABLES `media__gallery_media` WRITE;
/*!40000 ALTER TABLE `media__gallery_media` DISABLE KEYS */;
/*!40000 ALTER TABLE `media__gallery_media` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media__media`
--

DROP TABLE IF EXISTS `media__media`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `media__media` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `enabled` tinyint(1) NOT NULL,
  `provider_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `provider_status` int(11) NOT NULL,
  `provider_reference` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `provider_metadata` longtext COLLATE utf8_unicode_ci COMMENT '(DC2Type:json)',
  `width` int(11) DEFAULT NULL,
  `height` int(11) DEFAULT NULL,
  `length` decimal(10,0) DEFAULT NULL,
  `content_type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `content_size` int(11) DEFAULT NULL,
  `copyright` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `author_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `context` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cdn_is_flushable` tinyint(1) DEFAULT NULL,
  `cdn_flush_identifier` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cdn_flush_at` datetime DEFAULT NULL,
  `cdn_status` int(11) DEFAULT NULL,
  `updated_at` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  `role` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_5C6DD74E12469DE2` (`category_id`),
  CONSTRAINT `FK_5C6DD74E12469DE2` FOREIGN KEY (`category_id`) REFERENCES `classification__category` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media__media`
--

LOCK TABLES `media__media` WRITE;
/*!40000 ALTER TABLE `media__media` DISABLE KEYS */;
INSERT INTO `media__media` VALUES (1,45,'thumb-parterowe.png',NULL,0,'sonata.media.provider.image',1,'51aaf1395b37dc60034732f2ecc848aee424e74b.png','{\"filename\":\"thumb-parterowe.png\"}',360,240,NULL,'image/png',45215,NULL,NULL,'sonata_category',NULL,NULL,NULL,NULL,'2018-11-20 22:34:26','2018-11-20 22:34:26',NULL),(2,45,'thumb-parterowe.png',NULL,0,'sonata.media.provider.image',1,'05fdd09b49777d558a6b180d827dc753d876f344.png','{\"filename\":\"thumb-parterowe.png\"}',360,240,NULL,'image/png',45215,NULL,NULL,'sonata_category',NULL,NULL,NULL,NULL,'2018-11-20 22:37:40','2018-11-20 22:37:40',NULL),(3,45,'thumb-parterowe.png',NULL,0,'sonata.media.provider.image',1,'dbcd81a814d800b6b1ebd42421c3b7673f0f046a.png','{\"filename\":\"thumb-parterowe.png\"}',360,240,NULL,'image/png',45215,NULL,NULL,'sonata_category',NULL,NULL,NULL,NULL,'2018-11-20 22:44:44','2018-11-20 22:44:44',NULL),(4,43,'thumb-parterowe.png',NULL,0,'sonata.media.provider.image',1,'fd5e678811b59bd07791c7dad375aaf6709a3958.png','{\"filename\":\"thumb-parterowe.png\"}',360,240,NULL,'image/png',45215,NULL,NULL,'default',NULL,NULL,NULL,NULL,'2018-11-20 22:47:12','2018-11-20 22:47:12',NULL),(5,45,'thumb-parterowe.png',NULL,0,'sonata.media.provider.image',1,'959d6b1ca259fb8bd1f8a44188352142126154bb.png','{\"filename\":\"thumb-parterowe.png\"}',360,240,NULL,'image/png',45215,NULL,NULL,'sonata_category',NULL,NULL,NULL,NULL,'2018-11-20 22:47:34','2018-11-20 22:47:34',NULL),(6,43,'thumb-parterowe.png',NULL,0,'sonata.media.provider.image',1,'b881f869d6df55c15c54d092aaa2b0a8943fd477.png','{\"filename\":\"thumb-parterowe.png\"}',360,240,NULL,'image/png',45215,NULL,NULL,'default',NULL,NULL,NULL,NULL,'2018-11-20 22:50:48','2018-11-20 22:50:48',NULL),(7,43,'thumb-pietrowe.png',NULL,0,'sonata.media.provider.image',1,'e636a62ec116604e7c1a19719443dd9098de4ec5.png','{\"filename\":\"thumb-pietrowe.png\"}',360,240,NULL,'image/png',53437,NULL,NULL,'default',NULL,NULL,NULL,NULL,'2018-11-20 22:51:21','2018-11-20 22:51:21',NULL),(8,43,'thumb-z-poddaszem.png',NULL,0,'sonata.media.provider.image',1,'91fd5f1e75739e4ac8d64dcf0e07d174c55ee5ba.png','{\"filename\":\"thumb-z-poddaszem.png\"}',360,240,NULL,'image/png',46911,NULL,NULL,'default',NULL,NULL,NULL,NULL,'2018-11-20 22:51:41','2018-11-20 22:51:41',NULL),(9,43,'thumb-tradycyjne.png',NULL,0,'sonata.media.provider.image',1,'231bf8ddef6f0af1daf1e3951a7f19eb6d99e54c.png','{\"filename\":\"thumb-tradycyjne.png\"}',360,240,NULL,'image/png',51511,NULL,NULL,'default',NULL,NULL,NULL,NULL,'2018-11-20 22:51:57','2018-11-20 22:51:57',NULL),(10,43,'thumb-drewniane.png',NULL,0,'sonata.media.provider.image',1,'2336ca61ecb58d636f3245832aa0a019c4c93f5a.png','{\"filename\":\"thumb-drewniane.png\"}',360,240,NULL,'image/png',49157,NULL,NULL,'default',NULL,NULL,NULL,NULL,'2018-11-20 22:52:13','2018-11-20 22:52:13',NULL),(11,43,'thumb-w-promocji.png',NULL,0,'sonata.media.provider.image',1,'ad5adbe3e32271d2468e787569c0ee91e2925065.png','{\"filename\":\"thumb-w-promocji.png\"}',360,240,NULL,'image/png',45645,NULL,NULL,'default',NULL,NULL,NULL,NULL,'2018-11-20 22:52:31','2018-11-20 22:52:31',NULL),(12,43,'thumb-zabudowa-blizniacza.png',NULL,0,'sonata.media.provider.image',1,'719324198970b34a7b9fcb8972e58ce79f630050.png','{\"filename\":\"thumb-zabudowa-blizniacza.png\"}',360,240,NULL,'image/png',56480,NULL,NULL,'default',NULL,NULL,NULL,NULL,'2018-11-20 22:52:45','2018-11-20 22:52:45',NULL),(13,43,'thumb-garaze.png',NULL,0,'sonata.media.provider.image',1,'d363b6e417e9852a40dc0d838b6c99f21b8bd8be.png','{\"filename\":\"thumb-garaze.png\"}',360,240,NULL,'image/png',43241,NULL,NULL,'default',NULL,NULL,NULL,NULL,'2018-11-20 22:53:03','2018-11-20 22:53:03',NULL),(14,43,'thumb-nowoczesne.png',NULL,0,'sonata.media.provider.image',1,'a95a82242196723ae6db5271ad45f099e2a09c36.png','{\"filename\":\"thumb-nowoczesne.png\"}',360,240,NULL,'image/png',51336,NULL,NULL,'default',NULL,NULL,NULL,NULL,'2018-11-20 22:53:18','2018-11-20 22:53:18',NULL),(15,43,'thumb-male.png',NULL,0,'sonata.media.provider.image',1,'401abd7dc707ba151f5f878c467480e7cb7f5332.png','{\"filename\":\"thumb-male.png\"}',360,240,NULL,'image/png',56354,NULL,NULL,'default',NULL,NULL,NULL,NULL,'2018-11-20 22:53:30','2018-11-20 22:53:30',NULL),(16,43,'thumb-srednie.png',NULL,0,'sonata.media.provider.image',1,'126a1362656d613bb11073bc0da40e524f6b078a.png','{\"filename\":\"thumb-srednie.png\"}',360,240,NULL,'image/png',52388,NULL,NULL,'default',NULL,NULL,NULL,NULL,'2018-11-20 22:53:45','2018-11-20 22:53:45',NULL),(17,43,'thumb-wille.png',NULL,0,'sonata.media.provider.image',1,'d2b9a172422499eddb6511c698ce9ace8cb73448.png','{\"filename\":\"thumb-wille.png\"}',360,240,NULL,'image/png',45426,NULL,NULL,'default',NULL,NULL,NULL,NULL,'2018-11-20 22:53:57','2018-11-20 22:53:57',NULL),(18,43,'thumb-tanie-w-budowie.png',NULL,0,'sonata.media.provider.image',1,'1e9a4ec565b08179bb8320344ea6bba834d218f7.png','{\"filename\":\"thumb-tanie-w-budowie.png\"}',360,240,NULL,'image/png',46000,NULL,NULL,'default',NULL,NULL,NULL,NULL,'2018-11-20 22:54:12','2018-11-20 22:54:12',NULL),(19,43,'thumb-na-waska-dzialke.png',NULL,0,'sonata.media.provider.image',1,'1632a8d4518df274acc779129a94e773ecfecc99.png','{\"filename\":\"thumb-na-waska-dzialke.png\"}',360,240,NULL,'image/png',63208,NULL,NULL,'default',NULL,NULL,NULL,NULL,'2018-11-20 22:54:29','2018-11-20 22:54:29',NULL);
/*!40000 ALTER TABLE `media__media` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migration_versions`
--

DROP TABLE IF EXISTS `migration_versions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migration_versions` (
  `version` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migration_versions`
--

LOCK TABLES `migration_versions` WRITE;
/*!40000 ALTER TABLE `migration_versions` DISABLE KEYS */;
INSERT INTO `migration_versions` VALUES ('20181114134606');
/*!40000 ALTER TABLE `migration_versions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `offer`
--

DROP TABLE IF EXISTS `offer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `offer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `creator_user_id` int(11) DEFAULT NULL,
  `updated_user_id` int(11) DEFAULT NULL,
  `assigned_user_id` int(11) DEFAULT NULL,
  `external_id` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `search_phrase` longtext COLLATE utf8_unicode_ci,
  `updates` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_29D6873E29FC6AE1` (`creator_user_id`),
  KEY `IDX_29D6873EBB649746` (`updated_user_id`),
  KEY `IDX_29D6873EADF66B1A` (`assigned_user_id`),
  FULLTEXT KEY `IDX_29D6873E7BA98913` (`search_phrase`),
  CONSTRAINT `FK_29D6873E29FC6AE1` FOREIGN KEY (`creator_user_id`) REFERENCES `fos_user_user` (`id`),
  CONSTRAINT `FK_29D6873EADF66B1A` FOREIGN KEY (`assigned_user_id`) REFERENCES `fos_user_user` (`id`),
  CONSTRAINT `FK_29D6873EBB649746` FOREIGN KEY (`updated_user_id`) REFERENCES `fos_user_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `offer`
--

LOCK TABLES `offer` WRITE;
/*!40000 ALTER TABLE `offer` DISABLE KEYS */;
/*!40000 ALTER TABLE `offer` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `offer_attribute`
--

DROP TABLE IF EXISTS `offer_attribute`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `offer_attribute` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `offer_id` int(11) DEFAULT NULL,
  `attribute_id` int(11) DEFAULT NULL,
  `attribute_value_id` int(11) DEFAULT NULL,
  `gallery_id` int(11) DEFAULT NULL,
  `attribute_custom_value` longtext COLLATE utf8_unicode_ci,
  `attribute_custom_value_integer` double DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_59BF923F53C674EE` (`offer_id`),
  KEY `IDX_59BF923FB6E62EFA` (`attribute_id`),
  KEY `IDX_59BF923F65A22152` (`attribute_value_id`),
  KEY `IDX_59BF923F4E7AF8F` (`gallery_id`),
  FULLTEXT KEY `IDX_59BF923F5894AE7A` (`attribute_custom_value`),
  CONSTRAINT `FK_59BF923F4E7AF8F` FOREIGN KEY (`gallery_id`) REFERENCES `media__gallery` (`id`),
  CONSTRAINT `FK_59BF923F53C674EE` FOREIGN KEY (`offer_id`) REFERENCES `offer` (`id`),
  CONSTRAINT `FK_59BF923F65A22152` FOREIGN KEY (`attribute_value_id`) REFERENCES `classification__category` (`id`),
  CONSTRAINT `FK_59BF923FB6E62EFA` FOREIGN KEY (`attribute_id`) REFERENCES `classification__category` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `offer_attribute`
--

LOCK TABLES `offer_attribute` WRITE;
/*!40000 ALTER TABLE `offer_attribute` DISABLE KEYS */;
/*!40000 ALTER TABLE `offer_attribute` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `offer_import`
--

DROP TABLE IF EXISTS `offer_import`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `offer_import` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `creator_user_id` int(11) DEFAULT NULL,
  `assigned_user_id` int(11) DEFAULT NULL,
  `import_file_id` int(11) DEFAULT NULL,
  `status` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `error` longtext COLLATE utf8_unicode_ci,
  `errorsCount` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_441BB9BC29FC6AE1` (`creator_user_id`),
  KEY `IDX_441BB9BCADF66B1A` (`assigned_user_id`),
  KEY `IDX_441BB9BC80DBD080` (`import_file_id`),
  CONSTRAINT `FK_441BB9BC29FC6AE1` FOREIGN KEY (`creator_user_id`) REFERENCES `fos_user_user` (`id`),
  CONSTRAINT `FK_441BB9BC80DBD080` FOREIGN KEY (`import_file_id`) REFERENCES `media__media` (`id`) ON DELETE SET NULL,
  CONSTRAINT `FK_441BB9BCADF66B1A` FOREIGN KEY (`assigned_user_id`) REFERENCES `fos_user_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `offer_import`
--

LOCK TABLES `offer_import` WRITE;
/*!40000 ALTER TABLE `offer_import` DISABLE KEYS */;
/*!40000 ALTER TABLE `offer_import` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `offer_to_import`
--

DROP TABLE IF EXISTS `offer_to_import`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `offer_to_import` (
  `import_id` int(11) NOT NULL,
  `offer_id` int(11) NOT NULL,
  PRIMARY KEY (`import_id`,`offer_id`),
  KEY `IDX_1859BF44B6A263D9` (`import_id`),
  KEY `IDX_1859BF4453C674EE` (`offer_id`),
  CONSTRAINT `FK_1859BF4453C674EE` FOREIGN KEY (`offer_id`) REFERENCES `offer` (`id`),
  CONSTRAINT `FK_1859BF44B6A263D9` FOREIGN KEY (`import_id`) REFERENCES `offer_import` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `offer_to_import`
--

LOCK TABLES `offer_to_import` WRITE;
/*!40000 ALTER TABLE `offer_to_import` DISABLE KEYS */;
/*!40000 ALTER TABLE `offer_to_import` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `page__block`
--

DROP TABLE IF EXISTS `page__block`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `page__block` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) DEFAULT NULL,
  `page_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `type` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `settings` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:json)',
  `enabled` tinyint(1) DEFAULT NULL,
  `position` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `alias` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_66F58DA0E16C6B94` (`alias`),
  KEY `IDX_66F58DA0727ACA70` (`parent_id`),
  KEY `IDX_66F58DA0C4663E4` (`page_id`),
  CONSTRAINT `FK_66F58DA0727ACA70` FOREIGN KEY (`parent_id`) REFERENCES `page__block` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_66F58DA0C4663E4` FOREIGN KEY (`page_id`) REFERENCES `page__page` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=77 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `page__block`
--

LOCK TABLES `page__block` WRITE;
/*!40000 ALTER TABLE `page__block` DISABLE KEYS */;
INSERT INTO `page__block` VALUES (12,NULL,33,'header','sonata.page.block.container','{\"code\":\"header\"}',1,1,'2018-11-15 21:48:43','2018-11-15 21:48:43','header'),(13,NULL,33,'content_top','sonata.page.block.container','{\"code\":\"content_top\"}',1,1,'2018-11-15 21:48:43','2018-11-15 21:48:43','content_top'),(14,NULL,33,'content','sonata.page.block.container','{\"code\":\"content\"}',1,1,'2018-11-15 21:48:43','2018-11-15 21:48:43','content'),(15,NULL,33,'content_bottom','sonata.page.block.container','{\"code\":\"content_bottom\"}',1,1,'2018-11-15 21:48:43','2018-11-15 21:48:43','content_bottom'),(16,NULL,33,'footer','sonata.page.block.container','{\"code\":\"footer\"}',1,1,'2018-11-15 21:48:43','2018-11-15 21:48:43','footer'),(17,NULL,42,'header','sonata.page.block.container','{\"code\":\"header\"}',1,1,'2018-11-20 09:09:34','2018-11-20 09:09:34','header_1'),(18,NULL,42,'content_top','sonata.page.block.container','{\"code\":\"content_top\"}',1,1,'2018-11-20 09:09:34','2018-11-20 09:09:34','content_top_1'),(19,NULL,42,'content','sonata.page.block.container','{\"code\":\"content\"}',1,1,'2018-11-20 09:09:35','2018-11-20 09:09:35','content_1'),(20,NULL,42,'content_bottom','sonata.page.block.container','{\"code\":\"content_bottom\"}',1,1,'2018-11-20 09:09:35','2018-11-20 09:09:35','content_bottom_1'),(21,NULL,42,'footer','sonata.page.block.container','{\"code\":\"footer\"}',1,1,'2018-11-20 09:09:35','2018-11-20 09:09:35','footer_1'),(22,NULL,48,'header','sonata.page.block.container','{\"code\":\"header\"}',1,1,'2018-11-20 09:24:26','2018-11-20 09:24:26','header_2'),(23,NULL,48,'content_top','sonata.page.block.container','{\"code\":\"content_top\"}',1,1,'2018-11-20 09:24:26','2018-11-20 09:24:26','content_top_2'),(24,NULL,48,'content','sonata.page.block.container','{\"code\":\"content\"}',1,1,'2018-11-20 09:24:26','2018-11-20 09:24:26','content_2'),(25,NULL,48,'content_bottom','sonata.page.block.container','{\"code\":\"content_bottom\"}',1,1,'2018-11-20 09:24:26','2018-11-20 09:24:26','content_bottom_2'),(26,NULL,48,'footer','sonata.page.block.container','{\"code\":\"footer\"}',1,1,'2018-11-20 09:24:26','2018-11-20 09:24:26','footer_2'),(27,NULL,61,'header','sonata.page.block.container','{\"code\":\"header\"}',1,1,'2018-11-20 14:10:12','2018-11-20 14:10:12','header_3'),(28,NULL,61,'content_top','sonata.page.block.container','{\"code\":\"content_top\"}',1,1,'2018-11-20 14:10:12','2018-11-20 14:10:12','content_top_3'),(29,NULL,61,'content','sonata.page.block.container','{\"code\":\"content\"}',1,1,'2018-11-20 14:10:12','2018-11-20 14:10:12','content_3'),(30,NULL,61,'content_bottom','sonata.page.block.container','{\"code\":\"content_bottom\"}',1,1,'2018-11-20 14:10:12','2018-11-20 14:10:12','content_bottom_3'),(31,NULL,61,'footer','sonata.page.block.container','{\"code\":\"footer\"}',1,1,'2018-11-20 14:10:12','2018-11-20 14:10:12','footer_3'),(32,NULL,62,'header','sonata.page.block.container','{\"code\":\"header\"}',1,1,'2018-11-20 14:10:16','2018-11-20 14:10:16','header_4'),(33,NULL,62,'content_top','sonata.page.block.container','{\"code\":\"content_top\"}',1,1,'2018-11-20 14:10:16','2018-11-20 14:10:16','content_top_4'),(34,NULL,62,'content','sonata.page.block.container','{\"code\":\"content\"}',1,1,'2018-11-20 14:10:16','2018-11-20 14:10:16','content_4'),(35,NULL,62,'content_bottom','sonata.page.block.container','{\"code\":\"content_bottom\"}',1,1,'2018-11-20 14:10:16','2018-11-20 14:10:16','content_bottom_4'),(36,NULL,62,'footer','sonata.page.block.container','{\"code\":\"footer\"}',1,1,'2018-11-20 14:10:16','2018-11-20 14:10:16','footer_4'),(37,NULL,63,'header','sonata.page.block.container','{\"code\":\"header\"}',1,1,'2018-11-20 14:10:30','2018-11-20 14:10:30','header_5'),(38,NULL,63,'content_top','sonata.page.block.container','{\"code\":\"content_top\"}',1,1,'2018-11-20 14:10:30','2018-11-20 14:10:30','content_top_5'),(39,NULL,63,'content','sonata.page.block.container','{\"code\":\"content\"}',1,1,'2018-11-20 14:10:30','2018-11-20 14:10:30','content_5'),(40,NULL,63,'content_bottom','sonata.page.block.container','{\"code\":\"content_bottom\"}',1,1,'2018-11-20 14:10:30','2018-11-20 14:10:30','content_bottom_5'),(41,NULL,63,'footer','sonata.page.block.container','{\"code\":\"footer\"}',1,1,'2018-11-20 14:10:30','2018-11-20 14:10:30','footer_5'),(42,NULL,50,'header','sonata.page.block.container','{\"code\":\"header\"}',1,1,'2018-11-20 14:13:18','2018-11-20 14:13:18','header_6'),(43,NULL,50,'content_top','sonata.page.block.container','{\"code\":\"content_top\"}',1,1,'2018-11-20 14:13:18','2018-11-20 14:13:18','content_top_6'),(44,NULL,50,'content','sonata.page.block.container','{\"code\":\"content\"}',1,1,'2018-11-20 14:13:18','2018-11-20 14:13:18','content_6'),(45,NULL,50,'content_bottom','sonata.page.block.container','{\"code\":\"content_bottom\"}',1,1,'2018-11-20 14:13:18','2018-11-20 14:13:18','content_bottom_6'),(46,NULL,50,'footer','sonata.page.block.container','{\"code\":\"footer\"}',1,1,'2018-11-20 14:13:18','2018-11-20 14:13:18','footer_6'),(47,14,33,'Banners','sonata.formatter.block.formatter','{\"format\":\"richhtml\",\"rawContent\":\"<div class=\\\"container default-padding\\\">\\r\\n<div class=\\\"row\\\">\\r\\n<div class=\\\"col-xs-12\\\">\\r\\n<div id=\\\"home-jumbo-box-wrapper\\\"><a class=\\\"jumbo-box jumbo-box-truck jumbo-box-half jumbo-box-arrow jumbo-box-arrow-left\\\" href=\\\"\\/warto-wiedziec\\/dostawa-i-platnosc\\\"><span><img alt=\\\"\\\" src=\\\"\\/img\\/shared\\/icon-truck.svg\\\" \\/> Darmowa dostawa kurierem <\\/span> <\\/a> <a class=\\\"jumbo-box jumbo-box-bon jumbo-box-half jumbo-box-arrow jumbo-box-arrow-right\\\" href=\\\"\\/warto-wiedziec\\/dostawa-i-platnosc\\\"> <span> <img alt=\\\"\\\" src=\\\"\\/img\\/shared\\/icon-bon.svg\\\" \\/> Zyskaj do 300 PLN w&nbsp;formie bonu <\\/span> <\\/a><\\/div>\\r\\n<\\/div>\\r\\n<\\/div>\\r\\n<\\/div>\",\"content\":\"<div class=\\\"container default-padding\\\">\\n<div class=\\\"row\\\">\\n<div class=\\\"col-xs-12\\\">\\n<div id=\\\"home-jumbo-box-wrapper\\\"><a class=\\\"jumbo-box jumbo-box-truck jumbo-box-half jumbo-box-arrow jumbo-box-arrow-left\\\" href=\\\"\\/warto-wiedziec\\/dostawa-i-platnosc\\\"><span><img alt=\\\"\\\" src=\\\"\\/img\\/shared\\/icon-truck.svg\\\" \\/> Darmowa dostawa kurierem <\\/span> <\\/a> <a class=\\\"jumbo-box jumbo-box-bon jumbo-box-half jumbo-box-arrow jumbo-box-arrow-right\\\" href=\\\"\\/warto-wiedziec\\/dostawa-i-platnosc\\\"> <span> <img alt=\\\"\\\" src=\\\"\\/img\\/shared\\/icon-bon.svg\\\" \\/> Zyskaj do 300 PLN w&nbsp;formie bonu <\\/span> <\\/a><\\/div>\\n<\\/div>\\n<\\/div>\\n<\\/div>\",\"template\":\"@SonataFormatter\\/Block\\/block_formatter.html.twig\"}',1,1,'2018-11-20 21:46:50','2019-01-16 13:16:48','banners'),(48,14,33,'Kategorie','sonata.offer.block.categories','{\"template\":\"@ApplicationSonataOffer\\/Block\\/offerCategories.html.twig\"}',1,2,'2018-11-20 21:57:40','2018-11-20 22:02:23','kategorie'),(50,14,33,'Projekty warte uwagi','sonata.formatter.block.formatter','{\"format\":\"richhtml\",\"rawContent\":\"<section class=\\\"container projects-items-swiper projects-items\\\" id=\\\"projects-worth-attention\\\">\\r\\n<header class=\\\"row with-square\\\">\\r\\n<h1 class=\\\"col-xs-12 default-headline\\\">Projekty <span>warte uwagi<\\/span><\\/h1>\\r\\n<\\/header>\\r\\n\\r\\n<div class=\\\"row\\\">\\r\\n<div class=\\\"col-xs-12\\\">\\r\\n<div class=\\\"swiper-button-prev swiper-button-prev-worth-attention\\\">&nbsp;<\\/div>\\r\\n\\r\\n<div class=\\\"swiper-button-next swiper-button-next-worth-attention\\\">&nbsp;<\\/div>\\r\\n\\r\\n<div class=\\\"projects-swiper projects-swiper-worth-attention swiper-container\\\">\\r\\n<div class=\\\"swiper-wrapper\\\">\\r\\n<div class=\\\"swiper-slide\\\">\\r\\n<article class=\\\"project-item\\\">\\r\\n<header>\\r\\n<h2>Szyper 6 dr-s<\\/h2>\\r\\n<\\/header>\\r\\n\\r\\n<div class=\\\"content\\\">\\r\\n<div class=\\\"thumb\\\" style=\\\"background-image:url(\\/img\\/temp\\/thumb-home-01.png)\\\"><span class=\\\"price\\\">1235 PLN<\\/span><\\/div>\\r\\n\\r\\n<div class=\\\"description\\\">\\r\\n<p>Powierzchnia: <strong>123m2<\\/strong><\\/p>\\r\\n\\r\\n<p>Parter + poddasze<\\/p>\\r\\n\\r\\n<p>Z gara\\u017cem, dom tradycyjny<\\/p>\\r\\n<\\/div>\\r\\n<\\/div>\\r\\n\\r\\n<footer><a href=\\\"\\\">Wi\\u0119cej<\\/a><\\/footer>\\r\\n<\\/article>\\r\\n<\\/div>\\r\\n\\r\\n<div class=\\\"swiper-slide\\\">\\r\\n<article class=\\\"project-item\\\">\\r\\n<header>\\r\\n<h2>Abra<\\/h2>\\r\\n<\\/header>\\r\\n\\r\\n<div class=\\\"content\\\">\\r\\n<div class=\\\"thumb\\\" style=\\\"background-image:url(\\/img\\/temp\\/thumb-home-02.png)\\\"><span class=\\\"price\\\">1235 PLN<\\/span><\\/div>\\r\\n\\r\\n<div class=\\\"description\\\">\\r\\n<p>Powierzchnia: <strong>123m2<\\/strong><\\/p>\\r\\n\\r\\n<p>Parter + poddasze<\\/p>\\r\\n\\r\\n<p>Z gara\\u017cem, dom tradycyjny<\\/p>\\r\\n<\\/div>\\r\\n<\\/div>\\r\\n\\r\\n<footer><a href=\\\"\\\">Wi\\u0119cej<\\/a><\\/footer>\\r\\n<\\/article>\\r\\n<\\/div>\\r\\n\\r\\n<div class=\\\"swiper-slide\\\">\\r\\n<article class=\\\"project-item\\\">\\r\\n<header>\\r\\n<h2>HOMEKONCEPT 26<\\/h2>\\r\\n<\\/header>\\r\\n\\r\\n<div class=\\\"content\\\">\\r\\n<div class=\\\"thumb\\\" style=\\\"background-image:url(\\/img\\/temp\\/thumb-home-03.png)\\\"><span class=\\\"price\\\">1235 PLN<\\/span><\\/div>\\r\\n\\r\\n<div class=\\\"description\\\">\\r\\n<p>Powierzchnia: <strong>123m2<\\/strong><\\/p>\\r\\n\\r\\n<p>Parter + poddasze<\\/p>\\r\\n\\r\\n<p>Z gara\\u017cem, dom tradycyjny<\\/p>\\r\\n<\\/div>\\r\\n<\\/div>\\r\\n\\r\\n<footer><a href=\\\"\\\">Wi\\u0119cej<\\/a><\\/footer>\\r\\n<\\/article>\\r\\n<\\/div>\\r\\n\\r\\n<div class=\\\"swiper-slide\\\">\\r\\n<article class=\\\"project-item\\\">\\r\\n<header>\\r\\n<h2>tk 9<\\/h2>\\r\\n<\\/header>\\r\\n\\r\\n<div class=\\\"content\\\">\\r\\n<div class=\\\"thumb\\\" style=\\\"background-image:url(\\/img\\/temp\\/thumb-home-04.png)\\\"><span class=\\\"price\\\">1235 PLN<\\/span><\\/div>\\r\\n\\r\\n<div class=\\\"description\\\">\\r\\n<p>Powierzchnia: <strong>123m2<\\/strong><\\/p>\\r\\n\\r\\n<p>Parter + poddasze<\\/p>\\r\\n\\r\\n<p>Z gara\\u017cem, dom tradycyjny<\\/p>\\r\\n<\\/div>\\r\\n<\\/div>\\r\\n\\r\\n<footer><a href=\\\"\\\">Wi\\u0119cej<\\/a><\\/footer>\\r\\n<\\/article>\\r\\n<\\/div>\\r\\n\\r\\n<div class=\\\"swiper-slide\\\">\\r\\n<article class=\\\"project-item\\\">\\r\\n<header>\\r\\n<h2>Szyper 6 dr-s<\\/h2>\\r\\n<\\/header>\\r\\n\\r\\n<div class=\\\"content\\\">\\r\\n<div class=\\\"thumb\\\" style=\\\"background-image:url(\\/img\\/temp\\/thumb-home-01.png)\\\"><span class=\\\"price\\\">1235 PLN<\\/span><\\/div>\\r\\n\\r\\n<div class=\\\"description\\\">\\r\\n<p>Powierzchnia: <strong>123m2<\\/strong><\\/p>\\r\\n\\r\\n<p>Parter + poddasze<\\/p>\\r\\n\\r\\n<p>Z gara\\u017cem, dom tradycyjny<\\/p>\\r\\n<\\/div>\\r\\n<\\/div>\\r\\n\\r\\n<footer><a href=\\\"\\\">Wi\\u0119cej<\\/a><\\/footer>\\r\\n<\\/article>\\r\\n<\\/div>\\r\\n\\r\\n<div class=\\\"swiper-slide\\\">\\r\\n<article class=\\\"project-item\\\">\\r\\n<header>\\r\\n<h2>Abra<\\/h2>\\r\\n<\\/header>\\r\\n\\r\\n<div class=\\\"content\\\">\\r\\n<div class=\\\"thumb\\\" style=\\\"background-image:url(\\/img\\/temp\\/thumb-home-02.png)\\\"><span class=\\\"price\\\">1235 PLN<\\/span><\\/div>\\r\\n\\r\\n<div class=\\\"description\\\">\\r\\n<p>Powierzchnia: <strong>123m2<\\/strong><\\/p>\\r\\n\\r\\n<p>Parter + poddasze<\\/p>\\r\\n\\r\\n<p>Z gara\\u017cem, dom tradycyjny<\\/p>\\r\\n<\\/div>\\r\\n<\\/div>\\r\\n\\r\\n<footer><a href=\\\"\\\">Wi\\u0119cej<\\/a><\\/footer>\\r\\n<\\/article>\\r\\n<\\/div>\\r\\n\\r\\n<div class=\\\"swiper-slide\\\">\\r\\n<article class=\\\"project-item\\\">\\r\\n<header>\\r\\n<h2>HOMEKONCEPT 26<\\/h2>\\r\\n<\\/header>\\r\\n\\r\\n<div class=\\\"content\\\">\\r\\n<div class=\\\"thumb\\\" style=\\\"background-image:url(\\/img\\/temp\\/thumb-home-03.png)\\\"><span class=\\\"price\\\">1235 PLN<\\/span><\\/div>\\r\\n\\r\\n<div class=\\\"description\\\">\\r\\n<p>Powierzchnia: <strong>123m2<\\/strong><\\/p>\\r\\n\\r\\n<p>Parter + poddasze<\\/p>\\r\\n\\r\\n<p>Z gara\\u017cem, dom tradycyjny<\\/p>\\r\\n<\\/div>\\r\\n<\\/div>\\r\\n\\r\\n<footer><a href=\\\"\\\">Wi\\u0119cej<\\/a><\\/footer>\\r\\n<\\/article>\\r\\n<\\/div>\\r\\n\\r\\n<div class=\\\"swiper-slide\\\">\\r\\n<article class=\\\"project-item\\\">\\r\\n<header>\\r\\n<h2>tk 9<\\/h2>\\r\\n<\\/header>\\r\\n\\r\\n<div class=\\\"content\\\">\\r\\n<div class=\\\"thumb\\\" style=\\\"background-image:url(\\/img\\/temp\\/thumb-home-04.png)\\\"><span class=\\\"price\\\">1235 PLN<\\/span><\\/div>\\r\\n\\r\\n<div class=\\\"description\\\">\\r\\n<p>Powierzchnia: <strong>123m2<\\/strong><\\/p>\\r\\n\\r\\n<p>Parter + poddasze<\\/p>\\r\\n\\r\\n<p>Z gara\\u017cem, dom tradycyjny<\\/p>\\r\\n<\\/div>\\r\\n<\\/div>\\r\\n\\r\\n<footer><a href=\\\"\\\">Wi\\u0119cej<\\/a><\\/footer>\\r\\n<\\/article>\\r\\n<\\/div>\\r\\n<\\/div>\\r\\n<\\/div>\\r\\n<\\/div>\\r\\n<\\/div>\\r\\n\\r\\n<div class=\\\"row\\\">\\r\\n<div class=\\\"col-xs-12\\\">\\r\\n<div class=\\\"buttons-holder buttons-holder-center default-padding\\\"><a class=\\\"button button-big button-wide button-filled button-filled-gray\\\" href=\\\"\\\">Zobacz wi\\u0119cej<\\/a><\\/div>\\r\\n<\\/div>\\r\\n<\\/div>\\r\\n<\\/section>\",\"content\":\"<section class=\\\"container projects-items-swiper projects-items\\\" id=\\\"projects-worth-attention\\\">\\n<header class=\\\"row with-square\\\">\\n<h1 class=\\\"col-xs-12 default-headline\\\">Projekty <span>warte uwagi<\\/span><\\/h1>\\n<\\/header>\\n\\n<div class=\\\"row\\\">\\n<div class=\\\"col-xs-12\\\">\\n<div class=\\\"swiper-button-prev swiper-button-prev-worth-attention\\\">&nbsp;<\\/div>\\n\\n<div class=\\\"swiper-button-next swiper-button-next-worth-attention\\\">&nbsp;<\\/div>\\n\\n<div class=\\\"projects-swiper projects-swiper-worth-attention swiper-container\\\">\\n<div class=\\\"swiper-wrapper\\\">\\n<div class=\\\"swiper-slide\\\">\\n<article class=\\\"project-item\\\">\\n<header>\\n<h2>Szyper 6 dr-s<\\/h2>\\n<\\/header>\\n\\n<div class=\\\"content\\\">\\n<div class=\\\"thumb\\\" style=\\\"background-image:url(\\/img\\/temp\\/thumb-home-01.png)\\\"><span class=\\\"price\\\">1235 PLN<\\/span><\\/div>\\n\\n<div class=\\\"description\\\">\\n<p>Powierzchnia: <strong>123m2<\\/strong><\\/p>\\n\\n<p>Parter + poddasze<\\/p>\\n\\n<p>Z gara\\u017cem, dom tradycyjny<\\/p>\\n<\\/div>\\n<\\/div>\\n\\n<footer><a href=\\\"\\\">Wi\\u0119cej<\\/a><\\/footer>\\n<\\/article>\\n<\\/div>\\n\\n<div class=\\\"swiper-slide\\\">\\n<article class=\\\"project-item\\\">\\n<header>\\n<h2>Abra<\\/h2>\\n<\\/header>\\n\\n<div class=\\\"content\\\">\\n<div class=\\\"thumb\\\" style=\\\"background-image:url(\\/img\\/temp\\/thumb-home-02.png)\\\"><span class=\\\"price\\\">1235 PLN<\\/span><\\/div>\\n\\n<div class=\\\"description\\\">\\n<p>Powierzchnia: <strong>123m2<\\/strong><\\/p>\\n\\n<p>Parter + poddasze<\\/p>\\n\\n<p>Z gara\\u017cem, dom tradycyjny<\\/p>\\n<\\/div>\\n<\\/div>\\n\\n<footer><a href=\\\"\\\">Wi\\u0119cej<\\/a><\\/footer>\\n<\\/article>\\n<\\/div>\\n\\n<div class=\\\"swiper-slide\\\">\\n<article class=\\\"project-item\\\">\\n<header>\\n<h2>HOMEKONCEPT 26<\\/h2>\\n<\\/header>\\n\\n<div class=\\\"content\\\">\\n<div class=\\\"thumb\\\" style=\\\"background-image:url(\\/img\\/temp\\/thumb-home-03.png)\\\"><span class=\\\"price\\\">1235 PLN<\\/span><\\/div>\\n\\n<div class=\\\"description\\\">\\n<p>Powierzchnia: <strong>123m2<\\/strong><\\/p>\\n\\n<p>Parter + poddasze<\\/p>\\n\\n<p>Z gara\\u017cem, dom tradycyjny<\\/p>\\n<\\/div>\\n<\\/div>\\n\\n<footer><a href=\\\"\\\">Wi\\u0119cej<\\/a><\\/footer>\\n<\\/article>\\n<\\/div>\\n\\n<div class=\\\"swiper-slide\\\">\\n<article class=\\\"project-item\\\">\\n<header>\\n<h2>tk 9<\\/h2>\\n<\\/header>\\n\\n<div class=\\\"content\\\">\\n<div class=\\\"thumb\\\" style=\\\"background-image:url(\\/img\\/temp\\/thumb-home-04.png)\\\"><span class=\\\"price\\\">1235 PLN<\\/span><\\/div>\\n\\n<div class=\\\"description\\\">\\n<p>Powierzchnia: <strong>123m2<\\/strong><\\/p>\\n\\n<p>Parter + poddasze<\\/p>\\n\\n<p>Z gara\\u017cem, dom tradycyjny<\\/p>\\n<\\/div>\\n<\\/div>\\n\\n<footer><a href=\\\"\\\">Wi\\u0119cej<\\/a><\\/footer>\\n<\\/article>\\n<\\/div>\\n\\n<div class=\\\"swiper-slide\\\">\\n<article class=\\\"project-item\\\">\\n<header>\\n<h2>Szyper 6 dr-s<\\/h2>\\n<\\/header>\\n\\n<div class=\\\"content\\\">\\n<div class=\\\"thumb\\\" style=\\\"background-image:url(\\/img\\/temp\\/thumb-home-01.png)\\\"><span class=\\\"price\\\">1235 PLN<\\/span><\\/div>\\n\\n<div class=\\\"description\\\">\\n<p>Powierzchnia: <strong>123m2<\\/strong><\\/p>\\n\\n<p>Parter + poddasze<\\/p>\\n\\n<p>Z gara\\u017cem, dom tradycyjny<\\/p>\\n<\\/div>\\n<\\/div>\\n\\n<footer><a href=\\\"\\\">Wi\\u0119cej<\\/a><\\/footer>\\n<\\/article>\\n<\\/div>\\n\\n<div class=\\\"swiper-slide\\\">\\n<article class=\\\"project-item\\\">\\n<header>\\n<h2>Abra<\\/h2>\\n<\\/header>\\n\\n<div class=\\\"content\\\">\\n<div class=\\\"thumb\\\" style=\\\"background-image:url(\\/img\\/temp\\/thumb-home-02.png)\\\"><span class=\\\"price\\\">1235 PLN<\\/span><\\/div>\\n\\n<div class=\\\"description\\\">\\n<p>Powierzchnia: <strong>123m2<\\/strong><\\/p>\\n\\n<p>Parter + poddasze<\\/p>\\n\\n<p>Z gara\\u017cem, dom tradycyjny<\\/p>\\n<\\/div>\\n<\\/div>\\n\\n<footer><a href=\\\"\\\">Wi\\u0119cej<\\/a><\\/footer>\\n<\\/article>\\n<\\/div>\\n\\n<div class=\\\"swiper-slide\\\">\\n<article class=\\\"project-item\\\">\\n<header>\\n<h2>HOMEKONCEPT 26<\\/h2>\\n<\\/header>\\n\\n<div class=\\\"content\\\">\\n<div class=\\\"thumb\\\" style=\\\"background-image:url(\\/img\\/temp\\/thumb-home-03.png)\\\"><span class=\\\"price\\\">1235 PLN<\\/span><\\/div>\\n\\n<div class=\\\"description\\\">\\n<p>Powierzchnia: <strong>123m2<\\/strong><\\/p>\\n\\n<p>Parter + poddasze<\\/p>\\n\\n<p>Z gara\\u017cem, dom tradycyjny<\\/p>\\n<\\/div>\\n<\\/div>\\n\\n<footer><a href=\\\"\\\">Wi\\u0119cej<\\/a><\\/footer>\\n<\\/article>\\n<\\/div>\\n\\n<div class=\\\"swiper-slide\\\">\\n<article class=\\\"project-item\\\">\\n<header>\\n<h2>tk 9<\\/h2>\\n<\\/header>\\n\\n<div class=\\\"content\\\">\\n<div class=\\\"thumb\\\" style=\\\"background-image:url(\\/img\\/temp\\/thumb-home-04.png)\\\"><span class=\\\"price\\\">1235 PLN<\\/span><\\/div>\\n\\n<div class=\\\"description\\\">\\n<p>Powierzchnia: <strong>123m2<\\/strong><\\/p>\\n\\n<p>Parter + poddasze<\\/p>\\n\\n<p>Z gara\\u017cem, dom tradycyjny<\\/p>\\n<\\/div>\\n<\\/div>\\n\\n<footer><a href=\\\"\\\">Wi\\u0119cej<\\/a><\\/footer>\\n<\\/article>\\n<\\/div>\\n<\\/div>\\n<\\/div>\\n<\\/div>\\n<\\/div>\\n\\n<div class=\\\"row\\\">\\n<div class=\\\"col-xs-12\\\">\\n<div class=\\\"buttons-holder buttons-holder-center default-padding\\\"><a class=\\\"button button-big button-wide button-filled button-filled-gray\\\" href=\\\"\\\">Zobacz wi\\u0119cej<\\/a><\\/div>\\n<\\/div>\\n<\\/div>\\n<\\/section>\",\"template\":\"@SonataFormatter\\/Block\\/block_formatter.html.twig\"}',1,3,'2018-11-20 23:01:19','2018-11-20 23:03:01','projekty_warte_uwagi'),(51,14,33,'Projekty espiro','sonata.formatter.block.formatter','{\"format\":\"richhtml\",\"rawContent\":\"<section class=\\\"container projects-items-swiper projects-items\\\" id=\\\"projects-espiro-property\\\">\\r\\n<header class=\\\"row with-square\\\">\\r\\n<h1 class=\\\"col-xs-12 default-headline\\\">Realizacje <span>Espiro&nbsp;Property<\\/span><\\/h1>\\r\\n<\\/header>\\r\\n\\r\\n<div class=\\\"row\\\">\\r\\n<div class=\\\"col-xs-12\\\">\\r\\n<div class=\\\"swiper-button-prev swiper-button-prev-espiro-property\\\">&nbsp;<\\/div>\\r\\n\\r\\n<div class=\\\"swiper-button-next swiper-button-next-espiro-property\\\">&nbsp;<\\/div>\\r\\n\\r\\n<div class=\\\"project-swiper-espiro-property projects-swiper swiper-container\\\">\\r\\n<div class=\\\"swiper-wrapper\\\">\\r\\n<div class=\\\"swiper-slide\\\">\\r\\n<article class=\\\"project-item\\\">\\r\\n<header>\\r\\n<h2>dom w\\u015br\\u00f3d malin<\\/h2>\\r\\n<\\/header>\\r\\n\\r\\n<div class=\\\"content\\\">\\r\\n<div class=\\\"thumb\\\" style=\\\"background-image:url(\\/img\\/temp\\/thumb-home-05.png)\\\"><span class=\\\"location\\\">Radzymin<\\/span><\\/div>\\r\\n\\r\\n<div class=\\\"description\\\">\\r\\n<p>Powierzchnia: <strong>123m2<\\/strong><\\/p>\\r\\n\\r\\n<p>Parter + poddasze<\\/p>\\r\\n\\r\\n<p>Z gara\\u017cem, dom tradycyjny<\\/p>\\r\\n<\\/div>\\r\\n<\\/div>\\r\\n\\r\\n<footer><a class=\\\"wide\\\" href=\\\"\\\">Wi\\u0119cej<\\/a><\\/footer>\\r\\n<\\/article>\\r\\n<\\/div>\\r\\n\\r\\n<div class=\\\"swiper-slide\\\">\\r\\n<article class=\\\"project-item\\\">\\r\\n<header>\\r\\n<h2>PROJEKT INDYWIDUALNY<\\/h2>\\r\\n<\\/header>\\r\\n\\r\\n<div class=\\\"content\\\">\\r\\n<div class=\\\"thumb\\\" style=\\\"background-image:url(\\/img\\/temp\\/thumb-home-06.png)\\\"><span class=\\\"location\\\">Warszawa Bia\\u0142o\\u0142\\u0119ka<\\/span><\\/div>\\r\\n\\r\\n<div class=\\\"description\\\">\\r\\n<p>Powierzchnia: <strong>123m2<\\/strong><\\/p>\\r\\n\\r\\n<p>Parter + poddasze<\\/p>\\r\\n\\r\\n<p>Z gara\\u017cem, dom tradycyjny<\\/p>\\r\\n<\\/div>\\r\\n<\\/div>\\r\\n\\r\\n<footer><a class=\\\"wide\\\" href=\\\"\\\">Wi\\u0119cej<\\/a><\\/footer>\\r\\n<\\/article>\\r\\n<\\/div>\\r\\n\\r\\n<div class=\\\"swiper-slide\\\">\\r\\n<article class=\\\"project-item\\\">\\r\\n<header>\\r\\n<h2>z charakterem 4<\\/h2>\\r\\n<\\/header>\\r\\n\\r\\n<div class=\\\"content\\\">\\r\\n<div class=\\\"thumb\\\" style=\\\"background-image:url(\\/img\\/temp\\/thumb-home-07.png)\\\"><span class=\\\"location\\\">Marian\\u00f3w<\\/span><\\/div>\\r\\n\\r\\n<div class=\\\"description\\\">\\r\\n<p>Powierzchnia: <strong>123m2<\\/strong><\\/p>\\r\\n\\r\\n<p>Parter + poddasze<\\/p>\\r\\n\\r\\n<p>Z gara\\u017cem, dom tradycyjny<\\/p>\\r\\n<\\/div>\\r\\n<\\/div>\\r\\n\\r\\n<footer><a class=\\\"wide\\\" href=\\\"\\\">Wi\\u0119cej<\\/a><\\/footer>\\r\\n<\\/article>\\r\\n<\\/div>\\r\\n\\r\\n<div class=\\\"swiper-slide\\\">\\r\\n<article class=\\\"project-item\\\">\\r\\n<header>\\r\\n<h2>PROJEKT INDYWIDUALNY<\\/h2>\\r\\n<\\/header>\\r\\n\\r\\n<div class=\\\"content\\\">\\r\\n<div class=\\\"thumb\\\" style=\\\"background-image:url(\\/img\\/temp\\/thumb-home-08.png)\\\"><span class=\\\"location\\\">Podkowa Le\\u015bna<\\/span><\\/div>\\r\\n\\r\\n<div class=\\\"description\\\">\\r\\n<p>Powierzchnia: <strong>123m2<\\/strong><\\/p>\\r\\n\\r\\n<p>Parter + poddasze<\\/p>\\r\\n\\r\\n<p>Z gara\\u017cem, dom tradycyjny<\\/p>\\r\\n<\\/div>\\r\\n<\\/div>\\r\\n\\r\\n<footer><a class=\\\"wide\\\" href=\\\"\\\">Wi\\u0119cej<\\/a><\\/footer>\\r\\n<\\/article>\\r\\n<\\/div>\\r\\n\\r\\n<div class=\\\"swiper-slide\\\">\\r\\n<article class=\\\"project-item\\\">\\r\\n<header>\\r\\n<h2>Szyper 6 dr-s<\\/h2>\\r\\n<\\/header>\\r\\n\\r\\n<div class=\\\"content\\\">\\r\\n<div class=\\\"thumb\\\" style=\\\"background-image:url(\\/img\\/temp\\/thumb-home-01.png)\\\"><span class=\\\"price\\\">1235 PLN<\\/span><\\/div>\\r\\n\\r\\n<div class=\\\"description\\\">\\r\\n<p>Powierzchnia: <strong>123m2<\\/strong><\\/p>\\r\\n\\r\\n<p>Parter + poddasze<\\/p>\\r\\n\\r\\n<p>Z gara\\u017cem, dom tradycyjny<\\/p>\\r\\n<\\/div>\\r\\n<\\/div>\\r\\n\\r\\n<footer><a class=\\\"wide\\\" href=\\\"\\\">Wi\\u0119cej<\\/a><\\/footer>\\r\\n<\\/article>\\r\\n<\\/div>\\r\\n\\r\\n<div class=\\\"swiper-slide\\\">\\r\\n<article class=\\\"project-item\\\">\\r\\n<header>\\r\\n<h2>Abra<\\/h2>\\r\\n<\\/header>\\r\\n\\r\\n<div class=\\\"content\\\">\\r\\n<div class=\\\"thumb\\\" style=\\\"background-image:url(\\/img\\/temp\\/thumb-home-02.png)\\\"><span class=\\\"price\\\">1235 PLN<\\/span><\\/div>\\r\\n\\r\\n<div class=\\\"description\\\">\\r\\n<p>Powierzchnia: <strong>123m2<\\/strong><\\/p>\\r\\n\\r\\n<p>Parter + poddasze<\\/p>\\r\\n\\r\\n<p>Z gara\\u017cem, dom tradycyjny<\\/p>\\r\\n<\\/div>\\r\\n<\\/div>\\r\\n\\r\\n<footer><a class=\\\"wide\\\" href=\\\"\\\">Wi\\u0119cej<\\/a><\\/footer>\\r\\n<\\/article>\\r\\n<\\/div>\\r\\n\\r\\n<div class=\\\"swiper-slide\\\">\\r\\n<article class=\\\"project-item\\\">\\r\\n<header>\\r\\n<h2>HOMEKONCEPT 26<\\/h2>\\r\\n<\\/header>\\r\\n\\r\\n<div class=\\\"content\\\">\\r\\n<div class=\\\"thumb\\\" style=\\\"background-image:url(\\/img\\/temp\\/thumb-home-03.png)\\\"><span class=\\\"price\\\">1235 PLN<\\/span><\\/div>\\r\\n\\r\\n<div class=\\\"description\\\">\\r\\n<p>Powierzchnia: <strong>123m2<\\/strong><\\/p>\\r\\n\\r\\n<p>Parter + poddasze<\\/p>\\r\\n\\r\\n<p>Z gara\\u017cem, dom tradycyjny<\\/p>\\r\\n<\\/div>\\r\\n<\\/div>\\r\\n\\r\\n<footer><a class=\\\"wide\\\" href=\\\"\\\">Wi\\u0119cej<\\/a><\\/footer>\\r\\n<\\/article>\\r\\n<\\/div>\\r\\n\\r\\n<div class=\\\"swiper-slide\\\">\\r\\n<article class=\\\"project-item\\\">\\r\\n<header>\\r\\n<h2>tk 9<\\/h2>\\r\\n<\\/header>\\r\\n\\r\\n<div class=\\\"content\\\">\\r\\n<div class=\\\"thumb\\\" style=\\\"background-image:url(\\/img\\/temp\\/thumb-home-04.png)\\\"><span class=\\\"price\\\">1235 PLN<\\/span><\\/div>\\r\\n\\r\\n<div class=\\\"description\\\">\\r\\n<p>Powierzchnia: <strong>123m2<\\/strong><\\/p>\\r\\n\\r\\n<p>Parter + poddasze<\\/p>\\r\\n\\r\\n<p>Z gara\\u017cem, dom tradycyjny<\\/p>\\r\\n<\\/div>\\r\\n<\\/div>\\r\\n\\r\\n<footer><a class=\\\"wide\\\" href=\\\"\\\">Wi\\u0119cej<\\/a><\\/footer>\\r\\n<\\/article>\\r\\n<\\/div>\\r\\n<\\/div>\\r\\n<\\/div>\\r\\n<\\/div>\\r\\n<\\/div>\\r\\n\\r\\n<div class=\\\"row\\\">\\r\\n<div class=\\\"col-xs-12\\\">\\r\\n<div class=\\\"buttons-holder buttons-holder-center default-padding\\\"><a class=\\\"button button-big button-wide button-filled button-filled-gray\\\" href=\\\"\\\">Zobacz wi\\u0119cej<\\/a> <a class=\\\"button button-big button-wide button-filled button-filled-gray\\\" href=\\\"\\\">Odwied\\u017a Espiro<\\/a><\\/div>\\r\\n<\\/div>\\r\\n<\\/div>\\r\\n<\\/section>\",\"content\":\"<section class=\\\"container projects-items-swiper projects-items\\\" id=\\\"projects-espiro-property\\\">\\n<header class=\\\"row with-square\\\">\\n<h1 class=\\\"col-xs-12 default-headline\\\">Realizacje <span>Espiro&nbsp;Property<\\/span><\\/h1>\\n<\\/header>\\n\\n<div class=\\\"row\\\">\\n<div class=\\\"col-xs-12\\\">\\n<div class=\\\"swiper-button-prev swiper-button-prev-espiro-property\\\">&nbsp;<\\/div>\\n\\n<div class=\\\"swiper-button-next swiper-button-next-espiro-property\\\">&nbsp;<\\/div>\\n\\n<div class=\\\"project-swiper-espiro-property projects-swiper swiper-container\\\">\\n<div class=\\\"swiper-wrapper\\\">\\n<div class=\\\"swiper-slide\\\">\\n<article class=\\\"project-item\\\">\\n<header>\\n<h2>dom w\\u015br\\u00f3d malin<\\/h2>\\n<\\/header>\\n\\n<div class=\\\"content\\\">\\n<div class=\\\"thumb\\\" style=\\\"background-image:url(\\/img\\/temp\\/thumb-home-05.png)\\\"><span class=\\\"location\\\">Radzymin<\\/span><\\/div>\\n\\n<div class=\\\"description\\\">\\n<p>Powierzchnia: <strong>123m2<\\/strong><\\/p>\\n\\n<p>Parter + poddasze<\\/p>\\n\\n<p>Z gara\\u017cem, dom tradycyjny<\\/p>\\n<\\/div>\\n<\\/div>\\n\\n<footer><a class=\\\"wide\\\" href=\\\"\\\">Wi\\u0119cej<\\/a><\\/footer>\\n<\\/article>\\n<\\/div>\\n\\n<div class=\\\"swiper-slide\\\">\\n<article class=\\\"project-item\\\">\\n<header>\\n<h2>PROJEKT INDYWIDUALNY<\\/h2>\\n<\\/header>\\n\\n<div class=\\\"content\\\">\\n<div class=\\\"thumb\\\" style=\\\"background-image:url(\\/img\\/temp\\/thumb-home-06.png)\\\"><span class=\\\"location\\\">Warszawa Bia\\u0142o\\u0142\\u0119ka<\\/span><\\/div>\\n\\n<div class=\\\"description\\\">\\n<p>Powierzchnia: <strong>123m2<\\/strong><\\/p>\\n\\n<p>Parter + poddasze<\\/p>\\n\\n<p>Z gara\\u017cem, dom tradycyjny<\\/p>\\n<\\/div>\\n<\\/div>\\n\\n<footer><a class=\\\"wide\\\" href=\\\"\\\">Wi\\u0119cej<\\/a><\\/footer>\\n<\\/article>\\n<\\/div>\\n\\n<div class=\\\"swiper-slide\\\">\\n<article class=\\\"project-item\\\">\\n<header>\\n<h2>z charakterem 4<\\/h2>\\n<\\/header>\\n\\n<div class=\\\"content\\\">\\n<div class=\\\"thumb\\\" style=\\\"background-image:url(\\/img\\/temp\\/thumb-home-07.png)\\\"><span class=\\\"location\\\">Marian\\u00f3w<\\/span><\\/div>\\n\\n<div class=\\\"description\\\">\\n<p>Powierzchnia: <strong>123m2<\\/strong><\\/p>\\n\\n<p>Parter + poddasze<\\/p>\\n\\n<p>Z gara\\u017cem, dom tradycyjny<\\/p>\\n<\\/div>\\n<\\/div>\\n\\n<footer><a class=\\\"wide\\\" href=\\\"\\\">Wi\\u0119cej<\\/a><\\/footer>\\n<\\/article>\\n<\\/div>\\n\\n<div class=\\\"swiper-slide\\\">\\n<article class=\\\"project-item\\\">\\n<header>\\n<h2>PROJEKT INDYWIDUALNY<\\/h2>\\n<\\/header>\\n\\n<div class=\\\"content\\\">\\n<div class=\\\"thumb\\\" style=\\\"background-image:url(\\/img\\/temp\\/thumb-home-08.png)\\\"><span class=\\\"location\\\">Podkowa Le\\u015bna<\\/span><\\/div>\\n\\n<div class=\\\"description\\\">\\n<p>Powierzchnia: <strong>123m2<\\/strong><\\/p>\\n\\n<p>Parter + poddasze<\\/p>\\n\\n<p>Z gara\\u017cem, dom tradycyjny<\\/p>\\n<\\/div>\\n<\\/div>\\n\\n<footer><a class=\\\"wide\\\" href=\\\"\\\">Wi\\u0119cej<\\/a><\\/footer>\\n<\\/article>\\n<\\/div>\\n\\n<div class=\\\"swiper-slide\\\">\\n<article class=\\\"project-item\\\">\\n<header>\\n<h2>Szyper 6 dr-s<\\/h2>\\n<\\/header>\\n\\n<div class=\\\"content\\\">\\n<div class=\\\"thumb\\\" style=\\\"background-image:url(\\/img\\/temp\\/thumb-home-01.png)\\\"><span class=\\\"price\\\">1235 PLN<\\/span><\\/div>\\n\\n<div class=\\\"description\\\">\\n<p>Powierzchnia: <strong>123m2<\\/strong><\\/p>\\n\\n<p>Parter + poddasze<\\/p>\\n\\n<p>Z gara\\u017cem, dom tradycyjny<\\/p>\\n<\\/div>\\n<\\/div>\\n\\n<footer><a class=\\\"wide\\\" href=\\\"\\\">Wi\\u0119cej<\\/a><\\/footer>\\n<\\/article>\\n<\\/div>\\n\\n<div class=\\\"swiper-slide\\\">\\n<article class=\\\"project-item\\\">\\n<header>\\n<h2>Abra<\\/h2>\\n<\\/header>\\n\\n<div class=\\\"content\\\">\\n<div class=\\\"thumb\\\" style=\\\"background-image:url(\\/img\\/temp\\/thumb-home-02.png)\\\"><span class=\\\"price\\\">1235 PLN<\\/span><\\/div>\\n\\n<div class=\\\"description\\\">\\n<p>Powierzchnia: <strong>123m2<\\/strong><\\/p>\\n\\n<p>Parter + poddasze<\\/p>\\n\\n<p>Z gara\\u017cem, dom tradycyjny<\\/p>\\n<\\/div>\\n<\\/div>\\n\\n<footer><a class=\\\"wide\\\" href=\\\"\\\">Wi\\u0119cej<\\/a><\\/footer>\\n<\\/article>\\n<\\/div>\\n\\n<div class=\\\"swiper-slide\\\">\\n<article class=\\\"project-item\\\">\\n<header>\\n<h2>HOMEKONCEPT 26<\\/h2>\\n<\\/header>\\n\\n<div class=\\\"content\\\">\\n<div class=\\\"thumb\\\" style=\\\"background-image:url(\\/img\\/temp\\/thumb-home-03.png)\\\"><span class=\\\"price\\\">1235 PLN<\\/span><\\/div>\\n\\n<div class=\\\"description\\\">\\n<p>Powierzchnia: <strong>123m2<\\/strong><\\/p>\\n\\n<p>Parter + poddasze<\\/p>\\n\\n<p>Z gara\\u017cem, dom tradycyjny<\\/p>\\n<\\/div>\\n<\\/div>\\n\\n<footer><a class=\\\"wide\\\" href=\\\"\\\">Wi\\u0119cej<\\/a><\\/footer>\\n<\\/article>\\n<\\/div>\\n\\n<div class=\\\"swiper-slide\\\">\\n<article class=\\\"project-item\\\">\\n<header>\\n<h2>tk 9<\\/h2>\\n<\\/header>\\n\\n<div class=\\\"content\\\">\\n<div class=\\\"thumb\\\" style=\\\"background-image:url(\\/img\\/temp\\/thumb-home-04.png)\\\"><span class=\\\"price\\\">1235 PLN<\\/span><\\/div>\\n\\n<div class=\\\"description\\\">\\n<p>Powierzchnia: <strong>123m2<\\/strong><\\/p>\\n\\n<p>Parter + poddasze<\\/p>\\n\\n<p>Z gara\\u017cem, dom tradycyjny<\\/p>\\n<\\/div>\\n<\\/div>\\n\\n<footer><a class=\\\"wide\\\" href=\\\"\\\">Wi\\u0119cej<\\/a><\\/footer>\\n<\\/article>\\n<\\/div>\\n<\\/div>\\n<\\/div>\\n<\\/div>\\n<\\/div>\\n\\n<div class=\\\"row\\\">\\n<div class=\\\"col-xs-12\\\">\\n<div class=\\\"buttons-holder buttons-holder-center default-padding\\\"><a class=\\\"button button-big button-wide button-filled button-filled-gray\\\" href=\\\"\\\">Zobacz wi\\u0119cej<\\/a> <a class=\\\"button button-big button-wide button-filled button-filled-gray\\\" href=\\\"\\\">Odwied\\u017a Espiro<\\/a><\\/div>\\n<\\/div>\\n<\\/div>\\n<\\/section>\",\"template\":\"@SonataFormatter\\/Block\\/block_formatter.html.twig\"}',1,4,'2018-11-20 23:07:09','2018-11-20 23:07:09','projekty_espiro'),(52,NULL,43,'header','sonata.page.block.container','{\"code\":\"header\"}',1,1,'2018-11-25 21:18:01','2018-11-25 21:18:01','header_7'),(53,NULL,43,'content_top','sonata.page.block.container','{\"code\":\"content_top\"}',1,1,'2018-11-25 21:18:01','2018-11-25 21:18:01','content_top_7'),(54,NULL,43,'content','sonata.page.block.container','{\"code\":\"content\"}',1,1,'2018-11-25 21:18:01','2018-11-25 21:18:01','content_7'),(55,NULL,43,'content_bottom','sonata.page.block.container','{\"code\":\"content_bottom\"}',1,1,'2018-11-25 21:18:01','2018-11-25 21:18:01','content_bottom_7'),(56,NULL,43,'footer','sonata.page.block.container','{\"code\":\"footer\"}',1,1,'2018-11-25 21:18:01','2018-11-25 21:18:01','footer_7'),(57,NULL,66,'header','sonata.page.block.container','{\"code\":\"header\"}',1,1,'2018-11-25 21:46:43','2018-11-25 21:46:43','header_8'),(58,NULL,66,'content_top','sonata.page.block.container','{\"code\":\"content_top\"}',1,1,'2018-11-25 21:46:43','2018-11-25 21:46:43','content_top_8'),(59,NULL,66,'content','sonata.page.block.container','{\"code\":\"content\"}',1,1,'2018-11-25 21:46:43','2018-11-25 21:46:43','content_8'),(60,NULL,66,'content_bottom','sonata.page.block.container','{\"code\":\"content_bottom\"}',1,1,'2018-11-25 21:46:43','2018-11-25 21:46:43','content_bottom_8'),(61,NULL,66,'footer','sonata.page.block.container','{\"code\":\"footer\"}',1,1,'2018-11-25 21:46:43','2018-11-25 21:46:43','footer_8'),(62,NULL,60,'header','sonata.page.block.container','{\"code\":\"header\"}',1,1,'2019-01-16 08:10:16','2019-01-16 08:10:16','header_9'),(63,NULL,60,'content_top','sonata.page.block.container','{\"code\":\"content_top\"}',1,1,'2019-01-16 08:10:16','2019-01-16 08:10:16','content_top_9'),(64,NULL,60,'content','sonata.page.block.container','{\"code\":\"content\"}',1,1,'2019-01-16 08:10:16','2019-01-16 08:10:16','content_9'),(65,NULL,60,'content_bottom','sonata.page.block.container','{\"code\":\"content_bottom\"}',1,1,'2019-01-16 08:10:16','2019-01-16 08:10:16','content_bottom_9'),(66,NULL,60,'footer','sonata.page.block.container','{\"code\":\"footer\"}',1,1,'2019-01-16 08:10:16','2019-01-16 08:10:16','footer_9'),(72,NULL,64,'header','sonata.page.block.container','{\"code\":\"header\"}',1,1,'2019-01-16 13:17:17','2019-01-16 13:17:17','header_10'),(73,NULL,64,'content_top','sonata.page.block.container','{\"code\":\"content_top\"}',1,1,'2019-01-16 13:17:17','2019-01-16 13:17:17','content_top_10'),(74,NULL,64,'content','sonata.page.block.container','{\"code\":\"content\"}',1,1,'2019-01-16 13:17:17','2019-01-16 13:17:17','content_10'),(75,NULL,64,'content_bottom','sonata.page.block.container','{\"code\":\"content_bottom\"}',1,1,'2019-01-16 13:17:17','2019-01-16 13:17:17','content_bottom_10'),(76,NULL,64,'footer','sonata.page.block.container','{\"code\":\"footer\"}',1,1,'2019-01-16 13:17:17','2019-01-16 13:17:17','footer_10');
/*!40000 ALTER TABLE `page__block` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `page__page`
--

DROP TABLE IF EXISTS `page__page`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `page__page` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `site_id` int(11) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `target_id` int(11) DEFAULT NULL,
  `route_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `page_alias` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `position` int(11) NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `decorate` tinyint(1) NOT NULL,
  `edited` tinyint(1) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `slug` longtext COLLATE utf8_unicode_ci,
  `url` longtext COLLATE utf8_unicode_ci,
  `custom_url` longtext COLLATE utf8_unicode_ci,
  `request_method` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `meta_keyword` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `meta_description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `javascript` longtext COLLATE utf8_unicode_ci,
  `stylesheet` longtext COLLATE utf8_unicode_ci,
  `raw_headers` longtext COLLATE utf8_unicode_ci,
  `template` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_2FAE39EDF6BD1646` (`site_id`),
  KEY `IDX_2FAE39ED727ACA70` (`parent_id`),
  KEY `IDX_2FAE39ED158E0B66` (`target_id`),
  CONSTRAINT `FK_2FAE39ED158E0B66` FOREIGN KEY (`target_id`) REFERENCES `page__page` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_2FAE39ED727ACA70` FOREIGN KEY (`parent_id`) REFERENCES `page__page` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_2FAE39EDF6BD1646` FOREIGN KEY (`site_id`) REFERENCES `page__site` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=70 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `page__page`
--

LOCK TABLES `page__page` WRITE;
/*!40000 ALTER TABLE `page__page` DISABLE KEYS */;
INSERT INTO `page__page` VALUES (33,4,NULL,NULL,'page_slug',NULL,'sonata.page.service.default',1,1,1,1,'Start',NULL,'/',NULL,'GET|POST|HEAD|DELETE|PUT','Domo','Domo','Domo',NULL,NULL,NULL,'homepage','2018-11-15 21:46:37','2018-11-20 21:46:50'),(34,4,33,NULL,'sonata_cache_symfony',NULL,NULL,1,1,1,1,'sonata_cache_symfony','sonata-cache-symfony-token-type','/sonata/cache/symfony/{token}/{type}',NULL,'GET|POST|HEAD|DELETE|PUT',NULL,NULL,NULL,NULL,NULL,NULL,'default','2018-11-15 21:48:20','2018-11-15 21:48:20'),(35,4,33,NULL,'sonata_page_exceptions_list',NULL,NULL,1,1,1,1,'sonata_page_exceptions_list','exceptions-list','/exceptions/list',NULL,'GET|POST|HEAD|DELETE|PUT',NULL,NULL,NULL,NULL,NULL,NULL,'default','2018-11-15 21:48:20','2018-11-15 21:48:20'),(36,4,33,NULL,'sonata_page_exceptions_edit',NULL,NULL,1,1,1,1,'sonata_page_exceptions_edit','exceptions-edit-code','/exceptions/edit/{code}',NULL,'GET|POST|HEAD|DELETE|PUT',NULL,NULL,NULL,NULL,NULL,NULL,'default','2018-11-15 21:48:20','2018-11-15 21:48:20'),(37,4,33,NULL,'sonata_media_gallery_index',NULL,NULL,1,1,1,1,'sonata_media_gallery_index','media-gallery','/media/gallery/',NULL,'GET|POST|HEAD|DELETE|PUT',NULL,NULL,NULL,NULL,NULL,NULL,'default','2018-11-15 21:48:20','2018-11-15 21:48:20'),(38,4,33,NULL,'sonata_media_gallery_view',NULL,NULL,1,1,1,1,'sonata_media_gallery_view','media-gallery-view-id','/media/gallery/view/{id}',NULL,'GET|POST|HEAD|DELETE|PUT',NULL,NULL,NULL,NULL,NULL,NULL,'default','2018-11-15 21:48:20','2018-11-15 21:48:20'),(39,4,33,NULL,'sonata_media_view',NULL,NULL,1,1,1,1,'sonata_media_view','media-view-id-format','/media/view/{id}/{format}',NULL,'GET|POST|HEAD|DELETE|PUT',NULL,NULL,NULL,NULL,NULL,NULL,'default','2018-11-15 21:48:20','2018-11-15 21:48:20'),(40,4,33,NULL,'sonata_media_download',NULL,NULL,1,1,1,1,'sonata_media_download','media-download-id-format','/media/download/{id}/{format}',NULL,'GET|POST|HEAD|DELETE|PUT',NULL,NULL,NULL,NULL,NULL,NULL,'default','2018-11-15 21:48:20','2018-11-15 21:48:20'),(41,4,33,NULL,'liip_imagine_filter_runtime',NULL,NULL,1,1,1,1,'liip_imagine_filter_runtime','media-cache-resolve-filter-rc-hash-path','/media/cache/resolve/{filter}/rc/{hash}/{path}',NULL,'GET|POST|HEAD|DELETE|PUT',NULL,NULL,NULL,NULL,NULL,NULL,'default','2018-11-15 21:48:20','2018-11-15 21:48:20'),(42,4,33,NULL,'liip_imagine_filter',NULL,NULL,1,1,1,1,'liip_imagine_filter','media-cache-resolve-filter-path','/media/cache/resolve/{filter}/{path}',NULL,'GET|POST|HEAD|DELETE|PUT',NULL,NULL,NULL,NULL,NULL,NULL,'default','2018-11-15 21:48:20','2018-11-15 21:48:20'),(43,4,33,NULL,'application_sonata_offer_list',NULL,NULL,1,1,1,1,'application_sonata_offer_list','oferta-lista','/oferta/lista',NULL,'GET|POST|HEAD|DELETE|PUT',NULL,NULL,NULL,NULL,NULL,NULL,'default','2018-11-15 21:48:20','2018-11-15 21:48:20'),(44,4,33,NULL,'application_sonata_offer_view',NULL,NULL,1,1,1,1,'application_sonata_offer_view','oferta-id-szczegoly','/oferta/{id}/szczegoly',NULL,'GET|POST|HEAD|DELETE|PUT',NULL,NULL,NULL,NULL,NULL,NULL,'default','2018-11-15 21:48:20','2018-11-15 21:48:20'),(45,4,33,NULL,'application_sonata_offer_precincts',NULL,NULL,1,1,1,1,'application_sonata_offer_precincts','oferta-precincts','/oferta/precincts',NULL,'GET|POST|HEAD|DELETE|PUT',NULL,NULL,NULL,NULL,NULL,NULL,'default','2018-11-15 21:48:20','2018-11-15 21:48:20'),(46,4,NULL,NULL,'_page_internal_error_not_found',NULL,NULL,1,1,0,1,'_page_internal_error_not_found','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'default','2018-11-15 21:48:20','2018-11-15 21:48:20'),(47,4,NULL,NULL,'_page_internal_error_fatal',NULL,NULL,1,1,0,1,'_page_internal_error_fatal','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'default','2018-11-15 21:48:20','2018-11-15 21:48:20'),(48,4,33,NULL,'page_slug',NULL,'sonata.page.service.default',1,1,1,1,'Nasze usługi','nasze-uslugi','/nasze-uslugi','#','GET|POST|HEAD|DELETE|PUT',NULL,NULL,NULL,NULL,NULL,NULL,'default','2018-11-20 09:24:17','2018-11-20 09:43:39'),(49,4,48,NULL,'page_slug',NULL,'sonata.page.service.default',1,1,1,1,'Adaptacje i zmiany','adaptacje-i-zmiany','/nasze-uslugi/adaptacje-i-zmiany',NULL,'GET|POST|HEAD|DELETE|PUT',NULL,NULL,NULL,NULL,NULL,NULL,'default','2018-11-20 09:37:14','2018-11-20 09:44:49'),(50,4,48,NULL,'page_slug',NULL,'sonata.page.service.default',1,1,1,1,'Formalności','formalnosci','/nasze-uslugi/formalnosci',NULL,'GET|POST|HEAD|DELETE|PUT',NULL,NULL,NULL,NULL,NULL,NULL,'default','2018-11-20 09:38:03','2018-11-20 09:45:28'),(51,4,48,NULL,'page_slug',NULL,'sonata.page.service.default',1,1,1,1,'Projekty indywidualne','projekty-indywidualne','/nasze-uslugi/projekty-indywidualne',NULL,'GET|POST|HEAD|DELETE|PUT',NULL,NULL,NULL,NULL,NULL,NULL,'default','2018-11-20 09:39:09','2018-11-20 09:45:51'),(52,4,48,NULL,'page_slug',NULL,'sonata.page.service.default',1,1,1,1,'Budowa','budowa','/nasze-uslugi/budowa',NULL,'GET|POST|HEAD|DELETE|PUT',NULL,NULL,NULL,NULL,NULL,NULL,'default','2018-11-20 09:40:05','2018-11-20 09:46:02'),(53,4,48,NULL,'page_slug',NULL,NULL,1,1,1,1,'Wnętrze','wnetrze','/nasze-uslugi/wnetrze',NULL,'GET|POST|HEAD|DELETE|PUT',NULL,NULL,NULL,NULL,NULL,NULL,'default','2018-11-20 09:40:41','2018-11-20 09:46:12'),(54,4,48,NULL,'page_slug',NULL,'sonata.page.service.default',1,1,1,1,'Ogród','ogrod','/nasze-uslugi/ogrod',NULL,'GET|POST|HEAD|DELETE|PUT',NULL,NULL,NULL,NULL,NULL,NULL,'default','2018-11-20 09:41:21','2018-11-20 09:46:22'),(55,4,33,NULL,'page_slug',NULL,'sonata.page.service.default',1,1,1,1,'Warto wiedzieć','warto-wiedziec','/warto-wiedziec',NULL,'GET|POST|HEAD|DELETE|PUT',NULL,NULL,NULL,NULL,NULL,NULL,'default','2018-11-20 10:09:08','2018-11-20 10:09:08'),(56,4,55,NULL,'page_slug',NULL,'sonata.page.service.default',1,1,1,1,'Zanim kupisz projekt','zanim-kupisz-projekt','/warto-wiedziec/zanim-kupisz-projekt',NULL,'GET|POST|HEAD|DELETE|PUT',NULL,NULL,NULL,NULL,NULL,NULL,'default','2018-11-20 10:09:53','2018-11-20 10:09:53'),(57,4,55,NULL,'page_slug',NULL,'sonata.page.service.default',1,1,1,1,'Jak zamówić projekt?','jak-zamowic-projekt','/warto-wiedziec/jak-zamowic-projekt',NULL,'GET|POST|HEAD|DELETE|PUT',NULL,NULL,NULL,NULL,NULL,NULL,'default','2018-11-20 10:10:39','2018-11-20 10:10:39'),(58,4,55,NULL,'page_slug',NULL,'sonata.page.service.default',1,1,1,1,'Co zawiera dokumentacja?','co-zawiera-dokumentacja','/warto-wiedziec/co-zawiera-dokumentacja',NULL,'GET|POST|HEAD|DELETE|PUT',NULL,NULL,NULL,NULL,NULL,NULL,'default','2018-11-20 10:11:09','2018-11-20 10:11:09'),(59,4,55,NULL,'page_slug',NULL,'sonata.page.service.default',1,1,1,1,'Dostawa i płatność','dostawa-i-platnosc','/warto-wiedziec/dostawa-i-platnosc',NULL,'GET|POST|HEAD|DELETE|PUT',NULL,NULL,NULL,NULL,NULL,NULL,'default','2018-11-20 10:11:43','2018-11-20 10:11:43'),(60,4,55,NULL,'page_slug',NULL,'sonata.page.service.default',1,1,1,1,'Poradnik inwestora','poradnik-inwestora','/warto-wiedziec/poradnik-inwestora',NULL,'GET|POST|HEAD|DELETE|PUT',NULL,NULL,NULL,NULL,NULL,NULL,'default','2018-11-20 10:12:09','2018-11-20 10:12:09'),(61,4,33,NULL,'page_slug',NULL,'sonata.page.service.default',1,1,1,1,'O nas','o-nas','/o-nas',NULL,'GET|POST|HEAD|DELETE|PUT',NULL,NULL,NULL,NULL,NULL,NULL,'default','2018-11-20 10:13:16','2018-11-20 10:13:16'),(62,4,33,NULL,'page_slug',NULL,'sonata.page.service.default',1,1,1,1,'Co zyskujesz?','co-zyskujesz','/co-zyskujesz',NULL,'GET|POST|HEAD|DELETE|PUT',NULL,NULL,NULL,NULL,NULL,NULL,'default','2018-11-20 10:13:42','2018-11-20 10:13:42'),(63,4,33,NULL,'page_slug',NULL,'sonata.page.service.default',1,1,1,1,'Opinie klientów','opinie-klientow','/opinie-klientow',NULL,'GET|POST|HEAD|DELETE|PUT',NULL,NULL,NULL,NULL,NULL,NULL,'default','2018-11-20 10:14:10','2018-11-20 10:14:10'),(64,4,33,NULL,'page_slug',NULL,'sonata.page.service.default',1,1,1,1,'Wyszukiwarka','wyszukiwarka','/wyszukiwarka','/projekty','GET|POST|HEAD|DELETE|PUT','Wyszukiwarka','Wyszukiwarka','Wyszukiwarka',NULL,NULL,NULL,'default','2018-11-20 10:14:35','2019-01-16 13:18:24'),(65,4,33,NULL,'page_slug',NULL,'sonata.page.service.default',1,1,1,1,'Kontakt','kontakt','/kontakt',NULL,'GET|POST|HEAD|DELETE|PUT',NULL,NULL,NULL,NULL,NULL,NULL,'default','2018-11-20 10:14:56','2018-11-20 10:14:56'),(66,4,33,NULL,'page_slug',NULL,'sonata.page.service.default',1,1,1,1,'Regulamin','regulamin','/regulamin',NULL,'GET|POST|HEAD|DELETE|PUT','Regulamin','Regulamin','Regulamin',NULL,NULL,NULL,'default','2018-11-25 21:19:42','2018-11-25 21:19:42'),(67,4,33,NULL,'page_slug',NULL,'sonata.page.service.default',1,1,1,1,'Polityka prywatności','polityka-prywatnosci','/polityka-prywatnosci',NULL,'GET|POST|HEAD|DELETE|PUT','Polityka prywatności','Polityka prywatności','Polityka prywatności',NULL,NULL,NULL,'default','2018-11-25 21:20:24','2018-11-25 21:20:24'),(68,4,33,NULL,'page_slug',NULL,'sonata.page.service.default',1,1,1,1,'Współpraca','wspolpraca','/wspolpraca',NULL,'GET|POST|HEAD|DELETE|PUT','Współpraca','Współpraca','Współpraca',NULL,NULL,NULL,'default','2018-11-25 21:20:55','2018-11-25 21:20:55');
/*!40000 ALTER TABLE `page__page` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `page__site`
--

DROP TABLE IF EXISTS `page__site`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `page__site` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `enabled` tinyint(1) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `relative_path` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `host` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `enabled_from` datetime DEFAULT NULL,
  `enabled_to` datetime DEFAULT NULL,
  `is_default` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `locale` varchar(7) COLLATE utf8_unicode_ci DEFAULT NULL,
  `title` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `meta_keywords` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `meta_description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `headCode` longtext COLLATE utf8_unicode_ci,
  `footerCode` longtext COLLATE utf8_unicode_ci,
  `bodyCode` longtext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `page__site`
--

LOCK TABLES `page__site` WRITE;
/*!40000 ALTER TABLE `page__site` DISABLE KEYS */;
INSERT INTO `page__site` VALUES (4,1,'Domo',NULL,'domo.local','2018-11-15 21:41:38',NULL,1,'2018-11-15 21:41:48','2018-11-15 21:41:48',NULL,'Domo','Domo','Domo',NULL,NULL,NULL);
/*!40000 ALTER TABLE `page__site` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `page__snapshot`
--

DROP TABLE IF EXISTS `page__snapshot`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `page__snapshot` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `site_id` int(11) DEFAULT NULL,
  `page_id` int(11) DEFAULT NULL,
  `route_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `page_alias` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `position` int(11) NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `decorate` tinyint(1) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `url` longtext COLLATE utf8_unicode_ci,
  `parent_id` int(11) DEFAULT NULL,
  `target_id` int(11) DEFAULT NULL,
  `content` longtext COLLATE utf8_unicode_ci COMMENT '(DC2Type:json)',
  `publication_date_start` datetime DEFAULT NULL,
  `publication_date_end` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_3963EF9AF6BD1646` (`site_id`),
  KEY `IDX_3963EF9AC4663E4` (`page_id`),
  KEY `idx_snapshot_dates_enabled` (`publication_date_start`,`publication_date_end`,`enabled`),
  CONSTRAINT `FK_3963EF9AC4663E4` FOREIGN KEY (`page_id`) REFERENCES `page__page` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_3963EF9AF6BD1646` FOREIGN KEY (`site_id`) REFERENCES `page__site` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `page__snapshot`
--

LOCK TABLES `page__snapshot` WRITE;
/*!40000 ALTER TABLE `page__snapshot` DISABLE KEYS */;
INSERT INTO `page__snapshot` VALUES (17,4,33,'page_slug',NULL,'sonata.page.service.default',1,1,1,'Start','/',NULL,NULL,'{\"id\":33,\"name\":\"Start\",\"javascript\":null,\"stylesheet\":null,\"raw_headers\":null,\"title\":\"Domo\",\"meta_description\":\"Domo\",\"meta_keyword\":\"Domo\",\"template_code\":\"homepage\",\"request_method\":\"GET|POST|HEAD|DELETE|PUT\",\"created_at\":\"1542314797\",\"updated_at\":\"1542314797\",\"slug\":null,\"parent_id\":null,\"target_id\":null,\"blocks\":[]}','2018-11-15 21:47:26',NULL,'2018-11-15 21:47:26','2018-11-15 21:47:26');
/*!40000 ALTER TABLE `page__snapshot` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `page_menu`
--

DROP TABLE IF EXISTS `page_menu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `page_menu` (
  `page_id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  PRIMARY KEY (`page_id`,`menu_id`),
  KEY `IDX_FAC12649C4663E4` (`page_id`),
  KEY `IDX_FAC12649CCD7E912` (`menu_id`),
  CONSTRAINT `FK_FAC12649C4663E4` FOREIGN KEY (`page_id`) REFERENCES `page__page` (`id`),
  CONSTRAINT `FK_FAC12649CCD7E912` FOREIGN KEY (`menu_id`) REFERENCES `classification__collection` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `page_menu`
--

LOCK TABLES `page_menu` WRITE;
/*!40000 ALTER TABLE `page_menu` DISABLE KEYS */;
INSERT INTO `page_menu` VALUES (33,4),(48,5),(49,5),(50,5),(51,5),(52,5),(53,5),(54,5),(55,6),(56,6),(57,6),(58,6),(59,6),(60,6),(61,4),(62,4),(63,4),(64,4),(65,4),(66,7),(67,7),(68,7);
/*!40000 ALTER TABLE `page_menu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `province`
--

DROP TABLE IF EXISTS `province`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `province` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `province_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `province`
--

LOCK TABLES `province` WRITE;
/*!40000 ALTER TABLE `province` DISABLE KEYS */;
/*!40000 ALTER TABLE `province` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-01-16 13:36:39
