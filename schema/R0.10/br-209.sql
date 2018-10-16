ALTER TABLE `set_test_analytics` CHANGE `analytics` `analytics` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;

ALTER TABLE `set_test_analytics` ADD `corrent_answer` INT NOT NULL AFTER `analytics`;



ALTER TABLE `set_test_analytics` CHANGE `corrent_answer` `correct_answer` INT(11) NOT NULL;



ALTER TABLE `set_test_analytics` ADD `wrong_answer` INT NOT NULL AFTER `correct_answer`;



