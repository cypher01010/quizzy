<?php
$expiration = '';
if($folderInfo['expiration_date'] <= -1) {
	$expiration = 'Free Forever' . ' (' . $folderInfo['status'] . ')';
} else {
	$expiration = date(Yii::$app->params['dateFormat']['display'], strtotime($folderInfo['expiration_date'])) . ' (' . $folderInfo['status'] . ')';
}
?>
<section class="profile-env">
	<div class="row">
		<div class="col-sm-3">
			<?php echo $this->render('//../modules/User/views/etc/sidebar', array(
				'username' => $username,
				'usertype' => $usertype,
				'loginUser' => $loginUser,
				'profilePicture' => $profilePicture,
				'displaySidebarNavigations' => $displaySidebarNavigations,
				'online' => array('onlineDisplay' => $online['onlineDisplay'], 'onlineStatus' => $online['onlineStatus']),
				'sideBarProfileInfo' => $sideBarProfileInfo,
			)); ?>
		</div>
		<div class="col-sm-9">
			<ul class="list-group list-group-minimal" id="folder-<?php echo $folderInfo['keyword']; ?>">
				<li class="list-group-item">
					<h2>
						<a href="<?php echo \Yii::$app->getUrlManager()->createUrl(['folder/view/info', 'username' => $username, 'keyword' => $folderInfo['keyword']]); ?>">
							<i class="fa-folder"></i> <span id="folder-name-<?php echo $folderInfo['keyword']; ?>"><?php echo $folderInfo['name']; ?></span>
						</a>
						<small>Expiration : <?php echo $expiration; ?></small>
					</h2>
					<span id="folder-description-<?php echo $folderInfo['keyword']; ?>">
						<?php if(!empty($folderInfo['description'])) { ?>
							<p><?php echo $folderInfo['description']; ?></p>
						<?php } ?>
					</span>
					<div>
						<?php foreach ($folderSets as $setKey => $setValue) { ?>
							<div>
								<div>
									<h3><a href="<?php echo \Yii::$app->getUrlManager()->createUrl(['set/default/view', 'id' => $setValue['set_id']]); ?>"><i class="fa-book"></i> <?php echo stripslashes($setValue['title']); ?></a> ( <?php echo $setValue['terms_count']; ?> terms )</h3>
									<?php echo $setValue['from_language']; ?>
									<i class="fa fa-long-arrow-right"></i>
									<?php echo $setValue['to_language']; ?>
								</div>
							</div>
						<?php } ?>
					</div>
				</li>
			</ul>
		</div>
	</div>
</section>