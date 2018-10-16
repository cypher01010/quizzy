CREATE TABLE `user_upgrade` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `subscription_keyword` varchar(32) CHARACTER SET utf8 DEFAULT NULL,
  `date_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `transaction_key` text CHARACTER SET utf8,
  `date_completed` timestamp NULL DEFAULT NULL,
  `upgrade_key` varchar(64) CHARACTER SET utf8 DEFAULT NULL,
  `amount` float NOT NULL DEFAULT '0',
  `status` enum('progress','waiting_txn','done') CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



ALTER TABLE `user_upgrade` CHANGE `status` `status` ENUM('progress','waiting_txn','done','validate') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;



ALTER TABLE `user_upgrade` CHANGE `status` `status` ENUM('progress','waiting_txn','done','validating') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;



ALTER TABLE `user` ADD `upgraded_key` VARCHAR(128) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL AFTER `upgraded`;


ALTER TABLE `user_upgrade` ADD `target_set_id` BIGINT NOT NULL DEFAULT '0' AFTER `amount`;


INSERT INTO `settings` (`id`, `group`, `keyword`, `value`) VALUES (NULL, 'user', 'default.subscription', 'trial-user');



UPDATE `quizzy`.`user` SET `user`.`upgraded_key` = 'trial-user' LIMIT 1000;








ALTER TABLE `user_upgrade` ADD `date_expired` TIMESTAMP NULL DEFAULT NULL AFTER `date_completed`;



ALTER TABLE `user_upgrade` ADD `duration` INT NOT NULL DEFAULT '0' AFTER `amount`;
