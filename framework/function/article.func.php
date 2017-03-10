<?php
/**
 * [YIQIC System] Copyright (c) 2016 YIQIC.CN
 *  yanglinjun 810153135@qq.com
 */
define('IA_ROOT', str_replace("\\", '/', dirname(dirname(__FILE__))));
$controllers = array();
$handle = opendir(IA_ROOT . '/web/source/');
if(!empty($handle)) {
	while($dir = readdir($handle)) {
		if($dir != '.' && $dir != '..') {
			$controllers[] = $dir;
		}
	}
}
?>