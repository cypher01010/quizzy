<?php
$staticUrl = \Yii::$app->params['url']['static'];
?>
<section id="" class="page-content section tools">
	<div class="container">
		<div class="row">
			<div class="item col-md-4 col-sm-6 col-xs-12">
				<div class="content text-center">
					<img src="<?php echo $staticUrl; ?>/images/studytools.png" alt="" width="150" height="150" border="0" />
				</div>
				<span class="separator"></span>
				<div class="content text-center">
					<h2>Study Tools</h2>
					<p>Flashcards. Tests. Games. We transform learning into a whole new level.</p>
				</div>
			</div>
			<div class="item col-md-4 col-sm-6 col-xs-12">
				<div class="content text-center">
					<img src="<?php echo $staticUrl; ?>/images/mobileandweb.png" alt="" width="150" height="150" border="0" />
				</div>
				<span class="separator"></span>
				<div class="content text-center">
					<h2>Mobile & Web</h2>
					<p>Use Quizzy on your Computer or Mobile Devices wherever you are, whenever you want to.</p>
				</div>
			</div>
			<div class="item col-md-4 col-sm-6 col-xs-12">
				<div class="content text-center">
					<img src="<?php echo $staticUrl; ?>/images/funandeffective.png" alt="" width="150" height="150" border="0" />
				</div>
				<span class="separator"></span>
				<div class="content text-center">
					<h2>Fun & Effective</h2>
					<p>With an account, you can choose what to learn, track your progress and even compete with your friends.</p>
				</div>
			</div>
		</div>
	</div>
</section>

<section id="" class="page-content section tools">
	<div class="container">
		<div class="row">
			<div class="item col-md-4 col-sm-6 col-xs-12"></div>
			<div class="item col-md-4 col-sm-6 col-xs-12">
				<h2 class="col-md-12 text-center text-info">LOG IN</h2>
				<span class="text-center hr-separator"></span>
			</div>
			<div class="item col-md-4 col-sm-6 col-xs-12"></div>
		</div>
		<div class="row">
			<div class="item col-md-4 col-sm-6 col-xs-12">
				<div class="content text-center">
					<img src="<?php echo $staticUrl; ?>/images/studentlg.png" alt="" width="150" height="150" border="0" />
				</div>
				<span class="separator"></span>
				<div class="content text-center page-login">
					<h2>Students</h2>
					<p>
						<form action="">						
							<div>	
								<div class="btns header-btns">
			<a class="btn btn-cta-secondary header-btns-link" href="<?php echo \Yii::$app->getUrlManager()->createUrl('/user/login/index'); ?>">Login as Student</a>
								</div>	
								<span class="recover-style">
									Forgot Password? Click <a href="<?php echo \Yii::$app->getUrlManager()->createUrl('/user/recover/index'); ?>">here</a>
								</span>
								<br />
								<a href="<?php echo \Yii::$app->getUrlManager()->createUrl('user/register/index'); ?>">Register</a>
							</div>
						</form>
					</p>
				</div>
			</div>
			<div class="item col-md-4 col-sm-6 col-xs-12">
				<div class="content text-center">
					<img src="<?php echo $staticUrl; ?>/images/teacherslg.png" alt="" width="150" height="150" border="0" />
				</div>
				<span class="separator"></span>
				<div class="content text-center page-login">
					<h2>Teachers</h2>
					<p>
						<form action="">
							<div class="btns header-btns">
			<a class="btn btn-cta-secondary header-btns-link" href="<?php echo \Yii::$app->getUrlManager()->createUrl('/user/login/index'); ?>">Login as Teacher</a>
							</div>		
							<div>
								<span class="recover-style">
									Forgot Password? Click <a href="<?php echo \Yii::$app->getUrlManager()->createUrl('/user/recover/index'); ?>">here</a>
								</span>
								<br />
								<a href="<?php echo \Yii::$app->getUrlManager()->createUrl('user/register/index'); ?>">Register</a>
							</div>
						</form>
					</p>
				</div>
			</div>
			<div class="item col-md-4 col-sm-6 col-xs-12">
				<div class="content text-center">
					<img src="<?php echo $staticUrl; ?>/images/freetriallg.png" alt="" width="150" height="150" border="0" />
				</div>
				<span class="separator"></span>
				<div class="content text-center page-login">
					<h2>Free Trial</h2>
					<p>
						<form action="">
							<div class="btns header-btns">
			<a class="btn btn-cta-secondary header-btns-link" href="<?php echo \Yii::$app->getUrlManager()->createUrl('/user/login/index'); ?>">Login as Trial User</a>
							</div>	
							<div>
								<span class="recover-style">
									Forgot Password? Click <a href="<?php echo \Yii::$app->getUrlManager()->createUrl('/user/recover/index'); ?>">here</a>
								</span>
								<br />
								<a href="<?php echo \Yii::$app->getUrlManager()->createUrl('user/register/index'); ?>">Register</a>
							</div>
						</form>
					</p>
				</div>
			</div>
		</div>
	</div>
</section>

<section id="" class="page-content section tools">
	<div class="container">
		<div class="row">
			<div class="item col-md-4 col-sm-6 col-xs-12"></div>
			<div class="item col-md-4 col-sm-6 col-xs-12">
				<h2 class="col-md-12 text-center text-info">TESTIMONIALS</h2>
				<p class="text-center">what clients are saying about us</p>
				<span class="text-center hr-separator hr-separator-testimonial"></span>
			</div>
			<div class="item col-md-4 col-sm-6 col-xs-12"></div>
		</div>
		<div class="row">
			<div class="item col-md-4 col-sm-6 col-xs-12">
				<div class="content text-center">
					<p class="testimonial-details">
						<img src="<?php echo $staticUrl; ?>/images/quotes.png" alt="" class="quotes" />
						Quizzy allows me to learn the difficult vocabulary in English and Chinese in an easy and fun way. I am more confident of learning the difficult words now all thanks to Quizzy.
						<hr />
					</p>
					<p class="client-info">
						Primary 6 Student
					</p>
				</div>
			</div>
			<div class="item col-md-4 col-sm-6 col-xs-12">
				<div class="content text-center">
					<p class="testimonial-details">
						<img src="<?php echo $staticUrl; ?>/images/quotes.png" alt="" class="quotes" />
						Quizzy is an innovative learning application which has helped my students to learn difficult concepts more efficiently. I highly recommend this application to any learner.
						<hr />
					</p>
					<p class="client-info">
						Secondary School Teacher
					</p>
				</div>
			</div>
			<div class="item col-md-4 col-sm-6 col-xs-12">
				<div class="content text-center">
					<p class="testimonial-details">
						<img src="<?php echo $staticUrl; ?>/images/quotes.png" alt="" class="quotes" />
						Quizzy has helped my children to become more effective learners. They are very interested in learning now and I am happy that quizzy is helping them. I would recommend this application to anyone.
						<hr />
					</p>
					<p class="client-info">
						Parent of Primary 5 student
					</p>
				</div>
			</div>
		</div>
	</div>
</section>