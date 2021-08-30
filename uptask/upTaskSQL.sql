/*
SQLyog Ultimate v10.00 Beta1
MySQL - 5.5.5-10.4.18-MariaDB : Database - uptask
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`uptask` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `uptask`;

/*Table structure for table `proyectos` */

DROP TABLE IF EXISTS `proyectos`;

CREATE TABLE `proyectos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

/*Data for the table `proyectos` */

insert  into `proyectos`(`id`,`nombre`) values (1,'Tienda Virtual'),(2,'CV'),(3,'Pan y Queso Virtual'),(4,'Atenea'),(5,'Punto de Apoyo'),(6,'No se como se llama');

/*Table structure for table `tareas` */

DROP TABLE IF EXISTS `tareas`;

CREATE TABLE `tareas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `estado` int(1) DEFAULT NULL,
  `id_proyecto` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_proyecto` (`id_proyecto`),
  CONSTRAINT `tareas_ibfk_1` FOREIGN KEY (`id_proyecto`) REFERENCES `proyectos` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8;

/*Data for the table `tareas` */

insert  into `tareas`(`id`,`nombre`,`estado`,`id_proyecto`) values (1,'Diseñar Boceto en Papel',1,3),(10,'Crear el Logo',0,3),(12,'Crear Header',0,3),(19,'Agregar algo',0,3),(32,'Diseñarun Boceto en Papel',0,5),(33,'Crear Logo',1,5),(35,'Elegir paleta de colores',0,5),(36,'Registrar dominio en NIC',0,5),(37,'Prueba 2',1,2),(38,'Elegir un nombre para el proyecto',1,6),(39,'Elegir paleta de colores',0,6),(40,'Conseguir el dominio',0,6),(42,'Entrevistar al Candidato',1,6);

/*Table structure for table `usuarios` */

DROP TABLE IF EXISTS `usuarios`;

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario` varchar(50) NOT NULL,
  `password` varchar(60) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `usuarios` */

insert  into `usuarios`(`id`,`usuario`,`password`) values (1,'admin','$2y$12$KWCMCmwKtKeinFGskOJayOnRUOh0uV7m.zOV6LPuq.p7riJCH4mNK'),(2,'beto','$2y$12$6hcgimVWb8.8IpclbLFnquvtIp.nNGxEFGp2Ernv3oPMyDWepfUU6');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
