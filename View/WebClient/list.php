<html>

<body>

<table border=1>

<tr>
	<th> game id 	</th>
	<th> epoch </th>
	<th> status </th>
	<th> start time </th>
</tr>

<?php foreach ($v->game_list as $key => $game_data): ?>

 <tr>

 	<td> <a href="<?= $v->app_pos ?>/web_client/watch?game_id=<?= $game_data['id'] ?>"> <?= $game_data['id'] ?>  </a> </td>
 	<td> <?= $game_data['epoch'] ?> </td>
 	<td> <?= $game_data['winner'] ?> </td>
 	<td> <?= $game_data['start_time'] ?> </td>
 </tr>

<?php endforeach ?> 

</body>
</html>