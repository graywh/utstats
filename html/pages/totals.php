<?
echo'
<table border="0" cellpadding="1" cellspacing="2" width="710">
  <tbody><tr>
    <td class="heading" align="center">Totals Summary</td>
  </tr>
</tbody></table>
<br>
<table class="box" border="0" cellpadding="1" cellspacing="2">
  <tbody><tr>
    <td class="medheading" colspan="10" align="center">Summary</td>
  </tr>
  <tr>
    <td class="smheading" align="center" width="150">Game Type</td>
    <td class="smheading" align="center" width="45">Score</td>
    <td class="smheading" align="center" width="35">Frags</td>
    <td class="smheading" align="center" width="35">Kills</td>
    <td class="smheading" align="center" width="35">Suicides</td>
    <td class="smheading" align="center" width="35">Team Kills</td>
    <td class="smheading" align="center" width="50">Matches</td>
    <td class="smheading" align="center" width="45">Hours</td>
  </tr>';

$sql_totsumm = "SELECT g.name AS gamename, SUM(p.gamescore) AS gamescore, SUM(p.frags) AS frags, SUM(p.kills) AS kills, SUM(p.suicides) AS suicides, SUM(p.teamkills) AS teamkills, COUNT(DISTINCT p.matchid) AS matchcount, SUM(p.gametime) AS sumgametime
FROM uts_player AS p, uts_games AS g WHERE p.gid = g.id GROUP BY gamename ORDER BY gamename ASC";
$q_totsumm = mysql_query($sql_totsumm) or die(mysql_error());
while ($r_totsumm = zero_out(mysql_fetch_array($q_totsumm))) {

	$gametime = sec2hour($r_totsumm[sumgametime]);

	echo'
	  <tr>
	    <td class="dark" align="center">'.$r_totsumm['gamename'].'</td>
	    <td class="grey" align="center">'.$r_totsumm['gamescore'].'</td>
	    <td class="grey" align="center">'.$r_totsumm['frags'].'</td>
	    <td class="grey" align="center">'.$r_totsumm['kills'].'</td>
	    <td class="grey" align="center">'.$r_totsumm['suicides'].'</td>
	    <td class="grey" align="center">'.$r_totsumm['teamkills'].'</td>
	    <td class="grey" align="center">'.$r_totsumm['matchcount'].'</td>
	    <td class="grey" align="center">'.$gametime.'</td>
	  </tr>';
}

