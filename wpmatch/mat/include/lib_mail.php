<?php
/**
 * @calling : 概要、メール 共通ライブラリ
 * @purpose
 * @date
 * @argment
 * @return
 */
//require_once('../include/user_login.php');


class Lib_mail {
//	global $cfg;
	//------------------------------------------------------------------------------
	//メールテンプレート使用で送信
	//------------------------------------------------------------------------------
	function mail_template($email, $mailsub, $header, $tplfile ,$tpl){
		mb_language("Japanese");
		mb_internal_encoding("UTF-8");

		$mailbody = $this->tpl_file($tplfile, $tpl);
// var_dump($mailbody);
		mb_send_mail($email, $mailsub, $mailbody, $header);
		return true;
	}
	//------------------------------------------------
	//簡易テンプレート処理 
	//------------------------------------------------
	function tpl_file($file,$ps){
		if(!$txt = @file_get_contents ($file)) die("No Template File [ $file ]");
		return $this->tpl($txt, $ps);
	}	
	//------------------------------------------------
	//簡易テンプレート処理、変数の置き換え
	//------------------------------------------------
	function tpl($txt,$ps = array()){
//		global $cfg;
//		$ps['url'] = $cfg['homeurl_ssl'];
		foreach ($ps as $key => $vl) {
			$p[]="{{$key}}";
			$v[]= $vl;
		}
		$txt = str_replace($p, $v, $txt);
		return $txt;
	}
	//
	function aa(){
	}


}

	
