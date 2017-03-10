<?php
/**
 * [YIQIC System] Copyright (c) 2016 YIQIC.CN
 *  yanglinjun 810153135@qq.com
 */
defined('IN_IA') or exit('Access Denied');

//加载函数
function load() {
    //定义一个静态常量loader,如果为空，实例化一个Loader
	static $loader;
	if(empty($loader)) {
		$loader = new Loader();
	}
	return $loader;
}


class Loader {
	
	private $cache = array();
	//加载功能函数
	function func($name) {
	    //在函数内部访问外部变量,需要global申明
		global $_W;
		//判断变量是否已配置。
		if (isset($this->cache['func'][$name])) {
			return true;
		}
		//字符串拼接路径地址
		$file = IA_ROOT . '/framework/function/' . $name . '.func.php';
		//判断文件是否存在
		if (file_exists($file)) {
			include $file;
			$this->cache['func'][$name] = true;
			return true;
		} else {
		    //函数创建用户级别的错误消息。
			trigger_error('Invalid Helper Function /framework/function/' . $name . '.func.php', E_USER_ERROR);
			return false;
		}
	}
	//加载模块
	function model($name) {
		global $_W;
		if (isset($this->cache['model'][$name])) {
			return true;
		}
		$file = IA_ROOT . '/framework/model/' . $name . '.mod.php';
		if (file_exists($file)) {
			include $file;
			$this->cache['model'][$name] = true;
			return true;
		} else {
			trigger_error('Invalid Model /framework/model/' . $name . '.mod.php', E_USER_ERROR);
			return false;
		}
	}
	//加载类
	function classs($name) {
		global $_W;
		if (isset($this->cache['class'][$name])) {
			return true;
		}
		$file = IA_ROOT . '/framework/class/' . $name . '.class.php';
		if (file_exists($file)) {
			include $file;
			$this->cache['class'][$name] = true;
			return true;
		} else {
			trigger_error('Invalid Class /framework/class/' . $name . '.class.php', E_USER_ERROR);
			return false;
		}
	}
	//加载网站
	function web($name) {
		global $_W;
		if (isset($this->cache['web'][$name])) {
			return true;
		}
		$file = IA_ROOT . '/web/common/' . $name . '.func.php';
		if (file_exists($file)) {
			include $file;
			$this->cache['web'][$name] = true;
			return true;
		} else {
			trigger_error('Invalid Web Helper /web/common/' . $name . '.func.php', E_USER_ERROR);
			return false;
		}
	}
	//加载app
	function app($name) {
		global $_W;
		if (isset($this->cache['app'][$name])) {
			return true;
		}
		$file = IA_ROOT . '/app/common/' . $name . '.func.php';
		if (file_exists($file)) {
			include $file;
			$this->cache['app'][$name] = true;
			return true;
		} else {
			trigger_error('Invalid App Function /app/common/' . $name . '.func.php', E_USER_ERROR);
			return false;
		}
	}
	
}
