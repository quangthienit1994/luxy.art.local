<?php
$CI =& get_instance();
$CI->config->load();
$this->lang->load('dich', 'ja');
?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

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
		<link rel="stylesheet" href="publics/luxyart/css/photoi.css">

		
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
    
    <style>
    .p input[type="button"] {
		width: 150px;
		padding: 0 10px 0 10px;
		background-color: #009944;
		color: #fff;
		line-height: 2.5em;
	}
	#cboxMiddleRight{
		height: 300px !important;
	}
	.photos{
		height: 200px !important;
	}
    </style>
    
    <script>
	
	var id_img = "<?php echo $get->id_img ?>";
	
	function detail_image(){
		
		window.open('<?php echo base_url(); ?>admin/images/edit_image/'+id_img,'_blank');
			
	}
	
	</script>
    
<body>
	<div id="photo_area">
		<div class="photo_c">
			<p class="photos"><img src="<?=$get->server_path_upload.'/'.$get->file_name_original_img?>"  onerror="this.src='publics/product_img/image_default.png'"></p>
		</div>
		<div class="photo_control">
			<p><?php echo $this->lang->line('category'); ?></p>
					<p>
						<?php
						$this->db->from('luxyart_tb_img_category as ic');
						$this->db->join('luxyart_tb_product_category as c','ic.root_cate=c.cate_id or ic.parent_cate=c.cate_id','LEFT');
						$this->db->where('ic.id_img', $get->id_img);
						$category = $this->db->get()->result();

						foreach ($category as $key => $value) {
							if($key == 0){
								echo $value->cate_name;
								continue;
							}
							echo ', '.$value->cate_name;
						}
						?>
					</p>
			<p><?php echo $this->lang->line('title'); ?></p>
				<p><?=$get->img_title?></p>
			<p><?php echo $this->lang->line('username'); ?></p>
				<p><a href="admin/page_add_edit_user/<?=$get->user_id?>"><?=$get->user_firstname==''&&$get->user_lastname==''?$get->user_email:$get->user_firstname.' '.$get->user_lastname?></a></p>
			<p><?php echo $this->lang->line('member_grade'); ?></p>
				<p><?php switch ($get->user_level) {
                  case 1:
                    echo $this->lang->line('regular');
                    break;
                  case 2:
                    echo $this->lang->line('creator');
                    break;
                  case 3:
                    echo $this->lang->line('gold');
                    break;
                  case 4:
                    echo $this->lang->line('platinum');
                    break;
                  case 5:
                    echo $this->lang->line('enterprise');
                    break;
                  
                  default:
                    # code...
                    break;
                } ?></p>
            <p><a onClick="detail_image();"><input type="button" name="btn_edit" value="<?php echo $this->lang->line('edit'); ?>" /></a></p>
		</div>
	</div>
</body>
</html>
