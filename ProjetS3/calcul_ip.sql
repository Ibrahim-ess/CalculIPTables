-- MySQL dump 10.13  Distrib 5.5.52, for Linux (x86_64)
--
-- Host: localhost    Database: calcul_ip
-- ------------------------------------------------------
-- Server version	5.5.52

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
-- Table structure for table `branchements`
--

DROP TABLE IF EXISTS `branchements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `branchements` (
  `id_branchement` varchar(128) NOT NULL,
  `Difficulte` int(11) NOT NULL,
  `id_machine_1` varchar(64) NOT NULL,
  `id_machine_2` varchar(64) NOT NULL,
  `Machine1_port` int(11) NOT NULL,
  `Machine2_port` int(11) NOT NULL,
  PRIMARY KEY (`id_branchement`),
  UNIQUE KEY `Difficulte_id_machine_1_id_machine_2` (`Difficulte`,`id_machine_1`,`id_machine_2`),
  KEY `id_machine_2` (`id_machine_2`),
  KEY `id_machine_1` (`id_machine_1`),
  CONSTRAINT `Branchements_ibfk_1` FOREIGN KEY (`Difficulte`) REFERENCES `routes` (`Difficulte`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `Branchements_ibfk_2` FOREIGN KEY (`id_machine_1`) REFERENCES `machines` (`id_machine`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `Branchements_ibfk_3` FOREIGN KEY (`id_machine_2`) REFERENCES `machines` (`id_machine`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `branchements`
--

LOCK TABLES `branchements` WRITE;
/*!40000 ALTER TABLE `branchements` DISABLE KEYS */;
INSERT INTO `branchements` VALUES ('1-R1-1-M1',1,'1-R1','1-M1',0,0),('1-R1-1-M2',1,'1-R1','1-M2',1,0),('2-R1-2-Cloud',2,'2-R1','2-Cloud',2,0),('2-R1-2-M3',2,'2-R1','2-M3',0,0),('2-R1-2-R2',2,'2-R1','2-R2',1,0),('2-R2-2-M1',2,'2-R2','2-M1',1,0),('2-R2-2-M2',2,'2-R2','2-M2',2,0),('3-M1-3-P1',3,'3-M1','3-P1',0,0),('3-P1-3-P2',3,'3-P1','3-P2',1,0),('3-P2-3-P3',3,'3-P2','3-P3',1,0),('3-P3-3-P4',3,'3-P3','3-P4',1,0),('3-P4-3-M2',3,'3-P4','3-M2',1,0),('4-M1-4-P1',4,'4-M1','4-P1',0,0),('4-P1-4-P2',4,'4-P1','4-P2',1,0),('4-P2-4-P3',4,'4-P2','4-P3',1,0),('4-P3-4-P4',4,'4-P3','4-P4',1,0),('4-P4-4-M2',4,'4-P4','4-M2',1,0),('5-H1-5-M2',5,'5-H1','5-M2',1,0),('5-H1-5-P1',5,'5-H1','5-P1',0,1),('5-H1-5-P2',5,'5-H1','5-P2',2,0),('5-M1-5-P1',5,'5-M1','5-P1',0,0),('5-P2-5-M3',5,'5-P2','5-M3',1,0),('6-M1-6-S1',6,'6-M1','6-S1',0,0),('6-R1-6-S2',6,'6-R1','6-S2',1,0),('6-R2-6-S3',6,'6-R2','6-S3',1,0),('6-S1-6-R1',6,'6-S1','6-R1',1,0),('6-S2-6-R2',6,'6-S2','6-R2',1,0),('6-S3-6-M2',6,'6-S3','6-M2',1,0);
/*!40000 ALTER TABLE `branchements` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `citations`
--

DROP TABLE IF EXISTS `citations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `citations` (
  `id` int(11) DEFAULT NULL,
  `texte` longtext CHARACTER SET utf8,
  `auteur` varchar(50) DEFAULT NULL,
  `commentaire` longtext,
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `citations`
--

LOCK TABLES `citations` WRITE;
/*!40000 ALTER TABLE `citations` DISABLE KEYS */;
INSERT INTO `citations` VALUES (1,'Si vous pensez que l\'éducation coûte cher, essayez l\'ignorance','Derek Bok','Ancien président de Harvard'),(2,'Etre conscient de son ignorance, c\'est tendre vers la connaissance','Benjamin Disraeli','Ecrivain Anglais'),(3,'C\'est en apprenant que l\'on mesure son ignorance','Lao She/Shu Qingchun','Romancier Chinois'),(4,'L’ignorance est la nuit de l’esprit, et cette nuit n’a ni lune ni étoiles.','Proverbe Chinois',NULL),(5,'Chaque enfant qu’on enseigne est un homme qu’on gagne. L’ignorance est la nuit qui commence l’abîme','Victor Hugo','Les Quatres Vents de l\'esprit'),(6,'L\'ignorance mène à la peur, la peur mène à la haine et la haine conduit à la violence. Voilà l\'équation','Averroès','Médecin et philosophe arabe d\'origne espagnole né en 1126'),(7,'Un travail opiniâtre vient à bout de tout','Virgile','Poète latin né en -70'),(8,'On commence à vieillir quand on finit d\'apprendre','Proverbe japonais',NULL),(9,'La persévérance est au courage ce que la roue est au levier, c\'est le renouvellement perpétuel du point d\'appui','Victor Hugo','Les travailleurs de la mer (1866)'),(10,'Celui qui ouvre une porte d\'école, ferme une prison','Victor Hugo','Artiste, écrivain, Poète, Romancier (1802 - 1885)'),(11,'L\'éducation est l\'arme la plus puissante qu\'on puisse utiliser pour changer le monde','Nelson Mandela','Homme d\'état sud-africain (1918 - 2013), le dirigeant le plus connu dans la lutte contre l\'apartheid'),(12,'En travaillant assidûment il faut peu de chose pour changer le médiocre en bon et le bon en excellent','Gustave Flaubert','Ecrivain Français (1821 - 1880)');
/*!40000 ALTER TABLE `citations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `exercice_fait`
--

DROP TABLE IF EXISTS `exercice_fait`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `exercice_fait` (
  `id_exercice` int(11) NOT NULL,
  `userID` varchar(64) NOT NULL,
  `reussi` int(11) NOT NULL DEFAULT '0',
  `echec` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_exercice`,`userID`),
  KEY `userID` (`userID`),
  CONSTRAINT `Exercice_fait_ibfk_1` FOREIGN KEY (`id_exercice`) REFERENCES `exercices` (`id_exercice`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `Exercice_fait_ibfk_2` FOREIGN KEY (`userID`) REFERENCES `utilisateurs` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `exercice_fait`
--

LOCK TABLES `exercice_fait` WRITE;
/*!40000 ALTER TABLE `exercice_fait` DISABLE KEYS */;
INSERT INTO `exercice_fait` VALUES (1,'11403591',0,4),(2,'franck.butelle',2,1),(3,'franck.butelle',0,1),(5,'11403591',6,68),(6,'11403591',0,11),(6,'franck.butelle',1,1),(7,'franck.butelle',0,1),(9,'11403591',22,0),(10,'11403591',1,3),(10,'franck.butelle',0,2),(11,'franck.butelle',0,1);
/*!40000 ALTER TABLE `exercice_fait` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `exercices`
--

DROP TABLE IF EXISTS `exercices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `exercices` (
  `id_exercice` int(11) NOT NULL AUTO_INCREMENT,
  `nom_exercice` varchar(64) NOT NULL,
  `url` varchar(64) NOT NULL DEFAULT '#',
  PRIMARY KEY (`id_exercice`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `exercices`
--

LOCK TABLES `exercices` WRITE;
/*!40000 ALTER TABLE `exercices` DISABLE KEYS */;
INSERT INTO `exercices` VALUES (1,'Analyse de trame','/Exercices/AnalyseTrame.php'),(2,'Conversions : Binaire - Hexadécimal - Décimal','/Exercices/Conversion.php'),(3,'Classe de l\'IP : Trouver la classe correspondante','/Exercices/TrouverClasse.php'),(4,'Classe IP : Trouver l\'IP correspondante','/Exercices/TrouverClasseInverse.php'),(5,'Masque','/Exercices/Masque.php'),(6,'Notation CIDR S2','/Exercices/NotationCIDRS2.php'),(7,'Préfixe max : Facile','/Exercices/PrefixeMaxFacile.php'),(8,'Préfixe max : plus difficile','/Exercices/PrefixeMaxDifficile.php'),(9,'Structure de trame','/Exercices/StructureTrame.php'),(10,'Table de routage','/Exercices/TableRoutage.php'),(11,'Calcul de sous-réseaux','/Exercices/SousReseaux.php'),(12,'Notation CIDR S3','/Exercices/NotationCIDR.php');
/*!40000 ALTER TABLE `exercices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ip_machines`
--

DROP TABLE IF EXISTS `ip_machines`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ip_machines` (
  `id_ip_machine` varchar(64) CHARACTER SET utf8 NOT NULL,
  `Difficulte` int(11) NOT NULL,
  `id_machine` varchar(64) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `Interface` varchar(11) CHARACTER SET utf8 NOT NULL,
  `id_reseaux` varchar(64) CHARACTER SET utf8 NOT NULL,
  `ip_machine` varchar(64) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id_ip_machine`),
  UNIQUE KEY `unique` (`Difficulte`,`id_machine`,`Interface`),
  KEY `id_machine` (`id_machine`),
  KEY `id_reseaux` (`id_reseaux`),
  CONSTRAINT `Ip_machines_ibfk_1` FOREIGN KEY (`Difficulte`) REFERENCES `routes` (`Difficulte`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `Ip_machines_ibfk_2` FOREIGN KEY (`id_machine`) REFERENCES `machines` (`id_machine`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `Ip_machines_ibfk_3` FOREIGN KEY (`id_reseaux`) REFERENCES `reseaux` (`id_reseau`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ip_machines`
--

LOCK TABLES `ip_machines` WRITE;
/*!40000 ALTER TABLE `ip_machines` DISABLE KEYS */;
INSERT INTO `ip_machines` VALUES ('1-M2-eth0',1,'1-M2','eth0','1-1','218.189.13.222'),('1-R1-eth0',1,'1-R1','eth0','1-0','175.177.143.215'),('1-R1-eth1',1,'1-R1','eth1','1-1','218.189.13.103'),('2-M1-eth0',2,'2-M1','eth0','2-4','102.173.114.254'),('2-M2-eth0',2,'2-M2','eth0','2-5','190.58.48.248'),('2-M3-eth0',2,'2-M3','eth0','2-2','139.129.116.179'),('2-R1-eth0',2,'2-R1','eth0','2-2','139.129.138.86'),('2-R1-eth1',2,'2-R1','eth1','2-3','203.17.167.131'),('2-R1-eth2',2,'2-R1','eth2','2-1','9.149.91.137'),('2-R2-eth0',2,'2-R2','eth0','2-3','203.17.167.190'),('2-R2-eth1',2,'2-R2','eth1','2-4','102.109.142.16'),('2-R2-eth2',2,'2-R2','eth2','2-5','190.58.254.241'),('3-M1-eth0',3,'3-M1','eth0','3-1','159.231.10.106'),('3-M2-eth0',3,'3-M2','eth0','3-5','222.6.77.208'),('3-P1-eth0',3,'3-P1','eth0','3-1','159.231.136.241'),('3-P1-eth1',3,'3-P1','eth1','3-2','67.191.102.168'),('3-P2-eth0',3,'3-P2','eth0','3-2','67.136.196.19'),('3-P2-eth1',3,'3-P2','eth1','3-3','219.137.190.18'),('3-P3-eth0',3,'3-P3','eth0','3-3','219.137.190.82'),('3-P3-eth1',3,'3-P3','eth1','3-4','85.168.60.187'),('3-P4-eth0',3,'3-P4','eth0','3-4','85.142.177.132'),('3-P4-eth1',3,'3-P4','eth1','3-5','222.6.77.74'),('4-M1-eth0',4,'4-M1','eth0','4-1','176.85.8.114'),('4-M2-eth0',4,'4-M2','eth0','4-5','220.16.37.154'),('4-P1-eth0',4,'4-P1','eth0','4-1','176.85.21.34'),('4-P1-eth1',4,'4-P1','eth1','4-2','64.219.5.217'),('4-P2-eth0',4,'4-P2','eth0','4-2','64.38.197.109'),('4-P2-eth1',4,'4-P2','eth1','4-3','218.193.5.81'),('4-P3-eth0',4,'4-P3','eth0','4-3','218.193.5.168'),('4-P3-eth1',4,'4-P3','eth1','4-4','79.98.184.143'),('4-P4-eth0',4,'4-P4','eth0','4-4','79.133.51.245'),('4-P4-eth1',4,'4-P4','eth1','4-5','220.16.37.243'),('5-M1-eth0',5,'5-M1','eth0','5-2','12.24.184.34'),('5-M2-eth0',5,'5-M2','eth0','5-1','179.118.110.168'),('5-M3-eth0',5,'5-M3','eth0','5-3','200.101.208.117'),('5-P1-eth0',5,'5-P1','eth0','5-2','12.195.29.87'),('5-P1-eth1',5,'5-P1','eth1','5-1','179.118.255.153'),('5-P2-eth0',5,'5-P2','eth0','5-1','179.118.161.143'),('5-P2-eth1',5,'5-P2','eth1','5-3','200.101.208.36'),('6-M1-eth0',6,'6-M1','eth0','6-1','11.238.61.114'),('6-R1-eth0',6,'6-R1','eth0','6-1','11.98.166.218'),('6-R1-eth1',6,'6-R1','eth1','6-2','192.24.234.146'),('6-R2-eth0',6,'6-R2','eth0','6-2','192.24.234.227'),('6-R2-eth1',6,'6-R2','eth1','6-3','150.250.152.17');
/*!40000 ALTER TABLE `ip_machines` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `machines`
--

DROP TABLE IF EXISTS `machines`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `machines` (
  `id_machine` varchar(64) NOT NULL,
  `Difficulte` int(11) NOT NULL,
  `Nom` varchar(64) NOT NULL,
  `Image` varchar(64) NOT NULL,
  `X` int(11) NOT NULL,
  `Y` int(11) NOT NULL,
  `Width` int(11) NOT NULL,
  `Height` int(11) NOT NULL,
  PRIMARY KEY (`id_machine`),
  UNIQUE KEY `Unique` (`Difficulte`,`Nom`),
  UNIQUE KEY `Difficulte` (`Difficulte`,`Nom`),
  CONSTRAINT `Machines_ibfk_1` FOREIGN KEY (`Difficulte`) REFERENCES `routes` (`Difficulte`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `machines`
--

LOCK TABLES `machines` WRITE;
/*!40000 ALTER TABLE `machines` DISABLE KEYS */;
INSERT INTO `machines` VALUES ('1-M1',1,'M1','computer',0,150,100,64),('1-M2',1,'M2','computer',160,150,100,64),('1-R1',1,'R1','router',80,20,100,32),('2-Cloud',2,'Cloud','net',300,0,100,64),('2-M1',2,'M1','computer',50,420,100,64),('2-M2',2,'M2','computer',300,420,100,64),('2-M3',2,'M3','computer',0,240,100,64),('2-R1',2,'R1','router',200,130,100,32),('2-R2',2,'R2','router',200,300,100,32),('3-M1',3,'M1','computer',0,20,100,64),('3-M2',3,'M2','computer',350,400,100,64),('3-P1',3,'P1','router',0,150,100,32),('3-P2',3,'P2','router',0,300,100,32),('3-P3',3,'P3','router',250,150,100,32),('3-P4',3,'P4','router',250,300,100,32),('4-M1',4,'M1','computer',0,20,100,64),('4-M2',4,'M2','computer',350,400,100,64),('4-P1',4,'P1','router',0,150,100,32),('4-P2',4,'P2','router',0,300,100,32),('4-P3',4,'P3','router',250,150,100,32),('4-P4',4,'P4','router',250,300,100,32),('5-H1',5,'HUB1','hub',200,125,100,32),('5-M1',5,'M1','computer',210,320,100,64),('5-M2',5,'M2','computer',0,15,100,64),('5-M3',5,'M3','computer',350,150,100,64),('5-P1',5,'P1','router',80,220,100,32),('5-P2',5,'P2','router',340,20,100,32),('6-M1',6,'M1','computer',0,20,100,64),('6-M2',6,'M2','computer',225,275,100,64),('6-R1',6,'R1','router',150,20,100,32),('6-R2',6,'R2','router',300,20,100,32),('6-S1',6,'Switch1','switch',100,150,100,32),('6-S2',6,'Switch2','switch',225,150,100,32),('6-S3',6,'Switch3','switch',350,150,100,32);
/*!40000 ALTER TABLE `machines` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reseaux`
--

DROP TABLE IF EXISTS `reseaux`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reseaux` (
  `id_reseau` varchar(64) NOT NULL,
  `difficulte` int(11) NOT NULL,
  `num_reseau` int(11) NOT NULL,
  `classe` char(1) NOT NULL,
  `CIDR` int(11) NOT NULL,
  `masque` varchar(64) NOT NULL,
  `Adresse` varchar(64) NOT NULL,
  PRIMARY KEY (`id_reseau`),
  UNIQUE KEY `difficulte` (`difficulte`,`num_reseau`),
  CONSTRAINT `Reseaux_ibfk_1` FOREIGN KEY (`difficulte`) REFERENCES `routes` (`Difficulte`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reseaux`
--

LOCK TABLES `reseaux` WRITE;
/*!40000 ALTER TABLE `reseaux` DISABLE KEYS */;
INSERT INTO `reseaux` VALUES ('1-0',1,0,'B',16,'255.255.0.0','175.177.0.0'),('1-1',1,1,'C',24,'255.255.255.0','218.189.13.0'),('2-0',2,0,'0',0,'0.0.0.0','default'),('2-1',2,1,'A',8,'255.0.0.0','9.0.0.0'),('2-2',2,2,'B',16,'255.255.0.0','139.129.0.0'),('2-3',2,3,'C',24,'255.255.255.0','203.17.167.0'),('2-4',2,4,'A',8,'255.0.0.0','102.0.0.0'),('2-5',2,5,'B',16,'255.255.0.0','190.58.0.0'),('3-0',3,0,'0',0,'0.0.0.0','default'),('3-1',3,1,'B',16,'255.255.0.0','159.231.0.0'),('3-2',3,2,'A',8,'255.0.0.0','67.0.0.0'),('3-3',3,3,'C',24,'255.255.255.0','219.137.190.0'),('3-4',3,4,'A',8,'255.0.0.0','85.0.0.0'),('3-5',3,5,'C',24,'255.255.255.0','222.6.77.0'),('4-0',4,0,'0',0,'0.0.0.0','default'),('4-1',4,1,'B',16,'255.255.0.0','176.85.0.0'),('4-2',4,2,'A',8,'255.0.0.0','64.0.0.0'),('4-3',4,3,'C',24,'255.255.255.0','218.193.5.0'),('4-4',4,4,'A',8,'255.0.0.0','79.0.0.0'),('4-5',4,5,'C',24,'255.255.255.0','220.16.37.0'),('5-0',5,0,'0',0,'0.0.0.0','default'),('5-1',5,1,'B',16,'255.255.0.0','179.118.0.0'),('5-2',5,2,'A',8,'255.0.0.0','12.0.0.0'),('5-3',5,3,'C',24,'255.255.0.0','200.101.208.0'),('6-0',6,0,'0',0,'0.0.0.0','default'),('6-1',6,1,'A',8,'255.0.0.0','11.0.0.0'),('6-2',6,2,'C',24,'255.255.255.0','192.24.234.0'),('6-3',6,3,'B',16,'255.255.0.0','150.250.0.0');
/*!40000 ALTER TABLE `reseaux` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `routes`
--

DROP TABLE IF EXISTS `routes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `routes` (
  `Difficulte` int(11) NOT NULL,
  `machine` varchar(64) NOT NULL,
  PRIMARY KEY (`Difficulte`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `routes`
--

LOCK TABLES `routes` WRITE;
/*!40000 ALTER TABLE `routes` DISABLE KEYS */;
INSERT INTO `routes` VALUES (1,'R1'),(2,'R2'),(3,'M1'),(4,'P2'),(5,'P1'),(6,'R1');
/*!40000 ALTER TABLE `routes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary table structure for view `table_route`
--

DROP TABLE IF EXISTS `table_route`;
/*!50001 DROP VIEW IF EXISTS `table_route`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `table_route` (
  `difficulte` tinyint NOT NULL,
  `destination` tinyint NOT NULL,
  `gateway` tinyint NOT NULL,
  `mask` tinyint NOT NULL,
  `flags` tinyint NOT NULL,
  `interface` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `table_route_generation`
--

DROP TABLE IF EXISTS `table_route_generation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `table_route_generation` (
  `difficulte` int(11) NOT NULL,
  `destination` varchar(64) NOT NULL,
  `gateway` varchar(64) DEFAULT NULL,
  `flags` varchar(16) NOT NULL,
  `interface` varchar(11) NOT NULL,
  PRIMARY KEY (`difficulte`,`destination`),
  KEY `destination` (`destination`),
  KEY `gateway` (`gateway`),
  KEY `interface` (`interface`),
  CONSTRAINT `Table_route_generation_ibfk_1` FOREIGN KEY (`difficulte`) REFERENCES `routes` (`Difficulte`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `Table_route_generation_ibfk_3` FOREIGN KEY (`gateway`) REFERENCES `ip_machines` (`id_ip_machine`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `Table_route_generation_ibfk_4` FOREIGN KEY (`destination`) REFERENCES `reseaux` (`id_reseau`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `table_route_generation`
--

LOCK TABLES `table_route_generation` WRITE;
/*!40000 ALTER TABLE `table_route_generation` DISABLE KEYS */;
INSERT INTO `table_route_generation` VALUES (1,'1-0',NULL,'U','eth0'),(1,'1-1',NULL,'U','eth1'),(2,'2-0','2-R1-eth1','UG','eth0'),(2,'2-3',NULL,'U','eth0'),(2,'2-4',NULL,'U','eth1'),(2,'2-5',NULL,'U','eth2'),(3,'3-0','3-P1-eth0','UG','eth0'),(3,'3-1',NULL,'U','eth0'),(4,'4-0','4-P3-eth0','UG','eth1'),(4,'4-1','4-P1-eth1','UG','eth0'),(4,'4-2',NULL,'U','eth0'),(4,'4-3',NULL,'U','eth1'),(5,'5-0','5-P2-eth0','UG','eth1'),(5,'5-1',NULL,'U','eth1'),(5,'5-2',NULL,'U','eth0'),(6,'6-0','6-R2-eth0','UG','eth1'),(6,'6-1',NULL,'U','eth0'),(6,'6-2',NULL,'U','eth1');
/*!40000 ALTER TABLE `table_route_generation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary table structure for view `table_route_test`
--

DROP TABLE IF EXISTS `table_route_test`;
/*!50001 DROP VIEW IF EXISTS `table_route_test`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `table_route_test` (
  `destination` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `trame`
--

DROP TABLE IF EXISTS `trame`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `trame` (
  `Nom` varchar(32) NOT NULL,
  `Tab` text NOT NULL,
  `tailleMinFixe` text NOT NULL,
  `tailleMax` text NOT NULL,
  PRIMARY KEY (`Nom`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `trame`
--

LOCK TABLES `trame` WRITE;
/*!40000 ALTER TABLE `trame` DISABLE KEYS */;
INSERT INTO `trame` VALUES ('datagramme UDP','Port Source,Port Destination,Long. tot. en oct.,Checksum','16,16,16,16',',,,'),('paquet ARP','Hardware Address Space,Protocol Address Space,HLEN,PLEN,Opcode,Sender Hardware Address,Sender Protocol Address,Target Hardware Address,Target Protocol Address','16,16,8,8,16,32,32,32,32',',,,,,,,,'),('segment TCP','Port Source,Port Destination,Numéro de séquence,Numéro d\'acquittement,Long. entete,000,Drapeaux,Taille fenêtre,Checksum,Pointeur urgent','16,16,32,32,4,3,9,16,16,16',',,,,,,,,,'),('trame Ethernet','DA,SA,DL/ETYPE,Données,Bourrage,FCS','48,48,16,0,0,32',',,,12000,288,');
/*!40000 ALTER TABLE `trame` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `utilisateurs`
--

DROP TABLE IF EXISTS `utilisateurs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `utilisateurs` (
  `UserID` varchar(64) NOT NULL,
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`UserID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `utilisateurs`
--

LOCK TABLES `utilisateurs` WRITE;
/*!40000 ALTER TABLE `utilisateurs` DISABLE KEYS */;
INSERT INTO `utilisateurs` VALUES ('11402430','2018-03-26 12:38:48'),('11403591','2018-03-26 12:38:48'),('franck.butelle','2018-03-26 12:38:48');
/*!40000 ALTER TABLE `utilisateurs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Final view structure for view `table_route`
--

/*!50001 DROP TABLE IF EXISTS `table_route`*/;
/*!50001 DROP VIEW IF EXISTS `table_route`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`oursinator`@`%` SQL SECURITY DEFINER */
/*!50001 VIEW `table_route` AS select `table_route_generation`.`difficulte` AS `difficulte`,`reseaux`.`Adresse` AS `destination`,`table_route_generation`.`gateway` AS `gateway`,`reseaux`.`masque` AS `mask`,`table_route_generation`.`flags` AS `flags`,`table_route_generation`.`interface` AS `interface` from (`table_route_generation` join `reseaux` on(((`reseaux`.`id_reseau` = `table_route_generation`.`destination`) and (`table_route_generation`.`difficulte` = `reseaux`.`difficulte`)))) order by `reseaux`.`Adresse` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `table_route_test`
--

/*!50001 DROP TABLE IF EXISTS `table_route_test`*/;
/*!50001 DROP VIEW IF EXISTS `table_route_test`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`oursinator`@`%` SQL SECURITY DEFINER */
/*!50001 VIEW `table_route_test` AS select `reseaux`.`Adresse` AS `destination` from (`reseaux` join `table_route_generation` on(((`table_route_generation`.`destination` = `reseaux`.`id_reseau`) and (`table_route_generation`.`difficulte` = `reseaux`.`difficulte`)))) where (`table_route_generation`.`difficulte` = 1) union select `ip_machines`.`ip_machine` AS `gateway` from (`ip_machines` join `table_route_generation` on(((`ip_machines`.`id_ip_machine` = `table_route_generation`.`gateway`) and (`table_route_generation`.`difficulte` = `ip_machines`.`Difficulte`)))) where (`table_route_generation`.`difficulte` = 1) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-09-19 16:03:26
