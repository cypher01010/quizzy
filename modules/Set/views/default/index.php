<section class="profile-env">
	<div class="row">
		<div class="col-sm-3">
			<?php echo $this->render('/etc/sidebar', array(
				'username' => $username,
				'usertype' => $usertype,
				'loginUser' => $loginUser,
				'profilePicture' => $profilePicture,
				'displaySidebarNavigations' => $displaySidebarNavigations,
			)); ?>
		</div>

		<div class="col-sm-9">
			<?php echo $this->render('/etc/profile-link', array(
				'username' => $username,
			)); ?>

			<section class="user-timeline-stories">
				<article class="timeline-story">

				</article>
			</section>
		</div>
	</div>
</section>