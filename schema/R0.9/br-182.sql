
CREATE TABLE IF NOT EXISTS `set_speller_temp` (
	`id` bigint(20) NOT NULL AUTO_INCREMENT,
	`correct` varchar(1024) CHARACTER SET utf8 DEFAULT NULL,
	`failed` varchar(1024) CHARACTER SET utf8 DEFAULT NULL,
	`user_id` bigint(20) NOT NULL,
	`set_id` bigint(20) NOT NULL,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `set_speller_analytics` (
	`id` bigint(20) NOT NULL AUTO_INCREMENT,
	`correct` varchar(1024) CHARACTER SET utf8 DEFAULT NULL,
	`failed` varchar(1024) CHARACTER SET utf8 DEFAULT NULL,
	`user_id` bigint(20) NOT NULL,
	`set_id` bigint(20) NOT NULL,
	`date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `set_test_analytics` (
	`id` bigint(20) NOT NULL AUTO_INCREMENT,
	`analytics` varchar(2048) CHARACTER SET utf8 DEFAULT NULL,
	`user_id` bigint(20) NOT NULL,
	`set_id` bigint(20) NOT NULL,
	`date_created` timestamp NULL DEFAULT NULL,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;