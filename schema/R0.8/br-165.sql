ALTER TABLE `user` ADD `online_status` ENUM('yes','no') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'yes' AFTER `online`;



ALTER TABLE `user` ADD `last_active` VARCHAR(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL AFTER `online_status`;
