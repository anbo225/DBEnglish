<?php

require_once '../../source/class/class_core.php';

$discuz = C::app();
$discuz->init();
if(empty($_G['uid']) || empty($_POST['searchkey'])) exit();

$query = C::t('home_friend')->fetch_all_search($_G['uid'], -1, $_POST['searchkey'], false);

echo json_encode($query);

?>