
<div class="user-info-sidebar">
	<a href="<?php echo \Yii::$app->getUrlManager()->createUrl(['user/profile/index', 'username' => $username]); ?>" class="user-img">
		<img src="<?php echo \Yii::$app->params['url']['static'] . $profilePicture; ?>" alt="<?php echo $username; ?>" class="img-cirlce img-responsive img-thumbnail profile-picture-sidebar">
	</a>
	<a href="<?php echo \Yii::$app->getUrlManager()->createUrl(['user/profile/index', 'username' => $username]); ?>" class="user-name">
		<span class="user-name-info-sidebar">
			<?php echo $info['name']; ?>
		</span>
		<?php echo $onlineStatus; ?>
	</a>
	<span class="user-title"><strong><?php echo ucwords(str_replace('-', ' ', $usertype)); ?></strong></span>
	<hr />
	<?php if($hasInfo == true) { ?>
		<ul class="list-unstyled user-info-list">
			<li>
				<i class="fa-calendar"></i> 
				<a href="javascript:;">
					<span class="fa-info-circle" data-toggle="tooltip" data-placement="right" title="Date Joined" data-original-title="Date Joined"></span>
				</a>
				<?php echo $info['dateJoined']; ?> 
			</li>
			<li>
				<i class="fa-birthday-cake"></i> 
				<a href="javascript:;">
					<span class="fa-info-circle" data-toggle="tooltip" data-placement="right" title="Birth Day" data-original-title="Birth Day"></span>
				</a>
				<?php echo  $info['birthDay']; ?> 
				/ 
				<a href="javascript:;">
					<span class="fa-info-circle" data-toggle="tooltip" data-placement="right" title="Age" data-original-title="Age"></span>
				</a>
				<?php echo  $info['age']; ?> Yrs Old
			</li>
			<li>
				<i class="fa-graduation-cap"></i> 
				<a href="javascript:;">
					<span class="fa-info-circle" data-toggle="tooltip" data-placement="right" title="Academic Level" data-original-title="Academic Level"></span>
				</a>
				<?php echo $info['academicLevel']; ?> 
				/ 
				<a href="javascript:;">
					<span class="fa-info-circle" data-toggle="tooltip" data-placement="right" title="School Type" data-original-title="School Type"></span>
				</a>
				<?php echo $info['schoolType']; ?>
			</li>
			<li>
				<i class="fa-university"></i> 
				<a href="javascript:;">
					<span class="fa-info-circle" data-toggle="tooltip" data-placement="right" title="School Name" data-original-title="School"></span>
				</a>
				<?php echo  $info['schoolName']; ?>
			</li>
		</ul>
	<?php } ?>
</div>





