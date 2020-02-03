<?php
$CI =& get_instance();
$CI->config->load();
$this->lang->load('dich', 'ja');
?>

<!DOCTYPE html>
<html lang="ja">
	<head>
		<title><?php echo $this->lang->line('title_login_admin'); ?></title>
		<meta charset="utf-8">
		<meta name="keywords" content="<?php echo $this->lang->line('keyword_login_admin'); ?>" />
		<meta name="description" content="<?php echo $this->lang->line('description_login_admin'); ?>">
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
		<p style="font-weight: bold;"><?php echo $this->lang->line('notice_title'); ?></p>
		<p><?=$get->title?></p>
		<p style="font-weight: bold;"><?php echo $this->lang->line('notice_body'); ?></p>
		<p><?=$get->content?></p>
		<p style="font-weight: bold;"><?php echo $this->lang->line('release_date_time'); ?></p>
		<p><?=date('Y'.$this->lang->line('_year').'m'.$this->lang->line('_month').'d'.$this->lang->line('_day'),strtotime($get->date))?></p>
		
	</section>
</body>
</html>