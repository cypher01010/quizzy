<?php
use yii\helpers\Html;
use yii\helpers\Url;
$staticUrl = Yii::$app->params['url']['static'];
?> 
<div class="row">
	<div class="col-sm-12">
		<div class="panel panel-default">
			<div class="panel-heading">STUDY AND PLAY</div>
			<?php echo $this->render('/default/study-buttons', array(
				'id' => $id,
			)); ?>
		</div>
	</div>
	<div class="col-sm-12">
		<div class="col-sm-3" id="play-test-sidebar-options">
			<div class="panel panel-default">
				<div class="panel-body">
					<form role="form" method="POST" class="form-horizontal" id="play-test-content-settings" action="<?php echo \Yii::$app->getUrlManager()->createUrl(["set/test/play", "id" => $id]); ?>">
						<div class="form-group">
							<div class="col-sm-2"></div>
							<div class="col-sm-10">
								<div class="form-group">
									<div class="form-block">
										<label><strong>Question</strong></label>
									</div>
									<p>
										<input type="hidden" name="<?php echo \Yii::$app->request->csrfParam; ?>" value="<?php echo \Yii::$app->request->csrfToken; ?>" />
										<div class="checkbox">
											<label><input type="checkbox" name="question[written]" id="question-written" value="written" <?php echo in_array('written', $play) ? 'checked' : NULL; ?> /> Written</label>
										</div>
										<div class="checkbox">
											<label><input type="checkbox" name="question[matching]" id="question-matching" value="matching" <?php echo in_array('matching', $play) ? 'checked' : NULL; ?> /> Matching</label>
										</div>
										<div class="checkbox">
											<label><input type="checkbox" name="question[multiple-choice]" id="question-multiple-choice" value="multiple-choice" <?php echo in_array('multiple-choice', $play) ? 'checked' : NULL; ?> /> Multiple Choice</label>
										</div>
										<div class="checkbox">
											<label><input type="checkbox" name="question[bool]" id="question-bool" value="bool" <?php echo in_array('bool', $play) ? 'checked' : NULL; ?> /> True / False</label>
										</div>
									</p>
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-9">
								<?php echo Html::submitButton('New Test', ['class' => 'btn btn-quizzy', 'name' => 'new-test-play-test-button', 'id' => 'new-test-play-test-button']) ?>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
		<div class="col-sm-9">
			<div class="panel panel-default">
				<div class="panel-body play-test-content">
					<form role="form" class="form-horizontal" id="play-test-content" method="POST" action="<?php echo \Yii::$app->getUrlManager()->createUrl(["set/test/play", "id" => $id]); ?>">
						<?php 
							foreach ($test as $key => $value) {
								if(is_array($value['test']) && (count($value['test']) > 0)) {
									switch ($value['keyword']) {
										case 'written':
											?>
												<div class="form-group">
													<label class="col-sm-3 control-label"><strong><?php echo $value['text']; ?></strong></label>
													<div class="col-sm-9">
														<?php foreach ($value['test'] as $testKey => $testValue) { ?>
															<?php $testValue['image_path'] = trim($testValue['image_path']); ?>
															<div class="play-test-input-choice" id="play-test-input-choice-<?php echo $testValue['id']; ?>">
																<div class="form-block">
																	<?php
																		$image = '';
																		if($testValue['image_path'] !== '') {
																			$image = '<div class="terms-definition-image-display"><img src="' . $staticUrl . $testValue['image_path'] . '" width="240" alt="' . \Yii::$app->controller->desanitize($testValue['term']) . '" title="' . \Yii::$app->controller->desanitize($testValue['term']) . '" /></div>';
																		}
																		echo $image;
																	?>
																	<label><span class="play-test-number" id="written-play-test-number-<?php echo $testValue['id']; ?>" data-id="<?php echo ($testKey + 1); ?>"><?php echo ($testKey + 1); ?> ) </span> <label><?php echo \Yii::$app->controller->desanitize($testValue['term']); ?></label></label>
																</div>
																<p><input type="text" class="form-control" id="<?php echo $value['keyword'] . '-' . $testValue['id']; ?>" name="<?php echo $value['keyword'] . '[' . $value['keyword'] . '-' . $testValue['id'] . ']'; ?>" data-id="<?php echo $testValue['id']; ?>" data-test-type="<?php echo $value['keyword']; ?>" /></p>
															</div>
														<?php } ?>
													</div>
												</div>
												<div class="form-group-separator"></div>
											<?php
											break;
										case 'matching':
												$select = array();
												$options = '<option></option>';
												foreach ($value['alphabetAnswer'] as $alphabetKey => $alphabetValue) {
													$options .= '<option value="' . $alphabetValue . '">' . $alphabetValue . '</option>';
												}
											?>
												<div class="form-group">
													<label class="col-sm-3 control-label"><strong><?php echo $value['text']; ?></strong></label>
													<div class="col-sm-9" id="play-test-matching-content-container">
														<div class="col-sm-6 play-test-matching-selection">
															<div class="form-block play-test-matching-content">
																<?php foreach ($value['test'] as $testKey => $testValue) { ?>
																	<div id="test-matching-content-question-<?php echo $testValue['id']; ?>" name="test-matching-content-question-<?php echo $testValue['id']; ?>" data-id="<?php echo ($testKey + 1); ?>">
																		<select class="form-control" id="<?php echo $value['keyword'] . '-' . $testValue['id']; ?>" name="<?php echo $value['keyword'] . '[' . $value['keyword'] . '-' . $testValue['id'] . ']'; ?>" data-id="<?php echo $testValue['id']; ?>" data-test-type="<?php echo $value['keyword']; ?>">
																			<?php echo $options; ?>
																		</select>
																		<label class="play-test-matching-content-question" id="play-test-matching-content-question-<?php echo $testValue['id']; ?>" name="play-test-matching-content-question-<?php echo $testValue['id']; ?>" data-id="<?php echo $testValue['id']; ?>"><?php echo \Yii::$app->controller->desanitize($testValue['term']); ?></label>
																	</div>
																<?php } ?>
															</div>
														</div>
														<div class="col-sm-6 play-test-matching-choices">
															<div class="form-block play-test-matching-content">
																<?php foreach ($value['answers'] as $answersKey => $answersValue) { ?>
																	<?php $answersValue['image_path'] = trim($answersValue['image_path']); ?>
																	<div>
																		<span class="play-test-matching-content-answer play-test-alphabet"><?php echo $answersValue['alphabet']; ?> ) </span>
																		<label><?php echo \Yii::$app->controller->desanitize($answersValue['definition']); ?></label>
																		<?php
																			$image = '';
																			if($answersValue['image_path'] !== '') {
																				$image = '<div class="terms-definition-image-display"><img src="' . $staticUrl . $answersValue['image_path'] . '" width="240" alt="' . \Yii::$app->controller->desanitize($answersValue['definition']) . '" title="' . \Yii::$app->controller->desanitize($testValue['definition']) . '" /></div>';
																			}
																			echo $image;
																		?>
																	</div>
																<?php } ?>
															</div>
														</div>
													</div>
												</div>
												<div class="form-group-separator"></div>
											<?php
											break;
										case 'multiple-choice':
											?>
												<div class="form-group">
													<label class="col-sm-3 control-label"><strong><?php echo $value['text']; ?></strong></label>
													<div class="col-sm-9">
														<?php foreach ($value['test'] as $testKey => $testValue) { ?>
															<?php $testValue['image_path'] = trim($testValue['image_path']); ?>
															<div class="play-test-multiple-choice" id="play-test-multiple-choice-<?php echo $testValue['id']; ?>">
																<div class="form-block">
																	<?php
																		$image = '';
																		if($testValue['image_path'] !== '') {
																			$image = '<div class="terms-definition-image-display"><img src="' . $staticUrl . $testValue['image_path'] . '" width="240" alt="' . \Yii::$app->controller->desanitize($testValue['term']) . '" title="' . \Yii::$app->controller->desanitize($testValue['term']) . '" /></div>';
																		}
																		echo $image;
																	?>
																	<label><span class="play-test-number" id="multiple-choice-play-test-number-<?php echo $testValue['id']; ?>" data-id="<?php echo ($testKey + 1); ?>"><?php echo ($testKey + 1); ?> ) </span> <label><?php echo \Yii::$app->controller->desanitize($testValue['term']); ?></label>
																</div>
																<p>
																	<?php foreach ($testValue['answers'] as $answerKey => $answerValue) { ?>
																		<div class="radio">
																			<label><input type="radio" id="<?php echo $value['keyword'] . '-' . $testValue['id']; ?>" name="<?php echo $value['keyword'] . '[' . $value['keyword'] . '-' . $testValue['id'] . ']'; ?>" value="<?php echo $answerValue['id']; ?>" data-id="<?php echo $testValue['id']; ?>" data-test-type="<?php echo $value['keyword']; ?>" data-value="<?php echo $answerValue['id']; ?>" data-text="<?php echo \Yii::$app->controller->desanitize($answerValue['definition']); ?>" /> <?php echo \Yii::$app->controller->desanitize($answerValue['definition']); ?></label>
																		</div>
																	<?php } ?>
																</p>
															</div>
														<?php } ?>
													</div>
												</div>
												<div class="form-group-separator"></div>
											<?php
											break;
										case 'bool':
											?>
												<div class="form-group">
													<label class="col-sm-3 control-label"><strong><?php echo $value['text']; ?></strong></label>
													<div class="col-sm-9">
														<?php foreach ($value['test'] as $testKey => $testValue) { ?>
															<?php $testValue['image_path'] = trim($testValue['image_path']); ?>
															<div class="play-test-selection-choice" id="play-test-bool-<?php echo $testValue['id']; ?>">
																<div class="form-block">
																	<?php
																		$textSelectDisplay = \Yii::$app->controller->desanitize($testValue['definition']);
																		if($testValue['selection'] == 0) {
																			$textSelectDisplay = $testValue['answer']['guest']['definition'];
																		}
																	?>
																	<?php
																		if($testValue['image_path'] === '') {
																	?>
																		<label><span class="play-test-number" id="bool-play-test-number-<?php echo $testValue['id']; ?>" data-id="<?php echo ($testKey + 1); ?>"><?php echo ($testKey + 1); ?> ) </span><?php echo \Yii::$app->controller->desanitize($testValue['term']); ?> <span class="fa fa-long-arrow-right"></span> <?php echo \Yii::$app->controller->desanitize($textSelectDisplay); ?></label>
																	<?php
																		} else {
																			$image = '<div class="with-image-bool"><img src="' . $staticUrl . $testValue['image_path'] . '" width="240" alt="' . \Yii::$app->controller->desanitize($testValue['term']) . '" title="' . \Yii::$app->controller->desanitize($testValue['term']) . '" />';
																			$image .= '<br /><span>' . $textSelectDisplay . '</span></div>';
																	?>
																		<label>
																			<span class="play-test-number" id="bool-play-test-number-<?php echo $testValue['id']; ?>" data-id="<?php echo ($testKey + 1); ?>"><?php echo ($testKey + 1); ?> ) </span>
																			<?php echo \Yii::$app->controller->desanitize($testValue['term']); ?> 
																			<span class="fa fa-long-arrow-right"></span>
																			<?php echo $image; ?>
																		</label>
																	<?php } ?>
																</div>
																<p>
																	<label class="radio-inline"><input type="radio" id="<?php echo $value['keyword'] . '-' . $testValue['id'] . '-true'; ?>" name="<?php echo $value['keyword'] . '[' . $value['keyword'] . '-' . $testValue['id'] . ']'; ?>" value="true" data-id="<?php echo $testValue['id']; ?>" data-test-type="<?php echo $value['keyword']; ?>"> True</label>
																	<label class="radio-inline"><input type="radio" id="<?php echo $value['keyword'] . '-' . $testValue['id'] . '-false'; ?>" name="<?php echo $value['keyword'] . '[' . $value['keyword'] . '-' . $testValue['id'] . ']'; ?>" value="false" data-id="<?php echo $testValue['id']; ?>" data-test-type="<?php echo $value['keyword']; ?>"> False</label>
																</p>
															</div>
														<?php } ?>
													</div>
												</div>
												<div class="form-group-separator"></div>
												<input type="hidden" name="<?php echo \Yii::$app->request->csrfParam; ?>" value="<?php echo \Yii::$app->request->csrfToken; ?>" />
											<?php
											break;
									}
								}
							}
						?>
						<div class="form-group">
							<div class="col-sm-9 btn-submit-group">
								<?php echo Html::submitButton('Submit', ['class' => 'btn btn-quizzy', 'name' => 'submit-play-test-button', 'id' => 'submit-play-test-button']) ?>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
jQuery(document).ready(function($) {
	playTest(<?php echo $id; ?>, '<?php echo \Yii::$app->getUrlManager()->createUrl("set/test/submit"); ?>', '<?php echo \Yii::$app->getUrlManager()->createUrl(["set/test/play", "id" => $id]); ?>');
});
</script>