<?

// Read all available weapons, we'll need them later
if (!isset($weaponnames))
{
	$sql_weaponnames = "SELECT id, name FROM uts_weapons";
	$q_weaponnames = mysql_query($sql_weaponnames);
	while ($r_weaponnames = mysql_fetch_array($q_weaponnames))
	{
		$weaponnames[$r_weaponnames['name']] = $r_weaponnames['id'];
	}
}

// Get all kills by weapon and player
$sql_weapons = "	SELECT	col2 AS player,
								col3 AS weaponname,
								COUNT(*) AS kills
						FROM 	uts_temp_$uid 
						WHERE	col1 = 'kill'
							OR	col1 = 'teamkill'
						GROUP BY	weaponname, player";
						
$q_weapons = mysql_query($sql_weapons) or die(mysql_error());
$weapons = array();
while ($r_weapons = mysql_fetch_array($q_weapons))
{
	
	// Get the wepon's id or assign a new one
	if (empty($r_weapons['weaponname']))
		continue;
		
	if (isset($weaponnames[$r_weapons['weaponname']]))
	{
		$weaponid = $weaponnames[$r_weapons['weaponname']];
	}
	else
	{
		mysql_query("INSERT INTO uts_weapons SET name = '". addslashes($r_weapons['weaponname']) ."'") or die(mysql_error());
		$weaponid = mysql_insert_id();
		$weaponnames[$r_weapons['weaponname']] = $weaponid;
	}
	
	// Get the unique pid of this player
	if (!isset($playerid2pid[$r_weapons['player']]))
	{
		continue;
	}
	else
	{
		$pid = $playerid2pid[$r_weapons['player']];
	}
	
	$weapons[$pid][$weaponid] = array(
											'weap_kills' 		=> $r_weapons['kills'],
											'weap_shotcount'	=> 0,
											'weap_hitcount'		=> 0,
											'weap_damagegiven'	=> 0,
											'weap_accuracy'		=> 0
										);

}


// Get the weapon statistics
$sql_weapons = "	SELECT	col1 AS type,
								col2 AS weaponname, 
								col3 AS player,
								col4 AS value
						FROM	uts_temp_$uid 
						WHERE	col1 LIKE 'weap_%'";
						
$q_weapons = mysql_query($sql_weapons) or die(mysql_error());
while ($r_weapons = mysql_fetch_array($q_weapons))
{
	// Get the wepon's id or assign a new one
	if (empty($r_weapons['weaponname'])) continue;
	if (isset($weaponnames[$r_weapons['weaponname']]))
	{
		$weaponid = $weaponnames[$r_weapons['weaponname']];
	}
	else
	{
		mysql_query("INSERT INTO uts_weapons SET name = '". addslashes($r_weapons['weaponname']) ."'") or die(mysql_error());
		$weaponid = mysql_insert_id();
		$weaponnames[$r_weapons['weaponname']] = $weaponid;
	}
	
	// Get the unique pid of this player
	if (!isset($playerid2pid[$r_weapons['player']]))
	{
//		Happens if we're ignoring bots or banned players
		continue;
	}
	else
	{
		$pid = $playerid2pid[$r_weapons['player']];
	}
	
	if (!isset($weapons[$pid][$weaponid]['weap_kills']))
	{
		$weapons[$pid][$weaponid] = array(
											'weap_kills' 		=> 0,
											'weap_shotcount'	=> 0,
											'weap_hitcount'		=> 0,
											'weap_damagegiven'	=> 0,
											'weap_accuracy'		=> 0
											);	
	}
	
	$weapons[$pid][$weaponid][$r_weapons['type']] = $r_weapons['value'];
}


