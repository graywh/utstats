<?
function InvertSort($curr_field, $filter, $sort)
{
	if ($curr_field != $filter) return(($curr_field == "name") ? "ASC" : "DESC");
	if ($sort == 'ASC') return('DESC');
	return('ASC');
}

function SortPic($curr_field, $filter, $sort)
{
	if ($curr_field != $filter) return;
	$fname = 'images/s_'. strtolower($sort) .'.png';
	if (!file_exists($fname)) return;
	return('&nbsp;<img src="'. $fname .'" border="0" width="11" height="9" alt="" title="('.strtolower($sort).'ending)">');
}


// Get filter and set sorting
$filter = my_addslashes($_GET['filter']);
$sort = my_addslashes($_GET['sort']);

IF (empty($filter))
{
	$filter = "name";
}

if (empty($sort) or ($sort != 'ASC' and $sort != 'DESC')) $sort = ($filter == "name") ? "ASC" : "DESC";


// Work out Prev, Next, First, Last Stuff

$r_pcount = small_query("SELECT COUNT(*) AS pcount FROM uts_pinfo");
$pcount = $r_pcount['pcount'];

$ecount = $pcount/50;
$ecount2 = number_format($ecount, 0, '.', '');

IF($ecount > $ecount2)
{
	$ecount2 = $ecount2+1;
}

$fpage = 0;
IF($ecount < 1) { $lpage = 0; }
else { $lpage = $ecount2-1; }

$cpage = $_GET["page"];
IF ($cpage == "") { $cpage = "0"; }
$qpage = $cpage*50;

$tfpage = $cpage+1;
$tlpage = $lpage+1;

$ppage = $cpage-1;
$ppageurl = "<a class=\"pages\" href=\"./?p=players&amp;filter=$filter&amp;sort=$sort&amp;page=$ppage\">[Previous]</a>";
IF ($ppage < "0") { $ppageurl = "[Previous]"; }

$npage = $cpage+1;
$npageurl = "<a class=\"pages\" href=\"./?p=players&amp;filter=$filter&amp;sort=$sort&amp;page=$npage\">[Next]</a>";
IF ($npage >= "$ecount") { $npageurl = "[Next]"; }

$fpageurl = "<a class=\"pages\" href=\"./?p=players&amp;filter=$filter&amp;sort=$sort&amp;page=$fpage\">[First]</a>";
IF ($cpage == "0") { $fpageurl = "[First]"; }

$lpageurl = "<a class=\"pages\" href=\"./?p=players&amp;filter=$filter&amp;sort=$sort&amp;page=$lpage\">[Last]</a>";
IF ($cpage == "$lpage") { $lpageurl = "[Last]"; }

// Show information
echo'
<form NAME="playersearch" METHOD="post" ACTION="./?p=psearch">
  <table CLASS="searchformb">
    <tr>
      <td WIDTH="100" ALIGN="right">Name Search:</td>
      <td WIDTH="155" ALIGN="left"><input TYPE="text" NAME="name" MAXLENGTH="35" SIZE="20" CLASS="searchform"></td>
      <td WIDTH="80" ALIGN="left"><input TYPE="submit" NAME="Default" VALUE="Search" CLASS="searchformb"></td>
    </tr>
  </table>
<div class="opnote">* Enter a Partial Name *</div>
</form>
<div class="pages"><b>Page ['.$tfpage.'/'.$tlpage.'] Selection: '.$fpageurl.' / '.$ppageurl.' / '.$npageurl.' / '.$lpageurl.'</b></div>
<div class="opnote">* Click headings to change Sorting *</div>
<table class="box" border="0" cellpadding="1" cellspacing="1">
  <tbody><tr>
    <td class="heading" colspan="14" align="center">Unreal Tournament Player List</td>
  </tr>
  <tr>
    <td class="smheading" align="center" width="150"><a class="smheading" href="./?p=players&amp;filter=name&amp;sort='.InvertSort('name', $filter, $sort).'">Player Name</a>'.SortPic('name', $filter, $sort).'</td>
    <td class="smheading" align="center" width="45"><a class="smheading" href="./?p=players&amp;filter=games&amp;sort='.InvertSort('games', $filter, $sort).'">Matches</a>'.SortPic('games', $filter, $sort).'</td>
    <td class="smheading" align="center" width="50"><a class="smheading" href="./?p=players&amp;filter=gamescore&amp;sort='.InvertSort('gamescore', $filter, $sort).'">Score</a>'.SortPic('gamescore', $filter, $sort).'</td>
    <td class="smheading" align="center" width="50"><a class="smheading" href="./?p=players&amp;filter=frags&amp;sort='.InvertSort('frags', $filter, $sort).'">Frags</a>'.SortPic('frags', $filter, $sort).'</td>
    <td class="smheading" align="center" width="50"><a class="smheading" href="./?p=players&amp;filter=kills&amp;sort='.InvertSort('kills', $filter, $sort).'">Kills</a>'.SortPic('kills', $filter, $sort).'</td>
    <td class="smheading" align="center" width="50"><a class="smheading" href="./?p=players&amp;filter=deaths&amp;sort='.InvertSort('deaths', $filter, $sort).'">Deaths</a>'.SortPic('deaths', $filter, $sort).'</td>
    <td class="smheading" align="center" width="50"><a class="smheading" href="./?p=players&amp;filter=suicides&amp;sort='.InvertSort('suicides', $filter, $sort).'">Suicides</a>'.SortPic('suicides', $filter, $sort).'</td>
    <td class="smheading" align="center" width="50"><a class="smheading" href="./?p=players&amp;filter=teamkills&amp;sort='.InvertSort('teamkills', $filter, $sort).'">Team Kills</a>'.SortPic('teamkills', $filter, $sort).'</td>
    <td class="smheading" align="center" width="50"><a class="smheading" href="./?p=players&amp;filter=headshots&amp;sort='.InvertSort('headshots', $filter, $sort).'">Head Shots</a>'.SortPic('headshots', $filter, $sort).'</td>
    <td class="smheading" align="center" width="45"><a class="smheading" href="./?p=players&amp;filter=eff&amp;sort='.InvertSort('eff', $filter, $sort).'">Eff.</a>'.SortPic('eff', $filter, $sort).'</td>
    <td class="smheading" align="center" width="45"><a class="smheading" href="./?p=players&amp;filter=fph&amp;sort='.InvertSort('fph', $filter, $sort).'">FPH</a>'.SortPic('fph', $filter, $sort).'</td>
    <td class="smheading" align="center" width="45"><a class="smheading" href="./?p=players&amp;filter=accuracy&amp;sort='.InvertSort('accuracy', $filter, $sort).'">Acc.</a>'.SortPic('accuracy', $filter, $sort).'</td>
    <td class="smheading" align="center" width="45"><a class="smheading" href="./?p=players&amp;filter=ttl&amp;sort='.InvertSort('ttl', $filter, $sort).'">TTL</a>'.SortPic('ttl', $filter, $sort).'</td>
    <td class="smheading" align="center" width="45"><a class="smheading" href="./?p=players&amp;filter=gametime&amp;sort='.InvertSort('gametime', $filter, $sort).'">Hours</a>'.SortPic('gametime', $filter, $sort).'</td>
  </tr>';

