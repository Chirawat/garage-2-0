-- MySQL dump 10.13  Distrib 5.7.12, for Win64 (x86_64)
--
-- Host: localhost    Database: garage
-- ------------------------------------------------------
-- Server version	5.7.9

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
-- Table structure for table `body_type`
--

DROP TABLE IF EXISTS `body_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `body_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `body_type`
--

LOCK TABLES `body_type` WRITE;
/*!40000 ALTER TABLE `body_type` DISABLE KEYS */;
INSERT INTO `body_type` VALUES (2,'ประเภท 1'),(3,'ประเภท 2'),(4,'ประเภท 3'),(5,'ประเภท 4'),(6,'ประเภท 5');
/*!40000 ALTER TABLE `body_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customer`
--

DROP TABLE IF EXISTS `customer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `customer` (
  `CID` int(11) NOT NULL AUTO_INCREMENT,
  `fullname` text,
  `type` text,
  `address` text,
  `phone` text,
  `fax` text,
  `taxpayer_id` text COMMENT 'เลขประจำตัวผู้เสียภาษี',
  PRIMARY KEY (`CID`)
) ENGINE=InnoDB AUTO_INCREMENT=371 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customer`
--

LOCK TABLES `customer` WRITE;
/*!40000 ALTER TABLE `customer` DISABLE KEYS */;
INSERT INTO `customer` VALUES (346,'บริษัท วิริยะประกันภัย จำกัด','INSURANCE_COMP','10/7 ถนนราชปรารภ แขวงพญาไท เขตราชเทวี กรุงเทพฯ 10400','022391557',NULL,NULL),(347,'บริษัท อาคเนย์ประกันภัย จำกัด','INSURANCE_COMP','อาคารอาคเนย์ประกันภัย 315 ชั้น จี 1-3 ถนนสีลม แขวงสีลม เขตบางรัก กรุงเทพฯ 10500','022677777','022377409',''),(348,'บริษัท ฟินิกซ์ประกันภัย (ประเทศไทย) จำกัด','INSURANCE_COMP','38 อาคารปรีชาคอมเพล็กซ์ ถ.รัชดาภิเษก แขวงสามเสนนอก เขตห้วยขวาง กรุงเทพฯ','022900544',NULL,NULL),(349,'คุณสมชาย ใจดี','GENERAL','ต.ในเมือง อ.เมือง จ.ยโสธร/แก้2','045712120','045712121',NULL),(368,'fullname 6','INSURANCE_COMP','addr ุ6','006',NULL,'0006'),(369,'fullname1','GENERAL','addr1','001',NULL,'0001'),(370,'fullname 2','GENERAL','addr 2','002',NULL,'0002');
/*!40000 ALTER TABLE `customer` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `damage_position`
--

DROP TABLE IF EXISTS `damage_position`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `damage_position` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `position` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `damage_position`
--

LOCK TABLES `damage_position` WRITE;
/*!40000 ALTER TABLE `damage_position` DISABLE KEYS */;
INSERT INTO `damage_position` VALUES (1,'หน้า'),(2,'ข้างซ้าย'),(3,'ข้างขวา'),(4,'บน'),(5,'ล่าง'),(6,'หลัง');
/*!40000 ALTER TABLE `damage_position` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `description`
--

DROP TABLE IF EXISTS `description`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `description` (
  `DID` int(11) NOT NULL AUTO_INCREMENT,
  `QID` int(11) NOT NULL,
  `row` int(11) DEFAULT NULL,
  `description` text COMMENT 'รายการ',
  `type` text COMMENT 'ประเภท',
  `price` float DEFAULT NULL COMMENT 'ราคา',
  `date` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`DID`),
  KEY `fk_quotation_description_quotation1_idx` (`QID`),
  CONSTRAINT `fk_quotation_description_quotation1` FOREIGN KEY (`QID`) REFERENCES `quotation` (`QID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=607 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `description`
--

LOCK TABLES `description` WRITE;
/*!40000 ALTER TABLE `description` DISABLE KEYS */;
INSERT INTO `description` VALUES (468,162,NULL,'ซ่อม 1','MAINTENANCE',100,'2016-10-19 17:00:00'),(469,162,NULL,'ซ่อม 2','MAINTENANCE',200,'2016-10-19 17:00:00'),(470,162,NULL,'ซ่อม 1','MAINTENANCE',100,'2016-10-19 17:00:00'),(471,162,NULL,'ซ่อม 2','MAINTENANCE',200,'2016-10-19 17:00:00'),(472,162,NULL,'อะไหล่ 1','PART',300,'2016-10-19 17:00:00'),(473,162,NULL,'อะไหล่ 2','PART',400,'2016-10-19 17:00:00'),(474,162,NULL,'ซ่อม 1','MAINTENANCE',100,'2016-10-20 17:00:00'),(475,162,NULL,'ซ่อม 2','MAINTENANCE',200,'2016-10-20 17:00:00'),(476,162,NULL,'อะไหล่ 1','PART',300,'2016-10-19 17:00:00'),(477,162,NULL,'อะไหล่ 2','PART',400,'2016-10-19 17:00:00'),(546,162,NULL,'ซ่อม 1','MAINTENANCE',100,'2016-10-27 04:35:01'),(547,162,NULL,'ซ่อม 2','MAINTENANCE',200,'2016-10-27 04:35:01'),(548,162,NULL,'อะไหล่ 1','PART',300,'2016-10-27 04:35:01'),(549,162,NULL,'อะไหล่ 2','PART',400,'2016-10-27 04:35:01'),(550,162,NULL,'ซ่อม 1','MAINTENANCE',100,'2016-10-27 04:52:29'),(551,162,NULL,'ซ่อม 2','MAINTENANCE',200,'2016-10-27 04:52:29'),(552,162,NULL,'อะไหล่ 2','PART',400,'2016-10-27 04:52:29'),(553,171,1,'ค่าเปลี่ยนยาง','MAINTENANCE',100,'2016-10-26 17:00:00'),(554,171,1,'ค่ายางนอก','PART',250,'2016-10-26 17:00:00'),(555,172,1,'ค่าเปลี่ยนยาง','MAINTENANCE',100,'2016-10-26 17:00:00'),(556,172,1,'ค่ายางนอก','PART',250,'2016-10-26 17:00:00'),(557,173,1,'ค่าเปลี่ยนยาง','MAINTENANCE',100,'2016-10-26 17:00:00'),(558,173,1,'ค่ายางนอก','PART',250,'2016-10-26 17:00:00'),(559,174,1,'ค่าเปลี่ยนยาง','MAINTENANCE',100,'2016-10-26 17:00:00'),(560,174,1,'ค่ายางนอก','PART',250,'2016-10-26 17:00:00'),(561,175,1,'ค่าเปลี่ยนยาง','MAINTENANCE',100,'2016-10-26 17:00:00'),(562,175,1,'ค่ายางนอก','PART',250,'2016-10-26 17:00:00'),(563,176,1,'ค่าเปลี่ยนยาง','MAINTENANCE',100,'2016-10-26 17:00:00'),(564,176,1,'ค่ายางนอก','PART',250,'2016-10-26 17:00:00'),(565,177,1,'ค่าเปลี่ยนยาง','MAINTENANCE',100,'2016-10-26 17:00:00'),(566,177,1,'ค่ายางนอก','PART',250,'2016-10-26 17:00:00'),(567,178,1,'ค่ายางนอก','MAINTENANCE',250,'2016-10-26 17:00:00'),(568,178,1,'ค่าเปลี่ยนยาง','PART',100,'2016-10-26 17:00:00'),(569,178,NULL,'ค่ายางนอก','MAINTENANCE',250,'2016-10-27 15:06:42'),(570,178,NULL,'ค่าเปลี่ยนยาง','PART',100,'2016-10-27 15:06:42'),(571,178,NULL,'ค่าน้ำมันเครื่อง','PART',250,'2016-10-27 15:06:42'),(572,178,NULL,'ค่ายางนอก','MAINTENANCE',250,'2016-10-27 15:08:36'),(573,178,NULL,'ค่าเปลี่ยนยาง','PART',100,'2016-10-27 15:08:36'),(574,178,NULL,'ค่าน้ำมันเครื่อง','PART',250,'2016-10-27 15:08:36'),(575,178,NULL,'ค่าสี','PART',1000,'2016-10-27 15:08:36'),(576,178,NULL,'ค่าเปลี่ยนยาง','PART',100,'2016-10-27 15:13:54'),(577,178,NULL,'ค่าน้ำมันเครื่อง','PART',250,'2016-10-27 15:13:54'),(578,178,NULL,'ค่าสี','PART',1000,'2016-10-27 15:13:54'),(579,179,1,'ค่าน้ำมันเครื่อง','MAINTENANCE',250,'2016-10-26 17:00:00'),(580,180,1,'ค่าเคาะสี','MAINTENANCE',100,'2016-10-26 17:00:00'),(581,180,1,'ค่าสี','PART',1500,'2016-10-26 17:00:00'),(582,181,1,'ค่าพ่นสี','MAINTENANCE',100,'2016-10-26 17:00:00'),(583,181,1,'ค่าสี','PART',2000,'2016-10-26 17:00:00'),(584,182,1,'ค่าเคาะสี','MAINTENANCE',100,'2016-10-27 17:00:00'),(585,182,1,'ค่าสี','PART',2000,'2016-10-27 17:00:00'),(586,182,NULL,'ค่าเคาะสี','MAINTENANCE',100,'2016-10-28 15:32:29'),(587,182,NULL,'ค่าสี','PART',2000,'2016-10-28 15:32:29'),(588,182,NULL,'ค่ากระจก','PART',1000,'2016-10-28 15:32:29'),(589,183,1,'ค่าพ่นสี','MAINTENANCE',100,'2016-10-28 15:35:21'),(590,183,NULL,'ค่าพ่นสี','MAINTENANCE',100,'2016-10-28 15:35:54'),(591,183,NULL,'ค่าสี','PART',2000,'2016-10-28 15:35:54'),(592,183,NULL,'ค่าน้ำมันเครื่อง','PART',250,'2016-10-28 15:35:54'),(593,183,NULL,'ค่ายางนอก','PART',250,'2016-10-28 15:35:54'),(594,184,1,'ค่าเปลี่ยนยาง','MAINTENANCE',100,'2016-10-30 09:51:53'),(595,184,1,'ค่าเคาะสี','MAINTENANCE',100,'2016-10-30 09:51:53'),(596,184,1,'ค่าพ่นสี','MAINTENANCE',100,'2016-10-30 09:51:54'),(597,184,1,'ค่ายางนอก','PART',250,'2016-10-30 09:51:54'),(598,184,1,'ค่าน้ำมันเครื่อง','PART',250,'2016-10-30 09:51:54'),(599,184,1,'ค่าสี','PART',1000,'2016-10-30 09:51:54'),(600,184,1,'ค่ากระจก','PART',1000,'2016-10-30 09:51:54'),(601,184,1,'ค่าไฟท้าย','PART',1000,'2016-10-30 09:51:54'),(602,185,1,'ค่าเปลี่ยนยาง','MAINTENANCE',100,'2016-10-30 09:56:19'),(603,185,1,'ค่าเคาะสี','MAINTENANCE',100,'2016-10-30 09:56:19'),(604,185,1,'ค่าพ่นสี','MAINTENANCE',100,'2016-10-30 09:56:19'),(605,185,1,'ค่ายางนอก','PART',250,'2016-10-30 09:56:19'),(606,185,1,'ค่าสี','PART',1000,'2016-10-30 09:56:19');
/*!40000 ALTER TABLE `description` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `invoice`
--

DROP TABLE IF EXISTS `invoice`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `invoice` (
  `IID` int(11) NOT NULL AUTO_INCREMENT,
  `CID` int(11) NOT NULL,
  `invoice_id` text,
  `date` date DEFAULT NULL,
  `employee` int(11) DEFAULT NULL,
  PRIMARY KEY (`IID`),
  KEY `fk_invoice_customer1_idx` (`CID`),
  CONSTRAINT `fk_invoice_customer1` FOREIGN KEY (`CID`) REFERENCES `customer` (`CID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invoice`
--

LOCK TABLES `invoice` WRITE;
/*!40000 ALTER TABLE `invoice` DISABLE KEYS */;
INSERT INTO `invoice` VALUES (1,349,NULL,'2016-11-01',100),(2,349,NULL,'2016-11-01',100),(3,349,NULL,'2016-11-01',100),(4,349,NULL,'2016-11-01',100),(5,349,NULL,'2016-11-01',100),(6,349,NULL,'2016-11-01',100),(7,349,NULL,'2016-11-01',100),(8,349,NULL,'2016-11-01',100),(9,349,NULL,'2016-11-01',100),(10,349,NULL,'2016-11-01',100),(11,349,NULL,'2016-11-01',100),(12,349,NULL,'2016-11-01',100),(13,349,NULL,'2016-11-01',100),(14,349,NULL,'2016-11-01',100),(15,349,NULL,'2016-11-01',100),(16,369,NULL,'2016-11-05',100),(17,369,NULL,'2016-11-05',100),(18,369,NULL,'2016-11-05',100),(19,369,NULL,'2016-11-05',100),(20,369,NULL,'2016-11-05',100),(21,349,NULL,'2016-11-05',100),(22,349,NULL,'2016-11-05',100),(23,349,NULL,'2016-11-05',100);
/*!40000 ALTER TABLE `invoice` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `invoice_description`
--

DROP TABLE IF EXISTS `invoice_description`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `invoice_description` (
  `idid` int(11) NOT NULL AUTO_INCREMENT,
  `IID` int(11) NOT NULL,
  `description` text,
  `price` int(11) DEFAULT NULL,
  PRIMARY KEY (`idid`,`IID`),
  KEY `fk_invoice_description_invoice1_idx` (`IID`),
  CONSTRAINT `fk_invoice_description_invoice1` FOREIGN KEY (`IID`) REFERENCES `invoice` (`IID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invoice_description`
--

LOCK TABLES `invoice_description` WRITE;
/*!40000 ALTER TABLE `invoice_description` DISABLE KEYS */;
INSERT INTO `invoice_description` VALUES (1,3,'test1',100),(2,3,'test2',200),(3,4,'test1',100),(4,4,'test2',200),(5,5,'test1',100),(6,5,'test2',200),(7,6,'test1',100),(8,6,'test2',200),(9,7,'test1',100),(10,7,'test2',200),(11,8,'test1',100),(12,8,'test2',200),(13,9,'test1',100),(14,9,'test2',200),(15,10,'test1',100),(16,10,'test2',200),(17,11,'test1',100),(18,11,'test2',200),(19,12,'test1',100),(20,12,'test2',200),(21,13,'test1',100),(22,13,'test2',200),(23,14,'test1',100),(24,14,'test2',200),(25,15,'test1',100),(26,15,'test2',200),(27,16,'desc1',100),(28,16,'desc2',200),(29,17,'desc1',100),(30,17,'desc2',200),(31,18,'desc1',100),(32,18,'desc2',200),(33,19,'desc1',100),(34,19,'desc2',200),(35,20,'desc1',100),(36,20,'desc2',200),(37,21,'desc 1',100),(38,21,'desc 2',200),(39,22,'desc 1',NULL),(40,22,'desc 2',200),(41,23,'desc 1',100),(42,23,'desc 2',200);
/*!40000 ALTER TABLE `invoice_description` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `organization`
--

DROP TABLE IF EXISTS `organization`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `organization` (
  `OID` int(11) NOT NULL AUTO_INCREMENT,
  `name` text,
  `address` text,
  `phone` int(11) DEFAULT NULL,
  `fax` int(11) DEFAULT NULL,
  PRIMARY KEY (`OID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `organization`
--

LOCK TABLES `organization` WRITE;
/*!40000 ALTER TABLE `organization` DISABLE KEYS */;
/*!40000 ALTER TABLE `organization` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `quotation`
--

DROP TABLE IF EXISTS `quotation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `quotation` (
  `QID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสใบเสนอราคา',
  `CID` int(11) DEFAULT NULL COMMENT 'รหัสลูกค้า',
  `VID` int(11) DEFAULT NULL COMMENT 'รหัสรถ',
  `Employee` text COMMENT 'พนักงาน',
  `TID` int(11) DEFAULT NULL,
  `quotation_id` text COMMENT 'รหัสใบเสนอราคาอ้างอิง',
  `quotation_date` date DEFAULT NULL COMMENT 'วันทีทำรายการ',
  `claim_no` text COMMENT 'เลขที่เคลม',
  `damage_level` int(11) DEFAULT NULL,
  `damage_position` int(11) NOT NULL,
  PRIMARY KEY (`QID`),
  KEY `fk_quotation_customer1_idx` (`CID`),
  KEY `fk_quotation_test1_idx` (`VID`),
  KEY `fk_quotation_damage_position1_idx` (`damage_position`),
  CONSTRAINT `fk_quotation_customer1` FOREIGN KEY (`CID`) REFERENCES `customer` (`CID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_quotation_damage_position1` FOREIGN KEY (`damage_position`) REFERENCES `damage_position` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_quotation_test1` FOREIGN KEY (`VID`) REFERENCES `viecle` (`VID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=186 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `quotation`
--

LOCK TABLES `quotation` WRITE;
/*!40000 ALTER TABLE `quotation` DISABLE KEYS */;
INSERT INTO `quotation` VALUES (162,349,220,NULL,NULL,'1/2559','2016-10-18','CL1234',3,1),(163,346,220,'100',NULL,NULL,'2016-10-19','CL001',1,1),(164,349,220,'100',NULL,NULL,'2016-10-19','CL002',1,1),(165,349,220,'100',NULL,NULL,'2016-10-20','CL002',1,1),(166,349,220,'100',NULL,NULL,'2016-10-20','CL003',1,1),(167,349,220,'100',NULL,NULL,'2016-10-20','CL004',1,1),(168,349,220,'100',NULL,NULL,'2016-10-20','CL005',1,1),(169,349,220,'100',NULL,NULL,'2016-10-20','CL005',1,1),(170,349,220,'100',NULL,NULL,'2016-10-20','CL006',3,1),(171,348,220,'100',NULL,NULL,'2016-10-27','FINIX1234',1,3),(172,348,220,'100',NULL,NULL,'2016-10-27','FINIX1234',1,3),(173,348,220,'100',NULL,NULL,'2016-10-27','FINIX1234',1,3),(174,347,220,'100',NULL,NULL,'2016-10-27','A1234',1,1),(175,349,220,'100',NULL,NULL,'2016-10-27','TEST1234',1,1),(176,349,220,'100',NULL,NULL,'2016-10-27','TEST1234',1,1),(177,349,220,'100',NULL,NULL,'2016-10-27','TEST002',1,1),(178,346,220,'100',NULL,NULL,'2016-10-27','TEST003',1,1),(179,347,220,'100',NULL,'18/2559','2016-10-27','TEST004',1,1),(180,349,220,'100',NULL,'19/2559','2016-10-27','TEST005',1,1),(181,346,220,'100',NULL,'20/2559','2016-10-27','TEST006',1,1),(182,346,220,'100',NULL,'21/2559','2016-10-28','X001',1,1),(183,349,220,'100',NULL,'22/2559','2016-10-28','X002',1,1),(184,349,220,'100',NULL,'23/2559','2016-10-30','AUTO001',1,1),(185,349,220,'100',NULL,'24/2559','2016-10-30','AUTO002',1,1);
/*!40000 ALTER TABLE `quotation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `viecle`
--

DROP TABLE IF EXISTS `viecle`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `viecle` (
  `VID` int(11) NOT NULL AUTO_INCREMENT,
  `plate_no` text COMMENT 'เลขทะเบียน',
  `viecle_name` int(11) DEFAULT NULL,
  `viecle_model` int(11) DEFAULT NULL,
  `body_code` text COMMENT 'เลขตัวถัง',
  `engin_code` text COMMENT 'เลขเครื่องยนต์',
  `viecle_year` int(11) DEFAULT NULL COMMENT 'ปี',
  `body_type` int(11) DEFAULT NULL,
  `cc` int(11) DEFAULT NULL COMMENT 'ซีซี',
  `seat` int(11) DEFAULT NULL COMMENT 'ที่นั่ง',
  `weight` int(11) DEFAULT NULL COMMENT 'น้ำหนักรวม',
  `owner` int(11) DEFAULT NULL,
  PRIMARY KEY (`VID`),
  KEY `fk_viecle_customer1_idx` (`owner`),
  KEY `fk_viecle_viecle_name1_idx` (`viecle_name`),
  KEY `fk_viecle_viecle_model1_idx` (`viecle_model`),
  KEY `fk_viecle_body_type1_idx` (`body_type`),
  CONSTRAINT `fk_viecle_body_type1` FOREIGN KEY (`body_type`) REFERENCES `body_type` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_viecle_customer1` FOREIGN KEY (`owner`) REFERENCES `customer` (`CID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_viecle_viecle_model1` FOREIGN KEY (`viecle_model`) REFERENCES `viecle_model` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_viecle_viecle_name1` FOREIGN KEY (`viecle_name`) REFERENCES `viecle_name` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=320 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `viecle`
--

LOCK TABLES `viecle` WRITE;
/*!40000 ALTER TABLE `viecle` DISABLE KEYS */;
INSERT INTO `viecle` VALUES (220,'กก 0001',1,2,'BODY1234/แก้2','ENGIN1234/แก้ไข2',2012,2,300,4,1000,349),(221,'กก 0002',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(222,'กก 0003',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(223,'กก 0004',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(224,'กก 0005',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(225,'กก 0006',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(226,'กก 0007',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(227,'กก 0008',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(228,'กก 0009',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(229,'กก 0010',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(230,'กก 0011',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(231,'กก 0012',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(232,'กก 0013',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(233,'กก 0014',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(234,'กก 0015',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(235,'กก 0016',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(236,'กก 0017',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(237,'กก 0018',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(238,'กก 0019',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(239,'กก 0020',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(240,'กก 0021',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(241,'กก 0022',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(242,'กก 0023',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(243,'กก 0024',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(244,'กก 0025',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(245,'กก 0026',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(246,'กก 0027',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(247,'กก 0028',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(248,'กก 0029',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(249,'กก 0030',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(250,'กก 0031',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(251,'กก 0032',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(252,'กก 0033',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(253,'กก 0034',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(254,'กก 0035',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(255,'กก 0036',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(256,'กก 0037',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(257,'กก 0038',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(258,'กก 0039',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(259,'กก 0040',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(260,'กก 0041',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(261,'กก 0042',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(262,'กก 0043',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(263,'กก 0044',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(264,'กก 0045',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(265,'กก 0046',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(266,'กก 0047',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(267,'กก 0048',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(268,'กก 0049',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(269,'กก 0050',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(270,'กก 0051',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(271,'กก 0052',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(272,'กก 0053',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(273,'กก 0054',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(274,'กก 0055',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(275,'กก 0056',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(276,'กก 0057',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(277,'กก 0058',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(278,'กก 0059',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(279,'กก 0060',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(280,'กก 0061',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(281,'กก 0062',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(282,'กก 0063',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(283,'กก 0064',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(284,'กก 0065',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(285,'กก 0066',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(286,'กก 0067',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(287,'กก 0068',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(288,'กก 0069',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(289,'กก 0070',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(290,'กก 0071',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(291,'กก 0072',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(292,'กก 0073',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(293,'กก 0074',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(294,'กก 0075',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(295,'กก 0076',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(296,'กก 0077',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(297,'กก 0078',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(298,'กก 0079',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(299,'กก 0080',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(300,'กก 0081',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(301,'กก 0082',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(302,'กก 0083',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(303,'กก 0084',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(304,'กก 0085',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(305,'กก 0086',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(306,'กก 0087',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(307,'กก 0088',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(308,'กก 0089',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(309,'กก 0090',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(310,'กก 0091',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(311,'กก 0092',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(312,'กก 0093',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(313,'กก 0094',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(314,'กก 0095',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(315,'กก 0096',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(316,'กก 0097',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(317,'กก 0098',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(318,'กก 0099',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(319,'',1,NULL,'','',NULL,2,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `viecle` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `viecle_model`
--

DROP TABLE IF EXISTS `viecle_model`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `viecle_model` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `viecle_name` int(11) NOT NULL,
  `model` text,
  PRIMARY KEY (`id`),
  KEY `fk_viecle_model_viecle_name1_idx` (`viecle_name`),
  CONSTRAINT `fk_viecle_model_viecle_name1` FOREIGN KEY (`viecle_name`) REFERENCES `viecle_name` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `viecle_model`
--

LOCK TABLES `viecle_model` WRITE;
/*!40000 ALTER TABLE `viecle_model` DISABLE KEYS */;
INSERT INTO `viecle_model` VALUES (2,1,'Vios'),(3,1,'Yaris'),(4,1,'Corolla Altis'),(5,1,'Vios TRD Sportivo'),(6,1,'Yaris Sportivo'),(7,1,'Corolla Altis ESport'),(8,1,'Hilux Revo'),(9,1,'Avanza'),(10,1,'Fortuner'),(11,1,'Hilux Vigo Sportivo'),(12,1,'Innova'),(13,1,'Fortuner Sportivo'),(14,1,'Prius'),(15,1,'Camry'),(16,1,'Commuter'),(17,1,'Prius C'),(18,1,'86'),(19,1,'Hiace'),(20,1,'Ventury'),(21,1,'Alphard');
/*!40000 ALTER TABLE `viecle_model` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `viecle_name`
--

DROP TABLE IF EXISTS `viecle_name`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `viecle_name` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `viecle_name`
--

LOCK TABLES `viecle_name` WRITE;
/*!40000 ALTER TABLE `viecle_name` DISABLE KEYS */;
INSERT INTO `viecle_name` VALUES (1,'Toyota – โตโยต้า'),(2,'Honda – ฮอนด้า'),(3,'Isuzu – อีซูซุ'),(4,'Mitsubishi – มิตซูบิชิ'),(5,'Nissan – นิสสัน'),(6,'Mazda – มาสด้า'),(7,'Ford – ฟอร์ด'),(8,'Chevrolet – เชฟโรเลต'),(9,'Suzuki – ซูซูกิ'),(10,'Hyundai – ฮุนได'),(11,'MG – เอ็มจี');
/*!40000 ALTER TABLE `viecle_name` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-11-05 21:28:56
