<?php
/**
 * [YIQIC System] Copyright (c) 2016 YIQIC.CN
 * WEIZAN is NOT a free software, it under the license terms, visited http://www.yiqic.com/ for more details.
 * 完成初始工作
 *
 */
//定义入口
define('IN_IA', true);
//定义系统时间
define('STARTTIME', microtime());
//定义网站根目录
define('IA_ROOT', str_replace("\\", '/', dirname(dirname(__FILE__))));
//取得 PHP 环境配置的变量 magic_quotes_gpc (GPC, Get/Post/Cookie) 值返回 0 表示关闭本功能；返回 1 表示本功能打开。当 magic_quotes_gpc 打开时，所有的 ' (单引号), " (双引号), (反斜线) and 空字符会自动转为含有反斜线的溢出字符。
define('MAGIC_QUOTES_GPC', (function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc()) || @ini_get('magic_quotes_sybase'));
define('TIMESTAMP', time());
//定义全局变量
$_WW = $_GPC = array();
//获取数据库配置信息
$configfile = IA_ROOT . "/data/config.php";
//include 和 require 除了处理错误的方式不同之外，在其他方面都是相同的：请求执行一次这个文件
require $configfile;
//获取版本信息
require IA_ROOT . '/framework/version.inc.php';
//请求一次常量信息
require IA_ROOT . '/framework/const.inc.php';
//加载loader加载类
require IA_ROOT . '/framework/class/loader.class.php';

//加载功能函数
load()->func('global');

//json转化功能函数
load()->func('compat');
//数据库操作功能函数
load()->func('pdo');
//定义一些抽象基类
 
 ?>