$sql_summtot = zero_out(small_query("SELECT SUM(gamescore) AS gamescore, SUM(frags) AS frags, SUM(kills) AS kills, SUM(suicides) AS suicides, SUM(teamkills) AS teamkills, COUNT(DISTINCT matchid) AS matchcount, SUM(gametime) AS sumgametime
FROM uts_player"));

$gametime2 = sec2hour($sql_summtot[sumgametime]);

echo'
    <tr>
    <td class="dark" align="center"><b>Totals</b></td>
	    <td class="grey" align="center">'.$sql_summtot['gamescore'].'</td>
	    <td class="grey" align="center">'.$sql_summtot['frags'].'</td>
	    <td class="grey" align="center">'.$sql_summtot['kills'].'</td>
	    <td class="grey" align="center">'.$sql_summtot['suicides'].'</td>
	    <td class="grey" align="center">'.$sql_summtot['teamkills'].'</td>
	    <td class="grey" align="center">'.$sql_summtot['matchcount'].'</td>
	    <td class="grey" align="center">'.$gametime2.'</td>
  </tr>
</tbody></table>
<br>
<table border="0" cellpadding="1" cellspacing="2" width="600">
  <tbody><tr>
    <td class="medheading" colspan="11" align="center">Assault, Domination and CTF Events Summary</td>
  </tr>
  <tr>
    <td class="dark" align="center" rowspan="2">Assault Objectives</td>
    <td class="dark" align="center" rowspan="2">Control Point Captures</td>
    <td class="dark" align="center" colspan="9">Capture The Flag</td>
  </tr>
  <tr>
    <td class="dark" align="center">Flag Takes</td>
    <td class="dark" align="center">Flag Pickups</td>
    <td class="dark" align="center">Flag Drops</td>
    <td class="dark" align="center">Flag Assists</td>
    <td class="dark" align="center">Flag Covers</td>
    <td class="dark" align="center">Flag Seals</td>
    <td class="dark" align="center">Flag Captures</td>
    <td class="dark" align="center">Flag Kills</td>
    <td class="dark" align="center">Flag Returns</td>
  </tr>';

 $sql_cdatot = zero_out(small_query("SELECT SUM(dom_cp) AS dom_cp, SUM(ass_obj) AS ass_obj, SUM(flag_taken) AS flag_taken,
 SUM(flag_pickedup) AS flag_pickedup, SUM(flag_dropped) AS flag_dropped, SUM(flag_assist) AS flag_assist, SUM(flag_cover) AS flag_cover,
 SUM(flag_seal) AS flag_seal, SUM(flag_capture) AS flag_capture, SUM(flag_kill)as flag_kill,
 SUM(flag_return) AS flag_return FROM uts_player"));

  echo'
  <tr>
    <td class="grey" align="center">'.$sql_cdatot['ass_obj'].'</td>
    <td class="grey" align="center">'.$sql_cdatot['dom_cp'].'</td>
    <td class="grey" align="center">'.$sql_cdatot['flag_taken'].'</td>
    <td class="grey" align="center">'.$sql_cdatot['flag_pickedup'].'</td>
    <td class="grey" align="center">'.$sql_cdatot['flag_dropped'].'</td>
    <td class="grey" align="center">'.$sql_cdatot['flag_assist'].'</td>
    <td class="grey" align="center">'.$sql_cdatot['flag_cover'].'</td>
    <td class="grey" align="center">'.$sql_cdatot['flag_seal'].'</td>
    <td class="grey" align="center">'.$sql_cdatot['flag_capture'].'</td>
    <td class="grey" align="center">'.$sql_cdatot['flag_kill'].'</td>
    <td class="grey" align="center">'.$sql_cdatot['flag_return'].'</td>
  </tr>
</tbody></table>
<br>
<table border="0" cellpadding="1" cellspacing="2" width="500">
  <tbody><tr>
    <td class="medheading" colspan="4" align="center">Special Events</td>
  </tr>';

//$sql_firstblood = zero_out(small_count("SELECT firstblood FROM uts_match WHERE firstblood != ''"));
$sql_headshots = zero_out(small_query("SELECT SUM(headshots) AS headshots FROM uts_player"));
$sql_multis = zero_out(small_query("SELECT SUM(spree_double) AS spree_double, SUM(spree_multi) AS spree_multi, SUM(spree_ultra) AS spree_ultra, SUM(spree_monster) AS spree_monster FROM uts_player"));
$sql_sprees = zero_out(small_query("SELECT SUM(spree_kill) AS spree_kill, SUM(spree_rampage) AS spree_rampage, SUM(spree_dom) AS spree_dom, SUM(spree_uns) AS spree_uns, SUM(spree_god) AS spree_god FROM uts_player"));

  echo'
  <tr>
    <td class="smheading" align="center" colspan="2" width="250">Special/Multis</td>
    <td class="smheading" align="center" colspan="2" width="250">Sprees</td>
  </tr>
  <tr>
    <td class="dark" align="center" width="150">Head Shots</td>
    <td class="grey" align="center" width="100">'.$sql_headshots['headshots'].'</td>
    <td class="dark" align="center" width="150">Killing Spree</td>
    <td class="grey" align="center" width="100">'.$sql_sprees['spree_kill'].'</td>
  </tr>
  <tr>
    <td class="dark" align="center">Double Kills</td>
    <td class="grey" align="center">'.$sql_multis['spree_double'].'</td>
    <td class="dark" align="center">Rampage</td>
    <td class="grey" align="center">'.$sql_sprees['spree_rampage'].'</td>
  </tr>
  <tr>
    <td class="dark" align="center">Multi Kills</td>
    <td class="grey" align="center">'.$sql_multis['spree_multi'].'</td>
    <td class="dark" align="center">Dominating</td>
    <td class="grey" align="center">'.$sql_sprees['spree_dom'].'</td>
  </tr>
  <tr>
    <td class="dark" align="center">Ultra Kills</td>
    <td class="grey" align="center">'.$sql_multis['spree_ultra'].'</td>
    <td class="dark" align="center">Unstoppable</td>
    <td class="grey" align="center">'.$sql_sprees['spree_uns'].'</td>
  </tr>
  <tr>
    <td class="dark" align="center">Monster Kills</td>
    <td class="grey" align="center">'.$sql_multis['spree_monster'].'</td>
    <td class="dark" align="center">Godlike</td>
    <td class="grey" align="center">'.$sql_sprees['spree_god'].'</td>
  </tr>
</tbody></table>
<br>';

include('includes/weaponstats.php');
weaponstats(0, 0);

echo'<br>
<table border="0" cellpadding="1" cellspacing="2" width="710">
  <tbody><tr>
    <td class="heading" align="center">Totals for Players</td>
  </tr>
</tbody></table>';

// NGStats Style Total Highs (All Time)

// Generalized Career High Stats - Title => Forumula
$careerstatlist = array(
						'Frags' => 'SUM(p.frags)',
						'Deaths' => 'SUM(p.deaths)',
						'Kills' => 'SUM(p.kills)',
						'Suicides' => 'SUM(p.suicides)',
						'Team Kills' => 'SUM(p.teamkills)',
						'Head Shots' => 'SUM(p.headshots)',
						'Efficiency' => '(100*SUM(p.frags)/(SUM(p.kills)+SUM(p.deaths)+SUM(p.teamkills)+SUM(p.suicides)))',
						'Accuracy' => 'ws.acc',
						'TTL' => '(SUM(p.gametime)/(SUM(p.deaths)+SUM(p.suicides)+COUNT(p.id)))',
						'Flag Caps' => 'SUM(p.flag_capture)',
						'Flag Assists' => 'SUM(p.flag_assist)',
						'Flag Kills' => 'SUM(p.flag_kill)',
						'Domination Control Points' => 'SUM(p.dom_cp)',
						'Assault Objectives' => 'SUM(p.ass_obj)',
						'Monster Kills' => 'SUM(p.spree_monster)',
						'Godlikes' => 'SUM(p.spree_god)',
						'Rank Points' => 'SUM(rank)'
					);

// Generalized Match High Stats - Title => Formula
$matchstatlist = array(
					'Frags' => 'p.frags',
					'Deaths' => 'p.deaths',
					'Kills' => 'p.kills',
					'Suicides' => 'p.suicides',
					'Team Kills' => 'p.teamkills',
					'Head Shots' => 'p.headshots',
					'Efficiency' => 'p.eff',
					'Accuracy' => 'ws.acc',
					'TTL' => '(p.gametime/(p.deaths+p.suicides+1))',
					'Flag Caps' => 'p.flag_capture',
					'Flag Assists' => 'p.flag_assist',
					'Flag Kills' => 'p.flag_kill',
					'Domination Control Points' => 'p.dom_cp',
					'Assault Objectives' => 'p.ass_obj',
					'Monster Kills' => 'p.spree_monster',
					'Godlikes' => 'p.spree_god',
					'Rank Points' => 'rank'
				);

// Title => Format
$formatstatlist = array(
						'Efficiency' => 'decimal',
						'Accuracy' => 'decimal',
						'TTL' => 'minutes',
						'Rank Points' => 'decimal',
					);
//*/
echo'<br>
<table border="0" cellpadding="1" cellspacing="2" width="550">
  <tbody>
  <tr>
    <td class="medheading" colspan="5" align="center">Career Highs</td>
  </tr>
  <tr>
    <td class="smheading" align="center" width="200">Category</td>
    <td class="smheading" align="center" width="50">Amount</td>
    <td class="smheading" align="center" width="200">Player</td>
    <td class="smheading" align="center" width="50">Hours</td>
    <td class="smheading" align="center" width="50">Matches</td>
  </tr>';

foreach ($careerstatlist as $s_title => $s_formula)
{
    $stat_high = small_query("SELECT @max := $s_formula AS x, SUM(p.gametime) AS sumgametime FROM uts_player AS p ,uts_weaponstats AS ws WHERE ws.pid = p.pid AND ws.matchid = 0 AND ws.weapon = 0 GROUP BY p.pid HAVING sumgametime > 1800 ORDER BY x DESC LIMIT 1");
	$q_cstat = mysql_query("SELECT p.pid, pi.name, p.country, $s_formula AS stat, SUM(p.gametime) AS sumgametime, COUNT(p.matchid) AS mcount FROM uts_player AS p, uts_pinfo AS pi, uts_weaponstats AS ws WHERE ws.pid = pi.id AND p.pid = pi.id AND ws.matchid = 0 AND ws.weapon = 0 AND pi.banned <> 'Y' GROUP BY ws.pid HAVING sumgametime > 1800 AND stat > 0 AND stat = @max ORDER BY pi.name ASC");

    $nrows = mysql_num_rows($q_cstat);
	if ($nrows > 0)
	{
		echo '  
		<tr>
			<td class="dark" align="center" rowspan="'.$nrows.'">'.$s_title.'</td>
			<td class="grey" align="center" rowspan="'.$nrows.'">';
		if ($formatstatlist[$s_title] == 'decimal')
			echo get_dp($stat_high['x']);
		else if ($formatstatlist[$s_title] == 'minutes')
			echo GetMinutes($stat_high['x']);
		else
			echo $stat_high['x'];
		echo '</td>';
        while ($sql_cstat = mysql_fetch_array($q_cstat))
        {
            echo '
			<td nowrap class="greyhuman" align="center"><a class="greyhuman" href="./?p=pinfo&amp;pid='.$sql_cstat['pid'].'">'.FlagImage($sql_cstat['country'], false).' '.$sql_cstat['name'].'</a></td>
			<td class="grey" align="center">'.sec2hour($sql_cstat['sumgametime']).'</td>
			<td class="grey" align="center">'.$sql_cstat['mcount'].'</td>
		</tr>';
        }
	}
}

/*/
$sql_chighfrags = small_query("SELECT p.pid, pi.name, p.country, SUM(frags) AS frags , SUM(gametime) AS sumgametime, COUNT(matchid) AS mcount FROM uts_player AS p, uts_pinfo AS pi WHERE p.pid = pi.id AND pi.banned <> 'Y' GROUP BY pid HAVING sumgametime > 1800 ORDER BY frags DESC LIMIT 0,1");

$sql_chighdeaths = small_query("SELECT p.pid, pi.name, p.country, SUM(deaths) AS deaths , SUM(gametime) AS sumgametime, COUNT(matchid) AS mcount FROM uts_player AS p, uts_pinfo AS pi WHERE p.pid = pi.id AND pi.banned <> 'Y' GROUP BY pid HAVING sumgametime > 1800 ORDER BY deaths DESC LIMIT 0,1");

$sql_chighkills = small_query("SELECT p.pid, pi.name, p.country, SUM(kills) AS kills , SUM(gametime) AS sumgametime, COUNT(matchid) AS mcount FROM uts_player AS p, uts_pinfo AS pi WHERE p.pid = pi.id AND pi.banned <> 'Y' GROUP BY pid HAVING sumgametime > 1800 ORDER BY kills DESC LIMIT 0,1");

$sql_chighsuicides = small_query("SELECT p.pid, pi.name, p.country, SUM(suicides) AS suicides , SUM(gametime) AS sumgametime, COUNT(p.matchid) AS mcount FROM uts_player AS p, uts_pinfo AS pi WHERE p.pid = pi.id AND pi.banned <> 'Y' GROUP BY pid HAVING sumgametime > 1800 ORDER BY suicides DESC LIMIT 0,1");

$sql_chighteamkills = small_query("SELECT p.pid, pi.name, p.country, SUM(teamkills) AS teamkills , SUM(gametime) AS sumgametime, COUNT(p.matchid) AS mcount FROM uts_player AS p, uts_pinfo AS pi WHERE p.pid = pi.id AND pi.banned <> 'Y' GROUP BY pid HAVING sumgametime > 1800 ORDER BY teamkills DESC LIMIT 0,1");

$sql_chighheadshots = small_query("SELECT p.pid, pi.name, p.country, SUM(headshots) AS headshots , SUM(gametime) AS sumgametime, COUNT(p.matchid) AS mcount FROM uts_player AS p, uts_pinfo AS pi WHERE p.pid = pi.id AND pi.banned <> 'Y' GROUP BY pid HAVING sumgametime > 1800 ORDER BY headshots DESC LIMIT 0,1");

$sql_chigheff = small_query("SELECT p.pid, pi.name, p.country, (100 * SUM(kills) / (SUM(kills) + SUM(deaths) + SUM(suicides) + SUM(teamkills))) AS eff , SUM(gametime) AS sumgametime, COUNT(p.matchid) AS mcount FROM uts_player AS p, uts_pinfo AS pi WHERE p.pid = pi.id AND pi.banned <> 'Y' GROUP BY pid HAVING sumgametime > 1800 ORDER BY eff DESC LIMIT 0,1");

$sql_chighaccuracy = small_query("SELECT p.pid, pi.name, p.country, (100 * SUM(ws.hits) / SUM(ws.shots)) AS accuracy, SUM(p.gametime) AS sumgametime, COUNT(p.matchid) AS mcount FROM uts_player AS p, uts_pinfo AS pi, uts_weaponstats AS ws WHERE ws.pid = pi.id AND p.pid = ws.pid AND p.pid = pi.id AND ws.matchid = 0 AND ws.weapon = 0 AND pi.banned <> 'Y' GROUP BY ws.pid HAVING sumgametime > 1800 ORDER BY accuracy DESC LIMIT 0,1");

$sql_chighttl = small_query("SELECT p.pid, pi.name, p.country, (SUM(gametime)/(SUM(deaths) + SUM(suicides) + COUNT(matchid))) AS ttl , SUM(gametime) AS sumgametime, COUNT(p.matchid) AS mcount FROM uts_player AS p, uts_pinfo AS pi WHERE p.pid = pi.id AND pi.banned <> 'Y' GROUP BY pid HAVING sumgametime > 1800 ORDER BY ttl DESC LIMIT 0,1");

$sql_chighflag_capture = small_query("SELECT p.pid, pi.name, p.country, SUM(flag_capture) AS flag_capture , SUM(gametime) AS sumgametime, COUNT(p.matchid) AS mcount FROM uts_player AS p, uts_pinfo AS pi WHERE p.pid = pi.id AND pi.banned <> 'Y' GROUP BY pid HAVING sumgametime > 1800 ORDER BY flag_capture DESC LIMIT 0,1");

$sql_chighflag_assist = small_query("SELECT p.pid, pi.name, p.country, SUM(flag_assist) AS flag_assist, SUM(gametime) AS sumgametime, COUNT(p.matchid) AS mcount FROM uts_player AS p, uts_pinfo AS pi WHERE p.pid = pi.id AND pi.banned <> 'Y' GROUP BY pid HAVING sumgametime > 1800 ORDER BY flag_assist DESC LIMIT 0,1");

$sql_chighflag_kill = small_query("SELECT p.pid, pi.name, p.country, SUM(flag_kill) AS flag_kill , SUM(gametime) AS sumgametime, COUNT(p.matchid) AS mcount FROM uts_player AS p, uts_pinfo AS pi WHERE p.pid = pi.id AND pi.banned <> 'Y' GROUP BY pid HAVING sumgametime > 1800 ORDER BY flag_kill DESC LIMIT 0,1");

$sql_chighdom_cp = small_query("SELECT p.pid, pi.name, p.country, SUM(dom_cp) AS dom_cp , SUM(gametime) AS sumgametime, COUNT(p.matchid) AS mcount FROM uts_player AS p, uts_pinfo AS pi WHERE p.pid = pi.id AND pi.banned <> 'Y' GROUP BY pid HAVING sumgametime > 1800 ORDER BY dom_cp DESC LIMIT 0,1");

$sql_chighass_obj = small_query("SELECT p.pid, pi.name, p.country, SUM(ass_obj) AS ass_obj , SUM(gametime) AS sumgametime, COUNT(p.matchid) AS mcount FROM uts_player AS p, uts_pinfo AS pi WHERE p.pid = pi.id AND pi.banned <> 'Y' GROUP BY pid HAVING sumgametime > 1800 ORDER BY ass_obj DESC LIMIT 0,1");

$sql_chighspree_monster = small_query("SELECT p.pid, pi.name, p.country, SUM(spree_monster) AS spree_monster , SUM(gametime) AS sumgametime, COUNT(p.matchid) AS mcount FROM uts_player AS p, uts_pinfo AS pi WHERE p.pid = pi.id AND pi.banned <> 'Y' GROUP BY pid HAVING sumgametime > 1800 ORDER BY spree_monster DESC LIMIT 0,1");

$sql_chighspree_god = small_query("SELECT p.pid, pi.name, p.country, SUM(spree_god) AS spree_god , SUM(gametime) AS sumgametime, COUNT(p.matchid) AS mcount FROM uts_player AS p, uts_pinfo AS pi WHERE p.pid = pi.id AND pi.banned <> 'Y' GROUP BY pid HAVING sumgametime > 1800 ORDER BY spree_god DESC LIMIT 0,1");

$sql_chighrank = small_query("SELECT p.pid, pi.name, p.country, SUM(rank) AS rank , SUM(gametime) AS sumgametime, COUNT(p.matchid) AS mcount FROM uts_player AS p, uts_pinfo AS pi WHERE p.pid = pi.id AND pi.banned <> 'Y' GROUP BY pid HAVING sumgametime > 1800 ORDER BY rank DESC LIMIT 0,1");

if ($sql_chighfrags and $sql_chighfrags['frags']) {
   echo '  
  <tr>
    <td class="dark" align="center">Frags</td>
    <td nowrap class="greyhuman" align="center"><a class="greyhuman" href="./?p=pinfo&amp;pid='.$sql_chighfrags['pid'].'">'.FlagImage($sql_chighfrags['country'], false).' '.$sql_chighfrags['name'].'</a></td>
    <td class="grey" align="center">'.$sql_chighfrags['frags'].'</td>
    <td class="grey" align="center">'.sec2hour($sql_chighfrags['sumgametime']).'</td>
    <td class="grey" align="center">'.$sql_chighfrags['mcount'].'</td>
  </tr>';
}
if ($sql_chighdeaths and $sql_chighdeaths[deaths]) {
   echo '  
  <tr>
    <td class="dark" align="center">Deaths</td>
    <td nowrap class="greyhuman" align="center"><a class="greyhuman" href="./?p=pinfo&amp;pid='.$sql_chighdeaths[pid].'">'.FlagImage($sql_chighdeaths[country], false).' '.$sql_chighdeaths[name].'</a></td>
    <td class="grey" align="center">'.$sql_chighdeaths[deaths].'</td>
    <td class="grey" align="center">'.sec2hour($sql_chighdeaths[sumgametime]).'</td>
    <td class="grey" align="center">'.$sql_chighdeaths[mcount].'</td>
  </tr>';
}
if ($sql_chighkills and $sql_chighkills[kills]) {
   echo '  
  <tr>
    <td class="dark" align="center">Kills</td>
    <td nowrap class="greyhuman" align="center"><a class="greyhuman" href="./?p=pinfo&amp;pid='.$sql_chighkills[pid].'">'.FlagImage($sql_chighkills[country], false).' '.$sql_chighkills[name].'</a></td>
    <td class="grey" align="center">'.$sql_chighkills[kills].'</td>
    <td class="grey" align="center">'.sec2hour($sql_chighkills[sumgametime]).'</td>
    <td class="grey" align="center">'.$sql_chighkills[mcount].'</td>
  </tr>';
}
if ($sql_chighsuicides and $sql_chighsuicides[suicides]) {
   echo '  
  <tr>
    <td class="dark" align="center">Suicides</td>
    <td nowrap class="greyhuman" align="center"><a class="greyhuman" href="./?p=pinfo&amp;pid='.$sql_chighsuicides[pid].'">'.FlagImage($sql_chighsuicides[country], false).' '.$sql_chighsuicides[name].'</a></td>
    <td class="grey" align="center">'.$sql_chighsuicides[suicides].'</td>
    <td class="grey" align="center">'.sec2hour($sql_chighsuicides[sumgametime]).'</td>
    <td class="grey" align="center">'.$sql_chighsuicides[mcount].'</td>
  </tr>';
}
if ($sql_chighteamkills and $sql_chighteamkills[teamkills]) {
   echo '  
  <tr>
    <td class="dark" align="center">Team Kills</td>
    <td nowrap class="greyhuman" align="center"><a class="greyhuman" href="./?p=pinfo&amp;pid='.$sql_chighteamkills[pid].'">'.FlagImage($sql_chighteamkills[country], false).' '.$sql_chighteamkills[name].'</a></td>
    <td class="grey" align="center">'.$sql_chighteamkills[teamkills].'</td>
    <td class="grey" align="center">'.sec2hour($sql_chighteamkills[sumgametime]).'</td>
    <td class="grey" align="center">'.$sql_chighteamkills[mcount].'</td>
  </tr>';
}
if ($sql_chigheff and $sql_chigheff[eff]) {
   echo '  
  <tr>
    <td class="dark" align="center">Efficiency</td>
    <td nowrap class="greyhuman" align="center"><a class="greyhuman" href="./?p=pinfo&amp;pid='.$sql_chigheff[pid].'">'.FlagImage($sql_chigheff[country], false).' '.$sql_chigheff[name].'</a></td>
    <td class="grey" align="center">'.get_dp($sql_chigheff[eff]).'</td>
    <td class="grey" align="center">'.sec2hour($sql_chigheff[sumgametime]).'</td>
    <td class="grey" align="center">'.$sql_chigheff[mcount].'</td>
  </tr>';
}
if ($sql_chighaccuracy and $sql_chighaccuracy[accuracy]) {
   echo '  
  <tr>
    <td class="dark" align="center">Accuracy</td>
    <td nowrap class="greyhuman" align="center"><a class="greyhuman" href="./?p=pinfo&amp;pid='.$sql_chighaccuracy[pid].'">'.FlagImage($sql_chighaccuracy[country], false).' '.$sql_chighaccuracy[name].'</a></td>
    <td class="grey" align="center">'.get_dp($sql_chighaccuracy[accuracy]).'</td>
    <td class="grey" align="center">'.sec2hour($sql_chighaccuracy[sumgametime]).'</td>
    <td class="grey" align="center">'.$sql_chighaccuracy[mcount].'</td>
  </tr>';
}
if ($sql_chighttl and $sql_chighttl[ttl]) {
   echo '  
  <tr>
    <td class="dark" align="center">TTL</td>
    <td nowrap class="greyhuman" align="center"><a class="greyhuman" href="./?p=pinfo&amp;pid='.$sql_chighttl[pid].'">'.FlagImage($sql_chighttl[country], false).' '.$sql_chighttl[name].'</a></td>
    <td class="grey" align="center">'.GetMinutes($sql_chighttl[ttl]).'</td>
    <td class="grey" align="center">'.sec2hour($sql_chighttl[sumgametime]).'</td>
    <td class="grey" align="center">'.$sql_chighttl[mcount].'</td>
  </tr>';
}
if ($sql_chighheadshots and $sql_chighheadshots[headshots]) {
   echo '  
  <tr>
    <td class="dark" align="center">Head Shots</td>
    <td nowrap class="greyhuman" align="center"><a class="greyhuman" href="./?p=pinfo&amp;pid='.$sql_chighheadshots[pid].'">'.FlagImage($sql_chighheadshots[country], false).' '.$sql_chighheadshots[name].'</a></td>
    <td class="grey" align="center">'.$sql_chighheadshots[headshots].'</td>
    <td class="grey" align="center">'.sec2hour($sql_chighheadshots[sumgametime]).'</td>
    <td class="grey" align="center">'.$sql_chighheadshots[mcount].'</td>
  </tr>';
}
if ($sql_chighflag_capture and $sql_chighflag_capture[flag_capture]) {
   echo '  
  <tr>
    <td class="dark" align="center">Flag Caps</td>
    <td nowrap class="greyhuman" align="center"><a class="greyhuman" href="./?p=pinfo&amp;pid='.$sql_chighflag_capture[pid].'">'.FlagImage($sql_chighflag_capture[country], false).' '.$sql_chighflag_capture[name].'</a></td>
    <td class="grey" align="center">'.$sql_chighflag_capture[flag_capture].'</td>
    <td class="grey" align="center">'.sec2hour($sql_chighflag_capture[sumgametime]).'</td>
    <td class="grey" align="center">'.$sql_chighflag_capture[mcount].'</td>
  </tr>';
}
if ($sql_chighflag_assist and $sql_chighflag_assist[flag_assist]) {
	echo'
	<tr>
		<td class="dark" align="center">Flag Assists</td>
    	<td nowrap class="greyhuman" align="center"><a class="greyhuman" href="./?p=pinfo&amp;pid='.$sql_chighflag_assist[pid].'">'.FlagImage($sql_chighflag_assist[country], false).' '.$sql_chighflag_assist[name].'</a></td>
    	<td class="grey" align="center">'.$sql_chighflag_assist[flag_assist].'</td>
    	<td class="grey" align="center">'.sec2hour($sql_chighflag_assist[sumgametime]).'</td>
    	<td class="grey" align="center">'.$sql_chighflag_assist[mcount].'</td>
	</tr>';
}
if ($sql_chighflag_kill and $sql_chighflag_kill[flag_kill]) {
   echo '  
  <tr>
    <td class="dark" align="center">Flag Kills</td>
    <td nowrap class="greyhuman" align="center"><a class="greyhuman" href="./?p=pinfo&amp;pid='.$sql_chighflag_kill[pid].'">'.FlagImage($sql_chighflag_kill[country], false).' '.$sql_chighflag_kill[name].'</a></td>
    <td class="grey" align="center">'.$sql_chighflag_kill[flag_kill].'</td>
    <td class="grey" align="center">'.sec2hour($sql_chighflag_kill[sumgametime]).'</td>
    <td class="grey" align="center">'.$sql_chighflag_kill[mcount].'</td>
  </tr>';
}
if ($sql_chighdom_cp and $sql_chighdom_cp[dom_cp]) {
   echo '  
  <tr>
    <td class="dark" align="center">Domination Control Points</td>
    <td nowrap class="greyhuman" align="center"><a class="greyhuman" href="./?p=pinfo&amp;pid='.$sql_chighdom_cp[pid].'">'.FlagImage($sql_chighdom_cp[country], false).' '.$sql_chighdom_cp[name].'</a></td>
    <td class="grey" align="center">'.$sql_chighdom_cp[dom_cp].'</td>
    <td class="grey" align="center">'.sec2hour($sql_chighdom_cp[sumgametime]).'</td>
    <td class="grey" align="center">'.$sql_chighdom_cp[mcount].'</td>
  </tr>';
}
if ($sql_chighass_obj and $sql_chighass_obj[ass_obj]) {
   echo '  
  <tr>
    <td class="dark" align="center">Assault Objectives</td>
    <td nowrap class="greyhuman" align="center"><a class="greyhuman" href="./?p=pinfo&amp;pid='.$sql_chighass_obj[pid].'">'.FlagImage($sql_chighass_obj[country], false).' '.$sql_chighass_obj[name].'</a></td>
    <td class="grey" align="center">'.$sql_chighass_obj[ass_obj].'</td>
    <td class="grey" align="center">'.sec2hour($sql_chighass_obj[sumgametime]).'</td>
    <td class="grey" align="center">'.$sql_chighass_obj[mcount].'</td>
  </tr>';
}
if ($sql_chighspree_monster and $sql_chighspree_monster[spree_monster]) {
   echo '  
  <tr>
    <td class="dark" align="center">Monster Kills</td>
    <td nowrap class="greyhuman" align="center"><a class="greyhuman" href="./?p=pinfo&amp;pid='.$sql_chighspree_monster[pid].'">'.FlagImage($sql_chighspree_monster[country], false).' '.$sql_chighspree_monster[name].'</a></td>
    <td class="grey" align="center">'.$sql_chighspree_monster[spree_monster].'</td>
    <td class="grey" align="center">'.sec2hour($sql_chighspree_monster[sumgametime]).'</td>
    <td class="grey" align="center">'.$sql_chighspree_monster[mcount].'</td>
  </tr>';
}
if ($sql_chighspree_god and $sql_chighspree_god[spree_god]) {
   echo '  
  <tr>
    <td class="dark" align="center">Godlikes</td>
    <td nowrap class="greyhuman" align="center"><a class="greyhuman" href="./?p=pinfo&amp;pid='.$sql_chighspree_god[pid].'">'.FlagImage($sql_chighspree_god[country], false).' '.$sql_chighspree_god[name].'</a></td>
    <td class="grey" align="center">'.$sql_chighspree_god[spree_god].'</td>
    <td class="grey" align="center">'.sec2hour($sql_chighspree_god[sumgametime]).'</td>
    <td class="grey" align="center">'.$sql_chighspree_god[mcount].'</td>
  </tr>';
}
if ($sql_chighrank and $sql_chighrank[rank]) {
   echo '  
  <tr>
    <td class="dark" align="center">Rank Points</td>
    <td nowrap class="greyhuman" align="center"><a class="greyhuman" href="./?p=pinfo&amp;pid='.$sql_chighrank[pid].'">'.FlagImage($sql_chighrank[country], false).' '.$sql_chighrank[name].'</a></td>
    <td class="grey" align="center">'.get_dp($sql_chighrank[rank]).'</td>
    <td class="grey" align="center">'.sec2hour($sql_chighrank[sumgametime]).'</td>
    <td class="grey" align="center">'.$sql_chighrank[mcount].'</td>
  </tr>';
}
//*/
echo '
</tbody></table>
<br>';

// NGStats Style Total Highs (Single Match)

echo'<table border="0" cellpadding="1" cellspacing="2" width="500">
  <tbody>
  <tr>
    <td class="medheading" colspan="4" align="center">Match Highs</td>
  </tr>
  <tr>
    <td class="smheading" align="center" width="200">Category</td>
    <td class="smheading" align="center" width="50">Amount</td>
    <td class="smheading" align="center" width="200">Player</td>
    <td class="smheading" align="center" width="50">Matches</td>
  </tr>';


foreach ($matchstatlist as $s_title => $s_formula)
{
    $stat_high = small_query("SELECT @max := max($s_formula) AS x FROM uts_player as p LEFT JOIN uts_weaponstats AS ws ON (ws.pid = p.pid AND ws.matchid = p.matchid AND ws.weapon = 0)");
	$q_cstat = mysql_query("SELECT COUNT(p.matchid) as times, p.pid, pi.name, p.country, MAX($s_formula) AS stat FROM uts_player AS p, uts_pinfo AS pi, uts_weaponstats AS ws WHERE ws.pid = pi.id AND p.pid = pi.id AND ws.matchid = p.matchid AND ws.weapon = 0 AND pi.banned <> 'Y' AND p.gametime > 60 AND $s_formula > 0 AND $s_formula = @max GROUP BY p.pid ORDER BY times DESC, pi.name ASC");

    $nrows = mysql_num_rows($q_cstat);
	if ($nrows > 0)
	{
		echo '  
		<tr>
			<td class="dark" align="center" rowspan="'.$nrows.'">'.$s_title.'</td>
            <td class="grey" align="center" rowspan="'.$nrows.'">';
        if ($formatstatlist[$s_title] == 'decimal')
            echo get_dp($stat_high['x']);
        else if ($formatstatlist[$s_title] == 'minutes')
            echo GetMinutes($stat_high['x']);
        else
            echo $stat_high['x'];
        echo '</td>';
        while ($sql_cstat = mysql_fetch_array($q_cstat))
        {
            echo '
           <td nowrap class="greyhuman" align="center"><a class="greyhuman" href="./?p=pinfo&amp;pid='.$sql_cstat['pid'].'">'.FlagImage($sql_cstat['country'], false).' '.$sql_cstat['name'].'</a></td>
            <td class="grey" align="center">'.$sql_cstat['times'].'</td>
        </tr>';
        }
	}
}
/*/
//$sql_mhighfrags = small_query("SELECT p.matchid, p.pid, pi.name, p.country, frags , gametime FROM uts_player AS p, uts_pinfo AS pi WHERE p.pid = pi.id AND pi.banned <> 'Y' AND frags > 0 AND gametime > 60 ORDER BY frags DESC LIMIT 0,1");

run_query("SELECT @frags := MAX(frags) from uts_player");
$q_mhighfrags = run_query("SELECT p.matchid, p.pid, pi.name, p.country, frags , gametime FROM uts_player AS p, uts_pinfo AS pi WHERE p.pid = pi.id AND pi.banned <> 'Y' AND frags > 0 AND gametime > 60 AND frags = @frags");
//if ($sql_mhighfrags) {
while ($sql_mhighfrags = mysql_fetch_array($q_mhighfrags)) {
   echo '  
  <tr>
    <td class="dark" align="center">Frags</td>
    <td nowrap class="greyhuman" align="center"><a class="greyhuman" href="./?p=pinfo&amp;pid='.$sql_mhighfrags[pid].'">'.FlagImage($sql_mhighfrags[country], false).' '.$sql_mhighfrags[name].'</a></td>
    <td class="grey" align="center">'.$sql_mhighfrags[frags].'</td>
    <td class="grey" align="center"><a class="greyhuman" href="./?p=match&amp;mid='.$sql_mhighfrags[matchid].'">(click)</a></td>
  </tr>';
}

$sql_mhighdeaths = small_query("SELECT p.matchid, p.pid, pi.name, p.country, deaths , gametime FROM uts_player AS p, uts_pinfo AS pi WHERE p.pid = pi.id AND pi.banned <> 'Y' AND deaths > 0 AND gametime > 60 ORDER BY deaths DESC LIMIT 0,1");

if ($sql_mhighdeaths) {
   echo '  
  <tr>
    <td class="dark" align="center">Deaths</td>
    <td nowrap class="greyhuman" align="center"><a class="greyhuman" href="./?p=pinfo&amp;pid='.$sql_mhighdeaths[pid].'">'.FlagImage($sql_mhighdeaths[country], false).' '.$sql_mhighdeaths[name].'</a></td>
    <td class="grey" align="center">'.$sql_mhighdeaths[deaths].'</td>
    <td class="grey" align="center"><a class="greyhuman" href="./?p=match&amp;mid='.$sql_mhighdeaths[matchid].'">(click)</a></td>
  </tr>';
}

$sql_mhighkills = small_query("SELECT p.matchid, p.pid, pi.name, p.country, kills , gametime FROM uts_player AS p, uts_pinfo AS pi WHERE p.pid = pi.id AND pi.banned <> 'Y' AND kills > 0 AND gametime > 60 ORDER BY kills DESC LIMIT 0,1");

if ($sql_mhighkills) {
   echo '  
  <tr>
    <td class="dark" align="center">Kills</td>
    <td nowrap class="greyhuman" align="center"><a class="greyhuman" href="./?p=pinfo&amp;pid='.$sql_mhighkills[pid].'">'.FlagImage($sql_mhighkills[country], false).' '.$sql_mhighkills[name].'</a></td>
    <td class="grey" align="center">'.$sql_mhighkills[kills].'</td>
    <td class="grey" align="center"><a class="greyhuman" href="./?p=match&amp;mid='.$sql_mhighkills[matchid].'">(click)</a></td>
  </tr>';
}

$sql_mhighsuicides = small_query("SELECT p.matchid, p.pid, pi.name, p.country, suicides , gametime FROM uts_player AS p, uts_pinfo AS pi WHERE p.pid = pi.id AND pi.banned <> 'Y' AND suicides > 0 AND gametime > 60 ORDER BY suicides DESC LIMIT 0,1");

if ($sql_mhighsuicides) {
   echo '  
  <tr>
    <td class="dark" align="center">Suicides</td>
    <td nowrap class="greyhuman" align="center"><a class="greyhuman" href="./?p=pinfo&amp;pid='.$sql_mhighsuicides[pid].'">'.FlagImage($sql_mhighsuicides[country], false).' '.$sql_mhighsuicides[name].'</a></td>
    <td class="grey" align="center">'.$sql_mhighsuicides[suicides].'</td>
    <td class="grey" align="center"><a class="greyhuman" href="./?p=match&amp;mid='.$sql_mhighsuicides[matchid].'">(click)</a></td>
  </tr>';
}

$sql_mhighteamkills = small_query("SELECT p.matchid, p.pid, pi.name, p.country, teamkills , gametime FROM uts_player AS p, uts_pinfo AS pi WHERE p.pid = pi.id AND pi.banned <> 'Y' AND teamkills > 0 AND gametime > 60 ORDER BY teamkills DESC LIMIT 0,1");

if ($sql_mhighteamkills) {
   echo '  
  <tr>
    <td class="dark" align="center">Team Kills</td>
    <td nowrap class="greyhuman" align="center"><a class="greyhuman" href="./?p=pinfo&amp;pid='.$sql_mhighteamkills[pid].'">'.FlagImage($sql_mhighteamkills[country], false).' '.$sql_mhighteamkills[name].'</a></td>
    <td class="grey" align="center">'.$sql_mhighteamkills[teamkills].'</td>
    <td class="grey" align="center"><a class="greyhuman" href="./?p=match&amp;mid='.$sql_mhighteamkills[matchid].'">(click)</a></td>
  </tr>';
}

$sql_mhigheff = small_query("SELECT p.matchid, p.pid, pi.name, p.country, eff , gametime FROM uts_player AS p, uts_pinfo AS pi WHERE p.pid = pi.id AND pi.banned <> 'Y' AND eff > 0 AND gametime > 600 ORDER BY eff DESC LIMIT 0,1");

if ($sql_mhigheff) {
   echo '  
  <tr>
    <td class="dark" align="center">Efficiency</td>
    <td nowrap class="greyhuman" align="center"><a class="greyhuman" href="./?p=pinfo&amp;pid='.$sql_mhigheff[pid].'">'.FlagImage($sql_mhigheff[country], false).' '.$sql_mhigheff[name].'</a></td>
    <td class="grey" align="center">'.get_dp($sql_mhigheff[eff]).'</td>
    <td class="grey" align="center"><a class="greyhuman" href="./?p=match&amp;mid='.$sql_mhigheff[matchid].'">(click)</a></td>
  </tr>';
}

$sql_mhighaccuracy = small_query("SELECT p.matchid, p.pid, pi.name, p.country, ws.acc as accuracy, gametime FROM uts_player AS p, uts_pinfo AS pi, uts_weaponstats as ws WHERE ws.pid = p.pid AND p.pid = pi.id AND p.matchid = ws.matchid AND pi.banned <> 'Y' AND accuracy > 0 AND gametime > 60 AND ws.weapon = 0 AND ws.matchid > 0 ORDER BY accuracy DESC LIMIT 0,1");

if ($sql_mhighaccuracy) {
   echo '  
  <tr>
    <td class="dark" align="center">Accuracy</td>
    <td nowrap class="greyhuman" align="center"><a class="greyhuman" href="./?p=pinfo&amp;pid='.$sql_mhighaccuracy[pid].'">'.FlagImage($sql_mhighaccuracy[country], false).' '.$sql_mhighaccuracy[name].'</a></td>
    <td class="grey" align="center">'.get_dp($sql_mhighaccuracy[accuracy]).'</td>
    <td class="grey" align="center"><a class="greyhuman" href="./?p=match&amp;mid='.$sql_mhighaccuracy[matchid].'">(click)</a></td>
  </tr>';
}

$sql_mhighttl = small_query("SELECT p.matchid, p.pid, pi.name, p.country, (gametime/(deaths + suicides + 1)) AS ttl , gametime FROM uts_player AS p, uts_pinfo AS pi WHERE p.pid = pi.id AND pi.banned <> 'Y' AND ttl > 0 AND gametime > 60 ORDER BY ttl DESC LIMIT 0,1");

if ($sql_mhighttl) {
   echo '  
  <tr>
    <td class="dark" align="center">TTL</td>
    <td nowrap class="greyhuman" align="center"><a class="greyhuman" href="./?p=pinfo&amp;pid='.$sql_mhighttl[pid].'">'.FlagImage($sql_mhighttl[country], false).' '.$sql_mhighttl[name].'</a></td>
    <td class="grey" align="center">'.GetMinutes($sql_mhighttl[ttl]).'</td>
    <td class="grey" align="center"><a class="greyhuman" href="./?p=match&amp;mid='.$sql_mhighttl[matchid].'">(click)</a></td>
  </tr>';
}

$sql_mhighheadshots = small_query("SELECT p.matchid, p.pid, pi.name, p.country, headshots , gametime FROM uts_player AS p, uts_pinfo AS pi WHERE p.pid = pi.id AND pi.banned <> 'Y' AND headshots > 0 AND gametime > 60 ORDER BY headshots DESC LIMIT 0,1");

if ($sql_mhighheadshots) {
   echo '  
  <tr>
    <td class="dark" align="center">Head Shots</td>
    <td nowrap class="greyhuman" align="center"><a class="greyhuman" href="./?p=pinfo&amp;pid='.$sql_mhighheadshots[pid].'">'.FlagImage($sql_mhighheadshots[country], false).' '.$sql_mhighheadshots[name].'</a></td>
    <td class="grey" align="center">'.$sql_mhighheadshots[headshots].'</td>
    <td class="grey" align="center"><a class="greyhuman" href="./?p=match&amp;mid='.$sql_mhighheadshots[matchid].'">(click)</a></td>
  </tr>';
}

//$sql_mhighflag_capture = small_query("SELECT p.matchid, p.pid, pi.name, p.country, flag_capture , gametime FROM uts_player AS p, uts_pinfo AS pi WHERE p.pid = pi.id AND pi.banned <> 'Y' AND flag_capture > 0 AND gametime > 60 ORDER BY flag_capture DESC LIMIT 0,1");

run_query("SELECT @caps := MAX(flag_capture) from uts_player");
$q_mhighflag_capture = run_query("SELECT p.matchid, p.pid, pi.name, p.country, flag_capture , gametime FROM uts_player AS p, uts_pinfo AS pi WHERE p.pid = pi.id AND pi.banned <> 'Y' AND flag_capture > 0 AND gametime > 60 AND flag_capture = @caps");

$sql_mhighflag_capture = mysql_fetch_array($q_mhighflag_capture);
if ($sql_mhighflag_capture)
{
  echo '
  <tr>
    <td class="dark" align="center" rowspan='.mysql_num_rows($q_mhighflag_capture).'>Flag Caps</td>
    <td nowrap class="greyhuman" align="center"><a class="greyhuman" href="./?p=pinfo&amp;pid='.$sql_mhighflag_capture[pid].'">'.FlagImage($sql_mhighflag_capture[country], false).' '.$sql_mhighflag_capture[name].'</a></td>
    <td class="grey" align="center" rowspan='.mysql_num_rows($q_mhighflag_capture).'>'.$sql_mhighflag_capture[flag_capture].'</td>
    <td class="grey" align="center"><a class="greyhuman" href="./?p=match&amp;mid='.$sql_mhighflag_capture[matchid].'">(click)</a></td>
  </tr>';
  
  while ($sql_mhighflag_capture = mysql_fetch_array($q_mhighflag_capture))
  {
    echo '  
  <tr>
    <td nowrap class="greyhuman" align="center"><a class="greyhuman" href="./?p=pinfo&amp;pid='.$sql_mhighflag_capture[pid].'">'.FlagImage($sql_mhighflag_capture[country], false).' '.$sql_mhighflag_capture[name].'</a></td>
    <td class="grey" align="center"><a class="greyhuman" href="./?p=match&amp;mid='.$sql_mhighflag_capture[matchid].'">(click)</a></td>
  </tr>';
  }
}

$sql_mhighflag_assist = small_query("SELECT p.matchid, p.pid, pi.name, p.country, flag_assist , gametime FROM uts_player AS p, uts_pinfo AS pi WHERE p.pid = pi.id AND pi.banned <> 'Y' AND flag_capture > 0 AND gametime > 60 ORDER BY flag_assist DESC LIMIT 0,1");

if ($sql_mhighflag_assist) {
   echo '  
  <tr>
    <td class="dark" align="center">Flag Assists</td>
    <td nowrap class="greyhuman" align="center"><a class="greyhuman" href="./?p=pinfo&amp;pid='.$sql_mhighflag_assist[pid].'">'.FlagImage($sql_mhighflag_assist[country], false).' '.$sql_mhighflag_assist[name].'</a></td>
    <td class="grey" align="center">'.$sql_mhighflag_assist[flag_assist].'</td>
    <td class="grey" align="center"><a class="greyhuman" href="./?p=match&amp;mid='.$sql_mhighflag_assist[matchid].'">(click)</a></td>
  </tr>';
}

$sql_mhighflag_kill = small_query("SELECT p.matchid, p.pid, pi.name, p.country, flag_kill , gametime FROM uts_player AS p, uts_pinfo AS pi WHERE p.pid = pi.id AND pi.banned <> 'Y' AND flag_kill > 0 AND gametime > 60 ORDER BY flag_kill DESC LIMIT 0,1");

if ($sql_mhighflag_kill) {
   echo '  
  <tr>
    <td class="dark" align="center">Flag Kills</td>
    <td nowrap class="greyhuman" align="center"><a class="greyhuman" href="./?p=pinfo&amp;pid='.$sql_mhighflag_kill[pid].'">'.FlagImage($sql_mhighflag_kill[country], false).' '.$sql_mhighflag_kill[name].'</a></td>
    <td class="grey" align="center">'.$sql_mhighflag_kill[flag_kill].'</td>
    <td class="grey" align="center"><a class="greyhuman" href="./?p=match&amp;mid='.$sql_mhighflag_kill[matchid].'">(click)</a></td>
  </tr>';
}

$sql_mhighdom_cp = small_query("SELECT p.matchid, p.pid, pi.name, p.country, dom_cp , gametime FROM uts_player AS p, uts_pinfo AS pi WHERE p.pid = pi.id AND pi.banned <> 'Y' AND dom_cp > 0 AND gametime > 60 ORDER BY dom_cp DESC LIMIT 0,1");

if ($sql_mhighdom_cp) {
   echo '  
  <tr>
    <td class="dark" align="center">Domination Control Points</td>
    <td nowrap class="greyhuman" align="center"><a class="greyhuman" href="./?p=pinfo&amp;pid='.$sql_mhighdom_cp[pid].'">'.FlagImage($sql_mhighdom_cp[country], false).' '.$sql_mhighdom_cp[name].'</a></td>
    <td class="grey" align="center">'.$sql_mhighdom_cp[dom_cp].'</td>
    <td class="grey" align="center"><a class="greyhuman" href="./?p=match&amp;mid='.$sql_mhighdom_cp[matchid].'">(click)</a></td>
  </tr>';
}

$sql_mhighass_obj = small_query("SELECT p.matchid, p.pid, pi.name, p.country, ass_obj , gametime FROM uts_player AS p, uts_pinfo AS pi WHERE p.pid = pi.id AND pi.banned <> 'Y' AND ass_obj > 0 AND gametime > 60 ORDER BY ass_obj DESC LIMIT 0,1");

if ($sql_mhighass_obj) {
   echo '  
  <tr>
    <td class="dark" align="center">Assault Objectives</td>
    <td nowrap class="greyhuman" align="center"><a class="greyhuman" href="./?p=pinfo&amp;pid='.$sql_mhighass_obj[pid].'">'.FlagImage($sql_mhighass_obj[country], false).' '.$sql_mhighass_obj[name].'</a></td>
    <td class="grey" align="center">'.$sql_mhighass_obj[ass_obj].'</td>
    <td class="grey" align="center"><a class="greyhuman" href="./?p=match&amp;mid='.$sql_mhighass_obj[matchid].'">(click)</a></td>
  </tr>';
}

$sql_mhighspree_monster = small_query("SELECT p.matchid, p.pid, pi.name, p.country, spree_monster , gametime FROM uts_player AS p, uts_pinfo AS pi WHERE p.pid = pi.id AND pi.banned <> 'Y' AND spree_monster > 0 AND gametime > 60 ORDER BY spree_monster DESC LIMIT 0,1");

if ($sql_mhighspree_monster) {
   echo '  
  <tr>
    <td class="dark" align="center">Monster Kills</td>
    <td nowrap class="greyhuman" align="center"><a class="greyhuman" href="./?p=pinfo&amp;pid='.$sql_mhighspree_monster[pid].'">'.FlagImage($sql_mhighspree_monster[country], false).' '.$sql_mhighspree_monster[name].'</a></td>
    <td class="grey" align="center">'.$sql_mhighspree_monster[spree_monster].'</td>
    <td class="grey" align="center"><a class="greyhuman" href="./?p=match&amp;mid='.$sql_mhighspree_monster[matchid].'">(click)</a></td>
  </tr>';
}

$sql_mhighspree_god = small_query("SELECT p.matchid, p.pid, pi.name, p.country, spree_god , gametime FROM uts_player AS p, uts_pinfo AS pi WHERE p.pid = pi.id AND pi.banned <> 'Y' AND spree_god > 0 AND gametime > 60 ORDER BY spree_god DESC LIMIT 0,1");

if ($sql_mhighspree_god) {
   echo '  
  <tr>
    <td class="dark" align="center">Godlikes</td>
    <td nowrap class="greyhuman" align="center"><a class="greyhuman" href="./?p=pinfo&amp;pid='.$sql_mhighspree_god[pid].'">'.FlagImage($sql_mhighspree_god[country], false).' '.$sql_mhighspree_god[name].'</a></td>
    <td class="grey" align="center">'.$sql_mhighspree_god[spree_god].'</td>
    <td class="grey" align="center"><a class="greyhuman" href="./?p=match&amp;mid='.$sql_mhighspree_god[matchid].'">(click)</a></td>
  </tr>';
}

$sql_mhighrank = small_query("SELECT p.matchid, p.pid, pi.name, p.country, rank , gametime FROM uts_player AS p, uts_pinfo AS pi WHERE p.pid = pi.id AND pi.banned <> 'Y' AND rank > 0 AND gametime > 60 ORDER BY rank DESC LIMIT 0,1");

if ($sql_mhighrank) {
   echo '  
  <tr>
    <td class="dark" align="center">Rank Points</td>
    <td nowrap class="greyhuman" align="center"><a class="greyhuman" href="./?p=pinfo&amp;pid='.$sql_mhighrank[pid].'">'.FlagImage($sql_mhighrank[country], false).' '.$sql_mhighrank[name].'</a></td>
    <td class="grey" align="center">'.get_dp($sql_mhighrank[rank]).'</td>
    <td class="grey" align="center"><a class="greyhuman" href="./?p=match&amp;mid='.$sql_mhighrank[matchid].'">(click)</a></td>
  </tr>';
}
//*/

// NGStats Style Weapon Highs (All Time)

echo '
</tbody></table>
<br>
<table border="0" cellpadding="1" cellspacing="2" width="500">
  <tbody>
<tr>
    <td class="medheading" colspan="4" align="center">Weapon Career Highs</td>
  </tr>
  <tr>
    <td class="smheading" align="center" width="200">Category</td>
    <td class="smheading" align="center" width="50">Kills</td>
    <td class="smheading" align="center" width="200">Player</td>
    <td class="smheading" align="center" width="50">Matches</td>
  </tr>
';

$sql_mweapons = "SELECT id, name, image FROM uts_weapons WHERE hide <> 'Y' ORDER BY sequence, id ASC";
$q_mweapons = mysql_query($sql_mweapons) or die(mysql_error());
while ($r_mweapons = mysql_fetch_array($q_mweapons))
{
	$wid =  $r_mweapons['id'];
	$sql_mweaponsl = "SELECT w.pid AS playerid, pi.name AS name, pi.country AS country, SUM(w.kills) as kills, COUNT(DISTINCT w.matchid) AS mcount FROM uts_weaponstats AS w LEFT JOIN uts_pinfo AS pi ON w.pid = pi.id WHERE w.weapon = '$wid' AND w.pid > 0 AND w.matchid <> 0 AND pi.banned <> 'Y' GROUP BY w.pid ORDER BY kills DESC LIMIT 0,1";
	$q_mweaponsl = mysql_query($sql_mweaponsl) or die(mysql_error());
	while ($r_mweaponsl = mysql_fetch_array($q_mweaponsl))
	{
	      echo '<tr>
		    <td class="dark" align="center">'.$r_mweapons['name'].'</td>
		    <td class="grey" align="center">'.$r_mweaponsl['kills'].'</td>
		    <td class="greyhuman" align="center"><a class="greyhuman" href="./?p=pinfo&amp;pid='.$r_mweaponsl['playerid'].'">'.FlagImage($r_mweaponsl['country'], false).' '.$r_mweaponsl['name'].'</a></td>
		    <td class="grey" align="center">'.$r_mweaponsl['mcount'].'</td>
 		    </tr>';

	}
}

echo '</tbody></table>
<br>';

// NGStats Style Weapon Highs (Single Match)

echo '<table border="0" cellpadding="1" cellspacing="2" width="450">
  <tbody>
<tr>
    <td class="medheading" colspan="4" align="center">Weapon Match Highs</td>
  </tr>
  <tr>
    <td class="smheading" align="center" width="200">Category</td>
    <td class="smheading" align="center" width="50">Kills</td>
    <td class="smheading" align="center" width="200">Player</td>
    <td class="smheading" align="center" width="50">Match</td>
  </tr>
';

//$sql_mweapons = "SELECT id, name, image FROM uts_weapons WHERE hide <> 'Y' ORDER BY sequence, id ASC";
$q_mweapons = mysql_query($sql_mweapons) or die(mysql_error());
while ($r_mweapons = mysql_fetch_array($q_mweapons))
{
	$wid =  $r_mweapons['id'];
	$sql_mweaponsl = "SELECT w.matchid, w.pid AS playerid, pi.name AS name, pi.country AS country, w.kills FROM uts_weaponstats AS w LEFT JOIN uts_pinfo AS pi ON w.pid = pi.id WHERE w.weapon = '$wid' AND w.pid > 0 AND w.matchid > 0 AND pi.banned <> 'Y' ORDER BY w.kills DESC LIMIT 0,1";
	$q_mweaponsl = mysql_query($sql_mweaponsl) or die(mysql_error());
	while ($r_mweaponsl = mysql_fetch_array($q_mweaponsl))
	{
	      echo '<tr>
		    <td class="dark" align="center">'.$r_mweapons['name'].'</td>
		    <td class="grey" align="center">'.$r_mweaponsl['kills'].'</td>
		    <td class="greyhuman" align="center"><a class="greyhuman" href="./?p=pinfo&amp;pid='.$r_mweaponsl['playerid'].'">'.FlagImage($r_mweaponsl['country'], false).' '.$r_mweaponsl['name'].'</a></td>
		    <td class="grey" align="center"><a class="greyhuman" href="./?p=match&amp;mid='.$r_mweaponsl['matchid'].'">(click)</a></td>
 		    </tr>';
	}
}

echo'</tbody></table>';
?>
