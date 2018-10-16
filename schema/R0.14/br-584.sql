ALTER TABLE `subscription` CHANGE `number_set` `number_set` INT(11) NOT NULL DEFAULT '0';

ALTER TABLE `subscription` CHANGE `name_keyword` `name_keyword` VARCHAR(128) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;

ALTER TABLE `subscription` CHANGE `keyword` `keyword` VARCHAR(128) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;

ALTER TABLE `folder` ADD `subscription_id` INT NULL DEFAULT NULL AFTER `keyword`;

--
-- Table structure for table `user_folder`
--

DROP TABLE IF EXISTS `user_folder`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_folder` (
	`id` bigint(20) NOT NULL AUTO_INCREMENT,
	`user` bigint(20) NOT NULL,
	`folder_id` bigint(20) NOT NULL,
	`date_created` varchar(32) NOT NULL,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_folder`
--

LOCK TABLES `user_folder` WRITE;
/*!40000 ALTER TABLE `user_folder` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_folder` ENABLE KEYS */;
UNLOCK TABLES;


ALTER TABLE `user_folder` ADD `expiration_date` VARCHAR(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL AFTER `folder_id`;

ALTER TABLE `user_folder` ADD `statuc` ENUM('active','inactive','validation') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'validation' ;

























ALTER TABLE `user_folder` CHANGE `user` `user_id` BIGINT(20) NOT NULL;
ALTER TABLE `user_folder` CHANGE `statuc` `status` ENUM('active','inactive','validation') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'validation';

ALTER TABLE `user_folder` CHANGE `date_created` `date_created` VARCHAR(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;




























CREATE TABLE `user_purchase` (
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




ALTER TABLE `user_purchase` CHANGE `subscription_keyword` `keyword` VARCHAR(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;
ALTER TABLE `user_purchase` CHANGE `target_set_id` `folder_id` BIGINT(20) NOT NULL DEFAULT '0';
ALTER TABLE `user_purchase` CHANGE `upgrade_key` `purchase_keyword` VARCHAR(64) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;
ALTER TABLE `user_purchase` ADD `subscription_package` VARCHAR(128) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL AFTER `folder_id`;







TRUNCATE google_tts;




ALTER TABLE `user_purchase` CHANGE `date_expired` `date_expired` VARCHAR(64) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;