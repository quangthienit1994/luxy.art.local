<?php
if(!isset($_SESSION)){
	session_start();
}
$CI =& get_instance();
$CI->config->load();
if(!isset($_SESSION['lang'])){
	$_SESSION['lang'] = 'ja';
}
$config['language'] = $_SESSION['lang'];
$ngonngu = $config['language'];
$this->lang->load('dich', $ngonngu);
?>

<?php

	/*foreach($list_images as $detail_list_images){
    	echo $detail_list_images->id_img."<br>";
    }*/

?>

<?php
		  	foreach($list_images as $detail_list_images){?>
            
            

        <li class="mypage-img-list__list" id="li_<?php echo $detail_list_images->id_img;?>">

              <div class="mypage-img-list__wrapper">
            	<div class="mypage-img-list__inner">
                	<a href="<?php echo base_url()?>detail/<?php echo $detail_list_images->img_code; ?>.html"><img src="<?php echo $detail_list_images->server_path_upload;?>/<?php echo $detail_list_images->file_name_watermark_img_1; ?>" alt="<?php echo $detail_list_images->img_title;?>"></a>
              	</div>
                <div class="mypage-img-list__info">
                <?php
					if($user_id == $detail_list_images->user_id){//hinh anh cua ban	
						if($detail_list_images->img_is_join_compe == 0){//chua applied
							$time_remaining = $this->manager_image_model->get_time_remaining($detail_list_images->id_img);
							if($time_remaining == -1){//het han
								$str = $this->lang->line('expired');
							}
							else if($detail_list_images->img_is_sold == 1){//khong con san pham de ban
								$str = $this->lang->line('out_of_stock');
							}
							else{
								$str = "";
							}
				?>
                    <p class="applied applied-left"><?php echo $str; ?></p><br>
                    <label class="checkbox">
                    	<input type="checkbox" class="checked" name="check[]" value="<?php echo $detail_list_images->id_img;?>" >
                    </label>
                    <div class="delete" onclick="delete_image(<?php echo $detail_list_images->id_img;?>)"><span>x</span></div>
                    <div class="delete applied-delete">
                    	<span><a href="<?php echo base_url()?>mypage/edit-image/<?php echo $detail_list_images->id_img;?>"><i class="fa fa-pencil" aria-hidden="true"></i>
</a></span>
                    </div>
                <?php
						}
						else{
							$str = $this->lang->line('applied');
				?>
                			<p class="applied"><?php echo $this->lang->line('applied'); ?></p><br>
                            <!--<label class="checkbox">
                            	<input type="checkbox" class="checked" name="check[]" value="" style="" >
                            </label>-->
                            <!--<div class="delete" onclick="remove_image_sell(264, 2)"><span>x</span></div>-->
                            <!--<div class="delete applied-delete">
                            	<span><i class="fas fa-pencil-alt" style="color:#000"></i></span>
                            </div>-->
                <?php
						}
					}
					else{//hinh anh cua nguoi khac => co the reove get
							$str = $this->lang->line('image_got');
				?>
                	<p class="applied applied-left"><?php echo $this->lang->line('image_got'); ?></p><br>
                    <!--<label class="checkbox">
                    	<input type="checkbox" class="checked" name="check[]" value="" style="" >
                    </label>-->
                    <div class="delete">
                    	<span onclick="remove_image_sell(<?php echo $detail_list_images->id_img;?>, <?php echo $user_id; ?>)">x</span>
                  	</div>
                    <!--<div class="delete applied-delete">
                    	<span><i class="fas fa-pencil-alt" style="color:#000"></i></span>
                    </div>-->
                <?php
					}
				?>
                
                <?php
					$is_mobile = $CI->function_model->isMobile();
					if($is_mobile == 1){
				?>
				<p class="show-mobile" style="margin:-7px 0px 0px 10px;display:block;position:absolute;color:red;"><?php echo $str; ?></p>
				<?php
					}
				?>
                
                </div>
          	</div>

            </li>
        
        <?php
		 }
?>