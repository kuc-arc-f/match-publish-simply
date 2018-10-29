<?php
/**
 * @calling : 概要、ユーザー機能
 * @purpose
 * @date
 * @argment
 * @return
 */
require_once('../include/lib_inc.php');
require_once('../include/user_auth.php');
require_once('../include/user_bosyu.php');
require_once('../include/user_oubo.php');
require_once('../include/user_mypage.php');
require_once('../include/user_message.php');
require_once('../include/user_common.php');

//
class User {
	//
	function init(){
		global $mRequest, $mConfig, $mUser;
		$mConfig['temp_dir']         ='./template';
		$mConfig['mail_temp_dir'] ='../data/mail_template';
		//
		session_start();
//var_dump($_SESSION['user'] );
		if(isset($_SESSION['user'])){
			if(isset($_SESSION['user']['id']) ){
				 $mUser= $_SESSION['user'];
			 }
		}
	}
	//
	function get_function( $req ){
	}
	//
	function exec_function( $req ){
		switch($req['fn']){
			case 'user_top'     : $this->user_top($req); break;
			case 'user_add'     : $this->user_add($req); break;
			case 'user_add_show': $this->user_add_show($req); break;
			case 'user_login'     : $this->user_login($req); break;
			case 'user_logout'    : $this->user_logout($req); break;
			case 'user_edit'      : $this->user_edit($req); break;
			case 'user_update'    : $this->user_update($req); break;
			case 'user_show'      : $this->user_show($req); break;
			// 
			case 'user_bosyu'    : $this->user_bosyu($req); break;
			case 'user_bosyu_kakunin'    : $this->user_bosyu_kakunin($req); break;
			case 'user_bosyu_add'    : $this->user_bosyu_add($req); break;
			case 'user_bosyu_list'    : $this->user_bosyu_list($req); break;
			case 'user_bosyu_addtext_new'  : $this->user_bosyu_addtext_new($req); break;
			case 'user_bosyu_addtext_add'  : $this->user_bosyu_addtext_add($req); break;
			//
			case 'user_oubo_list'    : $this->user_oubo_list($req); break;
			case 'user_oubo_show'    : $this->user_oubo_show($req); break;
			case 'user_oubo_add_show'  : $this->user_oubo_add_show($req); break;
			case 'user_oubo_kakunin'   : $this->user_oubo_kakunin($req); break;
			case 'user_oubo_add'       : $this->user_oubo_add($req); break;
			//
			case 'user_mypage_oubo_list'   : $this->user_mypage_oubo_list($req); break;
			case 'user_mypage_oubo_show'   : $this->user_mypage_oubo_show($req); break;
			case 'user_mypage_bosyu_list'   : $this->user_mypage_bosyu_list($req); break;
			case 'user_mypage_bosyu_edit'   : $this->user_mypage_bosyu_edit($req); break;
			case 'user_mypage_bosyu_update' : $this->user_mypage_bosyu_update($req); break;
			// 
			case 'user_bosyu_sentaku_list'  : $this->user_bosyu_sentaku_list($req); break;
			case 'user_bosyu_sentaku_show'  : $this->user_bosyu_sentaku_show($req); break;
			case 'user_bosyu_sentaku_add'  : $this->user_bosyu_sentaku_add($req); break;
			case 'user_message_send_show'  : $this->user_message_send_show($req); break;
			case 'user_message_send'       : $this->user_message_send($req); break;
			case 'user_message_send_list'  : $this->user_message_send_list($req); break;
			case 'user_message_send_list_show'  : $this->user_message_send_list_show($req); break;
			case 'user_message_recv_list'  : $this->user_message_recv_list($req); break;
			case 'user_message_reply_show' : $this->user_message_reply_show($req); break;
			case 'user_message_reply'      : $this->user_message_reply($req); break;
			case 'user_mypage_prof_show'   : $this->user_mypage_prof_show($req); break;
			// 
			default:
				return;
		}
	}
	//
	function user_mypage_oubo_show($req){
		$cls= new User_mypage();
		$cls->user_mypage_oubo_show($req );
	}
	//
	function user_bosyu_addtext_add($req){
		$cls = new User_bosyu();
		$cls->user_bosyu_addtext_add($req);
	}
	//
	function user_bosyu_addtext_new($req){
		$cls = new User_bosyu();
		$cls->user_bosyu_addtext_new($req);
	}
	/*
	function user_oubo_addtext_new($req){
		$cls =new User_oubo();
		$cls->user_oubo_addtext_new($req);
	}	
	*/
	//
	function user_show($req){
		$cls=new User_auth();
		$cls->user_show($req);	
	}
	//
	function user_mypage_bosyu_update($req){
		$cls = new User_mypage();
		$cls->user_mypage_bosyu_update($req);
	}
	//
	function user_mypage_bosyu_edit($req){
		$cls = new User_mypage();
		$cls->user_mypage_bosyu_edit($req);
	}
	//
	function user_mypage_prof_show($req){
		$cls = new User_mypage();
		$cls->user_mypage_prof_show($req);
	}
	//
	function user_message_send_list_show($req){
		$cls = new User_message();
		$cls->user_message_send_list_show($req);
	}
	//
	function user_message_send_list($req){
		$cls = new User_message();
		$cls->user_message_send_list($req);
	}
	//
	function user_message_reply($req){
		$cls = new User_message();
		$cls->user_message_reply($req);
	}
	//
	function user_message_reply_show($req){
		$cls = new User_message();
		$cls->user_message_reply_show($req);
	}
	//
	function user_message_recv_list($req){
		$cls = new User_message();
		$cls->user_message_recv_list($req);
	}
	//
	function user_message_send($req){
		$cls = new User_message();
		$cls->user_message_send($req);
	}
	//
	function user_message_send_show($req){
		$cls = new User_message();
		$cls->user_message_send_show($req);
	}
	//
	function user_bosyu_sentaku_add($req){
		$cls = new User_mypage();
		$cls->user_bosyu_sentaku_add($req);
	}
	//
	function user_bosyu_sentaku_show($req){
		$cls = new User_mypage();
		$cls->user_bosyu_sentaku_show($req);
	}
	//
	function user_bosyu_sentaku_list($req){
		$cls = new User_mypage();
		$cls->user_bosyu_sentaku_list($req);
	}
	//
	function user_mypage_bosyu_list($req){
		$cls = new User_mypage();
		$cls->user_mypage_bosyu_list($req);
	}
	//
	function user_mypage_oubo_list($req){
		$cls = new User_mypage();
		$cls->user_mypage_oubo_list($req);
	}
	//
	function user_oubo_add($req){
		$cls = new User_oubo();
		$cls->user_oubo_add($req);	
	}
	//
	function user_oubo_kakunin($req){
		$cls = new User_oubo();
		$cls->user_oubo_kakunin($req);	
	}
	//
	function user_oubo_add_show($req){
		$cls = new User_oubo();
		$cls->user_oubo_add_show($req);	
	}
	//
	function user_oubo_show($req){
		$cls = new User_oubo();
		$cls->user_oubo_show($req);	
	}
	//
	function user_oubo_list($req){
		$cls = new User_oubo();
		$cls->user_oubo_list($req);
	}
	//
	function user_update($req){
		$cls=new User_auth();
		$cls->user_update($req);	
	}
	//
	function user_edit($req){
		$cls=new User_auth();
		$cls->user_edit($req);	
	}
	//
	function user_logout($req){
		require_once('../include/user_auth.php');
		$cls=new User_auth();
		$cls->logout($req);
	}
	//
	function user_add( $req ){
		require_once('../include/user_auth.php');
//		var_dump( $req );
		$cls=new User_auth();
		$cls->add($req);
	}
	//
	function user_add_show( $req ){
		$cls=new User_auth();
		$cls->user_add_show( $req );

	}
	//
	function user_login( $req ){
		require_once('../include/user_auth.php');
		$cls=new User_auth();
		$cls->login($req);
	}
	//
	function login_show(){
		$cls = new Lib_common();
		$tpl['temp_html'] = 'user_login.html';
		$cls->write_html($tpl, "user_wrap_login.html");
	}
	//
	function user_top( $req ){
		$cls=new User_auth();
		$cls->user_top($req);
	}
	//
	function user_bosyu( $req){
		 $cls= new User_bosyu();
		 $cls->bosyu_add_show( $req );
//		 $cls->aa( $req );
	}
	//
	function user_bosyu_kakunin( $req){
		$cls= new User_bosyu();
		$cls->user_bosyu_kakunin( $req );
	}
	//
	function user_bosyu_add( $req){
		$cls= new User_bosyu();
		$cls->user_bosyu_add( $req );
	}
	//
	// true= check
	function valid_login_page( $func ){
		$ret= true;
		if($func =="user_login"){ 		$ret= false;  }
		if($func =="user_add_show"){ 		$ret= false;  }
		if($func =="user_add"){ 		$ret= false;  }

		return $ret;
	}


}

//-------
// main
//-------
global $mConfig, $mRequest,  $mUser;
//var_dump($mConfig );
//exit();

$cls =new User();
$cls->init();
//
//
if(isset($_REQUEST)){ $mRequest=$_REQUEST; }
$aut =new User_auth();

//var_dump( $mRequest );
//exit();
//
// if($mRequest['fn'] != 'user_login'){
if($cls->valid_login_page($mRequest['fn'])==true){
	if($aut->check_login()==false){
		$cls->login_show();
		exit();
	}		
}

//$cls->get_function($mRequest);
//var_dump('user.php');	
if(isset($_REQUEST)){ $mRequest=$_REQUEST; }
if(isset($mRequest['fn'])){
	$cls->exec_function($mRequest ) ;
	exit();
}
//
$cls->user_top(null);


