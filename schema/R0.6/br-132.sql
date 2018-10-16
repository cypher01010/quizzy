ALTER TABLE `user` ADD `email_activated` ENUM('yes','no') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'no' ;


ALTER TABLE `user` ADD `profile_public` ENUM('yes','no') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'no' AFTER `profile_picture`;