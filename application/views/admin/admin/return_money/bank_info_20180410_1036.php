<!DOCTYPE html>
<html lang="ja">
	<head>
		<base href="<?=base_url('')?>">
		<title>Luxyart管理機能</title>
		<meta charset="utf-8">
		<meta name="keywords" content="キーワード" />
		<meta name="description" content="説明文">
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
		<link rel="stylesheet" href="publics/luxyart/css/reset.css">
		<link rel="stylesheet" href="publics/luxyart/css/photoc.css">

		
		<!--&#91;if lt IE 9&#93;>
		<script src="//cdn.jsdelivr.net/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="//cdnjs.cloudflare.com/ajax/libs/respond.js/1.4.2/respond.min.js"></script>
		<!&#91;endif&#93;-->
		<meta name="mobile-web-app-capable" content="yes">
		<link rel="canonical" href=""> 
		<link rel="apple-touch-icon-precomposed" href="img/common/ico.png"> 

		<!--   JavaScript Area Start  -->
		<!--   JavaScript Area End  -->	
		<?php $this->load->view('admin/layouts/css.php') ?>
		<?php $this->load->view('admin/layouts/script.php'); ?>
	</head>
    
    
<body>
	<script type="text/javascript">
		function report(){
			alert('kkkk');
		}
	</script>
	<div id="photo_area">
		
			<div class="photo_c">
				<p class="photos"><img src="publics/avatar/<?=$get->user_avatar?>"  onerror="this.src='publics/product_img/image_default.png'"></p>
				<style type="text/css">
					.photo_control div{
						margin-bottom: 10px;
					}

				</style>
				<div class="photo_control">
					<div>ユーザー名: <b><a target="_blank" style="color: blue" href="admin/page_add_edit_user/<?=$get->user_id?>"><?=$get->user_firstname==''&&$get->user_lastname==''?$get->user_email:$get->user_firstname.' '.$get->user_lastname?></a></b></div>
					<div>メールアドレス: <b><?=$get->user_email?></b></div>
					<div>銀行名: <b><?=$get->bank_name?></b></div>
					<div>支店名: <b><?=$get->bank_branch_name?></b></div>
					<div>口座番号: <b><?=$get->bank_account?></b></div>
					<div>口座名義: <b><?=$get->bank_account_name?></b></div>
					<div>口座種別: <b><?=$get->bank_type_account ==2?"当座":"普通"?></b></div>
				</div>
			</div>
	</div>
</body>
</html>