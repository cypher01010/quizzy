<?php
namespace app\modules\Admin\controllers;

use Yii;
use app\models\User;
use yii\data\ActiveDataProvider;

class SetController extends \app\components\BaseController
{
	public function beforeAction($event)
	{
		$this->filterHttps(['*']);

		return parent::beforeAction($event);
	}

	public function behaviors()
	{
		return [
			'access' => [
				'class' => \yii\filters\AccessControl::className(),
				'only' => ['index', 'deleteset', 'saveterms', 'add', 'saveset', 'newitem', 'edit', 'saveedit', 'audiolist', 'class', 'classadd', 'classlist', 'classremove', 'folderadd', 'folderlist', 'folderremove', 'folder', 'searchclass', 'view', 'setimage'],
				'rules' => [
					[
						'allow' => true,
						'matchCallback' => function() {
							return !empty(Yii::$app->session->get('id')) && ((Yii::$app->session->get('type') === User::USERTYPE_ADMIN || Yii::$app->session->get('type') === User::USERTYPE_SUPER_ADMIN));
						},
					],
				],
			],
		];
	}

	public function actionIndex()
	{
		$this->setLayout('/admin');

		$searchModel = new \app\models\SetSearch;
		
		$dataProvider = NULL;
		if(Yii::$app->session->get('type') === User::USERTYPE_ADMIN) {
			$dataProvider = $searchModel->searchAdmin(Yii::$app->request->queryParams, Yii::$app->session->get('id'));
		} else {
			$dataProvider = $searchModel->searchSuperAdmin(Yii::$app->request->queryParams);
		}

		return $this->render('index', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}

	public function actionAdd()
	{
		$this->setLayout('/admin');
		$setLanguageModel = new \app\models\SetLanguage;
		$setLanguage = $setLanguageModel->find()->asArray()->all();

		$userObject = new \app\models\User;

		$setCreateDefaultNumber = Yii::$app->params['set']['createDefaultNumber'];
		$limitPerSetTermsDefinition = Yii::$app->params['set']['limitPerSetTermsDefinition'];

		$setsTermsDefinitions = array();
		for ($index = 1; $index <= $setCreateDefaultNumber; $index++) { 
			$data = array(
				'data-item-id' => $userObject->randomCharacters(20, 20),
			);
			$setsTermsDefinitions[] = $data;
		}

		$setLanguageObject = new \app\models\SetLanguage;
		$languages = $setLanguageObject->allLanguage();

		return $this->render('add', array(
			'setCreateDefaultNumber' => $setCreateDefaultNumber,
			'limitPerSetTermsDefinition' => $limitPerSetTermsDefinition,
			'setsTermsDefinitions' => $setsTermsDefinitions,
			'languages' => $languages,
		));
	}
	
	public function actionSaveset()
	{
		if(!Yii::$app->request->post()) {
			$this->redirect(Yii::$app->urlManager->createUrl('site/index'));
		}

		$response = array();
		$response['success'] = false;

		if(isset($_POST['title']) && $_POST['title'] !== '' && isset($_POST['description']) && isset($_POST['_csrf']) && isset($_POST['languageTerms']) && isset($_POST['languageDefinitions'])) {
			$title = addslashes(trim($_POST['title']));
			$description = addslashes(trim($_POST['description']));
			if(empty($description)) {
				$description = NULL;
			}
			$dateCreated = date(Yii::$app->params['dateFormat']['set'], time());

			$languageTerms = $_POST['languageTerms'];
			$languageDefinitions = $_POST['languageDefinitions'];

			$setLanguageObject = new \app\models\SetLanguage;
			$language = $setLanguageObject->getByKeyword($languageTerms);
			$termSetLanguageId = 1;
			$languageTermsKeyword = 'en';
			if(!empty($language)) {
				$termSetLanguageId = $language['id'];
				$languageTermsKeyword = $language['voice_rss_code'];
			}

			$language = $setLanguageObject->getByKeyword($languageDefinitions);
			$definitionSetLanguageId = 1;
			$languageDefinitionsKeyword = 'en';
			if(!empty($language)) {
				$definitionSetLanguageId = $language['id'];
				$languageDefinitionsKeyword = $language['voice_rss_code'];
			}

			$setObject = new \app\models\Set;
			$setId = $setObject->addRecord(
				$title,
				$description,
				$termSetLanguageId,
				$definitionSetLanguageId,
				$dateCreated,
				Yii::$app->session->get('id')
			);

			$response['success'] = true;
			$response['setId'] = $setId;
			$response['languageTerms'] = $languageTermsKeyword;
			$response['languageDefinitions'] = $languageDefinitionsKeyword;
			$response['url'] = Yii::$app->getUrlManager()->createUrl(['admin/set/view', 'id' => $setId]);
		}

		echo json_encode($response);
	}
	
	public function actionSaveterms()
	{
		//trapping here for POST request method
		if(!Yii::$app->request->post()) {
			$this->redirect(Yii::$app->urlManager->createUrl('site/index'));
		}

		$response = array();
		$response['success'] = false;

		if(isset($_POST['list']) && is_array($_POST['list']) && isset($_POST['setId']) && isset($_POST['languageTerms']) && isset($_POST['languageDefinitions']) && isset($_POST['_csrf'])) {
			$list = $_POST['list'];

			$setImagesTempObject = new \app\models\SetImagesTemp;

			$images = isset($_POST['images']) ? $_POST['images'] : array();
			$listImages = array();
			foreach ($images as $key => $value) {
				$listImages[$value[0]] = $setImagesTempObject->getImage($this->cleanInput($value[1]));
			}

			$languageTerms = addslashes(trim($_POST['languageTerms']));
			$languageDefinitions = addslashes(trim($_POST['languageDefinitions']));

			$googleTTSPath = Yii::$app->params['googleTTSFilePath'];
			$setId = (int)trim($_POST['setId']);

			$settings = $this->siteSettings(array('voicerss'));

			$termVoiceRssConfig = array();
			$termVoiceRssConfig['language'] = $languageTerms;
			$termVoiceRssConfig['apiKey'] = $settings['voicerss']['key'];
			$termVoiceRssConfig['codec'] = $settings['voicerss']['codec'];

			$definitionVoiceRssConfig = array();
			$definitionVoiceRssConfig['language'] = $languageDefinitions;
			$definitionVoiceRssConfig['apiKey'] = $settings['voicerss']['key'];
			$definitionVoiceRssConfig['codec'] = $settings['voicerss']['codec'];

			$thisImagesIds = array();
			foreach ($list as $key => $value) {
				$terms = addslashes(trim($value[1]));
				if($terms === '') {
					$terms = 'Term';
				}
				$definitions = addslashes(trim($value[2]));
				if($definitions === '') {
					$definitions = 'Definition';
				}

				$index = $key + 1;

				$googleTtsObject = new \app\models\GoogleTts;

				//check for terms
				$filenameTerms = NULL;
				$response = $googleTtsObject->searchTTSAudio($terms, $languageTerms);
				if(empty($response)) {
					$filenameTerms = $googleTtsObject->translate($terms, $languageTerms, $googleTTSPath, $googleTtsObject->generateFilename(), $termVoiceRssConfig);
					$googleTtsObject->addRecord($terms, $languageTerms, $filenameTerms);
				} else {
					$filenameTerms = $response->file_name;
				}
				$response = NULL;
				$googleTtsObject = NULL;

				$googleTtsObject = new \app\models\GoogleTts;

				//check for definitions
				$filenameDefinitions = NULL;
				$response = $googleTtsObject->searchTTSAudio($definitions, $languageDefinitions);
				if(empty($response)) {
					$filenameDefinitions = $googleTtsObject->translate($definitions, $languageDefinitions, $googleTTSPath, $googleTtsObject->generateFilename(), $definitionVoiceRssConfig);
					$googleTtsObject->addRecord($definitions, $languageDefinitions, $filenameDefinitions);
				} else {
					$filenameDefinitions = $response->file_name;
				}
				$response = NULL;
				$googleTtsObject = NULL;

				$theImage = '';
				$theKey = '';
				$thisImagesIds[] = trim($value[0]);
				if(isset($listImages[trim($value[0])])) {
					$theImage = $listImages[trim($value[0])]['path'];
					$theKey = $listImages[trim($value[0])]['key'];
				}

				$setAnswerObject = new \app\models\SetAnswer;
				$setAnswerObject->addRecord($terms, $filenameTerms, $definitions, $filenameDefinitions, $index, $setId, $theImage, $theKey);
				$setAnswerObject = NULL;
			}

			$response['success'] = true;
			$response['url'] = Yii::$app->getUrlManager()->createUrl(['admin/set/view', 'id' => $setId]);
		}

		echo json_encode($response);
	}

	public function actionNewitem()
	{
		if(!Yii::$app->request->post()) {
			$this->redirect(Yii::$app->urlManager->createUrl('site/index'));
		}

		$response = array();
		$response['success'] = false;

		if(isset($_POST['_csrf'])) {
			$userObject = new \app\models\User;

			$response['success'] = true;
			$response['id'] = $userObject->randomCharacters(20, 20);
		}

		echo json_encode($response);
	}

	public function actionEdit($id)
	{
		$setObject = new \app\models\Set;
		$setInfo = $setObject->getInfo($id);

		if(empty($setInfo)) {
			$this->redirect(Yii::$app->urlManager->createUrl('admin/set/index'));
		}
		if(Yii::$app->session->get('type') === User::USERTYPE_ADMIN && $setInfo['user_id'] != Yii::$app->session->get('id')) {
			$this->redirect(Yii::$app->urlManager->createUrl('admin/set/index'));
		}

		$setAnswerObject = new \app\models\SetAnswer;
		$setTermsDefinitions = $setAnswerObject->getTermsDefinitions($id);

		$setLanguageObject = new \app\models\SetLanguage;
		$languages = $setLanguageObject->allLanguage();

		$limitPerSetTermsDefinition = Yii::$app->params['set']['limitPerSetTermsDefinition'];

		$setFolderObject = new \app\models\SetFolder;
		$folderList = $setFolderObject->folderList($id);

		$classSetObject = new \app\models\ClassSet;
		$classList = $classSetObject->classList($id);

		$setInfo['title'] = $this->desanitize($setInfo['title']);
		$setInfo['description'] = $this->desanitize($setInfo['description']);

		$this->setLayout('/admin');
		return $this->render('edit', array(
			'setInfo' => $setInfo,
			'setTermsDefinitions' => $setTermsDefinitions,
			'languages' => $languages,
			'limitPerSetTermsDefinition' => $limitPerSetTermsDefinition,
			'folderList' => $folderList,
			'classList' => $classList,
		));
	}

	public function actionSaveedit()
	{
		if(!Yii::$app->request->post()) {
			$this->redirect(Yii::$app->urlManager->createUrl('site/index'));
		}

		$response = array();
		$response['success'] = false;

		if(isset($_POST['title']) && $_POST['title'] !== '' && isset($_POST['description']) && isset($_POST['_csrf']) && isset($_POST['languageTerms']) && isset($_POST['languageDefinitions']) && isset($_POST['list']) && is_array($_POST['list']) && isset($_POST['setId'])) {
			$title = addslashes(trim($_POST['title']));
			$description = addslashes(trim($_POST['description']));
			if($description === '') {
				$description = NULL;
			}
			$dateUpdated = date(Yii::$app->params['dateFormat']['set'], time());

			$languageTerms = $_POST['languageTerms'];
			$languageDefinitions = $_POST['languageDefinitions'];

			$setId = (int)trim($_POST['setId']);
			$setFolder = isset($_POST['setFolder']) ? $_POST['setFolder'] : array();
			$setClass = isset($_POST['setClass']) ? $_POST['setClass'] : array();

			$setImagesTempObject = new \app\models\SetImagesTemp;

			$images = isset($_POST['images']) ? $_POST['images'] : array();
			$listImages = array();
			foreach ($images as $key => $value) {
				$listImages[$value[0]] = $setImagesTempObject->getImage($this->cleanInput($value[1]));
			}

			$settings = $this->siteSettings(array('voicerss'));

			$setLanguageObject = new \app\models\SetLanguage;
			$language = $setLanguageObject->getByKeyword($languageTerms);
			$termSetLanguageId = 1;
			$languageTermsKeyword = 'en';
			$termVoiceRssConfig = array();
			if(!empty($language)) {
				$termSetLanguageId = $language['id'];
				$languageTermsKeyword = $language['keyword'];

				$termVoiceRssConfig['language'] = $language['voice_rss_code'];
				$termVoiceRssConfig['apiKey'] = $settings['voicerss']['key'];
				$termVoiceRssConfig['codec'] = $settings['voicerss']['codec'];
			}

			$language = $setLanguageObject->getByKeyword($languageDefinitions);
			$definitionSetLanguageId = 1;
			$languageDefinitionsKeyword = 'en';
			$definitionVoiceRssConfig = array();
			if(!empty($language)) {
				$definitionSetLanguageId = $language['id'];
				$languageDefinitionsKeyword = $language['keyword'];

				$definitionVoiceRssConfig['language'] = $language['voice_rss_code'];
				$definitionVoiceRssConfig['apiKey'] = $settings['voicerss']['key'];
				$definitionVoiceRssConfig['codec'] = $settings['voicerss']['codec'];
			}

			$setObject = new \app\models\Set;
			$setObject->updateRecord(
				$title,
				$description,
				$termSetLanguageId,
				$definitionSetLanguageId,
				$dateUpdated,
				$setId
			);

			$list = $_POST['list'];
			$googleTTSPath = Yii::$app->params['googleTTSFilePath'];

			$setAnswerObject = new \app\models\SetAnswer;
			$setAnswerObject->deleteAllRecord($setId);

			foreach ($list as $key => $value) {
				$terms = addslashes(trim($value[1]));
				if($terms === '') {
					$terms = 'Term';
				}
				$definitions = addslashes(trim($value[2]));
				if($definitions === '') {
					$definitions = 'Definition';
				}

				$index = $key + 1;

				$googleTtsObject = new \app\models\GoogleTts;

				//check for terms
				$filenameTerms = NULL;
				$response = $googleTtsObject->searchTTSAudio($terms, $languageTerms);
				if(empty($response)) {
					$filenameTerms = $googleTtsObject->translate($terms, $languageTerms, $googleTTSPath, $googleTtsObject->generateFilename(), $termVoiceRssConfig);
					$googleTtsObject->addRecord($terms, $languageTerms, $filenameTerms);
				} else {
					$filenameTerms = $response->file_name;
				}
				$response = NULL;
				$googleTtsObject = NULL;

				$googleTtsObject = new \app\models\GoogleTts;

				//check for definitions
				$filenameDefinitions = NULL;
				$response = $googleTtsObject->searchTTSAudio($definitions, $languageDefinitions);
				if(empty($response)) {
					$filenameDefinitions = $googleTtsObject->translate($definitions, $languageDefinitions, $googleTTSPath, $googleTtsObject->generateFilename(), $definitionVoiceRssConfig);
					$googleTtsObject->addRecord($definitions, $languageDefinitions, $filenameDefinitions);
				} else {
					$filenameDefinitions = $response->file_name;
				}
				$response = NULL;
				$googleTtsObject = NULL;

				$theImage = '';
				$theKey = '';
				$thisImagesIds[] = trim($value[0]);
				if(isset($listImages[trim($value[0])])) {
					$theImage = $listImages[trim($value[0])]['path'];
					$theKey = $listImages[trim($value[0])]['key'];
				}

				$setAnswerObject = new \app\models\SetAnswer;
				$setAnswerObject->addRecord($terms, $filenameTerms, $definitions, $filenameDefinitions, $index, $setId, $theImage, $theKey);
				$setAnswerObject = NULL;
			}

			$setFolderObject = new \app\models\SetFolder;
			$setFolderObject->deleteBySetId($setId);
			foreach ($setFolder as $key => $value) {
				$setFolderObject = new \app\models\SetFolder;
				$setFolderObject->deleteRecord($value, $setId);

				$setFolderObject = new \app\models\SetFolder;
				$setFolderObject->addRecord($value, $setId);
			}

			$classSetObject = new \app\models\ClassSet;
			$classSetObject->deleteBySetId($setId);
			foreach ($setClass as $key => $value) {
				$classSetObject = new \app\models\ClassSet;
				$classSetObject->deleteRecord($value, $setId);

				$classSetObject = new \app\models\ClassSet;
				$classSetObject->addRecord($value, $setId);
			}

			$response['success'] = true;
			$response['url'] = Yii::$app->getUrlManager()->createUrl(['admin/set/view', 'id' => $setId]);
		}

		echo json_encode($response);		
	}

	public function actionDeleteset()
	{
		$response = array();
		$response['success'] = false;

		if(isset($_POST['_csrf']) && isset($_POST['set_id'])) {
			$set_id = $_POST['set_id'];
			$setModel = new \app\models\Set;
			$setModel->deleteRecord($set_id);

			$setAnswerModel = new \app\models\SetAnswer;
			$setAnswerModel->deleteAllRecord($set_id);

			$response['success'] = true;
		}

		echo json_encode($response);
	}

	public function actionAudiolist()
	{
		if(!Yii::$app->request->post()) {
			$this->redirect(Yii::$app->urlManager->createUrl('site/index'));
		}

		$response = array();
		$response['success'] = false;

		if(isset($_POST['_csrf']) && isset($_POST['setId'])) {
			$setId = (int)addslashes(trim($_POST['setId']));
			$setAnswerObject = new \app\models\SetAnswer;
			$setTermsDefinitions = $setAnswerObject->getTermsDefinitions($setId);

			$audiolist = array();
			foreach ($setTermsDefinitions as $key => $value) {
				$data['id'] = $value['id'];
				$data['term'] = array(
					'text' => $value['term'],
					'file' => Yii::$app->params['url']['static'] . '/tts/' . $value['terms_filename'] . '.mp3',
				);
				$data['definition'] = array(
					'text' => $value['definition'],
					'file' => Yii::$app->params['url']['static'] . '/tts/' .  $value['definition_filename'] . '.mp3',
				);

				$audiolist[] = $data;
			}

			$response['audiolist'] = $audiolist;
			$response['success'] = true;
		}

		echo json_encode($response);
	}

	public function actionView($id)
	{
		$this->setLayout('/admin');

		$setObject = new \app\models\Set;
		$setInfo = $setObject->getInfo($id);

		if(empty($setInfo)) {
			$this->redirect(Yii::$app->urlManager->createUrl('admin/set/index'));
		}

		if(Yii::$app->session->get('type') === User::USERTYPE_ADMIN && $setInfo['user_id'] != Yii::$app->session->get('id')) {
			$this->redirect(Yii::$app->urlManager->createUrl('admin/set/index'));
		}

		$setAnswerObject = new \app\models\SetAnswer;
		$setTermsDefinitions = $setAnswerObject->getTermsDefinitions($id);

		$setLanguageObject = new \app\models\SetLanguage;
		$languages = $setLanguageObject->allLanguage();

		$userObject = new \app\models\User;
		$owner = $userObject->getRecordById($setInfo['user_id']);

		$setUserObject = new \app\models\SetUser;
		$setUser = $setUserObject->getUsersOfSet($id);

		return $this->render('view', [
			'setInfo' => $setInfo,
			'owner' => $owner,
			'setTermsDefinitions' => $setTermsDefinitions,
			'languages' => $languages,
			'setUser' => $setUser,
		]);
	}

	public function actionClass()
	{
		if(!Yii::$app->request->post()) {
			$this->redirect(Yii::$app->urlManager->createUrl('site/index'));
		}

		$response = array();
		$response['success'] = false;

		if(isset($_POST['_csrf']) && isset($_POST['q'])) {
			$query = addslashes(trim($_POST['q']));

			if(!empty($query)) {
				$setObject = new \app\models\Set;
				$setList = $setObject->searchQuery($query);

				$set = array();
				if(!empty($setList)) {
					foreach ($setList as $key => $value) {
						$data = array(
							'id' => $value['id'],
							'title' => stripslashes($value['title']),
							'description' => empty($value['description']) ? '' : stripslashes($value['description']),
							'terms' => $value['terms'],
							'url' => \Yii::$app->getUrlManager()->createUrl(['admin/set/view', 'id' => $value['id']]),
						);

						$set[] = $data;
					}
				}

				$response['set'] = $set;
				$response['success'] = true;
			}
		}

		echo json_encode($response);
	}

	public function actionClassadd()
	{
		if(!Yii::$app->request->post()) {
			$this->redirect(Yii::$app->urlManager->createUrl('site/index'));
		}

		$response = array();
		$response['success'] = false;

		if(isset($_POST['_csrf']) && isset($_POST['classId']) && isset($_POST['setId'])) {
			$classId = trim($_POST['classId']);
			$setId = trim($_POST['setId']);

			if(is_numeric($classId) && is_numeric($setId)) {
				$classesObject = new \app\models\ClassSet;
				$classesObject->addRecord($classId, $setId);
				$response['success'] = true;
			}
		}

		echo json_encode($response);
	}

	public function actionClasslist()
	{
		if(!Yii::$app->request->post()) {
			$this->redirect(Yii::$app->urlManager->createUrl('site/index'));
		}

		$response = array();
		$response['success'] = false;

		if(isset($_POST['_csrf']) && isset($_POST['classId'])) {
			$classId = trim($_POST['classId']);

			if(is_numeric($classId)) {
				$classSetObject = new \app\models\ClassSet;
				$setList = $classSetObject->getRecords($classId);

				$set = array();
				if(!empty($setList)) {
					foreach ($setList as $key => $value) {
						$data = array(
							'id' => $value['id'],
							'title' => $value['title'],
							'description' => empty($value['description']) ? '' : $value['description'],
							'terms' => $value['terms'],
							'url' => \Yii::$app->getUrlManager()->createUrl(['admin/set/view', 'id' => $value['id']]),
						);

						$set[] = $data;
					}

					$response['set'] = $set;
					$response['success'] = true;
				}
			}
		}

		echo json_encode($response);
	}

	public function actionClassremove()
	{
		if(!Yii::$app->request->post()) {
			$this->redirect(Yii::$app->urlManager->createUrl('site/index'));
		}

		$response = array();
		$response['success'] = false;

		if(isset($_POST['_csrf']) && isset($_POST['classId']) && isset($_POST['setId'])) {
			$classId = trim($_POST['classId']);
			$setId = trim($_POST['setId']);

			if(is_numeric($classId) && is_numeric($setId)) {
				$classesObject = new \app\models\ClassSet;
				$classesObject->deleteRecord($classId, $setId);
				$response['success'] = true;
			}
		}

		echo json_encode($response);
	}

	public function actionFolderadd()
	{
		if(!Yii::$app->request->post()) {
			$this->redirect(Yii::$app->urlManager->createUrl('site/index'));
		}

		$response = array();
		$response['success'] = false;

		if(isset($_POST['_csrf']) && isset($_POST['folderId']) && isset($_POST['setId'])) {
			$folderId = trim($_POST['folderId']);
			$setId = trim($_POST['setId']);

			if(is_numeric($folderId) && is_numeric($setId)) {
				$setFolderObject = new \app\models\SetFolder;
				$setFolderObject->deleteRecord($folderId, $setId);
				$setFolderObject->addRecord($folderId, $setId);
				$response['success'] = true;
			}
		}

		echo json_encode($response);
	}

	public function actionFolderlist()
	{
		if(!Yii::$app->request->post()) {
			$this->redirect(Yii::$app->urlManager->createUrl('site/index'));
		}

		$response = array();
		$response['success'] = false;

		if(isset($_POST['_csrf']) && isset($_POST['setId'])) {
			$setId = trim($_POST['setId']);
			if(is_numeric($setId)) {
				$folderList = $this->getFolderSet($setId);

				$folder = array();
				if(!empty($folderList)) {
					foreach ($folderList as $key => $value) {
						$data = array(
							'id' => $value['id'],
							'name' => $value['name'],
							'description' => empty($value['description']) ? '' : $value['description'],
							'url' => \Yii::$app->getUrlManager()->createUrl(['admin/folders/view', 'id' => $value['id']]),
						);

						$folder[] = $data;
					}
				}

				$response['folder'] = $folder;
				$response['success'] = true;
			}
		}

		echo json_encode($response);
	}

	public function actionFolderremove()
	{
		if(!Yii::$app->request->post()) {
			$this->redirect(Yii::$app->urlManager->createUrl('site/index'));
		}

		$response = array();
		$response['success'] = false;

		if(isset($_POST['_csrf']) && isset($_POST['folderId']) && isset($_POST['setId'])) {
			$folderId = trim($_POST['folderId']);
			$setId = trim($_POST['setId']);

			if(is_numeric($folderId) && is_numeric($setId)) {
				$setFolderObject = new \app\models\SetFolder;
				$setFolderObject->deleteRecord($folderId, $setId);
				$response['success'] = true;
			}
		}

		echo json_encode($response);
	}

	public function actionFolder()
	{
		if(!Yii::$app->request->post()) {
			$this->redirect(Yii::$app->urlManager->createUrl('site/index'));
		}

		$response = array();
		$response['success'] = false;

		if(isset($_POST['_csrf']) && isset($_POST['q'])) {
			$query = addslashes(trim($_POST['q']));

			if(!empty($query)) {
				$folderObject = new \app\models\Folder;
				$folderList = $folderObject->searchQuery($query);
				$folder = array();

				if(!empty($folderList)) {
					$setFolderObject = new \app\models\SetFolder;
				
					foreach ($folderList as $key => $value) {
						$setList = $setFolderObject->setList($value['folder_id']);
						
						$set = array();
						$setCount = 0;

						if(!empty($setList)) {
							$setCount = count($setList);
							foreach ($setList as $setKey => $setValue) {
								$setData = array(
									'id' => $setValue['id'],
									'title' => $setValue['title'],
									'description' => empty($setValue['description']) ? '' : $setValue['description'],
									'terms' => $setValue['terms'],
									'url' => \Yii::$app->getUrlManager()->createUrl(['admin/set/view', 'id' => $setValue['id']]),
								);

								$set[] = $setData;
							}
						}

						$data = array(
							'name' => $value['folder_name'],
							'id' => $value['folder_id'],
							'set' => $set,
							'setCount' => $setCount,
						);

						$folder[] = $data;
					}
				}

				$response['folder'] = $folder;
				$response['success'] = true;
			}
		}

		echo json_encode($response);
	}

	public function actionSearchclass()
	{
		if(!Yii::$app->request->post()) {
			$this->redirect(Yii::$app->urlManager->createUrl('site/index'));
		}

		$response = array();
		$response['success'] = false;

		if(isset($_POST['_csrf']) && isset($_POST['q'])) {
			$query = addslashes(trim($_POST['q']));

			if(!empty($query)) {
				$classsObject = new \app\models\Classes;
				$classList = $classsObject->searchQuery($query);
				$class = array();

				if(!empty($classList)) {
					foreach ($classList as $key => $value) {

						$data = array(
							'name' => $value['name'],
							'id' => $value['id'],
						);

						$class[] = $data;
					}
				}

				$response['setClass'] = $class;
				$response['success'] = true;
			}
		}

		echo json_encode($response);
	}

	public function actionSetimage()
	{
		if(!Yii::$app->request->post()) {
			$this->redirect(Yii::$app->urlManager->createUrl('site/index'));
		}

		$response = array();
		$response['status'] = false;
		$response['message'] = 'Invalid image';

		if(isset($_FILES["set-terms-image"])) {

			$finfo = finfo_open(FILEINFO_MIME_TYPE);
			$mime = finfo_file($finfo, $_FILES['set-terms-image']['tmp_name']);
			$profilePictureAllowedExtension = Yii::$app->params['user']['profilePictureAllowedExtension'];

			if(in_array($mime, $profilePictureAllowedExtension)) {
				$imageTemp = new \app\models\SetImagesTemp;
				$userObject = new \app\models\User;

				$extension = str_replace('image/', '.', $_FILES['set-terms-image']['type']);
				$filename = $userObject->randomCharacters(15, 100);
				$key = $userObject->randomCharacters(10, 45);
				$foldername = date('mdY');

				$path = $this->imagePath(Yii::$app->params['setImagePath'] . DIRECTORY_SEPARATOR . $foldername);
				$filePath = $path . DIRECTORY_SEPARATOR . $filename . $extension;
				move_uploaded_file($_FILES["set-terms-image"]["tmp_name"], $filePath);

				$thisPath = '/set/' . $foldername . '/' . $filename . $extension;

				$imageTemp->addRecord($thisPath, $key);

				$response['status'] = true;
				$response['url'] = Yii::$app->params['url']['static'] . $thisPath;
				$response['key'] = $key;
				$response['message'] = 'Image uploaded, click to change';
			}
		}

		echo json_encode($response);
	}
}