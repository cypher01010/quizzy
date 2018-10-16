ALTER TABLE `user` CHANGE `type` `type` ENUM('student','teacher','admin') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'student';




INSERT INTO `user` (`id`, `username`, `password`, `hash`, `email`, `birth_day`, `date_created`, `date_updated`, `date_activated`, `online`, `activation_key`, `type`, `profile_picture`, `upgraded`, `status`, `recovery_key`, `email_alert`) VALUES 
(NULL, 'admin', 'ae3ebd006a57bbc751915a114df78857e17f3e8b', 'f24a3b36f93e90ad3fdbb1e36300', 'admin@quizzy.sg', '2014-12-31 16:00:00', '2015-01-27 12:47:27', NULL, '2015-01-26 16:00:00', 'no', NULL, 'admin', '/images/profile/user.png', 'no', 'active', NULL, 'yes');