<?php if($loginUser == true && $displaySidebarNavigations == true) { ?>
	<hr />
	<?php if($usertype === \app\models\User::USERTYPE_TEACHER && is_array($class) && empty($class)) { ?>
		<?php $spaceBottomChecker['createClass'] = true; ?>
	<?php } ?>

	<?php if(!empty(Yii::$app->session->get('id')) && Yii::$app->session->get('type') !== \app\models\User::USERTYPE_TRIAL) { ?>
		<?php if(is_array($folders) && empty($folders)) { ?>
			<div class="user-info-sidebar">
				<a href="javascript:;" onclick="jQuery('#modal-new-folder').modal('show', {backdrop: 'static'});">
					<button type="button" class="btn btn-quizzy btn-block text-left btn-icon btn-icon-standalone">
						<i class="fa-folder"></i>
						<span>Create Folder</span>
					</button>
				</a>
			</div>
			<?php $spaceBottomChecker['createFolder'] = true; ?>
		<?php } ?>
	<?php } ?>

	<?php if(is_array($set) && !empty($set)) { ?>
		<div class="xe-widget xe-todo-list sidebar-content-container">
			<div class="xe-header">
				<div class="xe-label header-label">
					<strong><a href="<?php echo \Yii::$app->getUrlManager()->createUrl(['set/default/user', 'username' => $username]); ?>">Set</a></strong>
				</div>
			</div>
			<div class="xe-body">
				<ul class="list-unstyled">
					<?php foreach ($set as $key => $value) { ?>
						<div class="xe-map-data">
							<li><label><span><a href="<?php echo \Yii::$app->getUrlManager()->createUrl(['set/default/view', 'id' => $value['id']]); ?>"><i class="fa-book"></i> <?php echo stripslashes($value['title']); ?></a></span></label></li>
						</div>
					<?php } ?>
					<?php if($moreSetLink == true) { ?>
						<li><label><span><a href="<?php echo \Yii::$app->getUrlManager()->createUrl(['set/default/user', 'username' => $username]); ?>">more ...</a></span></label></li>
					<?php } ?>
				</ul>
			</div>
		</div>
	<?php } ?>

	<?php if(is_array($class) && !empty($class)) { ?>
		<div class="xe-widget xe-todo-list sidebar-content-container">
			<div class="xe-header">
				<div class="xe-label header-label">
					<strong><a href="<?php echo \Yii::$app->getUrlManager()->createUrl(['classes/default/user', 'username' => $username]); ?>">Class</a></strong>
				</div>
			</div>
			<div class="xe-body">
				<ul class="list-unstyled">
					<?php foreach ($class as $key => $value) { ?>
						<div class="xe-map-data">
							<li><label><span><a href="<?php echo \Yii::$app->getUrlManager()->createUrl(['classes/default/view', 'id' => $value['id']]); ?>"><i class="fa-group"></i> <?php echo stripslashes($value['name']); ?></a></span></label></li>
						</div>
					<?php } ?>
					<?php if($moreClassLink == true) { ?>
						<li><label><span><a href="<?php echo \Yii::$app->getUrlManager()->createUrl(['classes/default/user', 'username' => $username]); ?>">more ...</a></span></label></li>
					<?php } ?>
				</ul>
			</div>
		</div>
	<?php } else { ?>
		<?php $spaceBottomChecker['createClass'] = true; ?>
	<?php } ?>

	<?php if(is_array($folders) && !empty($folders)) { ?>
		<div class="xe-widget xe-todo-list sidebar-content-container">
			<div class="xe-header">
				<div class="xe-label header-label">
					<strong><a href="<?php echo \Yii::$app->getUrlManager()->createUrl(['folder/view/user', 'username' => $username]); ?>">Folders</a></strong>
				</div>
			</div>
			<div class="xe-body">
				<ul class="list-unstyled">
					<?php foreach ($folders as $key => $value) { ?>
						<div class="xe-map-data">
							<li><label><span><a href="<?php echo \Yii::$app->getUrlManager()->createUrl(['folder/view/info', 'username' => $username, 'keyword' => $value['keyword']]); ?>"><i class="fa-folder"></i> <?php echo $value['name']; ?></a></span></label></li>
						</div>
					<?php } ?>
					<?php if($moreFolderLink == true) { ?>
						<li><label><span><a href="<?php echo \Yii::$app->getUrlManager()->createUrl(['folder/view/user', 'username' => $username]); ?>">more ...</a></span></label></li>
					<?php } ?>
				</ul>
			</div>
			<div class="xe-footer">
				<a href="javascript:;" onclick="jQuery('#modal-new-folder').modal('show', {backdrop: 'static'});">
					<button type="button" class="btn btn-quizzy btn-block text-left btn-icon btn-icon-standalone">
						<i class="fa-folder"></i>
						<span>Create Folder</span>
					</button>
				</a>
			</div>
		</div>
	<?php } ?>

	<?php if($spaceBottomChecker['createClass'] == true && $spaceBottomChecker['createFolder'] == true) { ?>
		<div class="xe-widget xe-todo-list empty-sidebar-space">&nbsp;</div>
	<?php } ?>
<?php } else { ?>
	<?php if($displayingProfilePublic === \app\models\User::PROFILE_DISPLAY_PUBLIC && $usertype !== \app\models\User::USERTYPE_TRIAL ) { ?>
		<hr />
		<div class="user-info-sidebar">
			<a href="<?php echo \Yii::$app->getUrlManager()->createUrl(['classes/default/user', 'username' => $username]); ?>">
				<button type="button" class="btn btn-quizzy btn-block text-left btn-icon btn-icon-standalone">
					<i class="fa-group"></i>
					<span>Class</span>
				</button>
			</a>
		</div>
	<?php } ?>
<?php } ?>