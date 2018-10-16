CREATE TABLE IF NOT EXISTS `set_user` (
`id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `set_id` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


ALTER TABLE `set_user` ADD `status` ENUM('granted','for-validation') NOT NULL DEFAULT 'for-validation' ;