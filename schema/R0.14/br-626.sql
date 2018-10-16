ALTER TABLE `user_purchase` ADD `purchase_type` ENUM('new','extend') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'new' AFTER `subscription_package`;


ALTER TABLE `user_folder` ADD `notified` TINYINT(1) NOT NULL DEFAULT '0' AFTER `date_created`;