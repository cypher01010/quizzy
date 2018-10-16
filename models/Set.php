<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "set".
 *
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property integer $term_set_language_id
 * @property integer $definition_set_language_id
 * @property string $date_created
 * @property string $date_updated
 * @property integer $user_id
 */
class Set extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'set';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['title', 'term_set_language_id', 'definition_set_language_id', 'user_id'], 'required'],
			[['term_set_language_id', 'definition_set_language_id', 'user_id'], 'integer'],
			[['date_created', 'date_updated'], 'safe'],
			[['title'], 'string', 'max' => 128],
			[['description'], 'string', 'max' => 1024]
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'title' => 'Title',
			'description' => 'Description',
			'term_set_language_id' => 'Term Set Language ID',
			'definition_set_language_id' => 'Definition Set Language ID',
			'date_created' => 'Date Created',
			'date_updated' => 'Date Updated',
			'user_id' => 'User ID',
		];
	}

	/**
	 * Get the users set
	 *
	 * @param $userId
	 * @param $limit
	 * @return objects|NULL
	 */
	public function getRecords($userId, $limit = 3)
	{
		$sql = '';

		if($limit == -1) {
			$sql =
			'
				SELECT
					`set`.* 
				FROM
					`set`
				WHERE
					`set`.user_id = ' . $userId . '
				ORDER BY
					`set`.id DESC
			';
		} else {
			$limit = $limit + 1;
			$sql =
			'
				SELECT
					`set`.* 
				FROM
					`set`
				WHERE
					`set`.user_id = ' . $userId . '
				ORDER BY
					`set`.id DESC
				LIMIT 0, ' . $limit;
		}

		$response = self::findBySql($sql)->all();
		if(isset($response) && $response != null) {
			return $response;
		}

		return NULL;
	}

	/**
	 * Grab the info for connected table 
	 *
	 * @return data
	 */
	public function getset_answer()
	{
		return $this->hasOne(SetAnswer::className(), ['set_id' => 'id'])->asArray()->all();
	}

	/**
	 * Add new record of set
	 *
	 * @param $title
	 * @param $description
	 * @param $termSetLanguageId
	 * @param $definitionSetLanguageId
	 * @param $dateCreated
	 * @return id
	 */
	public function addRecord($title, $description = '', $termSetLanguageId, $definitionSetLanguageId, $dateCreated, $userId)
	{
		$this->title = $title;
		$this->description = $description;
		$this->term_set_language_id = $termSetLanguageId;
		$this->definition_set_language_id = $definitionSetLanguageId;
		$this->date_created = $dateCreated;
		$this->user_id = $userId;
		$this->insert();
		return $this->id;
	}

	/**
	 * Get the information of Set
	 *
	 * @param $setId
	 * @return object|NULL
	 */
	public function getInfo($setId)
	{
		$sql =
		'
			SELECT 
				`set`.*,
				terms_language.keyword AS terms_language_keyword,
				definitions_language.keyword AS definitions_language_keyword
			FROM
				`set`
			LEFT JOIN `set_language` AS terms_language ON terms_language.id = `set`.term_set_language_id
			LEFT JOIN `set_language` AS definitions_language ON definitions_language.id = `set`.definition_set_language_id
			WHERE
				`set`.id = \'' . $setId . '\'
		';

		$response = self::findBySql($sql)->asArray()->one();
		if(isset($response) && $response != null) {
			return $response;
		}

		return NULL;
	}

	/**
	 * Update the record of set
	 *
	 * @param $title
	 * @param $description
	 * @param $termSetLanguageId
	 * @param $definitionSetLanguageId
	 * @param $dateUpdated
	 * @param $id
	 */
	public function updateRecord($title, $description = '', $termSetLanguageId, $definitionSetLanguageId, $dateUpdated, $id)
	{
		$record = $this->findOne($id);
		$record->title = $title;
		$record->description = $description;
		$record->term_set_language_id = $termSetLanguageId;
		$record->definition_set_language_id = $definitionSetLanguageId;
		$record->date_created = $dateUpdated;
		$record->save();
	}
	/**
	 * Delete the record of set
	 * @param $setId
	 */

	public function deleteRecord($setId)
	{
		$record = $this->findOne($setId);
		$record->delete();
	}

	/**
	 * Searching by query
	 *
	 * @param $query
	 * @return data
	 */
	public function searchQuery($query)
	{
		$sql =
		'
			SELECT 
				`set`.*,
				(
					SELECT 
						COUNT(`set_answer`.`set_id`)
					FROM
						`set_answer`
					WHERE
						`set_answer`.`set_id` = `set`.`id`
				) AS terms
			FROM
				`set`
			WHERE
				`title` LIKE \'%' . $query . '%\'
			ORDER BY `id` ASC
			LIMIT 0, 30
		';

		$response = self::findBySql($sql)->asArray()->all();
		if(isset($response) && $response != null) {
			return $response;
		}

		return NULL;
	}

	public function testTypePlaying()
	{
		return array(
			'written' => array('keyword' => 'written', 'text' => 'Written Question'),
			'matching' => array('keyword' => 'matching', 'text' => 'Matching Question'),
			'multiple-choice' => array('keyword' => 'multiple-choice', 'text' => 'Multiple Choice Question'),
			'bool' => array('keyword' => 'bool', 'text' => 'True / False Question'),
		);
	}

	public function testTypes($setAnswers, $types)
	{
		shuffle($setAnswers);
		shuffle($types);

		$theTypes = array();
		foreach ($types as $key => $value) {
			$theTypes[$key] = array(
				'keyword' => $value['keyword'],
				'text' => $value['text'],
				'test' => array(),
			);
		}

		foreach ($setAnswers as $key => $value) {
			$theTypes[array_rand($types)]['test'][] = $value;
		}

		$theTypes = $this->testTypeMatching($theTypes, $setAnswers);
		$theTypes = $this->testTypeMultipleChoice($theTypes, $setAnswers);
		$theTypes = $this->testTypeBool($theTypes, $setAnswers);

		return $theTypes;
	}

	private function testTypeMatching($theTypes, $setAnswers)
	{
		$matching = array();
		$termsIds = array();
		$matchingIndex = 0;
		foreach ($theTypes as $key => $value) {
			if($value['keyword'] === 'matching') {
				$matching = $value;
				$matchingIndex = $key;
			}
		}

		if(isset($matching['test']) && count($matching['test']) > 0) {
			$alphabet = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
			$theTest = array();
			$alphabetAnswer = array();
			$answersIds = array();
			$testLen = count($alphabet);


			for ($index = 0; $index < $testLen; $index++) {
				if(isset($matching['test'][$index])) {
					$thisAlphabet = strtolower($alphabet[$index]);

					$thisTest = $matching['test'][$index];
					$thisTest['alphabet'] = $thisAlphabet;

					$answersIds[] = $thisTest['id'];

					$theTest[] = $thisTest;
					$alphabetAnswer[] = $thisAlphabet;
				}
			}

			$thisRecordTest = $theTest;			

			shuffle($thisRecordTest);
			
			$answers = array();

			if((count($theTest) <= 1) && (count($setAnswers) >= 2)) {
				$answerSelect = rand(2, 5);
				$answers[] = $theTest[0];
				
				for ($index = 1; $index < $answerSelect; $index++) {
					$thisAlphabet = strtolower($alphabet[$index]);
					$alphabetAnswer[] = $thisAlphabet;
					$answerIndex = $index - 1;

					foreach ($setAnswers as $key => $value) {
						if(!in_array($value['id'], $answersIds)) {
							$thisAnswer = $value;
							$thisAnswer['alphabet'] = $thisAlphabet;

							$answers[] = $thisAnswer;

							$answersIds[] = $value['id'];
							break;
						}
					}
				}

			} else {

				foreach ($theTest as $key => $value) {
					$answers[] = $value;
				}

			}

			$matching['test'] = $thisRecordTest;
			$matching['answers'] = $answers;
			$matching['alphabetAnswer'] = $alphabetAnswer;

			$theTypes[$matchingIndex] = $matching;
		}

		return $theTypes;
	}

	private function testTypeMultipleChoice($theTypes, $setAnswers)
	{
		$multipleChoice = array();
		$multipleChoiceIndex = 0;
		foreach ($theTypes as $key => $value) {
			if($value['keyword'] === 'multiple-choice') {
				$multipleChoice = $value;
				$multipleChoiceIndex = $key;
			}
		}

		if(isset($multipleChoice['test']) && count($multipleChoice['test']) > 0) {

			$testMultipleChoice = array();
			foreach ($multipleChoice['test'] as $key => $value) {
				shuffle($setAnswers);

				$answers = array();
				$answersIds = array();
				$numberChoices = rand(2, 3);
				$answersIds[] =  $value['id'];

				for ($index = 0; $index < $numberChoices; $index++) { 
					if(!in_array($setAnswers[$index]['id'], $answersIds)) {
						$answersIds[] = $setAnswers[$index]['id'];

						$answer = array(
							'id' => $setAnswers[$index]['id'],
							'term' => $setAnswers[$index]['term'],
							'definition' => $setAnswers[$index]['definition'],
						);
						$answers[] = $answer;
					}
				}

				$thisAnswer = array(
					'id' => $value['id'],
					'term' => $value['term'],
					'definition' => $value['definition'],
				);
				$answers[] = $thisAnswer;
				shuffle($answers);

				$thisChoice = $value;
				$thisChoice['answers'] = $answers;

				$multipleChoice['test'][$key] = $thisChoice;
			}

			$theTypes[$multipleChoiceIndex] = $multipleChoice;
		}

		return $theTypes;
	}

	private function testTypeBool($theTypes, $setAnswers)
	{
		$bool = array();
		$boolIndex = 0;
		foreach ($theTypes as $key => $value) {
			if($value['keyword'] === 'bool') {
				$bool = $value;
				$boolIndex = $key;
			}
		}

		if(isset($bool['test']) && count($bool['test']) > 0) {
			$boolChoice = array();
			$countSetAnswers = count($setAnswers);

			foreach ($bool['test'] as $key => $value) {
				$thisBool = rand(0, 1);

				$thisTest = $value;
				$thisTest['selection'] = $thisBool;
				$thisTest['answer'] = array();

				if($thisBool == 0) {
					$selectedGuest = rand(0, ($countSetAnswers - 1));
					$thisTest['answer'] = array(
						'guest' => $setAnswers[$selectedGuest],
					);
				}

				$bool['test'][$key] = $thisTest;
			}

			$theTypes[$boolIndex] = $bool;
		}

		return $theTypes;
	}

	public function validateWritten($answers, $input)
	{
		$response = array();

		foreach ($answers['test'] as $answersKey => $answersValue) {

			if(is_array($input[$answersValue['id']]) && isset($input[$answersValue['id']])) {

				if(isset($input[$answersValue['id']][2]) && $input[$answersValue['id']][2] !== '') {

					$userAnswer = $input[$answersValue['id']][2];

					if(strtolower($userAnswer) === strtolower($answersValue['definition'])) {
						$response[$answersValue['id']]['result'] = array(
							'id' => $answersValue['id'],
							'result' => 1,
							'error' => 0,
							'answer' => $answersValue['definition'],
							'question' => $answersValue['term'],
							'input' => $userAnswer,
							'image' => $answersValue['image_path'],
						);
					} else {
						$response[$answersValue['id']]['result'] = array(
							'id' => $answersValue['id'],
							'result' => 0,
							'error' => 0,
							'answer' => $answersValue['definition'],
							'question' => $answersValue['term'],
							'input' => $userAnswer,
							'image' => $answersValue['image_path'],
						);
					}

				} else {
					$response[$answersValue['id']]['result'] = array(
						'id' => $answersValue['id'],
						'result' => 0,
						'error' => 1,
						'answer' => $answersValue['definition'],
						'question' => $answersValue['term'],
						'input' => '',
						'image' => $answersValue['image_path'],
					);
				}

			} else {
				$response[$answersValue['id']]['result'] = array(
					'id' => $answersValue['id'],
					'result' => 0,
					'error' => 1,
					'answer' => $answersValue['definition'],
					'question' => $answersValue['term'],
					'input' => '',
				);
			}
		}

		return $response;
	}

	public function validateMatching($answers, $input)
	{
		$response = array();
		$hasError = false;

		foreach ($answers['test'] as $answersKey => $answersValue) {
			if(is_array($input[$answersValue['id']]) && isset($input[$answersValue['id']])) {

				if(isset($input[$answersValue['id']][2]) && $input[$answersValue['id']][2] !== '') {

					$userAnswer = $input[$answersValue['id']][2];
					
					if(strtolower($userAnswer) === strtolower($answersValue['alphabet'])) {
						$response[$answersValue['id']]['result'] = array(
							'id' => $answersValue['id'],
							'result' => 1,
							'error' => 0,
							'answer' => $answersValue['definition'],
							'question' => $answersValue['term'],
							'input' => $userAnswer,
							'image' => $answersValue['image_path'],
						);
					} else {
						$response[$answersValue['id']]['result'] = array(
							'id' => $answersValue['id'],
							'result' => 0,
							'error' => 0,
							'answer' => $answersValue['definition'],
							'question' => $answersValue['term'],
							'input' => $userAnswer,
							'image' => $answersValue['image_path'],
						);
					}

				} else {
					$response[$answersValue['id']]['result'] = array(
						'id' => $answersValue['id'],
						'result' => 0,
						'error' => 1,
						'answer' => $answersValue['definition'],
						'question' => $answersValue['term'],
						'input' => '',
						'image' => $answersValue['image_path'],
					);
				}

			} else {
				$response[$answersValue['id']]['result'] = array(
					'id' => $answersValue['id'],
					'result' => 0,
					'error' => 1,
					'answer' => $answersValue['definition'],
					'question' => $answersValue['term'],
					'input' => '',
					'image' => $answersValue['image_path'],
				);
			}
		}

		return $response;
	}

	public function validateMultipleChoice($answers, $input)
	{
		$response = array();

		foreach ($answers['test'] as $answersKey => $answersValue) {

			if(is_array($input) && isset($input[$answersValue['id']])) {

				if(isset($input[$answersValue['id']][4]) && $input[$answersValue['id']][4] !== '') {

					$userAnswer = $input[$answersValue['id']][4];

					if(strtolower($userAnswer) === strtolower($answersValue['definition'])) {
						$response[$answersValue['id']]['result'] = array(
							'id' => $answersValue['id'],
							'result' => 1,
							'error' => 0,
							'answer' => $answersValue['definition'],
							'question' => $answersValue['term'],
							'input' => $userAnswer,
							'image' => $answersValue['image_path'],
						);
					} else {
						$response[$answersValue['id']]['result'] = array(
							'id' => $answersValue['id'],
							'result' => 0,
							'error' => 0,
							'answer' => $answersValue['definition'],
							'question' => $answersValue['term'],
							'input' => $userAnswer,
							'image' => $answersValue['image_path'],
						);
					}

				} else {
					$response[$answersValue['id']]['result'] = array(
						'id' => $answersValue['id'],
						'result' => 0,
						'error' => 1,
						'answer' => $answersValue['definition'],
						'question' => $answersValue['term'],
						'input' => '',
						'image' => $answersValue['image_path'],
					);
				}

			} else {
				$response[$answersValue['id']]['result'] = array(
					'id' => $answersValue['id'],
					'result' => 0,
					'error' => 1,
					'answer' => $answersValue['definition'],
					'question' => $answersValue['term'],
					'input' => '',
					'image' => $answersValue['image_path'],
				);
			}
		}

		return $response;
	}

	public function validateBool($answers, $input)
	{
		$response = array();

		foreach ($answers['test'] as $answersKey => $answersValue) {

			$correctAnswer = 'false';
			$guest = array();

			if(isset($answersValue['answer']['guest'])) {
				if($answersValue['definition'] === $answersValue['answer']['guest']['definition']) {
					$correctAnswer = 'true';
				} else {
					$correctAnswer = 'false';
				}

				$guest = $answersValue['answer']['guest'];
			} else if($answersValue['selection'] == 1) {
				$correctAnswer = 'true';
			}

			if(is_array($input) && isset($input[$answersValue['id']])) {

				if(isset($input[$answersValue['id']][2]) && $input[$answersValue['id']][2] !== '') {

					$userAnswer = $input[$answersValue['id']][2];

					if($userAnswer == $correctAnswer) {
						$response[$answersValue['id']]['result'] = array(
							'id' => $answersValue['id'],
							'result' => 1,
							'error' => 0,
							'answer' => $answersValue['definition'],
							'question' => $answersValue['term'],
							'input' => $userAnswer,
							'correctAnswer' => $correctAnswer,
							'guest' => $guest,
							'image' => $answersValue['image_path'],
						);
					} else {
						$response[$answersValue['id']]['result'] = array(
							'id' => $answersValue['id'],
							'result' => 0,
							'error' => 0,
							'answer' => $answersValue['definition'],
							'question' => $answersValue['term'],
							'input' => $userAnswer,
							'correctAnswer' => $correctAnswer,
							'guest' => $guest,
							'image' => $answersValue['image_path'],
						);
					}

				} else {
					$response[$answersValue['id']]['result'] = array(
						'id' => $answersValue['id'],
						'result' => 0,
						'error' => 1,
						'answer' => $answersValue['definition'],
						'question' => $answersValue['term'],
						'input' => '',
						'correctAnswer' => $correctAnswer,
						'guest' => $guest,
						'image' => $answersValue['image_path'],
					);
				}

			} else {
				$response[$answersValue['id']]['result'] = array(
					'id' => $answersValue['id'],
					'result' => 0,
					'error' => 1,
					'answer' => $answersValue['definition'],
					'question' => $answersValue['term'],
					'input' => '',
					'correctAnswer' => $correctAnswer,
					'guest' => $guest,
					'image' => $answersValue['image_path'],
				);
			}

		}

		return $response;
	}

	public function setSubscription($setId)
	{
		$sql =
		'
			SELECT
				`folder`.`id` AS `folder_id`,
				`folder`.`name` AS `folder_name`,
				`folder`.`keyword`,
				`subscription`.`id` AS `subscription_id`,
				`subscription`.`name` AS `subscription_name`,
				`subscription`.`price` AS `subscription_price`,
				`subscription`.`duration_days` AS `subscription_duration_days`,
				`subscription`.`keyword` AS `subscription_package`,
				(
					SELECT
						COUNT(`set_folder`.`id`) AS `count_set`
					FROM
						`set_folder`
					WHERE
						`set_folder`.`folder_id` = `folder`.`id`
				) AS `count_set`
			FROM
				`set_folder`
			LEFT JOIN `folder` ON `folder`.`id` = `set_folder`.`folder_id`
			LEFT JOIN `subscription` ON `subscription`.`id` = `folder`.`subscription_id`
			WHERE
				`set_folder`.`set_id` = ' . $setId;

		$response = self::findBySql($sql)->asArray()->all();
		if(isset($response) && $response != null) {
			return $response;
		}

		return array();
	}

	public function countRecords()
	{
		$sql =
		'
			SELECT
				COUNT(`set`.`id`) count_record
			FROM
				`set`
		';

		$count = 0;

		$response = self::findBySql($sql)->asArray()->one();
		if(isset($response) && is_array($response) && $response != null) {
			$count = $response['count_record'];
		}

		return $count;
	}
}