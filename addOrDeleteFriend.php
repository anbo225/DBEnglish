<?php

require_once '../../source/class/class_core.php';

$discuz = C::app();
$discuz->init();

$fuid = $_POST['uid'];
$op = $_POST['op'];
if(!empty($_G['uid']) && !empty($fuid)) {
	if($op == 0) {
		DB::query("DELETE FROM pre_home_friend WHERE uid=".$_G['uid']." AND fuid=".$fuid);
		DB::query("DELETE FROM pre_home_friend WHERE uid=".$fuid." AND fuid=".$_G['uid']);
		exit("deleteSuccess");
	}
	else {
		$res = DB::fetch_first("SELECT uid,fuid FROM pre_home_friend_request WHERE uid=".$fuid." AND fuid=".$_G['uid']);
		if(!empty($res)) {
			exit("alreadyExist");
		}
		else {
			$note = $_POST['note'];
			$myname = DB::fetch_first("SELECT username FROM pre_common_member WHERE uid=".$_G['uid']);
			DB::query("INSERT INTO pre_home_friend_request(uid, fuid, fusername, gid, note, dateline) VALUES('".$fuid."','".$_G['uid']."','".$myname['username']."','0','".$note."','".time()."')");
			exit("addReqSuccess");
		}
	}
}
else {
	echo "missParam";
}

?>