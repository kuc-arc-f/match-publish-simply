<?php
/**
 * @calling : 概要、admin User 機能
 * @purpose
 * @date
 * @argment
 * @return
 */


class Admin_user {
	//
	function user_list(){
		global $mConfig, $mRequest,  $mUser;
// var_dump($mRequest );
		$param =new Lib_sys_param();
		$page_num= 100;
		$utype='user';
		$query="select * from mat_users
			where utype='$utype'
			ORDER BY id DESC
			LIMIT {$page_num}
			";
		if(isset($mRequest['sc_key'])){
			$query="select * from mat_users
			where utype='$utype'
			AND
			(
			  id= '{$mRequest['sc_key']}'
			  OR nickname like '%{$mRequest['sc_key']}%'
			)
			ORDER BY id DESC
			LIMIT {$page_num}
			";			
		}
		//
		if(isset($mRequest['page'])){
			$query = $this->get_queryPagenate($mRequest, $page_num,  $utype );
		}
		$res = mysql_query($query);
		if (!$res ) {die('クエリーが失敗しました。'.mysql_error()); 	}
		$dat =array();
		while ($row = mysql_fetch_assoc($res)) {
//			$dat[] =$row;
			$item =$row;
			$item['stat_name']    = $param->user_state_type_arr[ $item['stat'] ];
			$item['sanka_type_name']= $param->sanka_type[ $item['sanka_type'] ];
			$dat[] =$item;
		}
		$tpl['dat']=$dat;
		//
		$query_all = "select id from mat_users where utype='{$utype}'";
		$res = mysql_query($query_all );
		if (!$res ) {die('クエリーが失敗しました。'.mysql_error()); 	}
		$page_count= mysql_num_rows($res );
//var_dump( $page_count);		
// var_dump($dat);
		$pagination = ceil($page_count / $page_num );		
		$tpl['pagination']=$pagination;
		$tpl['temp_html'] = 'admin_user_list.html';
		$cls = new Lib_common();
		$cls->write_html($tpl, "admin_wrap.html");
	}
	//
//	function get_queryPagenate($req, $page_num){
	function get_queryPagenate($req, $page_num ,$type){
		$st_page = ( (int)($req['page']) -1) * $page_num;
//		$end_page= (int)($req['page']) * $page_num;
		$query="select * from mat_users
			where utype='{$type}'
			ORDER BY id DESC
			LIMIT {$st_page} , {$page_num}
			";
		return $query;
	}
	//
	function admin_list(){
		global $mConfig, $mRequest,  $mUser;
		$page_num= 100;
		$utype='admin';
		$query="select * from mat_users
			where utype='$utype'
			ORDER BY id DESC
			LIMIT {$page_num}
			";
		if(isset($mRequest['page'])){
			$query = $this->get_queryPagenate($mRequest, $page_num,  $utype );
		}
		$res = mysql_query($query);
		if (!$res ) {die('クエリーが失敗しました。'.mysql_error()); 	}
		$dat =array();
		while ($row = mysql_fetch_assoc($res)) {
			$dat[] =$row;
		}
		$tpl['dat']=$dat;
		//
		$query_all = "select id from mat_users where utype='{$utype}'";
		$res = mysql_query($query_all );
		if (!$res ) {die('クエリーが失敗しました。'.mysql_error()); 	}
		$page_count= mysql_num_rows($res );
//var_dump( $page_count);		
// var_dump($dat);
		$pagination = ceil($page_count / $page_num );		
		$tpl['pagination']=$pagination;
		$cls = new Lib_common();
		$tpl['temp_html'] = 'admin_list.html';
		$cls->write_html($tpl, "admin_wrap.html");
	}
	//
//	function admin_top(){
//		$cls = new Lib_common();
//		$cls->write_html($tpl, "admin_wrap.html");
//	}
	//
	function admin_edit($req){
//var_dump( $req );
		$query="select * from mat_users
			where id={$req['id']}
			LIMIT 1
			";
		$res = mysql_query($query);
		if (!$res ) {die('クエリーが失敗しました。'.mysql_error()); 	}
		$dat =array();
		while ($row = mysql_fetch_assoc($res)) {
			$dat =$row;
		}
// var_dump( $dat );
		$tpl['dat']=$dat;		
		$cls = new Lib_common();
		$tpl['temp_html'] = 'admin_edit.html';
		$cls->write_html($tpl, "admin_wrap.html");
	}

