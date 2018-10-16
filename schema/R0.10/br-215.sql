CREATE TABLE `user_parent` (
	`id` bigint(20) NOT NULL AUTO_INCREMENT,
	`user_id` bigint(20) NOT NULL,
	`parent_email_address` varchar(128) CHARACTER SET utf8 DEFAULT NULL,
	`activation_key` varchar(512) CHARACTER SET utf8 DEFAULT NULL,
	`date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	`status` enum('activated','inactive','deleted') CHARACTER SET utf8 NOT NULL DEFAULT 'inactive',
	PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `user_parent` ADD `parent_id` BIGINT NOT NULL DEFAULT '0' AFTER `user_id`;

ALTER TABLE `user_parent` CHANGE `user_id` `student_id` BIGINT(20) NOT NULL;
