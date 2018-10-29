<?php
/**
 * @calling : 概要、mysql-LIB
 * @purpose
 * @date
 * @argment
 * @return
 */

class Lib_mysql {
	//
	function init(){
		global $mConfig, $mRequest,  $mUser;
//var_dump($mConfig );
//exit();
//		$link = @mysql_connect('localhost', 'wpadmin', 'password');
		$link = @mysql_connect($mConfig['db_host'] , $mConfig['db_user']  ,$mConfig['db_pass'] );
		if (!$link) {
			die('Not connected : ' . mysql_error());
		}
//		mysql_close($link);
		$db_selected = @mysql_select_db($mConfig['db_name'] , $link);
		if (!$db_selected) {
		    die ('Can\'t use foo : ' . mysql_error());
		}
		mysql_query("SET NAMES utf8");
//		echo '接続に成功しました';
		/*
		*/
	}
	//
	function test(){
		$result = mysql_query('SELECT * from ext_users LIMIT 1;');
		if (!$result) {
			die('Invalid query: ' . mysql_error());
		}
		while ($row = mysql_fetch_assoc($result)) {
			var_dump($row );
		}
	}
}

// main
$db= new Lib_mysql();
$db->init();
//$db->test();
