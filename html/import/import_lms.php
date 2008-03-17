<?
	// Find if player left early
	$r_player = small_query("SELECT count(id) as cnt FROM uts_temp_$uid WHERE col1 = 'player' AND col2 = 'Disconnect' AND col3 = $playerid");
	
	IF ($r_player[cnt] > 0) {
		$sql_playerlms = "UPDATE 	uts_player
							SET 	gamescore = 0
							WHERE 	id = $playerecordid";
		mysql_query($sql_playerlms) or die(mysql_error());
	}
?> 
