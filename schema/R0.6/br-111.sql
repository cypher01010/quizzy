UPDATE `set_language` SET `keyword` = 'en' WHERE `set_language`.`id` = 1;

ALTER TABLE `set` ADD `date_updated` TIMESTAMP NULL DEFAULT NULL AFTER `date_created`;