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
		  	foreach($list_image_apply as $detail_image_apply){
		  ?>
        <div class="pinto">
        <a href="<?php echo base_url()?>detail/<?php echo $detail_image_apply->img_code; ?>.html"><img alt="<?php echo $detail_image_apply->img_title;?>" src="<?php echo $detail_image_apply->server_path_upload;?>/<?php echo $detail_image_apply->file_name_watermark_img_1;?>"></a>
        </div>
        <?php }
?>