$sql_plist = "SELECT	pi.name AS name,
						pi.country AS country,
						p.pid, COUNT(p.id) AS games,
						SUM(p.gamescore) as gamescore,
						SUM(p.frags) AS frags,
						SUM(p.kills) AS kills,
						SUM(p.deaths) AS deaths,
						SUM(p.suicides) as suicides,
						(100*SUM(p.kills)/(SUM(p.kills)+SUM(p.deaths)+SUM(p.suicides)+SUM(p.teamkills))) AS eff,
						ws.acc AS accuracy,
						(SUM(p.gametime)/(SUM(p.deaths)+SUM(p.suicides)+COUNT(p.id))) AS ttl,
						SUM(p.gametime) as gametime,
						(SUM(p.frags)/SUM(p.gametime)*3600) AS fph,
						SUM(p.headshots) AS headshots,
						SUM(p.teamkills) AS teamkills
				FROM	uts_player AS p,
						uts_pinfo AS pi,
						uts_weaponstats AS ws
				WHERE	p.pid = pi.id
					AND	p.pid = ws.pid
					AND	pi.banned <> 'Y'
					AND	ws.matchid = 0
					AND	ws.weapon = 0
				GROUP BY p.pid
				ORDER BY $filter $sort
				LIMIT $qpage,50";

$q_plist = mysql_query($sql_plist) or die(mysql_error());
while ($r_plist = mysql_fetch_array($q_plist))
{
	  $gametime = sec2hour($r_plist['gametime']);
	  $fph = get_dp($r_plist['fph']);
	  $eff = get_dp($r_plist['eff']);
	  $acc = get_dp($r_plist['accuracy']);
	  $ttl = GetMinutes($r_plist['ttl']);
	  $r_pname = $r_plist['name'];
	  $myurl = urlencode($r_pname);

	  echo'
	  <tr>
		<td nowrap class="dark" align="left"><a class="darkhuman" href="./?p=pinfo&amp;pid='.$r_plist['pid'].'">'.FormatPlayerName($r_plist['country'], $r_plist['pid'], $r_pname).'</a></td>
		<td class="grey" align="center">'.$r_plist['games'].'</td>
		<td class="grey" align="center">'.$r_plist['gamescore'].'</td>
		<td class="grey" align="center">'.$r_plist['frags'].'</td>
		<td class="grey" align="center">'.$r_plist['kills'].'</td>
		<td class="grey" align="center">'.$r_plist['deaths'].'</td>
		<td class="grey" align="center">'.$r_plist['suicides'].'</td>
		<td class="grey" align="center">'.$r_plist['teamkills'].'</td>
		<td class="grey" align="center">'.$r_plist['headshots'].'</td>
		<td class="grey" align="center">'.$eff.'</td>
		<td class="grey" align="center">'.$fph.'</td>
		<td class="grey" align="center">'.$acc.'</td>
		<td class="grey" align="center">'.$ttl.'</td>
		<td class="grey" align="center">'.$gametime.'</td>
	  </tr>';
}

echo'
</tbody></table>
<div class="pages"><b>Page ['.$tfpage.'/'.$tlpage.'] Selection: '.$fpageurl.' / '.$ppageurl.' / '.$npageurl.' / '.$lpageurl.'</b></div>
';
?>
