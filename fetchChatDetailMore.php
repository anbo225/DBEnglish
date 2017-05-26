<?php

require_once '../../source/class/class_core.php';

$discuz = C::app();
$discuz->init();

$plid = $_POST['plid'];
$fuid = $_POST['fuid'];
$dateline = $_POST['dateline'];
if(empty($fuid) || empty($plid) || empty($dateline)) {
	sleep(10);
	exit(json_encode("empty"));
}
$res = DB::fetch_all('SELECT plid FROM pre_ucenter_pm_members WHERE plid='.$plid.' AND uid='.$_G['uid']);
if(count($res) > 0) {
 	$tableIndex = $plid % 10;
 	for($i = 0; $i < 25; $i++) {
 		$mesList = DB::fetch_all("SELECT authorid,message,dateline FROM pre_ucenter_pm_messages_".$tableIndex." WHERE plid='".$plid."' AND authorid='".$fuid."' AND dateline > '".$dateline."' ORDER BY dateline");
 		$mesCnt = count($mesList);
 		if($mesCnt > 0) {
 			$mesList[$mesCnt]["authorid"] = $fuid;
 			exit(json_encode($mesList));
 		}
 		sleep(1);
 	}
	$mesList[0]["authorid"] = $fuid;
	echo json_encode($mesList);
}
else {
	$mesList = array("authorid" => $fuid);
	echo json_encode($mesList);
}

?>