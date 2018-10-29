<?php
/**
 * @calling : 概要、ログイン処理
 * @purpose
 * @date
 * @argment
 * @return
 */
//require_once('../include/lib_common.php');

class User_login {
	//
	function check_login_forRoot(){
		global $mConfig, $mRequest,  $mUser;
		$ret=false;
		session_start();
		if(isset($_SESSION['user'])){
			if(isset($_SESSION['user']['id']) ){
				 $mUser= $_SESSION['user'];
				 $ret=true; 
			 }
		}
		return $ret;
	}
	//
	function check_proc(){
		if($this->check_login_forRoot()== false){
			header("Location: ./mat/user/");
			exit();
		}
	}
	//
	function login_show(){
		require_once('../include/lib_inc.php');
		$cls = new Lib_common();
		$cls->write_html($tpl, "user_login.html");
	}
	
	
	//
	function logout(){
	}

}
