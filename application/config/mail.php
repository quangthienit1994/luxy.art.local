<?php 

/**
 * Author: Quang Nguyen
 * Date: 03/02/2019
 * TODO: mail config
 */

$config["mail"] = [
    'host' => 'smtp.luxyart.conoha.io', //smtpサーバーのアドレス
    'port'=> 587 , //ポート番号
    'protocol' => 'SMTP_AUTH', //認証方法
    'user' => 'verification@luxy.art', //smtpサーバーのユーザー名
    'pass' => '1SMWVxF9ra', //smtpサーバーのパスワード
    'from' => 'verification@luxy.art',　//発信元メールアドレス
];
 