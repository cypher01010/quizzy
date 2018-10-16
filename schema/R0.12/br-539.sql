ALTER TABLE `set_language` ADD `voice_rss_code` VARCHAR(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL AFTER `keyword`;

UPDATE `set_language` SET `voice_rss_code` = 'en-gb' WHERE `set_language`.`id` = 1;
UPDATE `set_language` SET `voice_rss_code` = 'zh-cn' WHERE `set_language`.`id` = 2;
UPDATE `set_language` SET `voice_rss_code` = 'zh-tw' WHERE `set_language`.`id` = 3;
UPDATE `set_language` SET `voice_rss_code` = 'de-de' WHERE `set_language`.`id` = 8;
UPDATE `set_language` SET `voice_rss_code` = 'fr-fr' WHERE `set_language`.`id` = 9;
UPDATE `set_language` SET `voice_rss_code` = 'ja-jp' WHERE `set_language`.`id` = 10;
UPDATE `set_language` SET `voice_rss_code` = 'es-es' WHERE `set_language`.`id` = 11;
UPDATE `set_language` SET `voice_rss_code` = 'it-it' WHERE `set_language`.`id` = 13;
UPDATE `set_language` SET `voice_rss_code` = 'ru-ru' WHERE `set_language`.`id` = 14;
UPDATE `set_language` SET `voice_rss_code` = 'ko-kr' WHERE `set_language`.`id` = 15;


ALTER TABLE `set_language` ADD `status` ENUM('active','inactive') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'active' AFTER `voice_rss_code`;



UPDATE `set_language` SET `status` = 'inactive' WHERE `set_language`.`id` = 4;
UPDATE `set_language` SET `status` = 'inactive' WHERE `set_language`.`id` = 5;
UPDATE `set_language` SET `status` = 'inactive' WHERE `set_language`.`id` = 6;
UPDATE `set_language` SET `status` = 'inactive' WHERE `set_language`.`id` = 7;
UPDATE `set_language` SET `status` = 'inactive' WHERE `set_language`.`id` = 12;
UPDATE `set_language` SET `status` = 'inactive' WHERE `set_language`.`id` = 16;
UPDATE `set_language` SET `status` = 'inactive' WHERE `set_language`.`id` = 17;


INSERT INTO `settings` (`id`, `group`, `keyword`, `value`) VALUES (NULL, 'voicerss', 'key', '8075b80798b7403fb87457e1566ae905'), (NULL, 'voicerss', 'codec', 'MP3');