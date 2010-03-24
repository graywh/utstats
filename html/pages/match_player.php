<?php
$mid = $_GET[mid];
$pid = $_GET[pid];
$r_infos = small_query("SELECT p.playerid, p.country, pi.name, pi.banned, p.gid, g.name AS gamename FROM uts_player p, uts_pinfo pi, uts_games g WHERE p.gid = g.id AND p.pid = pi.id AND p.pid = '$pid'  AND matchid = '$mid' LIMIT 0,1;");

if (!$r_infos) {
	echo "Unable to retrieve data!";
	include("includes/footer.php");
	exit;
}
if ($r_infos['banned'] == 'Y') {
	if (isset($is_admin) and $is_admin) {
		echo "Warning: Banned player - Admin override<br>";
	} else {
		echo "Sorry, this player has been banned!";
		include("includes/footer.php");
		exit;
	}
}
	
$playerid = $r_infos['playerid'];
$playername = $r_infos['name'];
$country = $r_infos['country'];
$gamename = $r_infos['gamename'];
$gid = $r_infos['gid'];

echo'
<table border="0" cellpadding="1" cellspacing="2" width="720">
  <tbody><tr>
    <td class="heading" align="center">Individual Match Stats for <a href="./?p=pinfo&amp;pid='.$pid.'">'.FlagImage($country) .' '. htmlentities($playername) .'</a>
	 <span style="font-size: 70%">'. RankImageOrText($pid, $playername, NULL, $gid, $gamename, true, '(%IT% in %GN% with %RP% ranking points)') .'</span>
	 </td>
  </tr>
</tbody></table>
<br>
<table class="box" border="0" cellpadding="1" cellspacing="2">
  <tbody><tr>
    <td class="heading" colspan="6" align="center">Totals for This Match</td>
  </tr>
    <tr>
    <td class="smheading" align="center" width="45">Team Score</td>
    <td class="smheading" align="center" width="50">Player Score</td>
    <td class="smheading" align="center" width="45">Frags</td>
    <td class="smheading" align="center" width="45">Kills</td>
    <td class="smheading" align="center" width="50">Deaths</td>
    <td class="smheading" align="center" width="60">Suicides</td>
  </tr>';

// Get Summary Info
$teamscore = small_query("SELECT SUM(t0score + t1score + t2score + t3score) AS result FROM uts_match WHERE id = $mid");
$playerscore = small_query("SELECT SUM(gamescore) AS result FROM uts_player WHERE matchid = $mid");
$fragcount = small_query("SELECT SUM(frags) AS result FROM uts_match WHERE id = $mid");
$killcount = small_query("SELECT SUM(kills) AS result FROM uts_match WHERE id = $mid");
$deathcount = small_query("SELECT SUM(deaths) AS result FROM uts_player WHERE id = $mid");
$suicidecount = small_query("SELECT SUM(suicides) AS result FROM uts_match WHERE id = $mid");

echo'
  <tr>
    <td class="smheading" align="center" width="45">'.$teamscore[result].'</td>
    <td class="smheading" align="center" width="50">'.$playerscore[result].'</td>
    <td class="smheading" align="center" width="45">'.$fragcount[result].'</td>
    <td class="smheading" align="center" width="45">'.$killcount[result].'</td>
    <td class="smheading" align="center" width="50">'.$deathcount[result].'</td>
    <td class="smheading" align="center" width="60">'.$suicidecount[result].'</td>
  </tr>
</tbody></table>
<br>
<table border="0" cellpadding="1" cellspacing="2" width="720">
  <tbody><tr>
    <td class="heading" colspan="4" align="center">Unreal Tournament Match Stats</td>
  </tr>';

$matchinfo = small_query("SELECT m.time, m.servername, g.name AS gamename, m.mapname, m.mapfile, m.serverinfo, m.gameinfo, m.mutators, m.serverip FROM uts_match m, uts_games g WHERE m.gid = g.id AND m.id = $mid");
$matchdate = mdate($matchinfo[time]);
$gamename = $matchinfo[gamename];

$mapname = un_ut($matchinfo[mapfile]);
$mappic = strtolower("images/maps/".$mapname.".jpg");

if (file_exists($mappic)) {
} else {
   $mappic = ("images/maps/blank.jpg");
}

  $myurl = urlencode($mapname);

  echo'
  <tr>
    <td class="dark" align="center" width="110">Match Date</td>
    <td class="grey" align="center" width="250">'.$matchdate.'</td>
    <td class="dark" align="center" width="110">Server</td>
    <td class="grey" align="center" width="250"><a class="grey" href="./?p=sinfo&amp;serverip='.$matchinfo[serverip].'">'.$matchinfo[servername].'</a></td>
  </tr>
  <tr>
    <td class="dark" align="center">Match Type</td>
    <td class="grey" align="center">'.$gamename.'</td>
    <td class="dark" align="center">Map Name</td>
    <td class="greyhuman" align="center"><a class="grey" href="./?p=minfo&amp;map='.$myurl.'">'.$matchinfo[mapname].'</a></td>
  </tr>
  <tr>
    <td class="dark" align="center">Server Info</td>
    <td class="grey" align="center">'.$matchinfo[serverinfo].'</td>
    <td class="dark" align="center" rowspan="4" colspan="2"><img border="0" alt="'.$mapname.'" title="'.$mapname.'" src="'.$mappic.'"></td>
  </tr>
  <tr>
    <td class="dark" align="center">Game Info</td>
    <td class="grey" align="center">'.$matchinfo[gameinfo].'</td>
  </tr>
  <tr>
    <td class="dark" align="center">Mutators</td>
    <td class="grey" align="center">'.$matchinfo[mutators].'</td>
  </tr>
