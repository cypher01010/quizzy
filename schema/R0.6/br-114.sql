ALTER TABLE `set_answer` ADD `keyword` VARCHAR(32) NULL DEFAULT NULL AFTER `order`;

CREATE TABLE IF NOT EXISTS `google_tts` (
  `id` bigint(20) NOT NULL,
  `text` varchar(512) CHARACTER SET utf8 NOT NULL,
  `language` varchar(32) CHARACTER SET utf8 NOT NULL,
  `file_name` varchar(64) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


UPDATE `set_language` SET `keyword` = 'en-US' WHERE `set_language`.`id` = 1;

ALTER TABLE `set_answer` CHANGE `order` `order_display` INT(11) NOT NULL;

ALTER TABLE `set_answer` DROP `keyword`;


ALTER TABLE `set_answer` ADD `terms_filename` VARCHAR(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `term`;


ALTER TABLE `set_answer` ADD `definition_filename` VARCHAR(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `definition`;
