ALTER TABLE `set_speller_temp` CHANGE `correct` `correct` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL, CHANGE `failed` `failed` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;


ALTER TABLE `set_speller_analytics` CHANGE `correct` `correct` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL, CHANGE `failed` `failed` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;



