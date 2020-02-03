<?php
$CI =& get_instance();
$CI->config->load();
$this->lang->load('dich', 'ja');
?>

<!DOCTYPE html>
<html lang="ja">
	<head>
		<base href="<?=base_url('')?>">
		<title><?php echo $this->lang->line('title_login_admin'); ?></title>
		<meta charset="utf-8">
		<meta name="keywords" content="<?php echo $this->lang->line('keyword_login_admin'); ?>" />
		<meta name="description" content="<?php echo $this->lang->line('description_login_admin'); ?>">
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
				<p class="photos"><img src="<?=$get->server_path_upload.'/'.$get->file_name_original_img?>"  onerror="this.src='publics/product_img/image_default.png'"></p>
				<style type="text/css">
					.photo_control div{
						margin-bottom: 10px;
					}

				</style>
				<div class="photo_control">
					<?php /*
					<div>Owner: <b><a style="color: blue" href="admin/page_add_edit_user/<?=$get->user_id?>"><?=$get->user_firstname==''&&$get->user_lastname==''?$get->user_email:$get->user_firstname.' '.$get->user_lastname?></a></b></div>
					*/?>
					<?php 
					function reason_report($key) {
						switch ($key) {
							case 1:
								return $this->lang->line('possibility_of_violation');
								break;
							case 2:
								return $this->lang->line('this_is_other_ones_image');
								break;
							case 3:
								return $this->lang->line('violent_image');
								break;
							case 4:
								return $this->lang->line('porn_image');
								break;
							default:
								# code...
								break;
						}
					}
					?>
					<?php if($get->email!='') {?>
						<div><?php echo $this->lang->line('email_address'); ?>: <b><?=$get->email?></b></div>
					<?php } ?>
					<?php if($get->name!='') {?>
						<div><?php echo $this->lang->line('username'); ?>: <b><?=$get->name?></b></div>
					<?php } ?>

					<div><?php echo $this->lang->line('reason'); ?>: <b><?=reason_report($get->type)?></b></div>
					<div><?php echo $this->lang->line('reporting_date'); ?>: <b><?=$get->date_create?></b></div>
					<form method="post" action="admin/status_image/<?=$get->id_img_abc?>" onsubmit="return submitAjax(this)">
						<input type="hidden" name="status" value="2">
						<p class="phot_c1"><input type="submit" value="<?php echo $this->lang->line('hide_the_image'); ?>" onclick="setTimeout(function(){window.top.location.reload();},3000)"></p>
					</form>
					<form method="post" action="admin/status_report_image/<?=$get->id_img_abc?>" onsubmit="return submitAjax(this)">
						<input type="hidden" name="status" value="1">
						<p class="phot_c3"><input type="submit" value="<?php echo $this->lang->line('display_the_image_as_it_is'); ?>" onclick="setTimeout(function(){window.top.location.reload();},3000)"></p>
					</form>
				</div>
			</div>
	</div>
</body>
</html>