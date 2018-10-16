<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

\Yii::$app->controller->setInnerPageActive(array('key' => 'error', 'text' => $exception->statusCode . ' ERROR'));
?>
<div class="site-error">

	<center>
		<h1 class="error-code"><?php echo $exception->statusCode; ?></h1>
	</center>

</div>