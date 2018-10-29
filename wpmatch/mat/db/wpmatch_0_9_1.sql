
--
CREATE TABLE `mat_bosyu` (
	`id`         bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT primary key COMMENT 'ID',
	`state`      int(4) NOT NULL COMMENT '有効/無効',
	`koukai`     int(4) NOT NULL COMMENT '募集中/募集終了',
	`bosyu_num` int(4) NOT NULL COMMENT '募集人数',
	`bosyu_start` datetime default null COMMENT '募集開始/now関数',
	`bosyu_end_kbn`  varchar(20) NOT NULL COMMENT '募集終了日_区分(none, 3_day, 1_week, 1_month)',
	`bosyu_end`   datetime default null COMMENT '募集終了日',
	`keiyaku_span`      varchar(20) NOT NULL COMMENT '契約期間(none, 1_week, 1_month)',
	`title`            varchar(1024) NOT NULL COMMENT 'タイトル',
	`price_h`         int(12) NULL COMMENT '報酬金額_上限',
	`price_low`       int(12) NULL COMMENT '報酬金額_下限',
	`price`       int(12) NULL COMMENT '報酬金額',
	`price_kbn`       int(12) NULL COMMENT '報酬金額_区分',
	`naiyou`            text  NOT NULL COMMENT '募集内容',
	`user_id` bigint(20) NOT NULL COMMENT 'ユーザーID/募集者',
	`create_dt` datetime NOT NULL COMMENT '作成日時',
	`update_dt` datetime NOT NULL COMMENT '更新日時'	
)  DEFAULT CHARSET=utf8 COMMENT='募集情報';


--
CREATE TABLE `mat_bosyu_addtext` (
	`id`         bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT primary key COMMENT 'ID',
	`bosyu_id` bigint(20) NOT NULL COMMENT '募集ID',	
	`user_id` bigint(20) NOT NULL COMMENT 'ユーザーID/応募者',	
	`add_text`         text  NOT NULL COMMENT '応募テキスト',
	`create_dt` datetime NOT NULL COMMENT '作成日時',
	`update_dt` datetime NOT NULL COMMENT '更新日時'
)  DEFAULT CHARSET=utf8 COMMENT='応募情報';


--
CREATE TABLE `mat_bosyu_sentaku` (
	`id`         bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT primary key COMMENT 'ID',
	`bosyu_id` bigint(20) NOT NULL COMMENT '募集ID',
	`oubo_id` bigint(20) NOT NULL COMMENT '応募者ID',
	`sentaku_date`   datetime default null COMMENT '選択日時',
	`sentaku_text`   text  NOT NULL COMMENT '選定時_テキスト',
	`user_id` bigint(20) NOT NULL COMMENT 'ユーザーID/募集者',	
	`create_dt` datetime NOT NULL COMMENT '作成日時',
	`update_dt` datetime NOT NULL COMMENT '更新日時'
)  DEFAULT CHARSET=utf8 COMMENT='募集_当選者_情報';


