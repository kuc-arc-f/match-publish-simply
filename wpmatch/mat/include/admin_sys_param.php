<?php
/**
 * @calling : 概要、システム全体設定 機能
 * @purpose
 * @date
 * @argment
 * @return
 */


class Admin_sys_param {
	//
	function admin_sys_param_edit($req){

		$param = new Lib_sys_param();
		$query="select * from mat_sys_param
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
		$tpl['admin_type_yn_arr'] =$param->admin_type_yn_arr;		
		$cls = new Lib_common();
		$tpl['temp_html'] = 'admin_sys_param_edit.html';
		$cls->write_html($tpl, "admin_wrap.html");
	}
	//
	function admin_sys_param_update($req){
		global $mConfig, $mRequest,  $mUser;
		$cls = new Lib_common();
//var_dump( $req );
//exit();
		//
		$query="UPDATE mat_sys_param
		SET mail_send='{$req['mail_send']}' 
		where id={$req['id']};
		";
		$res = mysql_query($query);
		if (!$res ) { die('クエリーが失敗しました。'.mysql_error()); }
		$tpl['msg']='登録が完了しました。';
		$tpl['temp_html'] = 'admin_msg.html';
		$cls->write_sys_message($tpl, "admin_wrap.html");
//		$cls->write_html($tpl, "admin_wrap.html");
	}
	//
	function aa(){
	}


}
