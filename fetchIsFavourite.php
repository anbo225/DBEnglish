<?php

require_once '../../source/class/class_core.php';

$discuz = C::app();
$discuz->init();

$tid = $_POST['tid'];
$idtype = $_POST['idtype'];
if(!empty($_G['uid']) && !empty($tid) && !empty($idtype)) {
	$res = DB::fetch_first("SELECT * FROM pre_home_favorite WHERE uid=".$_G['uid']." AND idtype='".$idtype."' AND id=".$tid);
	if(!empty($res)) {
		exit("1");
	}
	exit("0");
}

echo "missing param";

?>