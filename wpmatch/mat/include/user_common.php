<?php
/**
 * @calling : 概要、ユーザー共通_処理
 * @purpose
 * @date
 * @argment
 * @return
 */
//
class User_common {
	//
	//　指定ユーザーを返す。
	function get_user_info($id){
		$ret= array();
		$query="select * from mat_users
		where id={$id}
		LIMIT 1
		";
		$res = mysql_query($query);
		if (!$res ) {die('クエリーが失敗しました。'.mysql_error()); 	}
		$dat =array();
		while ($row = mysql_fetch_assoc($res)) {
			$dat =$row;
		}
//var_dump($dat);
		$ret =$dat; 
		return $ret;
	}
	//
	// 指定、募集を返す。
	function get_bosyu_info($id){
		$ret= array();
		$query="select * from mat_bosyu
		where id={$id}
		LIMIT 1
		";
//echo ($query);
		$res = mysql_query($query);
		if (!$res ) {die('クエリーが失敗しました。'.mysql_error()); 	}
		$dat =array();
		while ($row = mysql_fetch_assoc($res)) {
			$dat =$row;
		}
//var_dump($dat);
		$ret =$dat; 
		return $ret;
	}
	//
	function get_oubo_info($id){
		$ret= array();
		$query="select * from mat_oubo
		where id={$id}
		LIMIT 1
		";
//echo ($query);
		$res = mysql_query($query);
		if (!$res ) {die('クエリーが失敗しました。'.mysql_error()); 	}
		$dat =array();
		while ($row = mysql_fetch_assoc($res)) {
			$dat =$row;
		}
//var_dump($dat);
		$ret =$dat; 
		return $ret;
	}
	//
	function get_bosyu_add($id){
		global $mConfig, $mRequest,  $mUser;
		$query="select * from  mat_bosyu_addtext
		where bosyu_id={$id}
		";
		$res = mysql_query($query);
		if (!$res ) {die('クエリーが失敗しました。'.mysql_error()); 	}
		$dat =array();
		$param =new Lib_sys_param();
		// 
		while ($row = mysql_fetch_assoc($res)) {
			$dat[] =$row;
		}
	//var_dump( count( $dat)  );
		return $dat;
	}	
	//
	function get_sys_param(){
		global $mConfig, $mRequest,  $mUser;
		$query="select * from  mat_sys_param
		 LIMIT 1
		";
		$res = mysql_query($query);
		if (!$res ) {die('クエリーが失敗しました。'.mysql_error()); 	}
		$dat =array();
		$param =new Lib_sys_param();
		// 
		while ($row = mysql_fetch_assoc($res)) {
			$dat =$row;
		}
	//var_dump( count( $dat)  );
		return $dat;
	}
	//
	function get_bosyu_end_chkNum($id){
		$ret =0;
		$query="
		select id ,create_dt, bosyu_end
		from mat_bosyu
		where
		 id={$id}
		 AND bosyu_end <= now();
		";
		$res = mysql_query($query);
		if (!$res ) {die('クエリーが失敗しました。'.mysql_error()); 	}
		$ret= mysql_num_rows($res );
		return $ret;
	}

}




