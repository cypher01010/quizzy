CREATE TABLE IF NOT EXISTS `email_alert_class_set` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `set_id` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


ALTER TABLE  `email_alert_class_set` CHANGE  `set_id`  `class_id` BIGINT( 20 ) NOT NULL ;


ALTER TABLE  `email_alert_class_set` ADD  `alert` ENUM(  'enable',  'disable' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT  'enable' AFTER `class_id` ;


ALTER TABLE  `email_alert_class_set` CHANGE  `alert`  `alert` ENUM(  'active',  'inactive' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'active';
