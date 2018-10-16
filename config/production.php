<?php
$params = require(__DIR__ . DIRECTORY_SEPARATOR . 'sub' . DIRECTORY_SEPARATOR . 'params.php');
$params['url'] = [
	'static' => 'https://www.quizzy.sg',
];
$params['profilePictureUploadPath'] = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'profile';
$params['googleTTSFilePath'] = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'tts';
$params['envi'] = \Environment::PRODUCTION;
$params['sendSMTPEmail'] = true;
$params['setImagePath'] = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'set';
$params['enforceHTTPS'] = true;

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
			'enablePrettyUrl' => true,
			'showScriptName' => false,
			'rules' => require(__DIR__ . DIRECTORY_SEPARATOR . 'sub' . DIRECTORY_SEPARATOR . 'urlManager.php')
		],
		'user' => [
			'identityClass' => 'app\components\UserIdentity',
			'enableAutoLogin' => true,
			'loginUrl' => ['user/login/index'],
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
			'username' => 'quizzy',
			'password' => 'pCAxcsp',
			'charset' => 'utf8',
		],
	],
	'params' => $params,
];

return $config;