	//
	function admin_update($req){
		global $mConfig, $mRequest,  $mUser;
		$cls = new Lib_common();
		$msg= $this->user_update_check($req);
		if( strlen($msg) > 0 ){
			$tpl['error']=$msg;
//			$cls->write_sys_message($tpl, "admin_msg.html");
			$tpl['temp_html'] = 'admin_msg.html';
			$cls->write_html($tpl, "admin_wrap.html");
		}
		//
		$query="UPDATE mat_users
		SET passwd='{$req['passwd']}' 
		where id={$req['id']};
		";
//var_dump( $query );
//exit();

		$res = mysql_query($query);
		if (!$res ) { die('クエリーが失敗しました。'.mysql_error()); }
		$tpl['msg']='登録が完了しました。';
//		$cls->write_sys_message($tpl, "admin_msg.html");
		$tpl['temp_html'] = 'admin_msg.html';
		$cls->write_html($tpl, "admin_wrap.html");
	}
	//
	function admin_add($req){
		global $mConfig, $mRequest,  $mUser;
		$cls = new Lib_common();
//var_dump('#admin_add');
		$msg= $this->admin_add_check($req);
		if( strlen($msg) > 0 ){
			$tpl['error']=$msg;
			$tpl['temp_html'] = 'admin_msg.html';
			$cls->write_sys_message($tpl, "admin_wrap.html");
		}
		$query="INSERT INTO mat_users
		SET email = '{$req['email']}' 
		, passwd='{$req['passwd']}'
		, utype='admin'
		, stat=1
		";
		$query= $cls->addSql_creat($query);
		$res = mysql_query($query);
		if (!$res ) {	die('クエリーが失敗しました。'.mysql_error()); }
		$tpl['msg']='登録が完了しました。';
		$tpl['temp_html'] = 'admin_msg.html';
		$cls->write_html($tpl, "admin_wrap.html");
	}
	//
	function admin_login($req){
		global $mConfig, $mRequest,  $mUser;
		$query="select * from mat_users
		 where email ='{$req[email]}'
		 and  passwd='{$req[passwd]}'
		 AND utype='admin'
		 LIMIT 1";
		$res = mysql_query($query);
		if (!$res ) { die('クエリーが失敗しました。'.mysql_error()); }
//var_dump(mysql_num_rows($res));
//exit();
		$cls = new Lib_common();
		if (mysql_num_rows($res) == 0) {
			$tpl['msg']='認証に失敗しました。';
			$tpl['temp_html'] = 'admin_login.html';
//			$cls->write_html($tpl, "admin_login.html");
			$cls->write_html($tpl, "admin_wrap.html");
		}
		session_start();
		while ($row = mysql_fetch_assoc($res)) {
			$_SESSION['admin'] = $row;
		}
//var_dump('OK-auth');
//		header("Location: {$mConfig['base_url']}");
		header("Location: ./?fn=admin_top");
		exit();
	}
	//
	function admin_add_check($req){
		$ret='';
//var_dump($req);
//exit();
		if(strlen($req['passwd']) < 1){ $ret .='passwd を入力下さい。<br />'; }
		if(strlen($req['email'] ) < 1){ $ret .='email を入力下さい。<br />'; }
		//db_check
		$query="select id from mat_users
		 where email ='{$req[email]}'
		 LIMIT 1";
//		 AND utype='admin'
//var_dump($query);
		$res = mysql_query($query);
		if (!$res ) {  die('クエリーが失敗しました。'.mysql_error()); }
		if (mysql_num_rows($res) > 0) {
			$ret .='登録済みの email が、存在します。<br />';
		}
		if(!preg_match("/^([\w|\.|\-|_]+)@([\w||\-|_]+)\.([\w|\.|\-|_]+)$/i", $req['email'])){
			$ret .= "メールアドレスの書式が不正です。";
		}
		return $ret;	
	}
	//
	function user_delete($req){
		global $mConfig, $mRequest,  $mUser;
		$cls = new Lib_common();
//var_dump($req);
		$query="delete from mat_users
		where id={$req['id']};
		";
		$res = mysql_query($query);
		if (!$res ) {	die('クエリーが失敗しました。'.mysql_error()); }
		$tpl['msg']='削除が完了しました。';
		$tpl['temp_html'] = 'admin_msg.html';
//		$cls->write_sys_message($tpl, "admin_msg.html");
		$cls->write_html($tpl, "admin_wrap.html");
	}
	//
	function user_edit($req){
//var_dump( $req );
		$param =new Lib_sys_param();
		$query="select * from mat_users
			where id={$req['id']}
			LIMIT 1
			";
		$res = mysql_query($query);
		if (!$res ) {die('クエリーが失敗しました。'.mysql_error()); 	}
		$dat =array();
		while ($row = mysql_fetch_assoc($res)) {
			$item =$row;
			$item['sanka_type_name'] =$param->sanka_type[$item['sanka_type']];
			$item['seibetu_name'] =$param->user_seibetu_arr[$item['seibetu']];
			$dat =$item;
		}
// var_dump( $dat );
// user_state_type_arr
		$tpl['dat']=$dat;
		$tpl['user_state_type_arr']   = $param->user_state_type_arr;	
		$cls = new Lib_common();
		$tpl['temp_html'] = 'admin_user_edit.html';
		$cls->write_html($tpl, "admin_wrap.html");
	}
	//
	function user_update($req){
		global $mConfig, $mRequest,  $mUser;
		$cls = new Lib_common();
//var_dump( 'user_update');
//var_dump( $req );
//exit();
		$msg= $this->user_update_check($req);
		if( strlen($msg) > 0 ){
			$tpl['error']=$msg;
			$tpl['temp_html'] = 'admin_msg.html';
			$cls->write_html($tpl, "admin_wrap.html");
		}
		//
		$query="UPDATE mat_users
		SET passwd='{$req['passwd']}' 
		,stat ={$req['stat']}
		where id={$req['id']};
		";
		$res = mysql_query($query);
		if (!$res ) { die('クエリーが失敗しました。'.mysql_error()); }
		$tpl['msg']='登録が完了しました。';
		$tpl['temp_html'] = 'admin_msg.html';
//		$cls->write_sys_message($tpl, "admin_msg.html");
		$cls->write_html($tpl, "admin_wrap.html");


	}
	//
	function user_update_check($req){
		$ret='';
//var_dump($req);
		if(strlen($req['passwd']) < 1){ $ret .='password を入力下さい。'; }
		return $ret;	
	}
	//
	function check_login(){
		global $mConfig;
		$ret=false;
		session_start();
//var_dump($_SESSION['admin'] );
//exit();
		if(isset($_SESSION['admin'])){
			if(isset($_SESSION['admin']['id']) ){ $ret=true; }
		}
		return $ret;
	}
	//
	function logout(){
		global $mConfig, $mRequest,  $mUser;
//exit();
		session_start();
		$_SESSION['admin'] = null;
		header("Location: ./");
		exit();
	}


}
