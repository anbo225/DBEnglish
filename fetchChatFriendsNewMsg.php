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
		$chatList = DB::fetch_all('SELECT uid,plid FROM pre_ucenter_pm_members WHERE plid IN (%n) AND uid !='.$_G['uid'].' ORDER BY uid', array($plidArr));
		$nameList = DB::fetch_all('SELECT fuid,fusername FROM pre_home_friend WHERE uid = '.$_G['uid'].' AND fuid IN (%n) ORDER BY fuid', array(array_column($chatList, 'uid')));
		$nameCnt = 0;
		for ($i=0; $i<count($chatList); $i++) {
			if($chatList[$i]['uid'] == $nameList[$nameCnt]['fuid']) {
				$chatList[$i]['name'] = $nameList[$nameCnt]['fusername'];
				$nameCnt++;
			}
		}
		exit(json_encode($chatList));
	}
}
echo "";

?>