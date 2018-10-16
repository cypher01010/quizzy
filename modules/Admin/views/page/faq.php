<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
?>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <h3>FAQs</h3>
            <div class="panel-body" style="padding-top: 0px;">
                <?php if($updated == true) { ?>
                    <div class="alert alert-default">
                        <button type="button" class="close" data-dismiss="alert">
                            <span aria-hidden="true">×</span>
                            <span class="sr-only">Close</span>
                        </button>
                       FAQs Updated
                    </div>
                <?php } ?>
                <form role="form" method="post" name="page-content-form" id="page-content-form">
                    <div class="form-group">
                        <textarea class="form-control ckeditor" rows="10" name="page-content" id="page-content">
                            <?php echo stripslashes($faq['content']); ?>
                        </textarea>
                    </div>
                    <div class="form-group">
                        <?php echo Html::submitButton('Update', ['class' => 'btn btn-quizzy', 'name' => 'update-page-content']) ?>
                    </div>
                    <input type="hidden" name="<?php echo Yii::$app->request->csrfParam; ?>" value="<?php echo Yii::$app->request->csrfToken; ?>" />
                </form>
            </div>
        </div>
    </div>
</div>