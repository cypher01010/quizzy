<?php

$sql = "UPDATE `google_tts` SET `voice_rss` = '0' WHERE `google_tts`.`id` = 1;";

for($index = 1; $index <= 1372; $index++) {
	$sql = "UPDATE `google_tts` SET `voice_rss` = '0' WHERE `google_tts`.`id` = $index;";
	echo $sql;
	echo "\n";
}