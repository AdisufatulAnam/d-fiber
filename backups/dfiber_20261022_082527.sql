-- MySQL dump 10.13  Distrib 8.0.45, for Linux (aarch64)
--
-- Host: localhost    Database: dfiber
-- ------------------------------------------------------
-- Server version	8.0.45

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
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
INSERT INTO `cache` VALUES ('laravel-cache-1b6d4b01dc242adafb0b19e203f0504bef607ab9','i:1;',1792053112),('laravel-cache-1b6d4b01dc242adafb0b19e203f0504bef607ab9:timer','i:1792053112;',1792053112),('laravel-cache-661240f1dd849025a3f431ab546141e78c89cbff','i:1;',1792591998),('laravel-cache-661240f1dd849025a3f431ab546141e78c89cbff:timer','i:1792591998;',1792591998),('laravel-cache-7aac73ee52be99d2036ea2e46ccd14c4086b4d89','i:2;',1792631887),('laravel-cache-7aac73ee52be99d2036ea2e46ccd14c4086b4d89:timer','i:1792631887;',1792631887),('laravel-cache-eec532143a3deb0c912ecdb344c1bc5968990d94','i:1;',1792590190),('laravel-cache-eec532143a3deb0c912ecdb344c1bc5968990d94:timer','i:1792590190;',1792590190);
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customers`
--

DROP TABLE IF EXISTS `customers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `customers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `package_id` bigint unsigned DEFAULT NULL,
  `qr_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `customers_qr_code_unique` (`qr_code`),
  KEY `customers_qr_code_index` (`qr_code`),
  KEY `customers_package_id_foreign` (`package_id`),
  CONSTRAINT `customers_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=74 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customers`
--

