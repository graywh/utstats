<?
global $s_lastvisit;
echo'
<table border="0" cellpadding="1" cellspacing="2" width="720">
  <tbody><tr>
    <td class="heading" align="center">Your Watchlist</td>
  </tr>
</tbody></table>';
echo '<br /><br /><br />';


$watchlist = GetCurrentWatchlist();
if (count($watchlist) > 25) $watchlist = array_slice($watchlist, 0, 25);
if (count($watchlist) == 0)
{
	echo "<p class='pages'>Your watchlist is empty!<br /><br />You can add players to your watchlist by clicking the appropriate icon on the header of their career summary page.</p>";
	return;
}

echo '
<table class="box" border="0" cellpadding="1" cellspacing="1" width="625">
  <tbody>
  <tr>
	<td class="smheading" align="center" width="150">Player Name</td>
	<td class="smheading" align="center">Last Match</td>
	<td class="smheading" align="center">Matches</td>
	<td class="smheading" align="center">Score</td>
	<td class="smheading" align="center">Frags</td>
	<td class="smheading" align="center">Kills</td>
	<td class="smheading" align="center">Deaths</td>
	<td class="smheading" align="center">Suicides</td>
	<td class="smheading" align="center">Team Kills</td>
	<td class="smheading" align="center">Head Shots</td>
	<td class="smheading" align="center">FPH</td>
	<td class="smheading" align="center">Eff</td>
	<td class="smheading" align="center">Acc</td>
	<td class="smheading" align="center">TTL</td>
	<td class="smheading" align="center">Hours</td>
  </tr>';


$i = 0;  
foreach($watchlist as $pid)
{
	$sql_players = "SELECT 	pi.id AS pid,
							pi.name, 
							pi.country,
							m.time,
							m.id AS mid
					FROM	uts_pinfo AS pi,
							uts_match AS m,
							uts_player AS p
					WHERE	pi.id = '$pid'
						AND	p.matchid = m.id
						AND	p.pid = pi.id
					ORDER BY	m.time DESC
					LIMIT	0,1";
						
	$sql_pinfo = "	SELECT	*
                        FROM uts_career
                        WHERE id = '$pid'";

	$r_pinfo = small_query($sql_pinfo);
						
	$q_players = mysql_query($sql_players) or die(mysql_error());
	
	
	while ($r_players = mysql_fetch_array($q_players))
	{
		$i++;
		$new = (mtimestamp($r_players['time']) > $s_lastvisit) ? true : false;
		$class = ($i % 2) ? 'grey' : 'grey2';
		echo '<tr>';
		echo '<td class="dark"><a class="darkhuman" href="?p=pinfo&amp;pid='. $r_players['pid'] .'">';
		echo FormatPlayerName($r_players['country'], $r_players['pid'], $r_players['name']);
		echo '</a></td>';
		echo '<td class="'.$class.'" align="center"><a class="'.$class.'" href="?p=match&amp;mid='. $r_players['mid'] .'">';;
		if  ($new)
			echo "<strong>";
		echo date("Y-m-d H:i", mtimestamp($r_players['time']));
		if ($new)
			echo "</strong>";
		echo '</a></td>';
		
		echo '
		<td class="'.$class.'" align="center">'.$r_pinfo['matches'].'</td>
		<td class="'.$class.'" align="center">'.$r_pinfo['gamescore'].'</td>
		<td class="'.$class.'" align="center">'.$r_pinfo['frags'].'</td>
		<td class="'.$class.'" align="center">'.$r_pinfo['kills'].'</td>
		<td class="'.$class.'" align="center">'.$r_pinfo['deaths'].'</td>
		<td class="'.$class.'" align="center">'.$r_pinfo['suicides'].'</td>
		<td class="'.$class.'" align="center">'.$r_pinfo['teamkills'].'</td>
		<td class="'.$class.'" align="center">'.$r_pinfo['headshots'].'</td>
		<td class="'.$class.'" align="center">'.get_dp($r_pinfo['fph']).'</td>
		<td class="'.$class.'" align="center">'.get_dp($r_pinfo['eff']).'</td>
		<td class="'.$class.'" align="center">'.get_dp($r_pinfo['accuracy']).'</td>
		<td class="'.$class.'" align="center">'.GetMinutes($r_pinfo['ttl']).'</td>
		<td class="'.$class.'" align="center">'.sec2hour($r_pinfo['gametime']).'</td>';
		
		echo '</tr>';
	}
}

echo '</tbody></table>';


?>
