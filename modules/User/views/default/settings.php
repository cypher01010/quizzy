<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\BaseUrl;
use yii\helpers\Url;
$tabList = array(
	'tab-settings-upgrade' => 'Upgrade',
	'tab-profile-picture' => 'Profile Picture',
	'tab-fullname' => 'Name',
	//'tab-settings-google-integration' => 'Google Integration',
	//'tab-settings-facebook-integration' => 'Facebook Integration',
	'tab-settings-update-email' => 'Email Address',
	'tab-settings-update-password' => 'Password',
	'tab-settings-online-indicator' => 'Online Indicator',
	'tab-settings-public-profile' => 'Public Profile',
	'tab-settings-school' => 'School',
	'tab-settings-parent' => 'Parent',
	'tab-settings-email-alerts' => 'Alerts',
	'tab-settings-delete-account' => 'Delete Account',
);
if(Yii::$app->session->get('type') !== \app\models\User::USERTYPE_TRIAL) {
	unset($tabList['tab-settings-upgrade']);
}
if(\Yii::$app->session->get('type') !== \app\models\User::USERTYPE_STUDENT) {
	unset($tabList['tab-settings-parent']);
}
if(Yii::$app->session->get('type') === \app\models\User::USERTYPE_PARENT) {
	unset($tabList['tab-settings-google-integration']);
	unset($tabList['tab-settings-facebook-integration']);
	unset($tabList['tab-settings-online-indicator']);
	unset($tabList['tab-settings-public-profile']);
	unset($tabList['tab-settings-school']);
	unset($tabList['tab-settings-email-alerts']);
}
?>
<section class="profile-env">
	<div class="row">
		<div class="col-sm-3">
			<?php echo $this->render('/etc/sidebar', array(
				'username' => \Yii::$app->session->get('username'),
				'usertype' => \Yii::$app->session->get('type'),
				'loginUser' => true,
				'profilePicture' => $profilePicture,
				'displaySidebarNavigations' => $displaySidebarNavigations,
				'online' => array('onlineDisplay' => $online['onlineDisplay'], 'onlineStatus' => $online['onlineStatus']),
				'sideBarProfileInfo' => $sideBarProfileInfo,
			)); ?>
		</div>
		<div class="col-sm-9">
			<div class="tabs-vertical-env">
				<ul class="nav tabs-vertical">
					<?php foreach ($tabList as $key => $value) { ?>
						<?php
							$class = NULL;
							if($key === $tab) {
								$class = 'class="active"';
							}
						?>
						<li <?php echo $class; ?>><a href="#<?php echo $key; ?>" data-toggle="tab"><?php echo $value; ?></a></li>
					<?php } ?>
				</ul>
				<div class="tab-content">
					<?php if(Yii::$app->session->get('type') === \app\models\User::USERTYPE_TRIAL) { ?>
						<div class="tab-pane" id="tab-settings-upgrade">
							<div class="col-sm-12">
								<p>You are currently a <strong><?php echo ucwords(str_replace('-', ' ', \Yii::$app->session->get('type'))); ?></strong>, with access to only 1 learning set. If you would like to access more than 1 learning set, please click on the upgrade button below</p>
								<br />
							</div>
							<div class="col-sm-3 margin-top-20">
								<a class="btn btn-info btn-block" href="<?php echo \Yii::$app->getUrlManager()->createUrl(['subscription/default/index']); ?>">Upgrade</a>
							</div>
						</div>
					<?php } ?>
					<div class="tab-pane" id="tab-profile-picture">
						<?php echo $this->render('/etc/settings-profile-picture', array(
							'profilePicture' => $profilePicture,
							'imagesList' => $imagesList,
							'username' => \Yii::$app->session->get('username'),
							'staticUrl' => \Yii::$app->params['url']['static'],
						)); ?>
					</div>
					<div class="tab-pane" id="tab-fullname">
						<div id="fullnameInfo"></div>
						<h4>Name</h4>
						<div class="form-group">
							<div><input type="text" id="fullname" class="form-control" name="fullname" style="width: 250px" value="<?php echo $fullname; ?>" /></div>
						</div>
						<button onclick="fullname();" type="button" class="btn btn-info">Save</button>
					</div>
					<?php /**if(Yii::$app->session->get('type') !== \app\models\User::USERTYPE_PARENT) { 
						<div class="tab-pane" id="tab-settings-google-integration">
							<p>google integration</p>
						</div>
						<div class="tab-pane" id="tab-settings-facebook-integration">
							<p>facebook integration</p>
						</div>
						//}*/ ?>
					<div class="tab-pane" id="tab-settings-update-email">
						<div id="updateEmailMsg"></div>
						<h4>Update your email address</h4>
						<p id="currentemail">Your email is currently: <?php echo $emailAddress ?></p>
						
						<div class="form-group">
						<label class="control-label">Enter new email address</label>
						<div><input type="text" id="email" class="form-control" name="email" style="width: 250px"></div>
						</div>
						
						<div class="form-group">
						<label class="control-label">Enter your quizzy password</label>
						<input type="password" name="password" class="form-control" id="password" style="width: 250px"></input> 
						</div>
						
						<div>
						<button onclick="updateEmail();" type="button" class="btn btn-info">Submit</button>
						</div>
					</div>
					<div class="tab-pane" id="tab-settings-update-password">
					
						<div id="updatePasswordMsg"></div>
						<h4>Change your password</h4>
												
						<div class="form-group">
						<label class="control-label">Current Password</label>
						<div><input type="password" id="old_password" class="form-control" name="old_password" style="width: 250px"></div>
						</div>
						
						<div class="form-group">
						<label class="control-label">New Password</label>
						<div><input type="password" id="new_password" class="form-control" name="new_password" style="width: 250px"></div>
						</div>
						
						<div class="form-group">
						<label class="control-label">Confirm New Password</label>
						<div><input type="password" id="confirm_password" class="form-control" name="confirm_password" style="width: 250px"></div>
						</div>
						
						<div>
						<button onclick="updatePassword();" type="button" class="btn btn-info">Submit</button>
						</div>
						
					</div>
					<?php if(Yii::$app->session->get('type') !== \app\models\User::USERTYPE_PARENT) { ?>
						<div class="tab-pane" id="tab-settings-online-indicator">
							<div id="onlineIndicator"></div>
							<h4>Allow people to see when you're studying?</h4>
							<?php
								$checked = ''; 
								if($onlineIndicator == 'yes'){
									$checked = 'checked="checked"';	
								}
							?>
							<p><input type="checkbox" id="online" name="online" value='Yes' <?php echo $checked; ?> > Show indicator when you're online</p>				
							<button onclick="updateIndicator();" type="button" class="btn btn-info">Save</button>
						</div>

						<div class="tab-pane" id="tab-settings-public-profile">
							<div id="profileDisplay"></div>
							<h4>Profile Display Settings</h4>
							<?php
								$checked = ''; 
								if($profileDisplayPublic == 'yes'){
									$checked = 'checked="checked"';	
								}
							?>
							<p><input type="checkbox" id="profilePublic" name="profilePublic" value='Yes' <?php echo $checked; ?> > Display your profile to the public?</p>				
							<button onclick="profileDisplay();" type="button" class="btn btn-info">Save</button>
						</div>

						<div class="tab-pane" id="tab-settings-school">
							<div id="schoolInfo"></div>
							<h4>School</h4>
							<div class="form-group">
								<label class="control-label">Academic Level</label>
								<div><?php echo Html::dropDownList('academic-level', $schoolSettings['academicLevel'], $academicLevelList, ['id' => 'academic-level', 'class' => 'form-control', 'style' => 'width: 250px;']); ?></div>
							</div>
							<div class="form-group">
								<label class="control-label">School Type</label>
								<div><?php echo Html::dropDownList('school-type', $schoolSettings['schoolType'], $schoolList, ['id' => 'school-type', 'class' => 'form-control', 'style' => 'width: 250px;']); ?></div>
							</div>
							<div class="form-group">
								<label class="control-label">School Name</label>
								<div><input type="text" id="school-name" class="form-control" name="school-name" style="width: 250px" value="<?php echo $schoolSettings['currentSchool']; ?>" /></div>
							</div>
							<button onclick="schoolSettings();" type="button" class="btn btn-info">Save</button>
						</div>
					<?php } ?>
					
					<?php if(\Yii::$app->session->get('type') == \app\models\User::USERTYPE_STUDENT) { ?>
						<div class="tab-pane" id="tab-settings-parent">
							<div id="parentEmailInvitationInfo"></div>
							<h4>Parent</h4>
							<div class="parent-status-info">
								<?php if(is_array($parent) && !empty($parent) && $parent['parent_id'] == 0) { ?>
									<div class="alert alert-default">Email invitation to <?php echo $parentEmail; ?> is already sent.</div>
								<?php } else if (is_array($parent) && !empty($parent) && $parent['parent_id'] !== 0) { ?>
									<table class="table table-bordered table-striped table-condensed table-hover">
										<tbody>
											<tr>
												<td>Name</td>
												<td><?php echo $parent['parent_name']; ?></td>
											</tr>
											
											<tr>
												<td>Username</td>
												<td><?php echo $parent['parent_username']; ?></td>
											</tr>
											
											<tr>
												<td>Email</td>
												<td><?php echo $parent['parent_email']; ?></td>
											</tr>
										</tbody>
									</table>
									<a href="javascript:;" onclick="jQuery('#modal-unlink-parent').modal('show', {backdrop: 'static'});">
										<button type="submit" class="btn btn-primary" name="delete-button" style="background-color:red">Unlink Parent</button>					
									</a>
								<?php } else { ?>
									<div class="form-group">
										<label class="control-label">Parent Email Address</label>
										<div>
											<input type="text" id="send-parent-invitation" class="form-control" name="send-parent-invitation" style="width: 250px" value="<?php echo $parentEmail; ?>" />
										</div>
									</div>
									<button onclick="sendParentInvitation();" type="button" class="btn btn-info">Send Invitation</button>
								<?php } ?>
							</div>
						</div>
					<?php } ?>

					<?php if(Yii::$app->session->get('type') !== \app\models\User::USERTYPE_PARENT) { ?>
						<div class="tab-pane" id="tab-settings-email-alerts">
							<div id="emailAlertIndicator"></div>
							<h4>Allow quizzy to send emails?</h4>
							<?php
								$checked = ''; 
								if($emailAlertIndicator == 'yes'){
									$checked = 'checked="checked"';	
								}
							?>
							<p><input type="checkbox" id="emailAlert" name="emailAlert" value='Yes' <?php echo $checked; ?> > Send me email for quizzy updates</p>				
							<button onclick="emailAlert();" type="button" class="btn btn-info">Save</button>
						</div>
					<?php } ?>

					<div class="tab-pane" id="tab-settings-delete-account">
						<h4>Permanently delete <?php echo $userName ?> </h4>
						<p>
							<strong>Warning!</strong>
				            By clicking on the button below, you will delete your account permanently. You will NOT be able to access the learning sets and subscribed sets. You will need to repurchase the learning sets again if you would like to access in the future. Are you sure you want to delete? 
						</p>
						<a href="javascript:;" onclick="jQuery('#modal-delete-account').modal('show', {backdrop: 'static'});">
							<button type="submit" class="btn btn-primary" id="delete-account-button" name="delete-account-button" style="background-color:red">Delete Account</button>					
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>	


