<?php
/**
 * @calling : 概要、admin機能
 * @purpose
 * @date
 * @argment
 * @return
 */
require_once('../include/lib_inc.php');
require_once('../include/admin_user.php');
require_once('../include/admin_bosyu.php');
require_once('../include/admin_message.php');
require_once('../include/admin_sys_param.php');

//
class Admin {
	//
	function init(){
		global $mRequest, $mConfig, $mUser;
		$mConfig['temp_dir'] ='./template';
	}
	//
	function exec_function( $req ){
		switch($req['fn']){
			case 'admin_user_list'     : $this->user_list($req); break;
			case 'admin_user_edit'     : $this->user_edit($req); break;
			case 'admin_user_update'   : $this->user_update($req); break;
			case 'admin_user_delete'     : $this->user_delete($req); break;
			case 'admin_add_show'     : $this->admin_add_show($req); break;
			case 'admin_add'          : $this->admin_add($req); break;
			case 'admin_top'          : $this->admin_top($req); break;
			case 'admin_login'          : $this->admin_login($req); break;
			case 'admin_logout'          : $this->admin_logout($req); break;
			case 'admin_list'          : $this->admin_list($req); break;
			case 'admin_edit'          : $this->admin_edit($req); break;
			case 'admin_update'        : $this->admin_update($req); break;
			//
			case 'admin_bosyu_list'    : $this->admin_bosyu_list($req); break;
			case 'admin_bosyu_edit'    : $this->admin_bosyu_edit($req); break;
			case 'admin_bosyu_update'  : $this->admin_bosyu_update($req); break;
			case 'admin_msg_list'      : $this->admin_msg_list($req); break;
			case 'admin_msg_show'      : $this->admin_msg_show($req); break;
			// 
			case 'admin_sys_param_edit'  : $this->admin_sys_param_edit($req); break;
			case 'admin_sys_param_update' : $this->admin_sys_param_update($req); break;
			// 
			default:
				return;
		}
	}
	//
	function admin_update($req){
		$cls= new Admin_user();
		$cls->admin_update($req);
	}
	//
	function admin_sys_param_update($req){
		$cls= new Admin_sys_param();
		$cls->admin_sys_param_update($req);
	}
	//
	function admin_sys_param_edit($req){
		$cls= new Admin_sys_param();
		$cls->admin_sys_param_edit($req);
	}
	//
	function admin_msg_show($req){
		$cls=new Admin_message();
		$cls->admin_msg_show($req);
	}
	//
	function admin_msg_list($req){
		$cls=new Admin_message();
		$cls->admin_msg_list($req);
	}
	//
	function admin_bosyu_update($req){
		$cls= new Admin_bosyu();
		$cls->admin_bosyu_update($req );	
	}
	//
	function admin_bosyu_edit($req){
		$cls= new Admin_bosyu();
		$cls->admin_bosyu_edit($req );
	}
	//
	function admin_bosyu_list($req){
		$cls= new Admin_bosyu();
		$cls->admin_bosyu_list($req );
	}
	//
	function admin_edit($req){
		$cls= new Admin_user();
		$cls->admin_edit($req);	
	}
	//
	function admin_login($req){
		$cls= new Admin_user();
		$cls->admin_login($req);	
	}
	//
	function admin_add($req){
		$cls= new Admin_user();
		$cls->admin_add($req);
	}
	//
	function admin_add_show($req){
		$cls = new Lib_common();
		$tpl['temp_html'] = 'admin_add.html';
		$cls->write_html($tpl, "admin_wrap.html");
	}
	//
	function user_delete($req){
		$cls= new Admin_user();
		$cls->user_delete($req);
	}
	//
	function user_update($req){
		$cls= new Admin_user();
		$cls->user_update($req);
	}
	//
	function user_edit($req){
		$cls= new Admin_user();
		$cls->user_edit($req);
	}
	//
	function user_list($req){
		require_once('../include/admin_user.php');
		$cls= new Admin_user();
		$cls->user_list();
	}
	//
	function admin_list($req ){
		$cls= new Admin_user();
		$cls->admin_list();
	}
	//
	function login_show(){
		$cls = new Lib_common();
		$tpl['temp_html'] = 'admin_login.html';
//		$cls->write_html($tpl, "admin_login.html");
		$cls->write_html($tpl, "admin_wrap.html");

	}
	//
	function admin_top(){
		$cls = new Lib_common();
		$tpl['temp_html'] = 'admin_top.html';
		$cls->write_html($tpl, "admin_wrap.html");
	}
	//
	function admin_logout(){
		$cls= new Admin_user();
		$cls->logout();
	}

}

//-------
// main
//-------
global $mConfig, $mRequest,  $mUser;

//echo $_SERVER["REQUEST_URI"];
//exit();

$cls =new Admin();
$cls->init();
//
if(isset($_REQUEST)){ $mRequest=$_REQUEST; }
$us =new Admin_user();

//var_dump($mRequest['fn'] );
//exit();
if($mRequest['fn'] != 'admin_login'){
	if($us->check_login()==false){
		$cls->login_show();
		exit();
	}	
}
if(isset($mRequest['fn'])){
	$cls->exec_function($mRequest ) ;
	exit();
}
//
$cls->admin_top();
exit();

