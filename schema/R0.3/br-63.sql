ALTER TABLE  `user` ADD  `activation_key` VARCHAR( 128 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL AFTER  `online` ;


ALTER TABLE  `user` ADD  `date_activated` TIMESTAMP NULL DEFAULT NULL AFTER  `date_updated` ;
