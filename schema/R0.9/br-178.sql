ALTER TABLE `user` CHANGE `type` `type` ENUM('trial-user','student','teacher','admin','super','parent') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'trial-user';
