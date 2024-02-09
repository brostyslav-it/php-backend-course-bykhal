-- MySQL dump 10.13  Distrib 8.0.36, for Win64 (x86_64)
--
-- Host: localhost    Database: library
-- ------------------------------------------------------
-- Server version	8.0.36

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `admins`
--

DROP TABLE IF EXISTS `admins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admins` (
  `login` varchar(30) NOT NULL,
  `password` varchar(50) NOT NULL,
  PRIMARY KEY (`login`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admins`
--

LOCK TABLES `admins` WRITE;
/*!40000 ALTER TABLE `admins` DISABLE KEYS */;
INSERT INTO `admins` VALUES ('rostik','123');
/*!40000 ALTER TABLE `admins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `authors`
--

DROP TABLE IF EXISTS `authors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `authors` (
  `id` int NOT NULL AUTO_INCREMENT,
  `author` varchar(300) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `author` (`author`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `authors`
--

LOCK TABLES `authors` WRITE;
/*!40000 ALTER TABLE `authors` DISABLE KEYS */;
INSERT INTO `authors` VALUES (7,'Andrew'),(14,'Arta'),(5,'Artem'),(6,'ArtemA'),(12,'Dater'),(9,'dfdfbdfh'),(17,'gfhfdh'),(15,'Hellooooo'),(16,'HelloooooS'),(8,'Tate'),(13,'Tates'),(10,'Varmel'),(11,'Vartolor'),(1,'Василь Симоненко'),(2,'Леся Українка'),(4,'Михайло Коцюбинський'),(3,'Тарас Шевченко');
/*!40000 ALTER TABLE `authors` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `books`
--

DROP TABLE IF EXISTS `books`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `books` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `img` varchar(100) DEFAULT NULL,
  `year` int DEFAULT NULL,
  `pages` int DEFAULT NULL,
  `isbn` varchar(20) DEFAULT NULL,
  `description` text,
  `viewsCounter` int DEFAULT '0',
  `wantsCounter` int DEFAULT '0',
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `books`
--

LOCK TABLES `books` WRITE;
/*!40000 ALTER TABLE `books` DISABLE KEYS */;
INSERT INTO `books` VALUES (3,'Learn Java','22.jpg',2000,3434,'4fgdsgfsdfg','fdgsgsdfg',5,3,NULL),(6,'Laern Rust with me','46.jpg',2024,525,'fewregi9244ghhdf','I love Rust :)',7,1,NULL),(7,'Learn WEB','39.jpg',2000,233,'fdsfsdg','sdfdsfsdf',9,4,NULL),(10,'gsfdgfsdg','422.jpg',2020,234,'grewgbweg','fdsgdsfgsdg',0,0,NULL),(11,'wertgwret','8image.png',2001,567,'srgeryrewy','34twtewtr',0,0,NULL),(12,'wertwet','default.png',1999,3,'wertwertwert','gtbngrb',0,0,NULL),(13,'fgdfsggs','default.png',344,234,'rdgsdfg','gfdhdh',0,0,NULL);
/*!40000 ALTER TABLE `books` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `books_authors`
--

DROP TABLE IF EXISTS `books_authors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `books_authors` (
  `book_id` int NOT NULL,
  `author_id` int NOT NULL,
  KEY `book_id` (`book_id`),
  KEY `author_id` (`author_id`),
  CONSTRAINT `books_authors_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `authors` (`id`) ON DELETE CASCADE,
  CONSTRAINT `books_authors_ibfk_2` FOREIGN KEY (`author_id`) REFERENCES `authors` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `books_authors`
--

LOCK TABLES `books_authors` WRITE;
/*!40000 ALTER TABLE `books_authors` DISABLE KEYS */;
INSERT INTO `books_authors` VALUES (1,3),(1,2),(1,1),(2,3),(2,2),(2,1),(3,1),(3,3),(3,4),(3,3),(3,2),(6,8),(6,3),(6,6),(6,1),(6,2),(7,7),(7,5),(7,6),(7,8),(7,4),(8,11),(9,14),(9,7),(9,3),(9,8),(9,5),(10,7),(10,14),(10,3),(10,16),(10,12),(11,1),(11,15),(11,3),(11,11),(11,8),(12,4),(12,3),(12,2),(12,14),(12,15),(13,14),(13,4),(13,1),(13,8),(13,17);
/*!40000 ALTER TABLE `books_authors` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations_history`
--

DROP TABLE IF EXISTS `migrations_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations_history` (
  `name` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations_history`
--

LOCK TABLES `migrations_history` WRITE;
/*!40000 ALTER TABLE `migrations_history` DISABLE KEYS */;
INSERT INTO `migrations_history` VALUES ('admins.sql'),('authors.sql'),('books.sql'),('books_authors.sql'),('add_deleted_column.sql');
/*!40000 ALTER TABLE `migrations_history` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-02-01 17:59:49
