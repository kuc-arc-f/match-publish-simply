<?php
/**
 * @calling : 概要、メッセージ_処理
 * @purpose
 * @date
 * @argment
 * @return
 */

class User_message{
	// 
	function user_message_send_show($req){
//var_dump( $req );
		global $mConfig, $mRequest,  $mUser;
		$cls = new Lib_common();
		$uc  =new User_common();
		$send_user =$uc->get_user_info($req['send_id']);
		$bosyu =$uc->get_bosyu_info($req['bosyu_id']);
//var_dump( $bosyu );
		$tpl['dat'] = $req;
		$tpl['dat']['send_user'] = $send_user;
		$tpl['dat']['bosyu'] = $bosyu;
		$tpl['temp_html'] = 'user_message_send_show.html';
		$cls->write_sys_message($tpl, "user_wrap.html");
	}
	//
	function user_message_send($req){
		global $mConfig, $mRequest,  $mUser;
//var_dump($req );
//exit();
		$cls = new Lib_common();
		$uco =new User_common();
		$arr = $this->message_send_check($req);
//var_dump($req );
 		if(count($arr) > 0){
			$tpl['dat']=$req;
			$msg=$cls->get_convert_msg($arr );
			 $tpl['error']= $msg;
			 $send_user =$uco->get_user_info($req['send_id']);
			 $bosyu =$uco->get_bosyu_info($req['bosyu_id']);
			 $tpl['dat'] = $req;
			 $tpl['dat']['send_user'] = $send_user;
			 $tpl['dat']['bosyu'] = $bosyu;	 
			 $tpl['temp_html'] = 'user_message_send_show.html';
			 $cls->write_html($tpl, "user_wrap.html");
 		}
		$query="INSERT INTO mat_msg
		SET bosyu_id={$req['bosyu_id']}
		,send_id={$req['send_id']}
		,from_id={$mUser['id']}
		,msg_text='{$req['msg_text']}'
		,user_id={$mUser['id']}
		";
		$query= $cls->addSql_creat($query);
		$res = mysql_query($query);
		if (!$res ) { die('INSERTクエリーが失敗しました。'.mysql_error());	}
		// lasst_id
		$query="select max(id) as msg_id  from  mat_msg
		where user_id={$mUser['id']}		
		";
		$res = mysql_query($query);
		if (!$res ) {die('クエリーが失敗しました。'.mysql_error()); 	}
		$num= mysql_num_rows($res );
		if( $num < 1 ){
			$tpl['error']="メッセージ IDの、取得処理に失敗しました。";
			$tpl['temp_html'] = 'user_msg.html';
			$cls->write_sys_message($tpl, "user_wrap.html");
		}
		$dat =array();
		while ($row = mysql_fetch_assoc($res)) {
			$dat =$row;
		}
		//
//		$uco =new User_common();
		$spara =$uco->get_sys_param();
//var_dump($spara );
//exit();
		// mail
		if($spara['mail_send']=='1'){
			$this->message_send_mail($req, $dat['msg_id']);
		}
		//
		$tpl['msg']='登録が完了しました。';
		$tpl['temp_html'] = 'user_msg.html';
		$cls->write_sys_message($tpl, "user_wrap.html");
	}
	//
	function message_send_check($req){
		$ret= array();
		if(strlen($req['msg_text']) < 1){ $ret[] ='メッセージ内容 を入力下さい。'; }
		return $ret;
	}
	//
	function message_send_mail($req ,$msg_id){
		global $mConfig, $mRequest,  $mUser;
		$uco= new User_common();
//		$oubo_user= $uco->get_user_info($mUser['id']);
		$oubo_user= $uco->get_user_info($req['send_id']);
		$bosyu  = $uco->get_bosyu_info($req['bosyu_id']);
//		$bosyu_user= $uco->get_user_info($bosyu['user_id']);
		$bosyu_user= $uco->get_user_info( $mUser['id'] );
		$email       = $oubo_user['email'];
		$tpl['from_name'] = $bosyu_user["nickname"];
//		$tpl['oubo_name'] = $oubo_user["nickname"];
		$tpl['send_name'] = $oubo_user["nickname"];
		$tpl['bosyu_title'] = $bosyu["title"];
		$tpl['bosyu_id'] = $bosyu['id'];
//		$tpl['oubo_id'] = $oubo_id;
// http://localhost/wpmatch/mat/user/?fn=user_message_reply_show&id=6
		$msg_url = $mConfig['host_name'] . $mConfig['base_url'] . "/mat/user/?fn=user_message_reply_show&id={$msg_id}";
		$tpl['msg_url'] = $msg_url;
		$header = 'From: ' . $mConfig["mail_from_addr"] . "\r\n";
		$tplfile = "{$mConfig['mail_temp_dir']}/mail_msg_send.txt";
//var_dump( $oubo_user  );
// var_dump( $tpl  );
		//
		$mail =new Lib_mail();
		$mailsub = "[ {$mConfig['sys_title']} ] メッセージが届きました。";
		$mail->mail_template($email, $mailsub, $header, $tplfile ,$tpl);
	}
	//
	function user_message_recv_list($req){
//var_dump($req );

		global $mConfig, $mRequest,  $mUser;
		$page_num= 20;
		$utype='user';
		$select_sql=" mat_msg.id , mat_msg.from_id
		,mat_users.nickname
	   , msg_text
	   , DATE_FORMAT( mat_msg.create_dt ,'%Y-%m-%d %H:%i') as create_dt
	   ";
	   $order_sql ="		ORDER BY id DESC";
	   $limit_sql ="  LIMIT {$page_num}";
		$query="select {$select_sql}
		from mat_msg, mat_users
			where 
			mat_msg.send_id={$mUser['id']}
			AND mat_msg.from_id= mat_users.id			
			";
// echo($query);
		if(isset($mRequest['page'])){
			$st_page = ( (int)($mRequest['page']) -1) * $page_num;
			$limit_sql=" LIMIT {$st_page} , {$page_num}";
			//			$query = $this->get_queryPagenate($mRequest, $page_num ,$select_sql );
		}
		$query_all=$query;
		$query    = $query . $order_sql . $limit_sql;		
		$res = mysql_query($query);
		if (!$res ) {die('クエリーが失敗しました。'.mysql_error()); 	}
		$dat =array();
		$param =new Lib_sys_param();
		while ($row = mysql_fetch_assoc($res)) {
		//			$dat[] =$row;
			$item =$row;
			$housyu = $param->user_housyu_price_arr[ $item['price_kbn'] ];
			$msg_fmt=  mb_strimwidth($item['msg_text'], 0, 60, "...");
			$item['msg_fmt']=  nl2br($msg_fmt);
			$item['housyu'] =$housyu;
			$dat[] =$item;
		}
//var_dump( $dat );
		$tpl['dat']=$dat;
//		$query_all = "select id from mat_bosyu";
		$res = mysql_query($query_all );
		if (!$res ) {die('クエリーが失敗しました。'.mysql_error()); 	}
		$page_count= mysql_num_rows($res );
		$pagination = ceil($page_count / $page_num );		
		$tpl['pagination']=$pagination;
		$tpl['temp_html'] = 'user_message_recv_list.html';
		$cls = new Lib_common();
		$cls->write_html($tpl, "user_wrap.html");
	}
	//
	function user_message_reply_show($req){
//var_dump($req );
		$param =new Lib_sys_param();
		$select_sql="id , from_id, bosyu_id, msg_text, DATE_FORMAT(create_dt ,'%Y-%m-%d %H:%i') as create_dt";
		$query="select {$select_sql} from mat_msg
			where id={$req['id']}
			LIMIT 1
			";
		$res = mysql_query($query);
		if (!$res ) {die('クエリーが失敗しました。'.mysql_error()); 	}
		$dat =array();
		while ($row = mysql_fetch_assoc($res)) {
			$dat =$row;
		}
		$uc = new User_common();
		$from =$uc->get_user_info($dat['from_id']);   
//var_dump( $dat );
//exit();
		$uc  =new User_common();
		$bosyu =$uc->get_bosyu_info($dat['bosyu_id']);
		$tpl['dat']=$dat;
		$tpl['dat']['bosyu'] = $bosyu;
		$tpl['dat']['from'] = $from;

//var_dump( $bosyu );
		$cls = new Lib_common();
		$tpl['temp_html'] = 'user_message_reply_show.html';
		$cls->write_html($tpl, "user_wrap.html");
	}
	//
	function user_message_reply($req){
		global $mConfig, $mRequest,  $mUser;
//var_dump($req );
		$cls = new Lib_common();
		$arr = $this->message_send_check($req);
//var_dump($arr );
//exit();
 		if(count($arr) > 0){
			$tpl['dat']=$req;
			$msg=$cls->get_convert_msg($arr );
			 $tpl['error']= $msg;
			 $tpl['temp_html'] = 'user_msg_back.html';
			 $cls->write_sys_message($tpl, "user_wrap.html");
 		}
		//
		$query="INSERT INTO mat_msg
		SET bosyu_id={$req['bosyu_id']}
		,send_id={$req['send_id']}
		,from_id={$mUser['id']}
		,msg_text='{$req['msg_text']}'
		,user_id={$mUser['id']}
		";
		$query= $cls->addSql_creat($query);
		$res = mysql_query($query);
		if (!$res ) { die('INSERTクエリーが失敗しました。'.mysql_error());	}
		// lasst_id
		$query="select max(id) as msg_id  from  mat_msg
		where user_id={$mUser['id']}		
		";
		$res = mysql_query($query);
		if (!$res ) {die('クエリーが失敗しました。'.mysql_error()); 	}
		$num= mysql_num_rows($res );
		if( $num < 1 ){
			$tpl['error']="メッセージ IDの、取得処理に失敗しました。";
			$tpl['temp_html'] = 'user_msg.html';
			$cls->write_sys_message($tpl, "user_wrap.html");
		}
		$dat =array();
		while ($row = mysql_fetch_assoc($res)) {
			$dat =$row;
		}
		// mail
		$uco =new User_common();
		$spara =$uco->get_sys_param();
		if($spara['mail_send']=='1'){
			$this->message_reply_mail($req, $dat['msg_id']);
		}
		//
		$tpl['msg']='登録が完了しました。';
		$tpl['temp_html'] = 'user_msg.html';
		$cls->write_sys_message($tpl, "user_wrap.html");
	}
	//
	function message_reply_mail($req ,$msg_id){
//var_dump( $req  );
//exit();
		global $mConfig, $mRequest,  $mUser;
		$uco= new User_common();
		$send_user= $uco->get_user_info($req['send_id']);
		$bosyu  = $uco->get_bosyu_info($req['bosyu_id']);
		$from_user= $uco->get_user_info( $mUser['id'] );
		$email       = $send_user['email'];
		$tpl['from_name'] = $from_user["nickname"];
		$tpl['send_name'] = $send_user["nickname"];
		$tpl['bosyu_title'] = $bosyu["title"];
		$tpl['bosyu_id'] = $bosyu['id'];
//		$tpl['oubo_id'] = $oubo_id;
// http://localhost/wpmatch/mat/user/?fn=user_message_reply_show&id=6
		$msg_url = $mConfig['host_name'] . $mConfig['base_url'] . "/mat/user/?fn=user_message_reply_show&id={$msg_id}";
		$tpl['msg_url'] = $msg_url;
		$header = 'From: ' . $mConfig["mail_from_addr"] . "\r\n";
		$tplfile = "{$mConfig['mail_temp_dir']}/mail_msg_send.txt";
//var_dump( $send_user  );
//var_dump( $tpl  );
		//
		$mail =new Lib_mail();
		$mailsub = "[ {$mConfig['sys_title']} ] メッセージが届きました。";
		$mail->mail_template($email, $mailsub, $header, $tplfile ,$tpl);
	}
	//
	function user_message_send_list($req){
		//var_dump($req );
		global $mConfig, $mRequest,  $mUser;
		$page_num= 20;
		$utype='user';
		$select_sql=" mat_msg.id , mat_msg.from_id
		,mat_msg.send_id
		,mat_users.nickname
		, msg_text
		, DATE_FORMAT( mat_msg.create_dt ,'%Y-%m-%d %H:%i') as create_dt
		";
		$order_sql ="		ORDER BY id DESC";
		$limit_sql ="  LIMIT {$page_num}";
		$query="select {$select_sql}
		from mat_msg, mat_users
			where 
			mat_msg.from_id={$mUser['id']}
			AND mat_msg.send_id= mat_users.id			
			";
		if(isset($mRequest['page'])){
			$st_page = ( (int)($mRequest['page']) -1) * $page_num;
			$limit_sql=" LIMIT {$st_page} , {$page_num}";			
//			$query = $this->get_queryPagenate($mRequest, $page_num ,$select_sql );
		}
		$query_all=$query;
		$query    = $query . $order_sql . $limit_sql;
		$res = mysql_query($query);
		if (!$res ) {die('クエリーが失敗しました。'.mysql_error()); 	}
		$dat =array();
		$param =new Lib_sys_param();
		while ($row = mysql_fetch_assoc($res)) {
		//			$dat[] =$row;
			$item =$row;
			$housyu = $param->user_housyu_price_arr[ $item['price_kbn'] ];
			$uc = new User_common();
//			$send =$uc->get_user_info($dat['send_id']);   
			$msg_fmt=  mb_strimwidth($item['msg_text'], 0, 60, "...");
			$item['msg_fmt']=  nl2br($msg_fmt);
			$item['housyu'] =$housyu;
//			$item['send'] =$send;
			$dat[] =$item;
		}
//var_dump( $dat );
		$tpl['dat']=$dat;
		$res = mysql_query($query_all );
		if (!$res ) {die('クエリーが失敗しました。'.mysql_error()); 	}
		$page_count= mysql_num_rows($res );
		$pagination = ceil($page_count / $page_num );		
		$tpl['pagination']=$pagination;
		$tpl['temp_html'] = 'user_message_send_list.html';
		$cls = new Lib_common();
		$cls->write_html($tpl, "user_wrap.html");
	}
	//
	function user_message_send_list_show($req){
		$param =new Lib_sys_param();
//		$select_sql="id , from_id, send_id, bosyu_id, msg_text, DATE_FORMAT(create_dt ,'%Y-%m-%d %H:%i') as create_dt";
		$query="select *
		    from mat_msg
			where id={$req['id']}
			LIMIT 1
			";
		$res = mysql_query($query);
		if (!$res ) {die('クエリーが失敗しました。'.mysql_error()); 	}
		$dat =array();
		while ($row = mysql_fetch_assoc($res)) {
			$dat =$row;
		}
//var_dump( $dat );
//exit();
		$uc  =new User_common();
//		$send_user =$uc->get_user_info($req['send_id']);
		$bosyu =$uc->get_bosyu_info($dat['bosyu_id']);
		$send =$uc->get_user_info($dat['send_id']);   
		$tpl['dat']=$dat;
		$tpl['send']=$send;
		$tpl['dat']['bosyu'] = $bosyu;
//var_dump( $bosyu );
		$tpl['housyu']    = $param->user_housyu_price_arr[ $dat['price_kbn'] ];	
		$tpl['keiyaku_span_name'] =$param->user_keiyaku_kikan_arr[ $dat['keiyaku_span']  ];	
		$cls = new Lib_common();
		$tpl['temp_html'] = 'user_message_send_list_show.html';
		$cls->write_html($tpl, "user_wrap.html");
	}



}
