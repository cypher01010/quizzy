CREATE TABLE `set_images_temp` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `path` varchar(512) CHARACTER SET utf8 DEFAULT NULL,
  `key` varchar(64) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE `set_answer` ADD `image_path` VARCHAR(512) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL AFTER `definition_filename`;