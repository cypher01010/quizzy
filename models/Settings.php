<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "settings".
 *
 * @property integer $id
 * @property string $group
 * @property string $keyword
 * @property string $value
 */
class Settings extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'settings';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['group', 'keyword', 'value'], 'required'],
			[['group', 'keyword'], 'string', 'max' => 32],
			[['value'], 'string', 'max' => 1024]
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'group' => 'Group',
			'keyword' => 'Keyword',
			'value' => 'Value',
		];
	}

	/**
	 * get settings by group
	 *
	 * @param $group
	 * @return list of group
	 */
	public function byGroup($group = array())
	{
		$sql = NULL;

		if(is_array($group) && empty($group)) {
			$sql = 'SELECT * FROM `' . self::tableName() . '`';
		} else {
			$thisGroup = '';
			foreach ($group as $key => $value) {
				$thisGroup .= '\'' .  $value  . '\',';
			}
			$thisGroup = substr($thisGroup, 0, -1);

			$sql =
			'
			SELECT * 
				FROM  `settings` 
				WHERE  `group` 
				IN (' . $thisGroup . ')
			';
		}

		$response = self::findBySql($sql)->asArray()->all();
		$settings = array();

		foreach ($response as $key => $value) {
			$settings[$value['group']][$value['keyword']] = $value['value'];
		}

		return $settings;
	}

	/**
	 * update settings by record
	 *
	 * @param $group
	 * @param $keyword
	 * @param $value
	 */
	public function updateRecordValue($group, $keyword, $value)
	{
		$sql =
		'
			UPDATE
				`settings`
			SET
				`settings`.`value` = \'' . $value . '\'
			WHERE
				`settings`.`group` = \'' . $group . '\'
					AND
				`settings`.`keyword` = \'' . $keyword . '\'';

		$connection = \Yii::$app->db;
		$command = $connection->createCommand($sql);
		$command->execute();
		return true;
	}
}