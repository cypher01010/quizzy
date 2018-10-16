ALTER TABLE `user` CHANGE `type` `type` ENUM('trial-user','student','teacher','admin','super') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'trial-user';


UPDATE `user` SET `date_updated` = NULL, `type` = 'super' WHERE `user`.`type` = 'super-admin';