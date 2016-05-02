-- MySQL dump 10.13  Distrib 5.5.46, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: behigorri
-- ------------------------------------------------------
-- Server version	5.5.46-0+deb8u1

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
-- Table structure for table `Group`
--

DROP TABLE IF EXISTS `Group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Group` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` varchar(300) DEFAULT NULL,
  `createdAt` varchar(300) DEFAULT NULL,
  `updatedAt` varchar(300) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Group`
--

LOCK TABLES `Group` WRITE;
/*!40000 ALTER TABLE `Group` DISABLE KEYS */;
INSERT INTO `Group` VALUES (1,'agroup','aaaaa',NULL,NULL),(2,'group2','222222222',NULL,NULL),(3,'group3','333333333',NULL,NULL),(4,'group4','444444444',NULL,NULL);
/*!40000 ALTER TABLE `Group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `SensitiveData`
--

DROP TABLE IF EXISTS `SensitiveData`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `SensitiveData` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `createdAt` varchar(300) DEFAULT NULL,
  `updatedAt` varchar(300) DEFAULT NULL,
  `ownerId` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_72C1B183E05EFD25` (`ownerId`),
  CONSTRAINT `FK_72C1B183E05EFD25` FOREIGN KEY (`ownerId`) REFERENCES `User` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `SensitiveData`
--

LOCK TABLES `SensitiveData` WRITE;
/*!40000 ALTER TABLE `SensitiveData` DISABLE KEYS */;
INSERT INTO `SensitiveData` VALUES (1,'sensitive1',NULL,NULL,5),(2,'2sensitivedata',NULL,NULL,1),(3,'3sensitivedata',NULL,NULL,1),(4,'4sensitivedata',NULL,NULL,1),(5,'5sensitivedata',NULL,NULL,3),(6,'6sensitivedata',NULL,NULL,3),(7,'7sensitivedata',NULL,NULL,3),(8,'8sensitivedata',NULL,NULL,3),(9,'senstivedata10',NULL,NULL,4),(10,'aaaaa',NULL,NULL,4),(11,'dddddd',NULL,NULL,4),(24,'senstitivedata24',NULL,NULL,4);
/*!40000 ALTER TABLE `SensitiveData` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `SensitiveDataGroup`
--

DROP TABLE IF EXISTS `SensitiveDataGroup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `SensitiveDataGroup` (
  `sensitivedata_id` int(10) unsigned NOT NULL,
  `group_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`sensitivedata_id`,`group_id`),
  KEY `IDX_72ED6F7F4C0D4FA5` (`sensitivedata_id`),
  KEY `IDX_72ED6F7FFE54D947` (`group_id`),
  CONSTRAINT `FK_72ED6F7F4C0D4FA5` FOREIGN KEY (`sensitivedata_id`) REFERENCES `SensitiveData` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_72ED6F7FFE54D947` FOREIGN KEY (`group_id`) REFERENCES `Group` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `SensitiveDataGroup`
--

LOCK TABLES `SensitiveDataGroup` WRITE;
/*!40000 ALTER TABLE `SensitiveDataGroup` DISABLE KEYS */;
INSERT INTO `SensitiveDataGroup` VALUES (2,1),(2,2),(3,1),(4,1),(5,2),(6,2),(7,2),(8,2);
/*!40000 ALTER TABLE `SensitiveDataGroup` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `SensitiveDataTag`
--

DROP TABLE IF EXISTS `SensitiveDataTag`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `SensitiveDataTag` (
  `sensitivedata_id` int(10) unsigned NOT NULL,
  `tag_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`sensitivedata_id`,`tag_id`),
  KEY `IDX_C90A39174C0D4FA5` (`sensitivedata_id`),
  KEY `IDX_C90A3917BAD26311` (`tag_id`),
  CONSTRAINT `FK_C90A39174C0D4FA5` FOREIGN KEY (`sensitivedata_id`) REFERENCES `SensitiveData` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_C90A3917BAD26311` FOREIGN KEY (`tag_id`) REFERENCES `Tag` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `SensitiveDataTag`
--

LOCK TABLES `SensitiveDataTag` WRITE;
/*!40000 ALTER TABLE `SensitiveDataTag` DISABLE KEYS */;
/*!40000 ALTER TABLE `SensitiveDataTag` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Tag`
--

DROP TABLE IF EXISTS `Tag`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Tag` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `createdAt` varchar(300) DEFAULT NULL,
  `updatedAt` varchar(300) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Tag`
--

LOCK TABLES `Tag` WRITE;
/*!40000 ALTER TABLE `Tag` DISABLE KEYS */;
/*!40000 ALTER TABLE `Tag` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `User`
--

DROP TABLE IF EXISTS `User`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `User` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `createdAt` varchar(300) DEFAULT NULL,
  `updatedAt` varchar(300) DEFAULT NULL,
  `token` varchar(355) DEFAULT NULL,
  `god` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_2DA17977E7927C74` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `User`
--

LOCK TABLES `User` WRITE;
/*!40000 ALTER TABLE `User` DISABLE KEYS */;
INSERT INTO `User` VALUES (1,'a','a@a.com','$2y$10$JSDtEx/Hgr5h9M/FVP8yX.UzFUz90uJA7dI7eiMiEoD..LaLSP5yK',NULL,NULL,'9Kwa1pPIRDYyH85QChA5EdqFW1LnCGl0MLTWqzsDKSaxzgiT1MOoNwjVNPi1',1),(2,'b','b@b.com','$2y$10$6QemgXKEThgGPHNXY4QlBOwwf9gkOoDTGuuMZoaBmcr047Qz0XM36',NULL,NULL,NULL,0),(3,'c','c@c.com','$2y$10$pI2EJYME38y1r0zTz56n0uQSmfUW14NMZDpvHwO2jk4x9wecxCf1a',NULL,NULL,NULL,0),(4,'d','d@d.com','$2y$10$xp/aKKFbyTzX8XLBUkJ.zOoFrTbtEnL9sdmqtf7xCzxs62VEVbNey',NULL,NULL,NULL,0),(5,'f','f@f.com','$2y$10$MnYzcjLFCx6Vz5zKOZaBYe0pfb7.W2oSkfk71Ot6qjRQFEBybClfm',NULL,NULL,NULL,0);
/*!40000 ALTER TABLE `User` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `UserGroup`
--

DROP TABLE IF EXISTS `UserGroup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `UserGroup` (
  `user_id` int(10) unsigned NOT NULL,
  `group_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`user_id`,`group_id`),
  KEY `IDX_954D5B0A76ED395` (`user_id`),
  KEY `IDX_954D5B0FE54D947` (`group_id`),
  CONSTRAINT `FK_954D5B0A76ED395` FOREIGN KEY (`user_id`) REFERENCES `User` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_954D5B0FE54D947` FOREIGN KEY (`group_id`) REFERENCES `Group` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `UserGroup`
--

LOCK TABLES `UserGroup` WRITE;
/*!40000 ALTER TABLE `UserGroup` DISABLE KEYS */;
INSERT INTO `UserGroup` VALUES (4,1),(4,2),(5,1);
/*!40000 ALTER TABLE `UserGroup` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `doctrine_migration_versions`
--

DROP TABLE IF EXISTS `doctrine_migration_versions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `doctrine_migration_versions`
--

LOCK TABLES `doctrine_migration_versions` WRITE;
/*!40000 ALTER TABLE `doctrine_migration_versions` DISABLE KEYS */;
INSERT INTO `doctrine_migration_versions` VALUES ('20151203184539'),('20160301173036');
/*!40000 ALTER TABLE `doctrine_migration_versions` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-05-02 16:53:36
