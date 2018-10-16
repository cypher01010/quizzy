<div id="speller-result">
	<table class="table table-striped">
		<tbody>
			<?php foreach ($result as $key => $value) { ?>
				<tr>
					<td><?php echo $value['status']; ?></td>
					<td><a href="javascript:;" class="play-flash-term-audio" data-id="<?php echo $key; ?>" data-key="term-<?php echo $key; ?>" data-type="term"><?php echo $value['term']; ?></a></td>
					<td><a href="javascript:;" class="play-flash-definition-audio" data-id="<?php echo $key; ?>" data-key="definition-<?php echo $key; ?>" data-type="definition"><?php echo $value['definition']; ?></a></td>
				</tr>
			<?php } ?>
		</tbody>
	</table>
	<div>
		<a class="btn btn-quizzy" href="<?php echo \Yii::$app->getUrlManager()->createUrl(['set/speller/play', 'id' => $setId]); ?>">Continue</a>
	</div>
</div>