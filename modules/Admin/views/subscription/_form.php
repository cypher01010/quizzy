<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Subscription */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="subscription-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 128]) ?>

    <?= $form->field($model, 'name_keyword')->textInput(['maxlength' => 32]) ?>

    <?= $form->field($model, 'user_type')->dropDownList([ 'trial-user' => 'Trial-user', 'student' => 'Student', 'teacher' => 'Teacher', 'parent' => 'Parent', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'number_set')->textInput() ?>

    <?= $form->field($model, 'price')->textInput() ?>

    <?= $form->field($model, 'duration_days')->textInput() ?>

    <?= $form->field($model, 'keyword')->textInput(['maxlength' => 32]) ?>

    <?= $form->field($model, 'status')->dropDownList([ 'active' => 'Active', 'inactive' => 'Inactive', ], ['prompt' => '']) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
