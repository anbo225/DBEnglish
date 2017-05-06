<?php

require_once '../../source/class/class_core.php';

$discuz = C::app();
$discuz->init();

$plid = $_POST['plid'];
if(empty($plid)) exit();
$res = DB::fetch_all('SELECT plid FROM pre_ucenter_pm_members WHERE plid='.$plid.' AND uid='.$_G['uid']);
if(count($res) > 0) {
 	$tableIndex = $plid % 10;
	$mesList = DB::fetch_all('SELECT authorid,message,dateline FROM pre_ucenter_pm_messages_'.$tableIndex.' WHERE plid='.$plid.' ORDER BY dateline');
	echo json_encode($mesList);
}
else {
	echo "";
}

?>