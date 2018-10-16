CREATE TABLE IF NOT EXISTS `usernames` (
`id` int(11) NOT NULL,
  `username` varchar(128) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;




INSERT INTO `usernames` (`id`, `username`) VALUES
(1, 'login'),
(2, 'administrator'),
(3, 'admin'),
(4, 'root'),
(5, 'register'),
(6, 'about'),
(7, 'faq'),
(8, 'news'),
(9, 'account'),
(10, 'recover'),
(11, 'validate'),
(12, 'quizzy'),
(13, 'root'),
(14, 'developer'),
(15, 'index'),
(16, 'about-us'),
(17, 'how'),
(18, 'new'),
(19, 'faqs'),
(20, 'contact'),
(21, 'contact-us'),
(22, 'privacy'),
(23, 'name'),
(24, 'salutation'),
(25, 'user-type'),
(26, 'user'),
(27, 'owner'),
(28, 'email'),
(29, 'password'),
(30, 'quizzy-sg');




ALTER TABLE `user` ADD `current_school` VARCHAR(512) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ;



ALTER TABLE `user` ADD `academic_level` VARCHAR(64) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ;





CREATE TABLE IF NOT EXISTS `academic_level` (
`id` int(11) NOT NULL,
  `academic` varchar(32) CHARACTER SET utf8 NOT NULL,
  `selectable` enum('yes','no') CHARACTER SET utf8 NOT NULL DEFAULT 'yes'
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=latin1;






INSERT INTO `academic_level` (`id`, `academic`, `selectable`) VALUES
(1, 'K1', 'yes'),
(2, 'K2', 'yes'),
(3, 'P1', 'yes'),
(4, 'P2', 'yes'),
(5, 'P3', 'yes'),
(6, 'P4', 'yes'),
(7, 'P5', 'yes'),
(8, 'P6', 'yes'),
(9, 'SEC1', 'yes'),
(10, 'SEC2', 'yes'),
(11, 'SEC3', 'yes'),
(12, 'SEC4', 'yes'),
(13, 'SEC5', 'yes'),
(14, 'JC1', 'yes'),
(15, 'JC2', 'yes'),
(16, 'POLY', 'yes'),
(17, 'INTERNATIONAL SCHOOL', 'yes');





ALTER TABLE `school`    DROP `description`;





ALTER TABLE `school` ADD `selectable` ENUM('yes','no') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'yes' ;






INSERT INTO `school` (`id`, `name`, `selectable`) VALUES
(1, 'Pre-school', 'yes'),
(2, 'Primary School', 'yes'),
(3, 'Secondary School', 'yes'),
(4, 'Junior College', 'yes'),
(5, 'Polytechnic', 'yes'),
(6, 'International School', 'yes');




ALTER TABLE `user` ADD `school_type` INT NOT NULL AFTER `email_activated`;



ALTER TABLE `user` CHANGE `academic_level` `academic_level` INT NOT NULL;




