<?
if (empty($import_adminkey) or isset($_REQUEST['import_adminkey']) or $import_adminkey != $adminkey) die('bla');
	
$options['title'] = 'Delete match';
$i = 0;
$options['vars'][$i]['name'] = 'server';
$options['vars'][$i]['type'] = 'server';
$options['vars'][$i]['prompt'] = 'Choose the server where the match took place:';
$options['vars'][$i]['caption'] = 'Server:';
$i++;
$options['vars'][$i]['name'] = 'mid';
$options['vars'][$i]['type'] = 'match';
$options['vars'][$i]['whereserver'] = 'server';
$options['vars'][$i]['prompt'] = 'Choose the match to delete:';
$options['vars'][$i]['caption'] = 'Match to delete:';
$i++;

$results = adminselect($options);


$matchid = $results['mid'];

echo'<br><table border="0" cellpadding="1" cellspacing="2" width="600">
<tr>
	<td class="smheading" align="center" colspan="2">Deleting Match ID '.$matchid.'</td>
</tr>
<tr>
	<td class="smheading" align="left" width="200">Adjusting Rankings</td>';
$sql_radjust = "SELECT pid, gid, rank FROM uts_player WHERE matchid = $matchid";
$q_radjust = mysql_query($sql_radjust) or die(mysql_error());
$pids = array();
while ($r_radjust = mysql_fetch_array($q_radjust))
{

	$pid = $r_radjust['pid'];
	$pids[] = $pid;
	$gid = $r_radjust['gid'];
	$rank = $r_radjust['rank'];

	$sql_crank = small_query("SELECT id, rank, matches FROM uts_rank WHERE pid = $pid AND gid = $gid");
	if (!$sql_crank) continue;
	
	$rid = $sql_crank['id'];
	$newrank = $sql_crank['rank']-$rank;
	$oldrank = $sql_crank['rank'];
	$matchcount = $sql_crank['matches']-1;

	mysql_query("UPDATE uts_rank SET rank = $newrank, prevrank = $oldrank, matches = $matchcount WHERE id = $rid") or die(mysql_error());
	mysql_query("DELETE FROM uts_rank WHERE matches = 0") or die(mysql_error());
}
	echo'<td class="grey" align="left" width="400">Done</td>
</tr>
<tr>
	<td class="smheading" align="left" width="200">Removing Match Record:</td>';
mysql_query("DELETE FROM uts_match WHERE id = $matchid") or die(mysql_error());
	echo'<td class="grey" align="left" width="400">Done</td>
</tr>
<tr>
	<td class="smheading" align="left" width="200">Removing Player Records:</td>';
mysql_query("DELETE FROM uts_player WHERE matchid = $matchid") or die(mysql_error());
	echo'<td class="grey" align="left" width="400">Done</td>
</tr>
<tr>
	<td class="smheading" align="left" width="200">Removing Kill Matrix Entries:</td>';
mysql_query("DELETE FROM uts_killsmatrix WHERE matchid = $matchid") or die(mysql_error());
	echo'<td class="grey" align="left" width="400">Done</td>
</tr>
<tr>
	<td class="smheading" align="left" width="200">Removing Weapon Stats:</td>';
mysql_query("DELETE FROM uts_weaponstats WHERE matchid = $matchid") or die(mysql_error());
	echo'<td class="grey" align="left" width="400">Done</td>
</tr>
<tr>
	<td class="smheading" align="left" width="200">Amending Player Weapon Stats:</td>';
foreach($pids as $pid)
{
	// Update the player's weapon statistics (matchid 0)
	mysql_query("	REPLACE	uts_weaponstats
					SELECT	0 AS matchid,
							pid,
							weapon,
							SUM(kills) AS kills,
							SUM(shots) AS shots,
							SUM(hits) AS hits,
							SUM(damage) AS damage,
							LEAST(ROUND(10000*SUM(hits)/SUM(shots))/100, 100) AS acc
					FROM	uts_weaponstats
					WHERE	pid = '$pid'
						AND weapon > 0
						AND matchid > 0
					GROUP BY weapon;"
	) or die(mysql_error());

	// Update the player's match statistics (weapon 0)
	mysql_query("	REPLACE	uts_weaponstats
					SELECT	matchid,
							pid,
							0 AS weapon,
							SUM(kills) AS kills,
							SUM(shots) AS shots,
							SUM(hits) AS hits,
							SUM(damage) AS damage,
							LEAST(ROUND(10000*SUM(hits)/SUM(shots))/100, 100) AS acc
					FROM	uts_weaponstats
					WHERE	matchid > 0
						AND	pid = '$pid'
						AND weapon > 0
					GROUP BY matchid;"
	) or die(mysql_error());

	// Update the player's match entry in uts_player
	mysql_query("	UPDATE	uts_player AS p,
							uts_weaponstats AS w
					SET 	p.accuracy = w.acc
					WHERE	w.matchid = p.matchid
						AND	p.pid = '$pid'
						AND	w.pid = p.pid
						AND	w.weapon = 0;"
	) or die(mysql_error());

	// Update the player's career statistics (weapon 0, match 0)
	mysql_query("	REPLACE	uts_weaponstats
					SELECT	0 AS matchid,
							'$pid' AS pid,
							0 AS weapon,
							SUM(kills) AS kills,
							SUM(shots) AS shots,
							SUM(hits) AS hits,
							SUM(damage) AS damage,
							LEAST(ROUND(10000*SUM(hits)/SUM(shots))/100, 100) AS acc
					FROM	uts_weaponstats
					WHERE	matchid > 0
						AND	pid = '$pid'
						AND weapon > 0;"
	) or die(mysql_error());
}
	echo'<td class="grey" align="left" width="400">Done</td>
</tr>
<tr>
	<td class="smheading" align="left" width="200">Amending Global Weapon Stats:</td>';
mysql_query("	REPLACE uts_weaponstats
				SELECT	0 AS matchid,
						0 AS pid,
						weapon,
						SUM(kills) AS kills,
						SUM(shots) AS shots,
						SUM(hits) AS hits,
						SUM(damage) AS damage,
						LEAST(ROUND(10000*SUM(hits)/SUM(shots))/100, 100) AS acc
				FROM	uts_weaponstats
				WHERE	matchid > 0
					AND	pid > 0
					AND weapon > 0
				GROUP BY weapon;"
) or die(mysql_error());
mysql_query("	REPLACE uts_weaponstats
				SELECT	0 AS matchid,
						0 AS pid,
						0 AS weapon,
						SUM(kills) AS kills,
						SUM(shots) AS shots,
						SUM(hits) AS hits,
						SUM(damage) AS damage,
						LEAST(ROUND(10000*SUM(hits)/SUM(shots))/100, 100) AS acc
				FROM	uts_weaponstats
				WHERE	matchid > 0
					AND	pid > 0
					AND weapon > 0;"
) or die(mysql_error());
	echo'<td class="grey" align="left" width="400">Done</td>
</tr>
<tr>
	<td class="smheading" align="center" colspan="2">Match Deleted - <a href="./admin.php?key='.$_REQUEST[key].'">Go Back To Admin Page</a></td>
</tr></table>';

?>
