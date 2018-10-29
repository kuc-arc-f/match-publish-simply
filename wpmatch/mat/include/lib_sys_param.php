<?php
/**
 * @calling : 概要、共通-定数
 * @purpose
 * @date
 * @argment
 * @return
 */


class Lib_sys_param {
	
//	var $aa=1;

	var $user_bosyu_kikan_arr= array(
		'3'  => '3日',
		'7'  => '7日',
		'30' => '30日',
	);
	/*
		'3D' => '3日',
		'1W' => '1週間',
		'1D' => '1カ月',	
	*/
	//
	var $user_keiyaku_kikan_arr= array(
		'none' => '指定なし',
		'1week' => '1週間',
		'1month' => '1カ月',
		'3month' => '3カ月',
		'6month' => '6カ月',
	);
	//
	var $user_housyu_price_arr= array(
		'0' => '応募者と相談する',
		'1' => '5,000 円以下',
		'2' => '5,000 ～ 10,000円以下',
		'3' => '10,000 ～ 50,000円以下',
		'4' => '50,000 ～ 100,000円以下',
		'5' => '100,000 ～ 500,000円以下',
		'6' => '500,000 ～ 1000,000円以下',
		'7' => '1000,000 ～ 5000,000円以下',
	);
	//
	var $sanka_type=array(
		'bosyu'=>'募集する',
		'oubo' =>'応募する',
	);
	/*
	var $sc_sanka_type=array(
		'all'  =>'全て',
		'bosyu'=>'募集する',
		'oubo' =>'応募する',
	);	
	*/
	//
	var $admin_type_yn_arr=array(
		'1' => '有効',
		'0' => '無効',
	);
	//
	var $admin_state_type_arr=array(
		'1' => '有効',
		'0' => '無効',
	);
	/*
	var $admin_state_type_arr=array(
		'1' => '有効',
		'2' => '無効/ユーザーキャンセル',
		'3' => '無効/管理者の無効化',
	);
		var $admin_state_type_bosyu =array(
		'1' => '有効',
		'2' => '無効/ユーザーキャンセル',
	);
	var $admin_state_type_admin =array(
		'1' => '有効',
		'3' => '無効/管理者の無効化',
	);
	*/
	//
	var $user_state_type_arr=array(
		'1' => '有効',
		'0' => '無効',
	);
	var $user_seibetu_arr=array(
		'men' => '男性',
		'women' => '女性',
	);
	var $bosyu_koukai_arr=array(
		'1' => '募集中',
		'0' => '募集終了',	
	);
	/*
	var $bosyu_koukai_sc_arr=array(
		'1' => '募集中',
		'0' => '募集終了',	
	);
	*/
	//
	function aa($tpl, $tplfile){
	}




}

	