LOCK TABLES `customers` WRITE;
/*!40000 ALTER TABLE `customers` DISABLE KEYS */;
INSERT INTO `customers` VALUES (1,'mbah dukun','083135322094','Piyungan, Jogja','192.168.77.2',1,2,'DFB-A73C1259','2026-10-14 18:28:05','2026-10-21 19:01:09'),(2,'imam prasetyo','083135322094','Piyungan, Jogja','192.168.77.3',1,2,'DFB-6D0054C5','2026-10-15 17:02:02','2026-10-21 19:01:09'),(3,'wahyu','083135322094','Piyungan, Jogja','192.168.77.4',1,2,'DFB-D95B1383','2026-10-15 17:02:20','2026-10-21 19:01:09'),(4,'darto','083135322094','Piyungan, Jogja','192.168.77.5',1,2,'DFB-2F792C95','2026-10-15 17:02:28','2026-10-21 19:01:09'),(5,'sarman','083135322094','Piyungan, Jogja','192.168.77.6',1,2,'DFB-F70FF48E','2026-10-15 17:02:37','2026-10-21 19:01:09'),(6,'joko','083135322094','Piyungan, Jogja','192.168.77.7',1,2,'DFB-3E12AE4C','2026-10-15 17:02:45','2026-10-21 19:01:09'),(7,'wiji','083135322094','Piyungan, Jogja','192.168.77.8',1,2,'DFB-C25ECA55','2026-10-15 17:02:53','2026-10-21 19:01:09'),(8,'sudiyo','083135322094','Piyungan, Jogja','192.168.77.9',1,2,'DFB-AC6BB663','2026-10-21 15:28:42','2026-10-21 19:01:09'),(9,'triyanto','083135322094','Piyungan, Jogja','192.168.77.10',1,2,'DFB-00718AA7','2026-10-21 15:28:42','2026-10-21 19:01:09'),(10,'kembar','083135322094','Piyungan, Jogja','192.168.77.11',1,2,'DFB-7545A804','2026-10-21 15:28:42','2026-10-21 19:01:09'),(11,'sularman','083135322094','Piyungan, Jogja','192.168.77.12',1,2,'DFB-C25D6408','2026-10-21 15:28:42','2026-10-21 19:01:09'),(12,'sardiyono','083135322094','Piyungan, Jogja','192.168.77.13',1,2,'DFB-569B62DD','2026-10-21 15:28:42','2026-10-21 19:01:09'),(13,'sarjono','083135322094','Piyungan, Jogja','192.168.77.14',1,2,'DFB-FB36A752','2026-10-21 15:28:42','2026-10-21 19:01:09'),(14,'siswanto','083135322094','Piyungan, Jogja','192.168.77.15',1,2,'DFB-314E578D','2026-10-21 15:28:42','2026-10-21 19:01:09'),(15,'fanni','083135322094','Piyungan, Jogja','192.168.77.16',1,2,'DFB-4E8F031D','2026-10-21 15:28:42','2026-10-21 19:01:09'),(16,'tukijo','083135322094','Piyungan, Jogja','192.168.77.17',1,2,'DFB-4BBA2794','2026-10-21 15:28:42','2026-10-21 19:01:09'),(17,'suradi','083135322094','Piyungan, Jogja','192.168.77.18',1,2,'DFB-4DA4FE8C','2026-10-21 15:28:42','2026-10-21 19:01:09'),(18,'triyuwono','083135322094','Piyungan, Jogja','192.168.77.19',1,2,'DFB-24F97D68','2026-10-21 15:28:42','2026-10-21 19:01:09'),(19,'pipit','083135322094','Piyungan, Jogja','192.168.77.20',1,2,'DFB-83DBC0B2','2026-10-21 15:28:42','2026-10-21 19:01:09'),(20,'teplo','083135322094','Piyungan, Jogja','192.168.77.21',1,2,'DFB-CB1F3A15','2026-10-21 15:28:42','2026-10-21 19:01:09'),(21,'wardiyanto','083135322094','Piyungan, Jogja','192.168.77.22',1,2,'DFB-E4464F64','2026-10-21 15:28:42','2026-10-21 19:01:09'),(22,'bobby','083135322094','Piyungan, Jogja','192.168.77.23',1,2,'DFB-F1A934C4','2026-10-21 15:28:42','2026-10-21 19:01:09'),(23,'kurnia','083135322094','Piyungan, Jogja','192.168.77.24',1,2,'DFB-2BC2AEBD','2026-10-21 15:28:42','2026-10-21 19:01:09'),(24,'octavia','083135322094','Piyungan, Jogja','192.168.77.25',1,2,'DFB-FAE04254','2026-10-21 15:28:42','2026-10-21 19:01:09'),(25,'rujik','083135322094','Piyungan, Jogja','192.168.77.26',1,2,'DFB-6FB4CEB4','2026-10-21 15:28:42','2026-10-21 19:01:09'),(26,'siti','083135322094','Piyungan, Jogja','192.168.77.27',1,2,'DFB-8B1CCE2F','2026-10-21 15:28:42','2026-10-21 19:01:09'),(27,'giar','083135322094','Piyungan, Jogja','192.168.77.28',1,2,'DFB-90CD9A3B','2026-10-21 15:28:42','2026-10-21 19:01:09'),(28,'mendud','083135322094','Piyungan, Jogja','192.168.77.29',1,2,'DFB-3572EDC0','2026-10-21 15:28:42','2026-10-21 19:01:09'),(29,'giman','083135322094','Piyungan, Jogja','192.168.77.30',1,2,'DFB-5CB33354','2026-10-21 15:28:42','2026-10-21 19:01:09'),(30,'herman','083135322094','Piyungan, Jogja','192.168.77.31',1,2,'DFB-7C6D1D50','2026-10-21 15:28:42','2026-10-21 19:01:09'),(31,'giyanto','083135322094','Piyungan, Jogja','192.168.77.32',1,2,'DFB-D70C98F5','2026-10-21 15:28:42','2026-10-21 19:01:09'),(32,'aris','083135322094','Piyungan, Jogja','192.168.77.33',1,2,'DFB-5624FDBA','2026-10-21 15:28:42','2026-10-21 19:01:09'),(33,'dukuh','083135322094','Piyungan, Jogja','192.168.77.34',1,2,'DFB-EF3450E6','2026-10-21 15:28:42','2026-10-21 19:01:09'),(34,'musbani','083135322094','Piyungan, Jogja','192.168.77.35',1,2,'DFB-1B8D0333','2026-10-21 15:28:42','2026-10-21 19:01:09'),(35,'andri','083135322094','Piyungan, Jogja','192.168.77.36',1,2,'DFB-3900B2DF','2026-10-21 15:28:42','2026-10-21 19:01:09'),(36,'kodet','083135322094','Piyungan, Jogja','192.168.77.37',1,2,'DFB-318696E4','2026-10-21 15:28:42','2026-10-21 19:01:09'),(37,'wahyudi','083135322094','Piyungan, Jogja','192.168.77.38',1,2,'DFB-8154EBD8','2026-10-21 15:28:42','2026-10-21 19:01:09'),(38,'supri','083135322094','Piyungan, Jogja','192.168.77.39',1,2,'DFB-16DD5717','2026-10-21 15:28:42','2026-10-21 19:01:09'),(39,'toni','083135322094','Piyungan, Jogja','192.168.77.40',1,2,'DFB-1E214DCF','2026-10-21 15:28:42','2026-10-21 19:01:09'),(40,'sutarno','083135322094','Piyungan, Jogja','192.168.77.41',1,2,'DFB-530AC811','2026-10-21 15:28:42','2026-10-21 19:01:09'),(41,'ari','083135322094','Piyungan, Jogja','192.168.77.42',1,2,'DFB-96BA8B9F','2026-10-21 15:28:42','2026-10-21 19:01:09'),(42,'sugeng','083135322094','Piyungan, Jogja','192.168.77.43',1,2,'DFB-FBD2C872','2026-10-21 15:28:42','2026-10-21 19:01:09'),(43,'kahar','083135322094','Piyungan, Jogja','192.168.77.44',1,2,'DFB-4391F1EF','2026-10-21 15:28:42','2026-10-21 19:01:09'),(44,'riris','083135322094','Piyungan, Jogja','192.168.77.45',1,2,'DFB-73BDF4AE','2026-10-21 15:28:42','2026-10-21 19:01:09'),(45,'gimin','083135322094','Piyungan, Jogja','192.168.77.46',1,2,'DFB-800A4F57','2026-10-21 15:28:42','2026-10-21 19:01:09'),(46,'diva','083135322094','Piyungan, Jogja','192.168.77.47',1,2,'DFB-5FE5BB32','2026-10-21 15:28:42','2026-10-21 19:01:09'),(47,'eko','083135322094','Piyungan, Jogja','192.168.77.48',0,2,'DFB-4D3AB4C6','2026-10-21 15:28:42','2026-10-21 19:01:09'),(48,'farida','083135322094','Piyungan, Jogja','192.168.77.49',1,2,'DFB-0FFA8E78','2026-10-21 15:28:42','2026-10-21 19:01:09'),(49,'retno','083135322094','Piyungan, Jogja','192.168.77.50',1,2,'DFB-1FFC90CC','2026-10-21 15:28:42','2026-10-21 19:01:09'),(50,'angga','083135322094','Piyungan, Jogja','192.168.77.51',1,2,'DFB-ED5A4588','2026-10-21 15:28:42','2026-10-21 19:01:09'),(51,'takim','083135322094','Piyungan, Jogja','192.168.77.52',1,2,'DFB-1BD9AEBE','2026-10-21 15:28:42','2026-10-21 19:01:09'),(52,'nuri','083135322094','Piyungan, Jogja','192.168.77.53',1,2,'DFB-0F91AE70','2026-10-21 15:28:42','2026-10-21 19:01:09'),(53,'jamingan','083135322094','Piyungan, Jogja','192.168.77.54',1,2,'DFB-183BE7C3','2026-10-21 15:28:42','2026-10-21 19:01:09'),(54,'yoga','083135322094','Piyungan, Jogja','192.168.77.55',1,2,'DFB-4D0D842B','2026-10-21 15:28:42','2026-10-21 19:01:09'),(55,'partini','083135322094','Piyungan, Jogja','192.168.77.56',1,2,'DFB-55F88A14','2026-10-21 15:28:42','2026-10-21 19:01:09'),(56,'puput','083135322094','Piyungan, Jogja','192.168.77.57',1,2,'DFB-0FE62837','2026-10-21 15:28:42','2026-10-21 19:01:09'),(57,'putri','083135322094','Piyungan, Jogja','192.168.77.58',1,2,'DFB-062773B6','2026-10-21 15:28:42','2026-10-21 19:01:09'),(58,'isa','083135322094','Piyungan, Jogja','192.168.77.59',1,2,'DFB-C9D9CF64','2026-10-21 15:28:42','2026-10-21 19:01:09'),(59,'azis ab karya','083135322094','Piyungan, Jogja','192.168.77.60',1,2,'DFB-205E6680','2026-10-21 15:28:42','2026-10-21 19:01:09'),(60,'rizki ardi','083135322094','Piyungan, Jogja','192.168.77.61',1,2,'DFB-88FB6A59','2026-10-21 15:28:42','2026-10-21 19:01:09'),(61,'apri','083135322094','Piyungan, Jogja','192.168.77.62',1,2,'DFB-8C173850','2026-10-21 15:28:42','2026-10-21 19:01:09'),(62,'sigit','083135322094','Piyungan, Jogja','192.168.77.63',1,2,'DFB-B34B463F','2026-10-21 15:28:42','2026-10-21 19:01:09'),(63,'dika','083135322094','Piyungan, Jogja','192.168.77.64',1,2,'DFB-EB948347','2026-10-21 15:28:42','2026-10-21 19:01:09'),(64,'amri','083135322094','Piyungan, Jogja','192.168.77.65',1,2,'DFB-3196A445','2026-10-21 15:28:42','2026-10-21 19:01:09'),(65,'mogrong','083135322094','Piyungan, Jogja','192.168.77.66',1,2,'DFB-40716112','2026-10-21 15:28:42','2026-10-21 19:01:09'),(66,'dorex','083135322094','Piyungan, Jogja','192.168.77.67',1,2,'DFB-38197FE8','2026-10-21 15:28:42','2026-10-21 19:01:09'),(67,'fitri','083135322094','Piyungan, Jogja','192.168.77.68',1,2,'DFB-9E00F219','2026-10-21 15:28:42','2026-10-21 19:01:09'),(68,'eko rio','083135322094','Piyungan, Jogja','192.168.77.69',1,2,'DFB-4FD99207','2026-10-21 15:28:42','2026-10-21 19:01:09'),(69,'rowi','083135322094','Piyungan, Jogja','192.168.77.70',1,2,'DFB-EAE92760','2026-10-21 15:28:42','2026-10-21 19:01:09'),(70,'bekti','083135322094','Piyungan, Jogja','192.168.77.71',1,2,'DFB-A4B64587','2026-10-21 15:28:42','2026-10-21 19:01:09'),(72,'Dedy Brian Nugroho','082138077077','banguntapan bantul yogyakarta','192.168.77.71',1,2,'DFB-62AD5661','2026-10-21 19:09:44','2026-10-21 19:09:44'),(73,'Gigih Ragasa Jiwo','085799141931','kanoman banguntapan janti yogyakarta','192.168.77.72',1,2,'DFB-16D64E40','2026-10-21 19:13:09','2026-10-21 19:13:09');
/*!40000 ALTER TABLE `customers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` smallint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
INSERT INTO `jobs` VALUES (26,'default','{\"uuid\":\"301c0098-f469-4b0f-957c-d88517578c0b\",\"displayName\":\"App\\\\Jobs\\\\SendPaymentInvoice\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":5,\"maxExceptions\":null,\"failOnTimeout\":false,\"countCrashesAsExceptions\":false,\"backoff\":\"30,60,120,300,600\",\"timeout\":null,\"retryUntil\":null,\"deleteWhenMissingModels\":false,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendPaymentInvoice\",\"command\":\"O:27:\\\"App\\\\Jobs\\\\SendPaymentInvoice\\\":17:{s:5:\\\"tries\\\";i:5;s:7:\\\"backoff\\\";a:5:{i:0;i:30;i:1;i:60;i:2;i:120;i:3;i:300;i:4;i:600;}s:9:\\\"paymentId\\\";i:199;s:3:\\\"job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:13:\\\"debounceOwner\\\";s:0:\\\"\\\";s:15:\\\"uniqueLockOwner\\\";s:0:\\\"\\\";s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\",\"batchId\":null},\"createdAt\":1792631876,\"delay\":null}',2,1792631963,1792631962,1792631932);
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_10_14_000001_add_role_to_users_table',2),(5,'2026_10_15_000001_create_payments_table',3),(6,'2026_10_15_000002_create_customers_table',4),(7,'2026_10_15_000003_add_customer_id_to_payments_table',4),(8,'2026_10_15_000004_create_packages_table',5),(9,'2026_10_15_000005_add_package_id_to_customers_table',5),(10,'2026_10_21_000001_add_ip_address_to_customers_table',6),(11,'2026_10_21_000002_add_is_active_to_customers_table',7),(12,'2026_10_22_000001_add_invoice_number_to_payments_table',8),(13,'2026_10_22_000002_add_soft_deletes_to_payments_table',9),(14,'2026_10_22_000003_create_payment_whatsapp_logs_table',10);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `packages`
--

DROP TABLE IF EXISTS `packages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `packages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `speed` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` decimal(12,2) NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `packages`
--

LOCK TABLES `packages` WRITE;
/*!40000 ALTER TABLE `packages` DISABLE KEYS */;
INSERT INTO `packages` VALUES (1,'Fiber 20 Mbps','20',200000.00,NULL,1,'2026-10-14 18:28:28','2026-10-15 01:07:12'),(2,'Fiber 10 Mbps','10 Mbps',100000.00,NULL,1,'2026-10-15 01:06:42','2026-10-15 01:06:42');
/*!40000 ALTER TABLE `packages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payment_whatsapp_logs`
--

DROP TABLE IF EXISTS `payment_whatsapp_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payment_whatsapp_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `payment_id` bigint unsigned NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `attempts` int unsigned NOT NULL DEFAULT '0',
  `message` text COLLATE utf8mb4_unicode_ci,
  `invoice_file` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `error` text COLLATE utf8mb4_unicode_ci,
  `response` text COLLATE utf8mb4_unicode_ci,
  `sent_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `payment_whatsapp_logs_payment_id_foreign` (`payment_id`),
  KEY `payment_whatsapp_logs_status_index` (`status`),
  CONSTRAINT `payment_whatsapp_logs_payment_id_foreign` FOREIGN KEY (`payment_id`) REFERENCES `payments` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payment_whatsapp_logs`
--

LOCK TABLES `payment_whatsapp_logs` WRITE;
/*!40000 ALTER TABLE `payment_whatsapp_logs` DISABLE KEYS */;
INSERT INTO `payment_whatsapp_logs` VALUES (4,135,'6283184683492','failed',14,'Assalamu’alaikum / Selamat malam Bapak/Ibu 😊\n\nKami dari *wiji.net* mengirimkan invoice pembayaran WiFi untuk periode *November 2026*.\n\n📄 *DETAIL PEMBAYARAN WIFI*\n👤 Nama Pelanggan: giman\n🆔 ID Pelanggan: 29\n📅 Periode Pembayaran: November 2026\n💰 Total Pembayaran: Rp100.000\n\n📎 Invoice pembayaran kami lampirkan dalam bentuk file PDF pada pesan ini.\n\nTerima kasih atas kepercayaan Bapak/Ibu dalam menggunakan layanan internet *wiji.net*. Semoga layanan kami selalu memberikan kenyamanan dan kelancaran dalam beraktivitas. 🙏🌐\n\nSalam hangat,\n*Admin wiji.net*','invoices/135-20261021185617.pdf','SQLSTATE[22001]: String data, right truncated: 1406 Data too long for column \'response\' at row 1 (Connection: mysql, Host: mysql, Port: 3306, Database: dfiber, SQL: update `payment_whatsapp_logs` set `status` = sent, `response` = {\"code\":\"SUCCESS\",\"message\":\"Message sent to 6283184683492@s.whatsapp.net (server timestamp: 2026-10-21 18:59:02 +0000 UTC)\",\"results\":{\"message_id\":\"3EB022BA19E63B049FA5F7\",\"status\":\"Message sent to 6283184683492@s.whatsapp.net (server timestamp: 2026-10-21 18:59:02 +0000 UTC)\"}}, `sent_at` = 2026-10-21 18:59:02, `payment_whatsapp_logs`.`updated_at` = 2026-10-21 18:59:02 where `id` = 4)',NULL,NULL,'2026-10-21 18:49:58','2026-10-21 18:59:02'),(7,146,'6283135322094','sent',1,'Assalamu’alaikum / Selamat malam Bapak/Ibu 😊\n\nKami dari *wiji.net* mengirimkan invoice pembayaran WiFi untuk periode *Desember 2025*.\n\n📄 *DETAIL PEMBAYARAN WIFI*\n👤 Nama Pelanggan: giman\n🆔 ID Pelanggan: 29\n📅 Periode Pembayaran: Desember 2025\n💰 Total Pembayaran: Rp100.000\n\n📎 Invoice pembayaran kami lampirkan dalam bentuk file PDF pada pesan ini.\n\nTerima kasih atas kepercayaan Bapak/Ibu dalam menggunakan layanan internet *wiji.net*. Semoga layanan kami selalu memberikan kenyamanan dan kelancaran dalam beraktivitas. 🙏🌐\n\nSalam hangat,\n*Admin wiji.net*',NULL,NULL,'{\"code\":\"SUCCESS\",\"message\":\"Message sent to 6283135322094@s.whatsapp.net (server timestamp: 2026-10-21 19:02:01 +0000 UTC)\",\"results\":{\"message_id\":\"3EB05D7B25A8E096F60047\",\"status\":\"Message sent to 6283135322094@s.whatsapp.net (server timestamp: 2026-10-21 19:02:01 +0000 UTC)\"}}','2026-10-21 19:02:01','2026-10-21 19:01:51','2026-10-21 19:02:01'),(11,161,'6282138077077','sent',1,'Assalamu’alaikum / Selamat malam Bapak/Ibu 😊\n\nKami dari *wiji.net* mengirimkan invoice pembayaran WiFi untuk periode *November 2025*.\n\n📄 *DETAIL PEMBAYARAN WIFI*\n👤 Nama Pelanggan: Dedy Brian Nugroho\n🆔 ID Pelanggan: 72\n📅 Periode Pembayaran: November 2025\n💰 Total Pembayaran: Rp100.000\n\n📎 Invoice pembayaran kami lampirkan dalam bentuk file PDF pada pesan ini.\n\nTerima kasih atas kepercayaan Bapak/Ibu dalam menggunakan layanan internet *wiji.net*. Semoga layanan kami selalu memberikan kenyamanan dan kelancaran dalam beraktivitas. 🙏🌐\n\nSalam hangat,\n*Admin wiji.net*',NULL,NULL,'{\"code\":\"SUCCESS\",\"message\":\"Message sent to 6282138077077@s.whatsapp.net (server timestamp: 2026-10-21 19:10:03 +0000 UTC)\",\"results\":{\"message_id\":\"3EB00147A479735D81F393\",\"status\":\"Message sent to 6282138077077@s.whatsapp.net (server timestamp: 2026-10-21 19:10:03 +0000 UTC)\"}}','2026-10-21 19:10:03','2026-10-21 19:09:55','2026-10-21 19:10:03'),(12,134,'6283135322094','sent',1,'Assalamu’alaikum / Selamat malam Bapak/Ibu 😊\n\nKami dari *wiji.net* mengirimkan invoice pembayaran WiFi untuk periode *November 2025*.\n\n📄 *DETAIL PEMBAYARAN WIFI*\n👤 Nama Pelanggan: imam prasetyo\n🆔 ID Pelanggan: 2\n📅 Periode Pembayaran: November 2025\n💰 Total Pembayaran: Rp100.000\n\n📎 Invoice pembayaran kami lampirkan dalam bentuk file PDF pada pesan ini.\n\nTerima kasih atas kepercayaan Bapak/Ibu dalam menggunakan layanan internet *wiji.net*. Semoga layanan kami selalu memberikan kenyamanan dan kelancaran dalam beraktivitas. 🙏🌐\n\nSalam hangat,\n*Admin wiji.net*','invoices/134-20261021191030.pdf',NULL,'{\"code\":\"SUCCESS\",\"message\":\"Document sent to 6283135322094@s.whatsapp.net (server timestamp: 2026-10-21 19:10:32 +0000 UTC)\",\"results\":{\"message_id\":\"3EB0239349D666D480811F\",\"status\":\"Document sent to 6283135322094@s.whatsapp.net (server timestamp: 2026-10-21 19:10:32 +0000 UTC)\"}}','2026-10-21 19:10:32','2026-10-21 19:10:30','2026-10-21 19:10:32'),(14,145,'6283135322094','sent',1,'Assalamu’alaikum / Selamat malam Bapak/Ibu 😊\n\nKami dari *wiji.net* mengirimkan invoice pembayaran WiFi untuk periode *Januari 2026*.\n\n📄 *DETAIL PEMBAYARAN WIFI*\n👤 Nama Pelanggan: giman\n🆔 ID Pelanggan: 29\n📅 Periode Pembayaran: Januari 2026\n💰 Total Pembayaran: Rp100.000\n\n📎 Invoice pembayaran kami lampirkan dalam bentuk file PDF pada pesan ini.\n\nTerima kasih atas kepercayaan Bapak/Ibu dalam menggunakan layanan internet *wiji.net*. Semoga layanan kami selalu memberikan kenyamanan dan kelancaran dalam beraktivitas. 🙏🌐\n\nSalam hangat,\n*Admin wiji.net*',NULL,NULL,'{\"code\":\"SUCCESS\",\"message\":\"Message sent to 6283135322094@s.whatsapp.net (server timestamp: 2026-10-21 19:16:27 +0000 UTC)\",\"results\":{\"message_id\":\"3EB0A1923E665357D23110\",\"status\":\"Message sent to 6283135322094@s.whatsapp.net (server timestamp: 2026-10-21 19:16:27 +0000 UTC)\"}}','2026-10-21 19:16:27','2026-10-21 19:16:25','2026-10-21 19:16:27'),(15,144,'6283135322094','sent',1,'Assalamu’alaikum / Selamat malam Bapak/Ibu 😊\n\nKami dari *wiji.net* mengirimkan invoice pembayaran WiFi untuk periode *Februari 2026*.\n\n📄 *DETAIL PEMBAYARAN WIFI*\n👤 Nama Pelanggan: giman\n🆔 ID Pelanggan: 29\n📅 Periode Pembayaran: Februari 2026\n💰 Total Pembayaran: Rp100.000\n\n📎 Invoice pembayaran kami lampirkan dalam bentuk file PDF pada pesan ini.\n\nTerima kasih atas kepercayaan Bapak/Ibu dalam menggunakan layanan internet *wiji.net*. Semoga layanan kami selalu memberikan kenyamanan dan kelancaran dalam beraktivitas. 🙏🌐\n\nSalam hangat,\n*Admin wiji.net*',NULL,NULL,'{\"code\":\"SUCCESS\",\"message\":\"Message sent to 6283135322094@s.whatsapp.net (server timestamp: 2026-10-21 19:16:49 +0000 UTC)\",\"results\":{\"message_id\":\"3EB073BD72ECB020C60C50\",\"status\":\"Message sent to 6283135322094@s.whatsapp.net (server timestamp: 2026-10-21 19:16:49 +0000 UTC)\"}}','2026-10-21 19:16:50','2026-10-21 19:16:47','2026-10-21 19:16:50'),(16,187,'6283135322094','sent',1,'Assalamu’alaikum / Selamat malam Bapak/Ibu 😊\n\nKami dari *wiji.net* mengirimkan invoice pembayaran WiFi untuk periode *November 2025*.\n\n📄 *DETAIL PEMBAYARAN WIFI*\n👤 Nama Pelanggan: suradi\n🆔 ID Pelanggan: 17\n📅 Periode Pembayaran: November 2025\n💰 Total Pembayaran: Rp100.000\n\n📎 Invoice pembayaran kami lampirkan dalam bentuk file PDF pada pesan ini.\n\nTerima kasih atas kepercayaan Bapak/Ibu dalam menggunakan layanan internet *wiji.net*. Semoga layanan kami selalu memberikan kenyamanan dan kelancaran dalam beraktivitas. 🙏🌐\n\nSalam hangat,\n*Admin wiji.net*',NULL,NULL,'{\"code\":\"SUCCESS\",\"message\":\"Message sent to 6283135322094@s.whatsapp.net (server timestamp: 2026-10-21 19:20:55 +0000 UTC)\",\"results\":{\"message_id\":\"3EB0D581E957806AEE745E\",\"status\":\"Message sent to 6283135322094@s.whatsapp.net (server timestamp: 2026-10-21 19:20:55 +0000 UTC)\"}}','2026-10-21 19:20:55','2026-10-21 19:20:53','2026-10-21 19:20:55'),(17,186,'6283135322094','sent',1,'Assalamu’alaikum / Selamat malam Bapak/Ibu 😊\n\nKami dari *wiji.net* mengirimkan invoice pembayaran WiFi untuk periode *Desember 2025*.\n\n📄 *DETAIL PEMBAYARAN WIFI*\n👤 Nama Pelanggan: suradi\n🆔 ID Pelanggan: 17\n📅 Periode Pembayaran: Desember 2025\n💰 Total Pembayaran: Rp100.000\n\n📎 Invoice pembayaran kami lampirkan dalam bentuk file PDF pada pesan ini.\n\nTerima kasih atas kepercayaan Bapak/Ibu dalam menggunakan layanan internet *wiji.net*. Semoga layanan kami selalu memberikan kenyamanan dan kelancaran dalam beraktivitas. 🙏🌐\n\nSalam hangat,\n*Admin wiji.net*','invoices/186-20261021192127.pdf',NULL,'{\"code\":\"SUCCESS\",\"message\":\"Document sent to 6283135322094@s.whatsapp.net (server timestamp: 2026-10-21 19:21:29 +0000 UTC)\",\"results\":{\"message_id\":\"3EB082EB2574B8D4F24742\",\"status\":\"Document sent to 6283135322094@s.whatsapp.net (server timestamp: 2026-10-21 19:21:29 +0000 UTC)\"}}','2026-10-21 19:21:29','2026-10-21 19:21:27','2026-10-21 19:21:29'),(18,185,'6283135322094','sent',1,'Assalamu’alaikum / Selamat malam Bapak/Ibu 😊\n\nKami dari *wiji.net* mengirimkan invoice pembayaran WiFi untuk periode *Januari 2026*.\n\n📄 *DETAIL PEMBAYARAN WIFI*\n👤 Nama Pelanggan: suradi\n🆔 ID Pelanggan: 17\n📅 Periode Pembayaran: Januari 2026\n💰 Total Pembayaran: Rp100.000\n\n📎 Invoice pembayaran kami lampirkan dalam bentuk file PDF pada pesan ini.\n\nTerima kasih atas kepercayaan Bapak/Ibu dalam menggunakan layanan internet *wiji.net*. Semoga layanan kami selalu memberikan kenyamanan dan kelancaran dalam beraktivitas. 🙏🌐\n\nSalam hangat,\n*Admin wiji.net*','invoices/suradi_PAY26102100038.pdf',NULL,'{\"code\":\"SUCCESS\",\"message\":\"Document sent to 6283135322094@s.whatsapp.net (server timestamp: 2026-10-21 19:23:26 +0000 UTC)\",\"results\":{\"message_id\":\"3EB0E4697ADC0F4D9AFA3C\",\"status\":\"Document sent to 6283135322094@s.whatsapp.net (server timestamp: 2026-10-21 19:23:26 +0000 UTC)\"}}','2026-10-21 19:23:26','2026-10-21 19:23:24','2026-10-21 19:23:26'),(20,200,'6283135322094','sent',1,'Assalamu’alaikum / Selamat malam Bapak/Ibu 😊\n\nKami dari *wiji.net* mengirimkan invoice pembayaran WiFi untuk periode *November 2025*.\n\n📄 *DETAIL PEMBAYARAN WIFI*\n👤 Nama Pelanggan: jamingan\n🆔 ID Pelanggan: 53\n📅 Periode Pembayaran: November 2025\n💰 Total Pembayaran: Rp100.000\n\n📎 Invoice pembayaran kami lampirkan dalam bentuk file PDF pada pesan ini.\n\nTerima kasih atas kepercayaan Bapak/Ibu dalam menggunakan layanan internet *wiji.net*. Semoga layanan kami selalu memberikan kenyamanan dan kelancaran dalam beraktivitas. 🙏🌐\n\nSalam hangat,\n*Admin wiji.net*','invoices/jamingan_PAY26102100039.pdf',NULL,'{\"code\":\"SUCCESS\",\"message\":\"Document sent to 6283135322094@s.whatsapp.net (server timestamp: 2026-10-21 19:25:17 +0000 UTC)\",\"results\":{\"message_id\":\"3EB0DFDCCE7F7F32F76571\",\"status\":\"Document sent to 6283135322094@s.whatsapp.net (server timestamp: 2026-10-21 19:25:17 +0000 UTC)\"}}','2026-10-21 19:25:17','2026-10-21 19:25:11','2026-10-21 19:25:17'),(21,174,'6285799141931','sent',1,'Assalamu’alaikum / Selamat malam Bapak/Ibu 😊\n\nKami dari *wiji.net* mengirimkan invoice pembayaran WiFi untuk periode *November 2025*.\n\n📄 *DETAIL PEMBAYARAN WIFI*\n👤 Nama Pelanggan: Gigih Ragasa Jiwo\n🆔 ID Pelanggan: 73\n📅 Periode Pembayaran: November 2025\n💰 Total Pembayaran: Rp100.000\n\n📎 Invoice pembayaran kami lampirkan dalam bentuk file PDF pada pesan ini.\n\nTerima kasih atas kepercayaan Bapak/Ibu dalam menggunakan layanan internet *wiji.net*. Semoga layanan kami selalu memberikan kenyamanan dan kelancaran dalam beraktivitas. 🙏🌐\n\nSalam hangat,\n*Admin wiji.net*','invoices/Gigih_Ragasa_Jiwo_PAY26102100040.pdf',NULL,'{\"code\":\"SUCCESS\",\"message\":\"Document sent to 6285799141931@s.whatsapp.net (server timestamp: 2026-10-21 19:25:37 +0000 UTC)\",\"results\":{\"message_id\":\"3EB075AE43E7D0DA25F9A8\",\"status\":\"Document sent to 6285799141931@s.whatsapp.net (server timestamp: 2026-10-21 19:25:37 +0000 UTC)\"}}','2026-10-21 19:25:37','2026-10-21 19:25:29','2026-10-21 19:25:37'),(22,163,'6285799141931','sent',1,'Assalamu’alaikum / Selamat malam Bapak/Ibu 😊\n\nKami dari *wiji.net* mengirimkan invoice pembayaran WiFi untuk periode *Oktober 2026*.\n\n📄 *DETAIL PEMBAYARAN WIFI*\n👤 Nama Pelanggan: Gigih Ragasa Jiwo\n🆔 ID Pelanggan: 73\n📅 Periode Pembayaran: Oktober 2026\n💰 Total Pembayaran: Rp100.000\n\n📎 Invoice pembayaran kami lampirkan dalam bentuk file PDF pada pesan ini.\n\nTerima kasih atas kepercayaan Bapak/Ibu dalam menggunakan layanan internet *wiji.net*. Semoga layanan kami selalu memberikan kenyamanan dan kelancaran dalam beraktivitas. 🙏🌐\n\nSalam hangat,\n*Admin wiji.net*','invoices/163-20261021192603.pdf',NULL,'{\"code\":\"SUCCESS\",\"message\":\"Document sent to 6285799141931@s.whatsapp.net (server timestamp: 2026-10-21 19:26:04 +0000 UTC)\",\"results\":{\"message_id\":\"3EB021379C22FA54C2D4CC\",\"status\":\"Document sent to 6285799141931@s.whatsapp.net (server timestamp: 2026-10-21 19:26:04 +0000 UTC)\"}}','2026-10-21 19:26:04','2026-10-21 19:26:03','2026-10-21 19:26:04'),(23,173,'6285799141931','sent',1,'Assalamu’alaikum / Selamat malam Bapak/Ibu 😊\n\nKami dari *wiji.net* mengirimkan invoice pembayaran WiFi untuk periode *Desember 2025*.\n\n📄 *DETAIL PEMBAYARAN WIFI*\n👤 Nama Pelanggan: Gigih Ragasa Jiwo\n🆔 ID Pelanggan: 73\n📅 Periode Pembayaran: Desember 2025\n💰 Total Pembayaran: Rp100.000\n\n📎 Invoice pembayaran kami lampirkan dalam bentuk file PDF pada pesan ini.\n\nTerima kasih atas kepercayaan Bapak/Ibu dalam menggunakan layanan internet *wiji.net*. Semoga layanan kami selalu memberikan kenyamanan dan kelancaran dalam beraktivitas. 🙏🌐\n\nSalam hangat,\n*Admin wiji.net*','invoices/Gigih_Ragasa_Jiwo_PAY26102100041.pdf',NULL,'{\"code\":\"SUCCESS\",\"message\":\"Document sent to 6285799141931@s.whatsapp.net (server timestamp: 2026-10-21 19:27:18 +0000 UTC)\",\"results\":{\"message_id\":\"3EB09D572B66AC90D6E8E4\",\"status\":\"Document sent to 6285799141931@s.whatsapp.net (server timestamp: 2026-10-21 19:27:18 +0000 UTC)\"}}','2026-10-21 19:27:18','2026-10-21 19:27:15','2026-10-21 19:27:18'),(24,172,'6285799141931','sent',1,'Assalamu’alaikum / Selamat malam Bapak/Ibu 😊\n\nKami dari *wiji.net* mengirimkan invoice pembayaran WiFi untuk periode *Januari 2026*.\n\n📄 *DETAIL PEMBAYARAN WIFI*\n👤 Nama Pelanggan: Gigih Ragasa Jiwo\n🆔 ID Pelanggan: 73\n📅 Periode Pembayaran: Januari 2026\n💰 Total Pembayaran: Rp100.000\n\n📎 Invoice pembayaran kami lampirkan dalam bentuk file PDF pada pesan ini.\n\nTerima kasih atas kepercayaan Bapak/Ibu dalam menggunakan layanan internet *wiji.net*. Semoga layanan kami selalu memberikan kenyamanan dan kelancaran dalam beraktivitas. 🙏🌐\n\nSalam hangat,\n*Admin wiji.net*','invoices/Gigih_Ragasa_Jiwo_PAY26102100042.pdf',NULL,'{\"code\":\"SUCCESS\",\"message\":\"Document sent to 6285799141931@s.whatsapp.net (server timestamp: 2026-10-21 19:30:47 +0000 UTC)\",\"results\":{\"message_id\":\"3EB0DA8634A115E891ACE9\",\"status\":\"Document sent to 6285799141931@s.whatsapp.net (server timestamp: 2026-10-21 19:30:47 +0000 UTC)\"}}','2026-10-21 19:30:48','2026-10-21 19:30:04','2026-10-21 19:30:48'),(26,2,'6283135322094','sent',2,'Assalamu’alaikum / Selamat malam Bapak/Ibu 😊\n\nKami dari *wiji.net* mengirimkan invoice pembayaran WiFi untuk periode *September 2026*.\n\n📄 *DETAIL PEMBAYARAN WIFI*\n👤 Nama Pelanggan: amri\n🆔 ID Pelanggan: 64\n📅 Periode Pembayaran: September 2026\n💰 Total Pembayaran: Rp100.000\n\n📎 Invoice pembayaran kami lampirkan dalam bentuk file PDF pada pesan ini.\n\nTerima kasih atas kepercayaan Bapak/Ibu dalam menggunakan layanan internet *wiji.net*. Semoga layanan kami selalu memberikan kenyamanan dan kelancaran dalam beraktivitas. 🙏🌐\n\nSalam hangat,\n*Admin wiji.net*','invoices/amri_PAY26102100001.pdf',NULL,'{\"code\":\"SUCCESS\",\"message\":\"Document sent to 6283135322094@s.whatsapp.net (server timestamp: 2026-10-21 19:38:36 +0000 UTC)\",\"results\":{\"message_id\":\"3EB0EE97D63F1C11510B7A\",\"status\":\"Document sent to 6283135322094@s.whatsapp.net (server timestamp: 2026-10-21 19:38:36 +0000 UTC)\"}}','2026-10-21 19:38:36','2026-10-21 19:38:04','2026-10-21 19:38:36'),(27,199,'6283135322094','pending',2,'Assalamu’alaikum / Selamat pagi Bapak/Ibu 😊\n\nKami dari *wiji.net* mengirimkan invoice pembayaran WiFi untuk periode *Desember 2025*.\n\n📄 *DETAIL PEMBAYARAN WIFI*\n👤 Nama Pelanggan: jamingan\n🆔 ID Pelanggan: 53\n📅 Periode Pembayaran: Desember 2025\n💰 Total Pembayaran: Rp100.000\n\n📎 Invoice pembayaran kami lampirkan dalam bentuk file PDF pada pesan ini.\n\nTerima kasih atas kepercayaan Bapak/Ibu dalam menggunakan layanan internet *wiji.net*. Semoga layanan kami selalu memberikan kenyamanan dan kelancaran dalam beraktivitas. 🙏🌐\n\nSalam hangat,\n*Admin wiji.net*','invoices/jamingan_PAY26102200001.pdf',NULL,'{\"code\":\"INTERNAL_SERVER_ERROR\",\"message\":\"failed to execute request: Post \\\"https://media-cgk2-1.cdn.whatsapp.net/mms/document/2VzemZWvgoPMoKu7nRKp8LFBaVZI4_A82Xjb96caZPc=?auth=Ac2NFJPTA1JO2FyOkgSUExEN11Q6AmY8i1eqHemIQLQpUEMeLju21V2b\\u0026token=2VzemZWvgoPMoKu7nRKp8LFBaVZI4_A82Xjb96caZPc%3D\\\": context deadline exceeded\"}',NULL,'2026-10-22 01:17:57','2026-10-22 01:19:23');
/*!40000 ALTER TABLE `payment_whatsapp_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `invoice_number` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_id` bigint unsigned DEFAULT NULL,
  `customer_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `billing_month` varchar(7) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` decimal(15,2) NOT NULL,
  `payment_method` enum('qrcode','manual') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'manual',
  `reference` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('pending','confirmed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `payments_status_index` (`status`),
  KEY `payments_payment_method_index` (`payment_method`),
  KEY `payments_customer_id_index` (`customer_id`),
  KEY `payments_billing_month_index` (`billing_month`),
  CONSTRAINT `payments_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=201 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payments`
--

LOCK TABLES `payments` WRITE;
/*!40000 ALTER TABLE `payments` DISABLE KEYS */;
INSERT INTO `payments` VALUES (2,'PAY26102100001',64,'amri','2026-09',100000.00,'manual',NULL,'confirmed',NULL,'2026-10-21 17:58:02','2026-10-21 17:58:56',NULL),(3,'PAY26102100003',64,'amri','2026-08',100000.00,'manual',NULL,'confirmed',NULL,'2026-10-21 17:58:02','2026-10-21 17:58:58',NULL),(4,'PAY26102100002',64,'amri','2026-07',100000.00,'manual',NULL,'confirmed',NULL,'2026-10-21 17:58:02','2026-10-21 17:58:57',NULL),(5,'PAY26102100004',64,'amri','2026-06',100000.00,'manual',NULL,'confirmed',NULL,'2026-10-21 17:58:02','2026-10-21 17:58:58',NULL),(6,'PAY26102100005',64,'amri','2026-05',100000.00,'manual',NULL,'confirmed',NULL,'2026-10-21 17:58:02','2026-10-21 17:58:58',NULL),(7,'PAY26102100006',64,'amri','2026-04',100000.00,'manual',NULL,'confirmed',NULL,'2026-10-21 17:58:02','2026-10-21 17:58:59',NULL),(8,'PAY26102100007',64,'amri','2026-03',100000.00,'manual',NULL,'confirmed',NULL,'2026-10-21 17:58:02','2026-10-21 17:58:59',NULL),(9,'PAY26102100008',64,'amri','2026-02',100000.00,'manual',NULL,'confirmed',NULL,'2026-10-21 17:58:02','2026-10-21 17:59:00',NULL),(10,'PAY26102100009',64,'amri','2026-01',100000.00,'manual',NULL,'confirmed',NULL,'2026-10-21 17:58:02','2026-10-21 17:59:01',NULL),(11,'PAY26102100010',64,'amri','2025-12',100000.00,'manual',NULL,'confirmed',NULL,'2026-10-21 17:58:02','2026-10-21 17:59:02',NULL),(12,'PAY26102100011',64,'amri','2025-11',100000.00,'manual',NULL,'confirmed',NULL,'2026-10-21 17:58:02','2026-10-21 17:59:03',NULL),(77,'PAY26102100012',50,'angga','2026-11',100000.00,'manual',NULL,'confirmed',NULL,'2026-10-21 18:06:16','2026-10-21 18:06:20',NULL),(78,'PAY26102100013',50,'angga','2026-10',100000.00,'manual',NULL,'confirmed',NULL,'2026-10-21 18:06:16','2026-10-21 18:06:23',NULL),(79,'PAY26102100024',50,'angga','2026-09',100000.00,'manual',NULL,'confirmed',NULL,'2026-10-21 18:06:16','2026-10-21 18:06:45',NULL),(80,'PAY26102100023',50,'angga','2026-08',100000.00,'manual',NULL,'confirmed',NULL,'2026-10-21 18:06:16','2026-10-21 18:06:44',NULL),(81,'PAY26102100022',50,'angga','2026-07',100000.00,'manual',NULL,'confirmed',NULL,'2026-10-21 18:06:16','2026-10-21 18:06:44',NULL),(82,'PAY26102100021',50,'angga','2026-06',100000.00,'manual',NULL,'confirmed',NULL,'2026-10-21 18:06:16','2026-10-21 18:06:43',NULL),(83,'PAY26102100020',50,'angga','2026-05',100000.00,'manual',NULL,'confirmed',NULL,'2026-10-21 18:06:16','2026-10-21 18:06:43',NULL),(84,'PAY26102100019',50,'angga','2026-04',100000.00,'manual',NULL,'confirmed',NULL,'2026-10-21 18:06:16','2026-10-21 18:06:42',NULL),(85,'PAY26102100018',50,'angga','2026-03',100000.00,'manual',NULL,'confirmed',NULL,'2026-10-21 18:06:16','2026-10-21 18:06:41',NULL),(86,'PAY26102100017',50,'angga','2026-02',100000.00,'manual',NULL,'confirmed',NULL,'2026-10-21 18:06:16','2026-10-21 18:06:40',NULL),(87,'PAY26102100016',50,'angga','2026-01',100000.00,'manual',NULL,'confirmed',NULL,'2026-10-21 18:06:16','2026-10-21 18:06:39',NULL),(88,'PAY26102100015',50,'angga','2025-12',100000.00,'manual',NULL,'confirmed',NULL,'2026-10-21 18:06:16','2026-10-21 18:06:39',NULL),(89,'PAY26102100014',50,'angga','2025-11',100000.00,'manual',NULL,'confirmed',NULL,'2026-10-21 18:06:16','2026-10-21 18:06:38',NULL),(90,NULL,35,'andri','2026-11',100000.00,'manual',NULL,'confirmed',NULL,'2026-10-21 18:08:02','2026-10-21 18:20:24',NULL),(91,NULL,35,'andri','2026-10',100000.00,'manual',NULL,'confirmed',NULL,'2026-10-21 18:08:02','2026-10-21 18:20:24',NULL),(92,NULL,35,'andri','2026-09',100000.00,'manual',NULL,'confirmed',NULL,'2026-10-21 18:08:02','2026-10-21 18:20:24',NULL),(93,NULL,35,'andri','2026-08',100000.00,'manual',NULL,'confirmed',NULL,'2026-10-21 18:08:02','2026-10-21 18:20:24',NULL),(94,NULL,35,'andri','2026-07',100000.00,'manual',NULL,'confirmed',NULL,'2026-10-21 18:08:02','2026-10-21 18:20:24',NULL),(95,NULL,35,'andri','2026-06',100000.00,'manual',NULL,'confirmed',NULL,'2026-10-21 18:08:02','2026-10-21 18:20:24',NULL),(96,NULL,35,'andri','2026-05',100000.00,'manual',NULL,'confirmed',NULL,'2026-10-21 18:08:02','2026-10-21 18:20:24',NULL),(97,NULL,35,'andri','2026-04',100000.00,'manual',NULL,'confirmed',NULL,'2026-10-21 18:08:02','2026-10-21 18:20:24',NULL),(98,NULL,35,'andri','2026-03',100000.00,'manual',NULL,'confirmed',NULL,'2026-10-21 18:08:02','2026-10-21 18:20:24',NULL),(99,NULL,35,'andri','2026-02',100000.00,'manual',NULL,'confirmed',NULL,'2026-10-21 18:08:02','2026-10-21 18:20:24',NULL),(100,NULL,35,'andri','2026-01',100000.00,'manual',NULL,'confirmed',NULL,'2026-10-21 18:08:02','2026-10-21 18:20:24',NULL),(101,NULL,35,'andri','2025-12',100000.00,'manual',NULL,'confirmed',NULL,'2026-10-21 18:08:02','2026-10-21 18:20:24',NULL),(102,NULL,35,'andri','2025-11',100000.00,'manual',NULL,'confirmed',NULL,'2026-10-21 18:08:02','2026-10-21 18:20:24',NULL),(103,'PAY26102100025',64,'amri','2026-11',100000.00,'manual',NULL,'confirmed',NULL,'2026-10-21 18:10:22','2026-10-21 18:34:15',NULL),(122,'PAY26102100026',2,'imam prasetyo','2026-11',100000.00,'manual',NULL,'confirmed',NULL,'2026-10-21 18:40:04','2026-10-21 18:40:06',NULL),(123,'PAY26102100027',2,'imam prasetyo','2026-10',100000.00,'manual',NULL,'confirmed',NULL,'2026-10-21 18:40:04','2026-10-21 18:43:44',NULL),(124,NULL,2,'imam prasetyo','2026-09',100000.00,'manual',NULL,'pending',NULL,'2026-10-21 18:40:04','2026-10-21 18:40:04',NULL),(125,NULL,2,'imam prasetyo','2026-08',100000.00,'manual',NULL,'pending',NULL,'2026-10-21 18:40:04','2026-10-21 18:40:04',NULL),(126,NULL,2,'imam prasetyo','2026-07',100000.00,'manual',NULL,'pending',NULL,'2026-10-21 18:40:04','2026-10-21 18:40:04',NULL),(127,NULL,2,'imam prasetyo','2026-06',100000.00,'manual',NULL,'pending',NULL,'2026-10-21 18:40:04','2026-10-21 18:40:04',NULL),(128,NULL,2,'imam prasetyo','2026-05',100000.00,'manual',NULL,'pending',NULL,'2026-10-21 18:40:04','2026-10-21 18:40:04',NULL),(129,NULL,2,'imam prasetyo','2026-04',100000.00,'manual',NULL,'pending',NULL,'2026-10-21 18:40:04','2026-10-21 18:40:04',NULL),(130,NULL,2,'imam prasetyo','2026-03',100000.00,'manual',NULL,'pending',NULL,'2026-10-21 18:40:04','2026-10-21 18:40:04',NULL),(131,NULL,2,'imam prasetyo','2026-02',100000.00,'manual',NULL,'pending',NULL,'2026-10-21 18:40:04','2026-10-21 18:40:04',NULL),(132,NULL,2,'imam prasetyo','2026-01',100000.00,'manual',NULL,'pending',NULL,'2026-10-21 18:40:04','2026-10-21 18:40:04',NULL),(133,NULL,2,'imam prasetyo','2025-12',100000.00,'manual',NULL,'pending',NULL,'2026-10-21 18:40:04','2026-10-21 18:40:04',NULL),(134,'PAY26102100032',2,'imam prasetyo','2025-11',100000.00,'manual',NULL,'confirmed',NULL,'2026-10-21 18:40:04','2026-10-21 19:10:29',NULL),(135,'PAY26102100029',29,'giman','2026-11',100000.00,'manual',NULL,'confirmed',NULL,'2026-10-21 18:44:28','2026-10-21 18:49:58',NULL),(136,NULL,29,'giman','2026-10',100000.00,'manual',NULL,'pending',NULL,'2026-10-21 18:44:28','2026-10-21 18:44:28',NULL),(137,NULL,29,'giman','2026-09',100000.00,'manual',NULL,'pending',NULL,'2026-10-21 18:44:28','2026-10-21 18:44:28',NULL),(138,NULL,29,'giman','2026-08',100000.00,'manual',NULL,'pending',NULL,'2026-10-21 18:44:28','2026-10-21 18:44:28',NULL),(139,NULL,29,'giman','2026-07',100000.00,'manual',NULL,'pending',NULL,'2026-10-21 18:44:28','2026-10-21 18:44:28',NULL),(140,NULL,29,'giman','2026-06',100000.00,'manual',NULL,'pending',NULL,'2026-10-21 18:44:28','2026-10-21 18:44:28',NULL),(141,NULL,29,'giman','2026-05',100000.00,'manual',NULL,'pending',NULL,'2026-10-21 18:44:28','2026-10-21 18:44:28',NULL),(142,NULL,29,'giman','2026-04',100000.00,'manual',NULL,'pending',NULL,'2026-10-21 18:44:28','2026-10-21 18:44:28',NULL),(143,NULL,29,'giman','2026-03',100000.00,'manual',NULL,'pending',NULL,'2026-10-21 18:44:28','2026-10-21 18:44:28',NULL),(144,'PAY26102100034',29,'giman','2026-02',100000.00,'manual',NULL,'confirmed',NULL,'2026-10-21 18:44:28','2026-10-21 19:16:46',NULL),(145,'PAY26102100033',29,'giman','2026-01',100000.00,'manual',NULL,'confirmed',NULL,'2026-10-21 18:44:28','2026-10-21 19:16:24',NULL),(146,'PAY26102100030',29,'giman','2025-12',100000.00,'manual',NULL,'confirmed',NULL,'2026-10-21 18:44:28','2026-10-21 19:01:51',NULL),(147,'PAY26102100028',29,'giman','2025-11',100000.00,'manual',NULL,'confirmed',NULL,'2026-10-21 18:44:28','2026-10-21 18:44:38',NULL),(149,NULL,72,'Dedy Brian Nugroho','2026-11',100000.00,'manual',NULL,'pending',NULL,'2026-10-21 19:09:51','2026-10-21 19:09:51',NULL),(150,NULL,72,'Dedy Brian Nugroho','2026-10',100000.00,'manual',NULL,'pending',NULL,'2026-10-21 19:09:51','2026-10-21 19:09:51',NULL),(151,NULL,72,'Dedy Brian Nugroho','2026-09',100000.00,'manual',NULL,'pending',NULL,'2026-10-21 19:09:51','2026-10-21 19:09:51',NULL),(152,NULL,72,'Dedy Brian Nugroho','2026-08',100000.00,'manual',NULL,'pending',NULL,'2026-10-21 19:09:51','2026-10-21 19:09:51',NULL),(153,NULL,72,'Dedy Brian Nugroho','2026-07',100000.00,'manual',NULL,'pending',NULL,'2026-10-21 19:09:51','2026-10-21 19:09:51',NULL),(154,NULL,72,'Dedy Brian Nugroho','2026-06',100000.00,'manual',NULL,'pending',NULL,'2026-10-21 19:09:51','2026-10-21 19:09:51',NULL),(155,NULL,72,'Dedy Brian Nugroho','2026-05',100000.00,'manual',NULL,'pending',NULL,'2026-10-21 19:09:51','2026-10-21 19:09:51',NULL),(156,NULL,72,'Dedy Brian Nugroho','2026-04',100000.00,'manual',NULL,'pending',NULL,'2026-10-21 19:09:51','2026-10-21 19:09:51',NULL),(157,NULL,72,'Dedy Brian Nugroho','2026-03',100000.00,'manual',NULL,'pending',NULL,'2026-10-21 19:09:51','2026-10-21 19:09:51',NULL),(158,NULL,72,'Dedy Brian Nugroho','2026-02',100000.00,'manual',NULL,'pending',NULL,'2026-10-21 19:09:51','2026-10-21 19:09:51',NULL),(159,NULL,72,'Dedy Brian Nugroho','2026-01',100000.00,'manual',NULL,'pending',NULL,'2026-10-21 19:09:51','2026-10-21 19:09:51',NULL),(160,NULL,72,'Dedy Brian Nugroho','2025-12',100000.00,'manual',NULL,'pending',NULL,'2026-10-21 19:09:51','2026-10-21 19:09:51',NULL),(161,'PAY26102100031',72,'Dedy Brian Nugroho','2025-11',100000.00,'manual',NULL,'confirmed',NULL,'2026-10-21 19:09:51','2026-10-21 19:09:55',NULL),(162,NULL,73,'Gigih Ragasa Jiwo','2026-11',100000.00,'manual',NULL,'pending',NULL,'2026-10-21 19:17:27','2026-10-21 19:17:27',NULL),(163,'PAY26102100035',73,'Gigih Ragasa Jiwo','2026-10',100000.00,'manual',NULL,'confirmed',NULL,'2026-10-21 19:17:27','2026-10-21 19:17:32',NULL),(164,NULL,73,'Gigih Ragasa Jiwo','2026-09',100000.00,'manual',NULL,'pending',NULL,'2026-10-21 19:17:27','2026-10-21 19:17:27',NULL),(165,NULL,73,'Gigih Ragasa Jiwo','2026-08',100000.00,'manual',NULL,'pending',NULL,'2026-10-21 19:17:27','2026-10-21 19:17:27',NULL),(166,NULL,73,'Gigih Ragasa Jiwo','2026-07',100000.00,'manual',NULL,'pending',NULL,'2026-10-21 19:17:27','2026-10-21 19:17:27',NULL),(167,NULL,73,'Gigih Ragasa Jiwo','2026-06',100000.00,'manual',NULL,'pending',NULL,'2026-10-21 19:17:27','2026-10-21 19:17:27',NULL),(168,NULL,73,'Gigih Ragasa Jiwo','2026-05',100000.00,'manual',NULL,'pending',NULL,'2026-10-21 19:17:27','2026-10-21 19:17:27',NULL),(169,NULL,73,'Gigih Ragasa Jiwo','2026-04',100000.00,'manual',NULL,'pending',NULL,'2026-10-21 19:17:27','2026-10-21 19:17:27',NULL),(170,NULL,73,'Gigih Ragasa Jiwo','2026-03',100000.00,'manual',NULL,'pending',NULL,'2026-10-21 19:17:27','2026-10-21 19:17:27',NULL),(171,NULL,73,'Gigih Ragasa Jiwo','2026-02',100000.00,'manual',NULL,'pending',NULL,'2026-10-21 19:17:27','2026-10-21 19:17:27',NULL),(172,'PAY26102100042',73,'Gigih Ragasa Jiwo','2026-01',100000.00,'manual',NULL,'confirmed',NULL,'2026-10-21 19:17:27','2026-10-21 19:28:33',NULL),(173,'PAY26102100041',73,'Gigih Ragasa Jiwo','2025-12',100000.00,'manual',NULL,'confirmed',NULL,'2026-10-21 19:17:27','2026-10-21 19:27:14',NULL),(174,'PAY26102100040',73,'Gigih Ragasa Jiwo','2025-11',100000.00,'manual',NULL,'confirmed',NULL,'2026-10-21 19:17:27','2026-10-21 19:25:29',NULL),(175,NULL,17,'suradi','2026-11',100000.00,'manual',NULL,'pending',NULL,'2026-10-21 19:20:12','2026-10-21 19:20:12',NULL),(176,NULL,17,'suradi','2026-10',100000.00,'manual',NULL,'pending',NULL,'2026-10-21 19:20:12','2026-10-21 19:20:12',NULL),(177,NULL,17,'suradi','2026-09',100000.00,'manual',NULL,'pending',NULL,'2026-10-21 19:20:12','2026-10-21 19:20:12',NULL),(178,NULL,17,'suradi','2026-08',100000.00,'manual',NULL,'pending',NULL,'2026-10-21 19:20:12','2026-10-21 19:20:12',NULL),(179,NULL,17,'suradi','2026-07',100000.00,'manual',NULL,'pending',NULL,'2026-10-21 19:20:12','2026-10-21 19:20:12',NULL),(180,NULL,17,'suradi','2026-06',100000.00,'manual',NULL,'pending',NULL,'2026-10-21 19:20:12','2026-10-21 19:20:12',NULL),(181,NULL,17,'suradi','2026-05',100000.00,'manual',NULL,'pending',NULL,'2026-10-21 19:20:12','2026-10-21 19:20:12',NULL),(182,NULL,17,'suradi','2026-04',100000.00,'manual',NULL,'pending',NULL,'2026-10-21 19:20:12','2026-10-21 19:20:12',NULL),(183,NULL,17,'suradi','2026-03',100000.00,'manual',NULL,'pending',NULL,'2026-10-21 19:20:12','2026-10-21 19:20:12',NULL),(184,NULL,17,'suradi','2026-02',100000.00,'manual',NULL,'pending',NULL,'2026-10-21 19:20:12','2026-10-21 19:20:12',NULL),(185,'PAY26102100038',17,'suradi','2026-01',100000.00,'manual',NULL,'confirmed',NULL,'2026-10-21 19:20:12','2026-10-21 19:21:54',NULL),(186,'PAY26102100037',17,'suradi','2025-12',100000.00,'manual',NULL,'confirmed',NULL,'2026-10-21 19:20:12','2026-10-21 19:21:25',NULL),(187,'PAY26102100036',17,'suradi','2025-11',100000.00,'manual',NULL,'confirmed',NULL,'2026-10-21 19:20:12','2026-10-21 19:20:52',NULL),(188,NULL,53,'jamingan','2026-11',100000.00,'manual',NULL,'pending',NULL,'2026-10-21 19:25:07','2026-10-21 19:25:07',NULL),(189,NULL,53,'jamingan','2026-10',100000.00,'manual',NULL,'pending',NULL,'2026-10-21 19:25:07','2026-10-21 19:25:07',NULL),(190,NULL,53,'jamingan','2026-09',100000.00,'manual',NULL,'pending',NULL,'2026-10-21 19:25:07','2026-10-21 19:25:07',NULL),(191,NULL,53,'jamingan','2026-08',100000.00,'manual',NULL,'pending',NULL,'2026-10-21 19:25:07','2026-10-21 19:25:07',NULL),(192,NULL,53,'jamingan','2026-07',100000.00,'manual',NULL,'pending',NULL,'2026-10-21 19:25:07','2026-10-21 19:25:07',NULL),(193,NULL,53,'jamingan','2026-06',100000.00,'manual',NULL,'pending',NULL,'2026-10-21 19:25:07','2026-10-21 19:25:07',NULL),(194,NULL,53,'jamingan','2026-05',100000.00,'manual',NULL,'pending',NULL,'2026-10-21 19:25:07','2026-10-21 19:25:07',NULL),(195,NULL,53,'jamingan','2026-04',100000.00,'manual',NULL,'pending',NULL,'2026-10-21 19:25:07','2026-10-21 19:25:07',NULL),(196,NULL,53,'jamingan','2026-03',100000.00,'manual',NULL,'pending',NULL,'2026-10-21 19:25:07','2026-10-21 19:25:07',NULL),(197,NULL,53,'jamingan','2026-02',100000.00,'manual',NULL,'pending',NULL,'2026-10-21 19:25:07','2026-10-21 19:25:07',NULL),(198,NULL,53,'jamingan','2026-01',100000.00,'manual',NULL,'pending',NULL,'2026-10-21 19:25:07','2026-10-21 19:25:07',NULL),(199,'PAY26102200001',53,'jamingan','2025-12',100000.00,'manual',NULL,'confirmed',NULL,'2026-10-21 19:25:07','2026-10-22 01:17:56',NULL),(200,'PAY26102100039',53,'jamingan','2025-11',100000.00,'manual',NULL,'confirmed',NULL,'2026-10-21 19:25:07','2026-10-21 19:25:11',NULL);
/*!40000 ALTER TABLE `payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('n50owGQ7aMCS6fn66RSxpQEAclpZDdr6D8ubOglO',1,'169.150.207.210','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36','eyJfdG9rZW4iOiJxNUJIZlpJbmRaZVplemxGMm1aS3R5cjFDa1U2QXdqbVVyMVZXeUw4IiwidXJsIjpbXSwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2xvY2FsaG9zdDo4MDAwXC9hZG1pblwvZGFzaGJvYXJkIiwicm91dGUiOiJkYXNoYm9hcmQifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MSwicGFzc3dvcmRfaGFzaF93ZWIiOiJjMGNiMDc3Mjc1YWNiMmE3MTg5NjNiOGFmYWIwYzk0YzZjMjlkM2IzMGE2NmE5N2UzYTkwZDFlOWE0Nzg1OTE1In0=',1792632027),('sPrwFNUSX9V6C4MAXtWpDtHG3ABu9noEPzM2DzmO',1,'169.150.207.210','Mozilla/5.0 (iPhone; CPU iPhone OS 16_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.0 Mobile/15E148 Safari/604.1','eyJfdG9rZW4iOiJmQld2TGRCTEVSOGJ6SFE2aHYxOFJZNDQzdDQwd0ltcUNzWjJEYnJOIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2xvY2FsaG9zdDo4MDAwXC9hZG1pblwvcGF5bWVudHMiLCJyb3V0ZSI6ImFkbWluLnBheW1lbnRzIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfSwidXJsIjpbXSwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjEsInBhc3N3b3JkX2hhc2hfd2ViIjoiYzBjYjA3NzI3NWFjYjJhNzE4OTYzYjhhZmFiMGM5NGM2YzI5ZDNiMzBhNjZhOTdlM2E5MGQxZTlhNDc4NTkxNSJ9',1792611068);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Admin Operasional','admin@dfiber.id',NULL,'$2y$12$.yYbBljJcOc07aJEHMdMsuhnvS2EzFj6ofZcdldiPRWeuC6VzU16u','admin','UMOJq7EDgsMk1yZDeWylXfieRi36zgTp1peS2AGiPwaOAMYM5DncC4piaN7L','2026-10-14 07:02:03','2026-10-14 07:02:03');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'dfiber'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-10-22  1:25:27
