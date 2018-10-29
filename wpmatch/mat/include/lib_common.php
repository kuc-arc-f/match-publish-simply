<?php
/**
 * @calling : 概要、共通ライブラリ
 * @purpose
 * @date
 * @argment
 * @return
 */
//require_once('../include/user_login.php');


class Lib_common {
//	global $cfg;
	//
	function addSql_creat( $query ){
		$ret="";
		$add="
		 ,create_dt= now()
		 ,update_dt= now()
		";
		$ret = $query . $add;
		return $ret;
	}
	//
	function addSql_update( $query ){
		$ret="";
		$add="
		 ,update_dt= now()
		";
		$ret = $query . $add;
		return $ret;
	}
	//
	function write_html($tpl, $tplfile){
		global $mConfig;
		//$tpl = tpl_htmlspecialchars($tpl);
		include("{$mConfig['temp_dir']}/{$tplfile}");
		if($con = @mysql_close()){
		}
		else{
			die("mysqlとの接続を解除できませんでした！<br>");
		}
		exit();
	}
	/*
	function write_sys_message_ng($tpl, $tplfile){
		global $mConfig;
		$tpl['temp_html'] = $tplfile;
		include("{$mConfig['temp_dir']}/admin_wrap.html" );
		if($con = @mysql_close()){
		}
		else{
			die("mysqlとの接続を解除できませんでした！<br>");
		}
		exit();
	}
	*/
	function write_sys_message($tpl, $tplfile){
		global $mConfig;
		//$tpl = tpl_htmlspecialchars($tpl);
		include("{$mConfig['temp_dir']}/{$tplfile}");
		if($con = @mysql_close()){
		}
		else{
			die("mysqlとの接続を解除できませんでした！<br>");
		}
		exit();
	}
	//
	function get_convert_msg($arr ){
//		$ret= array();
		$ret= "";
		foreach ( $arr as $key => $value) { 
			$ret= $ret . $value . "<br />";
		}
		return $ret;
	}



}

	
