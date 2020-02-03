<!DOCTYPE html>

<html lang="ja">

<head>

	<title><?php echo $this->lang->line('site_title'); ?>管理機能</title>

	<meta charset="utf-8">

	<meta name="keywords" content="キーワード" />

	<meta name="description" content="説明文">

	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">

	<base href="<?=base_url();?>">

    <link rel="stylesheet" href="publics/luxyart/css/reset.css">

    <link rel="stylesheet" href="publics/luxyart/css/common.css">

    <link rel="stylesheet" href="publics/luxyart/css/pc.css"><!-- PC Layout CSS -->

    <link rel="stylesheet" href="publics/luxyart/css/colorbox.css"><!-- PC Layout CSS -->

    

	

	<meta name="mobile-web-app-capable" content="yes">		

	<link rel="canonical" href=""> 

	<link rel="apple-touch-icon-precomposed" href="publics/luxyart/img/common/ico.png">

	

	<meta http-equiv="Pragma" content="no-cache">

	<meta http-equiv="Cache-Control" content="no-cache">

	<meta http-equiv="Expires" content="0">

	<?php $this->load->view('admin/layouts/css.php') ?>

	

	<!--	 JavaScript Area Start	-->



	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

	<script>

		$(function() {

		  var h = $(window).height();

		 

		  $('#wrap').css('display','none');

		  $('#loader-bg ,#loader').height(h).css('display','block');

		});

		 

		$(window).load(function () { //全ての読み込みが完了したら実行

		  $('#loader-bg').delay(900).fadeOut(800);

		  $('#loader').delay(600).fadeOut(300);

		  $('#wrap').css('display', 'block');

		});

		 

		//10秒たったら強制的にロード画面を非表示

		$(function(){

		  setTimeout('stopload()',10000);

		});

		 

		function stopload(){

		  $('#wrap').css('display','block');

		  $('#loader-bg').delay(900).fadeOut(800);

		  $('#loader').delay(600).fadeOut(300);

		}

	</script>



	<!--	 JavaScript Area End	-->	

</head>

<body ng-app="app">

	<div id="loader-bg">

		<div id="loader">

			<img src="publics/luxyart/img/common/img-loading.gif" width="80" height="80" alt="Now Loading..." />

			<p>Now Loading...</p>

		</div>

	</div>







	<div id="wrap">

		<header class="main_head">

			<p><a href="admin"><img src="publics/luxyart/img/common/luxyart_logo.png" alt=""></a></p>

		<!-- 	<p>お知らせエリア</p> -->

		</header>

		<div id="main_content">

			<section class="left-menu">

				<nav class="main_nav">

					<ul>

						<?php $this->load->view('admin/layouts/menu.php') ?>

					</ul>

				</nav>

			</section>

			<section class="main u_cont">

				<h2 style="margin-top: 0px;" class="bd2"><?=$title?></h2>

				<?php echo $body;?>	

			</section>		

		</div>

		<footer class="main_foot">

			<p><?php echo $this->lang->line('copyright'); ?></p>

		</footer>

	</div>

	<?php

		$request_url = $_SERVER["REQUEST_URI"];

		if(!strpos($request_url, 'post_image') && !strpos($request_url, 'edit_image')) {

			$this->load->view('admin/layouts/script.php');

		}

	?>

</body>

</html>