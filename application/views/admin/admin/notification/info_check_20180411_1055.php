<!DOCTYPE html>
<html lang="ja">
	<head>
		<title>Luxyart管理機能</title>
		<meta charset="utf-8">
		<meta name="keywords" content="キーワード" />
		<meta name="description" content="説明文">
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
		<link rel="stylesheet" href="css/reset.css">
		<link rel="stylesheet" href="css/info_check.css">
		
		<!--&#91;if lt IE 9&#93;>
		<script src="//cdn.jsdelivr.net/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="//cdnjs.cloudflare.com/ajax/libs/respond.js/1.4.2/respond.min.js"></script>
		<!&#91;endif&#93;-->
		<meta name="mobile-web-app-capable" content="yes">
		<link rel="canonical" href=""> 
		<link rel="apple-touch-icon-precomposed" href="img/common/ico.png"> 

		<!--   JavaScript Area Start  -->
		<!--   JavaScript Area End  -->	
	</head>
<body>
	<section id="info_check" style="font-size: 13px;">
		<p style="font-weight: bold;">お知らせタイトル</p>
		<p><?=$get->title?></p>
		<p style="font-weight: bold;">お知らせ本文</p>
		<p><?=$get->content?></p>
		<p style="font-weight: bold;">公開日</p>
		<p><?=date('Y年m月d日',strtotime($get->date))?></p>
		
	</section>
</body>
</html>