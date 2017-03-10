<?php
/**
 * [YIQIC System] Copyright (c) 2016 YIQIC.CN
 *  yanglinjun 810153135@qq.com
 */
defined('IN_IA') or exit('Access Denied');
$dos = array('platform', 'site', 'mc', 'setting', 'ext', 'solution', 'members', 'fournet', 'replystatistics');
$do = in_array($do, $dos) ? $do : $do;
$title = array('platform'=>'公众平台','site'=>'微站功能','mc'=>'会员及会员营销','setting'=>'功能选项','ext'=>'扩展功能','solution'=>'行业功能','members'=>'应用商店','fournet'=>'四网融合');
 
 ?>