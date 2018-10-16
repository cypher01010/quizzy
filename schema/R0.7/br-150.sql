ALTER TABLE `user` CHANGE `online` `online` ENUM('yes','no') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'yes';

ALTER TABLE `user` CHANGE `profile_public` `profile_public` ENUM('yes','no') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'yes';

