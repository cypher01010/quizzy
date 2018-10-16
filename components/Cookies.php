<?php
namespace app\components;

class Cookies
{
	public function hasCookie($name)
	{
		$cookies = \Yii::$app->request->cookies;
		return $cookies->getValue($name);
	}

	public function getCookie($name)
	{
		$cookies = \Yii::$app->request->cookies;
		return $cookies->getValue($name);
	}

	public static function setCookie($name, $value)
	{
		$cookies = \Yii::$app->response->cookies;
		$thisCookies = new \yii\web\Cookie(['name' => $name, 'value' => $value]);
		$thisCookies->expire =  time() + (86400 * 365 * 1);
		$cookies->add($thisCookies);
	}

	public static function clearCookies($name)
	{
		\Yii::$app->response->cookies->remove($name);
	}

	public static function encrypt($data)
	{
		return sha1($data);
	}
}