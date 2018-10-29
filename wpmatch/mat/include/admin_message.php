<?php
/**
 * @calling : 概要、admin募集 機能
 * @purpose
 * @date
 * @argment
 * @return
 */


class Admin_message {

	//
	function admin_msg_list($req){
		global $mConfig, $mRequest,  $mUser;
		$page_num= 100;
		$utype='user';
//		$select_sql="id , from_id, msg_text, DATE_FORMAT(create_dt ,'%Y-%m-%d %H:%i') as create_dt";
		$select_sql=" mat_msg.id , mat_msg.from_id
		,mat_users.nickname
	   , msg_text
	   , DATE_FORMAT( mat_msg.create_dt ,'%Y-%m-%d %H:%i') as create_dt
	   ";
		$query="select {$select_sql}
		from mat_msg, mat_users
		where 
			mat_msg.from_id= mat_users.id			
		ORDER BY id DESC
		LIMIT {$page_num}
		";
		//
		if(isset($mRequest['sc_key'])){
			$query="select {$select_sql}
			from mat_msg, mat_users
			where 
				 mat_msg.from_id= mat_users.id
				AND
				(
					mat_msg.id= '{$mRequest['sc_key']}'
				  OR mat_msg.msg_text like '%{$mRequest['sc_key']}%'
				)			
			ORDER BY id DESC
			LIMIT {$page_num}
			";
		}
		if(isset($mRequest['page'])){
			$query = $this->get_queryPagenate($mRequest, $page_num ,$select_sql );
		}
		$res = mysql_query($query);
		if (!$res ) {die('クエリーが失敗しました。'.mysql_error()); 	}
		$dat =array();
		$param =new Lib_sys_param();
		while ($row = mysql_fetch_assoc($res)) {
		//			$dat[] =$row;
			$item =$row;
			$housyu = $param->user_housyu_price_arr[ $item['price_kbn'] ];
			$item['housyu'] =$housyu;
			$msg_fmt=  mb_strimwidth($item['msg_text'], 0, 60, "...");
			$item['msg_fmt']=  nl2br($msg_fmt);
			$dat[] =$item;
		}
// var_dump( $dat );
		$tpl['dat']=$dat;
		$query_all = "select id from mat_bosyu";
		$res = mysql_query($query_all );
		if (!$res ) {die('クエリーが失敗しました。'.mysql_error()); 	}
		$page_count= mysql_num_rows($res );
		$pagination = ceil($page_count / $page_num );		
		$tpl['pagination']=$pagination;
		$tpl['temp_html'] = 'admin_msg_list.html';
		$cls = new Lib_common();
		$cls->write_html($tpl, "admin_wrap.html");
	}
	//
	function admin_msg_show($req){
		$param =new Lib_sys_param();
		$query="select * from mat_msg
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
		$tpl['dat']=$dat;
		$uc  =new User_common();
		$bosyu =$uc->get_bosyu_info($dat['bosyu_id']);
		$uc = new User_common();
		$from_user =$uc->get_user_info($dat['from_id']);   
		$tpl['dat']['from_user'] = $from_user;
		$send_user =$uc->get_user_info($dat['send_id']);   
		$tpl['dat']['send_user'] = $send_user;

		$tpl['dat']['bosyu'] = $bosyu;
//var_dump( $bosyu );
		$cls = new Lib_common();
		$tpl['temp_html'] = 'admin_msg_show.html';
		$cls->write_html($tpl, "admin_wrap.html");
	}





}
