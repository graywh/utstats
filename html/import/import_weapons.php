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
		
		mysql_query("	INSERT	uts_weaponstats
						SET		matchid = '$matchid',
								pid = '$playerid',
								weapon = '$weaponid',
								kills = '${infos['weap_kills']}',
								shots = '${infos['weap_shotcount']}',
								hits = '${infos['weap_hitcount']}',
								damage = '${infos['weap_damagegiven']}',
								acc = '${infos['weap_accuracy']}';"
		) or die(mysql_error());
						
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
						WHERE	pid = '$playerid'
							AND weapon = '$weapon'
							AND matchid > 0;"
		) or die(mysql_error());
	
	}
	
	// Create the player's match statistics (weapon 0)
	mysql_query("	INSERT	uts_weaponstats
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
						AND	pid = '$playerid'
						AND weapon > 0;"
	) or die(mysql_error());

	// Update the player's match entry in uts_player
	mysql_query("	UPDATE	uts_player AS p,
							uts_weaponstats AS w
					SET 	p.accuracy = w.acc
					WHERE	p.matchid = '$matchid'
						AND	w.matchid = p.matchid
						AND	p.pid = '$playerid'
						AND	w.pid = p.pid
						AND	w.weapon = 0;"
	) or die(mysql_error());
	
	// Update the player's career statistics (weapon 0, match 0)
	mysql_query("	REPLACE	uts_weaponstats
					SELECT	0 AS matchid,
							'$playerid' AS pid,
							0 AS weapon,
							SUM(kills) AS kills,
							SUM(shots) AS shots,
							SUM(hits) AS hits,
							SUM(damage) AS damage,
							LEAST(ROUND(10000*SUM(hits)/SUM(shots))/100, 100) AS acc
					FROM	uts_weaponstats
					WHERE	matchid > 0
						AND	pid = '$playerid'
						AND weapon > 0;"
	) or die(mysql_error());
}

// Update the global weapon statistics (matchid 0, playerid 0)
foreach($s_weapons as $weaponid => $infos)		// For each weapon
{
	if ($infos['weap_kills'] == 0 and $infos['weap_shotcount'] == 0)
		continue;
	
	// Update the weapon's global record (pid 0, matchid 0)
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
						AND	weapon = '$weaponid';"
	) or die(mysql_error());
}

// Update the global statistics (matchid 0, playerid 0, weaponid 0)
mysql_query("	REPLACE uts_weaponstats
				SELECT	0 as matchid,
						0 as pid,
						0 as weapon
						SUM(kills) as kills,
						SUM(shots) as shots,
						SUM(hits) as hits,
						SUM(damage) as damage,
						LEAST(ROUND(10000*SUM(hits)/SUM(shots))/100, 100) as acc
				FROM	uts_weaponstats
				WHERE	matchid > 0
					AND pid > 0
					AND weapon > 0;"
) or die(mysql_error());
?>
