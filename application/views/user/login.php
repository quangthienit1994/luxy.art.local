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
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script> 
<style>
.login-form__btn input[type="button"] {
    background: rgba(0, 0, 0, 0) linear-gradient(to bottom, #00d2b9, #00c6ad) repeat scroll 0 0;
    border-radius: 3px;
    color: #fff;
    font-size: 15px;
    font-weight: bold;
    height: inherit;
    text-align: center;
    width: inherit;
}
.login-form input[type="password"] {
    border: 1px solid #ddd;
    border-radius: 0;
    box-shadow: none;
    box-sizing: border-box;
    height: 40px;
    padding: 0 2px;
    width: 100%;
}
.warning{
	color:#F00;
}
.red{
	color:#F00;
}
</style>
<script>
var message_login = "<?php echo $message_login; ?>";
$(document).ready(function(){
	
	if(message_login != ""){
		$("#message").html("<?php echo $this->lang->line('p_login_text_login_fail'); ?>");
		$("#message").show();
	}
	else{
		$("#message").hide();
	}
	
	$("#user_email").keydown(function (e) {
	  if (e.keyCode == 13) {
		return login();	
	  }
	});
	
	$("#user_pass").keydown(function (e) {
	  if (e.keyCode == 13) {
		return login();	
	  }
	});
	
	$("#btnLogin").click(function(){
		return login();
	});
	
});
function login(){
	
	//define
	var msg = "";
	var invalid = 1;
	var flag = 1;
	var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;	
	var is_focus = 1;
	var remember_me = 0;
	
	$("#message").text("");
	$("#message").hide();
	
	//get data
	var user_email 		= $("#user_email").val();
	var user_pass 		= $("#user_pass").val();
	var actual_link		= $("#actual_link").val();
	
	if($('#remember_me').is(":checked")){
		remember_me = 1;
	}
	else{
		remember_me = 0;
	}
	
	//check email
	if(user_email == ""){
		show_msg("user_email", "<?php echo $this->lang->line('p_login_text_error_empty_input_email'); ?>", 1);  	
		flag = 0;
		if(is_focus == 1){
			set_focus("user_email");
			is_focus = 0;
		}
	}
	else{
		if(!emailReg.test(user_email)){
			show_msg("user_email", "<?php echo $this->lang->line('p_login_text_error_input_email_not_true'); ?>", 1);  	
			flag = 0;
			if(is_focus == 1){
				set_focus("user_email");
				is_focus = 0;
			}
	 	}
		else{
			show_msg("user_email", "", 0);
		}
	}
	//check password
	if(user_pass == ""){
		show_msg("user_pass", "<?php echo $this->lang->line('p_login_text_error_input_empty_pass'); ?>", 1);
		flag = 0;
		if(is_focus == 1){
			set_focus("user_pass");
			is_focus = 0;
		}
	}
	else{
		if(user_pass.length < 4){
			show_msg("user_pass", "<?php echo $this->lang->line('p_login_text_error_input_pass_4_charter'); ?>",1);  	
			flag = 0;
			if(is_focus == 1){
				set_focus("user_pass");
				is_focus = 0;
			}
		}
		else{
			msg="";
			show_msg("user_pass","",0);
		}
	}
	
	if(flag==1){
		$.ajax({
			type: "POST",cache: false,
			url: "<?php echo base_url(); ?>user/login_ajax",
			data: "user_email="+user_email+"&user_pass="+user_pass+"&remember_me="+remember_me+"&is_popup="+0,
			success: function(data) {
				
				/*if(user_email == "fermatandrew@gmail.com"){
					alert("data = " + data);
					return false;
				}*/
				
				if(data == 1){
					if(actual_link != ""){
						window.location.href = actual_link;
					}
					else{
						window.location.href = "<?php echo base_url(); ?>mypage.html";
					}
				}
				else if(data == 2){
					$("#message").html("<?php echo $this->lang->line('p_login_text_login_fail_error2'); ?>");
					$("#message").show();
				}
				else if(data == 3){
					$("#message").html("<?php echo $this->lang->line('p_login_text_login_fail_error2'); ?>");
					$("#message").show();
				}
				else if(data == 4){
					$("#message").html("<?php echo $this->lang->line('p_login_text_login_fail_error4'); ?>");
					$("#message").show();
				}
				else{
					$("#message").html("<?php echo $this->lang->line('p_login_text_login_fail'); ?>");
					$("#message").show();
				}
			}
			, async: false
		});
		return false;
	}
	else{
		return false;
	}
	
}
function show_msg(id,msg,show_flag){
	
	var id1=""; 	
	if(show_flag==1){
	  	$("#"+id+"_msg").show();
	  	$("#"+id+"_msg").html(msg);
	  	//document.getElementById(id).className="textbox_border_style_error";
	}
  	else{
	   	$("#"+id+"_msg").hide();
	   	$("#"+id+"_msg").html('');
	   	//document.getElementById(id).className="textbox_border_style";
  	}
	
}
function set_focus(id){
		
	$("#"+id).focus();
	var offset = $("#"+id).offset();
	$("html,body").scrollTop(offset.top-150);
	
}
</script>
<div class="contents-other">
    <div class="ttl-login">
      <div class="ttl-login__filter">
        <div class="ttl-login__box">
          <h1><?php echo $this->lang->line('login'); ?></h1>
        </div>
      </div>
    </div>
    <div class="contents-other__main">
      <div class="breadly">
        <ul>
          <li><a href="<?php echo base_url(); ?>"><span itemprop="title"><?php echo $this->lang->line('home'); ?></span></a></li>
          <li><span itemprop="title"><?php echo $this->lang->line('login'); ?></span></li>
        </ul>
      </div>
      <div class="contents-other__body">
        <h2><?php echo $this->lang->line('login'); ?></h2>
        <div class="login-form">
          <form>
          	<span id="message" class="red"></span><!--<span class="red"><?php //echo $this->lang->line('activation_content'); ?></span>-->
            <dl>
              <dt><?php echo $this->lang->line('email_address'); ?></dt>
              <dd>
                <input type="text" id="user_email" name="user_email" value="" autocomplete="off" placeholder="<?php echo $this->lang->line('input_email_address'); ?>">
                <span id="user_email_msg" class="warning" style="display: none;"></span>
              </dd>
              <dt><?php echo $this->lang->line('password'); ?></dt>
              <dd>
                <input type="password" id="user_pass" name="user_pass" value="" autocomplete="off" placeholder="<?php echo $this->lang->line('input_password'); ?>">
                <span id="user_pass_msg" class="warning" style="display: none;"></span>
              </dd>
            </dl>
            <div class="login-form__check">
              <label>
                <input id="remember_me" type="checkbox"><?php echo $this->lang->line('remember_login'); ?>
              </label>
            </div>
            <div class="login-form__btn">
              <input id="actual_link" type="hidden" value="<?php echo $actual_link; ?>" />
              <input id="btnLogin" type="button" value="<?php echo $this->lang->line('login'); ?>">
            </div>
          </form>
          <div class="login-form__password"><a href="remind.html"><?php echo $this->lang->line('if_you_forgotten_password'); ?>>></a></div>
          <div class="login-form__entry">
            <p class="login-form__entry-find"><?php echo $this->lang->line('new_registration'); ?></p>
            <p><?php echo $this->lang->line('new_registration_content'); ?></p>
            <p><a href="<?php echo base_url(); ?>entry.html"><?php echo $this->lang->line('new_registration_free'); ?>>></a></p>
          </div>
        </div>
      </div>
    </div>
  </div>