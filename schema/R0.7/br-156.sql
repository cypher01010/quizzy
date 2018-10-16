ALTER TABLE `class_user` CHANGE `status` `status` ENUM('active','inactive','drop','delete','request-access') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'inactive';
