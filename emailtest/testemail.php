<?php

echo 'Current PHP version: ' . phpversion();
 
if(isset($_REQUEST['submit'])) {
 
require_once("qdmail.php");
$date = date("Y/m/d H:i");
$message = <<<EOF
いまなにしてますかー？
ただいまは{$date}です。
EOF;
 
$mail = new Qdmail();
$mail -> smtp(true);
$param = array(
'host' => 'smtp.luxyart.conoha.io', //smtpサーバーのアドレス
'port'=> 587 , //ポート番号
'protocol' => 'SMTP_AUTH', //認証方法
'user' => 'verification@luxy.art', //smtpサーバーのユーザー名
'pass' => '1SMWVxF9ra', //smtpサーバーのパスワード
'from' => 'verification@luxy.art',　//発信元メールアドレス
);
$mail -> smtpServer($param);
$mail -> to('fermatandrew@gmail.com'); //宛先
$mail -> subject('PHPでメール送信してみます'); //タイトル
$mail -> text($message); //メッセージ本文
$mail -> attach('./testemail.php'); //添付ファイルつける
$return_flag = $mail ->send(); //送信
if($return_flag) {
$sys_msg = "メールを送信しました。";
} else {
$sys_msg = "メールの送信に失敗しました。";
}
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf8" />
<title>とにかくメールを送信するよ</title>
</head>
<body>
 
<h1>とにかくメールを送信します</h1>
 
<form aciton="testmail.php">
<button type="submit" name="submit">送信</button>
</form>
 
<?= $sys_msg ?>
 
</body>
</html>