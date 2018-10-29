<?php
/**
 * @calling : 概要、admin募集 機能
 * @purpose
 * @date
 * @argment
 * @return
 */


class Admin_bosyu {

	//
	function admin_bosyu_list($req ){
		global $mConfig, $mRequest,  $mUser;
//var_dump($mRequest );
		$page_num= 100;
		$utype='user';
		$select_sql="id, state, koukai,title, price_kbn , DATE_FORMAT(create_dt ,'%Y-%m-%d') as create_dt";
		$query="select {$select_sql}
		    from mat_bosyu
			";
		$order_sql ="		ORDER BY id DESC";
		$limit_sql ="  LIMIT {$page_num}";			
		$koukai_select=9;
		$page_flg=1;
//		if((isset($mRequest['sc_key'])) and (isset($mRequest['koukai']))){
		if((isset($mRequest['sc_key'])) or (isset($mRequest['koukai']))){
			$koukai_select= $mRequest['koukai'];
			$koukai_sql ="";
			if($mRequest['koukai'] !='9'){
				$koukai_sql ="  AND koukai={$mRequest['koukai']}";
			}
			if(isset($mRequest['sc_key'])){
				if(strlen($mRequest['sc_key']) >0){
					$page_flg=0;
				}
//				$sc_key=$mRequest['sc_key'];
			}
			$query="
			select {$select_sql}
			from mat_bosyu
			where 
			(
			ID= '{$mRequest['sc_key']}'
			OR title like '%{$mRequest['sc_key']}%'
			)
			{$koukai_sql}
			";
		}	
		if(isset($mRequest['page'])){
			$st_page = ( (int)($mRequest['page']) -1) * $page_num;
			$limit_sql=" LIMIT {$st_page} , {$page_num}";
			//			$query = $this->get_queryPagenate($mRequest, $page_num ,$select_sql );
		}
//echo($query );
		$query_all=$query;
		$query    = $query . $order_sql . $limit_sql;
		$res = mysql_query($query);
		if (!$res ) {die('クエリーが失敗しました。'.mysql_error()); 	}
		$dat =array();
		$param =new Lib_sys_param();
		while ($row = mysql_fetch_assoc($res)) {
//			$dat[] =$row;
			$item =$row;
			$state_name = $param->admin_state_type_arr[ $item['state'] ];
			$item['state_name'] =$state_name;
			$item['bosyu_koukai_name'] = $param->bosyu_koukai_arr[$item['koukai']];
			$dat[] =$item;
		}
// var_dump( $dat ); 
		$tpl['dat']=$dat;
//		$query_all = "select id from mat_bosyu";
		$res = mysql_query($query_all );
		if (!$res ) {die('クエリーが失敗しました。'.mysql_error()); 	}
		$page_count= mysql_num_rows($res );
		//var_dump( $page_count);		
		$pagination = ceil($page_count / $page_num );		
		$tpl['bosyu_koukai_arr'] = $param->bosyu_koukai_arr;
		$tpl['koukai_select']=$koukai_select;
		$tpl['pagination']=$pagination;
//		$tpl['sc_key']=$sc_key;
		$tpl['page_flg'] = $page_flg;
		$tpl['temp_html'] = 'admin_bosyu_list.html';
		$cls = new Lib_common();
		$cls->write_html($tpl, "admin_wrap.html");
	}
	//
	function admin_bosyu_edit($req ){
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
		$uc =new User_common();
		$bosyu_user =$uc->get_user_info($dat['user_id']);
		$dat['bosyu_user_name'] = $bosyu_user['nickname'];
//var_dump( $dat );
		$tpl['dat']=$dat;
//		$tpl['admin_state_type_arr']   = $param->admin_state_type_arr;
		$tpl['admin_state_type_arr']   = $param->admin_state_type_arr;

		$tpl['housyu']    = $param->user_housyu_price_arr[ $dat['price_kbn'] ];	
		$tpl['keiyaku_span_name'] =$param->user_keiyaku_kikan_arr[ $dat['keiyaku_span']  ];	

		$cls = new Lib_common();
		$tpl['temp_html'] = 'admin_bosyu_edit.html';
		$cls->write_html($tpl, "admin_wrap.html");
	}
	//
	function admin_bosyu_update($req ){
		global $mConfig, $mRequest,  $mUser;
		$cls = new Lib_common();
//var_dump( $req );
		$query="UPDATE mat_bosyu
		SET state='{$req['state']}' 
		,update_dt= now()
		where id={$req['id']}
		";
		$res = mysql_query($query);
		if (!$res ) { die('クエリーが失敗しました。'.mysql_error()); }
		$tpl['msg']='登録が完了しました。';
		$tpl['temp_html'] = 'admin_msg.html';
		$cls->write_sys_message($tpl, "admin_wrap.html");
	}


}
