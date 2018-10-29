<?php
/**
 * @calling : 概要、config設定
 * @purpose
 * @date
 * @argment
 * @return
 */
$mConfig= array();  //config設定
$mUser  = array();  //ログイン、ユーザーdata
$mRequest= array();  //HTTP-request

//config
$mConfig['sys_title'] = 'match-publish-simply';
$mConfig['host_name'] = 'http://localhost';
$mConfig['base_url'] = '/wpmatch';
$mConfig['temp_dir'] = '';     //template パス
$mConfig['mail_temp_dir'] = '';     //mail_template パス
// mail
$mConfig['mail_from_addr'] = 'norep@test.com';     //mail_from アドレス

//db
$mConfig['db_host'] = 'localhost';  //db host
$mConfig['db_name'] = 'db_name';     //db name
$mConfig['db_user'] = 'db_user';    //db user
$mConfig['db_pass'] = 'db_pass';   //db passwd

//
