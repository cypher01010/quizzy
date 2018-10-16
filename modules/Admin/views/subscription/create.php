<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $model app\models\Subscription */

$this->title = 'Create Subscription';
?>

<div class="subscription-create">
	<h1><?= Html::encode($this->title) ?></h1>
	<div class="subscription-form">
		<?php $form = ActiveForm::begin(); ?>

		<?= $form->field($createSubscriptionForm, 'name')->textInput(['maxlength' => 128]) ?>

		<?= $form->field($createSubscriptionForm, 'price')->textInput() ?>

		<?= $form->field($createSubscriptionForm, 'duration')->textInput() ?>

		<?= $form->field($createSubscriptionForm, 'status')->dropDownList([ 'active' => 'Active', 'inactive' => 'Inactive', ], ['prompt' => '']) ?>

		<div class="form-group">
			<?= Html::submitButton('Create Subscription', ['class' => 'btn btn-success']) ?>
		</div>

		<?php ActiveForm::end(); ?>

	</div>
</div>