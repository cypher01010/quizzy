ALTER TABLE `user` ADD `last_active_ip` VARCHAR(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL AFTER `last_active`;

ALTER TABLE `user` ADD `login_ip` VARCHAR(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL AFTER `last_active_ip`;

ALTER TABLE `user` ADD `login_browser` VARCHAR(512) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL AFTER `login_ip`;

ALTER TABLE `user` ADD `login_auth_key` VARCHAR(128) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL AFTER `login_browser`;

ALTER TABLE `user` ADD `keep_login` TINYINT NOT NULL DEFAULT '0' AFTER `login_auth_key`;