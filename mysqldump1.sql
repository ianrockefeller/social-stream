-- MySQL dump 10.13  Distrib 5.5.31, for Linux (x86_64)
--
-- Host: localhost    Database: StreamDB
-- ------------------------------------------------------
-- Server version	5.5.31

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
-- Table structure for table `object`
--

DROP TABLE IF EXISTS `object`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `object` (
  `object_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `url` varchar(250) DEFAULT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `body` varchar(1500) DEFAULT NULL,
  `title` varchar(150) DEFAULT NULL,
  `sender` varchar(75) DEFAULT NULL,
  `type` varchar(10) DEFAULT NULL,
  `pid` varchar(90) DEFAULT NULL,
  PRIMARY KEY (`object_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `object_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`),
  CONSTRAINT `object_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `object`
--

LOCK TABLES `object` WRITE;
/*!40000 ALTER TABLE `object` DISABLE KEYS */;
/*!40000 ALTER TABLE `object` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rss`
--

DROP TABLE IF EXISTS `rss`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rss` (
  `rss_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `url` varchar(75) NOT NULL,
  PRIMARY KEY (`rss_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `rss_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`),
  CONSTRAINT `rss_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`),
  CONSTRAINT `rss_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rss`
--

LOCK TABLES `rss` WRITE;
/*!40000 ALTER TABLE `rss` DISABLE KEYS */;
INSERT INTO `rss` VALUES (1,21,'http://feeds.hanselman.com/ScottHanselman'),(2,21,'http://www.reddit.com/.rss'),(4,26,'http://www.reddit.com/.rss?feed=00d4a147afefe00cf5e7449a76255847e8cbd0ef&us'),(5,30,'http://www.reddit.com/.rss'),(6,30,'http://feeds.hanselman.com/ScottHanselman'),(7,33,'http://www.reddit.com/.rss');
/*!40000 ALTER TABLE `rss` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(50) NOT NULL,
  `pw` varchar(50) NOT NULL,
  `gmail` varchar(90) DEFAULT NULL,
  `gpw` varchar(90) DEFAULT NULL,
  `twitter` varchar(150) DEFAULT NULL,
  `tpw` varchar(150) DEFAULT NULL,
  `facebook` varchar(250) DEFAULT NULL,
  `fbpw` varchar(250) DEFAULT NULL,
  `reddit` varchar(150) DEFAULT NULL,
  `rpw` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (21,'bob@bob.com','bob123',NULL,NULL,'1094742248-VJ59EbdLF8MkiJ1zylzbPHJ9tVyAJ71CCNj8RXD','fXD8YyXfW5xcpGRF4CzQ16S5Dux889aVcY2kHlGrrA','BAACFeqnaVi8BAEaeZBgtK3mMe6z4TAZBBqCRy8IxHb5Un92fDQ1SAB97FFPVgkSIm5I6ZB4CRAHXuEjaSM3ZBc5voMhmAY3LuwlZCQf5Y7nnVxMuA95G783hKQAvU0YgdtfCpedT7dRvHDERJhssmq1xETreRqwfrA2iihXDOgv4wjXIEvydCCRCFItMZAbf4sTBygyVhHfn4oboE0fDHs','2dda344629890878913464eb4dd54657',NULL,NULL),(22,'jen@jen.com','jen123',NULL,NULL,NULL,NULL,'BAACFeqnaVi8BAHd10dhTM9p0v0nueoq7NwWUwpv4P5uIic8zr7pm1BGYdsR2XDt10lUgpWg6Q6eaElQlAUGnhEX6Y1VjHox1AUqglOT7zF9honaiV81fY8BedfF27KCDvO9dzuSCS0lX84OQsIB0DCzSn4sm3S8PC2E3N6sopDxpZCimr4VSPTw0jk0NX3RtwmcuJDgAoG5rpYU2W','2850827294362595132ced55f52831d0',NULL,NULL),(23,'steve@steve.com','steve123',NULL,NULL,NULL,NULL,'BAACFeqnaVi8BAN06ROBj5HWVGwjbympJdoRXTgZAzZA5b9BSf3EbRuVVN4jRyarEbCxVQoMVOXEvaOBtC1w6LDupHGgR85jZCcGWThlY6eSlvnRneUlxBMsS4OdTSowqlh5xSv5ZCivddXV3zGAD1ks1JF78nXAZBB4UlwZCIr639MdrG1f8pgEbbMAtytEL1jLDFXN6ZChy8CrdhsLewU3','a6a84f503d7e7203bc6f87743b328c2e',NULL,NULL),(24,'cait@cait.com','cait123',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(25,'mike@mike.com','mike123',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(26,'tom@tom.com','tom',NULL,NULL,'381296178-b427MucfPtySpoVMgUdDFSQAWUwTViaLmj5W3mCt','nrvD8Hp3fUAvdFAnj5iqOM4Ojw6ixkB8LB4gfCGnk',NULL,'798f06c1797ce38a2c4c2f1be85af33d',NULL,NULL),(27,'pat@pat.com','pat123',NULL,NULL,'366201994-4hC8toslL0Tc6dbc13MeIMyxogyPOXWFMH4bWBse','h9W8FIXRm6wqnq6s5SUuLhnXjQYea5lnvKBjvmadDA','BAACFeqnaVi8BAEZBDwWUfhXM7GZBJjDTdys5uTgxvemNsW4OpKU2PmtX9XoYNQslFejCDknQ9Rs8q5u7MMrYAABAu01n58EoNX9ujKP6uSpaUrpGnSr8ZAp4PPEBmO1B1eZA1U8ZCaBVgVyWpLqujwtGE13L7z6eAZAbZBLn3m6u2HhJFm2TW45EgdjExuSW8bcXVrnydEyA1cposgIjQjK','bed302a379b01649a40d808ac26868c4',NULL,NULL),(28,'\' or 1=1--','test',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(29,'\' or 1=0--','test',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(30,'sally@sally.com','sally123',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(31,'eeee','test',NULL,NULL,'381296178-b427MucfPtySpoVMgUdDFSQAWUwTViaLmj5W3mCt','nrvD8Hp3fUAvdFAnj5iqOM4Ojw6ixkB8LB4gfCGnk',NULL,NULL,NULL,NULL),(32,'j','j',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(33,'test','test',NULL,NULL,'381296178-b427MucfPtySpoVMgUdDFSQAWUwTViaLmj5W3mCt','nrvD8Hp3fUAvdFAnj5iqOM4Ojw6ixkB8LB4gfCGnk','BAACFeqnaVi8BAM1gagjt8znINizGgKGUriL5HA8KrFebZBgckJMZCiDLoWXUzzVc9yWj2ZA3Ryw4xKznJRzvuKmXsRdpqqx5EQnuvXEJYZAOtmqIfFlhZCUxJDMpBQMXMZC9cMKUXZCwbV4GHm9pcm7FnpmOShbkYggLpmP1NSvJb4TjP0Fh4ZA6U7EUW4JZBZAHu1AXZBI0EP1P13cRL1loJO0','14d7a66f9c2516b5d808d763b02f19b6','tSWuT4d8P1fxs6z4K69lyYFBGLs',NULL),(34,'test2','test2',NULL,NULL,'381296178-b427MucfPtySpoVMgUdDFSQAWUwTViaLmj5W3mCt','nrvD8Hp3fUAvdFAnj5iqOM4Ojw6ixkB8LB4gfCGnk',NULL,NULL,NULL,NULL),(35,'test3','test3',NULL,NULL,NULL,NULL,'BAACFeqnaVi8BANYqtoh6CVFCBjZBrxPqZCoTd17PevVZCKBqWSiaAmDOM8dmzrLuwdDyHbEfDi3GGvd0OuyikOy2t8RFOPaIhrrtknlWF5fQfvPxtUdXZAe9L8YAZCUzb5xdnthFu2EEHuoH4gGivrcVEC1bjhigeZC7qGnZBISf20GJpTONGHZAiVZCdyvTdT2KoXivUSLI5wKObmV1yH75m','9286bdb743216dc6b211f0d5738ad032',NULL,NULL),(36,'ray@ray.com','ray123',NULL,NULL,'381296178-b427MucfPtySpoVMgUdDFSQAWUwTViaLmj5W3mCt','nrvD8Hp3fUAvdFAnj5iqOM4Ojw6ixkB8LB4gfCGnk','BAACFeqnaVi8BAHtt1qmZBJDTsCgmTasuMXdixpUZBDmvEnwdY79ZBkjvWPP2KxdA1qKk6pXQIsiCeLqlZCughYRGaQU0r5l5ZCdUX7LnBFH3VwFIpZB2K7haxFJKiYXA50P1ryoAu6DakEcNNcJcfxnB0gHp6LHeA4rNkHrNkpebrfXNDs5e4PmVt8QCAU9a8kQti1EHzD8kOn551wy3Fh','f76c276c06dfadab422e0ad43021c7c8',NULL,NULL),(37,'ian@ian.com','ian123',NULL,NULL,'366201994-4hC8toslL0Tc6dbc13MeIMyxogyPOXWFMH4bWBse','h9W8FIXRm6wqnq6s5SUuLhnXjQYea5lnvKBjvmadDA',NULL,NULL,NULL,NULL),(38,'yoyo','yoyo',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(39,'<script> alert(\"test\") </script>','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(40,'<script> alert(\"you have a security vulnerability\"','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(41,'<script> alert(\"you should fix this peaches\" </scr','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(42,'','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(43,'aadf','asdf',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(44,'<script> alerct(\"XSS\"); </script>','e',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2013-05-03 18:26:55
