CREATE TABLE `set_puzzle_score` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `set_id` bigint(20) NOT NULL,
  `set_answer_id` bigint(20) NOT NULL,
  `elapse` int(11) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip_created` varchar(32) DEFAULT NULL,
  `hash` varchar(128) DEFAULT NULL,
  `user_id` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;