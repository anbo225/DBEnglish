<?php

require_once '../../source/class/class_core.php';

$discuz = C::app();
$discuz->init();
if(empty($_G['uid'])) exit();

$query = C::t('home_friend_request')->count_by_uid($_G['uid']);

echo $query;

?>