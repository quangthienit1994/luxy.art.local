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

<style>
.g-nav__btn input[type="button"] {
    background: rgba(0, 0, 0, 0) linear-gradient(to bottom, #00d2b9, #00c6ad) repeat scroll 0 0;
    border-radius: 3px;
    color: #fff;
    font-size: 14px;
    height: 40px;
    line-height: 40px;
    text-align: center;
    width: 100%;
}
.warning{
	color:#F00;
}
.red{
	color:#F00;
}
.btn-view-more{
   width: 150px !important;
}
.mypage-img-list__inner{
 	height: 85% !important;
}
.mypage-img-list__info .delete{
	top: 4px !important;
}
@media (max-width: 768px)
{	
	.mypage-img-list__info .delete {top: 0px !important;}
}
</style>

<script>
$(document).ready(function () {
	
    $("#checkall").click(function () {
        $('#favorite-all-select input[type="checkbox"]').prop('checked', this.checked);
		$('.mypage-img-list__info input[type="checkbox"]').prop('checked', this.checked);
    });
	
	$("#add_to_cart").click(function () {
		
		var msg = "";
		var flag = 1;
		
		var id_bookmark = [];
		$('div.mypage-img-list input[type=checkbox]').each(function() {
		   if ($(this).is(":checked")) {
			   id_bookmark.push($(this).attr('value'));
		   }
		});
		
		if(id_bookmark.length > 0){
			
			var result = confirm("<?php echo $this->lang->line('pop_up_add_image_to_cart'); ?>");
			if(result){
				
				$.ajax({
					type: "POST",cache: false,
					url: "<?php echo base_url(); ?>shopping/add_cart_all",
					data: "id_bookmark="+id_bookmark,
					success: function(data) {
						if(data >= 1){
							msg = "<?php echo $this->lang->line('you_already_add_num_image_to_cart'); ?>";
							msg = msg.replace("{num}", parseInt(data));
							alert(msg);
							window.location.href = "<?php echo base_url(); ?>cart.html";
						}
						else{
							show_msg("type_size","<?php echo $this->lang->line('notice_image_already_buy_before'); ?>",1);
						}
					},
					async: false
				})
				
			}
				
		}
		else{
			show_msg("type_size","<?php echo $this->lang->line('notice_need_to_select_image'); ?>",1);
			flag=0;
		}
		
	});
	
	$("#delete_bookmark").click(function () {
		var selected = [];
		$('div#mypage-img-list input[type=checkbox]').each(function() {
			   if ($(this).is(":checked")) {
				   selected.push($(this).attr('value'));
			   }
		});
		if(selected!="")
			{
				var confirmText = "<?php echo $this->lang->line('pop_up_are_you_want_delete_bookmark'); ?>";
				if(confirm(confirmText)) {
					$.ajax({
					type: "POST",cache: false,
					url: '<?php echo base_url(); ?>user/do_ajax_delete_bookmark_img',
					data: "id_delete="+selected,
					async:false,
					success: function(data) {
										var msg=data.split("{return_delete_bookmark_img}");
										if(msg[1]=="success")
					                    {
											$('#msg_notice_msg').css('display','block');
											$('#msg_notice_msg').text("<?php echo $this->lang->line('notice_delete_bookmark_img_in_user_page_ok'); ?>");
											setTimeout(function(){ location.reload(); }, 7000);
					                    }
					                    else if(msg[1]=="failed")
					                    {
											 $('#msg_notice_msg').css('display','block');
											 $('#msg_notice_msg').text("<?php echo $this->lang->line('notice_problem_with_delete_bookmark_image'); ?>");
					                    }
						}
				   });
				}
			}
				
    });
});
function show_msg(id,msg,show_flag){
	
	if(show_flag == 1){
		$("#"+id+"_msg").show();
		$("#"+id+"_msg").html("<br>"+msg);
	}
	else{
		$("#"+id+"_msg").hide();
	   	$("#"+id+"_msg").html('');
	}
	
}
function remove_bookmark(id)
{
	var selected = [];
	selected.push(id);
	if(selected!="")
			{
				var confirmText = "<?php echo $this->lang->line('pop_up_are_you_want_delete_bookmark'); ?>";
				if(confirm(confirmText)) {
					$.ajax({
					type: "POST",cache: false,
					url: '<?php echo base_url(); ?>user/do_ajax_delete_bookmark_img',
					data: "id_delete="+selected,
					async:false,
					success: function(data) {
										var msg=data.split("{return_delete_bookmark_img}");
										if(msg[1]=="success")
					                    {
											$('#msg_notice_msg').css('display','block');
											$('#msg_notice_msg').text("<?php echo $this->lang->line('notice_delete_bookmark_img_in_user_page_ok'); ?>");
											setTimeout(function(){ location.reload(); }, 7000);
					                    }
					                    else if(msg[1]=="failed")
					                    {
											 $('#msg_notice_msg').css('display','block');
											 $('#msg_notice_msg').text("<?php echo $this->lang->line('notice_problem_with_delete_bookmark_image'); ?>");
					                    }
						}
				   });
				}
			}
			  
}
</script>
<div class="contents-mypage">
    <div class="contents-mypage__wrapper clearfix">
      <div class="breadly">
        <ul>
          <li><a href="<?php echo base_url()?>"><span itemprop="title"><?php echo $this->lang->line('home'); ?></span></a></li>
          <li><a href="<?php echo base_url()?>mypage.html"><span itemprop="title">><?php echo $this->lang->line('mypage'); ?></span></a></li>
          <li><span itemprop="title">><?php echo $this->lang->line('mypage_favorite'); ?></span></li>
        </ul>
      </div>
      <?php $this->load->view('user/sidebar_user.php') ?>
      
      <div class="mypage-main">
      <h1 class="contents-mypage__find"><?php echo $this->lang->line('mypage_favorite'); ?></h1>
      
      		<?php
					if(!empty($p_favorite_image))
					{?>
                    	<div class="mypage-favorite-menu">
              <div class="mypage-favorite-menu__select">
                <label id="favorite-all-select">
                  <input id="checkall" type="checkbox"><?php echo $this->lang->line('select_all'); ?>
                </label>
              </div>
              <div class="mypage-favorite-menu__delete" id="delete_bookmark"><span><!--<?php //echo $this->lang->line('collect'); ?><br />--><?php echo $this->lang->line('del'); ?></span></div>
              <div class="mypage-favorite-menu__cart" id="add_to_cart"><span><!--<?php //echo $this->lang->line('collect'); ?><br />--><?php echo $this->lang->line('add_cart'); ?></span></div>
            </div>
            <div id="msg_notice_msg" style="display:none"></div>
            <span id="type_size_msg" class="warning" style="display: none;"></span>
                        <div id="mypage-img-list" class="mypage-img-list">
                            <ul>
                    <?php foreach($p_favorite_image as $show_p_favorite_image){?>
                    	<li class="mypage-img-list__list">
              <div class="mypage-img-list__wrapper">
                <div class="mypage-img-list__inner"><a href="<?php echo base_url()?>detail/<?php echo $show_p_favorite_image->img_code;?>.html"><img src="<?php echo $show_p_favorite_image->server_path_upload;?>/<?php echo $show_p_favorite_image->file_name_watermark_img_1;?>" alt="<?php echo $show_p_favorite_image->img_title;?>"></a></div>
                <div class="mypage-img-list__info">
                      <p><?php echo $show_p_favorite_image->img_title;?></p>
                      <label class="checkbox">
                        <input type="checkbox" name="bookmark[<?php echo $show_p_favorite_image->id_bookmark;?>]" value="<?php echo $show_p_favorite_image->id_bookmark;?>">
                      </label>
                      <div class="delete" onclick="remove_bookmark(<?php echo $show_p_favorite_image->id_bookmark;?>)"><span>×</span></div>
                    </div>
              </div>
            </li>
                    	
                    <?php }?>
					</ul></div><?php echo $phantrang;?><?php } else {?>
                    <?php echo $this->lang->line('mypage_show_no_bookmark_image'); ?>
                    <?php }?>
          </div>
      
    </div>
  </div>