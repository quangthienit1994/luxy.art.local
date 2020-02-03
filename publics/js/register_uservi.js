$(function(){
});
function check_error_register(){
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
				$('#re_email_msg').text("Vi Invalid email address");
				 error =1;
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
											$('#re_email_msg').css('display','block');
											 $('#re_email_msg').text("Vi Email already used. Try another email");
											 set_focus("re_email");
											 error =1;
					                    }
					                    else if(msg[1]==0)
					                    {
											 $('#re_email_msg').css('display','none');
											 $('#re_email_msg').text("");
											 set_focus("re_password");
					                    }
						}

				   }); 
			}
	 }
	 if($("#re_password").val()==""){
		$('#re_password_msg').css('display','block');
		$('#re_password_msg').text("Vi Please input password"); 
		set_focus("re_password");
		error =1;
	}else{
		$('#re_password_msg').css('display','none');
		$('#re_password_msg').text("");
		set_focus("re_againpassword");
	}
	if($("#re_againpassword").val()==""){
		$('#re_againpassword_msg').css('display','block');
		$('#re_againpassword_msg').text("Vi Please input password again");	
		set_focus("re_againpassword");
		error =1;
	}else{
			if($("#re_password").val()!=$("#re_againpassword").val())
			{
				
				$('#re_againpassword_msg').css('display','block');
				$('#re_againpassword_msg').text("Vi Password confirmation do not match");	
				set_focus("re_againpassword");
				error =1;	
			}
			else
			{
				$('#re_againpassword_msg').css('display','none');
				$('#re_againpassword_msg').text("");
			}
	}
	if($("#username").val()==""){
		$('#re_username_msg').css('display','block');
		$('#re_username_msg').text("Vi Nhập user name"); 
		set_focus("re_password");
		error =1;
	}
	if(error ==0) {		
			// Call Ajax for register user
			var re_email = $('#re_email').val();
			var re_password = $("#re_password").val();
			var re_againpassword = $("#re_againpassword").val();
			var re_username = $('#username').val();
			if($("#re_checknotice").prop('checked') == true){
				var has_notice = 1;
			}
			else
			{
				var has_notice = 0;
			}
			$.ajax({
					type: "POST",cache: false,
					url: url+'do-ajax-register-user',
					data: "email="+re_email+"&username="+re_username+"&passord="+re_password+"&has_notice="+has_notice,
					async:false,
					success: function(data) {
										//alert(data);
										var msg=data.split("{return_do_register_user}");
										if(msg[1]==1)
					                    {
											
											$('#error_register').css('display','block');
											$('#error_register').text("Vi Error connect....Please try again later");
											return false;
					                    }
					                    else if(msg[1]==0)
					                    {
											window.location.href = url+"register-success.html";
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