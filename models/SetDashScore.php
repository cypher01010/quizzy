<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "set_dash_score".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $dash_id
 * @property integer $score
 */
class SetDashScore extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'set_dash_score';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'set_id', 'score'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'set_id' => 'Set ID',
            'score' => 'Score',
        ];
    }
    
    public function addRecord($userId, $setId, $score)
	{
		$this->user_id = $userId;
		$this->set_id = $setId;
		$this->score = $score;
		$this->insert();
		return $this->id;
	}
    
    public function leaderboard($limit, $setId)
	{
		$sql =
		'
			SELECT 
				`user`.`id`,
				`user`.`username`,
				`user`.`full_name`,
				`set_dash_score`.`score`,
				`set_dash_score`.`set_id`
			FROM
				`set_dash_score`
			LEFT JOIN `user` ON `user`.`id` = `set_dash_score`.`user_id`
			WHERE
				`set_dash_score`.`set_id` = ' . $setId . '
			ORDER BY `set_dash_score`.`score` DESC
			LIMIT 0, ' . $limit . '
		';

		$response = self::findBySql($sql)->asArray()->all();
		if(isset($response) && $response != null) {
			return $response;
		}

		return NULL;
	}

	public function getHighScore($id, $setId)
	{
		$sql =
		'
			SELECT 
				`set_dash_score`.*
			FROM
				`set_dash_score`
			WHERE
				`set_dash_score`.`user_id` = ' . $id . '
					AND
				`set_dash_score`.`set_id` = ' . $setId . '
			ORDER BY `set_dash_score`.`score` DESC
			LIMIT 0, 1
		';

		$response = self::findBySql($sql)->asArray()->all();
		if(isset($response) && $response != null) {
			return $response;
		}

		return [];
	}
}