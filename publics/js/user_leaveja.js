$(function(){
	//退会ページ
  $('#leave-conf-btn').on('click',function(){
	var host = location.protocol + "//" + location.host
	// sub path folder
    var pathName = window.location.pathname.substring(0, window.location.pathname.lastIndexOf('/mypage/user-leave') + 1);
	var url = host + pathName;
	
	var error =0;
	if($('#password').val()==""){
		$('#re_password_msg').css('display','block');
		$('#re_password_msg').text("パスワードを記入して下さい。");
		$('#password').focus();
		error =1;
	 }
	 if($('#password').val()!=""){
		 // Call function check password
		 var password = $('#password').val();
		 $.ajax({
					type: "POST",cache: false,
					url: url+'check-password-leave',
					data: "password="+password,
					async:false,
					success: function(data) {
										var msg=data.split("{return_checkpassword}");
										if(msg[1]==1)
					                    {	
											 // pass dung
											 $('#re_password_msg').css('display','none');
											 $('#re_password_msg').text("");
					                    }
					                    else if(msg[1]==0)
					                    {
											 // pass ko dung
											 $('#re_password_msg').css('display','block');
											 $('#re_password_msg').text("パスワードが不正です。");
											 $('#password').focus();
											 error =1;
					                    }
						}

				   }); 
	 }
	 if(error ==0) {	
  		$('#leave-popup').fadeIn(200);
	 }
  });
  $('#leave-popup').on('click','.leave-popup__filter',function(){
  	$('#leave-popup').fadeOut(100);
  });
  $('#leave-popup').on('click','.leave-popup__btn--leave',function(){
	var host = location.protocol + "//" + location.host
	// sub path folder
	var pathName = window.location.pathname.substring(0, window.location.pathname.lastIndexOf('/mypage/user-leave') + 1);
	var url = host + pathName;
	var password = $('#password').val();
		 $.ajax({
					type: "POST",cache: false,
					url: url+'do-user-leave',
					data: "password="+password,
					async:false,
					success: function(data) {
										var msg=data.split("{return_check_do_user_leave}");
										if(msg[1]==1)
					                    {	
											 window.location.href = url+"notice-user-leave.html";
					                    }
					                    else if(msg[1]==0)
					                    {
											 // pass ko dung
											 $('#re_password_msg').css('display','block');
											 $('#re_password_msg').text("パスワードが不正です。");
											 $('#password').focus();
											 error =1;
					                    }
						}

				   });
  });
  $('#leave-popup').on('click','.leave-popup__btn--cancel',function(){
  	$('#leave-popup').fadeOut(100);
  });
});
