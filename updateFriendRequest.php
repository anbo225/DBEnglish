<?php

require_once '../../source/class/class_core.php';

$discuz = C::app();
$discuz->init();

$fuid = $_POST['uid'];
$op = $_POST['op'];

if(!empty($_G['uid']) && !empty($fuid)) {
	$res = DB::fetch_first("SELECT uid,fuid FROM pre_home_friend_request WHERE uid=".$_G['uid']." AND fuid=".$fuid);
	if(!empty($res)) {
		DB::query("DELETE FROM pre_home_friend_request WHERE uid=".$_G['uid']." AND fuid=".$fuid);
		if($op == 1) {
			$fri = DB::fetch_first("SELECT uid,fuid FROM pre_home_friend WHERE uid=".$_G['uid']." AND fuid=".$fuid);
			if(empty($fri)) {
				
				$myname = DB::fetch_first("SELECT username FROM pre_common_member WHERE uid=".$_G['uid']);
				$fname = DB::fetch_first("SELECT username FROM pre_common_member WHERE uid=".$fuid);
				DB::query("INSERT INTO pre_home_friend(uid, fuid, fusername, gid, num, dateline) VALUES('".$_G['uid']."','".$fuid."','".$fname['username']."','0','1','".time()."')");
				DB::query("INSERT INTO pre_home_friend(uid, fuid, fusername, gid, num, dateline) VALUES('".$fuid."','".$_G['uid']."','".$myname['username']."','0','0','".time()."')");
				exit("acceptSuccess");
			}
			else {
				exit("alreadyExist");
			}
		}
		else {
			exit("ignoreSuccess");
		}
	}
	else echo "noSQL";
}
else {
	echo "missParam";
}

?>