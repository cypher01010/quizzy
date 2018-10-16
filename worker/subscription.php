<?php
/**
 * DB creds
 *
 * @var $environment
 */
$dbConfig = require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'config.php');
$config = $dbConfig['production'];

require_once(__DIR__ . DIRECTORY_SEPARATOR . 'Database.php');
$db = new Database($config);

$epoch = time();
$today = date("Y-m-d", $epoch);

$sql =
		"
			SELECT
				*
			FROM
				`user_folder`
			WHERE
				`user_folder`.`status` = 'active' AND `user_folder`.`expiration_date` != -1 AND `user_folder`.`expiration_date` LIKE '%$today%'
		";

$activeFolders = $db->query($sql);
if(is_array($activeFolders) && !empty($activeFolders)) {
	foreach ($activeFolders as $key => $folder) {
		$sql =
				"
					UPDATE
						`user_folder`
					SET `user_folder`.`status` = 'expired'
					WHERE `user_folder`.`id` = " . $folder['id'] . "
				";

		echo $sql;
		echo "\n\n";

		$db->update($sql);
	}
}