<script type="text/javascript">
var deleteAjax = false;
function updateIndicator() {
	var online = $('#online:checked').length;
	$.ajax({
		type : "POST",
		url : '<?php echo \Yii::$app->getUrlManager()->createUrl('user/default/updateindicator'); ?>',
		cache : true,
		data : {
			online : online,
			_csrf : $('meta[name="csrf-token"]').attr("content")
		},
		dataType:'json',
		success: function(response) {
			$('#onlineIndicator').hide().fadeIn().html(alertBox('Your options have been saved', 'alert alert-default'));
			if(response.success == true) {
				if(online == 1) {
					$('.user-status').removeClass('is-offline').addClass('is-online');
				} else {
					$('.user-status').removeClass('is-online').addClass('is-offline');
				}
			}
		}
	});
}

function emailAlert() {
	var emailAlert = $('#emailAlert:checked').length;
	
	$.ajax({
		type : "POST",
		url : '<?php echo \Yii::$app->getUrlManager()->createUrl('user/default/emailalert'); ?>',
		cache : true,
		data : {
			emailAlert : emailAlert,
			_csrf : $('meta[name="csrf-token"]').attr("content")
		},
		dataType:'json',
		success: function(response) {
			$('#emailAlertIndicator').hide().fadeIn().html(alertBox('Your options have been saved','alert alert-default'));
		}
	});
}

