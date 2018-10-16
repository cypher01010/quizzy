<?php
$urlConfig = array(
	'erson' => 'http://www.quizzy.local/subscription/renew/index',
	'development' => 'http://project.quizzy.businessfog.com/subscription/renew/index',
	'staging' => 'http://staging.quizzy.sg/subscription/renew/index',
	'production' => 'https://www.quizzy.sg/subscription/renew/index',
);
$dbConfig = require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'config.php');
$config = $dbConfig['production'];
$url = $urlConfig['production'];

require_once(__DIR__ . DIRECTORY_SEPARATOR . 'Database.php');
$db = new Database($config);

$time = time();
$today = date('Y-m-d H:i:s', time());
$toBeExpire = date('Y-m-d', strtotime($today . "+7 day"));

$sql = 'SELECT `settings`.* FROM `settings` WHERE `settings`.`group` IN ("smtp","email")';
$s = $db->query($sql);
$settings = array();
foreach ($s as $key => $value) {
	$settings[$value['group']][$value['keyword']] = $value['value'];
}

$sql =
'
	SELECT
		`user_folder`.`id`,
		`user`.`username`,
		`user_folder`.`user_id`,
		`user`.`full_name`,
		`user`.`email`,
		`folder`.`keyword` AS `folder_keyword`,
		`folder`.`name` AS `folder_name`,
		`subscription`.`price`,
		`user_folder`.`expiration_date`,
		`user_folder`.`notified`
	FROM
	    `user_folder`
	INNER JOIN `user` ON `user`.`id` = `user_folder`.`user_id`
	INNER JOIN `folder` ON `folder`.`id` = `user_folder`.`folder_id`
	INNER JOIN `subscription` ON `subscription`.`id` =  `folder`.`subscription_id`
	WHERE
		`user_folder`.`expiration_date` != \'-1\'
			AND
		`user_folder`.`expiration_date` < ' . $time . '
			AND
		`user_folder`.`notified` = 0
';

$response = $db->query($sql);

foreach ($response as $key => $value) {
	sendEmailNotification($value, $settings, $url);

	$sql =
	'
		UPDATE
			`user_folder`
		SET
			`notified` = 1
		WHERE `user_folder`.`id` = ' . $value['id'] . '
	';
	$db->update($sql);

	print_r($value);
	echo "\n\n";
	sleep(10);
}

function notificationCurl($url)
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.1.2) Gecko/20090729 Firefox/3.5.2 GTB5');
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
	$response = curl_exec($ch);
	curl_close($ch);
	return $response;
}

function sendEmailNotification($params, $settings, $url = 'http://hello-world.fuck')
{
	$swift = dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'swiftmailer' . DIRECTORY_SEPARATOR . 'swiftmailer' . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'swift_required.php';
	require_once($swift);

	$userid = $params['id'];
	$username = $params['username'];
	$full_name = $params['full_name'];
	$email = $params['email'];
	$folder_keyword = $params['folder_keyword'];
	$folder_name = $params['folder_name'];
	$price = $params['price'];
	$expiration_date = $params['expiration_date'];

	$transport = Swift_SmtpTransport::newInstance($settings['smtp']['host'], $settings['smtp']['port'])->setUsername($settings['smtp']['username'])->setPassword($settings['smtp']['password']);
	$mailer = Swift_Mailer::newInstance($transport);

	$url = $url . '?user=' . $username . '&folder=' . $folder_keyword;

	$notification =
	'
Dear ' . $full_name . ',
Thank you for your support of our learning platform.
This is a notification that your subscription to ' . $folder_name . ' is about to expire!.

Folder : ' . $folder_name . '
Expiry Date : ' .  date('M d, Y', strtotime($expiration_date)) . '

Please click on this link ' . $url . ' to renew your subscription in order to continue learning on our platform.

Happy Learning and Playing,
The Quizzy SG Team
	';

	$from = $settings['email']['noreply'];
	$to = $email;
	$subject = 'Quizzy - Folder Subscription Notification';

	// Create a message
	$message = Swift_Message::newInstance($subject)->setFrom(array($from => $from))->setTo(array($to))->setBody($notification);

	// Send the message
	$result = $mailer->send($message);
}