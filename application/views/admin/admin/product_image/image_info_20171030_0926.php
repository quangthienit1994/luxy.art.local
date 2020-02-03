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
<body>
	<div id="photo_area">
		<div class="photo_c">
			<p class="photos"><img src="<?=$get->server_path_upload.'/'.$get->file_name_original_img?>"  onerror="this.src='publics/product_img/image_default.png'"></p>
		</div>
		<div class="photo_control">
			<p>登録カテゴリー</p>
					<p>
						<?php
						$this->db->from('luxyart_tb_img_category as ic');
						$this->db->join('luxyart_tb_product_category as c','ic.root_cate=c.cate_id or ic.parent_cate=c.cate_id','LEFT');
						$this->db->where('ic.id_img', $get->id_img);
						$category = $this->db->get()->result();
						foreach ($category as $key => $value) {
							echo $value->cate_name.', ';
						}
						?>
					</p>
			<p>Comment</p>
				<p><?=$get->img_title?></p>
			<p>登録ユーザー名</p>
				<p><a href="admin/page_add_edit_user/<?=$get->user_id?>"><?=$get->user_firstname==''&&$get->user_lastname==''?$get->user_email:$get->user_firstname.' '.$get->user_lastname?></a></p>
			<p>会員グレード</p>
				<p><?php switch ($get->user_level) {
                  case 1:
                    echo "Regular";
                    break;
                  case 2:
                    echo "Creator";
                    break;
                  case 3:
                    echo "Gold";
                    break;
                  case 4:
                    echo "Platinum";
                    break;
                  case 5:
                    echo "Enterprise";
                    break;
                  
                  default:
                    # code...
                    break;
                } ?></p>
		</div>
	</div>
</body>
</html>