function profileDisplay(){
	var profilePublic = $('#profilePublic:checked').length;
	
	$.ajax({
		type : "POST",
		url : '<?php echo \Yii::$app->getUrlManager()->createUrl('user/default/profiledisplay'); ?>',
		cache : true,
		data : {
			profilePublic : profilePublic,
			_csrf : $('meta[name="csrf-token"]').attr("content")
		},
		dataType:'json',
		success: function(response) {
			$('#profileDisplay').hide().fadeIn().html(alertBox('Your options have been saved','alert alert-default'));
		}
	});


}

function updateEmail() {
	var email = $('#email').val();
	var password = $('#password').val();
	
	$.ajax({
		type : "POST",
		url : '<?php echo \Yii::$app->getUrlManager()->createUrl('user/default/updatemail'); ?>',
		cache : true,
		data : {
			email : email,
			password : password,
			_csrf : $('meta[name="csrf-token"]').attr("content")
		},
		dataType:'json',
		success: function(response) {
			$('#updateEmailMsg').hide().fadeIn().html(alertBox(response.msg, response.alertStyle));
		}
	});
}

function updatePassword() {
	var old_password = $('#old_password').val();
	var new_password = $('#new_password').val();
	var confirm_password = $('#confirm_password').val();

	
	$.ajax({
		type : "POST",
		url : '<?php echo \Yii::$app->getUrlManager()->createUrl('user/default/updatepassword'); ?>',
		cache : true,
		data : {
			old_password : old_password,
			new_password : new_password,
			confirm_password : confirm_password,
			_csrf : $('meta[name="csrf-token"]').attr("content")
		},
		dataType:'json',
		success: function(response) {
			$('#updatePasswordMsg').hide().fadeIn().html(alertBox(response.msg, response.alertStyle));
		}
	});
}

