<?php
/**
 * Global Configurations
 */
$config = [
	'voice_rss' => array(
		'key' => 'ca7a43d7930f406389731ed8f25b0aa9',
		'codec' => 'MP3',
		'url' => 'https://api.voicerss.org/?',
		'limitCalls' => 100,
	),
	'tts' => array(
		'new' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'tts',
		'old' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'old-tts',

		'build' => '/var/www/html/www.quizzy.sg/web/tts/',
	),
];

return $config;