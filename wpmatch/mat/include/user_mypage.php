<?php
/**
 * @calling : 概要、マイページ_処理
 * @purpose
 * @date
 * @argment
 * @return
 */

class User_mypage {
	// 
	function user_mypage_oubo_list($req){
		global $mConfig, $mRequest,  $mUser;
//var_dump($mUser );
		$page_num= 20;
		$utype='user';
//		$select_sql=" mat_oubo.id , mat_bosyu.id as bosyu_id ,mat_bosyu.title
//		, DATE_FORMAT( mat_oubo.create_dt ,'%Y-%m-%d') as oubo_dt
//		";
		$select_sql="
		  mat_oubo.id , mat_bosyu.id as bosyu_id ,mat_bosyu.title 
		, mat_bosyu.price_kbn 
		, DATE_FORMAT( mat_oubo.create_dt ,'%Y-%m-%d') as oubo_dt 
		, mat_bosyu.user_id as bosyu_user_id
		, mat_users.nickname as bosyu_user_name
		";
		$order_sql ="  ORDER BY mat_oubo.id DESC";
		$limit_sql ="  LIMIT {$page_num}";		
		$query="select {$select_sql}
		from mat_oubo, mat_bosyu , mat_users
		where mat_oubo.bosyu_id= mat_bosyu.id
		 AND mat_users.id=mat_bosyu.user_id 
		 AND mat_oubo.user_id={$mUser['id']}
		";
//echo ( $query );
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
			$item['housyu'] =$housyu;
			$dat[] =$item;
		}
// var_dump( $dat );
		$tpl['dat']=$dat;
//		$query_all = "select id from mat_oubo where user_id={$mUser['id']}";
		$res = mysql_query($query_all );
		if (!$res ) {die('クエリーが失敗しました。'.mysql_error()); 	}
		$page_count= mysql_num_rows($res );
//var_dump( $page_count);		
		$pagination = ceil($page_count / $page_num );		
		$tpl['pagination']=$pagination;
		$tpl['temp_html'] = 'user_mypage_oubo_list.html';
		$cls = new Lib_common();
		$cls->write_html($tpl, "user_wrap.html");
	}
	//
	function user_mypage_bosyu_list($req){
		global $mConfig, $mRequest, $mUser ;

		$page_num= 10;
		$utype='user';
		$select_sql="id, title, koukai, price_kbn , DATE_FORMAT(create_dt ,'%Y-%m-%d') as create_dt, bosyu_num";
		$order_sql ="		ORDER BY id DESC";
		$limit_sql ="  LIMIT {$page_num}";
		$query="select {$select_sql}
			from mat_bosyu
			where user_id={$mUser['id']}
			AND state=1
			AND koukai=1
			AND mat_bosyu.bosyu_end > now()
			";
		$koukai=1;
		if(isset($mRequest['koukai'])){
			if($mRequest['koukai']=='0'){
				$query="select {$select_sql}
				from mat_bosyu
				where user_id={$mUser['id']}
				AND
				( mat_bosyu.koukai=0
				 OR mat_bosyu.bosyu_end < now()
				)				 
				";
				$koukai=0;		
			}
		}
		if(isset($mRequest['page'])){
			$st_page = ( (int)($mRequest['page']) -1) * $page_num;
			$limit_sql=" LIMIT {$st_page} , {$page_num}";
//			$query = $this->get_queryPagenate($mRequest, $page_num ,$select_sql );
		}
// echo( $query );
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
			$oubo_num= $this->get_oubo_ninzu($item['id']);
			$item['housyu'] =$housyu;
			$item['oubo_num'] =$oubo_num;
			$dat[] =$item;
		}
