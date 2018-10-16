<h1>Your Time : <span id="puzzle-my-score"><?php echo gmdate("H:i:s", $score); ?></span></h1>
<table class="table">
	<thead>
		<tr>
			<th width="10%">Rank</th>
			<th width="40%">User</th>
			<th>Score</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($leaderboard as $key => $value) { ?>
			<tr>
				<td><?php echo ($key + 1); ?></td>
				<td class="middle-align"><?php echo $value['full_name']; ?></td>
				<td><?php echo gmdate("H:i:s", $value['elapse']); ?></td>
			</tr>
		<?php } ?>
	</tbody>
</table>