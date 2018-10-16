<?php
$config = include_once(dirname(__FILE__) . '/include/config.php');

require_once(dirname(__FILE__) . '/include/Database.php');
$dbConfig = require_once(dirname(__FILE__) . '/include/db.php');

$credentials = $dbConfig['production'];

$db = new Database($credentials);

for($index = 1; $index <= $config['voice_rss']['limitCalls']; $index++) {
	$sql = "SELECT `google_tts`.* FROM `google_tts` WHERE `google_tts`.`voice_rss` = '0' LIMIT 0, 1";
	$query = $db->query($sql);

	if(isset($query[0])) {
		$sql = "SELECT `set_language`.* FROM `set_language` WHERE `set_language`.`keyword` LIKE '" . $query[0]['language'] . "' LIMIT 0, 1";
		$language = $db->query($sql);

		if(isset($language[0])) {
			$url = $config['voice_rss']['url'];
			$params = array(
				'key' => $config['voice_rss']['key'],
				'src' => desanitize($query[0]['text']),
				'hl' => $language[0]['voice_rss_code'],
				'c' => $config['voice_rss']['codec'],
			);

			$directory = $config['tts']['new'];
			$filename = $query[0]['file_name'] . '.mp3';
			$content = voicerssCurl($url, $params);
			saveAudio($directory, $filename, $content);

			$sql = "UPDATE `google_tts` SET `google_tts`.`voice_rss` = '1' WHERE `google_tts`.`id` = " . $query[0]['id'];
			$db->update($sql);
		}
	}

	print_r($query);
	sleep(3);
}

function voicerssCurl($url, $params)
{
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.1.2) Gecko/20090729 Firefox/3.5.2 GTB5');
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POST, true);

	curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
	$tts = curl_exec($ch);
	curl_close($ch);

	return $tts;
}

function desanitize($input)
{
	return stripslashes($input);
}

function saveAudio($directory, $filename, $content)
{
	$path = $directory . DIRECTORY_SEPARATOR . $filename;

	file_put_contents($path, $content);
	chmod($path, 0777);
}