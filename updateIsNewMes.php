<?php

require_once '../../source/class/class_core.php';

$discuz = C::app();
$discuz->init();

$plid = $_POST['plid'];
$updRes = DB::query('UPDATE pre_ucenter_pm_members SET isnew = 0 WHERE plid='.$plid.' AND uid='.$_G['uid']);

echo $updRes;
?>