CREATE TABLE `subscription` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_type` enum('trial-user','student','teacher','parent') CHARACTER SET utf8 NOT NULL,
  `number_set` int(11) NOT NULL,
  `price` double NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


INSERT INTO `subscription` (`id`, `user_type`, `number_set`, `price`) VALUES (NULL, 'trial-user', '1', '0'), (NULL, 'student', '-1', '100');

INSERT INTO `subscription` (`id`, `user_type`, `number_set`, `price`) VALUES (NULL, 'teacher', '-1', '0');

ALTER TABLE `subscription` ADD `duration` INT NOT NULL DEFAULT '0' AFTER `price`;

ALTER TABLE `subscription` CHANGE `duration` `duration_days` INT(11) NOT NULL DEFAULT '0';

UPDATE `subscription` SET `duration_days` = '90' WHERE `subscription`.`user_type` = 'student';