<?php
$leftMenuList = isset($leftMenu) ? $leftMenu : array();
$rightMenuList = isset($rightMenu) ? $rightMenu : array();
?>
<div class="content-navigation">
	<nav class="navbar navbar-inverse" role="navigation">
		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-2">

			<?php if(is_array($leftMenuList) && !empty($leftMenuList)) { ?>
				<ul class="nav navbar-nav">
					<?php foreach ($leftMenuList as $key => $value) { ?>
						<?php $active = ($value['active'] == true) ? 'class="active"' : NULL; ?>
						<?php if(!isset($value['child'])) { ?>
							<li <?php echo $active; ?>><a href="<?php echo $value['url']; ?>"><?php echo $value['label']; ?></a></li>
						<?php } else { ?>
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $value['label']; ?> <b class="caret"></b></a>
								<ul class="dropdown-menu dropdown-primary">
									<?php foreach ($value['child'] as $childKey => $childValue) { ?>
										<?php if(isset($childValue['divider']) && $childValue['divider'] == true) { ?>
											<li class="divider"></li>
										<?php } else { ?>
											<a href="<?php echo $childValue['url']; ?>"  <?php if(isset($childValue['option'])) { foreach ($childValue['option'] as $key => $value) { echo $key . '= "' . $value . '"'; }} ?> ><?php echo $childValue['label']; ?></a>
										<?php } ?>
									<?php } ?>
								</ul>
							</li>
						<?php } ?>
					<?php } ?>
				</ul>
			<?php } ?>

			<?php if(is_array($rightMenuList) && !empty($rightMenuList)) { ?>
				<ul class="nav navbar-nav navbar-right">
					<?php foreach ($rightMenuList as $key => $value) { ?>
						<?php $active = ($value['active'] == true) ? 'class="active"' : NULL; ?>
						<?php if(!isset($value['child'])) { ?>
							<li <?php echo $active; ?>><a href="<?php echo $value['url']; ?>"><?php echo $value['label']; ?></a></li>
						<?php } else { ?>
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $value['label']; ?> <b class="caret"></b></a>
								<ul class="dropdown-menu dropdown-primary">
									<?php foreach ($value['child'] as $childKey => $childValue) { ?>
										<?php if(isset($childValue['divider']) && $childValue['divider'] == true) { ?>
											<li class="divider"></li>
										<?php } else { ?>
											<li>
												<a href="<?php echo $childValue['url']; ?>"  <?php if(isset($childValue['option'])) { foreach ($childValue['option'] as $key => $value) { echo $key . '= "' . $value . '"'; }} ?> ><?php echo $childValue['label']; ?></a>
											</li>
										<?php } ?>
									<?php } ?>
								</ul>
							</li>
						<?php } ?>
					<?php } ?>
				</ul>
			<?php } ?>

		</div>
	</nav>
</div>