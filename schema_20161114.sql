-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema garage
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema garage
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `garage` DEFAULT CHARACTER SET utf8 ;
USE `garage` ;

-- -----------------------------------------------------
-- Table `garage`.`body_type`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `garage`.`body_type` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `type` TEXT NULL DEFAULT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 7
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `garage`.`customer`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `garage`.`customer` (
  `CID` INT(11) NOT NULL AUTO_INCREMENT,
  `fullname` TEXT NULL DEFAULT NULL,
  `type` TEXT NULL DEFAULT NULL,
  `address` TEXT NULL DEFAULT NULL,
  `phone` TEXT NULL DEFAULT NULL,
  `fax` TEXT NULL DEFAULT NULL,
  `taxpayer_id` TEXT NULL DEFAULT NULL COMMENT 'เลขประจำตัวผู้เสียภาษี',
  PRIMARY KEY (`CID`))
ENGINE = InnoDB
AUTO_INCREMENT = 371
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `garage`.`damage_position`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `garage`.`damage_position` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `position` TEXT NULL DEFAULT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 7
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `garage`.`viecle_name`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `garage`.`viecle_name` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` TEXT NULL DEFAULT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 12
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `garage`.`viecle_model`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `garage`.`viecle_model` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `viecle_name` INT(11) NOT NULL,
  `model` TEXT NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_viecle_model_viecle_name1_idx` (`viecle_name` ASC),
  CONSTRAINT `fk_viecle_model_viecle_name1`
    FOREIGN KEY (`viecle_name`)
    REFERENCES `garage`.`viecle_name` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 22
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `garage`.`viecle`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `garage`.`viecle` (
  `VID` INT(11) NOT NULL AUTO_INCREMENT,
  `plate_no` TEXT NULL DEFAULT NULL COMMENT 'เลขทะเบียน',
  `viecle_name` INT(11) NULL DEFAULT NULL,
  `viecle_model` INT(11) NULL DEFAULT NULL,
  `body_code` TEXT NULL DEFAULT NULL COMMENT 'เลขตัวถัง',
  `engin_code` TEXT NULL DEFAULT NULL COMMENT 'เลขเครื่องยนต์',
  `viecle_year` INT(11) NULL DEFAULT NULL COMMENT 'ปี',
  `body_type` INT(11) NULL DEFAULT NULL,
  `cc` INT(11) NULL DEFAULT NULL COMMENT 'ซีซี',
  `seat` INT(11) NULL DEFAULT NULL COMMENT 'ที่นั่ง',
  `weight` INT(11) NULL DEFAULT NULL COMMENT 'น้ำหนักรวม',
  `owner` INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`VID`),
  INDEX `fk_viecle_customer1_idx` (`owner` ASC),
  INDEX `fk_viecle_viecle_name1_idx` (`viecle_name` ASC),
  INDEX `fk_viecle_viecle_model1_idx` (`viecle_model` ASC),
  INDEX `fk_viecle_body_type1_idx` (`body_type` ASC),
  CONSTRAINT `fk_viecle_body_type1`
    FOREIGN KEY (`body_type`)
    REFERENCES `garage`.`body_type` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_viecle_customer1`
    FOREIGN KEY (`owner`)
    REFERENCES `garage`.`customer` (`CID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_viecle_viecle_model1`
    FOREIGN KEY (`viecle_model`)
    REFERENCES `garage`.`viecle_model` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_viecle_viecle_name1`
    FOREIGN KEY (`viecle_name`)
    REFERENCES `garage`.`viecle_name` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 320
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `garage`.`Employee`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `garage`.`Employee` (
  `EID` INT NOT NULL AUTO_INCREMENT,
  `fullname` TEXT NULL,
  `Position` TEXT NULL,
  PRIMARY KEY (`EID`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `garage`.`claim`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `garage`.`claim` (
  `CLID` INT NOT NULL AUTO_INCREMENT,
  `claim_no` TEXT NOT NULL,
  PRIMARY KEY (`CLID`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `garage`.`quotation`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `garage`.`quotation` (
  `QID` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสใบเสนอราคา',
  `CID` INT(11) NULL DEFAULT NULL COMMENT 'รหัสลูกค้า',
  `VID` INT(11) NULL DEFAULT NULL COMMENT 'รหัสรถ',
  `TID` INT(11) NULL DEFAULT NULL,
  `quotation_id` TEXT NULL DEFAULT NULL COMMENT 'รหัสใบเสนอราคาอ้างอิง',
  `quotation_date` DATE NULL DEFAULT NULL COMMENT 'วันทีทำรายการ',
  `CLID` INT NOT NULL,
  `damage_level` INT(11) NULL DEFAULT NULL,
  `damage_position` INT(11) NOT NULL,
  `EID` INT NOT NULL,
  PRIMARY KEY (`QID`),
  INDEX `fk_quotation_customer1_idx` (`CID` ASC),
  INDEX `fk_quotation_test1_idx` (`VID` ASC),
  INDEX `fk_quotation_damage_position1_idx` (`damage_position` ASC),
  INDEX `fk_quotation_Employee1_idx` (`EID` ASC),
  INDEX `fk_quotation_claim1_idx` (`CLID` ASC),
  CONSTRAINT `fk_quotation_customer1`
    FOREIGN KEY (`CID`)
    REFERENCES `garage`.`customer` (`CID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_quotation_damage_position1`
    FOREIGN KEY (`damage_position`)
    REFERENCES `garage`.`damage_position` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_quotation_test1`
    FOREIGN KEY (`VID`)
    REFERENCES `garage`.`viecle` (`VID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_quotation_Employee1`
    FOREIGN KEY (`EID`)
    REFERENCES `garage`.`Employee` (`EID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_quotation_claim1`
    FOREIGN KEY (`CLID`)
    REFERENCES `garage`.`claim` (`CLID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 186
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `garage`.`description`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `garage`.`description` (
  `DID` INT(11) NOT NULL AUTO_INCREMENT,
  `QID` INT(11) NOT NULL,
  `row` INT(11) NULL DEFAULT NULL,
  `description` TEXT NULL DEFAULT NULL COMMENT 'รายการ',
  `type` TEXT NULL DEFAULT NULL COMMENT 'ประเภท',
  `price` FLOAT NULL DEFAULT NULL COMMENT 'ราคา',
  `date` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`DID`),
  INDEX `fk_quotation_description_quotation1_idx` (`QID` ASC),
  CONSTRAINT `fk_quotation_description_quotation1`
    FOREIGN KEY (`QID`)
    REFERENCES `garage`.`quotation` (`QID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 607
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `garage`.`invoice`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `garage`.`invoice` (
  `IID` INT(11) NOT NULL AUTO_INCREMENT,
  `CID` INT(11) NOT NULL,
  `VID` INT(11) NOT NULL,
  `CLID` INT NOT NULL,
  `invoice_id` TEXT NULL DEFAULT NULL,
  `date` DATE NULL DEFAULT NULL,
  `EID` INT NOT NULL,
  PRIMARY KEY (`IID`),
  INDEX `fk_invoice_customer1_idx` (`CID` ASC),
  INDEX `fk_invoice_viecle1_idx` (`VID` ASC),
  INDEX `fk_invoice_Employee1_idx` (`EID` ASC),
  INDEX `fk_invoice_claim1_idx` (`CLID` ASC),
  CONSTRAINT `fk_invoice_customer1`
    FOREIGN KEY (`CID`)
    REFERENCES `garage`.`customer` (`CID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_invoice_viecle1`
    FOREIGN KEY (`VID`)
    REFERENCES `garage`.`viecle` (`VID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_invoice_Employee1`
    FOREIGN KEY (`EID`)
    REFERENCES `garage`.`Employee` (`EID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_invoice_claim1`
    FOREIGN KEY (`CLID`)
    REFERENCES `garage`.`claim` (`CLID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 24
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `garage`.`invoice_description`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `garage`.`invoice_description` (
  `idid` INT(11) NOT NULL AUTO_INCREMENT,
  `IID` INT(11) NOT NULL,
  `description` TEXT NULL DEFAULT NULL,
  `price` FLOAT NULL DEFAULT NULL,
  `date` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`idid`, `IID`),
  INDEX `fk_invoice_description_invoice1_idx` (`IID` ASC),
  CONSTRAINT `fk_invoice_description_invoice1`
    FOREIGN KEY (`IID`)
    REFERENCES `garage`.`invoice` (`IID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 62
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `garage`.`organization`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `garage`.`organization` (
  `OID` INT(11) NOT NULL AUTO_INCREMENT,
  `name` TEXT NULL DEFAULT NULL,
  `address` TEXT NULL DEFAULT NULL,
  `phone` INT(11) NULL DEFAULT NULL,
  `fax` INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`OID`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `garage`.`Reciept`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `garage`.`Reciept` (
  `RID` INT NOT NULL AUTO_INCREMENT,
  `IID` INT(11) NOT NULL,
  `reciept_id` TEXT NULL,
  `date` TIMESTAMP NULL,
  `total` INT NULL,
  `EID` INT NULL,
  PRIMARY KEY (`RID`),
  INDEX `fk_Reciept_invoice1_idx` (`IID` ASC),
  INDEX `fk_Reciept_Employee1_idx` (`EID` ASC),
  CONSTRAINT `fk_Reciept_invoice1`
    FOREIGN KEY (`IID`)
    REFERENCES `garage`.`invoice` (`IID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Reciept_Employee1`
    FOREIGN KEY (`EID`)
    REFERENCES `garage`.`Employee` (`EID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `garage`.`photo`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `garage`.`photo` (
  `PID` INT NOT NULL AUTO_INCREMENT,
  `CLID` INT NOT NULL,
  `filename` TEXT NOT NULL,
  `last_update` TIMESTAMP NULL,
  `type` VARCHAR(45) NULL,
  `order` INT NULL,
  PRIMARY KEY (`PID`),
  INDEX `fk_photo_claim1_idx` (`CLID` ASC),
  CONSTRAINT `fk_photo_claim1`
    FOREIGN KEY (`CLID`)
    REFERENCES `garage`.`claim` (`CLID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