function alertBox(customMessage, alertStyle) {
	var msg = '<div class="'+ alertStyle +'"> ' + 
	'<button type="button" class="close" data-dismiss="alert">' +
	'<span aria-hidden="true">&times;</span>' +
	'<span class="sr-only">Close</span>' +
	'</button>' +
	customMessage +
	'</div>';
	return msg;
}

function schoolSettings()
{
	$.ajax({
		type : "POST",
		url : '<?php echo \Yii::$app->getUrlManager()->createUrl('user/default/school'); ?>',
		cache : true,
		data : {
			academicLevel : jQuery('#academic-level').val(),
			schoolType : jQuery('#school-type').val(),
			schoolName : jQuery('#school-name').val(),
			_csrf : $('meta[name="csrf-token"]').attr("content")
		},
		dataType:'json',
		success: function(response) {
			if(response.success == true) {
				$('#schoolInfo').hide().fadeIn().html(alertBox(response.msg, response.alertStyle));
			}
		}
	});
}

function fullname()
{
	$.ajax({
		type : "POST",
		url : '<?php echo \Yii::$app->getUrlManager()->createUrl('user/default/fullname'); ?>',
		cache : true,
		data : {
			fullname : jQuery('#fullname').val(),
			_csrf : $('meta[name="csrf-token"]').attr("content")
		},
		dataType:'json',
		success: function(response) {
			$('#fullnameInfo').hide().fadeIn().html(alertBox(response.message, response.alertStyle));
			if(response.success == true) {
				jQuery('span.user-name-info-nav-header').empty().append(response.fullname);
				jQuery('span.user-name-info-sidebar').empty().append(response.fullname);
			}
		}
	});
}

function sendParentInvitation()
{
	$.ajax({
		type : "POST",
		url : '<?php echo \Yii::$app->getUrlManager()->createUrl('user/default/parentinvitation'); ?>',
		cache : true,
		data : {
			parentEmail : jQuery('#send-parent-invitation').val(),
			_csrf : $('meta[name="csrf-token"]').attr("content")
		},
		dataType:'json',
		success: function(response) {
			if(response.success == true) {
				var message = '<div class="'+ response.alertStyle +'"> ' + response.message + '</div>';
				$('.parent-status-info').hide().fadeIn().html(message);
			} else {
				$('#parentEmailInvitationInfo').hide().fadeIn().html(alertBox(response.message, response.alertStyle));
			}
		}
	});
}

$(document).ready(function() {
	$('#<?php echo $tab; ?>').addClass('active');
	jQuery(document).on('click', 'button#delete-account-button', function() {
		if(deleteAjax == false) {
			deleteAjax = true;
			jQuery.ajax({
				type : "POST",
				url : '<?php echo \Yii::$app->getUrlManager()->createUrl('user/delete/index'); ?>',
				cache : true,
				data : {
					_csrf : $('meta[name="csrf-token"]').attr("content")
				},
				dataType:'json',
				success: function(response) {
					deleteAjax = false;
					window.location.href = response.redirectURL;
				}
			});
		}
	});
});
</script>

