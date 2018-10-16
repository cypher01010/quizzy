ALTER TABLE `set` DROP `image`, DROP `view`, DROP `view_password`, DROP `edit`, DROP `edit_password`;

ALTER TABLE `set_answer` DROP `term_set_language_id`, DROP `definition_set_language_id`, DROP `user_id`;

ALTER TABLE `set` ADD `term_set_language_id` INT NOT NULL AFTER `description`, ADD `definition_set_language_id` INT NOT NULL AFTER `term_set_language_id`;

ALTER TABLE `set_answer` ADD `order` INT NOT NULL AFTER `definition`;


INSERT INTO `set_language` (`id`, `name`, `keyword`, `description`) VALUES (NULL, 'English', 'en', 'US English Language');


DROP TABLE set_discussion;


ALTER TABLE `set` DROP `discussion`;