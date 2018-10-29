<?php
/**
 * @calling : 概要、応募_処理
 * @purpose
 * @date
 * @argment
 * @return
 */

class User_oubo {
	// 
	function user_oubo_list($req){
		global $mConfig, $mRequest,  $mUser;
// var_dump($mRequest );
		$page_num= 20;
		$utype='user';
		$select_sql="
		mat_bosyu.id
		, mat_bosyu.title
		, mat_bosyu.price_kbn 
		, mat_bosyu.koukai
		, DATE_FORMAT( mat_bosyu.create_dt ,'%Y-%m-%d') as create_dt
		, mat_bosyu.user_id 
		, mat_users.nickname		
		";
		$order_sql ="		ORDER BY id DESC";
		$limit_sql ="  LIMIT {$page_num}";
		$query="select {$select_sql}
		from mat_bosyu, mat_users
		where
		 mat_users.id = mat_bosyu.user_id
		 AND mat_bosyu.koukai=1
		 AND mat_bosyu.bosyu_end > now()		 
		 ";
		$koukai=1;
		$koukai_select=1;
		if(isset($mRequest['koukai'])){
			if($mRequest['koukai']=='0'){
				$query="select {$select_sql}
				from mat_bosyu, mat_users
				where
				 mat_users.id = mat_bosyu.user_id
				 AND
				 ( mat_bosyu.koukai=0
				  OR mat_bosyu.bosyu_end < now()
				 )				 
				";	
				$koukai=0;	
			}
			$koukai_select= $mRequest['koukai'];
		}
		if(isset($mRequest['page'])){
			$st_page = ( (int)($mRequest['page']) -1) * $page_num;
			$limit_sql=" LIMIT {$st_page} , {$page_num}";
//			$query = $this->get_queryPagenate($mRequest, $page_num ,$select_sql );
		}
		$query_all=$query;
		$query    = $query . $order_sql . $limit_sql;
//echo($query_all);
		$res = mysql_query($query);
		if (!$res ) {die('クエリーが失敗しました。'.mysql_error()); 	}
		$dat =array();
		$param =new Lib_sys_param();
		// 
		while ($row = mysql_fetch_assoc($res)) {
//			$dat[] =$row;
			$item =$row;
			$housyu = $param->user_housyu_price_arr[ $item['price_kbn'] ];
			$item['housyu'] =$housyu;
			$dat[] =$item;
		}
// var_dump( $dat );
		$tpl['dat']=$dat;
		$res = mysql_query($query_all );
		if (!$res ) {die('クエリーが失敗しました。'.mysql_error()); 	}
		$page_count= mysql_num_rows($res );
//var_dump( $page_count);		
		$pagination = ceil($page_count / $page_num );		
		$tpl['pagination']=$pagination;
		$tpl['koukai_select']=$koukai_select;
		$tpl['bosyu_koukai_arr'] = $param->bosyu_koukai_arr;
		$tpl['koukai'] =$koukai;
		$tpl['temp_html'] = 'user_oubo_list.html';
		$cls = new Lib_common();
		$cls->write_html($tpl, "user_wrap.html");
	}
	//
	function get_queryPagenate($req, $page_num ,$select_sql ){
		$st_page = ( (int)($req['page']) -1) * $page_num;
		$query="select {$select_sql} from mat_bosyu
			ORDER BY id DESC
			LIMIT {$st_page} , {$page_num}
			";
		return $query;
	}
	//
	function user_oubo_show($req){
		global $mConfig, $mRequest,  $mUser;
// var_dump($mUser );
		$param =new Lib_sys_param();
		$query="select * from mat_bosyu
			where id={$req['id']}
			LIMIT 1
			";
		$res = mysql_query($query);
		if (!$res ) {die('クエリーが失敗しました。'.mysql_error()); 	}
		$dat =array();
		$uc =new User_common();
		while ($row = mysql_fetch_assoc($res)) {
			$item =$row;
			$bosyu_user =$uc->get_user_info($item['user_id']);
			$item['bosyu_user_name'] = $bosyu_user['nickname'];
			$dat =$item; 
		}
		$tpl['dat']=$dat;
		// bosyu_sentaku
		$query_all = "
		select id
		from mat_bosyu_sentaku
		where
		 bosyu_id={$req['id']}	   
		";
		$res = mysql_query($query_all );
		if (!$res ) {die('クエリーが失敗しました。'.mysql_error()); 	}
		$ct_sentaku= mysql_num_rows($res );
// var_dump($ct_sentaku );
//		$bosyu_add =$this->get_bosyu_add($dat['id']);
		$bosyu_add =$uc->get_bosyu_add($dat['id']);
		$bosyu_add_size =count( $bosyu_add);
// var_dump( $dat );
		$tpl['ct_sentaku']     =$ct_sentaku;
		$tpl['bosyu_add']     =$bosyu_add;
		$tpl['bosyu_add_size']=$bosyu_add_size;
		$tpl['housyu']    = $param->user_housyu_price_arr[ $dat['price_kbn'] ];	
		$tpl['keiyaku_span_name'] =$param->user_keiyaku_kikan_arr[ $dat['keiyaku_span']  ];	
		$cls = new Lib_common();
		$tpl['temp_html'] = 'user_oubo_show.html';
		$cls->write_html($tpl, "user_wrap.html");
	}
	/*
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
	*/
	//
	function user_oubo_add_show($req){
//var_dump( $req );
		$param =new Lib_sys_param();
		$query="select * from mat_bosyu
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
		$tpl['housyu']    = $param->user_housyu_price_arr[ $dat['price_kbn'] ];	
		$tpl['keiyaku_span_name'] =$param->user_keiyaku_kikan_arr[ $dat['keiyaku_span']  ];	
		$cls = new Lib_common();
		$tpl['temp_html'] = 'user_oubo_add_show.html';
		$cls->write_html($tpl, "user_wrap.html");
	}
	//
	function user_oubo_kakunin($req){
		$cls = new Lib_common();
		$param =new Lib_sys_param();
		$arr = $this->oubo_add_check($req);
		if(count($arr) > 0){
	//			$tpl['dat']=$req;
			$uco= new User_common();
			$dat = $uco->get_bosyu_info($req['id'] );
//var_dump( $req );
			$msg=$cls->get_convert_msg($arr );
			 $tpl['error']= $msg;
			 $tpl['dat']=$dat;
//			 $tpl['bosyu_kikan_arr']   = $param->user_bosyu_kikan_arr;
//			 $tpl['keiyaku_kikan_arr'] = $param->user_keiyaku_kikan_arr ;
//			 $tpl['housyu_price_arr'] = $param->user_housyu_price_arr ;
			 $tpl['price']    = $req['price'];
			 $tpl['oubo_text']    = $req['oubo_text'];	
			 // oubo_text
			 $tpl['housyu']    = $param->user_housyu_price_arr[ $dat['price_kbn'] ];	
			 $tpl['keiyaku_span_name'] =$param->user_keiyaku_kikan_arr[ $dat['keiyaku_span']  ];		 
			 $tpl['temp_html'] = 'user_oubo_add_show.html';
			 $cls->write_html($tpl, "user_wrap.html");
 		}
		$query="select * from mat_bosyu
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
		$tpl['dat']['price'] =$req['price'];
		$tpl['dat']['oubo_text'] =$req['oubo_text'];
// var_dump( $tpl['dat'] );
		$tpl['housyu']    = $param->user_housyu_price_arr[ $dat['price_kbn'] ];	
		$tpl['keiyaku_span_name'] =$param->user_keiyaku_kikan_arr[ $dat['keiyaku_span']  ];	
//		$cls = new Lib_common();
		$tpl['temp_html'] = 'user_oubo_kakunin.html';
		$cls->write_html($tpl, "user_wrap.html");

	}
	//
	function oubo_add_check($req){
		$ret= array();
		if(strlen($req['price']) < 1){ $ret[] ='報酬金額 を入力下さい。'; }
		if(strlen($req['oubo_text']) < 1){ $ret[] ='応募内容 を入力下さい。'; }
		//num
		if(preg_match("/^[0-9]+$/", $req['price'])==false){ $ret[] ='報酬金額は、半角数字 を入力下さい。'; }
		return $ret;
	}
	//
	function user_oubo_add($req){
// var_dump($req );
		global $mConfig, $mRequest,  $mUser;
		$cls = new Lib_common();
		$query="INSERT INTO mat_oubo
		SET bosyu_id={$req['id']}
		,oubo_text='{$req['oubo_text']}'
		,price={$req['price']}
		,oubo_date=now()
		,user_id={$mUser['id']}
		";
		$query= $cls->addSql_creat($query);
//var_dump($query );
//exit();
		$res = mysql_query($query);
		if (!$res ) { die('INSERTクエリーが失敗しました。'.mysql_error());	}
		// lasst_id
		$query="select max(id) as oubo_id  from  mat_oubo
		where user_id={$mUser['id']}		
		";
		$res = mysql_query($query);
		if (!$res ) {die('クエリーが失敗しました。'.mysql_error()); 	}
		$num= mysql_num_rows($res );
		if( $num < 1 ){
			$tpl['error']="応募IDの、取得処理に失敗しました。";
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
			$this->oubo_send_mail($req, $dat['oubo_id']);
		}
		//
		$tpl['msg']='登録が完了しました。';
		$tpl['temp_html'] = 'user_msg.html';
		$cls->write_sys_message($tpl, "user_wrap.html");
	}
	//
	function oubo_send_mail($req ,$oubo_id){
		global $mConfig, $mRequest,  $mUser;
		$uco= new User_common();
		$oubo_user= $uco->get_user_info($mUser['id']);
		$bosyu  = $uco->get_bosyu_info($req['id']);
		$bosyu_user= $uco->get_user_info($bosyu['user_id']);
		$email       = $bosyu_user['email'];
		$tpl['name'] = $bosyu_user["nickname"];
		$tpl['oubo_name'] = $oubo_user["nickname"];
		$tpl['bosyu_title'] = $bosyu["title"];
		$tpl['bosyu_id'] = $bosyu['id'];
		$tpl['oubo_id'] = $oubo_id;
		// http://localhost/wpmatch/mat/user/?fn=user_mypage_oubo_show&id=26
		$oubo_url = $mConfig['host_name'] . $mConfig['base_url'] . "/mat/user/?fn=user_mypage_oubo_show&id={$oubo_id}";
		$tpl['oubo_url'] = $oubo_url;
//		$header = 'From: {$mConfig["mail_from_addr"]}' . "\r\n";
		$header = 'From: ' . $mConfig["mail_from_addr"] . "\r\n";
//		$header = 'From: info@kuc-arc-f.com' . "\r\n";
		$tplfile = "{$mConfig['mail_temp_dir']}/mail_oubo_add.txt";
//var_dump( $tpl  );
		//
		$mail =new Lib_mail();
		$mailsub = "[ {$mConfig['sys_title']} ] 応募者が登録されました。";
		$mail->mail_template($email, $mailsub, $header, $tplfile ,$tpl);
	}
	/*
	function user_oubo_addtext_new($req){
	}
	*/

}
