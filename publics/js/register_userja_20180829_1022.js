$(function(){
});
function check_error_register(){
	$("#btnRegister").attr('disabled','disabled');
	$("#btnRegister").val("読み込み中");
	//Get url submit for ajax
	var host = location.protocol + "//" + location.host
	// sub path folder
    var pathName = window.location.pathname.substring(0, window.location.pathname.lastIndexOf('/') + 1);
	var url = host + pathName;
	var error =0;
	if($('#re_email').val()==""){
		$('#re_email_msg').css('display','block');
		$('#re_email_msg').text("メールアドレスを記入して下さい。");
		$('#re_email').focus();
		error =1;
	 }
	 else{
			var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,10})?$/;
			var emailaddressVal = $('#re_email').val(); 
			if(!emailReg.test(emailaddressVal))
			{
				$('#re_email_msg').css('display','block');
				$('#re_email_msg').text("メールアドレスの形式が不正です。");
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
										var msg=data.split("{return_checkemail}");
										if(msg[1]==1)
					                    {
											$('#re_email_msg').css('display','block');
											 $('#re_email_msg').text("このメールアドレスは既に登録済みです。");
											 set_focus("re_email");
											 error =1;
					                    }
										else if(msg[1]==2)
					                    {
											$('#re_email_msg').css('display','block');
											 $('#re_email_msg').text("このメールアドレスは削除されたか管理人によりブロックされています。詳細は管理人までご連絡下さい。");
											 set_focus("re_email");
											 error =1;
					                    }
										else if(msg[1]==3)
					                    {
											$('#re_email_msg').css('display','block');
											 $('#re_email_msg').text("このメールアドレスは削除されたか管理人によりブロックされています。詳細は管理人までご連絡下さい。");
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
		$('#re_password_msg').text("パスワードを記入して下さい。"); 
		set_focus("re_password");
		error =1;
	}else{
		$('#re_password_msg').css('display','none');
		$('#re_password_msg').text("");
		set_focus("re_againpassword");
	}
	if($("#re_againpassword").val()==""){
		$('#re_againpassword_msg').css('display','block');
		$('#re_againpassword_msg').text("パスワード（確認）を記入して下さい。");	
		set_focus("re_againpassword");
		error =1;
	}else{
			if($("#re_password").val()!=$("#re_againpassword").val())
			{
				
				$('#re_againpassword_msg').css('display','block');
				$('#re_againpassword_msg').text("パスワードが一致しません。");	
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
		$('#re_username_msg').text("氏名を記入して下さい。"); 
		set_focus("re_password");
		error =1;
	}
	else
	{
		$('#re_username_msg').css('display','none');
		$('#re_username_msg').text("");
	}
	if(error ==0) {		
			// Call Ajax for register user
			var re_email = $('#re_email').val();
			var re_password = $("#re_password").val();
			var re_againpassword = $("#re_againpassword").val();
			var re_username = $('#username').val();
			var member_id = $('#member_id').val();
			var email_invite = $('#email_invite').val();
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
					data: "email="+re_email+"&username="+re_username+"&passord="+re_password+"&has_notice="+has_notice+"&member_id="+member_id+"&email_invite="+email_invite,
					async:false,
					success: function(data) {
						
										//alert(data);
										var msg=data.split("{return_do_register_user}");
										if(msg[1]==1)
					                    {
											
											$('#error_register').css('display','block');
											$('#error_register').text("接続エラーが発生しました。");
											$("#btnRegister").removeAttr('disabled','disabled');
											$("#btnRegister").val("ユーザー登録");
											return false;
					                    }
					                    else if(msg[1]==0)
					                    {
											window.location.href = url+"register-success.html";
					                    }
										else if(msg[1]==2)
					                    {
											window.location.href = url+"notice.html";
					                    }
						}

				   }); 
	}else{
		$("#btnRegister").removeAttr('disabled','disabled');
		$("#btnRegister").val("ユーザー登録");
		return false;
	}
}
function set_focus(id){
	$("#"+id).focus();
	var offset = $("#"+id).offset();
	$("html,body").scrollTop(offset.top-150);
}