<?
	if ($playerbanned) return;

// Get sums of different events

		// Work out all possible ranking scores
		$r_cnt = small_query("SELECT
									SUM(frags*0.5) AS frags,
									SUM(deaths*0.25) AS deaths,
									SUM(suicides*0.25) AS suicides,
									SUM(teamkills*2) AS teamkills,
									SUM(flag_taken*1) AS flag_taken,
									SUM(flag_pickedup*1) AS flag_pickedup,
									SUM(flag_return*5) AS flag_return,
									SUM(flag_capture*10) AS flag_capture,
									SUM(flag_cover*3) AS flag_cover,
									SUM(flag_seal*2) AS flag_seal,
									SUM(flag_assist*5) AS flag_assist,
									SUM(flag_kill*3) AS flag_kill,
									SUM(dom_cp*1) AS dom_cp,
									SUM(ass_obj*10) AS ass_obj,
									SUM(spree_double*1) AS spree_double,
									SUM(spree_multi*1) AS spree_multi,
									SUM(spree_ultra*1) AS spree_ultra,
									SUM(spree_monster*2) AS spree_monster,
									SUM(spree_kill*1) AS spree_kill,
									SUM(spree_rampage*1) AS spree_rampage,
									SUM(spree_dom*1.5) AS spree_dom,
									SUM(spree_uns*2) AS spree_uns,
									SUM(spree_god*3) AS spree_god,
									SUM(gametime) AS gametime 
								FROM uts_player
								WHERE pid = $pid and gid = $gid and matchid <= $matchid");

		// Work out per game ranking amounts
		$rank_ctf = $r_cnt[flag_taken] + $r_cnt[flag_pickedup] + $r_cnt[flag_return] + $r_cnt[flag_capture] + $r_cnt[flag_cover] + $r_cnt[flag_seal] + $r_cnt[flag_assist] + $r_cnt[flag_kill];
		$rank_ass = $r_cnt[ass_obj];
		$rank_dom = $r_cnt[dom_cp];
		$rank_jb = $r_cnt[ass_obj] * 0.15;
		$rank_fpos = $r_cnt[frags] + $r_cnt[spree_double] + $r_cnt[spree_multi] + $r_cnt[spree_ultra] + $r_cnt[spree_monster] + $r_cnt[spree_kill] + $r_cnt[spree_rampage] + $r_cnt[spree_dom] + $r_cnt[spree_uns] + $r_cnt[spree_god];
		$rank_fneg = $r_cnt[deaths] + $r_cnt[suicides] + $r_cnt[teamkills];
		$r_gametime = ceil($r_cnt[gametime] / 60);
		
		
		// Select rank record
		$r_rankp = small_query("SELECT id, time, rank, matches FROM uts_rank WHERE pid = '$pid' AND gid = '$gid'");
		$rank_id = $r_rankp[id];
		$rank_gametime = $r_rankp[time];
		$rank_crank = $r_rankp[rank];
		$rank_matches = $r_rankp[matches];

		// Work out initial rank dependant on game, if no game known use DM ranking
		IF ($gamename == "Assault" || $gamename == "Assault (insta)") {
			$rank_nrank = $rank_ass+$rank_fpos-$rank_fneg;
		} elseIF ($gamename == "Capture the Flag" || $gamename == "Capture the Flag (insta)") {
			$rank_nrank = $rank_ctf+$rank_fpos-$rank_fneg;
		} elseIF ($gamename == "Domination" || $gamename == "Domination (insta)") {
			$rank_nrank = $rank_dom+$rank_fpos-$rank_fneg;
		} elseIF ($gamename == "JailBreak" || $gamename == "JailBreak (insta)") {
			$rank_nrank = $rank_jb+$rank_fpos-$rank_fneg;
		} else {
			$rank_nrank = $rank_fpos-$rank_fneg;
		}

		// Average the rank over game minutes
		$rank_nrank = ($rank_nrank/$r_gametime) * 60;
		
		if ($dbg) echo "Nrank: $rank_nrank ";
		
		// Add rank gametime to previous amount
		$rank_gametime = $r_gametime;

		// Reduce ranking if player hasnt played that much
		/*IF ($rank_gametime < 10) {
			$rank_nrank = $rank_nrank*.1;
		}
		ELSE IF ($rank_gametime < 50) {
			$rank_nrank = $rank_nrank*.25;
		}
		ELSE IF ($rank_gametime < 100) {
			$rank_nrank = $rank_nrank*.50;
		}
		ELSE IF ($rank_gametime < 200) {
			$rank_nrank = $rank_nrank*.70;
		}
		ELSE IF ($rank_gametime < 300) {
			$rank_nrank = $rank_nrank*.85;
		}*/

		// Add new rank record if one does not exist
		IF($rank_id == NULL) {
			mysql_query("INSERT INTO uts_rank SET time = $r_gametime, pid = $pid, gid = $gid, rank = 0, matches = 0;") or die(mysql_error());
			$rank_id = mysql_insert_id();
			$rank_gametime = 0;
 			$rank_crank = 0;
			$rank_matches = 0;
		}
				
		// Add number of matches played
		$rank_matches = $rank_matches+1;

		// Work out effective rank given
		$eff_rank = $rank_nrank-$rank_crank;

		// Add effective rank points given to uts_player record
		mysql_query("UPDATE uts_player SET rank = $eff_rank WHERE id = $playerecordid") or die(mysql_error());

		// Update the rank
		mysql_query("UPDATE uts_rank SET time = $rank_gametime, rank = $rank_nrank, prevrank = $rank_crank, matches = $rank_matches WHERE id = $rank_id;") or die(mysql_error());
?>
