<?php
use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'Sets';
?>
<div class="admin-sets-list">
	<h1><?= Html::encode($this->title) ?></h1>
	<p>
		<?= Html::a('New Study Set', ['add'], ['class' => 'btn btn-success']) ?>
	</p>
	<?php echo GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel' => $searchModel,
		'columns' => [
			[
				'header' => '',
				'attribute' => 'title',
				'format' => 'raw',
				'value' => function($data) {
					$terms = count($data->set_answer);
					if($terms == 0) {
						$terms = NULL;
					} else {
						$terms = $terms . ' terms';
					}
					$terms = '<small class="text-left">' . $terms . '</small>';

					$href = \Yii::$app->getUrlManager()->createUrl(['admin/set/view', 'id' => $data->id]);

					$title = '<div class="terms-list-table"><h3><a href="' . $href . '">' . stripslashes($data->title) . '</a>' . $terms . '</h3></div>';

					$description = stripslashes($data->description);

					$editLink = '<a href="' . \Yii::$app->getUrlManager()->createUrl(['admin/set/edit', 'id' => $data->id]) . '"><span class="fa-edit"></span>&nbsp;Edit</a>';
					$deleteLink = '<a class="deleteClass" href="javascript:void(0)" id="'. $data->id .'"><span class="fa-remove"></span>&nbsp;Delete</a>';
					$editDeleteLink = '<div class="edit-delete-link-list">' . $editLink . '&nbsp;|&nbsp;' . $deleteLink . '</div>';

					$languages = \Yii::$app->controller->getLanguageByid($data->term_set_language_id) . ' to ' . \Yii::$app->controller->getLanguageByid($data->definition_set_language_id);

					$html = $editDeleteLink . $title . '<p>' . $languages . '</p>' . '<p>' . $description . '</p>';

					$folders = \Yii::$app->controller->getFolderSet($data->id);
					if(count($folders) > 0) {
						$html .= '<hr /><div>Folder Listed</div><br />';

						foreach ($folders as $key => $folder) {
							$html .= '<div>';
								$html .= '<a class="set-folder-listed" href="' . \Yii::$app->getUrlManager()->createUrl(['admin/folders/view', 'id' => $folder['id']]) . '" title="View">' . $folder['name'] . '</a>';
							$html .= '</div>';
						}
					}

					return $html;
				},
			],
		],
	]); ?>
</div>
<script type="text/javascript">

$( document ).ready(function() {
    $('.admin-sets-list table > thead > tr:first').remove();

    $('.deleteClass').click(function() {
    	set_id = parseInt($(this).attr("id"));
       	var rownum = parseInt($(this).closest("tr").index()) + 1;
       	jQuery('#delete-set').modal('show');
       
    });

    $('#btn-delete').click(function() {
        jQuery.ajax({
			type : "POST",
			url : '<?php echo \Yii::$app->getUrlManager()->createUrl('admin/set/deleteset'); ?>',
			cache : true,
			data : {
				set_id : set_id,
				_csrf : $('meta[name="csrf-token"]').attr("content")
			},
			dataType:'json',
			success: function(response) {
				if(response.success == true) {
	        		//$("table tr:eq("+ rownum +")").fadeOut();
	        		location.reload(); 
				} 
			}
		});
    });

});

</script>
