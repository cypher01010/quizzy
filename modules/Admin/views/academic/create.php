<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Create Academic Level';
?>
<div class="academic-level-create">

	<h1><?= Html::encode($this->title) ?></h1>

	<div class="academic-level-form">

		<?php $form = ActiveForm::begin(); ?>

			<?= $form->field($model, 'academic')->textInput(['maxlength' => 32]) ?>

			<?= $form->field($model, 'selectable')->dropDownList([ 'yes' => 'Yes', 'no' => 'No', ], ['prompt' => '']) ?>

			<div class="form-group">
				<?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
			</div>

		<?php ActiveForm::end(); ?>

	</div>

</div>