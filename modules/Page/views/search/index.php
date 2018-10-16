<?php
use yii\helpers\Html;
use yii\grid\GridView;
?>
<section class="search-env">
	<div class="row">
		<div class="col-md-12">
			<form method="post" action="" enctype="application/x-www-form-urlencoded">
				<input type="text" class="form-control input-lg" placeholder="Search..." name="q" id="q" />
				<input type="hidden" name="_csrf" value="<?php echo $this->renderDynamic('return Yii::$app->request->csrfToken;'); ?>" />
				<button type="submit" class="btn-unstyled">
					<i class="linecons-search"></i>
				</button>
			</form>
			<?php //if(!empty($query)) { ?>
				<div class="search-results">
					<div class="tabs-vertical-env">
						<div class="tab-content">
							<div class="tab-pane active" id="set-result">
								<h2>
									Search results for <span class="text-success"><?php echo $query; ?></span>
								</h2>
								<div class="search-content">
									<?php echo GridView::widget([
										'dataProvider' => $dataProvider,
										//'filterModel' => $searchModel,
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

													$href = \Yii::$app->getUrlManager()->createUrl(['set/default/view', 'id' => $data->id]);

													$title = '<div class="terms-list-table"><h3><a href="' . $href . '">' . stripslashes($data->title) . '</a>' . $terms . '</h3></div>';

													$html = $title  . '<p>' . stripslashes($data->description) . '</p>';
													return $html;
												},
											],
										],
									]); ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			<?php //} ?>
		</div>
	</div>
</section>
<script type="text/javascript">
$( document ).ready(function() {
	$('.search-content table > thead > tr:first').remove();
});
</script>