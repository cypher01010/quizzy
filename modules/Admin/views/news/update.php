<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Update News: ' . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'News', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="news-update">

    <h1><?php echo Html::encode($this->title) ?></h1>

    <div class="news-form">

	    <?php $form = ActiveForm::begin(); ?>

		    <?php echo $form->field($model, 'title')->textInput(['maxlength' => 128]) ?>

		    <?php //echo $form->field($model, 'content')->textarea(['rows' => 6]) ?>

		    <div class="form-group">
		    	<h4>Content</h4>
				<textarea class="form-control ckeditor" rows="10" name="News[content]" id="news-content">
					<?php echo $model->content; ?>
				</textarea>
			</div>

		    <?php //echo $form->field($model, 'url')->textInput(['maxlength' => 256]) ?>

		    <?php //echo $form->field($model, 'date_created')->textInput() ?>

		    <?php echo $form->field($model, 'status')->dropDownList($status); ?>

		    <div class="form-group">
		        <?php echo Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
		    </div>

	    <?php ActiveForm::end(); ?>

	</div>

</div>
