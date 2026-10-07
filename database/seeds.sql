-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: uiu_research_portal
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Dumping data for table `badges`
--

LOCK TABLES `badges` WRITE;
/*!40000 ALTER TABLE `badges` DISABLE KEYS */;
INSERT INTO `badges` (`id`, `name`, `icon`, `color`, `description`, `created_at`) VALUES (1,'Active Researcher','AR','accent','Contributed to 5+ projects','2026-10-07 13:28:10'),(2,'Top Contributor','TC','orange','Ranked top 10 in contributions','2026-10-07 13:28:10'),(3,'Knowledge Sharer','KS','purple','Shared 3+ quality resources','2026-10-07 13:28:10'),(4,'Idea Innovator','II','teal','Posted an idea with 50+ upvotes','2026-10-07 13:28:10'),(5,'Team Player','TP','green','Completed 3 team projects','2026-10-07 13:28:10'),(6,'Research Pioneer','RP','accent','Published a research paper','2026-10-07 13:28:10'),(7,'Data Champion','DC','purple','Shared 5+ datasets','2026-10-07 13:28:10'),(8,'Community Leader','CL','orange','Reach top 3 on leaderboard','2026-10-07 13:28:10');
/*!40000 ALTER TABLE `badges` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `blog_comments`
--

LOCK TABLES `blog_comments` WRITE;
/*!40000 ALTER TABLE `blog_comments` DISABLE KEYS */;
/*!40000 ALTER TABLE `blog_comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `blog_likes`
--

LOCK TABLES `blog_likes` WRITE;
/*!40000 ALTER TABLE `blog_likes` DISABLE KEYS */;
/*!40000 ALTER TABLE `blog_likes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `blog_posts`
--

LOCK TABLES `blog_posts` WRITE;
/*!40000 ALTER TABLE `blog_posts` DISABLE KEYS */;
INSERT INTO `blog_posts` (`id`, `user_id`, `title`, `slug`, `excerpt`, `content`, `category`, `read_time`, `likes_count`, `comments_count`, `published_at`, `created_at`, `updated_at`, `deleted_at`) VALUES (1,7,'How We Built a COVID-19 Pattern Analyzer in 3 Weeks',NULL,'Our team of three tackled an ambitious project: building an end-to-end COVID data analyzer. Here\'s everything we learned, from data collection to deployment...','Our team of three tackled an ambitious project: building an end-to-end COVID data analyzer. Here\'s everything we learned, from data collection to deployment...\n\nFull article content covering architectural decisions, benchmarks, methodology, experimental findings, and takeaways.','Research Summary','8 min',87,14,'2026-10-07 13:28:10','2026-10-07 13:28:10','2026-10-07 13:28:10',NULL),(2,5,'Getting Started with PyTorch for Computer Vision',NULL,'Computer vision is one of the most exciting fields in AI. In this tutorial, I\'ll walk you through building your first image classifier using PyTorch...','Computer vision is one of the most exciting fields in AI. In this tutorial, I\'ll walk you through building your first image classifier using PyTorch...\n\nFull article content covering architectural decisions, benchmarks, methodology, experimental findings, and takeaways.','Tutorial','12 min',134,28,'2026-10-07 13:28:10','2026-10-07 13:28:10','2026-10-07 13:28:10',NULL),(3,8,'Why Every CS Student Should Learn Blockchain (Even if You\'re Not into Crypto)',NULL,'Blockchain is far more than Bitcoin. Understanding the underlying technology opens doors to smart contracts, supply chain, healthcare records, and more...','Blockchain is far more than Bitcoin. Understanding the underlying technology opens doors to smart contracts, supply chain, healthcare records, and more...\n\nFull article content covering architectural decisions, benchmarks, methodology, experimental findings, and takeaways.','General','6 min',52,19,'2026-10-07 13:28:10','2026-10-07 13:28:10','2026-10-07 13:28:10',NULL),(4,1,'A Beginner\'s Guide to Writing a Research Paper',NULL,'Writing your first research paper is daunting. After struggling through my own first paper, I put together this guide to help fellow students navigate the process...','Writing your first research paper is daunting. After struggling through my own first paper, I put together this guide to help fellow students navigate the process...\n\nFull article content covering architectural decisions, benchmarks, methodology, experimental findings, and takeaways.','Academic Writing','10 min',201,43,'2026-10-07 13:28:10','2026-10-07 13:28:10','2026-10-07 13:28:10',NULL),(5,3,'Indoor Navigation with BLE Beacons: Lessons Learned',NULL,'Building an indoor navigation system sounds straightforward until you encounter beacon interference, multipath effects, and battery constraints...','Building an indoor navigation system sounds straightforward until you encounter beacon interference, multipath effects, and battery constraints...\n\nFull article content covering architectural decisions, benchmarks, methodology, experimental findings, and takeaways.','Research Summary','9 min',76,21,'2026-10-07 13:28:10','2026-10-07 13:28:10','2026-10-07 13:28:10',NULL);
/*!40000 ALTER TABLE `blog_posts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `blog_tags`
--

LOCK TABLES `blog_tags` WRITE;
/*!40000 ALTER TABLE `blog_tags` DISABLE KEYS */;
INSERT INTO `blog_tags` (`id`, `post_id`, `tag`, `created_at`) VALUES (1,1,'COVID','2026-10-07 13:28:10'),(2,1,'Data Science','2026-10-07 13:28:10'),(3,1,'Python','2026-10-07 13:28:10'),(4,2,'PyTorch','2026-10-07 13:28:10'),(5,2,'Computer Vision','2026-10-07 13:28:10'),(6,2,'Tutorial','2026-10-07 13:28:10'),(7,3,'Blockchain','2026-10-07 13:28:10'),(8,3,'Web3','2026-10-07 13:28:10'),(9,3,'Career','2026-10-07 13:28:10'),(10,4,'Research','2026-10-07 13:28:10'),(11,4,'Academic Writing','2026-10-07 13:28:10'),(12,4,'IEEE','2026-10-07 13:28:10'),(13,5,'IoT','2026-10-07 13:28:10'),(14,5,'BLE','2026-10-07 13:28:10'),(15,5,'Mobile','2026-10-07 13:28:10');
/*!40000 ALTER TABLE `blog_tags` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `contributions`
--

LOCK TABLES `contributions` WRITE;
/*!40000 ALTER TABLE `contributions` DISABLE KEYS */;
INSERT INTO `contributions` (`id`, `project_id`, `user_id`, `edits`, `uploads`, `tasks`, `comments`, `score`, `updated_at`) VALUES (1,1,1,42,8,12,28,90,'2026-10-07 13:28:10'),(2,1,5,38,5,15,19,87,'2026-10-07 13:28:10'),(3,1,7,24,12,9,14,73,'2026-10-07 13:28:10');
/*!40000 ALTER TABLE `contributions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `conversation_participants`
--

LOCK TABLES `conversation_participants` WRITE;
/*!40000 ALTER TABLE `conversation_participants` DISABLE KEYS */;
INSERT INTO `conversation_participants` (`id`, `conversation_id`, `user_id`, `unread_count`, `joined_at`) VALUES (1,1,1,0,'2026-10-07 13:28:10'),(2,1,5,0,'2026-10-07 13:28:10'),(3,1,7,0,'2026-10-07 13:28:10'),(4,2,1,0,'2026-10-07 13:28:10'),(5,2,5,0,'2026-10-07 13:28:10'),(6,3,1,0,'2026-10-07 13:28:10'),(7,3,2,0,'2026-10-07 13:28:10'),(8,4,3,0,'2026-10-07 13:28:10'),(9,4,2,0,'2026-10-07 13:28:10'),(10,4,8,0,'2026-10-07 13:28:10'),(11,4,1,0,'2026-10-07 13:28:10'),(12,5,1,0,'2026-10-07 13:28:10'),(13,5,8,0,'2026-10-07 13:28:10');
/*!40000 ALTER TABLE `conversation_participants` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `conversations`
--

LOCK TABLES `conversations` WRITE;
/*!40000 ALTER TABLE `conversations` DISABLE KEYS */;
INSERT INTO `conversations` (`id`, `type`, `name`, `last_message`, `last_message_at`, `created_at`, `updated_at`, `deleted_at`) VALUES (1,'group','AI Healthcare Team','Rafsan: Let\'s sync at 7PM today','2026-10-07 13:28:10','2026-10-07 13:28:10','2026-10-07 13:28:10',NULL),(2,'dm',NULL,'Can you push the model weights?','2026-10-07 13:28:10','2026-10-07 13:28:10','2026-10-07 13:28:10',NULL),(3,'dm',NULL,'I saw your idea on the marketplace!','2026-10-07 13:28:10','2026-10-07 13:28:10','2026-10-07 13:28:10',NULL),(4,'group','Smart Campus Project','Tanvir: PR is ready for review','2026-10-07 13:28:10','2026-10-07 13:28:10','2026-10-07 13:28:10',NULL),(5,'dm',NULL,'Blockchain deployment went live ','2026-10-07 13:28:10','2026-10-07 13:28:10','2026-10-07 13:28:10',NULL);
/*!40000 ALTER TABLE `conversations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `event_registrations`
--

LOCK TABLES `event_registrations` WRITE;
/*!40000 ALTER TABLE `event_registrations` DISABLE KEYS */;
/*!40000 ALTER TABLE `event_registrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `event_tags`
--

LOCK TABLES `event_tags` WRITE;
/*!40000 ALTER TABLE `event_tags` DISABLE KEYS */;
INSERT INTO `event_tags` (`id`, `event_id`, `tag`, `created_at`) VALUES (1,1,'AI','2026-10-07 13:28:10'),(2,1,'Web','2026-10-07 13:28:10'),(3,1,'IoT','2026-10-07 13:28:10'),(4,2,'Competitive Programming','2026-10-07 13:28:10'),(5,3,'Research','2026-10-07 13:28:10'),(6,3,'Academic Writing','2026-10-07 13:28:10'),(7,3,'IEEE','2026-10-07 13:28:10'),(8,4,'AI','2026-10-07 13:28:10'),(9,4,'ML','2026-10-07 13:28:10'),(10,4,'Deep Learning','2026-10-07 13:28:10'),(11,5,'Startup','2026-10-07 13:28:10'),(12,5,'Innovation','2026-10-07 13:28:10'),(13,5,'Fintech','2026-10-07 13:28:10');
/*!40000 ALTER TABLE `event_tags` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `events`
--

LOCK TABLES `events` WRITE;
/*!40000 ALTER TABLE `events` DISABLE KEYS */;
INSERT INTO `events` (`id`, `title`, `description`, `type`, `start_date`, `end_date`, `location`, `prize`, `price`, `spots`, `participants_count`, `status`, `organizer`, `created_by`, `created_at`, `updated_at`, `deleted_at`) VALUES (1,'UIU National Hackathon 2025','Academic and tech event organized by UIU CSE Department','Hackathon','2025-06-14','2025-06-15','UIU Campus','৳2,00,000','Free',500,320,'open','UIU CSE Department',NULL,'2026-10-07 13:28:10','2026-10-07 13:28:10',NULL),(2,'ICPC Asia Dhaka Regional 2025','Academic and tech event organized by ACM Bangladesh','Competition','2025-07-20',NULL,'BUET, Dhaka','ACM-ICPC Trophy','Free',300,180,'open','ACM Bangladesh',NULL,'2026-10-07 13:28:10','2026-10-07 13:28:10',NULL),(3,'Research Methodology Workshop','Academic and tech event organized by UIU Research Cell','Workshop','2025-05-28',NULL,'UIU Auditorium',NULL,'Free',100,65,'open','UIU Research Cell',NULL,'2026-10-07 13:28:10','2026-10-07 13:28:10',NULL),(4,'International Conference on AI (ICAI 2025)','Academic and tech event organized by IEEE Bangladesh','Conference','2025-08-05','2025-08-07','Virtual + Dhaka',NULL,'Free',1200,890,'open','IEEE Bangladesh',NULL,'2026-10-07 13:28:10','2026-10-07 13:28:10',NULL),(5,'Startup Pitch Competition — TechNext BD','Academic and tech event organized by ICT Division, Bangladesh','Competition','2025-06-01',NULL,'ICT Division, Dhaka','৳5,00,000 + Investment','Free',60,48,'closing-soon','ICT Division, Bangladesh',NULL,'2026-10-07 13:28:10','2026-10-07 13:28:10',NULL);
/*!40000 ALTER TABLE `events` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `idea_comments`
--

LOCK TABLES `idea_comments` WRITE;
/*!40000 ALTER TABLE `idea_comments` DISABLE KEYS */;
/*!40000 ALTER TABLE `idea_comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `idea_skills`
--

LOCK TABLES `idea_skills` WRITE;
/*!40000 ALTER TABLE `idea_skills` DISABLE KEYS */;
INSERT INTO `idea_skills` (`id`, `idea_id`, `skill`, `created_at`) VALUES (1,1,'Computer Vision','2026-10-07 13:28:10'),(2,1,'Reinforcement Learning','2026-10-07 13:28:10'),(3,1,'Python','2026-10-07 13:28:10'),(4,2,'Computer Vision','2026-10-07 13:28:10'),(5,2,'MediaPipe','2026-10-07 13:28:10'),(6,2,'Mobile Dev','2026-10-07 13:28:10'),(7,3,'IoT','2026-10-07 13:28:10'),(8,3,'Data Analysis','2026-10-07 13:28:10'),(9,3,'App Dev','2026-10-07 13:28:10'),(10,4,'Federated Learning','2026-10-07 13:28:10'),(11,4,'Privacy','2026-10-07 13:28:10'),(12,4,'Python','2026-10-07 13:28:10'),(13,5,'NLP','2026-10-07 13:28:10'),(14,5,'Chatbot','2026-10-07 13:28:10'),(15,5,'Psychology','2026-10-07 13:28:10');
/*!40000 ALTER TABLE `idea_skills` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `idea_upvotes`
--

LOCK TABLES `idea_upvotes` WRITE;
/*!40000 ALTER TABLE `idea_upvotes` DISABLE KEYS */;
/*!40000 ALTER TABLE `idea_upvotes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `ideas`
--

LOCK TABLES `ideas` WRITE;
/*!40000 ALTER TABLE `ideas` DISABLE KEYS */;
INSERT INTO `ideas` (`id`, `user_id`, `title`, `description`, `domain`, `status`, `upvotes_count`, `comments_count`, `created_at`, `updated_at`, `deleted_at`) VALUES (1,5,'AI-Based Traffic Signal Optimization for Dhaka','Using live camera feeds and RL to dynamically optimize traffic signal timing and reduce congestion in Dhaka city.','AI / Smart City','Open',134,28,'2026-10-07 13:28:10','2026-10-07 13:28:10',NULL),(2,7,'Bangla Sign Language Recognition App','A smartphone app that translates Bangla sign language gestures into text/speech in real time.','NLP / Accessibility','Open',98,19,'2026-10-07 13:28:10','2026-10-07 13:28:10',NULL),(3,4,'University Food Waste Reduction System','Sensor-based food waste tracking in UIU canteen with predictive ordering suggestions.','IoT / Sustainability','In Progress',61,11,'2026-10-07 13:28:10','2026-10-07 13:28:10',NULL),(4,1,'Federated Learning for Hospital Networks','Train ML models across multiple hospitals without sharing patient data — preserving privacy while improving accuracy.','AI / Healthcare','Open',87,22,'2026-10-07 13:28:10','2026-10-07 13:28:10',NULL),(5,2,'Student Mental Health Chatbot for UIU','An empathetic AI chatbot specifically designed for UIU students to provide mental health support and resource guidance.','NLP / Mental Health','Open',145,35,'2026-10-07 13:28:10','2026-10-07 13:28:10',NULL);
/*!40000 ALTER TABLE `ideas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `messages`
--

LOCK TABLES `messages` WRITE;
/*!40000 ALTER TABLE `messages` DISABLE KEYS */;
INSERT INTO `messages` (`id`, `conversation_id`, `sender_id`, `message`, `is_read`, `created_at`, `updated_at`, `deleted_at`) VALUES (1,1,1,'Rafsan: Let\'s sync at 7PM today',1,'2026-10-07 13:28:10','2026-10-07 13:28:10',NULL),(2,2,1,'Can you push the model weights?',1,'2026-10-07 13:28:10','2026-10-07 13:28:10',NULL),(3,3,1,'I saw your idea on the marketplace!',1,'2026-10-07 13:28:10','2026-10-07 13:28:10',NULL),(4,4,3,'Tanvir: PR is ready for review',1,'2026-10-07 13:28:10','2026-10-07 13:28:10',NULL),(5,5,1,'Blockchain deployment went live ',1,'2026-10-07 13:28:10','2026-10-07 13:28:10',NULL);
/*!40000 ALTER TABLE `messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` (`id`, `migration`, `batch`, `migrated_at`) VALUES (1,'001_create_users_table',1,'2026-10-07 13:28:09'),(2,'002_create_projects_table',1,'2026-10-07 13:28:09'),(3,'003_create_tasks_table',1,'2026-10-07 13:28:09'),(4,'004_create_messages_table',1,'2026-10-07 13:28:09'),(5,'005_create_resources_table',1,'2026-10-07 13:28:09'),(6,'006_create_blog_posts_table',1,'2026-10-07 13:28:09'),(7,'007_create_ideas_table',1,'2026-10-07 13:28:09'),(8,'008_create_notifications_table',1,'2026-10-07 13:28:09'),(9,'009_create_events_table',1,'2026-10-07 13:28:09'),(10,'010_create_badges_table',1,'2026-10-07 13:28:09'),(11,'011_create_project_members_table',1,'2026-10-07 13:28:09');
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
INSERT INTO `notifications` (`id`, `user_id`, `type`, `icon`, `text`, `data`, `is_read`, `read_at`, `created_at`, `deleted_at`) VALUES (1,1,'collab','collab','Nusrat Jahan sent you a collaboration request',NULL,0,NULL,'2026-10-07 13:28:10',NULL),(2,1,'project','project','New task assigned: Train CNN baseline model',NULL,0,NULL,'2026-10-07 13:28:10',NULL),(3,1,'message','message','Rakibul mentioned you in AI Healthcare Team',NULL,0,NULL,'2026-10-07 13:28:10',NULL),(4,1,'idea','idea','Your idea received 10 new upvotes',NULL,1,NULL,'2026-10-07 13:28:10',NULL),(5,1,'blog','blog','Sadia liked your blog post',NULL,1,NULL,'2026-10-07 13:28:10',NULL),(6,1,'resource','resource','Your dataset was downloaded 50 times today',NULL,1,NULL,'2026-10-07 13:28:10',NULL),(7,1,'event','event','UIU National Hackathon registration closes in 3 days',NULL,1,NULL,'2026-10-07 13:28:10',NULL),(8,1,'system','system','You earned the Active Researcher badge',NULL,1,NULL,'2026-10-07 13:28:10',NULL);
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `project_members`
--

LOCK TABLES `project_members` WRITE;
/*!40000 ALTER TABLE `project_members` DISABLE KEYS */;
INSERT INTO `project_members` (`id`, `project_id`, `user_id`, `role`, `joined_at`, `created_at`, `updated_at`) VALUES (1,1,1,'lead','2026-10-07 13:28:10','2026-10-07 13:28:10','2026-10-07 13:28:10'),(2,1,7,'contributor','2026-10-07 13:28:10','2026-10-07 13:28:10','2026-10-07 13:28:10'),(3,1,5,'contributor','2026-10-07 13:28:10','2026-10-07 13:28:10','2026-10-07 13:28:10'),(4,2,3,'lead','2026-10-07 13:28:10','2026-10-07 13:28:10','2026-10-07 13:28:10'),(5,2,2,'contributor','2026-10-07 13:28:10','2026-10-07 13:28:10','2026-10-07 13:28:10'),(6,2,8,'contributor','2026-10-07 13:28:10','2026-10-07 13:28:10','2026-10-07 13:28:10'),(7,3,8,'lead','2026-10-07 13:28:10','2026-10-07 13:28:10','2026-10-07 13:28:10'),(8,3,4,'contributor','2026-10-07 13:28:10','2026-10-07 13:28:10','2026-10-07 13:28:10'),(9,3,1,'contributor','2026-10-07 13:28:10','2026-10-07 13:28:10','2026-10-07 13:28:10'),(10,4,1,'lead','2026-10-07 13:28:10','2026-10-07 13:28:10','2026-10-07 13:28:10'),(11,4,5,'contributor','2026-10-07 13:28:10','2026-10-07 13:28:10','2026-10-07 13:28:10'),(12,4,7,'contributor','2026-10-07 13:28:10','2026-10-07 13:28:10','2026-10-07 13:28:10'),(13,5,1,'lead','2026-10-07 14:07:49','2026-10-07 14:07:49','2026-10-07 14:07:49'),(14,6,1,'lead','2026-10-07 14:08:18','2026-10-07 14:08:18','2026-10-07 14:08:18'),(17,7,1,'lead','2026-10-07 14:40:25','2026-10-07 14:40:25','2026-10-07 14:40:25'),(18,8,1,'lead','2026-10-07 14:43:28','2026-10-07 14:43:28','2026-10-07 14:43:28'),(20,9,1,'lead','2026-10-07 15:00:48','2026-10-07 15:00:48','2026-10-07 15:00:48'),(24,10,1,'lead','2026-10-07 15:15:58','2026-10-07 15:15:58','2026-10-07 15:15:58'),(25,11,1,'lead','2026-10-07 15:16:09','2026-10-07 15:16:09','2026-10-07 15:16:09'),(27,12,1,'lead','2026-10-07 15:23:39','2026-10-07 15:23:39','2026-10-07 15:23:39'),(29,13,1,'lead','2026-10-07 15:24:08','2026-10-07 15:24:08','2026-10-07 15:24:08'),(31,14,1,'lead','2026-10-07 15:25:46','2026-10-07 15:25:46','2026-10-07 15:25:46');
/*!40000 ALTER TABLE `project_members` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `project_tags`
--

LOCK TABLES `project_tags` WRITE;
/*!40000 ALTER TABLE `project_tags` DISABLE KEYS */;
INSERT INTO `project_tags` (`id`, `project_id`, `tag`, `created_at`) VALUES (1,1,'Machine Learning','2026-10-07 13:28:10'),(2,1,'Healthcare','2026-10-07 13:28:10'),(3,1,'Python','2026-10-07 13:28:10'),(4,2,'React Native','2026-10-07 13:28:10'),(5,2,'IoT','2026-10-07 13:28:10'),(6,2,'AR','2026-10-07 13:28:10'),(7,3,'Blockchain','2026-10-07 13:28:10'),(8,3,'Solidity','2026-10-07 13:28:10'),(9,3,'Web3','2026-10-07 13:28:10'),(10,4,'NLP','2026-10-07 13:28:10'),(11,4,'BERT','2026-10-07 13:28:10'),(12,4,'Python','2026-10-07 13:28:10'),(13,5,'AI','2026-10-07 14:07:49'),(14,5,'Testing','2026-10-07 14:07:49'),(15,6,'AI','2026-10-07 14:08:18'),(16,6,'Testing','2026-10-07 14:08:18'),(17,7,'AI','2026-10-07 14:40:25'),(18,7,'Testing','2026-10-07 14:40:25'),(19,8,'AI','2026-10-07 14:43:28'),(20,8,'Testing','2026-10-07 14:43:28'),(21,9,'AI','2026-10-07 15:00:48'),(22,9,'Testing','2026-10-07 15:00:48'),(23,10,'AI','2026-10-07 15:15:58'),(24,10,'Testing','2026-10-07 15:15:58'),(25,11,'AI','2026-10-07 15:16:09'),(26,11,'Testing','2026-10-07 15:16:09'),(27,12,'AI','2026-10-07 15:23:40'),(28,12,'Testing','2026-10-07 15:23:40'),(29,13,'AI','2026-10-07 15:24:08'),(30,13,'Testing','2026-10-07 15:24:08'),(31,14,'AI','2026-10-07 15:25:46'),(32,14,'Testing','2026-10-07 15:25:46');
/*!40000 ALTER TABLE `project_tags` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `projects`
--

LOCK TABLES `projects` WRITE;
/*!40000 ALTER TABLE `projects` DISABLE KEYS */;
INSERT INTO `projects` (`id`, `title`, `description`, `domain`, `status`, `progress`, `deadline`, `visibility`, `files_count`, `milestones_count`, `completed_milestones`, `created_by`, `created_at`, `updated_at`, `deleted_at`) VALUES (1,'AI-Based Healthcare Diagnosis','Leveraging deep learning to assist in early disease detection from medical imaging data.','AI / Healthcare','active',65,'2025-06-30','private',18,4,2,1,'2026-10-07 13:28:10','2026-10-07 13:28:10',NULL),(2,'Smart Campus Navigation App','Indoor navigation system for UIU campus using BLE beacons and AR overlays.','Mobile / IoT','active',40,'2025-07-15','public',11,3,1,3,'2026-10-07 13:28:10','2026-10-07 13:28:10',NULL),(3,'Blockchain-Based Credential Verification','Tamper-proof academic credential system using Ethereum smart contracts.','Blockchain','active',30,'2025-08-01','public',7,5,1,8,'2026-10-07 13:28:10','2026-10-07 13:28:10',NULL),(4,'NLP-Powered Research Summarizer','Automated academic paper summarization using transformer models.','NLP / AI','completed',100,'2025-03-01','public',26,4,4,1,'2026-10-07 13:28:10','2026-10-07 13:28:10',NULL),(5,'Automated Integration Test Project 1791360469','Testing project creation from integration test suite',NULL,'planning',0,NULL,'public',0,0,0,1,'2026-10-07 10:07:49','2026-10-07 14:07:49',NULL),(6,'Automated Integration Test Project 1791360498','Testing project creation from integration test suite',NULL,'planning',0,NULL,'public',0,0,0,1,'2026-10-07 10:08:18','2026-10-07 14:08:18',NULL),(7,'Automated Integration Test Project 1791362425','Testing project creation from integration test suite',NULL,'planning',0,NULL,'public',0,0,0,1,'2026-10-07 10:40:25','2026-10-07 14:40:25',NULL),(8,'Automated Integration Test Project 1791362608','Testing project creation from integration test suite',NULL,'planning',0,NULL,'public',0,0,0,1,'2026-10-07 10:43:28','2026-10-07 14:43:28',NULL),(9,'Automated Integration Test Project 1791363648','Testing project creation from integration test suite',NULL,'planning',0,NULL,'public',0,0,0,1,'2026-10-07 11:00:48','2026-10-07 15:00:48',NULL),(10,'Automated Integration Test Project 1791364558','Testing project creation from integration test suite',NULL,'planning',0,NULL,'public',0,0,0,1,'2026-10-07 11:15:58','2026-10-07 15:15:58',NULL),(11,'Automated Integration Test Project 1791364569','Testing project creation from integration test suite',NULL,'planning',0,NULL,'public',0,0,0,1,'2026-10-07 11:16:09','2026-10-07 15:16:09',NULL),(12,'Automated Integration Test Project 1791365019','Testing project creation from integration test suite',NULL,'planning',0,NULL,'public',0,0,0,1,'2026-10-07 11:23:39','2026-10-07 15:23:39',NULL),(13,'Automated Integration Test Project 1791365048','Testing project creation from integration test suite',NULL,'planning',0,NULL,'public',0,0,0,1,'2026-10-07 11:24:08','2026-10-07 15:24:08',NULL),(14,'Automated Integration Test Project 1791365146','Testing project creation from integration test suite',NULL,'planning',0,NULL,'public',0,0,0,1,'2026-10-07 11:25:46','2026-10-07 15:25:46',NULL);
/*!40000 ALTER TABLE `projects` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `reputation_logs`
--

LOCK TABLES `reputation_logs` WRITE;
/*!40000 ALTER TABLE `reputation_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `reputation_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `resource_downloads`
--

LOCK TABLES `resource_downloads` WRITE;
/*!40000 ALTER TABLE `resource_downloads` DISABLE KEYS */;
/*!40000 ALTER TABLE `resource_downloads` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `resource_tags`
--

LOCK TABLES `resource_tags` WRITE;
/*!40000 ALTER TABLE `resource_tags` DISABLE KEYS */;
INSERT INTO `resource_tags` (`id`, `resource_id`, `tag`, `created_at`) VALUES (1,1,'CNN','2026-10-07 13:28:10'),(2,1,'Healthcare','2026-10-07 13:28:10'),(3,1,'PyTorch','2026-10-07 13:28:10'),(4,2,'NLP','2026-10-07 13:28:10'),(5,2,'BERT','2026-10-07 13:28:10'),(6,2,'GPT','2026-10-07 13:28:10'),(7,3,'IoT','2026-10-07 13:28:10'),(8,3,'Power Systems','2026-10-07 13:28:10'),(9,4,'Blockchain','2026-10-07 13:28:10'),(10,4,'Web3','2026-10-07 13:28:10'),(11,4,'DeFi','2026-10-07 13:28:10'),(12,5,'React','2026-10-07 13:28:10'),(13,5,'JavaScript','2026-10-07 13:28:10'),(14,5,'Frontend','2026-10-07 13:28:10'),(15,6,'Statistics','2026-10-07 13:28:10'),(16,6,'Research Methods','2026-10-07 13:28:10'),(17,6,'SPSS','2026-10-07 13:28:10'),(18,7,'IoT','2026-10-07 13:28:10'),(19,7,'Environment','2026-10-07 13:28:10'),(20,7,'CSV','2026-10-07 13:28:10'),(21,8,'Python','2026-10-07 13:28:10'),(22,8,'Medical Imaging','2026-10-07 13:28:10'),(23,8,'DICOM','2026-10-07 13:28:10'),(24,9,'NLP','2026-10-07 13:28:10'),(25,9,'Bengali','2026-10-07 13:28:10'),(26,9,'Text','2026-10-07 13:28:10'),(27,10,'MATLAB','2026-10-07 13:28:10'),(28,10,'Power Systems','2026-10-07 13:28:10'),(29,10,'Simulation','2026-10-07 13:28:10');
/*!40000 ALTER TABLE `resource_tags` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `resources`
--

LOCK TABLES `resources` WRITE;
/*!40000 ALTER TABLE `resources` DISABLE KEYS */;
INSERT INTO `resources` (`id`, `title`, `description`, `type`, `category`, `user_id`, `author_name`, `file_path`, `file_size`, `downloads_count`, `rating`, `license`, `version`, `stars_count`, `created_at`, `updated_at`, `deleted_at`) VALUES (1,'Deep Learning for Medical Image Analysis','Resource shared by academic collaborator Rafsan Ahmed','Paper','AI/ML',1,'Rafsan Ahmed',NULL,'2.4 MB',142,4.80,'MIT','v1.0',0,'2026-10-07 13:28:10','2026-10-07 13:28:10',NULL),(2,'Introduction to Transformer Models','Resource shared by academic collaborator Nusrat Jahan','Study Material','NLP',2,'Nusrat Jahan',NULL,'1.8 MB',318,4.90,'MIT','v1.0',0,'2026-10-07 13:28:10','2026-10-07 13:28:10',NULL),(3,'Smart Grid Optimization Techniques','Resource shared by academic collaborator Farhan Kabir','PDF','EEE',6,'Farhan Kabir',NULL,'3.1 MB',89,4.50,'MIT','v1.0',0,'2026-10-07 13:28:10','2026-10-07 13:28:10',NULL),(4,'Blockchain Fundamentals & Use Cases','Resource shared by academic collaborator Imran Chowdhury','Study Material','CS',8,'Imran Chowdhury',NULL,'4.2 MB',205,4.70,'MIT','v1.0',0,'2026-10-07 13:28:10','2026-10-07 13:28:10',NULL),(5,'React 18 Best Practices Guide','Resource shared by academic collaborator Tanvir Hossain','Tutorial','Web Dev',3,'Tanvir Hossain',NULL,'1.2 MB',456,4.90,'MIT','v1.0',0,'2026-10-07 13:28:10','2026-10-07 13:28:10',NULL),(6,'Statistical Methods in Research','Resource shared by academic collaborator Sadia Islam','PDF','Statistics',4,'Sadia Islam',NULL,'5.7 MB',167,4.60,'MIT','v1.0',0,'2026-10-07 13:28:10','2026-10-07 13:28:10',NULL),(7,'UIU Campus Air Quality Dataset 2024','Hourly air quality readings from 12 sensors across UIU campus for 2024.','Dataset','Environment',7,'Maliha Rahman',NULL,'45 MB',78,4.60,'CC BY 4.0','v2.1',23,'2026-10-07 13:28:10','2026-10-07 13:28:10',NULL),(8,'Chest X-Ray Preprocessing Scripts','Python scripts to preprocess and augment chest X-ray images from NIH dataset.','Code','Medical Imaging',1,'Rafsan Ahmed',NULL,'1.2 MB',156,4.80,'MIT','v1.3',41,'2026-10-07 13:28:10','2026-10-07 13:28:10',NULL),(9,'Bangla NLP Text Corpus (UIU Edition)','Curated Bangla text corpus from news, social media, and academic sources.','Dataset','NLP',2,'Nusrat Jahan',NULL,'120 MB',234,4.90,'Research Only','v3.0',67,'2026-10-07 13:28:10','2026-10-07 13:28:10',NULL),(10,'Smart Grid Simulation Results','MATLAB Simulink results for grid optimization experiments.','Experiment','EEE',6,'Farhan Kabir',NULL,'22 MB',45,4.40,'CC BY-SA 4.0','v1.0',15,'2026-10-07 13:28:10','2026-10-07 13:28:10',NULL);
/*!40000 ALTER TABLE `resources` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `tasks`
--

LOCK TABLES `tasks` WRITE;
/*!40000 ALTER TABLE `tasks` DISABLE KEYS */;
INSERT INTO `tasks` (`id`, `project_id`, `title`, `description`, `status`, `priority`, `assignee_id`, `due_date`, `created_by`, `created_at`, `updated_at`, `deleted_at`) VALUES (1,1,'Collect chest X-ray dataset from NIH',NULL,'in_progress','high',7,'2025-05-20',NULL,'2026-10-07 13:28:10','2026-10-07 11:25:55',NULL),(2,1,'Write preprocessing pipeline docs',NULL,'todo','medium',1,'2025-05-25',NULL,'2026-10-07 13:28:10','2026-10-07 13:28:10',NULL),(3,1,'Research augmentation techniques',NULL,'todo','low',5,'2025-05-28',NULL,'2026-10-07 13:28:10','2026-10-07 13:28:10',NULL),(4,1,'Train CNN baseline model',NULL,'in_progress','high',5,'2025-05-18',NULL,'2026-10-07 13:28:10','2026-10-07 13:28:10',NULL),(5,1,'Implement DICOM reader module',NULL,'in_progress','medium',1,'2025-05-22',NULL,'2026-10-07 13:28:10','2026-10-07 13:28:10',NULL),(6,1,'Project setup and repository init',NULL,'done','medium',1,'2025-04-10',NULL,'2026-10-07 13:28:10','2026-10-07 13:28:10',NULL),(7,1,'Literature review — 20 papers',NULL,'done','high',7,'2025-04-20',NULL,'2026-10-07 13:28:10','2026-10-07 13:28:10',NULL),(8,1,'Define model architecture',NULL,'done','high',5,'2025-04-28',NULL,'2026-10-07 13:28:10','2026-10-07 13:28:10',NULL);
/*!40000 ALTER TABLE `tasks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `user_badges`
--

LOCK TABLES `user_badges` WRITE;
/*!40000 ALTER TABLE `user_badges` DISABLE KEYS */;
INSERT INTO `user_badges` (`id`, `user_id`, `badge_id`, `earned_at`) VALUES (1,1,1,'2026-10-07 13:28:10'),(2,1,2,'2026-10-07 13:28:10'),(3,1,3,'2026-10-07 13:28:10'),(4,1,4,'2026-10-07 13:28:10');
/*!40000 ALTER TABLE `user_badges` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `user_interests`
--

LOCK TABLES `user_interests` WRITE;
/*!40000 ALTER TABLE `user_interests` DISABLE KEYS */;
INSERT INTO `user_interests` (`id`, `user_id`, `interest`, `created_at`) VALUES (1,1,'AI in Healthcare','2026-10-07 13:28:09'),(2,1,'Computer Vision','2026-10-07 13:28:09'),(3,1,'NLP','2026-10-07 13:28:09'),(4,1,'Edge Computing','2026-10-07 13:28:09'),(5,2,'Smart Grid','2026-10-07 13:28:09'),(6,2,'Embedded Systems','2026-10-07 13:28:09'),(7,3,'Web Dev','2026-10-07 13:28:10'),(8,3,'System Design','2026-10-07 13:28:10'),(9,4,'Fintech','2026-10-07 13:28:10'),(10,4,'Business Analytics','2026-10-07 13:28:10'),(11,5,'Autonomous Systems','2026-10-07 13:28:10'),(12,5,'Robotics','2026-10-07 13:28:10'),(13,6,'Renewable Energy','2026-10-07 13:28:10'),(14,6,'Heat Transfer','2026-10-07 13:28:10'),(15,7,'Healthcare Data','2026-10-07 13:28:10'),(16,7,'Bioinformatics','2026-10-07 13:28:10'),(17,8,'DeFi','2026-10-07 13:28:10'),(18,8,'Smart Contracts','2026-10-07 13:28:10');
/*!40000 ALTER TABLE `user_interests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `user_looking_for`
--

LOCK TABLES `user_looking_for` WRITE;
/*!40000 ALTER TABLE `user_looking_for` DISABLE KEYS */;
INSERT INTO `user_looking_for` (`id`, `user_id`, `looking_for`, `created_at`) VALUES (1,1,'Frontend developers','2026-10-07 13:28:09'),(2,1,'Research collaborators','2026-10-07 13:28:09'),(3,2,'IoT experts','2026-10-07 13:28:09'),(4,2,'Data analysts','2026-10-07 13:28:09'),(5,3,'Backend devs','2026-10-07 13:28:10'),(6,3,'UI designers','2026-10-07 13:28:10'),(7,4,'CSE students','2026-10-07 13:28:10'),(8,4,'Statisticians','2026-10-07 13:28:10'),(9,5,'ML engineers','2026-10-07 13:28:10'),(10,5,'Robotics enthusiasts','2026-10-07 13:28:10'),(11,6,'CSE collaborators','2026-10-07 13:28:10'),(12,6,'EEE students','2026-10-07 13:28:10'),(13,7,'Medical students','2026-10-07 13:28:10'),(14,7,'ML experts','2026-10-07 13:28:10'),(15,8,'Business analysts','2026-10-07 13:28:10'),(16,8,'Legal experts','2026-10-07 13:28:10');
/*!40000 ALTER TABLE `user_looking_for` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `user_skills`
--

LOCK TABLES `user_skills` WRITE;
/*!40000 ALTER TABLE `user_skills` DISABLE KEYS */;
INSERT INTO `user_skills` (`id`, `user_id`, `skill`, `created_at`) VALUES (1,1,'Python','2026-10-07 13:28:09'),(2,1,'Machine Learning','2026-10-07 13:28:09'),(3,1,'TensorFlow','2026-10-07 13:28:09'),(4,1,'React','2026-10-07 13:28:09'),(5,1,'Data Analysis','2026-10-07 13:28:09'),(6,2,'VLSI','2026-10-07 13:28:09'),(7,2,'IoT','2026-10-07 13:28:09'),(8,2,'Arduino','2026-10-07 13:28:09'),(9,2,'Signal Processing','2026-10-07 13:28:09'),(10,3,'React','2026-10-07 13:28:09'),(11,3,'Node.js','2026-10-07 13:28:09'),(12,3,'MongoDB','2026-10-07 13:28:09'),(13,3,'TypeScript','2026-10-07 13:28:10'),(14,4,'Data Analysis','2026-10-07 13:28:10'),(15,4,'Excel','2026-10-07 13:28:10'),(16,4,'Python','2026-10-07 13:28:10'),(17,4,'Market Research','2026-10-07 13:28:10'),(18,5,'AI','2026-10-07 13:28:10'),(19,5,'PyTorch','2026-10-07 13:28:10'),(20,5,'Computer Vision','2026-10-07 13:28:10'),(21,5,'C++','2026-10-07 13:28:10'),(22,6,'CAD','2026-10-07 13:28:10'),(23,6,'MATLAB','2026-10-07 13:28:10'),(24,6,'Thermal Analysis','2026-10-07 13:28:10'),(25,6,'FEM','2026-10-07 13:28:10'),(26,7,'Python','2026-10-07 13:28:10'),(27,7,'Data Science','2026-10-07 13:28:10'),(28,7,'SQL','2026-10-07 13:28:10'),(29,7,'Tableau','2026-10-07 13:28:10'),(30,8,'Blockchain','2026-10-07 13:28:10'),(31,8,'Solidity','2026-10-07 13:28:10'),(32,8,'Web3','2026-10-07 13:28:10'),(33,8,'React','2026-10-07 13:28:10');
/*!40000 ALTER TABLE `user_skills` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `name`, `email`, `password_hash`, `avatar`, `initials`, `department`, `academic_year`, `role`, `bio`, `reputation`, `status`, `email_verified_at`, `created_at`, `updated_at`, `deleted_at`) VALUES (1,'Rafsan Ahmed','rafsan.ahmed@uiu.ac.bd','$2y$10$Pb0/1ZGYDGylHn6DR3q4AOSfFbaM4aUuzeK0Zyo3cgiPe0q0JRpt2',NULL,'RA','Computer Science & Engineering','3rd Year','Graduate Student','Passionate about AI, machine learning, and healthcare applications. Looking for collaborative minds!',2840,'online','2026-10-07 13:28:09','2026-10-07 13:28:09','2026-10-07 11:25:54',NULL),(2,'Nusrat Jahan','nusrat.jahan@uiu.ac.bd','$2y$10$Pb0/1ZGYDGylHn6DR3q4AOSfFbaM4aUuzeK0Zyo3cgiPe0q0JRpt2',NULL,'NJ','EEE','4th Year','Student','Focused on smart grids, embedded systems, and VLSI circuit design.',3200,'online','2026-10-07 13:28:09','2026-10-07 13:28:09','2026-10-07 13:28:09',NULL),(3,'Tanvir Hossain','tanvir.hossain@uiu.ac.bd','$2y$10$Pb0/1ZGYDGylHn6DR3q4AOSfFbaM4aUuzeK0Zyo3cgiPe0q0JRpt2',NULL,'TH','CSE','2nd Year','Student','Full-stack web developer and open source enthusiast.',1850,'offline','2026-10-07 13:28:09','2026-10-07 13:28:09','2026-10-07 13:28:09',NULL),(4,'Sadia Islam','sadia.islam@uiu.ac.bd','$2y$10$Pb0/1ZGYDGylHn6DR3q4AOSfFbaM4aUuzeK0Zyo3cgiPe0q0JRpt2',NULL,'SI','BBA','3rd Year','Student','Exploring the intersection of business strategy, data analytics, and fintech.',2100,'online','2026-10-07 13:28:10','2026-10-07 13:28:10','2026-10-07 13:28:10',NULL),(5,'Rakibul Islam','rakibul.islam@uiu.ac.bd','$2y$10$Pb0/1ZGYDGylHn6DR3q4AOSfFbaM4aUuzeK0Zyo3cgiPe0q0JRpt2',NULL,'RI','CSE','4th Year','Student','Computer vision researcher working on autonomous systems and robotics.',4100,'online','2026-10-07 13:28:10','2026-10-07 13:28:10','2026-10-07 13:28:10',NULL),(6,'Farhan Kabir','farhan.kabir@uiu.ac.bd','$2y$10$Pb0/1ZGYDGylHn6DR3q4AOSfFbaM4aUuzeK0Zyo3cgiPe0q0JRpt2',NULL,'FK','ME','3rd Year','Student','Mechanical engineering student passionate about renewable energy and simulations.',1650,'offline','2026-10-07 13:28:10','2026-10-07 13:28:10','2026-10-07 13:28:10',NULL),(7,'Maliha Rahman','maliha.rahman@uiu.ac.bd','$2y$10$Pb0/1ZGYDGylHn6DR3q4AOSfFbaM4aUuzeK0Zyo3cgiPe0q0JRpt2',NULL,'MR','CSE','2nd Year','Student','Bioinformatics and data science student analyzing medical and environmental trends.',2300,'online','2026-10-07 13:28:10','2026-10-07 13:28:10','2026-10-07 13:28:10',NULL),(8,'Imran Chowdhury','imran.chowdhury@uiu.ac.bd','$2y$10$Pb0/1ZGYDGylHn6DR3q4AOSfFbaM4aUuzeK0Zyo3cgiPe0q0JRpt2',NULL,'IC','CSE','4th Year','Student','Smart contract developer building decentralized governance and DeFi apps.',2650,'away','2026-10-07 13:28:10','2026-10-07 13:28:10','2026-10-07 13:28:10',NULL),(14,'Attacker','attacker@external-domain.com','$2y$10$bKhHDsTM0aTQZWPafYrM0en/uqdQiODbMH5IFpdKWaejmPsMtvW0W',NULL,'A','CSE','1st Year','Student',NULL,100,'online',NULL,'2026-10-07 10:41:38','2026-10-07 14:41:38',NULL),(20,'Attacker External','attacker@gmail.com','$2y$10$eLKJ3X8NslpFuSpiGYabe.Hb8HU64I7m9BMCmjLkQQctO7Vf3bcfG',NULL,'AE','CSE','1st Year','Student',NULL,100,'online',NULL,'2026-10-07 11:13:53','2026-10-07 15:13:53',NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-10-07 15:32:45
