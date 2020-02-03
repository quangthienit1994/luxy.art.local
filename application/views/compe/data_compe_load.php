<?php
if(!isset($_SESSION)){
	session_start();
}
$CI =& get_instance();
$CI->config->load();
if(!isset($_SESSION['lang'])){
	$_SESSION['lang'] = 'ja';
}
if($_SESSION['lang'] == "ja"){
	$this->lang_id = 1;
}
else if($_SESSION['lang'] == "vi"){
	$this->lang_id = 2;
}
else if($_SESSION['lang'] == "en"){
	$this->lang_id = 3;
}
$config['language'] = $_SESSION['lang'];
$ngonngu = $config['language'];
$this->lang->load('dich', $ngonngu);
?>

<style>
.img-new {
    position: absolute;
	margin: 0 0 0 -50px;
    width: 50px !important;
    height: 29px !important;
}
</style>

<?php
		  	foreach($list_compe_other as $detail_compe_other){
				
				$user_fullname = $CI->function_model->get_fullname($detail_compe_other->user_id,$this->lang_id);
				$time_remain_competition = $CI->manager_compe_model->get_time_remain_competition($detail_compe_other->id_competition);
				
		  ?>
          
          	<li>
              <a href="<?php echo base_url(); ?>compe-detail/<?php echo $detail_compe_other->id_competition; ?>">
              <div class="compe-list__thumb">
              	<?php
					if($detail_compe_other->photo_des_com != ""){
				?>
              		<img alt="<?php echo $detail_compe_other->title_com; ?>" src="<?php echo base_url(); ?>publics/competition_img/<?php echo $detail_compe_other->photo_des_com; ?>">
                <?php
					}
					else{
				?>
                	<img alt="<?php echo $detail_compe_other->title_com; ?>" src="<?php echo base_url(); ?>publics/img/no_image_available.jpg">
                <?php	
					}
				?>
                <?php
					if($detail_compe_other->is_private == 1){
						if($this->lang_id == 1){
							echo '<img alt="ja" class="img-new" src="'.base_url().'publics/img/icon-private-ja.png" />';
						}
						else if($this->lang_id == 2){
							echo '<img alt="vi" class="img-new" src="'.base_url().'publics/img/icon-private-vi.png" />';
						}
						else if($this->lang_id == 3){
							echo '<img alt="en" class="img-new" src="'.base_url().'publics/img/icon-private-en.png" />';
						}
					}
				?>
              </div>
              <div class="compe-list__meta">
                <div class="compe-list__icon">
                	<img alt="<?php echo $detail_compe_other->display_name; ?>" src="<?php echo base_url(); ?>publics/avatar/<?php echo ($detail_compe_other->user_avatar != "") ? $detail_compe_other->user_avatar : "prof-img.png"; ?>">
               	</div>
                <p class="compe-list__ttl"><?php echo $detail_compe_other->title_com; ?></p>
                <p class="compe-list__name"><?php echo $user_fullname; ?></p>
              </div>
              <div class="compe-list__footer">
                <div class="compe-list__price"><span><?php echo $this->lang->line('prize'); ?>&nbsp;</span><?php echo $detail_compe_other->point_img_com*$detail_compe_other->img_quantity_com ?>&nbsp;<?php echo $this->lang->line('point'); ?></div>
                <div class="compe-list__limit">
                	<span><?php echo $this->lang->line('remaining'); ?>&nbsp;</span>
                    <?php echo $CI->manager_compe_model->get_time_remain_apply($detail_compe_other->date_end_com); ?>
              	</div>
              </div>
             </a>
         	</li>
              
          <?php
			}
?>