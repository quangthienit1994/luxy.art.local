$(function(){
});
function check_error_user_reset_password(){
	//Get url submit for ajax
	var host = location.protocol + "//" + location.host
	// sub path folder
    var pathName = window.location.pathname.substring(0, window.location.pathname.lastIndexOf('/') + 1);
	var url = host + pathName;
	var error =0;
	if($('#re_email').val()==""){
		$('#re_email_msg').css('display','block');
		$('#re_email_msg').text("Please input your email");
		$('#re_email').focus();
		error =1;
	 }
	 else{
			var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
			var emailaddressVal = $('#re_email').val(); 
			if(!emailReg.test(emailaddressVal))
			{
				$('#re_email_msg').css('display','block');
				 $('#re_email_msg').text("Invalid email address");
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
											 $('#re_email_msg').text("Invalid email address. Please input email when you register account");
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
					url: url+'do-ajax-reset-password',
					data: "email="+re_email,
					async:false,
					success: function(data) {
										var msg=data.split("{return_do_reset_password}");
										if(msg[1]==1)
					                    {
											$('#re_email_msg').css('display','block');
											$('#re_email_msg').text("Error connect....Please try again later");
											return false;
					                    }
					                    else if(msg[1]==0)
					                    {
											window.location.href = url+"remind-success.html";
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