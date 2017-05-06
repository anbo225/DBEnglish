<?php

require_once './source/class/class_core.php';

$discuz = C::app();
$discuz->init();

class table_home_friend extends discuz_table
{
	public function __construct() {

		$this->_table = 'home_friend';
		$this->_pk    = 'uid';

		parent::__construct();
	}
}
?>