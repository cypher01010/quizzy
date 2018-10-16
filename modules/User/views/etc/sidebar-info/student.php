<?php
$displayName = $info['username'];
if(!empty($info['name'])) {
	$displayName = $info['name'];
}

$picture = \Yii::$app->params['url']['static'] . $info['profilePicture'];
?>
<?php if($loginUser == true && $displaySidebarNavigations == true) { ?>

	<div class="user-info-sidebar">
		<a href="<?php echo \Yii::$app->getUrlManager()->createUrl(['user/profile/index', 'username' => $username]); ?>" class="user-img">
			<img src="<?php echo $picture; ?>" alt="<?php echo $username; ?>" class="img-cirlce img-responsive img-thumbnail profile-picture-sidebar" title="<?php echo $username; ?>" />
		</a>
		<a href="<?php echo \Yii::$app->getUrlManager()->createUrl(['user/profile/index', 'username' => $username]); ?>" class="user-name">
			<span class="user-name-info-sidebar">
				<?php echo $displayName; ?>
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

	<hr />

	<?php if(is_array($set) && !empty($set)) { ?>
		<div class="xe-widget xe-todo-list sidebar-content-container">
			<div class="xe-header">
				<div class="xe-label header-label">
					<strong><a href="<?php echo \Yii::$app->getUrlManager()->createUrl(['set/default/user', 'username' => $username]); ?>">Study Sets</a></strong>
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
		</div>
	<?php } ?>

<?php } else { ?>
	<?php
		$picture = \Yii::$app->params['url']['static'] . "/images/profile/user.png";

		if($info['profilePublic'] === 'yes') {
			$picture = \Yii::$app->params['url']['static'] . $info['profilePicture'];
		}
	?>

	<div class="user-info-sidebar">
		<a href="<?php echo \Yii::$app->getUrlManager()->createUrl(['user/profile/index', 'username' => $username]); ?>" class="user-img">
			<img src="<?php echo $picture; ?>" alt="<?php echo $username; ?>" class="img-cirlce img-responsive img-thumbnail profile-picture-sidebar" title="<?php echo $username; ?>" />
		</a>
		<a href="<?php echo \Yii::$app->getUrlManager()->createUrl(['user/profile/index', 'username' => $username]); ?>" class="user-name">
			<span class="user-name-info-sidebar">
				<?php
					if($info['profilePublic'] === 'yes') {
						echo $displayName;
					} else {
						echo $info['username'];
					}
				?>
			</span>

			<?php
				if($info['profilePublic'] === 'yes') {
					echo $onlineStatus;
				}
			?>
		</a>
		<?php if($info['profilePublic'] === 'yes') { ?>
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
		<?php  } ?>
	</div>
<?php } ?>