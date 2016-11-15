-- MySQL dump 10.13  Distrib 5.7.12, for Win32 (AMD64)
--
-- Host: localhost    Database: garage
-- ------------------------------------------------------
-- Server version	5.7.14

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
-- Table structure for table `claim`
--

DROP TABLE IF EXISTS `claim`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `claim` (
  `CLID` int(11) NOT NULL AUTO_INCREMENT,
  `claim_no` text NOT NULL,
  PRIMARY KEY (`CLID`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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
  `phone2` text,
  `branch` text,
  PRIMARY KEY (`CID`)
) ENGINE=InnoDB AUTO_INCREMENT=387 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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
) ENGINE=InnoDB AUTO_INCREMENT=680 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `employee`
--

DROP TABLE IF EXISTS `employee`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `employee` (
  `EID` int(11) NOT NULL AUTO_INCREMENT,
  `fullname` text,
  `Position` text,
  PRIMARY KEY (`EID`)
) ENGINE=InnoDB AUTO_INCREMENT=103 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `invoice`
--

DROP TABLE IF EXISTS `invoice`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `invoice` (
  `IID` int(11) NOT NULL AUTO_INCREMENT,
  `CID` int(11) NOT NULL,
  `VID` int(11) NOT NULL,
  `CLID` int(11) NOT NULL,
  `book_number` text,
  `invoice_id` text,
  `date` date DEFAULT NULL,
  `EID` int(11) NOT NULL,
  PRIMARY KEY (`IID`),
  KEY `fk_invoice_customer1_idx` (`CID`),
  KEY `fk_invoice_viecle1_idx` (`VID`),
  KEY `fk_invoice_Employee1_idx` (`EID`),
  KEY `fk_invoice_claim1_idx` (`CLID`),
  CONSTRAINT `fk_invoice_Employee1` FOREIGN KEY (`EID`) REFERENCES `employee` (`EID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_invoice_claim1` FOREIGN KEY (`CLID`) REFERENCES `claim` (`CLID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_invoice_customer1` FOREIGN KEY (`CID`) REFERENCES `customer` (`CID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_invoice_viecle1` FOREIGN KEY (`VID`) REFERENCES `viecle` (`VID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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
  `price` float DEFAULT NULL,
  `date` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`idid`,`IID`),
  KEY `fk_invoice_description_invoice1_idx` (`IID`),
  CONSTRAINT `fk_invoice_description_invoice1` FOREIGN KEY (`IID`) REFERENCES `invoice` (`IID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=141 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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
-- Table structure for table `photo`
--

DROP TABLE IF EXISTS `photo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `photo` (
  `PID` int(11) NOT NULL AUTO_INCREMENT,
  `CLID` int(11) NOT NULL,
  `filename` text NOT NULL,
  `last_update` timestamp NULL DEFAULT NULL,
  `type` varchar(45) DEFAULT NULL,
  `order` int(11) DEFAULT NULL,
  PRIMARY KEY (`PID`),
  KEY `fk_photo_claim1_idx` (`CLID`),
  CONSTRAINT `fk_photo_claim1` FOREIGN KEY (`CLID`) REFERENCES `claim` (`CLID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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
  `TID` int(11) DEFAULT NULL,
  `quotation_id` text COMMENT 'รหัสใบเสนอราคาอ้างอิง',
  `quotation_date` date DEFAULT NULL COMMENT 'วันทีทำรายการ',
  `CLID` int(11) NOT NULL,
  `damage_level` int(11) DEFAULT NULL,
  `damage_position` int(11) NOT NULL,
  `EID` int(11) NOT NULL,
  PRIMARY KEY (`QID`),
  KEY `fk_quotation_customer1_idx` (`CID`),
  KEY `fk_quotation_test1_idx` (`VID`),
  KEY `fk_quotation_damage_position1_idx` (`damage_position`),
  KEY `fk_quotation_Employee1_idx` (`EID`),
  KEY `fk_quotation_claim1_idx` (`CLID`),
  CONSTRAINT `fk_quotation_Employee1` FOREIGN KEY (`EID`) REFERENCES `employee` (`EID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_quotation_claim1` FOREIGN KEY (`CLID`) REFERENCES `claim` (`CLID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_quotation_customer1` FOREIGN KEY (`CID`) REFERENCES `customer` (`CID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_quotation_damage_position1` FOREIGN KEY (`damage_position`) REFERENCES `damage_position` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_quotation_test1` FOREIGN KEY (`VID`) REFERENCES `viecle` (`VID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=198 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `reciept`
--

DROP TABLE IF EXISTS `reciept`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reciept` (
  `RID` int(11) NOT NULL AUTO_INCREMENT,
  `IID` int(11) NOT NULL,
  `book_number` text,
  `reciept_id` text,
  `date` timestamp NULL DEFAULT NULL,
  `total` int(11) DEFAULT NULL,
  `EID` int(11) DEFAULT NULL,
  PRIMARY KEY (`RID`),
  KEY `fk_Reciept_invoice1_idx` (`IID`),
  KEY `fk_Reciept_Employee1_idx` (`EID`),
  CONSTRAINT `fk_Reciept_Employee1` FOREIGN KEY (`EID`) REFERENCES `employee` (`EID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_Reciept_invoice1` FOREIGN KEY (`IID`) REFERENCES `invoice` (`IID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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
  `body_type` text,
  `cc` int(11) DEFAULT NULL COMMENT 'ซีซี',
  `seat` int(11) DEFAULT NULL COMMENT 'ที่นั่ง',
  `weight` int(11) DEFAULT NULL COMMENT 'น้ำหนักรวม',
  `owner` int(11) DEFAULT NULL,
  PRIMARY KEY (`VID`),
  KEY `fk_viecle_customer1_idx` (`owner`),
  KEY `fk_viecle_viecle_name1_idx` (`viecle_name`),
  KEY `fk_viecle_viecle_model1_idx` (`viecle_model`),
  CONSTRAINT `fk_viecle_customer1` FOREIGN KEY (`owner`) REFERENCES `customer` (`CID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_viecle_viecle_model1` FOREIGN KEY (`viecle_model`) REFERENCES `viecle_model` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_viecle_viecle_name1` FOREIGN KEY (`viecle_name`) REFERENCES `viecle_name` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=331 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-11-15 11:23:49
