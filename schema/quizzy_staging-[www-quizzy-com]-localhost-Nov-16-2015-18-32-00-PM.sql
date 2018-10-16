-- MySQL dump 10.13  Distrib 5.5.41, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: quizzy_staging
-- ------------------------------------------------------
-- Server version	5.5.41-0ubuntu0.14.04.1

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
-- Table structure for table `academic_level`
--

DROP TABLE IF EXISTS `academic_level`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `academic_level` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `academic` varchar(32) CHARACTER SET utf8 NOT NULL,
  `selectable` enum('yes','no') CHARACTER SET utf8 NOT NULL DEFAULT 'yes',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `academic_level`
--

LOCK TABLES `academic_level` WRITE;
/*!40000 ALTER TABLE `academic_level` DISABLE KEYS */;
INSERT INTO `academic_level` VALUES (1,'K1','yes'),(2,'K2','yes'),(3,'P1','yes'),(4,'P2','yes'),(5,'P3','yes'),(6,'P4','yes'),(7,'P5','yes'),(8,'P6','yes'),(9,'SEC1','yes'),(10,'SEC2','yes'),(11,'SEC3','yes'),(12,'SEC4','yes'),(13,'SEC5','yes'),(14,'JC1','yes'),(15,'JC2','yes'),(16,'POLY','yes'),(17,'INTERNATIONAL SCHOOL','yes');
/*!40000 ALTER TABLE `academic_level` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `class`
--

DROP TABLE IF EXISTS `class`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `class` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `description` varchar(512) DEFAULT NULL,
  `date_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `user_id` bigint(20) NOT NULL,
  `status` enum('inactive','active','drop','delete') NOT NULL DEFAULT 'active',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `class`
--

LOCK TABLES `class` WRITE;
/*!40000 ALTER TABLE `class` DISABLE KEYS */;
/*!40000 ALTER TABLE `class` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `class_set`
--

DROP TABLE IF EXISTS `class_set`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `class_set` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `class_id` bigint(20) NOT NULL,
  `set_id` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `class_set`
--

LOCK TABLES `class_set` WRITE;
/*!40000 ALTER TABLE `class_set` DISABLE KEYS */;
/*!40000 ALTER TABLE `class_set` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `class_user`
--

DROP TABLE IF EXISTS `class_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `class_user` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `class_id` bigint(20) NOT NULL,
  `status` enum('active','inactive','drop','delete','request-access') NOT NULL DEFAULT 'inactive',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `class_user`
--

LOCK TABLES `class_user` WRITE;
/*!40000 ALTER TABLE `class_user` DISABLE KEYS */;
/*!40000 ALTER TABLE `class_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `email_alert_class_set`
--

DROP TABLE IF EXISTS `email_alert_class_set`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `email_alert_class_set` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `class_id` bigint(20) NOT NULL,
  `alert` enum('active','inactive') CHARACTER SET utf8 NOT NULL DEFAULT 'active',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `email_alert_class_set`
--

LOCK TABLES `email_alert_class_set` WRITE;
/*!40000 ALTER TABLE `email_alert_class_set` DISABLE KEYS */;
/*!40000 ALTER TABLE `email_alert_class_set` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `folder`
--

DROP TABLE IF EXISTS `folder`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `folder` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(512) NOT NULL,
  `description` varchar(1024) DEFAULT NULL,
  `keyword` varchar(64) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `folder`
--

LOCK TABLES `folder` WRITE;
/*!40000 ALTER TABLE `folder` DISABLE KEYS */;
/*!40000 ALTER TABLE `folder` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `google_tts`
--

DROP TABLE IF EXISTS `google_tts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `google_tts` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `text` varchar(512) CHARACTER SET utf8 NOT NULL,
  `language` varchar(32) CHARACTER SET utf8 NOT NULL,
  `file_name` varchar(64) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `google_tts`
--

LOCK TABLES `google_tts` WRITE;
/*!40000 ALTER TABLE `google_tts` DISABLE KEYS */;
INSERT INTO `google_tts` VALUES (1,'Present','en-UK','dtGokXXyUCCHtFJKEvp3Bp2W-1425878810'),(2,'Gift','en-UK','rDJ6RdB2ghh5HcG6riQIyUqxqZua-1425878810'),(3,'Loud','en-UK','XkAXtnWFmxP50WsZcpBfM-1425878810'),(4,'Noisy','en-UK','pXPwYkzznc3ygxufKJhcKZUK2UB-1425878810'),(5,'Old','en-UK','G2y6sYFAe8mddKeAEdXvAZfmzYVw-1425878810'),(6,'Aged','en-UK','RjNszctC86cvXrq67vA29kIp-1425878810'),(7,'Jump','en-UK','4X5R70BGispvaEpAlzxMCp88NN-1425878810'),(8,'Hop','en-UK','zlVqeiNh48ENCqyIda4NJearDA3QT-1425878810'),(9,'Rug','en-UK','tO2nbSLQDxJXn4rewhyQr9W-1425878810'),(10,'Carpet','en-UK','KrHh2AhyZVQM5MoZDcvM54at-1425878810'),(11,'1','en-UK','31Tb8mTFxudXQxSerJUGGLV-1429263762'),(12,'2','en-UK','lUdjUDlbxn7p89HJ4nba9-1429263762'),(13,'3','en-UK','92LwVpMFIqrCXSXWI4MGGQ6JvrVJm-1429263762'),(14,'4','en-UK','xdvVrKRJOFrpwXAyuBLW7-1429263793'),(15,'a','en-UK','b2DN1a0jDypgsiuC1E8Gzw8Nhotn72-1432990928'),(16,'b','en-UK','oL5o9HeDYGK5LzVytBNwxXr2b-1432990928'),(17,'c','en-UK','shQrmj4sZkzBqgCttkEi8XtNHF-1432990928'),(18,'1','ja','Zn8rJ1AELWzYnIUxRkycsEeD-1445561350'),(19,'2','ja','ru0seYutLJAl4UYyxeBnAUq-1445561350'),(20,'3','ja','bueiiVsf6J03P969NmwC6v1d7v5-1445561351'),(21,'a1','en-UK','Sf3gfGMHO29QlpCnIjRI-1447221223'),(22,'1','ja-jp','g1e9f49kWiGpFvh3hSi3Rz-1447228532'),(23,'1','fr-fr','c4fitwFe74ZRwAvWNg0muJafv4t-1447228534'),(24,'2','ja-jp','BeW7tDNMI5PRhITJIF4dediLoE-1447228535'),(25,'2','fr-fr','kt38hQvLshAtlXozjT1tv70KoUn-1447228537'),(26,'3','ja-jp','cqKz5HViKT0RDEjm9iGGU-1447228539'),(27,'3','fr-fr','OY4RmHHQTkNoLVcGRcrKtLntlaEDx-1447228540');
/*!40000 ALTER TABLE `google_tts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `news`
--

DROP TABLE IF EXISTS `news`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(128) NOT NULL,
  `content` text NOT NULL,
  `url` varchar(256) DEFAULT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `news`
--

LOCK TABLES `news` WRITE;
/*!40000 ALTER TABLE `news` DISABLE KEYS */;
INSERT INTO `news` VALUES (1,'Digital Education in Singapore','<p>\r\n20 Dec 2014\r\n</p>\r\n<p>\r\nQuizzy is an effective elearning platform designed to help any level of learner to master difficult concepts and contents. Read more about the current use of education technology in Singapore below.\r\n</p>\r\n<p>\r\nDigital Education in Singapore\r\n</p>\r\n<p>\r\nSingapore is recognized globally for its high-performing education system that has pioneered new education models and inspired other initiatives worldwide. According to IMD World Competitiveness Yearbook 2013, Singapore was ranked among top 5 in the world for its educational system and educationists credit this success partially to technology, as the city-state has indeed astutely integrated designed digital tools such as digital libraries and e-learning in its education landscape. Written by Rebecca Zay, Project Mnager at swissnex Singapore\r\n</p>\r\n<p>\r\nAdapted from <a title=\"https://globalstatement.wordpress.com/2014/09/02/digital-education-in-singapore/\" href=\"https://globalstatement.wordpress.com/2014/09/02/digital-education-in-singapore/\">https://globalstatement.wordpress.com/2014/09/02/digital-education-in-singapore/</a>\r\n</p>','digital-education-in-singapore','2015-01-16 03:13:33',1),(2,'MOE Launches Third Masterplan for ICT in Education','<p>31 Dec 2014</p>\r\n\r\n<p>Quizzy supports MOE’s efforts to harness technology and to tranform teaching and learning. By using Quizzy, learners become self-directed and awesome learners! Read more about MOE’s directions below.</p>\r\n\r\n<p>MOE Launches Third Masterplan for ICT in Education</p>\r\n\r\n<p>The Ministry of Education has developed the third Masterplan for ICT in Education (2009-2014). The third masterplan represents a continuum of the vision of the first and second Masterplans i.e. to enrich and transform the learning environments of our students and equip them with the critical competencies and dispositions to succeed in a knowledge economy.</p>\r\n\r\n<p>\r\nThe broad strategies of the third Masterplan for ICT in Education are:\r\n<ul>\r\n	<li>To strengthen integration of ICT into curriculum, pedagogy and assessment to enhance learning and develop competencies for the 21st century;</li>\r\n	<li>To provide differentiated professional development that is more practice-based and models how ICT can be effectively used to help students learn better;</li>\r\n	<li>To improve the sharing of best practices and successful innovations; and</li>\r\n	<li>To enhance ICT provisions in schools to support the implementation of mp3.</li>\r\n</ul>\r\nRead Full Press Release <a title=\"http://www.moe.gov.sg/media/press/2008/08/moe-launches-third-masterplan.php\" href=\"http://www.moe.gov.sg/media/press/2008/08/moe-launches-third-masterplan.php\">here</a>\r\n</p>','moe-launches-third-masterplan-for-ict-in-education','2015-01-16 02:57:47',1);
/*!40000 ALTER TABLE `news` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `page_content`
--

DROP TABLE IF EXISTS `page_content`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `page_content` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `keyword` varchar(32) CHARACTER SET utf8 NOT NULL,
  `content` text CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `page_content`
--

LOCK TABLES `page_content` WRITE;
/*!40000 ALTER TABLE `page_content` DISABLE KEYS */;
INSERT INTO `page_content` VALUES (1,'about.us','<p>We are passionate about learning and how technology transforms the way we learn. That&rsquo;s why we have designed Quizzy to empower students to learn better.</p>\r\n\r\n<p>Our learning packages are based on MOE&rsquo;s latest syllabus. MOE also supports learning through the use of technology. Read about it <a href=\"/news\">here</a>.</p>\r\n\r\n<p>We have current school teachers who are our consultants. They advise us on the currency and accuracy of the learning packages. So you are assured of the best quality learning packages in the market.</p>\r\n\r\n<p>We value the support of students, teachers and parents. If you have any feedback, please click <a href=\"/contact-us\">here</a>.</p>'),(2,'footer.home','<p>All rights reserved. Copyright of Quizzy.sg. Read about our Privacy Terms <a href=\"\">here</a>. Quizzy.sg is a division of The House Of Gurus. The House of Gurus is a Singapore Government Registered Business.<br />\r\nBiz Reg. No: 53154567M</p>'),(3,'footer.inner','<p>All rights reserved. Copyright of Quizzy.sg. Read about our Privacy Terms <a href=\"/privacy\">here</a>. Quizzy.sg is a division of The House Of Gurus. The House of Gurus is a Singapore Government Registered Business. Biz Reg. No: 53154567M</p>'),(4,'privacy','<p>Your privacy is important to us. As Quizzy is a division of <a href=\"http://www.thehouseofgurus.com\" target=\"_blank\">www.thehouseofgurus.com</a>, this THE HOUSE OF GURUS Personal Data Protection Policy applies to users of www.quizzy.sg and sets out how THE HOUSE OF GURUS with the provisions of the Personal Data Protection Act 2012 of Singapore (PDPA).</p>\r\n\r\n<p>We also want you to understand the way in which we collect, use, disclose and/ or retain your Personal Data. This Personal Data Protection Policy sets out:</p>\r\n\r\n<ul>\r\n   <li>a. our policies on how we manage your Personal Data;</li>\r\n   <li>b. the types of Personal Data we collect, use, disclose and/ or retain;</li>\r\n    <li>c. how we collect, use, disclose and/ or retain your Personal Data; and</li>\r\n    <li>d. the purpose(s) for which we collect, use, disclose and/ or retain your Personal Data.</li>\r\n</ul>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>You agree and consent to us, the Organisation, and our authorised service providers and third parties to collect, use and disclose and/ or retain your Personal Data in the manner set forth in this Personal Data Protection Policy.</p>\r\n\r\n<p>This Personal Data Protection Policy supplements but does not supersede nor replace any other consent which you may have previously provided to us nor does it affect any right that we may have at law in connection with the collection, use, disclosure and/ or retention of your Personal Data.</p>\r\n\r\n<p>From time to time, we may update this Personal Data Protection Policy to ensure that our Policy is consistent with developments and trends in the Tuition Services Industry and/ or any regulatory changes. Should any revision(s) be made to this Personal Data Protection Policy, updates will be published on our main website (<a href=\"http://www.thehouseofgurus.com\" target=\"_blank\">www.thehouseofgurus.com</a>). Subject to your legal rights in Singapore, the prevailing terms of the Personal Data Protection Policy shall apply.</p>\r\n\r\n<p>This Personal Data Protection Policy forms a part of the terms and conditions governing your relationship with us and should be read in conjunction with such terms and conditions (Terms and Conditions). In the event of any inconsistency between the provisions of the Personal Data Protection Policy and the Terms and Conditions, the provisions of the Terms and Conditions shall prevail.</p>\r\n\r\n<h3>1. Your Personal Data</h3>\r\n\r\n<p>In this Personal Data Protection Policy, ?Personal Data? refers to any data and/or information about you from which you can be identified by, either (a) from that data; or (b) from that data and other information to which we may have legitimate access to. Examples of such Personal Data include but are not limited to:</p>\r\n\r\n<p>Your name, NRIC, passport or other identification number, telephone number(s), mailing address, email address and any other information relating to you which you have provided in any forms you may have submitted to use, or in other forms of interaction with you; Your photos;</p>\r\n\r\n<p>Your employment history, education background, and income levels;</p>\r\n\r\n<p>Information relating to payments, such as your bank account(s) information;</p>\r\n\r\n<p>Information about your usage of and interaction with our website and/ or services including computer and connection information, device capability, bandwidth, statistics on page views and traffic to and from our website</p>\r\n\r\n<h3>2. Collection of your Personal Data</h3>\r\n\r\n<p>Generally, we may collect your Personal Data through the following ways:</p>\r\n\r\n<p>when you sign up with us as a tutor looking for assignments or as a parent/student when engaging our services;</p>\r\n\r\n<p>when you access our websites or perform an online transaction;</p>\r\n\r\n<p>when you interact with any of our employees and/or parent/student;</p>\r\n\r\n<p>when you submit an application to us for membership services and benefits;</p>\r\n\r\n<p>when you engage us for tuition services;</p>\r\n\r\n<p>when you respond to us with regards to tuition assigments available to you;</p>\r\n\r\n<p>when you respond to our request for additional Personal Data;</p>\r\n\r\n<p>when you are included in an email or other mailing list;</p>\r\n\r\n<p>when you request that we contact you;</p>\r\n\r\n<p>when you respond to our initiatives or promotions; and</p>\r\n\r\n<p>when you submit your Personal Data to us for any other reason.</p>\r\n\r\n<p>when you browse our website, you generally do so anonymously, but please see section 6 below on cookies.</p>\r\n\r\n<h3>3. Purposes for the Collection, Use and Disclosure of Your Personal Data</h3>\r\n\r\n<p>Generally, we may collect, use, disclose and/ or retain your Personal Data for the following purposes:</p>\r\n\r\n<p>to manage your membership with us including recruitment, processing and termination of your membership;</p>\r\n\r\n<p>to provide you with membership benefits and services such as tips on academic success;</p>\r\n\r\n<p>to provide you with employment (as a tutor) and employability services;</p>\r\n\r\n<p>to provide you with tuition and or academic related services;</p>\r\n\r\n<p>to provide you with training;</p>\r\n\r\n<p>to assist you with your enquiries;</p>\r\n\r\n<p>to process payment for tuition related matters or any other purchases and subscriptions;</p>\r\n\r\n<p>to improve membership/ customer services, such as resolving complaints and handling requests and enquiries;</p>\r\n\r\n<p>to conduct research, surveys and interviews;</p>\r\n\r\n<p>to keep you updated on our events and or services; and</p>\r\n\r\n<p>to comply with applicable laws and regulations.</p>\r\n\r\n<h3>4. Marketing/ Optional Purposes</h3>\r\n\r\n<p>From time to time, we may contact you via mail, electronic mail, telephone (call or SMS-Text), facsimile or social media platforms, to inform you about our membership benefits, services and events that we think may be of interest to you.</p>\r\n\r\n<p>You can let us know at any time if you no longer wish to receive marketing materials (by sending an email with your personal particulars to contact@thehouseofgurus.com ) and we will remove your details from our direct marketing database.</p>\r\n\r\n<p>Please note that we may still send you non-marketing messages such as surveys, customer-service notices and other service related notices.</p>\r\n\r\n<h3>5. Disclosure of your Personal Data</h3>\r\n\r\n<p>We may disclose your Personal Data to the following group of external organisation for purposes mentioned above, subjected to the requirements of applicable laws:</p>\r\n\r\n<p>THE HOUSE OF GURUS, our affiliated companies and partners;</p>\r\n\r\n<p>a company subjected to a Collective Agreement with one of our affiliates;</p>\r\n\r\n<p>agents, contractors, data intermediaries or third party service providers who provide services, such as telecommunications, mailing, information technology, payment, payroll, data processing, training, market research, carding, storage and archival, to THE HOUSE OF GURUS;</p>\r\n\r\n<p>external banks, financial institutions, credit card companies and their respective service providers;</p>\r\n\r\n<p>our professional advisers such as our auditors;</p>\r\n\r\n<p>relevant government regulators, statutory boards or authorities or law enforcement agencies to comply with any laws, rules, guidelines and regulations or schemes imposed by any government authority;</p>\r\n\r\n<p>third party reward, loyalty, privileges and co-branded programme providers;</p>\r\n\r\n<p>business partners that provides any membership services and benefits; and any other person in connection with the purposes set forth above.</p>\r\n\r\n<h3>6. Use of Cookies</h3>\r\n\r\n<p>We may collect or analyse anonymised information from which individuals cannot be identified (Aggregate Information), such as number of users and their frequency of use, the number of page views (or page impressions) that occur on our websites and common entry and exit points into our websites.</p>\r\n\r\n<p>We make use of cookies to store and track Aggregate Information about you when you enter our website(s). Such cookies are used to track information such as the number of users and their frequency of use, profiles of users and their online preferences.</p>\r\n\r\n<p>Such aggregate Information collected may be used to assist us in analysing the usage of our website(s) so as to improve your online experience with us.</p>\r\n\r\n<p>Should you wish to disable the cookies associated with these technologies you may do so by changing the setting on your browser. However, please note that this may affect the functionality of the website(s).</p>\r\n\r\n<h3>7. Third-Party Sites</h3>\r\n\r\n<p>Our website may contain links to other websites operated by third parties independent of THE HOUSE OF GURUS. We are not responsible for the privacy practices of such websites operated by third parties even though it is linked to our website(s).</p>\r\n\r\n<p>We encourage you to learn about the privacy policies of such third party website(s) by checking the policy of each site you visit and contact its owner or operator if you have any concerns or questions.</p>\r\n\r\n<h3>8. Protection of your Personal Data</h3>\r\n\r\n<p>We maintain appropriate security safeguards and practices to protect your Personal Data unauthorised access, collection, use, disclosure, copying, modification disposal or similar risks, in accordance with applicable laws.</p>\r\n\r\n<h3>9. Accuracy of your Personal Data</h3>\r\n\r\n<p>We take all reasonable measures to ensure that your Personal Data remains accurate, complete and up-to-date.</p>\r\n\r\n<p>You may also keep us informed when there are any updates to your Personal Data by contacting us directly.</p>\r\n\r\n<h3>10. Withdrawal of Consent</h3>\r\n\r\n<p>If you wish to withdraw your consent to any use or disclosure of your Personal Data as set out in this Personal Data Protection Policy, you may contact us via contact@thehouseofgurus.com.</p>\r\n\r\n<p>Please note that if you withdraw your consent to any or all use or disclosure of your Personal Data, depending on the nature of your request, we may no longer be in a position to continue to provide membership benefits and services to you.</p>\r\n\r\n<p>Such a withdrawal may therefore result in the termination of any membership and or relationship that you may have with us.</p>\r\n\r\n<h3>11. Access and Correction of your Personal Data</h3>\r\n\r\n<p>You may request access to or make corrections to your Personal Data records. Please submit your request to us by sending an email with your personal particulars to contact@thehouseofgurus.com .</p>\r\n\r\n<h3>12. Contacting Us</h3>\r\n\r\n<p>If you have any questions or complaints relating to the use or disclosure of your Personal Data, or if you wish to know more about our data protection policies and practices, please visit <a href=\"http://www.thehouseofgurus.com\" target=\"_blank\">www.thehouseofgurus.com</a> for more information.</p>'),(5,'how','<p>Quizzy is really simple to use. All the study materials are prepared by us according to the latest Singapore&rsquo;s examination (PSLE, O Level, N Level and A Level) contents, aligned to the MOE school syllabus. You just have to choose how you want to learn them.</p>\r\n\r\n<p>Learning vocabulary and concepts are no longer difficult. With our engaging &ldquo;Play&rdquo; games, you will remember the things to learn in no time. In addition, you can compete with your friends in the same class to see who is the fastest. Learning is social now!</p>\r\n\r\n<p>Have more questions? Refer to our <a href=\"/faq\">FAQs.</a></p>\r\n\r\n<p>If you would like to try out our learning packages and discover how learning can be effective and fun, sign up for a free account <a href=\"/register\">here</a>.</p>\r\n\r\n<p>If you would like to find out more to purchase our learning packages, please click <a href=\"/contact-us\">here</a>.</p>'),(6,'faq','<h3>A. Basics</h3>\r\n\r\n<p>1. How Quizzy works</p>\r\n\r\n<p>Quizzy is really simple to use. All the study materials are prepared by us according to the latest Singapore&rsquo;s examination (PSLE, O Level, N Level and A Level) contents, aligned to the MOE school syllabus. You just have to choose how you want to learn them.</p>\r\n\r\n<p>Learning vocabulary and concepts are no longer difficult. With our engaging &ldquo;Play&rdquo; games, you will remember the things to learn in no time. In addition, you can compete with your friends in the same class to see who is the fastest. Learning is social now!</p>\r\n\r\n<p>2. Sign Up as Trial User</p>\r\n\r\n<p>On the homepage, locate Trial User and click on &ldquo;Register&rdquo; to sign up as a trial user. As a trial user, you are given an opportunity to try out the wonderful features of Quizzy by selecting a particular study set to start with. If Quizzy works for you, you may proceed to purchase our database of study sets.</p>\r\n\r\n<p>3. How to find a learning set</p>\r\n\r\n<p>Simply use our search function to locate your desired study set based on academic level, subject or topic. For paid and registered users, the study sets available are allocated based on the purchase and allocation. For trial users, only 1 study set is available for trying out.</p>\r\n\r\n<p>4. Study a learning set</p>\r\n\r\n<p>The main set page organizes everything you want to learn and the different ways you can learn it. You can click on any of the study tools or play games or stay on this page for review.</p>\r\n\r\n<p>5. Print a learning set</p>\r\n\r\n<p>Quizzy allows users to print their study sets for learning. Simply click locate the tools and click on the &ldquo;Print&rdquo; icon to open the printing options.</p>\r\n\r\n<h3>B. Study Tools</h3>\r\n\r\n<p>1. Flashcards</p>\r\n\r\n<p>Start studying your terms and definitions in our main Flashcards mode. You can choose between two motions: Flip or Flow.</p>\r\n\r\n<p>Flip</p>\r\n\r\n<p>Click on the Arrow buttons with your mouse to flip between cards and click the card to see the other side. You can also use the left and right arrow keys to advance cards and space bar to flip.</p>\r\n\r\n<p>By default, Quizlet shows you the term first, but if you&#39;d like to see both sides at once, click &quot;Both Sides&quot; in the sidebar on the right.</p>\r\n\r\n<p>Audio</p>\r\n\r\n<p>Turn audio on and off in the sidebar, or click Advanced to slow down audio and turn it on or off for specific sides.</p>\r\n\r\n<p>Shuffle and Play</p>\r\n\r\n<p>Use Shuffle to mix up your terms and Play for a hands-free mode.</p>\r\n\r\n<p>Flow</p>\r\n\r\n<p>Try our Flow mode for another option of studying with Flashcards. You can advance with one motion - just use your down arrow key.</p>\r\n\r\n<p>2. Speller</p>\r\n\r\n<p>You type or write (depends on the language input functions that you are using) what you hear. This mode works best for mother tongue language vocabulary and pronunciation, spelling, and familiarizing yourself with terms you don&#39;t know very well.</p>\r\n\r\n<p>With audio in various languages, Speller works automatically with almost any flashcard set.</p>\r\n\r\n<p>Listen to a term or definition and type in the correct response. Click on the &quot;replay audio&quot; button or click &quot;esc&quot; to repeat the term. In this case the answer is &ldquo;sake.&rdquo; If you type it in correctly you move on to the next question. If you make a mistake, Speller shows you which letters you missed so you can try again.</p>\r\n\r\n<p>You must spell the word correctly to move on to the next term. Speller asks 7 terms per round, and in between rounds you see a Progress Checkpoint to see how you&rsquo;re doing. You must answer each term correctly twice in a row for it to count as &quot;Fully Learned.&quot;</p>\r\n\r\n<p>Speller is complete once you&rsquo;ve &quot;Fully Learned&quot; each term. On the results page you can see the percentage of answers correct in each round and which words you missed. You can repeat Speller again or move onto other Quizzy study modes or play games.</p>\r\n\r\n<p>3. Learn</p>\r\n\r\n<p>Learn Mode tests your knowledge of a subject after you&#39;ve studied it a few times. It keeps track of what you know and what you don&#39;t and retests you on your mistakes.</p>\r\n\r\n<p>Learn prompts you with the back or the front of the flashcards you made.</p>\r\n\r\n<p>A correct answer is put in the &quot;Correct&quot; bucket. An incorrect answer is put in the &quot;Incorrect&quot; bucket. If you don&#39;t know the answer and select &quot;Give up,&quot; Learn makes you retype the answer correctly to proceed.</p>\r\n\r\n<p>Quizlet tracks what you get right and wrong as you progress through a set. At the end of each round, Quizlet retests you on what you missed.</p>\r\n\r\n<p>Answer every term/definition correctly twice in a row and Learn mode ends, showing you which words you missed or answered correctly in each round.</p>\r\n\r\n<p>Retest missed answers</p>\r\n\r\n<p>After completing Learn mode you can choose to star the terms you missed and study just them.</p>\r\n\r\n<p>Customize Learn Mode</p>\r\n\r\n<p>Quizzy can prompt you with either side of the card. You can also turn on audio for Learn, by checking the &quot;Speak Text&quot; box.</p>\r\n\r\n<p>Edit in Learn Mode</p>\r\n\r\n<p>If you notice a mistake (or want to make a change) to your term or definition while in Learn Mode, it&#39;s easy to fix.</p>\r\n\r\n<p>After you answer the question (or give up), click &quot;Edit.&quot;</p>\r\n\r\n<p>Then change whatever you&#39;d like in the term or definition and click &quot;Save.&quot;</p>\r\n\r\n<p>4. Test</p>\r\n\r\n<p>You can take customized practice tests on any study set or print it out and practice on paper.</p>\r\n\r\n<p>Step 1: Click the Test button on the main set page</p>\r\n\r\n<p>Step 2: Customize your Test</p>\r\n\r\n<p>Quizzy will automatically generate a random test for you using the terms and definitions from your set of flashcards. On the righthand sidebar you can customize:</p>\r\n\r\n<p>* Question Types (Written, Matching, Multiple Choice, or True/False)</p>\r\n\r\n<p>* Prompt With (Term, Definition)</p>\r\n\r\n<p>* Question Limit</p>\r\n\r\n<p>Click &quot;Create new test&quot; to create your custom quiz.</p>\r\n\r\n<p>Step 3: Take the test</p>\r\n\r\n<p>Enter in your answers and click &quot;Check Answers&quot; at the bottom of the page.</p>\r\n\r\n<p>Step 4: Check your score</p>\r\n\r\n<p>See how you did on your test! Your grade is in the upper righthand corner of the screen.</p>\r\n\r\n<p>Step 5: Print/Share your test</p>\r\n\r\n<p>Click &quot;Print test&quot; to print out your Test.</p>\r\n\r\n<p>You can bring a printed copy to class, or save it as a PDF to email to your teacher.</p>\r\n\r\n<p>Step 6: Repeat!</p>\r\n\r\n<p>Click &quot;Create new test&quot; to randomly generate another test with new questions. Keep practicing until you get an A+.</p>\r\n\r\n<h3>C. Play Games</h3>\r\n\r\n<p>1. Puzzle</p>\r\n\r\n<p>Step 1: Click the Puzzle button on the main set page</p>\r\n\r\n<p>Step 2: Customize your Crossword Puzzle</p>\r\n\r\n<p>Quizzy will automatically generate a random Crossword Puzzle for you using the terms and definitions from your set of flashcards. On the righthand sidebar you can customize:</p>\r\n\r\n<p>* Prompt With (Term, Definition)</p>\r\n\r\n<p>* Question Limit</p>\r\n\r\n<p>Click &quot;Create new puzzle&quot; to create your custom Crossword Puzzle.</p>\r\n\r\n<p>Step 3: Take the puzzle</p>\r\n\r\n<p>Enter in your answers and click &quot;Check Answers&quot; at the bottom of the page.</p>\r\n\r\n<p>Step 4: Check your score</p>\r\n\r\n<p>See how you did on your test! Your grade is in the upper righthand corner of the screen.</p>\r\n\r\n<p>Step 5: Print/Share your test</p>\r\n\r\n<p>Click &quot;Print test&quot; to print out your Test.</p>\r\n\r\n<p>You can bring a printed copy to class, or save it as a PDF to email to your teacher.</p>\r\n\r\n<p>Step 6: Repeat!</p>\r\n\r\n<p>Click &quot;Create new puzzle&quot; to randomly generate another Crossword Puzzle with new questions. Keep practicing until you get an A+.</p>\r\n\r\n<p>2. Scramble</p>\r\n\r\n<p>Quizzy keeps learning lively with Scatter! Race against the clock to match terms and definitions and compete for the top score with your friends.</p>\r\n\r\n<p>Quizzy scatters the back and front of the study sets on your screen.Match the terms and definitions by dragging and dropping them with your mouse.</p>\r\n\r\n<p>High Scores</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>Compete with friends to beat the record. The top ten high scores are displayed on the &quot;Scores&quot; tab of the main set page.</p>\r\n\r\n<p>3. Dash</p>\r\n\r\n<p>This is the ultimate video game study simulation. Once you start the game, definitions will scroll across the screen. It&#39;s your job to type in the corresponding term to &quot;destroy&quot; the definition before it scrolls out of sight. The longer you play, the faster they scroll.</p>\r\n\r\n<p>Type the correct term as the definition appears on the screen. If you get it wrong at first, keep trying!</p>\r\n\r\n<p>If a definition makes it to the end of the screen without a correct answer, Space Race will make you type it in in order to move on.</p>\r\n\r\n<p>You can choose to have terms scroll across instead of the definitions, or to have a random mix of terms and definitions - just click the drop-down menu next to &quot;Show...&quot;:</p>\r\n\r\n<p>You can change this at any time in the game; the next item to scroll across will reflect your changes. However, it may be helpful to pause the game first so that you don&#39;t accidentally miss a term. Just click &quot;Pause&quot; at the top of the screen. Keep going until you use all your &quot;lives.&quot; Then play again to beat the highest score!</p>\r\n\r\n<p>Go to the &quot;Scores&quot; tab on the main set page to see the top ten high scores.</p>\r\n\r\n<h3>D. Manage Your Account</h3>\r\n\r\n<p>1. Change your username or password</p>\r\n\r\n<p>To change your username, email, or password, log in and go to your Settings page.</p>\r\n\r\n<p>Username</p>\r\n\r\n<p>You can change your username one time only. Just scroll to the &quot;Change your username&quot; section, and enter your password. You will then be able to pick a new username.</p>\r\n\r\n<p>If you log in to Quizlet with Facebook, you&#39;ll be asked to verify your Facebook account before you can change your username.</p>\r\n\r\n<p>All of your account data will be seamlessly available under your new username. Also, your old name will always refer to your account. You&#39;ll be able to log in with either your old or new name.</p>\r\n\r\n<p>Password</p>\r\n\r\n<p>On the Settings page, you can change your password.</p>\r\n\r\n<p>2. Change your profile image</p>\r\n\r\n<p>To change your randomly assigned profile picture, go to your Settings page. You can get to your Settings from the drop-down under your username and profile picture, or the side bar on the main log in page.</p>\r\n\r\n<p>You can choose from the options that we provide for you.</p>\r\n\r\n<p>Link with Facebook</p>\r\n\r\n<p>You can link with Facebook to use your Facebook profile picture. Go to your Settings page and click on the &quot;Link your account to Facebook&quot; button.</p>\r\n\r\n<p>Add your own profile image</p>\r\n\r\n<p>To add an image from your computer, click on &quot;Upload your own photo.&quot;</p>\r\n\r\n<p>You can change or remove it at any time.</p>\r\n\r\n<p>3. Facebook Login</p>\r\n\r\n<p>Login with Facebook allows external websites like Quizzy to use Facebook&#39;s login system. Instead of creating a Quizzy account by entering your email and password, everything is done automatically with one click.</p>\r\n\r\n<p>How can I link my Facebook account?</p>\r\n\r\n<p>If you have registered for Quizzy with an email and password but have not linked your Facebook account, go to the Settings page and click &quot;Link your account to Facebook.&quot;</p>\r\n\r\n<p>How can I unlink my Facebook account?</p>\r\n\r\n<p>You can unlink your Facebook account at any time. Go to your Settings page and add a password so you can still log in after you unlink Facebook.</p>\r\n\r\n<p>Once you&#39;ve added a password, the option to unlink your account from Facebook will appear on your Settings page.</p>\r\n\r\n<p>Using Facebook login on shared computers at school</p>\r\n\r\n<p>When using Facebook login, a user stays logged in to Facebook in that web browser. To ensure that other users can log in after you on a shared computer, make sure to go to facebook.com and log yourself out of your Facebook account.</p>\r\n\r\n<p>Troubleshooting</p>\r\n\r\n<p>If you see a message that says &ldquo;Unable to load Facebook&rdquo; with a link to this page your computer didn&rsquo;t successfully load the Facebook login system. The likely reason for this was just a bad internet connection at the time. Please clear your browser&rsquo;s cache, refresh the Quizzy home page, and try again. If the problem persists after doing this, either Facebook&rsquo;s servers are having problems or your network is blocking access to Facebook. Contact your network administrator about this issue.</p>\r\n\r\n<p>4. Resend a confirmation email</p>\r\n\r\n<p>You must click the confirm link in the confirmation email to use many features of the site.</p>\r\n\r\n<p>If you have not received your confirmation email, take the following steps:</p>\r\n\r\n<p>1) Wait. It can take up to 24 hours for your confirmation email to be delivered.</p>\r\n\r\n<p>2) Check your spam. Make sure Quizzy and support@quizzy.sg is not being blocked by your personal or school-wide spam filter.</p>\r\n\r\n<p>3) Click &quot;re-send confirmation link.&quot; Log in to your account, on the home page at the top of the screen is the link.</p>\r\n\r\n<p>4) Change your email address. If you still haven&rsquo;t received the email, try going to your Settings page and change your email address.</p>\r\n\r\n<p>5. Delete your account</p>\r\n\r\n<p>If you need to delete your account, go to your Settings page.</p>\r\n\r\n<p>Then scroll down to the bottom of the page and click the &quot;Delete Account&quot; button.</p>\r\n\r\n<p>6. Forgotten password</p>\r\n\r\n<p>If you have forgotten your password, lclick on the &ldquo;Forgotten Password&rdquo; link. As the page, enter your username in the first box and hit &quot;Reset my password.&quot; We will send a link to reset your password to the email address you signed up with.</p>\r\n\r\n<p>If you haven&#39;t received our support email:</p>\r\n\r\n<p>&bull; try checking your spam folder</p>\r\n\r\n<p>&bull; make sure you are entering either your username OR email address on this page - not both</p>\r\n\r\n<p>The above doesn&rsquo;t help? Fret not! Send us an enquiry by clicking <a href=\"/contact-us\">here</a>.</p>'),(7,'login','<p>Login is currently disable</p>'),(8,'register','<p>Sorry, registration is currently disable by now.</p>'),(9,'login','<p>Login is currently disable</p>'),(10,'register','<p>Sorry, registration is currently disable by now.</p>'),(11,'site.maintainance','We are under maintenance!');
/*!40000 ALTER TABLE `page_content` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `school`
--

DROP TABLE IF EXISTS `school`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `school` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(512) NOT NULL,
  `selectable` enum('yes','no') NOT NULL DEFAULT 'yes',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `school`
--

LOCK TABLES `school` WRITE;
/*!40000 ALTER TABLE `school` DISABLE KEYS */;
INSERT INTO `school` VALUES (1,'Pre-school','yes'),(2,'Primary School','yes'),(3,'Secondary School','yes'),(4,'Junior College','yes'),(5,'Polytechnic','yes'),(6,'International School','yes');
/*!40000 ALTER TABLE `school` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `set`
--

DROP TABLE IF EXISTS `set`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `set` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `title` varchar(128) NOT NULL,
  `description` varchar(1024) DEFAULT NULL,
  `term_set_language_id` int(11) NOT NULL,
  `definition_set_language_id` int(11) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `date_updated` timestamp NULL DEFAULT NULL,
  `user_id` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `set`
--

LOCK TABLES `set` WRITE;
/*!40000 ALTER TABLE `set` DISABLE KEYS */;
INSERT INTO `set` VALUES (1,'Learn English',NULL,1,1,'2015-03-09 09:26:49',NULL,9),(2,'Number',NULL,1,1,'2015-04-17 13:43:13',NULL,9),(3,'alphabet',NULL,1,1,'2015-11-11 10:53:43',NULL,9),(4,'Test 001 - By Erson','this is a test created by erson',1,10,'2015-10-23 04:49:09',NULL,9),(5,'testing by erson',NULL,1,1,'2015-11-07 07:31:14',NULL,9),(6,'numbering','123123',10,9,'2015-11-11 12:55:32',NULL,9);
/*!40000 ALTER TABLE `set` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `set_answer`
--

DROP TABLE IF EXISTS `set_answer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `set_answer` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `term` varchar(512) NOT NULL,
  `terms_filename` varchar(64) NOT NULL,
  `definition` varchar(512) NOT NULL,
  `order` int(11) NOT NULL,
  `definition_filename` varchar(64) NOT NULL,
  `image_path` varchar(512) DEFAULT NULL,
  `image_key` varchar(64) DEFAULT NULL,
  `order_display` int(11) NOT NULL,
  `set_id` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `set_answer`
--

LOCK TABLES `set_answer` WRITE;
/*!40000 ALTER TABLE `set_answer` DISABLE KEYS */;
INSERT INTO `set_answer` VALUES (1,'Present','dtGokXXyUCCHtFJKEvp3Bp2W-1425878810','Gift',0,'rDJ6RdB2ghh5HcG6riQIyUqxqZua-1425878810',NULL,NULL,1,1),(2,'Loud','XkAXtnWFmxP50WsZcpBfM-1425878810','Noisy',0,'pXPwYkzznc3ygxufKJhcKZUK2UB-1425878810',NULL,NULL,2,1),(3,'Old','G2y6sYFAe8mddKeAEdXvAZfmzYVw-1425878810','Aged',0,'RjNszctC86cvXrq67vA29kIp-1425878810',NULL,NULL,3,1),(4,'Jump','4X5R70BGispvaEpAlzxMCp88NN-1425878810','Hop',0,'zlVqeiNh48ENCqyIda4NJearDA3QT-1425878810',NULL,NULL,4,1),(5,'Rug','tO2nbSLQDxJXn4rewhyQr9W-1425878810','Carpet',0,'KrHh2AhyZVQM5MoZDcvM54at-1425878810',NULL,NULL,5,1),(9,'1','31Tb8mTFxudXQxSerJUGGLV-1429263762','1',0,'31Tb8mTFxudXQxSerJUGGLV-1429263762','/set/04172015/BcV5vuJE5pYopzITFbzFZBk9NRB9YvCh94xsO.jpeg','UyKa0wDNh96ZGQEjKsGK4',1,2),(10,'2','lUdjUDlbxn7p89HJ4nba9-1429263762','2',0,'lUdjUDlbxn7p89HJ4nba9-1429263762','/set/04172015/KRKLQZtUtZtv6KiASM9iAQGq8AeKZINeg2lzRflGI1CXQk3P.jpeg','jcA7xMtqYRxfmr',2,2),(11,'3','92LwVpMFIqrCXSXWI4MGGQ6JvrVJm-1429263762','3',0,'92LwVpMFIqrCXSXWI4MGGQ6JvrVJm-1429263762','/set/04172015/TR9iPqnnaLOdVnU7jdUtl83cx1FIGboRhQuZjHHWYvggKY2HdPaMsPD2vzCeraTd05hoROWA8EkwNgd08ADbm7KDJbY.jpeg','3IAusPag6l0iOFD2yDTl86WB6nzQHhV8Qj',3,2),(12,'4','xdvVrKRJOFrpwXAyuBLW7-1429263793','4',0,'xdvVrKRJOFrpwXAyuBLW7-1429263793','/set/04172015/Yc5itvISLSRgATBm1tuBBkNG60tgIUHTrjkh9kw0G6nG0j.jpeg','vyZbfgJD0fVL2kvX6zKevr',4,2),(16,'1','31Tb8mTFxudXQxSerJUGGLV-1429263762','1',0,'Zn8rJ1AELWzYnIUxRkycsEeD-1445561350',NULL,NULL,1,4),(17,'2','lUdjUDlbxn7p89HJ4nba9-1429263762','2',0,'ru0seYutLJAl4UYyxeBnAUq-1445561350',NULL,NULL,2,4),(18,'3','92LwVpMFIqrCXSXWI4MGGQ6JvrVJm-1429263762','3',0,'bueiiVsf6J03P969NmwC6v1d7v5-1445561351',NULL,NULL,3,4),(19,'1','31Tb8mTFxudXQxSerJUGGLV-1429263762','1',0,'31Tb8mTFxudXQxSerJUGGLV-1429263762',NULL,NULL,1,5),(20,'a1','Sf3gfGMHO29QlpCnIjRI-1447221223','a1',0,'Sf3gfGMHO29QlpCnIjRI-1447221223',NULL,NULL,1,3),(21,'b','oL5o9HeDYGK5LzVytBNwxXr2b-1432990928','b',0,'oL5o9HeDYGK5LzVytBNwxXr2b-1432990928',NULL,NULL,2,3),(22,'c','shQrmj4sZkzBqgCttkEi8XtNHF-1432990928','c',0,'shQrmj4sZkzBqgCttkEi8XtNHF-1432990928',NULL,NULL,3,3),(23,'1','g1e9f49kWiGpFvh3hSi3Rz-1447228532','1',0,'c4fitwFe74ZRwAvWNg0muJafv4t-1447228534',NULL,NULL,1,6),(24,'2','BeW7tDNMI5PRhITJIF4dediLoE-1447228535','2',0,'kt38hQvLshAtlXozjT1tv70KoUn-1447228537',NULL,NULL,2,6),(25,'3','cqKz5HViKT0RDEjm9iGGU-1447228539','3',0,'OY4RmHHQTkNoLVcGRcrKtLntlaEDx-1447228540',NULL,NULL,3,6);
/*!40000 ALTER TABLE `set_answer` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `set_dash_score`
--

DROP TABLE IF EXISTS `set_dash_score`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `set_dash_score` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `set_id` int(11) DEFAULT NULL,
  `score` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `set_dash_score`
--

LOCK TABLES `set_dash_score` WRITE;
/*!40000 ALTER TABLE `set_dash_score` DISABLE KEYS */;
INSERT INTO `set_dash_score` VALUES (1,4,1,500),(2,4,1,460),(3,4,1,49),(4,4,1,40),(5,9,3,30);
/*!40000 ALTER TABLE `set_dash_score` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `set_folder`
--

DROP TABLE IF EXISTS `set_folder`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `set_folder` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `set_id` bigint(20) NOT NULL,
  `folder_id` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `set_folder`
--

LOCK TABLES `set_folder` WRITE;
/*!40000 ALTER TABLE `set_folder` DISABLE KEYS */;
/*!40000 ALTER TABLE `set_folder` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `set_images_temp`
--

DROP TABLE IF EXISTS `set_images_temp`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `set_images_temp` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `path` varchar(512) DEFAULT NULL,
  `key` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `set_images_temp`
--

LOCK TABLES `set_images_temp` WRITE;
/*!40000 ALTER TABLE `set_images_temp` DISABLE KEYS */;
INSERT INTO `set_images_temp` VALUES (1,'/set/04172015/BcV5vuJE5pYopzITFbzFZBk9NRB9YvCh94xsO.jpeg','UyKa0wDNh96ZGQEjKsGK4'),(2,'/set/04172015/KRKLQZtUtZtv6KiASM9iAQGq8AeKZINeg2lzRflGI1CXQk3P.jpeg','jcA7xMtqYRxfmr'),(3,'/set/04172015/VFe9NCaIKbs5HozAL7ik2rAvvogWrFTroS7aXAtNI4ShdMlq9jsQBUmpny2A1WE77O919HtqGBXLjWjbciJk4fx.jpeg','nVhMY6lW3IUMOlIT6S6PkWA9lALxut5xH1ZqJvuFj'),(4,'/set/04172015/TR9iPqnnaLOdVnU7jdUtl83cx1FIGboRhQuZjHHWYvggKY2HdPaMsPD2vzCeraTd05hoROWA8EkwNgd08ADbm7KDJbY.jpeg','3IAusPag6l0iOFD2yDTl86WB6nzQHhV8Qj'),(5,'/set/04172015/Yc5itvISLSRgATBm1tuBBkNG60tgIUHTrjkh9kw0G6nG0j.jpeg','vyZbfgJD0fVL2kvX6zKevr');
/*!40000 ALTER TABLE `set_images_temp` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `set_language`
--

DROP TABLE IF EXISTS `set_language`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `set_language` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `keyword` varchar(16) NOT NULL,
  `voice_rss_code` varchar(32) DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `description` varchar(512) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `set_language`
--

LOCK TABLES `set_language` WRITE;
/*!40000 ALTER TABLE `set_language` DISABLE KEYS */;
INSERT INTO `set_language` VALUES (1,'English (UK)','en-UK','en-gb','active',NULL),(2,'Chinese (Simplified)','zh-CN','zh-cn','active',NULL),(3,'Chinese (traditional)','zh-TW','zh-tw','active',NULL),(4,'Malay','ms',NULL,'inactive',NULL),(5,'Tamil','ta',NULL,'inactive',NULL),(6,'Hindi','hi',NULL,'inactive',NULL),(7,'Bengali','bn',NULL,'inactive',NULL),(8,'German','de','de-de','active',NULL),(9,'French','fr','fr-fr','active',NULL),(10,'Japanese','ja','ja-jp','active',NULL),(11,'Spanish','es','es-es','active',NULL),(12,'Indonesian','id',NULL,'inactive',NULL),(13,'Italian','it','it-it','active',NULL),(14,'Russian','ru','ru-ru','active',NULL),(15,'Korean','ko','ko-kr','active',NULL),(16,'Vietnamese','vi',NULL,'inactive',NULL),(17,'Cambodian','km',NULL,'inactive',NULL);
/*!40000 ALTER TABLE `set_language` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `set_puzzle_score`
--

DROP TABLE IF EXISTS `set_puzzle_score`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `set_puzzle_score` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `set_id` bigint(20) NOT NULL,
  `set_answer_id` bigint(20) NOT NULL,
  `elapse` int(11) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip_created` varchar(32) DEFAULT NULL,
  `hash` varchar(128) DEFAULT NULL,
  `user_id` bigint(20) NOT NULL,
  `completed` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `set_puzzle_score`
--

LOCK TABLES `set_puzzle_score` WRITE;
/*!40000 ALTER TABLE `set_puzzle_score` DISABLE KEYS */;
INSERT INTO `set_puzzle_score` VALUES (1,1,1,30,'2015-08-06 07:53:05','112.198.99.6','0b7eee419ec0c71e71b3448b44d137ce',4,0),(2,1,2,10,'2015-08-06 07:54:50','112.198.99.6','434400d7c792e790d4b8c799c68cadd1',4,0),(3,1,5,43,'2015-08-06 07:55:19','112.198.99.6','434400d7c792e790d4b8c799c68cadd1',4,0),(4,1,1,60,'2015-08-06 07:55:38','112.198.99.6','434400d7c792e790d4b8c799c68cadd1',4,0),(5,1,3,93,'2015-08-06 07:56:09','112.198.99.6','434400d7c792e790d4b8c799c68cadd1',4,0),(6,1,4,114,'2015-08-06 07:56:30','112.198.99.6','434400d7c792e790d4b8c799c68cadd1',4,0),(7,1,5,15,'2015-08-21 13:09:17','112.210.59.66','8f2c3c7e46b4f6c92b4a3ab054d1e589',4,0),(8,1,4,42,'2015-08-21 13:09:45','112.210.59.66','8f2c3c7e46b4f6c92b4a3ab054d1e589',4,0),(9,1,3,57,'2015-08-21 13:10:00','112.210.59.66','8f2c3c7e46b4f6c92b4a3ab054d1e589',4,0),(10,1,1,68,'2015-08-21 13:10:10','112.210.59.66','8f2c3c7e46b4f6c92b4a3ab054d1e589',4,0),(11,1,2,80,'2015-08-21 13:10:23','112.210.59.66','8f2c3c7e46b4f6c92b4a3ab054d1e589',4,0),(12,1,3,15,'2015-08-21 13:10:50','112.210.59.66','3692b93ba79bd9bbfd400589e0c01ad2',4,0),(13,1,2,27,'2015-08-21 13:11:02','112.210.59.66','3692b93ba79bd9bbfd400589e0c01ad2',4,0),(14,1,4,36,'2015-08-21 13:11:12','112.210.59.66','3692b93ba79bd9bbfd400589e0c01ad2',4,0),(15,1,5,42,'2015-08-21 13:11:17','112.210.59.66','3692b93ba79bd9bbfd400589e0c01ad2',4,0),(16,1,1,48,'2015-08-21 13:11:24','112.210.59.66','3692b93ba79bd9bbfd400589e0c01ad2',4,0),(17,1,2,27,'2015-08-21 13:12:36','112.210.59.66','868f02b3487dda08ce04fd34b5fe54e0',4,0),(18,1,5,63,'2015-08-21 13:13:12','112.210.59.66','868f02b3487dda08ce04fd34b5fe54e0',4,0),(19,1,1,83,'2015-08-21 13:13:32','112.210.59.66','868f02b3487dda08ce04fd34b5fe54e0',4,0),(20,1,3,102,'2015-08-21 13:13:51','112.210.59.66','868f02b3487dda08ce04fd34b5fe54e0',4,0),(21,1,4,120,'2015-08-21 13:14:09','112.210.59.66','868f02b3487dda08ce04fd34b5fe54e0',4,0),(22,1,4,20,'2015-08-22 18:11:43','112.210.59.66','c37d9c6a6712ac379d84aa35e0a8eaeb',4,0),(23,1,1,38,'2015-08-22 18:12:01','112.210.59.66','c37d9c6a6712ac379d84aa35e0a8eaeb',4,0),(24,1,3,52,'2015-08-22 18:12:16','112.210.59.66','c37d9c6a6712ac379d84aa35e0a8eaeb',4,0),(25,1,5,66,'2015-08-22 18:12:29','112.210.59.66','c37d9c6a6712ac379d84aa35e0a8eaeb',4,0),(26,1,2,77,'2015-08-22 18:12:41','112.210.59.66','c37d9c6a6712ac379d84aa35e0a8eaeb',4,0),(27,1,3,10,'2015-08-23 14:09:58','112.210.59.66','bed0cd7b4d6c2b5253d71ae9c952f96a',4,0),(28,1,5,21,'2015-08-23 14:10:09','112.210.59.66','bed0cd7b4d6c2b5253d71ae9c952f96a',4,0),(29,1,1,66,'2015-08-23 14:10:55','112.210.59.66','bed0cd7b4d6c2b5253d71ae9c952f96a',4,0),(30,1,4,73,'2015-08-23 14:11:02','112.210.59.66','bed0cd7b4d6c2b5253d71ae9c952f96a',4,0),(31,1,2,85,'2015-08-23 14:11:14','112.210.59.66','bed0cd7b4d6c2b5253d71ae9c952f96a',4,1);
/*!40000 ALTER TABLE `set_puzzle_score` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `set_score`
--

DROP TABLE IF EXISTS `set_score`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `set_score` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `score` float NOT NULL,
  `category` enum('cards','learn','speller','test','puzzle','scrabble','dash') NOT NULL DEFAULT 'cards',
  `user_id` bigint(20) NOT NULL,
  `set_id` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `set_score`
--

LOCK TABLES `set_score` WRITE;
/*!40000 ALTER TABLE `set_score` DISABLE KEYS */;
/*!40000 ALTER TABLE `set_score` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `set_scramble_score`
--

DROP TABLE IF EXISTS `set_scramble_score`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `set_scramble_score` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `set_id` int(11) DEFAULT NULL,
  `time` time DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `set_scramble_score`
--

LOCK TABLES `set_scramble_score` WRITE;
/*!40000 ALTER TABLE `set_scramble_score` DISABLE KEYS */;
INSERT INTO `set_scramble_score` VALUES (1,4,1,'00:00:19'),(2,4,1,'00:00:29'),(3,4,1,'00:00:10'),(4,4,1,'00:00:18'),(5,4,1,'00:00:23'),(6,9,4,'00:00:05');
/*!40000 ALTER TABLE `set_scramble_score` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `set_speller_analytics`
--

DROP TABLE IF EXISTS `set_speller_analytics`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `set_speller_analytics` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `correct` text,
  `failed` text,
  `user_id` bigint(20) NOT NULL,
  `set_id` bigint(20) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `set_speller_analytics`
--

LOCK TABLES `set_speller_analytics` WRITE;
/*!40000 ALTER TABLE `set_speller_analytics` DISABLE KEYS */;
/*!40000 ALTER TABLE `set_speller_analytics` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `set_speller_temp`
--

DROP TABLE IF EXISTS `set_speller_temp`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `set_speller_temp` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `correct` text,
  `failed` text,
  `user_id` bigint(20) NOT NULL,
  `set_id` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `set_speller_temp`
--

LOCK TABLES `set_speller_temp` WRITE;
/*!40000 ALTER TABLE `set_speller_temp` DISABLE KEYS */;
INSERT INTO `set_speller_temp` VALUES (1,'a:1:{i:5;i:1;}','a:0:{}',18,1);
/*!40000 ALTER TABLE `set_speller_temp` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `set_test_analytics`
--

DROP TABLE IF EXISTS `set_test_analytics`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `set_test_analytics` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `analytics` text CHARACTER SET utf8,
  `correct_answer` int(11) NOT NULL,
  `wrong_answer` int(11) NOT NULL,
  `score_percentage` float NOT NULL DEFAULT '0',
  `user_id` bigint(20) NOT NULL,
  `set_id` bigint(20) NOT NULL,
  `date_created` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `set_test_analytics`
--

LOCK TABLES `set_test_analytics` WRITE;
/*!40000 ALTER TABLE `set_test_analytics` DISABLE KEYS */;
INSERT INTO `set_test_analytics` VALUES (1,'YTowOnt9',0,0,0,18,1,'2015-03-13 05:42:20'),(2,'YTozOntzOjE1OiJtdWx0aXBsZS1jaG9pY2UiO2E6MTp7aToxNzthOjE6e3M6NjoicmVzdWx0IjthOjc6e3M6MjoiaWQiO3M6MjoiMTciO3M6NjoicmVzdWx0IjtpOjE7czo1OiJlcnJvciI7aTowO3M6NjoiYW5zd2VyIjtzOjE6IjIiO3M6ODoicXVlc3Rpb24iO3M6MToiMiI7czo1OiJpbnB1dCI7czoxOiIyIjtzOjU6ImltYWdlIjtOO319fXM6Nzoid3JpdHRlbiI7YToxOntpOjE4O2E6MTp7czo2OiJyZXN1bHQiO2E6Nzp7czoyOiJpZCI7czoyOiIxOCI7czo2OiJyZXN1bHQiO2k6MTtzOjU6ImVycm9yIjtpOjA7czo2OiJhbnN3ZXIiO3M6MToiMyI7czo4OiJxdWVzdGlvbiI7czoxOiIzIjtzOjU6ImlucHV0IjtzOjE6IjMiO3M6NToiaW1hZ2UiO047fX19czo4OiJtYXRjaGluZyI7YToxOntpOjE2O2E6MTp7czo2OiJyZXN1bHQiO2E6Nzp7czoyOiJpZCI7czoyOiIxNiI7czo2OiJyZXN1bHQiO2k6MTtzOjU6ImVycm9yIjtpOjA7czo2OiJhbnN3ZXIiO3M6MToiMSI7czo4OiJxdWVzdGlvbiI7czoxOiIxIjtzOjU6ImlucHV0IjtzOjE6ImEiO3M6NToiaW1hZ2UiO047fX19fQ==',3,0,100,9,4,'2015-10-23 04:50:15'),(3,'YToyOntzOjg6Im1hdGNoaW5nIjthOjI6e2k6MTY7YToxOntzOjY6InJlc3VsdCI7YTo3OntzOjI6ImlkIjtzOjI6IjE2IjtzOjY6InJlc3VsdCI7aTowO3M6NToiZXJyb3IiO2k6MDtzOjY6ImFuc3dlciI7czoxOiIxIjtzOjg6InF1ZXN0aW9uIjtzOjE6IjEiO3M6NToiaW5wdXQiO3M6MToiYiI7czo1OiJpbWFnZSI7Tjt9fWk6MTc7YToxOntzOjY6InJlc3VsdCI7YTo3OntzOjI6ImlkIjtzOjI6IjE3IjtzOjY6InJlc3VsdCI7aTowO3M6NToiZXJyb3IiO2k6MDtzOjY6ImFuc3dlciI7czoxOiIyIjtzOjg6InF1ZXN0aW9uIjtzOjE6IjIiO3M6NToiaW5wdXQiO3M6MToiYSI7czo1OiJpbWFnZSI7Tjt9fX1zOjc6IndyaXR0ZW4iO2E6MTp7aToxODthOjE6e3M6NjoicmVzdWx0IjthOjc6e3M6MjoiaWQiO3M6MjoiMTgiO3M6NjoicmVzdWx0IjtpOjE7czo1OiJlcnJvciI7aTowO3M6NjoiYW5zd2VyIjtzOjE6IjMiO3M6ODoicXVlc3Rpb24iO3M6MToiMyI7czo1OiJpbnB1dCI7czoxOiIzIjtzOjU6ImltYWdlIjtOO319fX0=',1,2,33.3333,9,4,'2015-10-23 04:50:33');
/*!40000 ALTER TABLE `set_test_analytics` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `set_user`
--

DROP TABLE IF EXISTS `set_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `set_user` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `set_id` bigint(20) NOT NULL,
  `status` enum('granted','for-validation') NOT NULL DEFAULT 'for-validation',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `set_user`
--

LOCK TABLES `set_user` WRITE;
/*!40000 ALTER TABLE `set_user` DISABLE KEYS */;
INSERT INTO `set_user` VALUES (1,9,1,'granted'),(2,4,1,'granted'),(3,17,1,'granted'),(4,17,2,'granted'),(5,19,1,'granted'),(6,19,2,'granted'),(7,21,1,'granted'),(8,21,2,'granted'),(9,23,1,'granted'),(10,9,4,'granted'),(11,9,3,'granted'),(12,9,5,'granted'),(13,9,6,'granted');
/*!40000 ALTER TABLE `set_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group` varchar(32) NOT NULL,
  `keyword` varchar(32) NOT NULL,
  `value` varchar(1024) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES (1,'site','site.name','Quizzy'),(2,'language','default','en-US'),(3,'user','default.status','inactive'),(4,'site','maintenance','0'),(5,'site','email.contact','contact@quizzy.sg'),(6,'user','allow.register','1'),(7,'user','allow.login','1'),(8,'student','nonupgrade.class.limit','5'),(9,'student','upgrade.class.limit','-1'),(10,'smtp','host','smtp.mandrillapp.com'),(11,'smtp','username','mandrill@webdesignlab.sg'),(12,'smtp','password','75GjRrXYxPgpekHMpN7f-w'),(13,'smtp','port','587'),(14,'smtp','encryption','tls'),(15,'email','noreply','noreply@quizzy.sg'),(16,'email','contact','contact@quizzy.sg'),(17,'email','test.receiver','mandrill@webdesignlab.sg'),(18,'paypal','live.url','www.paypal.com'),(19,'paypal','live.email','email@quizzy.sg'),(20,'paypal','live.button','button id here'),(21,'paypal','sandbox.url','www.sandbox.paypal.com'),(22,'paypal','sandbox.email','business.owner@my-domain.com'),(23,'paypal','sandbox.button','HLD5BNFYLWJL2'),(24,'paypal','currency','USD'),(25,'paypal','live.payment','0'),(26,'user','default.subscription','trial-user'),(27,'user','default.subscription','trial-user'),(28,'voicerss','key','8075b80798b7403fb87457e1566ae905'),(29,'voicerss','codec','MP3');
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subscription`
--

DROP TABLE IF EXISTS `subscription`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `subscription` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) CHARACTER SET utf8 DEFAULT NULL,
  `name_keyword` varchar(32) CHARACTER SET utf8 DEFAULT NULL,
  `user_type` enum('trial-user','student','teacher','parent') CHARACTER SET utf8 NOT NULL,
  `number_set` int(11) NOT NULL,
  `price` double NOT NULL,
  `duration_days` int(11) NOT NULL DEFAULT '0',
  `expire` tinyint(1) NOT NULL DEFAULT '1',
  `keyword` varchar(32) CHARACTER SET utf8 DEFAULT NULL,
  `status` enum('active','inactive') CHARACTER SET utf8 NOT NULL DEFAULT 'active',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subscription`
--

LOCK TABLES `subscription` WRITE;
/*!40000 ALTER TABLE `subscription` DISABLE KEYS */;
INSERT INTO `subscription` VALUES (1,'Trial User','trial-user','trial-user',1,-1,-1,0,'trial-user','active'),(12,'Student','student-126WzZs2r54L','student',-1,3,30,1,'gWH24UWME0onVVFVC','active');
/*!40000 ALTER TABLE `subscription` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `username` varchar(64) NOT NULL,
  `password` varchar(128) NOT NULL,
  `hash` varchar(64) NOT NULL,
  `email` varchar(64) NOT NULL,
  `birth_day` timestamp NULL DEFAULT NULL,
  `date_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `date_updated` timestamp NULL DEFAULT NULL,
  `date_activated` timestamp NULL DEFAULT NULL,
  `online` enum('yes','no') NOT NULL DEFAULT 'yes',
  `online_status` enum('yes','no') NOT NULL DEFAULT 'yes',
  `last_active` varchar(32) DEFAULT NULL,
  `last_active_ip` varchar(32) DEFAULT NULL,
  `login_ip` varchar(32) DEFAULT NULL,
  `login_browser` varchar(512) DEFAULT NULL,
  `login_auth_key` varchar(128) DEFAULT NULL,
  `keep_login` tinyint(4) NOT NULL DEFAULT '0',
  `activation_key` varchar(128) DEFAULT NULL,
  `type` enum('trial-user','student','teacher','admin','super','parent') NOT NULL DEFAULT 'trial-user',
  `profile_picture` varchar(128) DEFAULT '/images/profile/user.png',
  `upgraded` enum('yes','no') NOT NULL DEFAULT 'no',
  `upgraded_key` varchar(128) DEFAULT NULL,
  `status` enum('inactive','active','delete') NOT NULL DEFAULT 'inactive',
  `recovery_key` varchar(128) DEFAULT NULL,
  `email_alert` enum('yes','no') NOT NULL DEFAULT 'yes',
  `profile_public` enum('yes','no') NOT NULL DEFAULT 'yes',
  `email_activated` enum('yes','no') NOT NULL DEFAULT 'no',
  `school_type` int(11) NOT NULL,
  `current_school` varchar(512) DEFAULT NULL,
  `academic_level` int(11) NOT NULL,
  `full_name` varchar(512) DEFAULT NULL,
  `google_account` varchar(64) DEFAULT NULL,
  `fb_account` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (4,'erson','4139b2f9bf6202afbb55c876fadcf7c6c4a97570','f57f2a35be6a4672d5e4be1fc8fd','erson.puyos@gmail.com','1984-11-11 05:00:00','2015-11-04 08:27:25',NULL,'2015-01-19 19:18:16','no','yes','1446625645','121.54.58.241','121.54.58.241','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/46.0.2490.80 Safari/537.36','97aa9fc9f03eb83b157c7c6bdfd89aa49a849f6c',0,NULL,'teacher','/images/profile/NsW1t73wpvlqRzUjX9y9.png','no','trial-user','active','','','no','yes',0,NULL,0,'Erson Baby',NULL,NULL),(7,'studentboy','77bd2a40161015a470128c41376ff1a8f44357bc','292762cb7989d76df69d35ceb16f','student@quizzy.sg','1989-02-14 05:00:00','2015-04-22 03:50:02',NULL,NULL,'yes','yes',NULL,NULL,NULL,NULL,NULL,0,'frP7ORhKQ32TUPDdAFKONNrbvHuKOXOcmI7g1DeYPv1rA','student','/images/profile/VmbAqsC0E580EY6jLMbj.png','no','trial-user','inactive','','yes','yes','no',0,NULL,0,NULL,NULL,NULL),(8,'admin','ae3ebd006a57bbc751915a114df78857e17f3e8b','f24a3b36f93e90ad3fdbb1e36300','admin@quizzy.sg','2014-12-31 21:00:00','2015-04-22 03:50:02',NULL,'2015-01-26 21:00:00','no','no','',NULL,NULL,NULL,NULL,0,'uZmdekV3Khf7ayhWILHoJg9LHdSccF9dIuSLrk2g3mjR6','admin','/images/profile/user.png','no','trial-user','active',NULL,'yes','no','no',0,NULL,0,NULL,NULL,NULL),(9,'superadmin','ae3ebd006a57bbc751915a114df78857e17f3e8b','f24a3b36f93e90ad3fdbb1e36300','superadmin@quizzy.sg','2015-01-29 05:00:00','2015-11-07 02:29:24',NULL,'2015-01-29 05:00:00','no','yes','1446863364','112.198.98.234','112.198.98.234','Mozilla/5.0 (Windows NT 6.3; rv:41.0) Gecko/20100101 Firefox/41.0','638a6aed562a735b169c0cfe9be879369228b309',1,NULL,'super','/images/profile/QAlQFVHP4GEDM5P9nkA6.png','no','trial-user','active',NULL,'no','no','no',0,NULL,0,NULL,NULL,NULL),(16,'bryan','ae3ebd006a57bbc751915a114df78857e17f3e8b','f24a3b36f93e90ad3fdbb1e36300','bryan@quizzy.sg','1979-05-30 04:00:00','2015-04-22 03:50:02',NULL,'2015-01-29 05:00:00','no','yes','1427424116',NULL,NULL,NULL,NULL,0,'Z2l2Dr5nJE8qgys3Pq7j4FP1ny3aHzpjAah5n0XDxEYU','super','/images/profile/QAlQFVHP4GEDM5P9nkA6.png','no','trial-user','active',NULL,'no','no','no',0,NULL,0,NULL,NULL,NULL),(18,'test','9f54838309b9572b6ca19c09da6983c8ac46423b','fc32ec5652a65ea0198c4257aee0','bryankonghk@gmail.com','2015-01-01 05:00:00','2015-04-22 03:50:02',NULL,NULL,'yes','yes','1426210940',NULL,NULL,NULL,NULL,0,NULL,'trial-user','/images/profile/NsW1t73wpvlqRzUjX9y9.png','no','trial-user','inactive',NULL,'yes','yes','yes',2,'nanyang primary school',3,'','',''),(22,'onlinewize','aa84433683a574d0e7b8d1457f21e74fbb675e85','6a5083e39f1f72ea5f0a9aacdf4d','onlinewize@gmail.com','2011-05-04 04:00:00','2015-09-01 14:00:28',NULL,NULL,'yes','no','','112.207.141.241','112.207.141.241','Mozilla/5.0 (Windows NT 6.3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/44.0.2403.157 Safari/537.36','',0,'LGSgmrCDjGSl6TuzGbeKr4ZauoKPJomaZhpw1CYS7CFFhzTuJ','trial-user','/images/profile/user.png','no','trial-user','active','wyCdAZLY3DdgxprvJCnuFpf20EvrPEyZVsPlbf','no','yes','yes',3,'slasdkjf asdlfjasdf adsf as',3,'Online Wize','',''),(23,'cypherbox','ffc41901010b4d0b4ef5c965c5892708f4d032ec','7b6de0c8b0764d79ab273dea67b1','caralos@gmail.com','1997-02-18 05:00:00','2015-08-06 07:48:59',NULL,NULL,'yes','yes','1433945953','112.210.17.207','112.210.17.207','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/43.0.2357.124 Safari/537.36','c07d8d6e5e6a48d1cf6b29873cd3eaa8472eafbd',0,'X5UbicV9ajNg6JV0QrOdooZi1PRfZcTzQ3wbV2MjPj6LoWjJnUD2L','trial-user','/images/profile/user.png','no','trial-user','active','XnjFUuoS53qz2Vnt6XGxfQf9fztRCkuuNooIwTq','no','yes','no',1,'Umang Kamang-kamang Saka-saka Alimango Talaba High',16,'Christopher Caralos','','');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_parent`
--

DROP TABLE IF EXISTS `user_parent`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_parent` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `student_id` bigint(20) NOT NULL,
  `parent_id` bigint(20) NOT NULL DEFAULT '0',
  `parent_email_address` varchar(128) DEFAULT NULL,
  `activation_key` varchar(512) DEFAULT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` enum('activated','inactive','deleted') NOT NULL DEFAULT 'inactive',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_parent`
--

LOCK TABLES `user_parent` WRITE;
/*!40000 ALTER TABLE `user_parent` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_parent` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_upgrade`
--

DROP TABLE IF EXISTS `user_upgrade`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_upgrade` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `subscription_keyword` varchar(32) DEFAULT NULL,
  `date_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `transaction_key` text,
  `date_completed` timestamp NULL DEFAULT NULL,
  `date_expired` timestamp NULL DEFAULT NULL,
  `upgrade_key` varchar(64) DEFAULT NULL,
  `amount` float NOT NULL DEFAULT '0',
  `duration` int(11) NOT NULL DEFAULT '0',
  `target_set_id` bigint(20) NOT NULL DEFAULT '0',
  `status` enum('progress','waiting_txn','done','validating') DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_upgrade`
--

LOCK TABLES `user_upgrade` WRITE;
/*!40000 ALTER TABLE `user_upgrade` DISABLE KEYS */;
INSERT INTO `user_upgrade` VALUES (22,17,'IWIoEUI2vpAwIoun','2015-04-22 06:57:36',NULL,NULL,NULL,'gWH24UWME0onVVFVC',3,0,0,'validating'),(23,17,'gq4SULGAiX3qD8SXfqj','2015-04-22 07:07:01',NULL,NULL,NULL,'gWH24UWME0onVVFVC',3,0,0,'validating'),(24,17,'ZZgZ1uGSsuTWIWJ8qOrNU0EKbFMXGets','2015-04-22 07:10:43','5XF196776U610883C','2015-04-22 07:11:13',NULL,'gWH24UWME0onVVFVC',3,0,0,'done'),(25,17,'ZOIj1qU2eybg93WwdFfzl7zX7WVTPsr','2015-04-22 07:26:15','29134471A21321215','2015-04-22 07:26:50',NULL,'gWH24UWME0onVVFVC',3,0,0,'done'),(26,19,'5Xh9gKRykDRPGcWax1vBybef','2015-04-22 11:50:37','1J122225EY4400511','2015-04-22 11:52:36','2015-05-22 11:52:36','gWH24UWME0onVVFVC',3,30,2,'done'),(28,20,'kl0fndyjUm5ngrJOa53oH','2015-04-22 12:16:59','7SW374725Y9428509','2015-04-22 12:18:11','2015-05-22 12:18:11','gWH24UWME0onVVFVC',3,30,0,'done'),(29,21,'6ngUB1Fspcnrh6LJUVhXO7YxJ','2015-04-22 15:12:27','0NH8062529291402U','2015-04-22 15:14:36','2015-05-22 15:14:36','gWH24UWME0onVVFVC',3,30,2,'done');
/*!40000 ALTER TABLE `user_upgrade` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usernames`
--

DROP TABLE IF EXISTS `usernames`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usernames` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `username` varchar(128) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usernames`
--

LOCK TABLES `usernames` WRITE;
/*!40000 ALTER TABLE `usernames` DISABLE KEYS */;
INSERT INTO `usernames` VALUES (1,'login'),(2,'administrator'),(3,'admin'),(4,'root'),(5,'register'),(6,'about'),(7,'faq'),(8,'news'),(9,'account'),(10,'recover'),(11,'validate'),(12,'quizzy'),(13,'root'),(14,'developer'),(15,'index'),(16,'about-us'),(17,'how'),(18,'new'),(19,'faqs'),(20,'contact'),(21,'contact-us'),(22,'privacy'),(23,'name'),(24,'salutation'),(25,'user-type'),(26,'user'),(27,'owner'),(28,'email'),(29,'password'),(30,'quizzy-sg');
/*!40000 ALTER TABLE `usernames` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-11-16 18:32:00
