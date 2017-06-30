CREATE DATABASE  IF NOT EXISTS `production_suite` /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_spanish_ci */;
USE `production_suite`;
-- MySQL dump 10.13  Distrib 5.5.31, for debian-linux-gnu (i686)
--
-- Host: localhost    Database: production_suite
-- ------------------------------------------------------
-- Server version	5.5.31-0ubuntu0.12.04.2

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
-- Table structure for table `centro_produccion`
--

DROP TABLE IF EXISTS `centro_produccion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `centro_produccion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `centro` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `centro_produccion`
--

LOCK TABLES `centro_produccion` WRITE;
/*!40000 ALTER TABLE `centro_produccion` DISABLE KEYS */;
INSERT INTO `centro_produccion` VALUES (1,'Colombia'),(2,'Venezuela');
/*!40000 ALTER TABLE `centro_produccion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dias_grabacion`
--

DROP TABLE IF EXISTS `dias_grabacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dias_grabacion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dias_grabacion` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `lunes` int(11) DEFAULT NULL,
  `martes` int(11) DEFAULT NULL,
  `miercoles` int(11) DEFAULT NULL,
  `jueves` int(11) DEFAULT NULL,
  `viernes` int(11) DEFAULT NULL,
  `sabado` int(11) DEFAULT NULL,
  `domingo` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dias_grabacion`
--

LOCK TABLES `dias_grabacion` WRITE;
/*!40000 ALTER TABLE `dias_grabacion` DISABLE KEYS */;
INSERT INTO `dias_grabacion` VALUES (22,'3',1,1,1,0,0,0,0),(23,'2',1,1,0,0,0,0,0),(24,'3',1,1,1,0,0,0,0),(25,'2',1,1,1,0,0,0,0),(26,'2',1,1,1,0,0,0,0),(27,'5',1,1,1,0,0,0,0),(28,'6',1,0,0,0,0,0,0),(29,'6',1,0,0,0,0,0,0),(30,'6',1,0,0,0,0,0,0),(31,'5',1,1,0,0,0,0,0),(32,'3',1,1,0,0,0,0,0),(33,'5',1,1,0,0,0,0,0),(34,'5',1,1,0,0,0,0,0),(35,'5',1,1,0,0,0,0,0),(36,'5',1,1,0,0,0,0,0),(37,'5',1,1,0,0,0,0,0),(38,'5',1,1,0,0,0,0,0),(39,'5',1,1,0,0,0,0,0),(40,'5',1,1,0,0,0,0,0),(41,'6',1,1,1,0,0,0,0),(42,'1',1,1,1,0,0,0,0),(43,'1',1,1,1,0,0,0,0),(44,'3',0,0,0,0,0,0,0),(45,'4',1,1,1,0,0,0,0),(46,'4',1,1,1,0,0,0,0),(47,'4',1,1,1,0,0,0,0),(48,'4',1,1,1,0,0,0,0),(49,'2',1,1,0,0,0,0,0),(50,'2',1,1,1,0,0,0,0),(51,'2',1,1,1,0,0,0,0),(52,'2',1,0,0,1,0,0,0),(53,'2',1,1,1,0,0,0,0),(54,'2',1,1,0,0,0,0,0);
/*!40000 ALTER TABLE `dias_grabacion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `estado_produccion`
--

DROP TABLE IF EXISTS `estado_produccion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `estado_produccion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion_estado` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `estado_produccion`
--

LOCK TABLES `estado_produccion` WRITE;
/*!40000 ALTER TABLE `estado_produccion` DISABLE KEYS */;
INSERT INTO `estado_produccion` VALUES (1,'abierta');
/*!40000 ALTER TABLE `estado_produccion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `estados_capitulo`
--

DROP TABLE IF EXISTS `estados_capitulo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `estados_capitulo` (
  `id` int(11) NOT NULL,
  `descripcion` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `estados_capitulo`
--

LOCK TABLES `estados_capitulo` WRITE;
/*!40000 ALTER TABLE `estados_capitulo` DISABLE KEYS */;
INSERT INTO `estados_capitulo` VALUES (1,'En Progreso'),(2,'Escrito'),(3,'Producido'),(4,'Cancelado');
/*!40000 ALTER TABLE `estados_capitulo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `produccion`
--

DROP TABLE IF EXISTS `produccion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `produccion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_produccion` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `id_centroProduccion` int(11) NOT NULL,
  `id_tipoProduccion` int(11) NOT NULL,
  `inicio_PreProduccion` date DEFAULT NULL,
  `inicio_grabacion` date DEFAULT NULL,
  `fecha_aire` date DEFAULT NULL,
  `fin_grabacion` date DEFAULT NULL,
  `numero_capitulo` int(11) DEFAULT NULL,
  `minuto_capitulo` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `cap_esce_semana` int(11) DEFAULT NULL,
  `min_proy_semana` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `id_dias_grabacion` int(11) DEFAULT NULL,
  `id_productor_ejecutivo` int(11) DEFAULT NULL,
  `id_productor_general` int(11) DEFAULT NULL,
  `id_productor` int(11) DEFAULT NULL,
  `numero_unidades` int(11) DEFAULT NULL,
  `produccion_interior` int(11) DEFAULT NULL,
  `produccion_exterior` int(11) DEFAULT NULL,
  `numero_locaciones` int(11) DEFAULT NULL,
  `numero_set` int(11) DEFAULT NULL,
  `numero_reparto` int(11) DEFAULT NULL,
  `imagen_produccion` varchar(150) COLLATE utf8_spanish_ci DEFAULT NULL,
  `estado` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_produccion_1_idx` (`id_centroProduccion`),
  KEY `fk_produccion_2_idx` (`id_tipoProduccion`),
  KEY `fk_produccion_3_idx` (`id_dias_grabacion`),
  KEY `fk_produccion_3_idx1` (`id_productor_ejecutivo`),
  KEY `fk_produccion_5_idx` (`id_productor_general`),
  KEY `fk_produccion_6_idx` (`id_productor`),
  KEY `fk_produccion_7_idx` (`estado`),
  CONSTRAINT `fk_produccion_1` FOREIGN KEY (`id_centroProduccion`) REFERENCES `centro_produccion` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_produccion_2` FOREIGN KEY (`id_tipoProduccion`) REFERENCES `tipo_produccion` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_produccion_3` FOREIGN KEY (`id_productor_ejecutivo`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_produccion_4` FOREIGN KEY (`id_dias_grabacion`) REFERENCES `dias_grabacion` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_produccion_5` FOREIGN KEY (`id_productor_general`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_produccion_6` FOREIGN KEY (`id_productor`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_produccion_7` FOREIGN KEY (`estado`) REFERENCES `estado_produccion` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=66 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `produccion`
--

LOCK TABLES `produccion` WRITE;
/*!40000 ALTER TABLE `produccion` DISABLE KEYS */;
INSERT INTO `produccion` VALUES (32,'produccion prueba',1,1,'2013-06-12','2013-06-13','2013-06-20','2013-06-19',10,'10',0,'0',24,1,1,1,2,50,50,0,0,0,NULL,1),(33,'produccion 525',1,2,'2013-06-12','2013-06-19','2013-06-19','2013-07-17',10,'10',0,'0',25,1,17,17,1,50,50,0,0,0,NULL,1),(34,'producion alex',1,1,'2013-06-13','2013-06-19','2013-06-27','2013-07-31',10,'100',50,'10',26,1,1,1,2,0,0,0,0,0,NULL,1),(35,'produccion prueba 525',1,2,'2013-06-13','2013-07-24','2013-06-12','2013-12-12',10,'15',20,'25',27,1,1,1,2,50,50,0,0,0,NULL,1),(36,'producion alex',1,2,'2013-06-20','2013-06-20','2013-06-11','2013-07-17',1,'1',1,'1',NULL,NULL,NULL,NULL,0,0,0,0,0,0,NULL,1),(37,'as',1,2,'2013-06-14','2013-06-19','2013-06-20','2013-07-14',1,'1',1,'1',28,9,9,1,0,0,0,0,0,0,NULL,1),(38,'as',1,2,'2013-06-14','2013-06-19','2013-06-20','2013-07-14',1,'1',1,'1',29,9,9,1,0,0,0,0,0,0,NULL,1),(39,'as',1,2,'2013-06-14','2013-06-19','2013-06-20','2013-07-14',1,'1',1,'1',30,9,9,1,0,0,0,0,0,0,NULL,1),(40,'as',2,1,'2013-06-13','2013-06-20','2013-06-18','2013-07-21',1,'1',1,'1',31,1,1,1,1,50,50,0,0,0,NULL,1),(41,'as',1,1,'2013-06-14','2013-06-19','2013-06-20','2013-06-30',1,'1',1,'1',32,NULL,NULL,NULL,0,0,0,0,0,0,NULL,1),(42,'as',1,2,'2013-06-14','2013-06-19','2013-06-20','2013-06-30',1,'1',1,'1',33,1,1,1,1,50,50,0,0,0,NULL,1),(43,'o',1,1,'2013-06-13','2013-07-21','2013-06-21','2013-08-19',10,'10',10,'10',34,1,NULL,NULL,0,50,50,0,0,0,NULL,1),(44,'o',1,1,'2013-06-13','2013-07-21','2013-06-21','2013-08-19',10,'10',10,'10',35,1,NULL,NULL,0,50,50,0,0,0,NULL,1),(45,'o',1,1,'2013-06-13','2013-07-21','2013-06-21','2013-08-19',10,'10',10,'10',36,1,NULL,NULL,0,50,50,0,0,0,NULL,1),(46,'o',1,1,'2013-06-13','2013-07-21','2013-06-21','2013-08-19',10,'10',10,'10',37,1,NULL,NULL,0,50,50,0,0,0,NULL,1),(47,'o',1,1,'2013-06-13','2013-07-21','2013-06-21','2013-08-19',10,'10',10,'10',38,1,NULL,NULL,0,50,50,0,0,0,NULL,1),(48,'o',1,1,'2013-06-13','2013-07-21','2013-06-21','2013-08-19',10,'10',10,'10',39,1,NULL,NULL,0,50,50,0,0,0,NULL,1),(49,'o',1,1,'2013-06-13','2013-07-21','2013-06-21','2013-08-19',10,'10',10,'10',40,1,NULL,NULL,0,50,50,0,0,0,NULL,1),(50,'o',1,2,'2013-06-13','2013-06-22','2013-06-20','2013-07-21',10,'10',10,'10',41,1,1,1,1,5,95,0,0,0,NULL,1),(51,'o',1,2,'2013-06-13','2013-06-22','2013-06-20','2013-07-21',10,'10',10,'10',42,NULL,NULL,NULL,1,5,95,0,0,0,NULL,1),(52,'o',1,2,'2013-06-13','2013-06-22','2013-06-20','2013-07-21',10,'10',10,'10',43,NULL,NULL,NULL,1,5,95,0,0,0,NULL,1),(53,'o',1,2,'2013-07-16','2013-08-11','2013-06-20','2013-09-16',1,'1',1,'1',NULL,NULL,NULL,NULL,0,0,0,0,0,0,NULL,1),(54,'a',1,2,'2013-06-14','2013-06-28','2013-06-20','2013-07-23',1,'1',1,'1',NULL,NULL,NULL,NULL,0,0,0,0,0,0,NULL,1),(55,'producion alex',1,2,'2013-06-28','2013-06-29','2013-06-20','2013-07-23',1,'2',3,'4',44,1,1,1,2,1,99,0,0,0,NULL,1),(56,'asd',1,1,'2013-07-25','2013-08-26','2013-06-29','2013-09-26',2,'2',2,'2',45,1,9,17,3,80,20,0,0,0,NULL,1),(57,'asd',1,1,'2013-07-25','2013-08-26','2013-06-29','2013-09-26',2,'2',2,'2',46,1,9,17,3,80,20,0,0,0,NULL,1),(58,'asd',1,1,'2013-07-25','2013-08-26','2013-06-29','2013-09-26',2,'2',2,'2',47,1,9,17,3,80,20,0,0,0,NULL,1),(59,'asd',1,1,'2013-07-25','2013-08-26','2013-06-29','2013-09-26',2,'2',2,'2',48,1,9,17,3,80,20,0,0,0,NULL,1),(60,'asdas',1,1,'2013-06-28','2013-07-24','2013-06-29','2013-08-29',2,'2',2,'2',49,1,1,9,4,0,0,0,0,0,NULL,1),(61,'sdasd',1,1,'2013-06-21','2013-06-28','2013-06-29','2013-06-29',2,'2',2,'2',50,9,1,17,4,0,0,0,0,0,NULL,1),(62,'xczx',1,1,'2013-06-29','2013-07-29','2013-06-19','2013-08-04',2,'2',2,'2',51,1,1,17,2,0,0,0,0,0,NULL,1),(63,'sdfsdf',1,2,'2013-06-22','2013-06-29','2013-06-29','2013-07-24',2,'2',2,'2',52,1,1,9,4,0,0,0,0,0,NULL,1),(64,'Cap',1,1,'2013-06-29','2013-07-29','2013-06-30','2013-08-31',10,'10',10,'10',53,17,9,1,3,0,0,0,0,0,NULL,1),(65,'asdasd',2,2,'2013-06-29','2013-07-29','2013-06-30','2013-07-31',10,'10',10,'10',54,1,9,17,2,0,0,0,0,0,NULL,1);
/*!40000 ALTER TABLE `produccion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `produccion_has_capitulos`
--

DROP TABLE IF EXISTS `produccion_has_capitulos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `produccion_has_capitulos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_produccion` int(11) DEFAULT NULL,
  `numero` int(11) DEFAULT NULL,
  `nombre` varchar(100) COLLATE utf8_spanish_ci DEFAULT NULL,
  `numero_escenas` int(11) DEFAULT NULL,
  `sinopsis` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `escenas_escritas` int(11) DEFAULT NULL,
  `escenas_producidas` int(11) DEFAULT NULL,
  `duracion_estimada` time DEFAULT NULL,
  `duracion_real` time DEFAULT NULL,
  `fecha_aire` date DEFAULT NULL,
  `estado` int(11) DEFAULT NULL,
  `libreto` varchar(100) COLLATE utf8_spanish_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_produccion_has_capitulos_1_idx` (`id_produccion`),
  CONSTRAINT `fk_produccion_has_capitulos_1` FOREIGN KEY (`id_produccion`) REFERENCES `produccion` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `produccion_has_capitulos`
--

LOCK TABLES `produccion_has_capitulos` WRITE;
/*!40000 ALTER TABLE `produccion_has_capitulos` DISABLE KEYS */;
INSERT INTO `produccion_has_capitulos` VALUES (4,33,1,'asdasd',NULL,NULL,NULL,NULL,'00:00:00',NULL,NULL,1,NULL),(5,33,2,'asdasda',NULL,NULL,NULL,NULL,'00:00:00',NULL,NULL,1,NULL),(6,33,3,'asdasda',NULL,NULL,NULL,NULL,'00:00:00',NULL,NULL,1,NULL),(7,33,4,'FRSDFSD',NULL,NULL,NULL,NULL,'00:00:00',NULL,NULL,1,NULL),(8,33,5,'ASDASD',NULL,NULL,NULL,NULL,'00:00:00',NULL,NULL,1,NULL),(9,33,6,'DCSD',NULL,NULL,NULL,NULL,'00:00:00',NULL,NULL,1,NULL),(10,33,7,'SDFSDF',NULL,NULL,NULL,NULL,'00:00:00',NULL,NULL,1,NULL),(11,33,8,'sfdf',NULL,NULL,NULL,NULL,'00:00:00',NULL,NULL,1,NULL),(12,33,9,'sdfsdf',NULL,NULL,NULL,NULL,'00:00:00',NULL,NULL,1,NULL),(14,33,10,'sedfsdfs',NULL,NULL,NULL,NULL,'00:00:00',NULL,NULL,1,NULL),(15,65,1,NULL,NULL,NULL,NULL,NULL,'00:00:00',NULL,NULL,1,NULL),(16,65,2,NULL,NULL,NULL,NULL,NULL,'00:00:00',NULL,NULL,1,NULL),(17,65,3,NULL,NULL,NULL,NULL,NULL,'00:00:00',NULL,NULL,1,NULL),(18,65,4,NULL,NULL,NULL,NULL,NULL,'00:00:00',NULL,NULL,1,NULL),(19,65,5,NULL,NULL,NULL,NULL,NULL,'00:00:00',NULL,NULL,1,NULL),(20,65,6,NULL,NULL,NULL,NULL,NULL,'00:00:00',NULL,NULL,1,NULL),(21,65,7,NULL,NULL,NULL,NULL,NULL,'00:00:00',NULL,NULL,1,NULL),(22,65,8,NULL,NULL,NULL,NULL,NULL,'00:00:00',NULL,NULL,1,NULL),(23,65,9,NULL,NULL,NULL,NULL,NULL,'00:00:00',NULL,NULL,1,NULL),(24,65,10,NULL,NULL,NULL,NULL,NULL,'00:00:00',NULL,NULL,1,NULL);
/*!40000 ALTER TABLE `produccion_has_capitulos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rol_otros`
--

DROP TABLE IF EXISTS `rol_otros`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rol_otros` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rol_otros`
--

LOCK TABLES `rol_otros` WRITE;
/*!40000 ALTER TABLE `rol_otros` DISABLE KEYS */;
INSERT INTO `rol_otros` VALUES (1,'Script'),(2,'Desglosador'),(3,'Maquillaje'),(4,'Vestuario'),(5,'Tecnico'),(6,'Productor solo lectura'),(7,'Director');
/*!40000 ALTER TABLE `rol_otros` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `semanas_produccion`
--

DROP TABLE IF EXISTS `semanas_produccion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `semanas_produccion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dias_trabajo` int(11) NOT NULL,
  `fecha_inicio_semana` date DEFAULT NULL,
  `fecha_fin_semana` date DEFAULT NULL,
  `capitulos_programados` int(11) DEFAULT NULL,
  `minutos_proyectados` int(11) DEFAULT NULL,
  `id_produccion` int(11) DEFAULT NULL,
  `lunes` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `martes` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `miercoles` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `jueves` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `viernes` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `sabado` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `domingo` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `comentario` longtext COLLATE utf8_spanish_ci,
  PRIMARY KEY (`id`),
  KEY `fk_semanas_produccion_1_idx` (`id_produccion`),
  CONSTRAINT `fk_semanas_produccion_1` FOREIGN KEY (`id_produccion`) REFERENCES `produccion` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=316 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `semanas_produccion`
--

LOCK TABLES `semanas_produccion` WRITE;
/*!40000 ALTER TABLE `semanas_produccion` DISABLE KEYS */;
INSERT INTO `semanas_produccion` VALUES (37,25,'2013-06-12','2013-06-16',NULL,NULL,33,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(38,25,'2013-06-17','2013-06-24',NULL,NULL,33,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(39,25,'2013-06-25','2013-07-02',NULL,NULL,33,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(40,25,'2013-07-03','2013-07-10',NULL,NULL,33,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(41,25,'2013-07-11','2013-07-18',NULL,NULL,33,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(42,24,'2013-06-12','2013-06-16',NULL,NULL,32,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(235,2,'2013-06-13','2013-06-16',20,20,34,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(236,2,'2013-06-17','2013-06-24',20,10,34,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(237,2,'2013-06-25','2013-07-02',20,20,34,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(238,2,'2013-07-03','2013-07-10',1,20,34,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(239,2,'2013-07-11','2013-07-18',20,20,34,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(240,2,'2013-07-19','2013-07-26',20,20,34,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(241,2,'2013-07-27','2013-08-03',20,20,34,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(242,3,'2013-06-28','2013-06-30',4,3,55,'checked','checked','checked','','','','',NULL),(243,3,'2013-07-01','2013-07-08',4,3,55,'checked','checked','checked','','','','',NULL),(244,3,'2013-07-09','2013-07-16',4,3,55,'checked','checked','checked','','','','',NULL),(245,3,'2013-07-17','2013-07-24',4,3,55,'checked','checked','checked','','','','',NULL),(246,4,'2013-07-25','2013-07-28',NULL,NULL,56,'checked','checked','checked','checked','','','',NULL),(247,4,'2013-07-29','2013-08-05',NULL,NULL,56,'checked','checked','checked','checked','','','',NULL),(248,4,'2013-08-06','2013-08-13',NULL,NULL,56,'checked','checked','checked','checked','','','',NULL),(249,4,'2013-08-14','2013-08-21',NULL,NULL,56,'checked','checked','checked','checked','','','',NULL),(250,4,'2013-08-22','2013-08-29',NULL,NULL,56,'checked','checked','checked','checked','','','',NULL),(251,4,'2013-08-30','2013-09-06',NULL,NULL,56,'checked','checked','checked','checked','','','',NULL),(252,4,'2013-09-07','2013-09-14',NULL,NULL,56,'checked','checked','checked','checked','','','',NULL),(253,4,'2013-09-15','2013-09-22',NULL,NULL,56,'checked','checked','checked','checked','','','',NULL),(254,4,'2013-09-23','2013-09-30',NULL,NULL,56,'checked','checked','checked','checked','','','',NULL),(255,4,'2013-07-25','2013-07-28',NULL,NULL,57,'checked','checked','checked','checked','','','',NULL),(256,4,'2013-07-29','2013-08-05',NULL,NULL,57,'checked','checked','checked','checked','','','',NULL),(257,4,'2013-08-06','2013-08-13',NULL,NULL,57,'checked','checked','checked','checked','','','',NULL),(258,4,'2013-08-14','2013-08-21',NULL,NULL,57,'checked','checked','checked','checked','','','',NULL),(259,4,'2013-08-22','2013-08-29',NULL,NULL,57,'checked','checked','checked','checked','','','',NULL),(260,4,'2013-08-30','2013-09-06',NULL,NULL,57,'checked','checked','checked','checked','','','',NULL),(261,4,'2013-09-07','2013-09-14',NULL,NULL,57,'checked','checked','checked','checked','','','',NULL),(262,4,'2013-09-15','2013-09-22',NULL,NULL,57,'checked','checked','checked','checked','','','',NULL),(263,4,'2013-09-23','2013-09-30',NULL,NULL,57,'checked','checked','checked','checked','','','',NULL),(264,4,'2013-07-25','2013-07-28',NULL,NULL,58,'checked','checked','checked','checked','','','',NULL),(265,4,'2013-07-29','2013-08-05',NULL,NULL,58,'checked','checked','checked','checked','','','',NULL),(266,4,'2013-08-06','2013-08-13',NULL,NULL,58,'checked','checked','checked','checked','','','',NULL),(267,4,'2013-08-14','2013-08-21',NULL,NULL,58,'checked','checked','checked','checked','','','',NULL),(268,4,'2013-08-22','2013-08-29',NULL,NULL,58,'checked','checked','checked','checked','','','',NULL),(269,4,'2013-08-30','2013-09-06',NULL,NULL,58,'checked','checked','checked','checked','','','',NULL),(270,4,'2013-09-07','2013-09-14',NULL,NULL,58,'checked','checked','checked','checked','','','',NULL),(271,4,'2013-09-15','2013-09-22',NULL,NULL,58,'checked','checked','checked','checked','','','',NULL),(272,4,'2013-09-23','2013-09-30',NULL,NULL,58,'checked','checked','checked','checked','','','',NULL),(273,4,'2013-07-25','2013-07-28',NULL,2,59,'checked','checked','checked','checked','','','',NULL),(274,4,'2013-07-29','2013-08-05',NULL,2,59,'checked','checked','checked','checked','','','',NULL),(275,4,'2013-08-06','2013-08-13',NULL,2,59,'checked','checked','checked','checked','','','',NULL),(276,4,'2013-08-14','2013-08-21',NULL,2,59,'checked','checked','checked','checked','','','',NULL),(277,4,'2013-08-22','2013-08-29',NULL,2,59,'checked','checked','checked','checked','','','',NULL),(278,4,'2013-08-30','2013-09-06',NULL,2,59,'checked','checked','checked','checked','','','',NULL),(279,4,'2013-09-07','2013-09-14',NULL,2,59,'checked','checked','checked','checked','','','',NULL),(280,4,'2013-09-15','2013-09-22',NULL,2,59,'checked','checked','checked','checked','','','',NULL),(281,4,'2013-09-23','2013-09-30',NULL,2,59,'checked','checked','checked','checked','','','',NULL),(282,2,'2013-06-28','2013-06-30',NULL,2,60,'checked','checked','','','','','',NULL),(283,2,'2013-07-01','2013-07-08',NULL,2,60,'checked','checked','','','','','',NULL),(284,2,'2013-07-09','2013-07-16',NULL,2,60,'checked','checked','','','','','',NULL),(285,2,'2013-07-17','2013-07-24',NULL,2,60,'checked','checked','','','','','',NULL),(286,2,'2013-07-25','2013-08-01',NULL,2,60,'checked','checked','','','','','',NULL),(287,2,'2013-08-02','2013-08-09',NULL,2,60,'checked','checked','','','','','',NULL),(288,2,'2013-08-10','2013-08-17',NULL,2,60,'checked','checked','','','','','',NULL),(289,2,'2013-08-18','2013-08-25',NULL,2,60,'checked','checked','','','','','',NULL),(290,2,'2013-08-26','2013-09-02',NULL,2,60,'checked','checked','','','','','',NULL),(291,2,'2013-06-21','2013-06-23',NULL,2,61,'checked','checked','','','','','',NULL),(292,2,'2013-06-29','2013-06-30',NULL,2,62,'checked','checked','','','','','',NULL),(293,2,'2013-07-01','2013-07-08',NULL,2,62,'checked','checked','','','','','',NULL),(294,2,'2013-07-09','2013-07-16',NULL,2,62,'checked','checked','','','','','',NULL),(295,2,'2013-07-17','2013-07-24',NULL,2,62,'checked','checked','','','','','',NULL),(296,2,'2013-07-25','2013-08-01',NULL,2,62,'checked','checked','','','','','',NULL),(297,2,'2013-06-22','2013-06-23',2,2,63,'checked','checked','','','','','',NULL),(298,2,'2013-06-24','2013-07-01',2,2,63,'checked','checked','','','','','',NULL),(299,2,'2013-07-02','2013-07-09',2,2,63,'checked','checked','','','','','',NULL),(300,2,'2013-07-10','2013-07-17',2,2,63,'checked','checked','','','','','',NULL),(301,2,'2013-07-18','2013-07-25',2,2,63,'checked','checked','','','','','',NULL),(302,2,'2013-06-29','2013-06-30',10,10,64,'checked','checked','','','','','',NULL),(303,2,'2013-07-01','2013-07-08',10,10,64,'checked','checked','','','','','',NULL),(304,2,'2013-07-09','2013-07-16',10,10,64,'checked','checked','','','','','',NULL),(305,2,'2013-07-17','2013-07-24',10,10,64,'checked','checked','','','','','',NULL),(306,2,'2013-07-25','2013-08-01',10,10,64,'checked','checked','','','','','',NULL),(307,2,'2013-08-02','2013-08-09',10,10,64,'checked','checked','','','','','',NULL),(308,2,'2013-08-10','2013-08-17',10,10,64,'checked','checked','','','','','',NULL),(309,2,'2013-08-18','2013-08-25',10,10,64,'checked','checked','','','','','',NULL),(310,2,'2013-08-26','2013-09-02',10,10,64,'checked','checked','','','','','',NULL),(311,2,'2013-06-29','2013-06-30',10,10,65,'checked','checked','','','','','',NULL),(312,2,'2013-07-01','2013-07-08',10,10,65,'checked','checked','','','','','',NULL),(313,2,'2013-07-09','2013-07-16',10,10,65,'checked','checked','','','','','',NULL),(314,2,'2013-07-17','2013-07-24',10,10,65,'checked','checked','','','','','',NULL),(315,2,'2013-07-25','2013-08-01',10,10,65,'checked','checked','','','','','',NULL);
/*!40000 ALTER TABLE `semanas_produccion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tipo_produccion`
--

DROP TABLE IF EXISTS `tipo_produccion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tipo_produccion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipo` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tipo_produccion`
--

LOCK TABLES `tipo_produccion` WRITE;
/*!40000 ALTER TABLE `tipo_produccion` DISABLE KEYS */;
INSERT INTO `tipo_produccion` VALUES (1,'Unitario'),(2,'Novela');
/*!40000 ALTER TABLE `tipo_produccion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tipo_usuario`
--

DROP TABLE IF EXISTS `tipo_usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tipo_usuario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tipo_usuario`
--

LOCK TABLES `tipo_usuario` WRITE;
/*!40000 ALTER TABLE `tipo_usuario` DISABLE KEYS */;
INSERT INTO `tipo_usuario` VALUES (1,'Master'),(2,'Ejecutivo Read Only'),(3,'Ejecutivo'),(4,'Productor'),(5,'Otros');
/*!40000 ALTER TABLE `tipo_usuario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `unidad`
--

DROP TABLE IF EXISTS `unidad`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `unidad` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_director` int(11) DEFAULT NULL,
  `id_script` int(11) DEFAULT NULL,
  `fecha_inicio` date DEFAULT NULL,
  `id_produccion` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_unidad_1_idx` (`id_produccion`),
  KEY `fk_unidad_2_idx` (`id_director`),
  CONSTRAINT `fk_unidad_1` FOREIGN KEY (`id_produccion`) REFERENCES `produccion` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_unidad_2` FOREIGN KEY (`id_director`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=128 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `unidad`
--

LOCK TABLES `unidad` WRITE;
/*!40000 ALTER TABLE `unidad` DISABLE KEYS */;
INSERT INTO `unidad` VALUES (53,11,19,'2013-06-19',33),(57,11,11,'2013-06-11',32),(58,16,16,'2013-06-11',32),(92,11,11,'2013-06-18',34),(93,18,16,'2013-06-26',34),(94,NULL,NULL,'0000-00-00',52),(95,NULL,NULL,'0000-00-00',55),(96,NULL,NULL,'0000-00-00',55),(97,NULL,NULL,'0000-00-00',56),(98,NULL,NULL,'0000-00-00',56),(99,NULL,NULL,'0000-00-00',56),(100,NULL,NULL,'0000-00-00',57),(101,NULL,NULL,'0000-00-00',57),(102,NULL,NULL,'0000-00-00',57),(103,NULL,NULL,'0000-00-00',58),(104,NULL,NULL,'0000-00-00',58),(105,NULL,NULL,'0000-00-00',58),(106,NULL,NULL,'0000-00-00',59),(107,NULL,NULL,'0000-00-00',59),(108,NULL,NULL,'0000-00-00',59),(109,NULL,NULL,'0000-00-00',60),(110,NULL,NULL,'0000-00-00',60),(111,NULL,NULL,'0000-00-00',60),(112,NULL,NULL,'0000-00-00',60),(113,NULL,NULL,'0000-00-00',61),(114,NULL,NULL,'0000-00-00',61),(115,NULL,NULL,'0000-00-00',61),(116,NULL,NULL,'0000-00-00',61),(117,NULL,NULL,'0000-00-00',62),(118,NULL,NULL,'0000-00-00',62),(119,NULL,NULL,'0000-00-00',63),(120,NULL,NULL,'0000-00-00',63),(121,NULL,NULL,'0000-00-00',63),(122,NULL,NULL,'0000-00-00',63),(123,NULL,NULL,'0000-00-00',64),(124,NULL,NULL,'0000-00-00',64),(125,NULL,NULL,'0000-00-00',64),(126,NULL,NULL,'0000-00-00',65),(127,NULL,NULL,'0000-00-00',65);
/*!40000 ALTER TABLE `unidad` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `apellido` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `correo` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `idioma` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `estado` int(11) DEFAULT NULL,
  `id_tipoUsuario` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_user_1_idx` (`id_tipoUsuario`),
  CONSTRAINT `fk_user_1` FOREIGN KEY (`id_tipoUsuario`) REFERENCES `tipo_usuario` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'alex','ospina','alexander.ospina@cincoveinticinco.com',NULL,1,3),(9,'alex','Ospina Castro','alexander.ospina39@gmail.com','es',1,2),(11,'crsitan','Mora','cristhian.mora@cincoveinticinco.com','en',0,5),(16,'pablo','aguero','pablo@cincoveinticinco.com','es',1,5),(17,'isabel','sarmiento','lsarmiento@rtitv.com','en',1,1),(18,'francisco','montes','francis.montesdeoca@gmail.com','en',1,5),(19,'andres','vera','andres.v@cincoveinticinco.com','es',1,5);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_has_rol_otros`
--

DROP TABLE IF EXISTS `user_has_rol_otros`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_has_rol_otros` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `id_rol_otros` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_user_has_tipo_usuario_1_idx` (`id_user`),
  KEY `fk_user_has_tipo_usuario_2_idx` (`id_rol_otros`),
  CONSTRAINT `fk_user_has_rol_otros_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_user_has_rol_otros_2` FOREIGN KEY (`id_rol_otros`) REFERENCES `rol_otros` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_has_rol_otros`
--

LOCK TABLES `user_has_rol_otros` WRITE;
/*!40000 ALTER TABLE `user_has_rol_otros` DISABLE KEYS */;
INSERT INTO `user_has_rol_otros` VALUES (8,16,1),(9,16,5),(10,16,7),(11,19,2),(12,19,7),(13,11,1),(14,11,2),(15,11,7),(18,18,1),(19,18,2);
/*!40000 ALTER TABLE `user_has_rol_otros` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuario_has_produccion`
--

DROP TABLE IF EXISTS `usuario_has_produccion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuario_has_produccion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) DEFAULT NULL,
  `id_produccion` int(11) DEFAULT NULL,
  `id_rol` int(11) DEFAULT NULL,
  `activo` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_usuario_has_produccion_1_idx` (`id_usuario`),
  KEY `fk_usuario_has_produccion_2_idx` (`id_produccion`),
  KEY `fk_usuario_has_produccion_3_idx` (`id_rol`),
  CONSTRAINT `fk_usuario_has_produccion_1` FOREIGN KEY (`id_usuario`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_usuario_has_produccion_2` FOREIGN KEY (`id_produccion`) REFERENCES `produccion` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_usuario_has_produccion_3` FOREIGN KEY (`id_rol`) REFERENCES `rol_otros` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario_has_produccion`
--

LOCK TABLES `usuario_has_produccion` WRITE;
/*!40000 ALTER TABLE `usuario_has_produccion` DISABLE KEYS */;
INSERT INTO `usuario_has_produccion` VALUES (15,11,34,2,1),(17,19,34,2,1),(18,18,62,2,1);
/*!40000 ALTER TABLE `usuario_has_produccion` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2013-06-14  9:30:22
