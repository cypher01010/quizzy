<?php

namespace app\modules\Set\controllers;

use Yii;

class ScrambleController extends \app\components\BaseController
{
	public function beforeAction($event)
	{
		$this->filterHttps(['*']);

		return parent::beforeAction($event);
	}

	public function actionIndex()
	{
		return $this->render('index');
	}

	public function actionPlay($id)
	{
		$setObject = new \app\models\Set;
		$setInfo = $setObject->getInfo($id);

		if(empty($setInfo)) {
			$this->redirect(Yii::$app->urlManager->createUrl('site/4oh4'));
		}

		$setUserObject = new \app\models\SetUser;
		if(Yii::$app->session->get('type') == \app\models\User::USERTYPE_ADMIN || Yii::$app->session->get('type') == \app\models\User::USERTYPE_SUPER_ADMIN) {
			$isMySet = $setUserObject->isMySet(Yii::$app->session->get('id'), $setInfo['id']);
			if($isMySet == false) {
				$setUserObject->addRecord(Yii::$app->session->get('id'), $setInfo['id'], \app\models\SetUser::STATUS_GRANTED );
			}
		} else {
			$isMySet = $setUserObject->isMySet(Yii::$app->session->get('id'), $setInfo['id']);
			if($isMySet == false) {
				$this->redirect(Yii::$app->urlManager->createUrl(['set/default/view', 'id' => $setInfo['id']]));
			}
		}

		$this->setInnerPageActive(array('key' => 'set', 'text' => stripslashes($setInfo['title'])));

		return $this->render('/html/play-scramble', array(
			'setInfo' => $setInfo,
			'id' => $id,
		));
	}
    
    public function actionSavescore(){
        if(!Yii::$app->request->post()) {
            $this->redirect(Yii::$app->urlManager->createUrl('site/index'));
        }
        
        $setScrambleScore = new \app\models\SetScrambleScore;
        $time = $_POST['time'];
        if(isset($_POST['_csrf']) && isset($_POST['setId']) && isset($_POST['time'])){
            $setScrambleScore->addRecord(
                Yii::$app->session->get('id'),
                $_POST['setId'],
                (string)$time
            );
            
            $leaderboard = $setScrambleScore->leaderboard(Yii::$app->params['set']['maxLeaderboardDisplay'], $_POST['setId']);
            $response['modal']['title'] = 'Scramble Leaderboard';
            $response['modal']['content'] = $this->renderAjax('//etc/modal/content/scramble', array('leaderboard' => $leaderboard, 'time' => $time));
            $response['success'] = true;
        }
        echo json_encode($response);
    }
    
}