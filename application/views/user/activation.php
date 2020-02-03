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
<script src="<?php echo base_url(); ?>publics/js/user_reset_password<?php echo $ngonngu?>.js"></script>
<div class="contents-other">
        <div class="ttl-surport">
          <div class="ttl-surport__filter">
            <div class="ttl-surport__box">
              <h1><?php echo $this->lang->line('get_email_activation'); ?></h1>
            </div>
          </div>
        </div>
        <div class="contents-other__main">
          <div class="breadly">
            <ul>
              <li><a href="<?php echo base_url(); ?>"><span itemprop="title"><?php echo $this->lang->line('home'); ?></span></a></li>
              <li><span itemprop="title"><?php echo $this->lang->line('get_email_activation'); ?></span></li>
            </ul>
          </div>
          <div class="contents-other__body">
            <h2><?php echo $this->lang->line('get_email_activation'); ?></h2>
            <p><?php echo $this->lang->line('get_email_activation_description'); ?></p>
            <div class="contact-form remind">
                <dl>
                  <dt><?php echo $this->lang->line('email'); ?></dt>
                  <dd>
                    <input name="re_email" id="re_email" type="text" placeholder="<?php echo $this->lang->line('p_dang_ky_creator_form_email_placeholder'); ?>">
                  </dd>
                </dl>
                <div id="re_email_msg" class="warning" style="display: none;"></div>
                <div class="contact-form__btn">
                  <input type="submit" value="<?php echo $this->lang->line('send'); ?>" onclick="return get_email_activation();">
                </div>
            </div>
          </div>
        </div>
      </div>
      
      
<script>

function get_email_activation(){
	
	//Get url submit for ajax
	var host = location.protocol + "//" + location.host
	// sub path folder
    var pathName = window.location.pathname.substring(0, window.location.pathname.lastIndexOf('/') + 1);
	var url = host + pathName;
	var error =0;
	if($('#re_email').val()==""){
		$('#re_email_msg').css('display','block');
		$('#re_email_msg').text("<?php echo $this->lang->line('please_input_email'); ?>");
		$('#re_email').focus();
		error =1;
	 }
	 else{
			var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,10})?$/;
			var emailaddressVal = $('#re_email').val(); 
			if(!emailReg.test(emailaddressVal))
			{
				$('#re_email_msg').css('display','block');
				 $('#re_email_msg').text("<?php echo $this->lang->line('email_is_not_valid'); ?>");
				 error =1;
				 $('#re_email').focus();
				 return false;
			}
			if(emailaddressVal!="")
			{
				//goi function kiem tra trung email bao loi
				 $.ajax({
					type: "POST",cache: false,
					url: url+'check-email-register',
					data: "email="+emailaddressVal,
					async:false,
					success: function(data) {
										var msg=data.split("{return_checkemail}");
										if(msg[1]==1)
					                    {
											 $('#re_email_msg').css('display','none');
											 $('#re_email_msg').text("");
					                    }
					                    else if(msg[1]==0)
					                    {
											 
											 $('#re_email_msg').css('display','block');
											 $('#re_email_msg').text("<?php echo $this->lang->line('email_is_not_valid_please_input_email'); ?>");
											 $('#re_email').focus();
											 error =1;
					                    }
						}

				   }); 
			}
	 }
	 
	if(error ==0) {		
			// Call Ajax for register user
			var re_email = $('#re_email').val();
			$.ajax({
					type: "POST",cache: false,
					url: url+'user/get_email_activation',
					data: "email="+re_email,
					async:false,
					success: function(data) {
						var msg=data.split("{luxyart}");
						if(msg[0]==1){
							window.location.href = url+"activation-success.html";
						}
						else if(msg[0]==0)
						{
							$('#re_email_msg').css('display','block');
							$('#re_email_msg').text(msg[1]);
						}
					}

			}); 
	}else{
		return false;
	}
}
function set_focus(id){
	$("#"+id).focus();
	var offset = $("#"+id).offset();
	$("html,body").scrollTop(offset.top-150);
}

</script>