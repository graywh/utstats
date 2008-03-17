<?
if (empty($import_adminkey) or isset($_REQUEST['import_adminkey']) or $import_adminkey != $adminkey) die('bla');
	
$options['title'] = 'Extended Player Info';
$options['requireconfirmation'] = false;
$i = 0;
$options['vars'][$i]['name'] = 'v_pid';
$options['vars'][$i]['type'] = 'player';
$options['vars'][$i]['prompt'] = 'Player?';
$options['vars'][$i]['caption'] = 'Player:';
$i++;

if (isset($_REQUEST['pid'])) {
	$pid = $_REQUEST['pid'];
}else {
	$results = adminselect($options);
	$pid = $results['v_pid'];
}

$is_admin = true;
include('pages/players_info.php');

	
?>
