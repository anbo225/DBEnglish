<?php

require_once '../../source/class/class_core.php';

$discuz = C::app();
$discuz->init();

if(empty($_G['uid'])) exit();
for($i = 0; $i < 2; $i++) {
	$res = DB::fetch_all('SELECT plid FROM pre_ucenter_pm_members WHERE uid='.$_G['uid'].' AND isnew = 1');
	if(count($res) == 0) sleep(12);
	else {
		$plidArr = array_column($res, 'plid');
		$chatList = DB::fetch_all('SELECT uid,plid FROM pre_ucenter_pm_members WHERE plid IN (%n) AND uid !='.$_G['uid'], array($plidArr));
		exit(json_encode($chatList));
	}
}
echo "";

?>