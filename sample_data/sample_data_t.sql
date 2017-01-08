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
-- Dumping data for table `damage_position`
--

LOCK TABLES `damage_position` WRITE;
/*!40000 ALTER TABLE `damage_position` DISABLE KEYS */;
INSERT INTO `damage_position` VALUES (1,'หน้า'),(2,'ข้างซ้าย'),(3,'ข้างขวา'),(4,'บน'),(5,'ล่าง'),(6,'หลัง');
/*!40000 ALTER TABLE `damage_position` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `employee`
--

LOCK TABLES `employee` WRITE;
/*!40000 ALTER TABLE `employee` DISABLE KEYS */;
INSERT INTO `employee` VALUES (100,'ADMIN','ADMIN');
/*!40000 ALTER TABLE `employee` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `viecle_model`
--

LOCK TABLES `viecle_model` WRITE;
/*!40000 ALTER TABLE `viecle_model` DISABLE KEYS */;
INSERT INTO `viecle_model` VALUES (2,1,'Vios'),(3,1,'Yaris'),(4,1,'Corolla Altis'),(5,1,'Vios TRD Sportivo'),(6,1,'Yaris Sportivo'),(7,1,'Corolla Altis ESport'),(8,1,'Hilux Revo'),(9,1,'Avanza'),(10,1,'Fortuner'),(11,1,'Hilux Vigo Sportivo'),(12,1,'Innova'),(13,1,'Fortuner Sportivo'),(14,1,'Prius'),(15,1,'Camry'),(16,1,'Commuter'),(17,1,'Prius C'),(18,1,'86'),(19,1,'Hiace'),(20,1,'Ventury'),(21,1,'Alphard'),(22,2,'Accord'),(23,2,'Brio'),(24,14,'รุ่น 1-1'),(25,14,'รุ่น 1-2'),(26,15,'รุ่น 2-1'),(27,4,'ทดสอบ');
/*!40000 ALTER TABLE `viecle_model` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `viecle_name`
--

LOCK TABLES `viecle_name` WRITE;
/*!40000 ALTER TABLE `viecle_name` DISABLE KEYS */;
INSERT INTO `viecle_name` VALUES (1,'Toyota – โตโยต้า'),(2,'Honda – ฮอนด้า'),(3,'Isuzu – อีซูซุ'),(4,'Mitsubishi – มิตซูบิชิ'),(5,'Nissan – นิสสัน'),(6,'Mazda – มาสด้า'),(7,'Ford – ฟอร์ด'),(8,'Chevrolet – เชฟโรเลต'),(9,'Suzuki – ซูซูกิ'),(10,'Hyundai – ฮุนได'),(11,'MG – เอ็มจี'),(14,'ชื่อทดสอบ 1'),(15,'ชื่อทดสอบ 2');
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

-- Dump completed on 2016-11-15 11:25:53


/*
-- Query: SELECT * FROM garage.customer where type = 'INSURANCE_COMP'
LIMIT 0, 1000

-- Date: 2016-11-14 12:30
*/
INSERT INTO `customer` (`CID`,`fullname`,`type`,`address`,`phone`,`fax`,`taxpayer_id`) VALUES (346,'บริษัท วิริยะประกันภัย จำกัด','INSURANCE_COMP','10/7 ถนนราชปรารภ แขวงพญาไท เขตราชเทวี กรุงเทพฯ 10400','022391557','','001');
INSERT INTO `customer` (`CID`,`fullname`,`type`,`address`,`phone`,`fax`,`taxpayer_id`) VALUES (347,'บริษัท อาคเนย์ประกันภัย จำกัด','INSURANCE_COMP','อาคารอาคเนย์ประกันภัย 315 ชั้น จี 1-3 ถนนสีลม แขวงสีลม เขตบางรัก กรุงเทพฯ 10500','022677777','022377409','002');
INSERT INTO `customer` (`CID`,`fullname`,`type`,`address`,`phone`,`fax`,`taxpayer_id`) VALUES (348,'บริษัท ฟินิกซ์ประกันภัย (ประเทศไทย) จำกัด','INSURANCE_COMP','38 อาคารปรีชาคอมเพล็กซ์ ถ.รัชดาภิเษก แขวงสามเสนนอก เขตห้วยขวาง กรุงเทพฯ','022900544','','003');