--
CREATE TABLE `mat_message` (
	`id`         bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT primary key COMMENT 'ID',
	`bosyu_id` bigint(20) NOT NULL COMMENT '募集ID',	
	`user_id` bigint(20) NOT NULL COMMENT 'ユーザーID/応募者',	
	`send_id` bigint(20) NOT NULL COMMENT '送信者ID',
	`retmsg_id` bigint(20) NOT NULL COMMENT '返信元メッセージID',
	`hensin_text`         text  NOT NULL COMMENT '返信テキスト',
	`up_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新日時'
)  DEFAULT CHARSET=utf8 COMMENT='メッセージ情報';


--
CREATE TABLE `mat_msg` (
	`id`         bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT primary key COMMENT 'ID',
	`bosyu_id` bigint(20) NOT NULL COMMENT '募集ID',	
	`send_id` bigint(20) NOT NULL COMMENT 'ユーザーID/送信先',	
	`from_id` bigint(20) NOT NULL COMMENT 'ユーザーID/送信者',	
	`msg_text`         text  NOT NULL COMMENT '返信テキスト',
	`user_id` bigint(20) NOT NULL COMMENT '登録ユーザーID',	
	`create_dt` datetime NOT NULL COMMENT '作成日時',
	`update_dt` datetime NOT NULL COMMENT '更新日時'
)  DEFAULT CHARSET=utf8 COMMENT='メッセージ_受信情報';

--
CREATE TABLE `mat_oubo` (
	`id`         bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT primary key COMMENT 'ID',
	`bosyu_id` bigint(20) NOT NULL COMMENT '募集ID',	
	`user_id` bigint(20) NOT NULL COMMENT 'ユーザーID/応募者',	
	`oubo_text`         text  NOT NULL COMMENT '応募テキスト',
	`oubo_date`   datetime default null COMMENT '応募日時',
	`price`       int(12) NULL COMMENT '報酬金額',
	`create_dt` datetime NOT NULL COMMENT '作成日時',
	`update_dt` datetime NOT NULL COMMENT '更新日時'
)  DEFAULT CHARSET=utf8 COMMENT='応募情報';


--
CREATE TABLE `mat_sys_param` (
	`id`         bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT primary key COMMENT 'ID',
	`tesuu_price`       int(12) NOT NULL COMMENT 'システム手数料_比率',
	`tax`         int(12) NOT NULL COMMENT '消費税率',
	`mail_send`   int(1) NOT NULL DEFAULT 0 COMMENT 'メール自動送信_有効/無効',
	`user_id` bigint(20) NOT NULL COMMENT 'ユーザーID',
	`up_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新日時'
)  DEFAULT CHARSET=utf8 COMMENT='システム定数_情報';

--
INSERT INTO mat_sys_param (mail_send) VALUES(0);


--
CREATE TABLE `mat_users` (
  `id`         bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT primary key COMMENT 'User-ID',
  `fb_id`      bigint(20) UNSIGNED DEFAULT NULL COMMENT 'facebook-ID',
  `stat`       varchar(16) NOT NULL COMMENT 'ステータス 有効/無効',
  `utype`      varchar(20) NOT NULL COMMENT 'ユーザー種別(user,admin)',
  `seibetu`    varchar(24) NOT NULL COMMENT '性別 men/women',
  `sanka_type` varchar(20) NOT NULL COMMENT '参加-種別(bosyu,oubo)',
  `nickname`   varchar(128) NOT NULL COMMENT 'ニックネーム',
  `email`      varchar(128) NOT NULL COMMENT 'メールアドレス',
  `loginid`    varchar(128) DEFAULT NULL,
  `passwd` varchar(128) NOT NULL COMMENT 'パスワード',
  `tel_no` varchar(128) NOT NULL COMMENT '電話番号',
  `pref` varchar(4) NOT NULL COMMENT '都道府県コード',
  `addr_1` varchar(1024) NOT NULL COMMENT '住所＿１_市町村',
  `addr_2` varchar(1024) NOT NULL COMMENT '住所＿2',
  `birthday` datetime default null COMMENT '生年月日',
  `ac_ip` varchar(20) NOT NULL COMMENT 'アクセスIP',
  `sessid` varchar(256) NOT NULL COMMENT 'セッション',
  `ac_date` datetime NOT NULL COMMENT 'アクセス日時',
  `denycnt` tinyint(2) NOT NULL COMMENT 'ログイン失敗回数',
  `create_dt` datetime NOT NULL COMMENT '作成日時',
  `update_dt` datetime NOT NULL COMMENT '更新日時'
)  DEFAULT CHARSET=utf8 COMMENT='ユーザーログイン用情報';


--
INSERT INTO `mat_users` (`id`, `fb_id`, `stat`, `utype`, `email`, `loginid`, `passwd`, `ac_ip`, `sessid`, `ac_date`, `denycnt` ) VALUES
(1, NULL, '', 'admin', 'admin@test.com', NULL, '123', '', '', '0000-00-00 00:00:00', 0 );









