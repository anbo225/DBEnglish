<?php

require_once '../../source/class/class_core.php';

$discuz = C::app();
$discuz->init();

$plid = $_POST['plid'];
$fuid = $_POST['fuid'];
$mymes = $_POST['mymes'];
if(empty($fuid) || empty($mymes)) exit("param null fuid: ".$fuid." mymes: ".$mymes);
if(strlen($mymes) > 500) exit("message too long");
if(!empty($plid)) {
	$res = DB::fetch_all('SELECT uid FROM pre_ucenter_pm_members WHERE plid='.$plid);
	if(count($res) == 0) exit("invalid plid");
	if(!($res[0]['uid'] == $_G['uid'] && $res[1]['uid'] == $fuid) && !($res[1]['uid'] == $_G['uid'] && $res[0]['uid'] == $fuid)) exit("not in chat");
	$tableIndex = $plid % 10;
	$pmid = DB::query("INSERT INTO pre_ucenter_pm_indexes(plid) VALUES('".$plid."')");
	DB::query("INSERT INTO pre_ucenter_pm_messages_".$tableIndex."(pmid, plid, authorid, message, dateline, delstatus) VALUES('".$pmid."','".$plid."','".$_G['uid']."','".$mymes."','".time()."',0)");
	$inRes = DB::query("INSERT INTO pre_ucenter_pm_members(plid, uid, isnew, pmnum, lastupdate, lastdateline) VALUES('".$plid."','".$fuid."','1','1','0','".time()."')", 'SILENT');
	if(!$inRes) {
		DB::query("UPDATE pre_ucenter_pm_members SET isnew=1, pmnum=pmnum+1, lastdateline='".time()."' WHERE plid='".$plid."' AND uid='".$fuid."'");
	}
	$inRes = DB::query("INSERT INTO pre_ucenter_pm_members(plid, uid, isnew, pmnum, lastupdate, lastdateline) VALUES('".$plid."','".$_G['uid']."','0','1','".time()."','".time()."')", 'SILENT');
	if(!$inRes) {
		DB::query("UPDATE pre_ucenter_pm_members SET isnew=0, pmnum=pmnum+1, lastupdate='".time()."', lastdateline='".time()."' WHERE plid='".$plid."' AND uid='".$_G['uid']."'");
	}
	DB::query("UPDATE pre_ucenter_pm_lists SET lastmessage='".$mymes."' WHERE plid='".$plid."'");
}
else {
	$mm = "";
	if($fuid < $_G['uid']) $mm = $fuid.'_'.$_G['uid'];
	else if($fuid > $_G['uid']) $mm = $_G['uid'].'_'.$fuid;
	else exit("not in chat");
	$res = DB::fetch_first("SELECT plid FROM pre_ucenter_pm_lists WHERE min_max='".$mm."'");
	if(!empty($res['plid'])) exit("exist ".$res['plid']);
	$subject = substr($mymes, 0, 50);
	$plid = DB::query("INSERT INTO pre_ucenter_pm_lists(authorid, pmtype, subject, members, min_max, dateline, lastmessage) VALUES('".$_G['uid']."','1','".$subject."','2','".$mm."','".time()."','".$mymes."')");
	$tableIndex = $plid % 10;
	$pmid = DB::query("INSERT INTO pre_ucenter_pm_indexes(plid) VALUES('".$plid."')");
	DB::query("INSERT INTO pre_ucenter_pm_messages_".$tableIndex."(pmid, plid, authorid, message, dateline, delstatus) VALUES('".$pmid."','".$plid."','".$_G['uid']."','".$mymes."','".time()."',0)");
	DB::query("INSERT INTO pre_ucenter_pm_members(plid, uid, isnew, pmnum, lastupdate, lastdateline) VALUES('".$plid."','".$fuid."','1','1','0','".time()."')");
	DB::query("INSERT INTO pre_ucenter_pm_members(plid, uid, isnew, pmnum, lastupdate, lastdateline) VALUES('".$plid."','".$_G['uid']."','0','1','".time()."','".time()."')");
}

DB::query("REPLACE INTO pre_ucenter_newpm(uid) VALUES('".$fuid."')");
echo "success ".$_G['uid']."**".$plid;

?>