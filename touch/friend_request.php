<?php

require_once '../../../source/class/class_core.php';

$discuz = C::app();
$discuz->init();

if(!empty($_G['uid'])) {
	$reqList = DB::fetch_all('SELECT fuid,fusername,note,dateline FROM pre_home_friend_request WHERE uid='.$_G['uid'].' ORDER BY dateline DESC');
	$op = "request";
}

include template('touch/home/space_friend');

?>