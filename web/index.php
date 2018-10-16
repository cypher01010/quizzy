<?php
/**
 * Load the Environment
 */
require_once(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Environment.php');

/**
 * The environment to load
 *
 * @var $environment
 */
$environment = Environment::PRODUCTION;

if($environment === Environment::STAGING) {
	$users = array('quizzy' => 'letmein');

	if (!isset($_SERVER['PHP_AUTH_USER']) && !isset($_SERVER['PHP_AUTH_PW'])) {
		header('WWW-Authenticate: Basic realm="Restricted area"');
		header('HTTP/1.0 401 Unauthorized');

		die('');
	} else {
		if(!isset($users[$_SERVER['PHP_AUTH_USER']])) {
			header('WWW-Authenticate: Basic realm="Restricted area"');
			header('HTTP/1.0 401 Unauthorized');

			die('Wrong Credentials!');
		} else if($users[$_SERVER['PHP_AUTH_USER']] !== $_SERVER['PHP_AUTH_PW']) {
			header('WWW-Authenticate: Basic realm="Restricted area"');
			header('HTTP/1.0 401 Unauthorized');

			die('Wrong Credentials!');
		}
	}
}

$debug = TRUE;
$env = 'dev';

if($environment === Environment::PRODUCTION) {
	$debug = FALSE;
	$env = 'production';
}

// comment out the following two lines when deployed to production
defined('YII_DEBUG') or define('YII_DEBUG', $debug);
defined('YII_ENV') or define('YII_ENV', $env);

require_once(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php');
require_once(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'yiisoft' . DIRECTORY_SEPARATOR . 'yii2' . DIRECTORY_SEPARATOR . 'Yii.php');

$config = require_once(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . $environment . '.php');

(new yii\web\Application($config))->run();