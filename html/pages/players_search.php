<?php
// Get filter and set sorting
$playername = my_stripslashes($_POST[name]);
$playersearch = my_addslashes($_POST[name]);

echo'
<form NAME="playersearch" METHOD="post" ACTION="./?p=psearch">
  <table CLASS="searchformb">
    <tr>
      <td WIDTH="100" ALIGN="right">Name Search:</td>
      <td WIDTH="155" ALIGN="left"><input TYPE="text" NAME="name" MAXLENGTH="35" SIZE="20" CLASS="searchform" VALUE="'.$playername.'"></td>
      <td WIDTH="80" ALIGN="left"><input TYPE="submit" NAME="Default" VALUE="Search" CLASS="searchformb"></td>
    </tr>
  </table>
<div class="opnote">* Enter a Partial Name *</div>
</form>
<table class="box" border="0" cellpadding="1" cellspacing="1">
  <tbody><tr>
    <td class="heading" colspan="11" align="center">Player Search List</td>
  </tr>
  <tr>
    <td class="smheading" align="center" width="150"><a class="smheading" href="./?p=players&amp;filter=name">Player Name</a></td>
    <td class="smheading" align="center" width="45"><a class="smheading" href="./?p=players&amp;filter=games">Matches</a></td>
    <td class="smheading" align="center" width="50"><a class="smheading" href="./?p=players&amp;filter=gamescore">Score</a></td>
    <td class="smheading" align="center" width="50"><a class="smheading" href="./?p=players&amp;filter=frags">Frags</a></td>
    <td class="smheading" align="center" width="50"><a class="smheading" href="./?p=players&amp;filter=kills">Kills</a></td>
    <td class="smheading" align="center" width="50"><a class="smheading" href="./?p=players&amp;filter=deaths">Deaths</a></td>
    <td class="smheading" align="center" width="50"><a class="smheading" href="./?p=players&amp;filter=suicides">Suicides</a></td>
    <td class="smheading" align="center" width="45"><a class="smheading" href="./?p=players&amp;filter=eff">Eff.</a></td>
    <td class="smheading" align="center" width="45"><a class="smheading" href="./?p=players&amp;filter=accuracy">Acc.</a></td>
    <td class="smheading" align="center" width="45"><a class="smheading" href="./?p=players&amp;filter=ttl">TTL</a></td>
    <td class="smheading" align="center" width="45"><a class="smheading" href="./?p=players&amp;filter=gametime">Hours</a></td>
  </tr>';

$sql_plist = "SELECT pi.name AS name, pi.country AS country, p.pid, COUNT(p.id) AS games, SUM(p.gamescore) as gamescore, SUM(p.frags) AS frags, SUM(p.kills) AS kills,
SUM(p.deaths) AS deaths, SUM(p.suicides) as suicides, AVG(p.eff) AS eff, AVG(p.accuracy) AS accuracy, AVG(p.ttl) AS ttl, SUM(gametime) as gametime
FROM uts_player AS p, uts_pinfo AS pi WHERE p.pid = pi.id AND pi.name LIKE '%".$playersearch."%' AND pi.banned <> 'Y' GROUP BY name ORDER BY name";

$q_plist = mysql_query($sql_plist) or die(mysql_error());
while ($r_plist = mysql_fetch_array($q_plist)) {

	  $gametime = sec2hour($r_plist[gametime]);
	  $eff = get_dp($r_plist[eff]);
	  $acc = get_dp($r_plist[accuracy]);
	  $ttl = GetMinutes($r_plist[ttl]);
	  
	  echo'
	  <tr>
		<td nowrap class="dark" align="left"><a class="darkhuman" href="./?p=pinfo&amp;pid='.$r_plist['pid'].'">'.FormatPlayerName($r_plist[country], $r_plist['pid'], $r_plist[name]).'</a></td>
		<td class="grey" align="center">'.$r_plist[games].'</td>
		<td class="grey" align="center">'.$r_plist[gamescore].'</td>
		<td class="grey" align="center">'.$r_plist[frags].'</td>
		<td class="grey" align="center">'.$r_plist[kills].'</td>
		<td class="grey" align="center">'.$r_plist[deaths].'</td>
		<td class="grey" align="center">'.$r_plist[suicides].'</td>
		<td class="grey" align="center">'.$eff.'</td>
		<td class="grey" align="center">'.$acc.'</td>
		<td class="grey" align="center">'.$ttl.'</td>
		<td class="grey" align="center">'.$gametime.'</td>
	  </tr>';
}
echo'
</tbody></table>';
?>