// Write the weapon statistics for this match
$s_weapons = array();
foreach($weapons as $playerid => $weapon)		// For each player
{
	foreach($weapon as $weaponid => $infos)		// For each weapon
	{
		if ($infos['weap_kills'] == 0 and $infos['weap_shotcount'] == 0 and $infos['damagegiven'] == 0 and $infos['hitcount'] == 0)
			continue;
		
		if ($infos['weap_shotcount'] > 0 and $infos['weap_hitcount'] > 0)
		{
			$infos['weap_accuracy'] = round(100 * $infos['weap_hitcount'] / $infos['weap_shotcount'], 2);
			if ($infos['weap_accuracy'] > 100.0)
				$infos['weap_accuracy'] = 100;
		}
		else
		{
			$infos['weap_accuracy'] = 0;
		}
		
		mysql_query("	INSERT	
							INTO	uts_weaponstats
							SET		matchid = '$matchid',
									pid = '$playerid',
									weapon = '$weaponid',
									kills = '${infos['weap_kills']}',
									shots = '${infos['weap_shotcount']}',
									hits = '${infos['weap_hitcount']}',
									damage = '${infos['weap_damagegiven']}',
									acc = '${infos['weap_accuracy']}';"
					) or die(mysql_error());
						
		// Summarize totals for this match (playerid 0)
/*		if (!isset($s_weapons[$weaponid]['weap_kills']))
		{
			$s_weapons[$weaponid]['weap_kills'] = $infos['weap_kills'];
			$s_weapons[$weaponid]['weap_shotcount'] = $infos['weap_shotcount'];
			$s_weapons[$weaponid]['weap_hitcount'] = $infos['weap_hitcount'];
			$s_weapons[$weaponid]['weap_damagegiven'] = $infos['weap_damagegiven'];
		}
		else*/
		//{
			$s_weapons[$weaponid]['weap_kills'] += $infos['weap_kills'];
			$s_weapons[$weaponid]['weap_shotcount'] += $infos['weap_shotcount'];
			$s_weapons[$weaponid]['weap_hitcount'] += $infos['weap_hitcount'];
			$s_weapons[$weaponid]['weap_damagegiven'] += $infos['weap_damagegiven'];
		//}
		
		// Update the player's weapon statistics (matchid 0)
		// Check whether a record for this player and weapon already
		// exists
		$r_pstat = small_query("	SELECT	pid,
											kills,
											shots,
											hits,
											damage
										FROM	uts_weaponstats
										WHERE	matchid = '0'
											AND	pid = '$playerid'
											AND	weapon = '$weaponid';");
		// No -> create
		if (!$r_pstat)
		{
			mysql_query("	INSERT	
								INTO	uts_weaponstats
								SET		matchid = '0',
										pid = '$playerid',
										weapon = '$weaponid',
										kills = '${infos['weap_kills']}',
										shots = '${infos['weap_shotcount']}',
										hits = '${infos['weap_hitcount']}',
										damage = '${infos['weap_damagegiven']}',
										acc = '${infos['weap_accuracy']}';"
						) or die(mysql_error());
		// Yes -> update 
		}
		else
		{
			$r_pstat['kills'] += $infos['weap_kills'];
			$r_pstat['shots'] += $infos['weap_shotcount'];
			$r_pstat['hits'] += $infos['weap_hitcount'];
			$r_pstat['damage'] += $infos['weap_damagegiven'];
			if ($r_pstat['shots'] > 0 and $r_pstat['hits'] > 0)
			{
				$r_pstat['acc'] = round(100 * $r_pstat['hits'] / $r_pstat['shots'], 2);
				if ($r_pstat['acc'] > 100.0)
					$r_pstat['acc'] = 100;
			}
			else
			{
				$r_pstat['acc'] = 0;
			}
			
			mysql_query("	UPDATE	uts_weaponstats
								SET		kills = '${r_pstat['kills']}',
										shots = '${r_pstat['shots']}',
										hits = '${r_pstat['hits']}',
										damage = '${r_pstat['damage']}',
										acc = '${r_pstat['acc']}'
								WHERE	matchid = '0'
									AND	pid = '$playerid'
									AND	weapon = '$weaponid';"
						) or die(mysql_error());
		}
	}
	
	// Update the player's match statistics (weapon 0)
	mysql_query("	INSERT INTO	uts_weaponstats
						SELECT	matchid,
								pid,
								0 AS weapon,
								SUM(kills) AS kills,
								SUM(shots) AS shots,
								SUM(hits) AS hits,
								SUM(damage) AS damage,
								LEAST(ROUND(10000*SUM(hits)/SUM(shots))/100, 100) AS acc
						FROM	uts_weaponstats
						WHERE	matchid = '$matchid'
							AND	pid = '$playerid';"
				) or die(mysql_error());
	mysql_query("	UPDATE		uts_player
						SET accuracy = '${s_mplayer['acc']}'
						WHERE	matchid = '$matchid'
							AND	pid = '$playerid';"
				) or die(mysql_error());
	
	// Check whether a record for this player already exists
	$r_pstat = small_query("	SELECT	pid,
										kills,
										shots,
										hits,
										damage
									FROM	uts_weaponstats
									WHERE	matchid = '0'
										AND	pid = '$playerid'
										AND	weapon = '0';");
	// No -> create
	if (!$r_pstat)
	{
		mysql_query("	INSERT	
							INTO	uts_weaponstats
							SELECT	0 AS matchid,
									pid,
									0 AS weapon,
									kills,
									shots,
									hits,
									damage,
									acc
							FROM	uts_weaponstats
							WHERE	matchid = '$matchid'
								AND	pid = '$playerid';"
					) or die(mysql_error());
	// Yes -> update 
	}
	else
	{
		$r_pstat['kills'] += $s_mplayer['kills'];
		$r_pstat['shots'] += $s_mplayer['shots'];
		$r_pstat['hits'] += $s_mplayer['hits'];
		$r_pstat['damage'] += $s_mplayer['damage'];
		if ($r_pstat['shots'] > 0 and $r_pstat['hits'] > 0)
		{
			$r_pstat['acc'] = round(100 * $r_pstat['hits'] / $r_pstat['shots'], 2);
			if ($r_pstat['acc'] > 100.0)
				$r_pstat['acc'] = 100;
		}
		else
		{
			$r_pstat['acc'] = 0;
		}
		
		mysql_query("	UPDATE	uts_weaponstats
							SET		kills = '${r_pstat['kills']}',
									shots = '${r_pstat['shots']}',
									hits = '${r_pstat['hits']}',
									damage = '${r_pstat['damage']}',
									acc = '${r_pstat['acc']}'
							WHERE	matchid = '0'
								AND	pid = '$playerid'
								AND	weapon = '0';"
					) or die(mysql_error());
	}
}

// For calculating global statistics (matchid 0, playerid 0, weaponid 0)
$s_global = array(
						'kills' 		=> 0,
						'shotcount'	=> 0,
						'hitcount'		=> 0,
						'damagegiven'	=> 0
					);

// Update the global weapon statistics (matchid 0, playerid 0)
foreach($s_weapons as $weaponid => $infos)		// For each weapon
{
	if ($infos['weap_kills'] == 0 and $infos['weap_shotcount'] == 0)
		continue;
	
	if ($infos['weap_shotcount'] > 0 and $infos['weap_hitcount'] > 0)
	{
		$infos['weap_accuracy'] = round(100 * $infos['weap_hitcount'] / $infos['weap_shotcount'], 2);
		if ($infos['weap_accuracy'] > 100.0)
			$infos['weap_accuracy'] = 100;
	}
	else
	{
		$infos['weap_accuracy'] = 0;
	}
		
	$s_global['kills'] += $infos['weap_kills'];
	$s_global['shots'] += $infos['weap_shotcount'];
	$s_global['hits'] += $infos['weap_hitcount'];
	$s_global['damage'] += $infos['weap_damagegiven'];
	
	// Check whether the global record for this weapon already exists
	$r_pstat = small_query("	SELECT	kills,
										shots,
										hits,
										damage
									FROM	uts_weaponstats
									WHERE	matchid = '0'
										AND	pid = '0'
										AND	weapon = '$weaponid';");
	// No -> create
	if (!$r_pstat)
	{
		mysql_query("	INSERT	
							INTO	uts_weaponstats
							SET		matchid = '0',
									pid = '0',
									weapon = '$weaponid',
									kills = '${infos['weap_kills']}',
									shots = '${infos['weap_shotcount']}',
									hits = '${infos['weap_hitcount']}',
									damage = '${infos['weap_damagegiven']}',
									acc = '${infos['weap_accuracy']}';"
					) or die(mysql_error());
	// Yes -> update 
	}
	else
	{
			$r_pstat['kills'] += $infos['weap_kills'];
			$r_pstat['shots'] += $infos['weap_shotcount'];
			$r_pstat['hits'] += $infos['weap_hitcount'];
			$r_pstat['damage'] += $infos['weap_damagegiven'];
			if ($r_pstat['shots'] > 0 and $r_pstat['hits'] > 0)
			{
				$r_pstat['acc'] = round(100 * $r_pstat['hits'] / $r_pstat['shots'], 2);
				if ($r_pstat['acc'] > 100.0)
					$r_pstat['acc'] = 100;
			}
			else
			{
				$r_pstat['acc'] = 0;
			}
		
			mysql_query("	UPDATE	uts_weaponstats
								SET		kills = '${r_pstat['kills']}',
										shots = '${r_pstat['shots']}',
										hits = '${r_pstat['hits']}',
										damage = '${r_pstat['damage']}',
										acc = '${r_pstat['acc']}'
								WHERE	matchid = '0'
									AND	pid = '0'
									AND	weapon = '$weaponid';"
					) or die(mysql_error());
	}
}

// Update the global statistics (matchid 0, playerid 0, weaponid 0)
// Check whether the global record exists
$r_pglobal = small_query("	SELECT	pid
								FROM	uts_weaponstats
								WHERE	matchid = '0'
									AND	pid = '0'
									AND	weapon = '0';");
// No -> create
if (!$r_pglobal)
{
	if ($s_global['shots'] > 0 and $s_global['hits'] > 0)
	{
		$s_global['acc'] = round(100 * $s_global['hits'] / $s_global['shots'], 2);
		if ($s_global['acc'] > 100.0)
			$s_global['acc'] = 100;
	}
	else
	{
		$s_global['acc'] = 0;
	}
	mysql_query("	INSERT
						INTO	uts_weaponstats
						SET		kills = '${s_global['kills']}',
								shots = '${s_global['shots']}',
								hits = '${s_global['hits']}',
								damage = '${s_global['damage']}',
								acc = '${s_global['acc']}'
						WHERE	matchid = '0'
							AND	pid = '0'
							AND weapon = '0';"
				) or die(mysql_error());
// Yes -> update
}
else
{
	$r_pglobal['kills'] += $s_global['kills'];
	$r_pglobal['shots'] += $s_global['shots'];
	$r_pglobal['hits'] += $s_global['hits'];
	$r_pglobal['damage'] += $s_global['damage'];
	if ($r_pglobal['shots'] > 0 and $r_pglobal['hits'] > 0)
	{
		$r_pglobal['acc'] = round(100 * $s_global['hits'] / $s_global['shots'], 2);
		if ($r_pglobal['acc'] > 100.0)
			$r_pglobal['acc'] = 100;
	}
	else
	{
		$r_pglobal['acc'] = 0;
	}
	
	mysql_query("	UPDATE	uts_weaponstats
						SET		kills = '${r_pglobal['kills']}',
								shots = '${r_pglobal['shots']}',
								hits = '${r_pglobal['hits']}',
								damage = '${r_pglobal['damage']}',
								acc = '${r_pglobal['acc']}'
						WHERE	matchid = '0'
							AND	pid = '0'
							AND weapon = '0';"
				) or die(mysql_error());
}
	
?>