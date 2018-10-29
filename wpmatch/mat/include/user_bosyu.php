<?php
/**
 * @calling : 概要、募集処理
 * @purpose
 * @date
 * @argment
 * @return
 */

class User_bosyu {
	// 
	function bosyu_add_show($req){
		global $mConfig, $mRequest,  $mUser;
		$cls = new Lib_common();
		$param =new Lib_sys_param();
//var_dump( $mUser );
		$tpl['bosyu_kikan_arr']   = $param->user_bosyu_kikan_arr;
		$tpl['keiyaku_kikan_arr'] = $param->user_keiyaku_kikan_arr ;
		$tpl['housyu_price_arr'] = $param->user_housyu_price_arr ;
//		$tpl['bosyu_num'] = 1;
		$tpl['dat']['bosyu_num'] =1;
		$tpl['temp_html'] = 'user_bosyu.html';
		$cls->write_sys_message($tpl, "user_wrap.html");
	}
	//
	function user_bosyu_kakunin($req){
		$cls = new Lib_common();
		$tpl=array();
		$tpl = $req;
		$param =new Lib_sys_param();
		$arr = $this->bosyu_add_check($req);
//var_dump($req );
 		if(count($arr) > 0){
			$tpl['dat']=$req;
			$msg=$cls->get_convert_msg($arr );
			 $tpl['error']= $msg;
			 $tpl['bosyu_kikan_arr']   = $param->user_bosyu_kikan_arr;
			 $tpl['keiyaku_kikan_arr'] = $param->user_keiyaku_kikan_arr ;
			 $tpl['housyu_price_arr'] = $param->user_housyu_price_arr ;
	 
			 $tpl['temp_html'] = 'user_bosyu.html';
			 $cls->write_html($tpl, "user_wrap.html");
// 			$tpl['temp_html'] = 'user_msg.html';
//			$cls->write_sys_message($tpl, "user_wrap.html");
 		}
		//echo date("Y-m-d H:i:s",strtotime("+3 day"));
		$dt_diff = "+{$req['bosyu_end_kbn']} day";
//echo date("Y-m-d H:i",strtotime("+3 day"));
//echo date("Y-m-d H:i",strtotime( $dt_diff  ));
		$bosyu_end = date("Y-m-d H:i",strtotime( $dt_diff ));
//		$tpl['bosyu_end'] = $param->user_bosyu_kikan_arr[ $req['bosyu_end_kbn'] ];
		$tpl['bosyu_end'] = $bosyu_end ;
		$tpl['housyu']    = $param->user_housyu_price_arr[ $req['price_kbn'] ];
		$tpl['keiyaku_span_name'] =$param->user_keiyaku_kikan_arr[ $req['keiyaku_span']  ];
//		$tpl['bosyu_kikan_arr']   = $param->user_bosyu_kikan_arr;
//		$tpl['keiyaku_kikan_arr'] = $param->user_keiyaku_kikan_arr ;
//		$tpl['housyu_price_arr'] = $param->user_housyu_price_arr ;
//		var_dump($req );
		$tpl['temp_html'] = 'user_bosyu_kakunin.html';
		$cls->write_html($tpl, "user_wrap.html");
	}
	//
	function user_bosyu_add($req){
		global $mConfig, $mRequest,  $mUser;
//		var_dump($req);
		$cls = new Lib_common();
 		$query="INSERT INTO mat_bosyu
		SET title = '{$req['title']}' , bosyu_end='{$req['bosyu_end']}'
		,  keiyaku_span='{$req['keiyaku_span']}'
		,  bosyu_num   ='{$req['bosyu_num']}'
		,  naiyou     ='{$req['naiyou']}'
		,  price_kbn  ='{$req['price_kbn']}'
		,  bosyu_end_kbn  ='{$req['bosyu_end_kbn']}'
		,bosyu_start=now()
		,koukai=1
		,state=1
		,user_id={$mUser['id']}
		";
		$query= $cls->addSql_creat($query);
		$res = mysql_query($query);
		if (!$res ) { die('INSERTクエリーが失敗しました。'.mysql_error());	}
		//
		$tpl['msg']='登録が完了しました。';
		$tpl['temp_html'] = 'user_msg.html';
		$cls->write_sys_message($tpl, "user_wrap.html");
	}
	//
	function bosyu_add_check($req){
		$ret= array();
		if(strlen($req['title']) < 1){ $ret[] ='タイトル を入力下さい。'; }
		if(strlen($req['naiyou']) < 1){ $ret[] ='募集内容 を入力下さい。'; }
		if(strlen($req['bosyu_num']) < 1){ $ret[] ='募集人数 を入力下さい。'; }
		//num
		if(preg_match("/^[0-9]+$/", $req['bosyu_num'])==false){ $ret[] ='募集人数は、半角数字 を入力下さい。'; }
		return $ret;
	}
	//
	function user_bosyu_addtext_new($req){
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
		$tpl['temp_html'] = 'user_bosyu_addtext_new.html';
		$cls->write_html($tpl, "user_wrap.html");
	}
	//
	function user_bosyu_addtext_add($req){
		global $mConfig, $mRequest,  $mUser;
		$cls = new Lib_common();

		$arr = $this->bosyu_addtext_check($req);
//var_dump($req );
 		if(count($arr) > 0){
			$tpl['dat']=$req;
			$msg=$cls->get_convert_msg($arr );
			 $tpl['error']= $msg;
//			 $tpl['bosyu_kikan_arr']   = $param->user_bosyu_kikan_arr;
//			 $tpl['keiyaku_kikan_arr'] = $param->user_keiyaku_kikan_arr ;
//			 $tpl['housyu_price_arr'] = $param->user_housyu_price_arr ;
			$tpl['temp_html'] = 'user_msg_back.html';
			$cls->write_sys_message($tpl, "user_wrap.html");	 
 		}
		$query="INSERT INTO mat_bosyu_addtext
		SET bosyu_id={$req['id']}
		,add_text='{$req['add_text']}'
		,user_id={$mUser['id']}
		";
		$query= $cls->addSql_creat($query);
//var_dump($query );
//exit();
		$res = mysql_query($query);
		if (!$res ) { die('INSERTクエリーが失敗しました。'.mysql_error());	}
		//
		$tpl['msg']='登録が完了しました。';
		$tpl['temp_html'] = 'user_msg.html';
		$cls->write_sys_message($tpl, "user_wrap.html");
	}
	//
	function bosyu_addtext_check($req){
		$ret= array();
		if(strlen($req['add_text']) < 1){ $ret[] ='追記内容 を入力下さい。'; }
		return $ret;
	}
	

}
