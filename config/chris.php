<?php
$params = require(__DIR__ . DIRECTORY_SEPARATOR . 'sub' . DIRECTORY_SEPARATOR . 'params.php');
$params['url'] = [
	'static' => 'http://quizzy.local',
];
$params['profilePictureUploadPath'] = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'profile';
$params['googleTTSFilePath'] = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'tts';
$params['envi'] = \Environment::CHRIS;
$params['sendSMTPEmail'] = true;
$params['setImagePath'] = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'set';
$params['enforceHTTPS'] = false;

$config = [
	'id' => 'basic',
	'basePath' => dirname(__DIR__),
	'bootstrap' => ['log'],
	'modules' => require(__DIR__ . DIRECTORY_SEPARATOR . 'sub' . DIRECTORY_SEPARATOR . 'modules.php'),
	'components' => [
		'request' => [
			// !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
			'cookieValidationKey' => 'YpHz5EApe_TjxEkX3154rFqCtpTuO80o',
		],
		'cache' => [
			'class' => 'yii\caching\FileCache',
		],
		'urlManager' => [
			'class' => 'yii\web\UrlManager',
			'enablePrettyUrl' => false,
			'showScriptName' => true,
			//'rules' => require(__DIR__ . DIRECTORY_SEPARATOR . 'sub' . DIRECTORY_SEPARATOR . 'urlManager.php')
		],
		'user' => [
			'identityClass' => 'app\components\UserIdentity',
			'enableAutoLogin' => true,
			'loginUrl' => ['site/index'],
		],
		'errorHandler' => [
			'errorAction' => 'errorhandler/default/error',
		],
		'mailer' => [
			'class' => 'yii\swiftmailer\Mailer',
		],
		'log' => [
			'traceLevel' => YII_DEBUG ? 3 : 0,
			'targets' => [
				[
					'class' => 'yii\log\FileTarget',
					'levels' => ['error', 'warning'],
				],
			],
		],
		'db' => [
			'class' => 'yii\db\Connection',
			'dsn' => 'mysql:host=localhost;dbname=quizzy',
			'username' => 'root',
			'password' => '',
			'charset' => 'utf8',
		],
	],
	'params' => $params,
];

if (YII_ENV_DEV) {
	// configuration adjustments for 'dev' environment
	$config['bootstrap'][] = 'debug';
	$config['modules']['debug'] = 'yii\debug\Module';

	$config['bootstrap'][] = 'gii';
	$config['modules']['gii'] = 'yii\gii\Module';
}

return $config;