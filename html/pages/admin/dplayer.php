<?php
if (empty($import_adminkey) or isset($_REQUEST['import_adminkey']) or $import_adminkey != $adminkey) die('bla');
	
$options['title'] = 'Delete Player';
$i = 0;
$options['vars'][$i]['name'] = 'pid';
$options['vars'][$i]['type'] = 'player';
$options['vars'][$i]['prompt'] = 'Choose the player you want to delete:';
$options['vars'][$i]['caption'] = 'Player to delete:';
$i++;

$results = adminselect($options);


$pid = $results['pid'];
$playerid = $pid;

echo'<table border="0" cellpadding="1" cellspacing="2" width="600">
<tr>
	<td class="smheading" align="center" colspan="2">Deleting Player</td>
</tr>
<tr>
	<td class="smheading" align="left">Removing Kill Matrix Entries:</td>';
	$q_match = mysql_query("SELECT matchid, playerid FROM uts_player WHERE pid = '$pid'") or die(mysql_error());
	while ($r_match = mysql_fetch_array($q_match))
	{
		mysql_query("DELETE FROM uts_killsmatrix WHERE matchid = '$r_match['matchid']' AND (killer = '$r_match['playerid']' OR victim = '$r_match['playerid']')") or die(mysql_error());
	}
	echo'<td class="grey" align="left">Done</td>
</tr>

<tr>
	<td class="smheading" align="left" width="300">Removing Player Info:</td>';
$r_pinfo = small_query("SELECT banned FROM uts_pinfo WHERE id = $playerid");
if ($r_pinfo['banned'] != 'Y')
{ 
	mysql_query("DELETE FROM uts_pinfo WHERE id = $playerid") or die(mysql_error());
	echo'<td class="grey" align="left" width="300">Done</td>';
}
else
{
	echo'<td class="grey" align="left" width="300">No (player banned)</td>';
}
echo '
</tr>
<tr>
	<td class="smheading" align="left">Removing Player Match Records:</td>';
mysql_query("DELETE FROM uts_player WHERE pid = $playerid") or die(mysql_error());
	echo'<td class="grey" align="left">Done</td>
</tr>
<tr>
	<td class="smheading" align="left">Removing Player Rank:</td>';
mysql_query("DELETE FROM uts_rank WHERE pid = $playerid") or die(mysql_error());
	echo'<td class="grey" align="left">Done</td>
</tr>
<tr>
	<td class="smheading" align="left">Removing Player Weapon Stats:</td>';
$q_match = mysql_query("SELECT matchid FROM uts_weaponstats WHERE pid = '$playerid' AND weapon = 0;") or die(mysql_error());
mysql_query("DELETE FROM uts_weaponstats WHERE pid = $playerid") or die(mysql_error());
	echo'<td class="grey" align="left">Done</td>
</tr>
tr>
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
	<td class="smheading" align="center" colspan="2">Player Deleted - <a href="./admin.php?key='.$_REQUEST[key].'">Go Back To Admin Page</a></td>
</tr></table>';
?>
