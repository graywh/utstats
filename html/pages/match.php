<?php
$mid = $_GET[mid];
$pid = $_GET[pid];

IF ($pid != "") {
	include("match_player.php");
} else {
	include("match_info.php");
}
?>