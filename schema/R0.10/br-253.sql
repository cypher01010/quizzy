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
INSERT INTO `subscription` VALUES (1,'Trial User','trial-user','trial-user',1,-1,-1,0,'trial-user','active'),(12,'Student','student-126WzZs2r54L','student',-1,45,30,1,'gWH24UWME0onVVFVC','active');
/*!40000 ALTER TABLE `subscription` ENABLE KEYS */;
UNLOCK TABLES;