// var_dump( $dat );
		$tpl['dat']=$dat;
		//$query_all = "select id from mat_bosyu where user_id={$mUser['id']}";
		$res = mysql_query($query_all );
		if (!$res ) {die('クエリーが失敗しました。'.mysql_error()); 	}
		$page_count= mysql_num_rows($res );
		//var_dump( $page_count);		
		$pagination = ceil($page_count / $page_num );		
		$tpl['pagination']=$pagination;
		$tpl['koukai'] =$koukai;
		$tpl['temp_html'] = 'user_mypage_bosyu_list.html';
		$cls = new Lib_common();
		$cls->write_html($tpl, "user_wrap.html");
	}
	//
	function user_mypage_bosyu_edit($req){
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
		$bosyu_add      =$uc->get_bosyu_add($dat['id']);
		$bosyu_add_size =count( $bosyu_add);
		$sentaku = $this->get_bosyu_sentaku($dat['id'] );
		$tpl['sentaku']          =$sentaku;
		$tpl['sentaku_size']     =count( $sentaku);;
// var_dump( $sentaku );
//var_dump( $tpl['sentaku_size'] );
		$tpl['bosyu_add']     =$bosyu_add;
		$tpl['bosyu_add_size']=$bosyu_add_size;
		$tpl['admin_state_type_bosyu']   = $param->admin_state_type_arr;
		$tpl['housyu']    = $param->user_housyu_price_arr[ $dat['price_kbn'] ];	
		$tpl['keiyaku_span_name'] =$param->user_keiyaku_kikan_arr[ $dat['keiyaku_span']  ];	
		$cls = new Lib_common();
		$tpl['temp_html'] = 'user_mypage_bosyu_edit.html';
		$cls->write_html($tpl, "user_wrap.html");
	}
	//
	function get_bosyu_sentaku($id){
		global $mConfig, $mRequest,  $mUser;
		$query="
		select mat_oubo.id as oubo_id ,mat_oubo.user_id ,mat_users.nickname
		,mat_bosyu_sentaku.sentaku_date
		from  mat_bosyu_sentaku, mat_oubo, mat_users
		where 
		mat_bosyu_sentaku.oubo_id =mat_oubo.id
		AND mat_users.id= mat_oubo.user_id 
		AND mat_bosyu_sentaku.bosyu_id={$id}		
		";
//echo( $query  );
		$res = mysql_query($query);
		if (!$res ) {die('クエリーが失敗しました。'.mysql_error()); 	}
		$dat =array();
		$param =new Lib_sys_param();
		$uco= new User_common();
		while ($row = mysql_fetch_assoc($res)) {
//			$item =$row;
//			$oubo_user=$uco->get_user_info($item['oubo_id']);
//			$item[''] =$oubo_user['nickname'];
			$dat[] =$row;
		}
		return $dat;
	}

	//
	function user_mypage_bosyu_update($req){
		global $mConfig, $mRequest,  $mUser;
		$cls = new Lib_common();
//var_dump( $req );
//exit();
		$query="UPDATE mat_bosyu
		SET state='{$req['state']}' 
		, koukai=0
		,update_dt= now()
		where id={$req['id']}
		";
		$res = mysql_query($query);
		if (!$res ) { die('クエリーが失敗しました。'.mysql_error()); }
		$tpl['msg']='登録が完了しました。';
		$tpl['temp_html'] = 'user_msg.html';
		$cls->write_sys_message($tpl, "user_wrap.html");
	}
	//
	function get_oubo_ninzu($bosyu_id){
		$ret=0;
				/*
		$query="
		select id from 
		mat_oubo 
		where bosyu_id={$bosyu_id}
		";
		*/
		$query="
		select  distinct user_id from 
		mat_oubo 
		where bosyu_id={$bosyu_id}
		";
		$res = mysql_query($query );
		if (!$res ) {die('クエリーが失敗しました。'.mysql_error()); 	}
		$num= mysql_num_rows($res );
		$ret= $num;
		return $ret;
	}
	//
	function user_bosyu_sentaku_list($req){
		global $mConfig, $mRequest,  $mUser;
// var_dump($req);
		$page_num= 1000;
		$utype='user';
		$select_sql="mat_oubo.id
		, mat_bosyu.id as bosyu_id
		, mat_oubo.user_id
		, mat_users.email
		, mat_users.nickname
		, mat_oubo.price
		, mat_oubo.oubo_date
		";
		$order_sql ="		ORDER BY id DESC";
		$limit_sql ="  LIMIT {$page_num}";
		$query="select {$select_sql}
		  from mat_oubo, mat_bosyu, mat_users
		where
		  mat_bosyu.id=mat_oubo.bosyu_id
		  AND mat_users.id = mat_oubo.user_id
		  AND mat_bosyu.id={$req['id']}
			";
//echo ($query);
		if(isset($mRequest['page'])){
			$query = $this->get_queryPagenate($mRequest, $page_num ,$select_sql );
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
			$price_fmt=number_format( (float)$item['price'] );
			$item['price_fmt'] =$price_fmt;
			$item['housyu'] =$housyu;
			$dat[] =$item;
		}
		$tpl['dat']=$dat;
		/*
		$query_all = "select id from mat_oubo where bosyu_id={$req['id']}";
		$res = mysql_query($query_all );
		if (!$res ) {die('クエリーが失敗しました。'.mysql_error()); 	}
		$page_count= mysql_num_rows($res );
		$pagination = ceil($page_count / $page_num );		
		$tpl['pagination']=$pagination;
		*/
		$tpl['temp_html'] = 'user_bosyu_sentaku_list.html';
		$cls=new User_common();
		$bosyu =$cls->get_bosyu_info( $req['id'] );
		$tpl['bosyu']= $bosyu;
//var_dump( $tpl );
		$cls = new Lib_common();
		$cls->write_html($tpl, "user_wrap.html");
	}
	//
	function user_bosyu_sentaku_show($req){
		$param =new Lib_sys_param();
		$select_sql="mat_oubo.id as oubo_id, mat_oubo.user_id, mat_bosyu.title
		, mat_bosyu.id as bosyu_id
		, mat_users.nickname
		, mat_bosyu.price_kbn , DATE_FORMAT(mat_bosyu.bosyu_end ,'%Y-%m-%d') as bosyu_end
		, mat_oubo.oubo_date
		, mat_oubo.oubo_text
		, mat_oubo.price
		";
		$query="select {$select_sql}
		from mat_oubo, mat_bosyu, mat_users
		where
		  mat_bosyu.id=mat_oubo.bosyu_id
		  AND mat_users.id = mat_oubo.user_id
		  AND mat_oubo.id={$req['id']}
		LIMIT 1
		";

		$res = mysql_query($query);
		if (!$res ) {die('クエリーが失敗しました。'.mysql_error()); 	}
		$dat =array();
		while ($row = mysql_fetch_assoc($res)) {
			$dat =$row;
		}
		$dat['housyu']    = $param->user_housyu_price_arr[ $dat['price_kbn'] ];
//var_dump( $dat );
		$tpl['dat']=$dat;
		$tpl['housyu']    = $param->user_housyu_price_arr[ $dat['price_kbn'] ];	
		$tpl['keiyaku_span_name'] =$param->user_keiyaku_kikan_arr[ $dat['keiyaku_span']  ];	
		$tpl['dat']['price_fmt'] =number_format( (float)$dat['price'] );
		$cls = new Lib_common();
		$tpl['temp_html'] = 'user_bosyu_sentaku_show.html';
		$cls->write_html($tpl, "user_wrap.html");
	}
	//
	function user_bosyu_sentaku_add($req){
		global $mConfig, $mRequest,  $mUser;
		$cls = new Lib_common();
		$sentaku_dat =$this->get_bosyu_sentaku($req['bosyu_id']);
		$sen_num=$this->bosyu_sentaku_add_check($req);
//var_dump($sen_num );
// if (in_array("Irix", $sentaku_dat)) {
//		if(in_array($req['oubo_id'] , $sentaku_dat)){
		if($sen_num > 0){
			$msg="この応募は、当選済みです。" . "　応募ID={$req['oubo_id']}";
			$tpl['error']= $msg;
 			$tpl['temp_html'] = 'user_msg.html';
			$cls->write_sys_message($tpl, "user_wrap.html");
		}
//exit();		
		$query="INSERT INTO mat_bosyu_sentaku
		SET bosyu_id={$req['bosyu_id']}
		,oubo_id='{$req['oubo_id']}'
		,sentaku_date=now()
		,user_id={$mUser['id']}
		";
		$query= $cls->addSql_creat($query);
//var_dump($query );
//exit();
		$res = mysql_query($query);
		if (!$res ) { die('INSERTクエリーが失敗しました。'.mysql_error());	}
		//bosyu
		$uc= new User_common();
		$bosyu = $uc->get_bosyu_info( $req['bosyu_id']);
		$bosyu_num = $bosyu['bosyu_num'];
		$sentaku =$this->get_sentaku_num( $req['bosyu_id'] );
//var_dump($sentaku );
//exit();
		if($bosyu_num <= $sentaku ){
			$query="
			UPDATE mat_bosyu
			SET koukai=0
			 where ID={$req['bosyu_id']}
			 ";
			 $res = mysql_query($query);
			 if (!$res ) { die('INSERTクエリーが失敗しました。'.mysql_error());	}
		}
		// mail
		$uco =new User_common();
		$spara =$uco->get_sys_param();
		if($spara['mail_send']=='1'){
			$this->bosyu_sentaku_add_mail($req );
		}
		//
		$tpl['msg']='登録が完了しました。';
		$tpl['temp_html'] = 'user_msg.html';
		$cls->write_sys_message($tpl, "user_wrap.html");
	}
	//
	function bosyu_sentaku_add_check($req){
		global $mConfig, $mRequest,  $mUser;
		$ret=0;
		$query="
		select id from mat_bosyu_sentaku
			where
			bosyu_id={$req['bosyu_id']}
			and oubo_id={$req['oubo_id']};
		";
		$res = mysql_query($query );
		if (!$res ) {die('クエリーが失敗しました。'.mysql_error()); 	}
		$ret= mysql_num_rows($res );
		return $ret;
	}
	//
	function bosyu_sentaku_add_mail($req ){
		global $mConfig, $mRequest,  $mUser;
		$uco= new User_common();
//		$oubo_user= $uco->get_user_info($mUser['id']);
		$bosyu_user= $uco->get_user_info($mUser['id']);
		$bosyu  = $uco->get_bosyu_info($req['bosyu_id']);
		$oubo   = $uco->get_oubo_info($req['oubo_id']);
//var_dump($oubo);
//		$bosyu_user= $uco->get_user_info($bosyu['user_id']);
		$oubo_user= $uco->get_user_info($oubo['user_id']);
		$email       = $oubo_user['email'];
		$tpl['from_name'] = $bosyu_user["nickname"];
		$tpl['send_name'] = $oubo_user["nickname"];
		$tpl['bosyu_title'] = $bosyu["title"];
		$tpl['bosyu_id']    = $bosyu['id'];
//		$tpl['oubo_id']     = $oubo_id;
		//
		$bosyu_url = $mConfig['host_name'] . $mConfig['base_url'] . "/mat/user/?fn=user_oubo_show&id={$req['bosyu_id']}";
		$tpl['bosyu_url'] = $bosyu_url;
		$header = 'From: ' . $mConfig["mail_from_addr"] . "\r\n";
		$tplfile = "{$mConfig['mail_temp_dir']}/mail_bosyu_sentaku.txt";
//var_dump( $tpl  );
		//
		$mail =new Lib_mail();
		$mailsub = "[ {$mConfig['sys_title']} ] 応募 当選の連絡となります。";
		$mail->mail_template($email, $mailsub, $header, $tplfile ,$tpl);
	}
	//
	function get_sentaku_num($bosyu_id){
		$ret=0;
		$query="
		select id from mat_bosyu_sentaku
		where bosyu_id={$bosyu_id}
		";
		$res = mysql_query($query );
		if (!$res ) {die('クエリーが失敗しました。'.mysql_error()); 	}
		$ret= mysql_num_rows($res );
		return $ret;
	}	
	/*
	function get_bosyu_ninzu($bosyu_id){
		$ret=0;
		$query ="
		select id , bosyu_num
		from mat_bosyu
		where id={$bosyu_id}	   
		";
		return $ret;
	} 
	*/
	//
	function user_mypage_prof_show($req){

	}
	//
	function user_mypage_oubo_show($req ){
		global $mConfig, $mRequest,  $mUser;
//var_dump($mUser );
//exit();
		$param =new Lib_sys_param();
		/*
		$query="select * from mat_bosyu
			where id={$req['id']}
			LIMIT 1
			";
		*/
		$select_sql="mat_oubo.id as oubo_id, mat_oubo.user_id, mat_bosyu.title
		, mat_bosyu.id as bosyu_id
		, mat_bosyu.user_id as bosyu_user_id
		, mat_bosyu.bosyu_num
		, mat_bosyu.naiyou
		, mat_users.nickname
		, mat_bosyu.price_kbn , DATE_FORMAT(mat_bosyu.bosyu_end ,'%Y-%m-%d') as bosyu_end
		, mat_oubo.oubo_date
		, mat_oubo.oubo_text
		, mat_oubo.price
		";
		$query="select {$select_sql}
		from mat_oubo, mat_bosyu, mat_users
		where
			mat_bosyu.id=mat_oubo.bosyu_id
			AND mat_users.id = mat_oubo.user_id
			AND mat_oubo.id={$req['id']}
		LIMIT 1
		";	
//echo ($query );		
		$res = mysql_query($query);
		if (!$res ) {die('クエリーが失敗しました。'.mysql_error()); 	}
		$dat =array();
		$uc =new User_common();
		while ($row = mysql_fetch_assoc($res)) {
			$item =$row;
//			$bosyu_user =$uc->get_user_info($item['user_id']);
//			$item['bosyu_user_name'] = $bosyu_user['nickname'];
			$dat =$item;
		}
		$tpl['dat']=$dat;
		//add_text
		$bosyu_add =$uc->get_bosyu_add($dat['bosyu_id']);
		$bosyu_add_size =count( $bosyu_add);
//var_dump( $dat );
		$tpl['bosyu_add']     =$bosyu_add;
		$tpl['bosyu_add_size']=$bosyu_add_size;
		$tpl['housyu']    = $param->user_housyu_price_arr[ $dat['price_kbn'] ];	
		$tpl['keiyaku_span_name'] =$param->user_keiyaku_kikan_arr[ $dat['keiyaku_span']  ];	
		$tpl['housyu']    = $param->user_housyu_price_arr[ $dat['price_kbn'] ];	
		$tpl['dat']['price_fmt'] =number_format( (float)$dat['price'] );
		$cls = new Lib_common();
		$tpl['temp_html'] = 'user_mypage_oubo_show.html';
		$cls->write_html($tpl, "user_wrap.html");
	}


}
