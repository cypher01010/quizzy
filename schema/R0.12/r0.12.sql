ALTER TABLE `set_user` DROP `id`;


ALTER TABLE `set_user` ADD `id` BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST;


ALTER TABLE `academic_level` DROP `id`;


ALTER TABLE `academic_level` ADD `id` BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST;

ALTER TABLE `google_tts` DROP `id`;

ALTER TABLE `google_tts` ADD `id` BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST;

ALTER TABLE `usernames` DROP `id`;

ALTER TABLE `usernames` ADD `id` BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST;