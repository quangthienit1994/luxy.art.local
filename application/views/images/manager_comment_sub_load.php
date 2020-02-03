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

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>

<script>
$(document).ready(function(){
	
	//process comment
	//hide comment
	$(".box-comment-answer").hide();
	
});
</script>
      				
<?php
	//if(count($list_comment) > 0){
		foreach($list_comment_sub as $detail_comment_sub){
?>

		<!--<div class="div-sub-comment">-->
            <div class="div-sub-comment-left">&nbsp;</div>
            <div class="div-sub-comment-right">
                <div class="sub_comment width_common clearfix">
                    <div class="sub_comment_item width_common">
                        <p class="full_content"><?php echo $detail_comment_sub->comment; ?></p>
                        <div class="user_status width_common" data-user-type="0">
                            <span class="left txt_666 txt_14"><a class="avata_coment" href="<?php echo base_url(); ?>timeline/<?php echo $detail_comment_sub->user_id; ?>.html"><img src="<?php echo base_url(); ?>publics/avatar/<?php echo ($detail_comment_sub->user_avatar!="") ? $detail_comment_sub->user_avatar : "prof-img.png" ?>" alt="Đang tải ảnh đại diện"></a><a class="nickname txt_666" href="<?php echo base_url(); ?>timeline/<?php echo $detail_comment_sub->user_id; ?>.html" title="Xem trang ý kiến của <?php echo $detail_comment_sub->display_name; ?>" target="_blank"><b><?php echo $detail_comment_sub->display_name; ?></b></a> - <?php echo date('Y-m-d H:i', strtotime($detail_comment_sub->date_create)); ?></span>
                            <p class="txt_666 right block_like_web">
                                <?php
									if($detail_comment_sub->status == 1){
										echo '<span id="status_'.$detail_comment_sub->comment_id.'"><a onclick="set_status('.$detail_comment_sub->comment_id.',2,1)">'.$this->lang->line('active').'</a></span>';
									}
									else{
										 echo '<span id="status_'.$detail_comment_sub->comment_id.'"><a onclick="set_status('.$detail_comment_sub->comment_id.',2,0)">'.$this->lang->line('inactive').'</a></span>';
									}
								?>&nbsp;
                                <i class="ic ic-caret-down"></i>
                           </p>
                        </div>
                    </div>
                </div>
            </div>
        <!--</div>-->
		
<?php
		}
	//}
?>