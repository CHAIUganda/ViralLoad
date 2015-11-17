-- MySQL dump 10.13  Distrib 5.6.25, for Win32 (x86)
--
-- Host: localhost    Database: eid_uganda_vl
-- ------------------------------------------------------
-- Server version	5.6.25

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
-- Table structure for table `vl_appendix_regimen`
--

DROP TABLE IF EXISTS `vl_appendix_regimen`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vl_appendix_regimen` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `appendix` varchar(200) NOT NULL,
  `position` mediumint(8) unsigned NOT NULL,
  `treatmentStatusID` mediumint(8) unsigned NOT NULL,
  `created` datetime NOT NULL,
  `createdby` varchar(250) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `treatmentStatusIDIndex` (`treatmentStatusID`)
) ENGINE=InnoDB AUTO_INCREMENT=71 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vl_appendix_regimen`
--

LOCK TABLES `vl_appendix_regimen` WRITE;
/*!40000 ALTER TABLE `vl_appendix_regimen` DISABLE KEYS */;
INSERT INTO `vl_appendix_regimen` VALUES (1,'1c = AZT-3TC-NVP',1,1,'2014-07-01 17:05:03','samuel@trailanalytics.com'),(2,'1d = AZT-3TC-EFV',2,1,'2014-07-01 17:05:09','samuel@trailanalytics.com'),(3,'1e = TDF-3TC-NVP',3,1,'2014-07-01 17:05:13','samuel@trailanalytics.com'),(4,'1f = TDF-3TC-EFV',4,1,'2014-07-01 17:05:16','samuel@trailanalytics.com'),(5,'1g = TDF-FTC-NVP',5,1,'2014-07-01 17:05:20','samuel@trailanalytics.com'),(6,'1h = TDF-FTC-EFV',6,1,'2014-07-01 17:05:24','samuel@trailanalytics.com'),(7,'1i = ABC-3TC-EFV',7,1,'2014-07-01 17:05:27','samuel@trailanalytics.com'),(8,'1j = ABC-3TC-NVP',8,1,'2014-07-01 17:05:30','samuel@trailanalytics.com'),(11,'2b = TDF-3TC-LPV/r',9,2,'2014-07-01 17:06:28','samuel@trailanalytics.com'),(12,'2c = TDF-FTC-LPV/r',10,2,'2014-07-01 17:06:33','samuel@trailanalytics.com'),(13,'2e = AZT-3TC-LPV/r',11,2,'2014-07-01 17:06:37','samuel@trailanalytics.com'),(14,'2f = TDF-FTC-ATV/r',12,2,'2014-07-01 17:06:40','samuel@trailanalytics.com'),(15,'2g = TDF-3TC-ATV/r',13,2,'2014-07-01 17:06:44','samuel@trailanalytics.com'),(16,'2h = AZT-3TC-ATV/r',14,2,'2014-07-01 17:06:47','samuel@trailanalytics.com'),(17,'2i = ABC-3TC-LPV/r',15,2,'2014-07-01 17:06:51','samuel@trailanalytics.com'),(18,'2j = ABC-3TC-ATV/r',16,2,'2014-07-01 17:06:54','samuel@trailanalytics.com'),(19,'4a = d4T-3TC-NVP',17,1,'2014-07-01 17:06:57','samuel@trailanalytics.com'),(20,'4b = d4T-3TC-EFV',18,1,'2014-07-01 17:07:01','samuel@trailanalytics.com'),(21,'4c = AZT-3TC-NVP',19,1,'2014-07-01 17:07:05','samuel@trailanalytics.com'),(22,'4d = AZT-3TC-EFV',20,1,'2014-07-01 17:07:09','samuel@trailanalytics.com'),(23,'4e = ABC-3TC-NVP',21,1,'2014-07-01 17:07:12','samuel@trailanalytics.com'),(24,'4f = ABC-3TC-EFV',22,1,'2014-07-01 17:07:15','samuel@trailanalytics.com'),(25,'5d = TDF-3TC-LPV/r',23,2,'2014-07-01 17:07:19','samuel@trailanalytics.com'),(26,'5e = TDF-FTC-LPV/r',24,2,'2014-07-01 17:07:22','samuel@trailanalytics.com'),(27,'5g = AZT-ABC-LPV/r',25,2,'2014-07-01 17:07:25','samuel@trailanalytics.com'),(28,'5i = AZT-3TC-ATV/r',26,2,'2014-07-01 17:07:29','samuel@trailanalytics.com'),(29,'5j = ABC-3TC-LPV/r',27,2,'2014-07-01 17:07:33','samuel@trailanalytics.com'),(30,'5k = ABC-3TC-ATV/r',28,2,'2014-07-01 17:07:36','samuel@trailanalytics.com'),(31,'Left Blank',29,0,'2014-07-05 20:04:42','samuel@trailanalytics.com'),(32,'RLT-LPV/r',30,0,'2014-12-16 18:17:53','joseph.kibirige@yahoo.com'),(33,'RACTEGRAVIR/ALUVIA',31,0,'2014-12-17 13:52:31','joseph.kibirige@yahoo.com'),(35,'3TC/EFV/LPV/r',33,0,'2014-12-17 15:17:42','joseph.kibirige@yahoo.com'),(36,'3TC/EFV/ALUVIA',33,0,'2014-12-22 11:54:37','joseph.kibirige@yahoo.com'),(37,'EVF/3TC/<PV/r',34,0,'2014-12-22 13:07:14','joseph.kibirige@yahoo.com'),(38,'EVF/3TC/PV/r',34,0,'2014-12-22 13:08:43','joseph.kibirige@yahoo.com'),(39,'RALTEGRAVIR/ALUVIA',36,0,'2014-12-22 13:10:02','joseph.kibirige@yahoo.com'),(40,'3TC/MVP/LP',37,0,'2014-12-22 13:18:08','joseph.kibirige@yahoo.com'),(41,'10d',38,0,'2014-12-23 14:41:04','joseph.kibirige@yahoo.com'),(42,'CBV/LPVr',39,0,'2015-01-14 10:51:50','joseph.kibirige@yahoo.com'),(43,'ABC-3TC-AZT',40,0,'2015-01-27 16:48:20','joseph.kibirige@yahoo.com'),(44,'1b',41,0,'2015-02-02 11:17:37','joseph.kibirige@yahoo.com'),(45,'CBV/Kaletra',42,0,'2015-02-02 12:04:24','joseph.kibirige@yahoo.com'),(46,'ABC/NVP/LP',43,0,'2015-02-03 10:46:28','joseph.kibirige@yahoo.com'),(47,'3TC,TDF,AZT',44,0,'2015-02-05 09:41:34','joseph.kibirige@yahoo.com'),(48,'2d',45,0,'2015-02-05 09:50:28','joseph.kibirige@yahoo.com'),(49,'3TC+Nev+Aluvia',46,0,'2015-02-05 12:31:54','joseph.kibirige@yahoo.com'),(50,'AZT-3TC-ABC-LPV/r',47,0,'2015-02-06 12:28:22','joseph.kibirige@yahoo.com'),(51,'TDF/3TC/ABC',48,0,'2015-02-07 12:34:50','joseph.kibirige@yahoo.com'),(52,'Duovir N',49,0,'2015-02-16 11:30:30','joseph.kibirige@yahoo.com'),(53,'3FC/EFV/LPV/r',50,0,'2015-02-23 12:13:23','joseph.kibirige@yahoo.com'),(54,'LPV/r/EFV',51,0,'2015-02-23 12:25:59','joseph.kibirige@yahoo.com'),(55,'CBV/NVP',52,0,'2015-02-23 12:38:03','joseph.kibirige@yahoo.com'),(56,'TDF/3TC/AZT/ATV/r',53,0,'2015-02-25 16:14:15','joseph.kibirige@yahoo.com'),(57,'CBV/ALV',54,0,'2015-03-04 12:18:48','joseph.kibirige@yahoo.com'),(58,'RLT/TDF/3TC/ALV',55,0,'2015-03-09 10:05:24','joseph.kibirige@yahoo.com'),(59,'ZDV/3TC/TDFLPV/r',56,0,'2015-03-11 11:04:26','joseph.kibirige@yahoo.com'),(60,'1k',57,0,'2015-03-12 14:05:51','joseph.kibirige@yahoo.com'),(61,'2r',58,0,'2015-03-18 08:59:24','joseph.kibirige@yahoo.com'),(62,'NVP/3TC/LPVr',59,0,'2015-04-01 11:56:53','joseph.kibirige@yahoo.com'),(63,'Duranavir/Rategravir/3TC/EFV',60,0,'2015-04-10 14:05:06','joseph.kibirige@yahoo.com'),(64,'AZT/3TC/LPV/ry',61,0,'2015-04-14 14:14:09','joseph.kibirige@yahoo.com'),(65,'TDF/3TC/EFV',62,0,'2015-05-07 14:06:43','joseph.kibirige@yahoo.com'),(66,'AZT/3TC/kaletra',63,0,'2015-05-11 14:49:28','joseph.kibirige@yahoo.com'),(67,'(RLT/LPV/r/Etravirine',64,0,'2015-05-21 12:19:50','joseph.kibirige@yahoo.com'),(68,'3TC-TDF-EFV',65,0,'2015-08-18 14:32:31','joseph.kibirige@yahoo.com'),(69,'AZT+ABC+EFV',66,0,'2015-09-04 14:35:27','joseph.kibirige@yahoo.com'),(70,'TDF/3TC/Aluvia',67,0,'2015-10-13 10:48:14','joseph.kibirige@yahoo.com');
/*!40000 ALTER TABLE `vl_appendix_regimen` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-11-17 15:02:33
