<?php $staticUrl = Yii::$app->params['url']['static']; ?>
<?php if($validateValue['result']['result'] == 1) { ?>
	<label class="display-answer-content">
		<div id="display-answer-<?php echo $validateValue['result']['id']; ?>">
			<blockquote class="blockquote blockquote-info">
				<p>
					<?php
						$question = $validateValue['result']['question'];
						$answer = $validateValue['result']['answer'];
						if($type == 'bool') {
							$compare = $validateValue['result']['answer'];
							if(is_array($validateValue['result']['guest']) && !empty($validateValue['result']['guest'])) {
								$compare = $validateValue['result']['guest']['definition'];
							}
							$question = $validateValue['result']['question'] . ' <span class="fa fa-long-arrow-right"></span> ' . $compare;
							$answer = ucfirst(strtolower($validateValue['result']['correctAnswer']));
						}
					?>
					<?php
						$validateValue['result']['image'] = trim($validateValue['result']['image']);
						if($validateValue['result']['image'] !== '') {
							echo '<div><img src="' . $staticUrl . $validateValue['result']['image'] . '" width="240" alt="' . $validateValue['result']['question'] . '" title="' . $validateValue['result']['question'] . '" /></div>';
						}
					?>
					<div><?php echo $question; ?></div>
					<div class="answer"><span>ANSWER</span>: <?php echo $answer; ?></div>
					<div>&nbsp; You got it right!</div>
				</p>
			</blockquote>
		</div>
	</label>
<?php } else { ?>
	<label class="display-answer-content">
		<div id="display-answer-<?php echo $validateValue['result']['id']; ?>">
			<blockquote class="blockquote blockquote-red">
				<p>
					<?php
						$incorrectMessage = '';
						$answer = $validateValue['result']['answer'];
						if($validateValue['result']['input'] == '') {
							$incorrectMessage = 'No answer';
							if($type == 'bool') {
								$answer = ucfirst(strtolower($validateValue['result']['correctAnswer']));
							}
						} else {
							switch ($type) {
								case 'written':
									$incorrectMessage = 'You answer ' . $validateValue['result']['input'];
									break;
								case 'matching':
									$incorrectMessage = 'Incorrect answer';
									break;
								case 'multiple-choice':
									$incorrectMessage = 'You answer ' . $validateValue['result']['input'];
									break;
								case 'bool':
									$incorrectMessage = 'You answer ' . ucfirst(strtolower($validateValue['result']['input']));
									$answer = ucfirst(strtolower($validateValue['result']['correctAnswer']));
									break;
							}
						}
						$question = $validateValue['result']['question'];
						if($type == 'bool') {
							$compare = $validateValue['result']['answer'];
							if(is_array($validateValue['result']['guest']) && !empty($validateValue['result']['guest'])) {
								$compare = $validateValue['result']['guest']['definition'];
							}
							$question = $validateValue['result']['question'] . ' <span class="fa fa-long-arrow-right"></span> ' . $compare;
						}
					?>
					<?php
						$validateValue['result']['image'] = trim($validateValue['result']['image']);
						if($validateValue['result']['image'] !== '') {
							echo '<div><img src="' . $staticUrl . $validateValue['result']['image'] . '" width="240" alt="' . $validateValue['result']['question'] . '" title="' . $validateValue['result']['question'] . '" /></div>';
						}
					?>
					<div><?php echo $question; ?></div>
					<div class="incorrect"><span>INCORRECT</span>: <?php echo $incorrectMessage; ?></div>
					<div class="answer"><span>ANSWER</span>: <?php echo $answer; ?></div>
				</p>
			</blockquote>
		</div>
	</label>
<?php } ?>