<?php
/**
 * [YIQIC System] Copyright (c) 2016 YIQIC.CN
 *  yanglinjun 810153135@qq.com
 */
require '../framework/bootstrap.inc.php';
$controllers = array();
$handle = opendir(IA_ROOT . '/web/source/');
if(!empty($handle)) {
	while($dir = readdir($handle)) {
		if($dir != '.' && $dir != '..') {
			$controllers[] = $dir;
		}
	}
}
$actions = array();
$controller = 'home';
$handle = opendir(IA_ROOT . '/web/source/' . $controller);
if(!empty($handle)) {
	while($dir = readdir($handle)) {
		if($dir != '.' && $dir != '..' && strexists($dir, '.ctrl.php')) {
			$dir = str_replace('.ctrl.php', '', $dir);
			$actions[] = $dir;
		}
	}
}
var_dump($controllers);
var_dump($actions);
$gip = getip();
var_dump($gip);
$sql = "SELECT * FROM ".tablename('users');
echo $sql;

?>