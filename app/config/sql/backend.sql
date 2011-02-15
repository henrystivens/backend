-- MySQL dump 10.13  Distrib 5.1.41, for debian-linux-gnu (i486)
--
-- Host: localhost    Database: backend
-- ------------------------------------------------------
-- Server version	5.1.41-3ubuntu12.9

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
-- Table structure for table `menu`
--

DROP TABLE IF EXISTS `menu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `menu` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `titulo` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre_UNIQUE` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menu`
--

LOCK TABLES `menu` WRITE;
/*!40000 ALTER TABLE `menu` DISABLE KEYS */;
INSERT INTO `menu` VALUES (1,'Usuarios','Administración de usuarios'),(2,'Roles','Administración de roles'),(3,'Permisos','Administración de permisos'),(4,'Recursos','Administración de recursos'),(5,'Menús','Administración de menús'),(6,'Blog','Administración del blog');
/*!40000 ALTER TABLE `menu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permiso`
--

DROP TABLE IF EXISTS `permiso`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `permiso` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `rol_id` int(10) NOT NULL,
  `recurso_id` bigint(20) NOT NULL,
  `menu_id` int(10) NOT NULL,
  `visible` char(1) NOT NULL,
  `estado` char(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `rol_fk` (`rol_id`),
  KEY `fk_rol_recurso_recuso_id` (`recurso_id`),
  KEY `fk_rol_recurso_menu_id` (`menu_id`),
  CONSTRAINT `fk_rol_recurso_menu_id` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`id`),
  CONSTRAINT `fk_rol_recurso_recuso_id` FOREIGN KEY (`recurso_id`) REFERENCES `recurso` (`id`),
  CONSTRAINT `fk_rol_recurso_rol_id` FOREIGN KEY (`rol_id`) REFERENCES `rol` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permiso`
--

LOCK TABLES `permiso` WRITE;
/*!40000 ALTER TABLE `permiso` DISABLE KEYS */;
INSERT INTO `permiso` VALUES (1,1,1,1,'1','A'),(2,1,2,1,'1','A'),(3,1,3,1,'0','A'),(4,1,4,4,'1','A'),(5,1,5,4,'1','A'),(6,1,6,4,'0','A'),(7,1,7,2,'1','A'),(8,1,8,2,'1','A'),(9,1,9,2,'0','A'),(10,1,10,5,'1','A'),(11,1,11,5,'1','A'),(12,1,12,5,'0','A'),(13,1,13,3,'1','A'),(14,1,14,3,'1','A'),(15,1,15,3,'0','A'),(16,1,16,3,'0','A'),(17,1,17,1,'0','A');
/*!40000 ALTER TABLE `permiso` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `recurso`
--

DROP TABLE IF EXISTS `recurso`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `recurso` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `url` varchar(150) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `url_UNIQUE` (`url`),
  UNIQUE KEY `nombre_UNIQUE` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `recurso`
--

LOCK TABLES `recurso` WRITE;
/*!40000 ALTER TABLE `recurso` DISABLE KEYS */;
INSERT INTO `recurso` VALUES (1,'Crear usuario','admin/usuario/crear/'),(2,'Listar usuarios','admin/usuario/index/'),(3,'Editar usuario','admin/usuario/editar/'),(4,'Crear recurso','admin/recurso/crear/'),(5,'Listar recursos','admin/recurso/index/'),(6,'Editar recurso','admin/recurso/editar/'),(7,'Crear rol','admin/rol/crear/'),(8,'Listar roles','admin/rol/index/'),(9,'Editar rol','admin/rol/editar/'),(10,'Crear Menú','admin/menu/crear/'),(11,'Listar menús','admin/menu/index/'),(12,'Editar menú','admin/menu/editar/'),(13,'Crear permiso','admin/permiso/crear/'),(14,'Listar permiso','admin/permiso/index/'),(15,'Editar permiso','admin/permiso/editar/'),(16,'Inicio admin','admin/index/index/'),(17,'Cambiar clave','admin/usuario/cambiar_clave/'),(18,'Ver usuario','admin/usuario/ver/'),(19,'Ver recurso','admin/recurso/ver/'),(20,'Ver rol','admin/rol/ver/'),(21,'Ver menu','admin/menu/ver/'),(22,'Ver permiso','admin/permiso/ver/');
/*!40000 ALTER TABLE `recurso` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rol`
--

DROP TABLE IF EXISTS `rol`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rol` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre_UNIQUE` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rol`
--

LOCK TABLES `rol` WRITE;
/*!40000 ALTER TABLE `rol` DISABLE KEYS */;
INSERT INTO `rol` VALUES (1,'Administrador'),(2,'Editor');
/*!40000 ALTER TABLE `rol` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuario`
--

DROP TABLE IF EXISTS `usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuario` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `nick` varchar(50) NOT NULL,
  `email` varchar(150) NOT NULL,
  `clave` text NOT NULL,
  `estado` char(1) NOT NULL,
  `nombre` text NOT NULL,
  `bio` text,
  `avatar` varchar(200) DEFAULT NULL,
  `rol_id` int(10) NOT NULL,
  `registrado_at` datetime DEFAULT NULL,
  `actualizado_in` datetime DEFAULT NULL,
  `reset` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_nick` (`nick`),
  UNIQUE KEY `email_UNIQUE` (`email`),
  KEY `fk_usuario_rol_id` (`rol_id`),
  CONSTRAINT `fk_usuario_rol_id` FOREIGN KEY (`rol_id`) REFERENCES `rol` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario`
--

LOCK TABLES `usuario` WRITE;
/*!40000 ALTER TABLE `usuario` DISABLE KEYS */;
INSERT INTO `usuario` VALUES (1,'admin','maxter2024@gmail.com','7c4a8d09ca3762af61e59520943dc26494f8941b','1','Henry Stivens Adarme','Desarrollador de soluciones web y móviles.',NULL,1,'2011-01-06 21:28:36','2011-02-03 16:10:45','SbfWL3qEJqGhZPjuRgWAluWdAWHS0VZQx');
/*!40000 ALTER TABLE `usuario` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2011-02-15  8:35:27
