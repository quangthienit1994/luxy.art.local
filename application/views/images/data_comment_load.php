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

<link href="http://dev.luxy.art/publics/highlighttextarea/jquery.highlighttextarea.css" rel="stylesheet">
  
<script>

var strWordList = "<?=$strWordList;?>";
var arrWordList = strWordList.split(",");

function appendScript(src, callback) {
  var head = document.getElementsByTagName('head')[0];

  if (src.endsWith(".js")) {
	var elt = document.createElement("script");
	elt.type = "text/javascript";
	elt.src = src;
  } else {
	var elt = document.createElement("link");
	elt.rel = "stylesheet";
	elt.link = src;
  }

  elt.onload = function() {
	callback();
  }

  head.appendChild(elt);
}

function runTest() {
  $('.highlight').highlightTextarea({
	  words: {
		color: '#FFFF00',
		words: arrWordList
	  },
	  debug: true
  });
}

function test() {
	
  var jqv = "jquery-1.11.1.min.js";

  try {
	jqv = window.location.search.split('?')[1].split('=')[1];
  } catch (e) {}

  appendScript("http://code.jquery.com/" + jqv,
	function() {
	  $('#jqv').val(jqv);

	  appendScript("http://code.jquery.com/ui/1.10.4/jquery-ui.min.js",
	  function() {
		appendScript("http://dev.luxy.art/publics/highlighttextarea/jquery.highlighttextarea.js",
		runTest
	  )})});
}

test();

</script>
      				
