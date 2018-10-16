<div class="vertical-top">
	<a href="<?php echo \Yii::$app->getUrlManager()->createUrl(['set/default/user', 'username' => $username]); ?>">
		<button type="button" class="btn btn-quizzy btn-icon btn-icon-standalone text-left">
			<i class="fa-book"></i>
			<span>Set</span>
		</button>
	</a>
	<a href="<?php echo \Yii::$app->getUrlManager()->createUrl(['classes/default/user', 'username' => $username]); ?>">
		<button type="button" class="btn btn-quizzy btn-icon btn-icon-standalone text-left">
			<i class="fa-group"></i>
			<span>Class</span>
		</button>
	</a>
	<a href="<?php echo \Yii::$app->getUrlManager()->createUrl(['folder/view/user', 'username' => $username]); ?>">
		<button type="button" class="btn btn-quizzy btn-icon btn-icon-standalone text-left">
			<i class="fa-folder"></i>
			<span>Folders</span>
		</button>
	</a>
</div>