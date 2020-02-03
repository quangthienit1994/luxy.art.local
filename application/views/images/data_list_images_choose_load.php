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
		  	foreach($list_images as $detail_list_images){?>

                    	<li class="mypage-img-list__list">

              <div class="mypage-img-list__wrapper">

                <div class="mypage-img-list__inner">

                <?php echo $detail_list_images->img_title;?><br />

                <a href="<?php echo base_url()?>detail/<?php echo $detail_list_images->img_code; ?>.html"><img src="<?php echo $detail_list_images->server_path_upload;?>/<?php echo $detail_list_images->file_name_watermark_img_1; ?>" alt="<?php echo $detail_list_images->img_title;?>"></a></div>

                <div class="mypage-img-list__info">
                
                	 <?php
					  
					  	$time_remaining = $this->manager_image_model->get_time_remaining($detail_list_images->id_img);
							
							if($time_remaining == -1){
								echo '<p class="applied">'.$this->lang->line('expired').'</p>';
							}
							else if($detail_list_images->img_is_sold == 1){
								echo '<p class="applied">'.$this->lang->line('out_of_stock').'</p>';
							}
					  
					  ?>
                

                      <!--<p><a href="<?php //echo base_url()?>mypage/edit-image/<?php //echo $detail_list_images->id_img;?>"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a></p>-->

                      <label class="checkbox">

                        <input type="checkbox" class="checked" name="check[]" value="<?php echo $detail_list_images->id_img;?>" >

                      </label>

                      <!--<div class="delete" onclick="delete_image(<?php //echo $detail_list_images->id_img;?>)"><span>×</span></div>-->

                    </div>

              </div>

            </li>

                    	

                    <?php }
?>