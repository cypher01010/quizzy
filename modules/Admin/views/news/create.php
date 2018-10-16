<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\News */

$this->title = 'Create News';
$this->params['breadcrumbs'][] = ['label' => 'News', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="news-create">

    <h1><?php echo Html::encode($this->title) ?></h1>

    <div class="news-form">

	    <?php $form = ActiveForm::begin(); ?>

	    <?php echo $form->field($model, 'title')->textInput(['maxlength' => 128]) ?>

	    <div class="form-group">
	    	<h4>Content</h4>
			<textarea class="form-control ckeditor" rows="10" name="News[content]" id="news-content">
				<?php echo $model->content; ?>
			</textarea>
		</div>

	    <?php //$form->field($model, 'url')->textInput(['maxlength' => 256]) ?>

	    <?php //$form->field($model, 'date_created')->textInput() ?>

	    <?php echo $form->field($model, 'status')->dropDownList($status); ?>

	    <div class="form-group">
	        <?php echo Html::submitButton('Create', ['class' => 'btn btn-success']) ?>
	    </div>

	    <?php ActiveForm::end(); ?>

	</div>

</div>
