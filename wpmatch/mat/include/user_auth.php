<?php
/**
 * @calling : 概要、ログイン処理
 * @purpose
 * @date
 * @argment
 * @return
 */

class User_auth {
	//
	function check_login(){
		global $mConfig;
		$ret=false;
		session_start();
//var_dump($_SESSION['admin'] );
//exit();
		if(isset($_SESSION['user'])){
			if(isset($_SESSION['user']['id']) ){ $ret=true; }
		}
		return $ret;
	}
	//
	function add($req){
		global $mConfig, $mRequest,  $mUser;
		$cls = new Lib_common();
		$msg =$this->user_add_check($req );
		if( $msg != null ){
			$tpl['error']=$msg;
			$tpl['temp_html'] = 'user_msg.html';
			$cls->write_sys_message($tpl, "user_wrap.html");
		}
		$query="INSERT INTO mat_users
		SET email = '{$req['email']}' , passwd='{$req['passwd']}'
		,  utype='user'
		, sanka_type='{$req['sanka_type']}'
		, stat =1
		, seibetu='{$req['seibetu']}'
		, nickname= '{$req['nickname']}'
		";
		$query= $cls->addSql_creat($query);
//var_dump($query );
//exit();
		$res = mysql_query($query);
		if (!$res ) {
			die('INSERTクエリーが失敗しました。'.mysql_error());
		}
		$tpl['msg']='登録が完了しました。';
		$tpl['temp_html'] = 'user_msg.html';
		$cls->write_sys_message($tpl, "user_wrap.html");
	}
	//
	function user_add_check($req){
		$ret=null;
//var_dump($req);
		if(strlen($req['passwd']) < 1){ $ret ='passwd を入力下さい。'; }
		if(strlen($req['email'] ) < 1){ $ret ='email を入力下さい。'; }
		if(strlen($req['nickname'] ) < 1){ $ret ='ニックネーム を入力下さい。'; }
		//db_check
		$query="select id from mat_users
		 where email ='{$req[email]}' LIMIT 1";
//var_dump($query);
		$res = mysql_query($query);
		if (!$res ) {  die('クエリーが失敗しました。'.mysql_error()); }
		if (mysql_num_rows($res) > 0) {
			$ret='登録済みの email が、存在します。';
		}
		if(!preg_match("/^([\w|\.|\-|_]+)@([\w||\-|_]+)\.([\w|\.|\-|_]+)$/i", $req['email'])){
			$ret = "メールアドレスの書式が不正です。";
		}
		return $ret;	
	}
	//
	function user_update($req){
		global $mConfig, $mRequest,  $mUser;
		$cls = new Lib_common();
//var_dump( $req );
//exit();
		$msg= $this->user_update_check($req);
		if( strlen($msg) > 0 ){
			$tpl['error']=$msg;
			$tpl['temp_html'] = 'user_msg.html';
			$cls->write_html($tpl, "user_wrap.html");
		}
		//
		$query="UPDATE mat_users
		SET passwd='{$req['passwd']}' 
		, nickname='{$req['nickname']}'
		, seibetu='{$req['seibetu']}'
		, sanka_type='{$req['sanka_type']}'
		where id={$req['id']};
		";
//var_dump( $query );
		$res = mysql_query($query);
		if (!$res ) { die('クエリーが失敗しました。'.mysql_error()); }
		$tpl['msg']='登録が完了しました。';
		$tpl['temp_html'] = 'user_msg.html';
		$cls->write_html($tpl, "user_wrap.html");
	}
	//
	function user_update_check($req){
		$ret='';
//var_dump($req);
		if(strlen($req['passwd']) < 1){ $ret .='password を入力下さい。'; }
		if(strlen($req['nickname']) < 1){ $ret .='ニックネーム を入力下さい。'; }
		return $ret;	
	}
	//
	function user_edit($req){
		global $mConfig, $mRequest,  $mUser;
		$cls = new Lib_common();
		$param =new Lib_sys_param();
		if(isset($mUser['id'])==false){
			$tpl['error']="ユーザー情報の取得に失敗しました。";
			$tpl['temp_html'] = 'user_msg.html';
			$cls->write_html($tpl, "user_wrap.html");
		}
		$query="select * from mat_users
			where id={$mUser['id']}
			LIMIT 1
			";
		$res = mysql_query($query);
		if (!$res ) {die('クエリーが失敗しました。'.mysql_error()); 	}
		$dat =array();
		while ($row = mysql_fetch_assoc($res)) {
			$dat =$row;
		}
//  var_dump( $dat );
		$tpl['dat']=$dat;		
		$tpl['sanka_type_arr'] = $param->sanka_type;
		$tpl['user_seibetu_arr'] = $param->user_seibetu_arr;
		$tpl['temp_html'] = 'user_edit.html';
		$cls->write_html($tpl, "user_wrap.html");
	}
	//
	function login($req){
		global $mConfig, $mRequest,  $mUser;
		$query="select * from mat_users
		 where email ='{$req[email]}'
		 and  passwd='{$req[passwd]}'
		 and utype='user'
		 LIMIT 1";
		$res = mysql_query($query);
		if (!$res ) {
			die('クエリーが失敗しました。'.mysql_error());
		}
//var_dump(mysql_num_rows($res));
		$cls = new Lib_common();
		if (mysql_num_rows($res) == 0) {
			$tpl['msg']='認証に失敗しました。';
			$tpl['temp_html'] = 'user_login.html';
			$cls->write_html($tpl, "user_wrap.html");
		}
		session_start();
		while ($row = mysql_fetch_assoc($res)) {
			$_SESSION['user'] = $row;
		}
//var_dump('OK-auth');
//		header("Location: {$mConfig['base_url']}");
		header("Location: ./");
		exit();
	}
	//
	function logout($req){
		global $mConfig, $mRequest,  $mUser;
//var_dump($mConfig['base_url'] );
//exit();
		session_start();
		$_SESSION['user'] = null;
//		header("Location: {$mConfig['base_url']}");
		header("Location:  ./");
		exit();
	}
	//
	function user_top($req){
		global $mConfig, $mRequest,  $mUser;
//var_dump($mUser );
		$cls = new Lib_common();
		$tpl['temp_html'] = 'user_top.html';
		$cls->write_html($tpl, "user_wrap.html");
	}
	//
	function user_add_show( $req ){
		$cls = new Lib_common();
		$param =new Lib_sys_param();
		$tpl['sanka_type_arr'] = $param->sanka_type;
		$tpl['user_seibetu_arr'] = $param->user_seibetu_arr;
		$tpl['temp_html'] = 'user_add.html';
		$cls->write_html($tpl, "user_wrap_login.html");
	}
	//
	function user_show($req){
		global $mConfig, $mRequest,  $mUser;
		$cls = new Lib_common();
		$param =new Lib_sys_param();
		if(isset($mUser['id'])==false){
			$tpl['error']="ユーザー情報の取得に失敗しました。";
			$tpl['temp_html'] = 'user_msg.html';
			$cls->write_html($tpl, "user_wrap.html");
		}
		$query="select * from mat_users
			where id={$mUser['id']}
			LIMIT 1
			";
		$res = mysql_query($query);
		if (!$res ) {die('クエリーが失敗しました。'.mysql_error()); 	}
		$dat =array();
		while ($row = mysql_fetch_assoc($res)) {
			$dat =$row;
		}
//  var_dump( $dat );
		$tpl['dat']=$dat;		
		$tpl['sanka_type_name'] = $param->sanka_type[$dat['sanka_type']];
		$tpl['user_seibetu_name'] = $param->user_seibetu_arr[$dat['seibetu']];
		$tpl['temp_html'] = 'user_show.html';
		$cls->write_html($tpl, "user_wrap.html");
	}


}