</tbody></table>
<br>
<table border="0" cellpadding="0" cellspacing="2" width="400">
  <tbody><tr>
    <td class="heading" colspan="8" align="center">Game Summary</td>
  </tr>
  <tr>
    <td class="smheading" align="center" width="40">Frags</td>
    <td class="smheading" align="center" width="40">Kills</td>
    <td class="smheading" align="center" width="50">Deaths</td>
    <td class="smheading" align="center" width="60">Suicides</td>
    <td class="smheading" align="center" width="70">Efficiency</td>
    <td class="smheading" align="center" width="50">Accuracy</td>
    <td class="smheading" align="center" width="50">Avg TTL</td>
    <td class="smheading" align="center" width="50">Time</td>
  </tr>';

$r_gsumm = zero_out(small_query("SELECT gamescore, frags, SUM(frags+suicides) AS kills, deaths, suicides, teamkills, eff, accuracy, ttl, gametime, spree_kill, spree_rampage, spree_dom, spree_uns, spree_god
FROM uts_player WHERE matchid = $mid AND pid = '$pid'
GROUP BY pid"));

  echo'
  <tr>
	<td class="grey" align="center">'.$r_gsumm[frags].'</td>
	<td class="grey" align="center">'.$r_gsumm[kills].'</td>
	<td class="grey" align="center">'.$r_gsumm[deaths].'</td>
	<td class="grey" align="center">'.$r_gsumm[suicides].'</td>
	<td class="grey" align="center">'.$r_gsumm[eff].'</td>
	<td class="grey" align="center">'.$r_gsumm[accuracy].'</td>
	<td class="grey" align="center">'.$r_gsumm[ttl].'</td>
	<td class="grey" align="center">'.GetMinutes($r_gsumm[gametime]).'</td>
  </tr>';

echo'
</tbody></table>
<br>
<table border="0" cellpadding="0" cellspacing="2" width="400">
  <tbody><tr>
    <td class="heading" colspan="10" align="center">Special Events</td>
  </tr>
  <tr>
    <td class="smheading" align="center" rowspan="2" width="40">First Blood</td>
    <td class="smheading" align="center" colspan="4" width="160" '.OverlibPrintHint('Multis').'>Multis</td>
    <td class="smheading" align="center" colspan="5" width="200" '.OverlibPrintHint('Sprees').'>Sprees</td>
  </tr>
  <tr>
    <td class="smheading" align="center" width="40" '.OverlibPrintHint('DK').'>Dbl</td>
    <td class="smheading" align="center" width="40" '.OverlibPrintHint('MK').'>Multi</td>
    <td class="smheading" align="center" width="40" '.OverlibPrintHint('UK').'>Ultra</td>
    <td class="smheading" align="center" width="40" '.OverlibPrintHint('MOK').'>Mons</td>
    <td class="smheading" align="center" width="40" '.OverlibPrintHint('KS').'>Kill</td>
    <td class="smheading" align="center" width="40" '.OverlibPrintHint('RA').'>Ram</td>
    <td class="smheading" align="center" width="40" '.OverlibPrintHint('DO').'>Dom</td>
    <td class="smheading" align="center" width="40" '.OverlibPrintHint('US').'>Uns</td>
    <td class="smheading" align="center" width="40" '.OverlibPrintHint('GL').'>God</td>
  </tr>';

$r_gsumm = zero_out(small_query("SELECT spree_double, spree_multi, spree_ultra, spree_monster, spree_kill, spree_rampage, spree_dom, spree_uns, spree_god
FROM uts_player WHERE matchid = $mid AND pid = '$pid'
GROUP BY pid"));

$sql_firstblood = small_query("SELECT firstblood FROM uts_match WHERE id = $mid");

IF ($sql_firstblood[firstblood] == $pid) {
	$firstblood = "Yes";
} else {
	$firstblood = "No";
}


  echo'
  <tr>
	<td class="grey" align="center">'.$firstblood.'</td>
	<td class="grey" align="center">'.$r_gsumm[spree_double].'</td>
	<td class="grey" align="center">'.$r_gsumm[spree_multi].'</td>
	<td class="grey" align="center">'.$r_gsumm[spree_ultra].'</td>
	<td class="grey" align="center">'.$r_gsumm[spree_monster].'</td>
	<td class="grey" align="center">'.$r_gsumm[spree_kill].'</td>
	<td class="grey" align="center">'.$r_gsumm[spree_rampage].'</td>
	<td class="grey" align="center">'.$r_gsumm[spree_dom].'</td>
	<td class="grey" align="center">'.$r_gsumm[spree_uns].'</td>
	<td class="grey" align="center">'.$r_gsumm[spree_god].'</td>
  </tr>
  </tbody></table>
<br>';

include('includes/weaponstats.php');
weaponstats($mid, $pid);

$r_pings = small_query("SELECT lowping, avgping, highping FROM uts_player WHERE pid = $pid  and matchid = $mid and lowping > 0");
if ($r_pings and $r_pings['lowping']) {
echo '
	<br>
	<table border="0" cellpadding="0" cellspacing="2">
	<tbody><tr>
		<td class="heading" colspan="6" align="center">Pings</td>
	</tr>
	<tr>
		<td class="smheading" align="center" width="80">Min</td>
		<td class="smheading" align="center" width="80">Avg</td>
		<td class="smheading" align="center" width="80">Max</td>
	</tr>
	<tr>
		<td class="grey" align="center">'.ceil($r_pings['lowping']).'</td>
		<td class="grey" align="center">'.ceil($r_pings['avgping']).'</td>
		<td class="grey" align="center">'.ceil($r_pings['highping']).'</td>
	</tr>
	</tbody></table>';
}

?>
