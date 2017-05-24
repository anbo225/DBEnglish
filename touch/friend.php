<?php

require_once '../../../source/class/class_core.php';

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
			return $pivot;
		}
	}
	if($arr[$left]['fuid'] == $id) {
		return $left;
	}
	else if($right < count($arr) && $arr[$right]['fuid'] == $id) {
		return $right;
	}
	else return -1;
}

if(!empty($_G['uid'])) {
	$res = DB::fetch_all('SELECT plid FROM pre_ucenter_pm_members WHERE uid='.$_G['uid'].' ORDER BY lastdateline DESC');
	$plidArr = array_column($res, 'plid');
	$chatList = DB::fetch_all('SELECT isnew,uid,plid,lastdateline FROM pre_ucenter_pm_members WHERE plid IN (%n) ORDER BY lastdateline DESC', array($plidArr));

	$talkArr = array();
	for ($i=0; $i<count($chatList); $i++) {
		if($chatList[$i]['uid'] == $_G['uid']) {
			$talkArr[$i / 2]['isnew'] = $chatList[$i]['isnew'];
		}
		else {
			$talkArr[$i / 2]['fuid'] = $chatList[$i]['uid'];
			$talkArr[$i / 2]['plid'] = $chatList[$i]['plid'];
			$talkArr[$i / 2]['lastdateline'] = $chatList[$i]['lastdateline'];
		}
	}

	$friendList = DB::fetch_all('SELECT fuid,fusername FROM pre_home_friend WHERE uid = '.$_G['uid']);

	for ($i=0; $i<count($talkArr); $i++) {
		$fIndex = dividedFind($talkArr[$i]['fuid'], $friendList);
		if($fIndex > -1) {
			$talkArr[$i]['name'] = $friendList[$fIndex]['fusername'];
			
			$res = DB::fetch_first("SELECT plid,lastmessage FROM pre_ucenter_pm_lists WHERE plid=".$talkArr[$i]['plid']);
			if(!empty($res['plid'])) {
				$talkArr[$i]['lastMes'] = $res['lastmessage'];
				$talkArr[$i]['lastMes'] = str_replace('**211**', '&', $talkArr[$i]['lastMes']);
				$talkArr[$i]['lastMes'] = str_replace('**221**', '=', $talkArr[$i]['lastMes']);
				$talkArr[$i]['lastMes'] = str_replace('**241**', '?', $talkArr[$i]['lastMes']);
				$talkArr[$i]['lastMes'] = str_replace('**251**', '\'', $talkArr[$i]['lastMes']);
				$talkArr[$i]['lastMes'] = str_replace('**271**', '"', $talkArr[$i]['lastMes']);
			}

			$friendList[$fIndex]['isTalk'] = 1;
		}
	}
}

include template('touch/home/space_friend');

?>
