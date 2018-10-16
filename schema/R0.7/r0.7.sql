TRUNCATE `set_language`;

TRUNCATE `google_tts`;


TRUNCATE `set`;
TRUNCATE `set_answer`;
TRUNCATE `set_folder`;
TRUNCATE `set_language`;
TRUNCATE `set_score`;
TRUNCATE `set_user`;

TRUNCATE `class`;
TRUNCATE `class_set`;
TRUNCATE `class_user`;
TRUNCATE `folder`;


INSERT INTO `set_language` (`id`, `name`, `keyword`, `description`) VALUES (NULL, 'English (UK)', 'en-UK', NULL), (NULL, 'Chinese (traditional)', 'zh-TW', NULL);

INSERT INTO `set_language` (`id`, `name`, `keyword`, `description`) VALUES (NULL, 'Chinese (Simplified)', 'zh-CN', NULL), (NULL, 'Malay', 'ms', NULL);

INSERT INTO `set_language` (`id`, `name`, `keyword`, `description`) VALUES (NULL, 'Tamil', 'ta', NULL), (NULL, 'Hindi', 'hi', NULL);

INSERT INTO `set_language` (`id`, `name`, `keyword`, `description`) VALUES (NULL, 'Bengali', 'bn', NULL), (NULL, 'German', 'de', NULL);

INSERT INTO `set_language` (`id`, `name`, `keyword`, `description`) VALUES (NULL, 'French', 'fr', NULL), (NULL, 'Japanese', 'ja', NULL);

INSERT INTO `set_language` (`id`, `name`, `keyword`, `description`) VALUES (NULL, 'Spanish', 'es', NULL), (NULL, 'Indonesian', 'id', NULL);

INSERT INTO `set_language` (`id`, `name`, `keyword`, `description`) VALUES (NULL, 'Italian', 'it', NULL), (NULL, 'Russian', 'ru', NULL);

INSERT INTO `set_language` (`id`, `name`, `keyword`, `description`) VALUES (NULL, 'Korean', 'ko', NULL), (NULL, 'Vietnamese', 'vi', NULL);

INSERT INTO `set_language` (`id`, `name`, `keyword`, `description`) VALUES (NULL, 'Cambodian', 'km', NULL);
