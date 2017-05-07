<?php

require_once '../../source/class/class_core.php';

$discuz = C::app();
$discuz->init();

function dividedFind($id, $arr) {
	$left = 0;
	$right = count($arr);
	while($left <= $right) {
		$pivot = ($left + $right) / 2;
		if($arr[$pivot]['fuid'] > $id) $right = $pivot - 1;
		else if($arr[$pivot]['fuid'] < $id) $left = $pivot + 1;
		else {
			return $arr[$pivot]['fusername'];
		}
	}
	if($arr[$left]['fuid'] == $id) {
		return $arr[$left]['fusername'];
	}
	else if($right < count($arr) && $arr[$right]['fuid'] == $id) {
		return $arr[$right]['fusername'];
	}
	else return "";
}

$res = DB::fetch_all('SELECT plid FROM pre_ucenter_pm_members WHERE uid='.$_G['uid'].' ORDER BY lastdateline DESC');
if(count($res) == 0) exit();
$plidArr = array_column($res, 'plid');

$chatList = DB::fetch_all('SELECT isnew,uid,plid FROM pre_ucenter_pm_members WHERE plid IN (%n) ORDER BY lastdateline DESC', array($plidArr));

$resArr = array();
for ($i=0; $i<count($chatList); $i++) {
	if($chatList[$i]['uid'] == $_G['uid']) {
		$resArr[$i / 2]['isnew'] = $chatList[$i]['isnew'];
	}
	else {
		$resArr[$i / 2]['fuid'] = $chatList[$i]['uid'];
		$resArr[$i / 2]['plid'] = $chatList[$i]['plid'];
	}
}

$fList = DB::fetch_all('SELECT fuid,fusername FROM pre_home_friend WHERE uid = '.$_G['uid'].' AND fuid IN (%n) ORDER BY fuid', array(array_column($resArr, 'fuid')));
for ($i=0; $i<count($resArr); $i++) {
	$resArr[$i]['name'] = dividedFind($resArr[$i]['fuid'], $fList);
}

echo json_encode($resArr);
?>