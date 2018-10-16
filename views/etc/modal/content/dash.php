<h1>Your Score : <span id="puzzle-my-score"><?php echo $score; ?></span></h1>
<table class="table">
	<thead>
		<tr>
		    <th width="5%">Rank</th>
			<th width="30%">User</th>
			<th>Score</th>
		</tr>
	</thead>
	<tbody>
	    <?php $i = 1;?>
		<?php foreach ($leaderboard as $key => $value) { ?>
			<tr>
			    <td><?php echo $i; ?></td>
				<td class="middle-align"><?php echo $value['full_name']; ?></td>
				<td><?php echo $value['score']; ?></td>
			</tr>
			<?php $i++ ?>
		<?php } ?>
	</tbody>
</table>