<?php
	if(count($list_comment) > 0){
		foreach($list_comment as $detail_comment){
?>

		<div class="comment_item width_common">
			<p class="full_content"><?php echo $detail_comment->comment; ?></p>
			<div class="user_status width_common" data-user-type="8">
				<a class="avata_coment" href="<?php echo base_url(); ?>timeline/<?php echo $detail_comment->user_id; ?>.html">
					<img class="img_avatar" src="<?php echo base_url(); ?>publics/avatar/<?php echo ($detail_comment->user_avatar!="") ? $detail_comment->user_avatar : "prof-img.png" ?>">
				</a>
				<span class="left txt_666 txt_14">
					<a class="nickname txt_666" href="<?php echo base_url(); ?>timeline/<?php echo $detail_comment->user_id; ?>.html" title="Xem trang ý kiến của <?php echo $detail_comment->display_name; ?>" target="_blank"><b><?php echo $detail_comment->display_name; ?></b></a> - <?php echo date('Y-m-d H:i', strtotime($detail_comment->date_create)); ?>
				</span>
				<p class="txt_666 right block_like_web">
					<a onclick="open_box_answer_root(<?php echo $detail_comment->comment_id; ?>);" class="share_cmt_fb txt_blue txt_11"><?php echo $this->lang->line('reply'); ?> <i class="ic ic-caret-down"></i></a>
			   </p>
				<div id="box-comment-answer-root-<?php echo $detail_comment->comment_id; ?>" class="box-comment box-comment-answer">
					<div class="pad-bot-5">
						<textarea class="highlight" id="comment_root_<?php echo $detail_comment->comment_id; ?>" name="comment_root_<?php echo $detail_comment->comment_id; ?>" rows="3" placholder="<?php echo $this->lang->line('your_comments'); ?>"></textarea>
                       	<div id="comment_root_<?php echo $detail_comment->comment_id; ?>_msg" class="warning" style="display: none;"></div>
					</div>
					<div class="btn-send pad-bot-5">
						<input onclick="send_root(<?php echo $detail_comment->comment_id; ?>)" type="button" class="btn_send_root" name="btn_send_root" value="<?php echo $this->lang->line('send'); ?>" />&nbsp;
						<input onclick="close_box_answer_root(<?php echo $detail_comment->comment_id; ?>)" type="button" class="btn_cancel" name="btn_cancel" value="<?php echo $this->lang->line('cancel'); ?>" />
					</div>
				</div>
			</div>
			
			<?php
				$list_comment_sub = $CI->manager_comment_model->get_list_comment_by_level_sub($detail_comment->comment_id)->result();
				if(count($list_comment_sub) > 0){
					foreach($list_comment_sub as $detail_comment_sub){						
			?>
			
			<div class="div-sub-comment">
				<div class="div-sub-comment-left">&nbsp;</div>
				<div class="div-sub-comment-right">
					<div class="sub_comment width_common clearfix">
						<div class="sub_comment_item width_common">
							<p class="full_content"><?php echo $detail_comment_sub->comment; ?></p>
							<div class="user_status width_common" data-user-type="0">
								<a class="avata_coment" href="<?php echo base_url(); ?>timeline/<?php echo $detail_comment_sub->user_id; ?>.html"></a>
								<span class="left txt_666 txt_14"><a href="<?php echo base_url(); ?>timeline/<?php echo $detail_comment_sub->user_id; ?>.html"><img src="<?php echo base_url(); ?>publics/avatar/<?php echo ($detail_comment_sub->user_avatar!="") ? $detail_comment_sub->user_avatar : "prof-img.png" ?>" alt="Đang tải ảnh đại diện"></a><a class="nickname txt_666" href="<?php echo base_url(); ?>timeline/<?php echo $detail_comment_sub->user_id; ?>.html" title="Xem trang ý kiến của <?php echo $detail_comment_sub->display_name; ?>" target="_blank">&nbsp;&nbsp;&nbsp;<b><?php echo $detail_comment_sub->display_name; ?></b></a> - <?php echo date('Y-m-d H:i', strtotime($detail_comment_sub->date_create)); ?></span>
								<p class="txt_666 right block_like_web">
									<a onclick="open_box_answer_sub(<?php echo $detail_comment_sub->comment_id; ?>);" class="share_cmt_fb txt_blue txt_11"><?php echo $this->lang->line('reply'); ?> <i class="ic ic-caret-down"></i></a>
							   </p>
							</div>
							<div id="box-comment-answer-sub-<?php echo $detail_comment_sub->comment_id; ?>" class="box-comment box-comment-answer">
								<div class="pad-bot-5">
									<textarea class="highlight" id="comment_sub_<?php echo $detail_comment_sub->comment_id; ?>" name="comment_sub_<?php echo $detail_comment_sub->comment_id; ?>" rows="3" placholder="<?php echo $this->lang->line('your_comments'); ?>"></textarea>
                                  	<div id="comment_sub_<?php echo $detail_comment_sub->comment_id; ?>_msg" class="warning" style="display: none;"></div>
								</div>
								<div class="btn-send pad-bot-5">
									<input onclick="send_sub(<?php echo $detail_comment_sub->comment_id; ?>)" type="button" class="btn_send_sub" name="btn_send_sub" value="<?php echo $this->lang->line('send'); ?>" />&nbsp;
									<input onclick="close_box_answer_sub(<?php echo $detail_comment_sub->comment_id; ?>);" type="button" class="btn_cancel" name="btn_cancel" value="<?php echo $this->lang->line('cancel'); ?>" />
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			
			<?php
					}
			?>
			
            <?php
				 if($count_comment_sub > 3){
			?>	
                <div class="txt_view_more width_common">
                    <a href="#" class="txt_blue view_all_reply"><?php echo $this->lang->line('view_all_comment'); ?> <?php echo count($list_comment_sub); ?> <?php echo $this->lang->line('reply'); ?></a>
                </div>
            <?php
				 }
			?>
				
			<?php
				}
			?>
			
		</div>

<?php

		}
?>
		
		<div class="clearfix"></div>
		<!--<div class="view-more-comment"><strong><a><?php //echo $this->lang->line('view_more_comment'); ?></a></strong></div>-->
		
<?php
	}
	else{
?>
		
		<div class="clearfix"></div>
		<!--<div class="view-more-comment"><strong><?php //echo $this->lang->line('no_comments_yet'); ?></strong></div>-->
		
<?php
	}
?>

<!--<div class="box-comment box-comment-answer-main">
	<div class="pad-bot-5">
		<textarea rows="3" placeholder="<?php //echo $this->lang->line('your_comments'); ?>"></textarea>
	</div>
	<div class="btn-send">
		<input type="button" class="btn_send" name="btn_send" value="<?php //echo $this->lang->line('send'); ?>" />
	</div>
</div>-->