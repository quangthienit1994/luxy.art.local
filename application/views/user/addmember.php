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
.list-pagenav {background-color:#fff!important;}
@media (max-width: 768px)
{
	.mypage-purchase__list .method .notice_admin {float:right!important;margin-top:-50px!important}
}
.add-member{
	float:right;
	width: 106px;
	height: 26px;text-align: center;
	line-height: 26px;color: #FFF !important;
	font-size: 13px;
	display: block;
	background: #00cbb4;
	border-radius: 3px;
}
btn-invited-member{
	float:left;
	width: 106px;
	height: 26px;text-align: center;
	line-height: 26px;color: #FFF !important;
	font-size: 13px;
	display: block;
	background: #00cbb4;
	border-radius: 3px;
}
.mypage-form__btn input[type="button"]:hover {
    background: #00cbb4;
}
.mypage-form__btn input[type="button"] {
    width: inherit;
    height: inherit;
    color: #fff;
    font-size: 15px;
    font-weight: bold;
    background: gradient(linear, left top, left bottom, from(#00d2b9), to(#00c6ad));
    background: -webkit-gradient(linear, left top, left bottom, from(#00d2b9), to(#00c6ad));
    background: linear-gradient(to bottom, #00d2b9, #00c6ad);
    border-radius: 3px;
    text-align: center;
    cursor: pointer;
}
</style>

<script type="text/javascript" src="<?php echo base_url(); ?>publics/multiselect/js/jquery.js"></script>

<link rel="stylesheet" href="<?php echo base_url(); ?>publics/css/multiple-emails.css">
<script type="text/javascript" src="<?php echo base_url(); ?>publics/js/multiple-emails-check-user-exits.js"></script>

<script>

var user_id = "<?php echo $user_id; ?>";

$(document).ready(function(){
	
	$("#btnInviteMembers").click(function(){
		return validateInviteMembers();
	});
	
});

function validateInviteMembers(){
	
	var msg = "";
	var flag = 1;
	var is_focus = 1;
	
	var list_email = $("#list_email").val();
	if(list_email == "" || list_email == "[]"){
		msg = "<?php echo $this->lang->line('please_input_email_participant'); ?>";
		show_msg("list_email",msg,1);
		flag = 0;
		if(is_focus == 1){
			set_focus("list_email");
			is_focus = 0;
		}
	}
	else{
		show_msg("list_email","",0);
	}
	
	if(flag == 1){
		
		$('#btnInviteMembers').attr('disabled','disabled');
		$('#btnInviteMembers').text("<?php echo $this->lang->line('processing'); ?>");
		
		$.ajax({
			type: "POST",cache: false,
			url: "<?php echo base_url(); ?>ajax/add_member",
			data: "list_email="+list_email+"&userid="+user_id,
			success: function(data) {
				if(data == 0){
					alert("<?php echo $this->lang->line('error_invite'); ?>");
				}
				else{
					window.location.href = "<?php echo base_url(); ?>mypage/listinvited";
				}
			},
			async: false
		})
		
		return true;
	}
	else{
		return false;	
	}
	
}

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
  
function set_focus(id){
		
	$("#"+id).focus();
	var offset = $("#"+id).offset();
	$("html,body").scrollTop(offset.top-150);
	
}

$(function() {
	$('#list_email').multiple_emails({theme: "Basic"});
	$('#current_emailsB').text($('#list_email').val());
	$('#list_email').change( function(){
		$('#current_emailsB').text($(this).val());
	});
});

</script>

<div class="contents-mypage">
    <div class="contents-mypage__wrapper clearfix">
      <div class="breadly">
        <ul>
          <li><a href="<?php echo base_url()?>"><span itemprop="title"><?php echo $this->lang->line('path_home'); ?></span></a></li>
          <li><a href="<?php echo base_url()?>mypage.html"><span itemprop="title"><?php echo $this->lang->line('mypage_h1'); ?></span></a></li>
          <li><span itemprop="title"><?php echo $this->lang->line('user_page_purchase_order_h1'); ?></span></li>
        </ul>
      </div>
      <?php $this->load->view('user/sidebar_user.php') ?>
      <div class="mypage-main">
            <h1 class="contents-mypage__find">
				<?php echo $this->lang->line('add_member'); ?>
                <a class="add-member" href="<?php echo base_url()?>mypage/listmember"><?php echo $this->lang->line('list_member'); ?></a>
          	</h1>
            
      <div>
            <?php //echo $this->lang->line('content_invited'); ?><br />
        	<?php echo $this->lang->line('input_email_participant'); ?><br />
        	<div class="mypage-form">
                <form name="f" method="post" action="" enctype="multipart/form-data">
                    <input id="list_email" name="list_email" maxlength="100" value="" placeholder="<?php echo $this->lang->line('please_input_email'); ?>" type="text"><br />
                    <span id="list_email_msg" class="warning" style="display: none;"></span>
                    <div class="mypage-form__btn">
                      <input id="btnInviteMembers" name="btnInviteMembers" value="<?php echo $this->lang->line('invite_members'); ?>" type="button">
                    </div>
                </form>
            </div>
        </div>
            
            
      
    </div>
  </div>