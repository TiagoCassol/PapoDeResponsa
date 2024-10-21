CREATE DATABASE  IF NOT EXISTS `papo_de_responsa` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `papo_de_responsa`;
-- MySQL dump 10.13  Distrib 8.0.38, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: papo_de_responsa
-- ------------------------------------------------------
-- Server version	8.0.39

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `contato`
--

DROP TABLE IF EXISTS `contato`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `contato` (
  `id_contato` int NOT NULL AUTO_INCREMENT,
  `id_solicitante` int NOT NULL,
  `numero_contato` varchar(20) DEFAULT NULL,
  `status_contato` char(1) DEFAULT 'A',
  PRIMARY KEY (`id_contato`),
  KEY `contato_ibfk_1` (`id_solicitante`),
  CONSTRAINT `contato_ibfk_1` FOREIGN KEY (`id_solicitante`) REFERENCES `solicitante` (`id_solicitante`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contato`
--

LOCK TABLES `contato` WRITE;
/*!40000 ALTER TABLE `contato` DISABLE KEYS */;
/*!40000 ALTER TABLE `contato` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `multiplicador`
--

DROP TABLE IF EXISTS `multiplicador`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `multiplicador` (
  `id_multiplicador` int NOT NULL AUTO_INCREMENT,
  `nome_multiplicador` varchar(100) NOT NULL,
  `email_multiplicador` varchar(100) NOT NULL,
  `senha_multiplicador` varchar(100) NOT NULL,
  `matricula` varchar(100) NOT NULL,
  `cpf_multiplicador` varchar(20) NOT NULL,
  `endereco_multiplicador` varchar(255) NOT NULL,
  `status_multiplicador` char(1) DEFAULT 'A',
  `nivel_hierarquia` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_multiplicador`),
  UNIQUE KEY `email_multiplicador` (`email_multiplicador`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `multiplicador`
--

LOCK TABLES `multiplicador` WRITE;
/*!40000 ALTER TABLE `multiplicador` DISABLE KEYS */;
INSERT INTO `multiplicador` VALUES (1,'João Silva','joao.silva@email.com','senha123','23121313','123.456.789-00','Rua A, 123 - Cidade','A','administrador'),(2,'Maria Oliveira','maria.oliveira@email.com','senha456','12312132','987.654.321-00','Rua B, 456 - Cidade','A','administrador'),(3,'Pedro Santos','pedro.santos@email.com','senha123','34567890','345.678.901-02','Rua C, 789','A','administrador'),(4,'Ana Costa','ana.costa@email.com','senha123','45678901','456.789.012-03','Rua D, 101','A','administrador'),(5,'Lucas Pereira','lucas.pereira@email.com','senha123','56789012','567.890.123-04','Rua E, 202','A','administrador'),(6,'Carla Almeida','carla.almeida@email.com','senha123','67890123','678.901.234-05','Rua F, 303','A','administrador'),(7,'Rafael Martins','rafael.martins@email.com','senha123','78901234','789.012.345-06','Rua G, 404','A','administrador'),(8,'Juliana Rocha','juliana.rocha@email.com','senha123','89012345','890.123.456-07','Rua H, 505','I','trainee'),(9,'Gabriel Lima','gabriel.lima@email.com','senha123','90123456','901.234.567-08','Rua I, 606','A','trainee'),(11,'Tiago Cassol','cassoltiago7@gmail.com','senha123','1234567','12345678900','R. Cel. Genuíno, 130 - Centro Histórico, Porto Alegre','A','padrao');
/*!40000 ALTER TABLE `multiplicador` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `solicitacao`
--

DROP TABLE IF EXISTS `solicitacao`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `solicitacao` (
  `id_solicitacao` int NOT NULL AUTO_INCREMENT,
  `id_solicitante` int NOT NULL,
  `id_multiplicador` int DEFAULT NULL,
  `data_criacao` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `descricao` text,
  `status_solicitacao` char(1) DEFAULT 'A',
  PRIMARY KEY (`id_solicitacao`),
  KEY `id_multiplicador` (`id_multiplicador`),
  KEY `solicitacao_ibfk_1` (`id_solicitante`),
  CONSTRAINT `solicitacao_ibfk_1` FOREIGN KEY (`id_solicitante`) REFERENCES `solicitante` (`id_solicitante`),
  CONSTRAINT `solicitacao_ibfk_2` FOREIGN KEY (`id_multiplicador`) REFERENCES `multiplicador` (`id_multiplicador`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `solicitacao`
--

LOCK TABLES `solicitacao` WRITE;
/*!40000 ALTER TABLE `solicitacao` DISABLE KEYS */;
INSERT INTO `solicitacao` VALUES (1,1,1,'2024-10-20 17:40:10','Solicitação de palestra sobre suicidio','A'),(2,2,NULL,'2024-10-20 17:40:10','Solicitação de palestra sobre maconha','A'),(3,1,5,'2024-10-21 02:00:00','Palestra sobre segurança pública e prevenção de crimes','A'),(4,2,3,'2024-10-22 02:00:00','Palestra sobre a importância da integração comunitária com a polícia','A'),(5,3,4,'2024-10-23 02:00:00','Palestra sobre o papel da polícia no combate ao tráfico de drogas','A'),(6,4,2,'2024-10-24 02:00:00','Palestra sobre a utilização da tecnologia em investigações policiais','A'),(7,5,1,'2024-10-25 02:00:00','Palestra sobre estratégias para aumentar a confiança da comunidade na polícia','A'),(18,6,NULL,'2024-10-26 02:00:00','Palestra sobre ética policial e comportamento no trabalho','A'),(19,7,NULL,'2024-10-27 02:00:00','Palestra sobre combate à violência urbana e segurança preventiva','A'),(20,8,NULL,'2024-10-28 02:00:00','Palestra sobre a atuação da polícia em eventos de grande porte','A'),(21,9,NULL,'2024-10-29 02:00:00','Palestra sobre patrulhamento comunitário e policiamento de proximidade','A'),(22,10,NULL,'2024-10-30 02:00:00','Palestra sobre o uso da inteligência policial no combate ao crime organizado','A'),(23,11,NULL,'2024-10-31 02:00:00','Palestra sobre direitos humanos e a atuação policial','A'),(24,12,NULL,'2024-11-01 02:00:00','Palestra sobre a importância da formação continuada dos policiais','A'),(25,4,1,'2024-11-02 02:00:00','Palestra sobre novas estratégias para melhorar a segurança pública','A'),(26,7,NULL,'2024-11-03 02:00:00','Palestra sobre o uso de drones em operações policiais','A'),(27,10,NULL,'2024-11-04 02:00:00','Palestra sobre cooperação entre a polícia e outros órgãos de segurança','A');
/*!40000 ALTER TABLE `solicitacao` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `solicitante`
--

DROP TABLE IF EXISTS `solicitante`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `solicitante` (
  `id_solicitante` int NOT NULL AUTO_INCREMENT,
  `cnpj` varchar(30) NOT NULL,
  `email_solicitante` varchar(100) NOT NULL,
  `senha_solicitante` varchar(100) NOT NULL,
  `responsavel` varchar(100) NOT NULL,
  `tipo_escola` varchar(30) NOT NULL,
  `endereco_solicitante` varchar(255) NOT NULL,
  `status_solicitante` char(1) DEFAULT 'A',
  `nome_instituicao` varchar(255) DEFAULT NULL,
  `esfera` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_solicitante`),
  UNIQUE KEY `email_solicitante` (`email_solicitante`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `solicitante`
--

LOCK TABLES `solicitante` WRITE;
/*!40000 ALTER TABLE `solicitante` DISABLE KEYS */;
INSERT INTO `solicitante` VALUES (1,'12.345.678/0001-01','escola@email.com','senha789','José','Escola Municipal','Rua X, 789 - Cidade','A','Escola Exemplo 1','privada'),(2,'98.765.432/0001-02','creche@email.com','senha012','Ana','Creche','Rua Y, 012 - Cidade','A','Escola Exemplo 2','publica'),(3,'12.345.678/0001-01','contato@escola1.edu.br','senha123','João da Silva','Municipal - Ensino Fundamental','Rua dos Andradas, 123','A','Escola Municipal Porto Alegre','Publica'),(4,'23.456.789/0001-02','contato@escola2.edu.br','senha123','Maria Oliveira','Estadual - Ensino Médio','Av. Borges de Medeiros, 456','A','Colégio Estadual Rio Grande','Publica'),(5,'34.567.890/0001-03','contato@escola3.edu.br','senha123','Carlos Souza','Federal - Ensino Superior','Rua da Praia, 789','A','Universidade Federal de Porto Alegre','Publica'),(6,'45.678.901/0001-04','contato@escola4.edu.br','senha123','Ana Costa','Municipal - Ensino Fundamental','Rua General Lima e Silva, 101','A','Escola Municipal Bento Gonçalves','Publica'),(7,'56.789.012/0001-05','contato@escola5.edu.br','senha123','Pedro Lima','Estadual - Ensino Médio','Av. Protásio Alves, 202','A','Colégio Estadual Protásio Alves','Publica'),(8,'67.890.123/0001-06','contato@escola6.edu.br','senha123','Fernanda Ribeiro','Privada - Ensino Superior','Rua Padre Chagas, 303','A','Faculdade Privada Dom Bosco','Privada'),(9,'78.901.234/0001-07','contato@escola7.edu.br','senha123','Rafael Martins','Privada - Ensino Médio','Av. Ipiranga, 404','A','Colégio Privado São João','Privada'),(10,'89.012.345/0001-08','contato@escola8.edu.br','senha123','Juliana Rocha','Municipal - Ensino Fundamental','Rua Coronel Bordini, 505','A','Escola Municipal Floriano Peixoto','Publica'),(11,'90.123.456/0001-09','contato@escola9.edu.br','senha123','Gabriel Lima','Estadual - Ensino Médio','Rua Dom Pedro II, 606','A','Colégio Estadual Dom Pedro II','Publica'),(12,'01.234.567/0001-10','contato@escola10.edu.br','senha123','Lucas Pereira','Privada - Ensino Superior','Av. Cristóvão Colombo, 707','A','Faculdade Privada Unisinos','Privada');
/*!40000 ALTER TABLE `solicitante` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-10-20 23:24:44
