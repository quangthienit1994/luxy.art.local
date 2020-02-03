<meta property="og:image" content="<?php echo $detail_product->server_path_upload ?>/<?php echo $detail_product->file_name_watermark_img ?>" />
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
$logged_in = $CI->function_model->get_logged_in();

$data['cart_check_main'] = $this->cart->contents();
$count_cart_check_main = count($data['cart_check_main']);

$user_fullname = $CI->function_model->get_fullname($detail_product->user_id, $this->lang_id);

?>

<!DOCTYPE html>
<html lang="<?php echo $_SESSION['lang']; ?>">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1, maximum-scale=1">
<meta name="description" content="<?php echo isset($meta_seo['description_page']) ? $meta_seo['description_page'] : ""; ?>">
<meta name="keywords" content="<?php echo isset($meta_seo['keywords']) ? $meta_seo['keywords'] : ""; ?>">
<meta property="og:title" content="<?php echo isset($meta_seo['title_page']) ? $meta_seo['title_page'] : ""; ?>">
<meta name="google-site-verification" content="j16YBWHZvpnCXtl9hXM242usQBwPwUBjsOfsW8O49dQ" />
<meta property="og:type" content="website">
<meta property="og:url" content="">
<meta property="og:site_name" content="<?php echo $this->lang->line('site_name'); ?>">
<meta property="og:description" content="<?php echo isset($meta_seo['description_page']) ? $meta_seo['description_page'] : ""; ?>">
<title><?php echo isset($meta_seo['title_page']) ? $meta_seo['title_page'] : ""; ?></title>
<link rel="stylesheet" href="<?php echo base_url(); ?>publics/css/normalize.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>publics/css/main.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>publics/css/font-awesome.min.css">
<link rel="shortcut icon" href="<?php echo base_url(); ?>favicon.ico">
<link rel="canonical" href="<?php echo (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";?>" />
</head>
<script type="text/javascript" src="<?php echo base_url(); ?>publics/multiselect/js/jquery.js"></script>
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
</style>
<script>
$(document).ready(function(){
	
	$("#user_email_popup").keydown(function (e) {
	  if (e.keyCode == 13) {
		return login_popup();	
	  }
	});
	
	$("#user_pass_popup").keydown(function (e) {
	  if (e.keyCode == 13) {
		return login_popup();	
	  }
	});
	
	$("#btnLoginPopup").click(function(){
		return login_popup();
	});
	
	//submit
	$("#btn_send_main").click(function(){	
		return validateMain();
	});
	
});

function login_popup(){
	
	//define
	var msg = "";
	var invalid = 1;
	var flag = 1;
	var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;	
	var is_focus = 1;
	var remember_me = 0;
	
	//get data
	var user_email 		= $("#user_email_popup").val();
	var user_pass 		= $("#user_pass_popup").val();
	
	if($('#remember_me_popup').is(":checked")){
		remember_me = 1;
	}
	else{
		remember_me = 0;
	}
	
	if(flag==1){
		$.ajax({
			type: "POST",cache: false,
			url: "<?php echo base_url(); ?>user/login_ajax",
			data: "user_email="+user_email+"&user_pass="+user_pass+"&remember_me="+remember_me+"&is_popup="+1,
			success: function(data) {
				if(data == 1){
					window.location.href = "<?php echo base_url(); ?>mypage.html";
				}
				else{
					window.location.href = "<?php echo base_url(); ?>login.html";
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
<body>
<div class="sp-menu" style="display:none">
  <div class="sp-menu__wrapper">
    <div class="sp-menu__search">
      <form>
        <input type="text" placeholder="search_materials">
      </form>
    </div>
    <div class="sp-menu__inner">
      <div class="sp-menu__list">
        <ul>
          <li class="sp-menu__cart"><a href="<?php echo base_url(); ?>cart.html"><?php echo $this->lang->line('cart'); ?></a></li>
          <li class="sp-menu__favorite"><a href="<?php echo base_url(); ?>mypage-favorite.html"><?php echo $this->lang->line('mypage_favorite'); ?></a></li>
          <li><a href="<?php echo base_url(); ?>mypage.html"><?php echo $this->lang->line('mypage'); ?></a></li>
          <li><a href="<?php echo base_url(); ?>category.html"><?php echo $this->lang->line('photo_mypage_category'); ?></a></li>
          <li><a href="<?php echo base_url(); ?>page/guideline.html"><?php echo $this->lang->line('user_guide'); ?></a></li>
          <li><a href="<?php echo base_url(); ?>qa.html"><?php echo $this->lang->line('frequently_asked_questions'); ?></a></li>
        </ul>
      </div>
    </div>
  </div>
</div>
<div id="container">
  <header class="g-header">
    <div class="g-header__wrapper">
      <h1 class="site-name"><a href="<?php echo base_url(); ?>"><img src="<?php echo base_url(); ?>publics/img/logo.png" alt="LUXYART"></a></h1>
      <nav class="g-nav">
        <ul>
          <?php
		  	$menu_top = $CI->function_model->getMenuTop()->result();
    		if(!empty($menu_top)){
				foreach($menu_top as $detail_menu_top){
					
					 if($_SESSION['lang'] == "ja"){
						echo '<li><a href="'.base_url().$detail_menu_top->link_url.'">'.$detail_menu_top->menu_ja.'</a></li>';
					 }
					 else if($_SESSION['lang'] == "vi"){
						echo '<li><a href="'.base_url().$detail_menu_top->link_url.'">'.$detail_menu_top->menu_vi.'</a></li>';
					 }
					 else if($_SESSION['lang'] == "en"){
						echo '<li><a href="'.base_url().$detail_menu_top->link_url.'">'.$detail_menu_top->menu_en.'</a></li>';
					 }
				}
			}
		  ?>
          
          <?php
		  	if(!empty($logged_in)){
		  ?>
          		<li><a href="<?php echo base_url(); ?>mypage.html"><i class="fa fa-user" aria-hidden="true"></i>&nbsp;<?php echo $detail_user->display_name; ?></a></li>
                <li>
                	<?php
						if($count_cart_check_main > 0){
					?>
                	<a href="<?php echo base_url(); ?>cart.html"><i class="fa fa-shopping-cart"></i>&nbsp;<?php echo $this->lang->line('cart'); ?>&nbsp;<strong class="red">(<?php echo $count_cart_check_main; ?>)</strong></a>
                    <?php
						}
						else{
					?>
                    <a href="<?php echo base_url(); ?>cart.html"><i class="fa fa-shopping-cart"></i>&nbsp;<?php echo $this->lang->line('cart'); ?>&nbsp;<strong>(<?php echo $count_cart_check_main; ?>)</strong></a>
                    <?php
						}
					?>
              	</li>
                <li><a href="<?php echo base_url(); ?>logout.html"><i class="fa fa-sign-out"></i>&nbsp;<?php echo $this->lang->line('logout'); ?></a></li>
          <?php		
			}
			else{
		  ?>
          		<li>
                	<?php
						if($count_cart_check_main > 0){
					?>
                	<a href="<?php echo base_url(); ?>cart.html"><i class="fa fa-shopping-cart"></i>&nbsp;<?php echo $this->lang->line('cart'); ?>&nbsp;<strong class="red">(<?php echo $count_cart_check_main; ?>)</strong></a>
                    <?php
						}
						else{
					?>
                    <a href="<?php echo base_url(); ?>cart.html"><i class="fa fa-shopping-cart"></i>&nbsp;<?php echo $this->lang->line('cart'); ?>&nbsp;<strong>(<?php echo $count_cart_check_main; ?>)</strong></a>
                    <?php
						}
					?>
              	</li>
          		<li id="g-nav-login-btn"><span><?php echo $this->lang->line('login'); ?></span>
            <div class="g-nav__login">
            
           		<form>
                <div class="g-nav__form">
                  <input type="text" id="user_email_popup" name="user_email_popup" value="" autocomplete="off" placeholder="<?php echo $this->lang->line('enter_email_address'); ?>">
                  <input type="password" id="user_pass_popup" name="user_pass_popup" value="" autocomplete="off" placeholder="<?php echo $this->lang->line('enter_password'); ?>">
                </div>
                <div class="g-nav__checkbox">
                  <label>
                    <input id="remember_me_popup" type="checkbox">
                    <?php echo $this->lang->line('remember_login'); ?> </label>
                </div>
                <div class="g-nav__btn">
                  <input id="btnLoginPopup" type="button" value="<?php echo $this->lang->line('login'); ?>">
                </div>
                <div class="g-nav__link"><a href="<?php echo base_url(); ?>remind.html"><?php echo $this->lang->line('if_you_forgot_password'); ?>>></a></div>
                
                </form>
            </div>
          </li>
          		<li class="g-nav__entry"><a href="<?php echo base_url(); ?>entry.html"><?php echo $this->lang->line('join_free'); ?></a></li>
                
          <?php
			}
		  ?>
        </ul>
      </nav>
      <nav class="sp-nav">
        <ul>
          <li id="sp-nav__entry" class="sp-nav__entry"><span></span></li>
          <li id="sp-nav-open" class="sp-nav__menu"><span></span></li>
        </ul>
        <div class="sp-nav__entry-menu">
          <ul>
            <li><a href="<?php echo base_url(); ?>login.html"><?php echo $this->lang->line('login'); ?></a></li>
            <li><a href="<?php echo base_url(); ?>entry.html"><?php echo $this->lang->line('new_member_registration'); ?></a></li>
            <li><a href="<?php echo base_url(); ?>creator-entry.html"><?php echo $this->lang->line('creator_member_registration'); ?></a></li>
          </ul>
        </div>
      </nav>
    </div>
  </header>

<script src="<?php echo base_url(); ?>/publics/js/jquery-1.11.0.min.js"></script>
<link rel="stylesheet" href="<?php echo base_url(); ?>publics/css/comment.css">

<style>
.lowercase{
	text-transform: lowercase;
}
.detail-cart__btn input[type=submit] {
	width: 200px;
	height: 40px;
	line-height: 40px;
	color: #fff;
	font-weight: bold;
	font-size: 14px;
	text-align: center;
	background: gradient(linear, left top, left bottom, from(#00d2b9), to(#00c6ad));
	background: -webkit-gradient(linear, left top, left bottom, from(#00d2b9), to(#00c6ad));
	background: linear-gradient(to bottom, #00d2b9, #00c6ad);
	border-radius: 3px;
	display: block;
}
.mypage-form input[type="button"] {
	width: auto;
	height: 30px;
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
.mypage-form input[type="button"]:hover {
	background: #00cbb4;
}
.mypage-form input[type="text"]{
	width: 303px !important;
}
#link{
	font-size: 12px;
}
.detail-user__follow-btn{
	cursor:pointer !important;
}
.timeline-share__twitter iframe {height:28px!important}
iframe body {height:28px!important}
.timeline-share__twitter .btn {height:28px!important}
.timeline-share ul li {
    width: 130px;
    height: 28px;
    /* display: inline-block; */
    float: left;
}
@media (max-width: 768px)
{
	#report{margin-top:7px}
	#report i.fa{width: 100%;text-align: center; margin-top: 5px;}
	.detail-img-meta .pixel .detail-img-meta__header{width:100%;text-align:center!important}
	.breadly {display:block!important;padding:0px !important}
	.contents-detail__ttl{margin-top:0px!important}
	.button_invite_get_image_sell {top:inherit!important;display:inline-block!important}
}
#report{
	/*height:44px !important;*/
	line-height:15px !important;
}
</style>

<style>
.pad-top-event{
	padding:10px 0 0 0;
}
.follow-btn{
	width: 120px;
	height: 26px;
	margin:0 !important;
	line-height: 26px;
	color: #00cbb4;
	font-weight: bold;
	font-size: 12px;
	text-align: center;
	background: #fff;
	border: 1px solid #00cbb4;
	border-radius: 3px;
	display: inline-block;
	vertical-align: top;
	cursor: pointer;
}
.follow-btn a:hover{
	color:#666;
}
/*show web or mobile*/
.show-web { display: inline;}
.show-mobile { display: none;}
@media only screen
and (min-device-width : 320px)
and (max-device-width : 480px){ 
	.show-web {display: none;}
	.show-mobile {display: inline;}
}
</style>
<script>
$(document).ready(function(){
	//report
	$('#report').click(function(){
		$('#show_report').slideToggle('slow');
	});
	
	//invite
	$('#invite').click(function(){
		$('#show_invite').slideToggle('slow');
	});
	
	//btn report image
	$("#btn_report_image").click(function(){	
		return validateReportImage();
	});
	
	//process comment
	//hide comment
	$(".box-comment-answer").hide();
	
});

function open_box_answer_root(comment_id){
	$("#box-comment-answer-root-"+comment_id).show();
}

function close_box_answer_root(comment_id){
	$("#box-comment-answer-root-"+comment_id).hide();
}

function open_box_answer_sub(comment_id){
	$("#box-comment-answer-sub-"+comment_id).show();
}

function close_box_answer_sub(comment_id){
	$("#box-comment-answer-sub-"+comment_id).hide();
}

function validateMain(){
	
	var msg = "";
	var flag = 1;
	var is_focus = 1;
	var user_id = "<?php echo $user_id ?>";
	
	if(user_id == ""){
		msg="<?php echo $this->lang->line('please_login_to_comment'); ?>";
		show_msg("comment_main",msg,1);
		flag=0;
		//check focus
		if(is_focus == 1){
			set_focus("comment_main");
			is_focus = 0;
		}
		return false;
	}
	else{
		msg="";
		show_msg("comment_main",msg,0);
	}
	
	var comment = $("#comment_main").val();
	
	//alert("id_img = " + id_img);
	//alert("comment = " + comment);
	
	if(comment == ""){
		msg="<?php echo $this->lang->line('please_enter_comment'); ?>";
		show_msg("comment_main",msg,1);
		flag=0;
		//check focus
		if(is_focus == 1){
			set_focus("comment_main");
			is_focus = 0;
		}
	}
	else{
		msg="";
		show_msg("comment_main",msg,0);
	}
	
	if(flag == 1){
	
		$.ajax({
			type: "POST",cache: false,
			url: "<?php echo base_url(); ?>ajax/add_comment",
			data: "comment="+comment+"&id_img="+id_img+"&comment_id=0"+"&level=1",
			success: function(data) {
				if(data == 1){
					location.reload();
				}
			},
			async: false
		});
	
	}
	
}

function send_root(comment_id){
	
	var msg = "";
	var flag = 1;
	var is_focus = 1;
	var user_id = "<?php echo $user_id ?>";
	
	if(user_id == ""){
		msg="<?php echo $this->lang->line('please_login_to_comment'); ?>";
		show_msg("comment_root_"+comment_id,msg,1);
		flag=0;
		//check focus
		if(is_focus == 1){
			set_focus("comment_root_"+comment_id);
			is_focus = 0;
		}
		return false;
	}
	else{
		msg="";
		show_msg("comment_root_"+comment_id,msg,0);
	}
	
	var comment = $("#comment_root_"+comment_id).val();
	
	//alert("id_img = " + id_img);
	//alert("comment_id = " + comment_id);
	//alert("comment = " + comment);
	
	if(comment == ""){
		msg="<?php echo $this->lang->line('please_enter_comment'); ?>";
		show_msg("comment_root_"+comment_id,msg,1);
		flag=0;
		//check focus
		if(is_focus == 1){
			set_focus("comment_root_"+comment_id);
			is_focus = 0;
		}
	}
	else{
		msg="";
		show_msg("comment_root_"+comment_id,msg,0);
	}
	
	$.ajax({
		type: "POST",cache: false,
		url: "<?php echo base_url(); ?>ajax/add_comment",
		data: "comment="+comment+"&id_img="+id_img+"&comment_id="+comment_id+"&level=2",
		success: function(data) {
			if(data == 1){
				location.reload();
			}
		},
		async: false
	});
	
}

function send_sub(comment_id){
	
	var msg = "";
	var flag = 1;
	var is_focus = 1;
	var user_id = "<?php echo $user_id ?>";
	
	if(user_id == ""){
		msg="<?php echo $this->lang->line('please_login_to_comment'); ?>";
		show_msg("comment_sub_"+comment_id,msg,1);
		flag=0;
		//check focus
		if(is_focus == 1){
			show_msg("comment_sub_"+comment_id,msg,0);
			is_focus = 0;
		}
		return false;
	}
	else{
		msg="";
		show_msg("comment_main",msg,0);
	}
	
	var comment = $("#comment_sub_"+comment_id).val();
	
	//alert("id_img = " + id_img);
	//alert("comment_id = " + comment_id);
	//alert("comment = " + comment);
	
	if(comment == ""){
		msg="<?php echo $this->lang->line('please_enter_comment'); ?>";
		show_msg("comment_sub_"+comment_id,msg,1);
		flag=0;
		//check focus
		if(is_focus == 1){
			set_focus("comment_sub_"+comment_id);
			is_focus = 0;
		}
	}
	else{
		msg="";
		show_msg("comment_sub_"+comment_id,msg,0);
	}
	
	$.ajax({
		type: "POST",cache: false,
		url: "<?php echo base_url(); ?>ajax/add_comment",
		data: "comment="+comment+"&id_img="+id_img+"&comment_id="+comment_id+"&level=2",
		success: function(data) {
			if(data == 1){
				location.reload();
			}
		},
		async: false
	});
	
}

function load_type_size(type_size_id){
	
	$.ajax({
		type: "POST",
		cache: false,
		url: "<?php echo base_url(); ?>ajax/load_type_size",
		data: "type_size_id=" + type_size_id,
		success: function(data) {
			$("#info_add_cart").html(data);
		}
	});
	
}

function validateReportImage(){
	
	var msg = "";
	var flag = 1;
	var is_focus = 1;
	
	email = $("#email").val().trim();
	full_name = $("#full_name").val().trim();
	
	//email	
	/*email = $("#email").val().trim();
	if(email == ""){
		msg="<?php //echo $this->lang->line('please_input_email'); ?>";
		show_msg("email",msg,1);
		flag=0;
		//check focus
		if(is_focus == 1){
			set_focus("email");
			is_focus = 0;
		}
	}
	else{
		var pattern = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
		if(email != ""){
			if(!pattern.test(email)){
				msg="<?php //echo $this->lang->line('email_is_not_valid'); ?>";
				show_msg("email",msg,1);
				flag=0;
				//check focus
				if(is_focus == 1){
					set_focus("email");
					is_focus = 0;
				}
			}
			else{
				msg="";
				show_msg("email",msg,0);
			}
		}
	}*/
	
	//full_name
	/*full_name = $("#full_name").val().trim();
	if(full_name == ""){
		msg="<?php //echo $this->lang->line('please_input_full_name'); ?>";
		show_msg("full_name",msg,1);
		flag=0;
		//check focus
		if(is_focus == 1){
			set_focus("full_name");
			is_focus = 0;
		}
	}
	else{
		msg="";
		show_msg("full_name",msg,0);
	}*/
	
	//content
	reason = $("#reason").val();
	if(reason == ""){
		msg="<?php echo $this->lang->line('please_choose_reason'); ?>";
		show_msg("reason",msg,1);
		flag=0;
		//check focus
		if(is_focus == 1){
			set_focus("reason");
			is_focus = 0;
		}
	}
	else{
		/*if(content.length < 30){
			msg="<?php //echo $this->lang->line('please_input_content_at_least_30_characters'); ?>";
			show_msg("content",msg,1);
			flag=0;
			//check focus
			if(is_focus == 1){
				set_focus("content");
				is_focus = 0;
			}
		}
		else{
			msg="";
			show_msg("content",msg,0);
		}*/
		msg="";
		show_msg("content",msg,0);
	}
	
	if(flag == 1){
		
		$.ajax({
			type: "POST",
			cache: false,
			url: "<?php echo base_url(); ?>ajax/report_image",
			data: "id_img=" + id_img + "&email=" + email + "&full_name=" + full_name + "&reason=" + reason,
			success: function(data) {
				if(data == 1){
					location.reload();
				}
				else if(data == 2){
					alert("<?php echo $this->lang->line('this_email_is_reported'); ?>");
				}
			}
		});
		
		return true;
	}
	else{
		return false;
	}
	
}

</script>

<script>
var user_id = "<?php echo $user_id ?>";
var id_img = "<?php echo $detail_product->id_img ?>";
$(document).ready(function() {
	
	$("#message_success").hide();
	$("#message_success2").hide();
	$("#message_success3").hide();
	
	$('#all').change(function() {
		var checkboxes = $(this).find(':checkbox');
		if($(this).is(':checked')) {
			$('.checked').prop( "checked", true );
		} else {
			$('.checked').prop( "checked", false );
		}
	});
		
});
function add_to_favorite(){
	
	if(user_id == ""){
		window.location.href = "<?php echo base_url(); ?>login.html";
		return;
	}
	
	var result = confirm("<?php echo $this->lang->line('are_you_want_bookmark_this_image'); ?>");
	if(result){
		
		$.ajax({
			type: "POST",
			cache: false,
			url: "<?php echo base_url(); ?>ajax/add_to_favorite",
			data: "id_img=" + id_img + "&user_id=" + user_id,
			success: function(data) {
				
				if(data == 1){
					
					message_success = "<?php echo $this->lang->line('add_to_favorite_successfully'); ?>";
					$("#message_success").show();
					$("#message_success").text(message_success);
					
					setTimeout(
						function(){
							location.reload();
						}, 3000
					);
					
				}
			}
		});
		
	}
	
}
function remove_to_favorite(){
	
	if(user_id == ""){
		window.location.href = "<?php echo base_url(); ?>login.html";
		return;
	}
	
	var result = confirm("<?php echo $this->lang->line('are_you_want_remove_bookmark_this_image'); ?>");
	if(result){
		
		$.ajax({
			type: "POST",
			cache: false,
			url: "<?php echo base_url(); ?>ajax/remove_to_favorite",
			data: "id_img=" + id_img + "&user_id=" + user_id,
			success: function(data) {
				
				if(data == 1){
					
					message_success = "<?php echo $this->lang->line('remove_to_favorite_successfully'); ?>";
					$("#message_success").show();
					$("#message_success").text(message_success);
					
					setTimeout(
						function(){
							location.reload();
						}, 3000
					);
					
				}
			}
		});
		
	}
	
}
function add_to_follow(){
	
	if(user_id == ""){
		window.location.href = "<?php echo base_url(); ?>login.html";
		return;
	}
	
	var result = confirm("<?php echo $this->lang->line('are_you_want_follow_this_user'); ?>");
	if(result){
		
		$.ajax({
			type: "POST",
			cache: false,
			url: "<?php echo base_url(); ?>ajax/add_to_follow",
			data: "id_img=" + id_img + "&user_id=" + user_id,
			success: function(data) {
				
				if(data == 1){
					
					message_success = "<?php echo $this->lang->line('add_to_follow_successfully'); ?>";
					$("#message_success3").show();
					$("#message_success3").text(message_success);
					
					setTimeout(
						function(){
							location.reload();
						}, 3000
					);
					
				}
			}
		});
		
	}
	
}
function remove_to_follow(){
	
	if(user_id == ""){
		window.location.href = "<?php echo base_url(); ?>login.html";
		return;
	}
	
	var result = confirm("<?php echo $this->lang->line('are_you_want_remove_follow_this_user'); ?>");
	if(result){
		
		$.ajax({
			type: "POST",
			cache: false,
			url: "<?php echo base_url(); ?>ajax/remove_to_follow",
			data: "id_img=" + id_img + "&user_id=" + user_id,
			success: function(data) {
				if(data == 1){
					
					message_success = "<?php echo $this->lang->line('remove_to_follow_successfully'); ?>";
					$("#message_success3").show();
					$("#message_success3").text(message_success);
					
					setTimeout(
						function(){
							location.reload();
						}, 3000
					);
					
				}
			}
		});
		
	}
	
}
function add_to_cart(){
	
	var msg = "";
	var flag = 1;
	
	var isMobile = window.matchMedia("only screen and (max-width: 768px)");
    if(isMobile.matches) {
		var id_img_size = $("#type_size_mobile option:selected").val();
    }
	else{
		var id_img_size = $('input[name=type_size]:checked').val();
	}
	
	if(id_img_size === undefined){
		show_msg("type_size","<?php echo $this->lang->line('please_select_image_size'); ?>",1);
		flag=0;
	}
	else{
		show_msg("type_size","",0);
	}
	
	if(flag == 1){
		
		$.ajax({
			type: "POST",cache: false,
			url: "<?php echo base_url(); ?>shopping/add_cart",
			data: "id_img_size="+id_img_size,
			success: function(data) {
				/*if(user_id == 68){
					alert("data = " + data);
					return false;
				}*/
				if(data == 1){
					window.location.href = "<?php echo base_url(); ?>cart.html";
				}
				else{
					show_msg("type_size","<?php echo $this->lang->line('you_have_added_the_cart'); ?>",1);
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
function get_image_sell(id_img, user_id){
	
	var result = confirm("<?php echo $this->lang->line('are_you_want_get_this_image_to_list_my_picture'); ?>");
	if(result){
		
		$.ajax({
			type: "POST",cache: false,
			url: "<?php echo base_url(); ?>ajax/get_image_sell",
			data: "id_img="+id_img+"&user_id="+user_id,
			success: function(data) {
				if(data == 1){
					
					message_success = "<?php echo $this->lang->line('get_image_sell_successfully'); ?>";
					$("#message_success2").show();
					$("#message_success2").text(message_success);
					
					setTimeout(
						function(){
							location.reload();
						}, 3000
					);
					
				}
			},
			async: false
		})
		
	}
	
}
function remove_image_sell(id_img, user_id){
	
	var result = confirm("<?php echo $this->lang->line('are_you_want_remove_this_image_to_list_my_picture'); ?>");
	if(result){
		
		$.ajax({
			type: "POST",cache: false,
			url: "<?php echo base_url(); ?>ajax/remove_image_sell",
			data: "id_img="+id_img+"&user_id="+user_id,
			success: function(data) {
				if(data == 1){
					
					message_success = "<?php echo $this->lang->line('remove_image_sell_successfully'); ?>";
					$("#message_success2").show();
					$("#message_success2").text(message_success);
					
					setTimeout(
						function(){
							location.reload();
						}, 3000
					);
					
				}
			},
			async: false
		})
		
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

function copyToClipboard(element) {
  var $temp = $("<input>");
  $("body").append($temp);
  $temp.val($(element).text()).select();
  document.execCommand("copy");
  $temp.remove();
}

</script>
<div class="detail-img-big">
	<div class="detail-img-big__filter"></div>
	<div class="detail-img-big__inner"><img alt="<?php echo $detail_product->img_title ?>" src="<?php echo $detail_product->server_path_upload ?>/<?php echo $detail_product->file_name_watermark_img ?>"></div>
</div>
<div class="contents-detail">
<div class="contents-detail__wrapper clearfix">
  <div class="breadly">
    <ul>
      <li><a href="<?php echo base_url(); ?>"><span itemprop="title"><?php echo $this->lang->line('home'); ?></span></a></li>
      <li><a href="<?php echo base_url(); ?>category.html">
        	<?php
				$k = 0;
            	foreach($img_category as $key_img_category => $value_img_category){
					echo '<a href="'.base_url().'category/'.$key_img_category.'.html"><span itemprop="title">'.$CI->function_model->getRootCate($key_img_category,$this->lang_id)->cate_name.'</span></a>&gt;';
				}
			?>
      </a></li>
      <li><a href="<?php echo base_url(); ?>category.html">
        	<?php
				$k = 0;
            	foreach($img_category as $key_img_category => $value_img_category){
					if($k == 0){
						echo '<a href="'.base_url().'category/'.$value_img_category[0][0].'.html"><span itemprop="title">'.$CI->function_model->getParentCate($value_img_category[0][0],$this->lang_id)->cate_name.'</span></a>&nbsp;&nbsp;&nbsp;';
						
					}
				}
			?>
      </a></li>
      <!--<li><a href="index.html" itemtype="http://data-vocabulary.org/Breadcrumb"><span itemprop="title">ポーズ・表情</span></a></li>-->
      <li><span itemprop="title"><?php echo $detail_product->img_title ?></span></li>
    </ul>
  </div>
  <h1 class="contents-detail__ttl"><?php echo $detail_product->img_title ?></h1>
  <div class="contents-detail__left">
    <div class="detail__img">
      <div class="detail__img-zoom">&nbsp;</div><img alt="><?php echo $detail_product->img_title ?>" src="<?php echo $detail_product->server_path_upload ?>/<?php echo $detail_product->file_name_watermark_img ?>">
    </div>
	<div>
    	<?php
			if($detail_product->option_img_watermark == 1){//blur
				echo $this->lang->line('show_text_blur_image');
			}
			elseif($detail_product->option_img_watermark == 2){//watermark
				echo $this->lang->line('show_text_watermark_image');
			}
		?>
    </div>
    <div id="message_success" class="red"></div>
    <div class="detail__img-button">
      <ul>
        <li class="download"><a href="<?php echo base_url(); ?>ajax/download_file/<?php echo $id_img_code; ?>"><span><?php echo $this->lang->line('download_the_picture_materia'); ?></span></a></li>
        <li class="favorite">
			<?php
				if($detail_product->user_id != $user_id){
					if($is_bookmark_image == 1){
						echo '<a onclick="remove_to_favorite()"><span>'.$this->lang->line('add_to_favourites_exits')."</span></a>";
					}
					else{
						echo '<a onclick="add_to_favorite()"><span>'.$this->lang->line('add_to_favourites')."</span></a>";
					}
				}
				else{
					echo '<a><span>'.$this->lang->line('add_to_favourites')."</span></a>";
				}
				
            ?>
            
       	</li>
      
      	
           
	   <?php
        if($detail_product->user_id != $user_id || $user_id == ""){
       ?>
      
        <li id="report" class="report" class="download"><a><span><i class="fa fa-flag" aria-hidden="true"></i>&nbsp;<?php echo $this->lang->line('report'); ?></span></a></li>
        
        <?php
		}
		?>
        
      </ul>
      
     
      
      <div id="show_report" style="display:none;">
      
      	<div class="mypage-form">

          <form name="f" method="post" action="" enctype="multipart/form-data">
          
          	<input type="hidden" id="email" name="email" value="<?php echo isset($detail_user->user_email) ? $detail_user->user_email : ""; ?>">
            <input type="hidden" id="full_name" name="full_name" value="<?php echo isset($user_fullname) ? $user_fullname : ""; ?>">

            <!--<dl>

              <dt><?php //echo $this->lang->line('email'); ?><span class="mypage-form__label"><?php //echo $this->lang->line('required'); ?></span></dt>
              <dd>
                <input type="text" id="email" name="email" value="<?php //echo isset($detail_user->user_email) ? $detail_user->user_email : ""; ?>" placeholder="<?php //echo $this->lang->line('please_input_email'); ?>">
                <span id="email_msg" class="warning" style="display: none;"></span>
              </dd>
              
           </dl>-->
           
           <!--<dl>

              <dt>Họ tên<span class="mypage-form__label"><?php //echo $this->lang->line('required'); ?></span></dt>
              <dd>
                <input type="text" id="full_name" name="full_name" value="<?php //echo isset($user_fullname) ? $user_fullname : ""; ?>" placeholder="<?php //echo $this->lang->line('please_input_full_name'); ?>">
                <span id="full_name_msg" class="warning" style="display: none;"></span>
              </dd>
              
           </dl>-->
           
           <dl>

              <!--content-->
              <dt><?php echo $this->lang->line('reason'); ?><span class="mypage-form__label"><?php echo $this->lang->line('required'); ?></span></dt>
              <dd>
                <select id="reason" name="reason">
                	<option value="">---<?php echo $this->lang->line('choose'); ?>---</option>
                    <option value="1"><?php echo $this->lang->line('possibility_of_violation'); ?></option>
                    <option value="2"><?php echo $this->lang->line('this_is_other_ones_image'); ?></option>
                    <option value="3"><?php echo $this->lang->line('violent_image'); ?></option>
                    <option value="4"><?php echo $this->lang->line('porn_image'); ?></option>
                </select>
                <span id="reason_msg" class="warning" style="display: none;"></span>
              </dd>
              <!--end content-->
              
           </dl>
           
           <dl>

              <!--report image-->
              <dd>
                <input type="button" id="btn_report_image" name="btn_report_image" value="&nbsp;&nbsp;<?php echo $this->lang->line('report_image'); ?>&nbsp;&nbsp;" />
              </dd>
              <!--end report image-->
              
           </dl>
           
           </form>
           
           </div>
        
      </div>
      
    </div>
    
  </div>
  <div class="contents-detail__right">
    <div class="detail__label">
	   <?php
		if($time_remaining == -1){
			echo '<div class="label-pink"><span>'.$this->lang->line('remaining').":&nbsp;".$this->lang->line('expired')."</span></div>";
		}
		else if($time_remaining == 1){
			echo '<div class="label-pink"><span>'.$this->lang->line('remaining').":&nbsp;".$this->lang->line('limited_of_time_expired')."</span></div>";
		}
		else{
			
			/*if($this->lang_id == 1){
				echo '<div class="label-pink"><span>'.$this->lang->line('remaining')."&nbsp;:".$time_remaining.$this->lang->line('end_with')."</span></div>";
			}
			else{
				echo '<div class="label-pink"><span>'.$this->lang->line('remaining').":&nbsp;".$time_remaining."</span></div>";
			}*/
			
			if($this->lang_id == 1){
				echo '<div class="label-pink"><span>'.$this->lang->line('remaining').":&nbsp;".'<span id="demo"></span>'.$this->lang->line('end_with')."</span></div>";
			}
			else{
				echo '<div class="label-pink"><span>'.$this->lang->line('remaining').":&nbsp;".'<span id="demo"></span>'."</span></div>";
			}
			
		}
		//$size_stock > 0 || $detail_product->is_limit == 0
	  	if($detail_product->is_limit == 1){
			$quantity_sold = $this->lang->line('quantity_sold');
			$quantity_sold = str_replace("{num}",$size_stock,$quantity_sold);
			echo '<div class="label-orange">'.$quantity_sold.'</div>';
		}
		else{
			//echo '<div class="label-orange">'.$this->lang->line('limited_of_out_stock').'</div>';
		}
	  ?>
    </div>
    <div class="detail-img-meta">
    
      <div class="show-web">
      	
        <ul>
            <li class="pixel">
              <p class="detail-img-meta__header" style="text-align:left;margin: 0 0 8px 0 !important;"><?php echo $this->lang->line('pixel'); ?></p>
              
              <?php
                foreach($list_img_size as $detail_img_size){
                    echo '<p class="detail-img-meta__body" style="text-align:left;"><label><input type="radio" name="type_size" value="'.$detail_img_size->id_img_size.'" />&nbsp;'.str_replace(",","",$detail_img_size->type_size_description).'</p></label>';
                }
              ?>
            </li>
            <li class="size">
              <p class="detail-img-meta__header"><?php echo $this->lang->line('stock'); ?></p>
              <?php
                foreach($list_img_size as $detail_img_size){
                    if($detail_product->is_limit == 1){
                        echo '<p class="detail-img-meta__body">'.$detail_img_size->size_stock.'</p>';
                    }
                    else{
                        echo '<p class="detail-img-meta__body">'.$this->lang->line('limited_of_out_stock').'</p>';
                    }
                }
              ?>
            </li>
            <li class="type">
              <p class="detail-img-meta__header"><?php echo $this->lang->line('format'); ?></p>
              <?php
                foreach($list_img_size as $detail_img_size){
                    echo '<p class="detail-img-meta__body">'.$detail_product->img_type.'</p>';
                }
              ?>
            </li>
            <li class="price">
              <p class="detail-img-meta__header"><?php echo $this->lang->line('price'); ?></p>
               <?php
                foreach($list_img_size as $detail_img_size){
                    echo '<p class="detail-img-meta__body">'.$detail_img_size->price_size_point.'&nbsp;<span class="lowercase">'.$this->lang->line('point').'</span></p>';
                }
              ?>
            </li>
       	</ul>
        
      </div>
      <div class="show-mobile">
      	<div style="padding:10px;">	
          <?php
		  	echo "<select id='type_size_mobile' name='type_size_mobile' onchange='load_type_size(this.value)'>";
			foreach($list_img_size as $detail_img_size){
				echo "<option value='".$detail_img_size->id_img_size."'>".str_replace(",","",$detail_img_size->type_size_description)."</option>";
			}
			echo "</select>";
		  ?>
          <br />
          <span id="info_add_cart">
              <strong><?php echo $this->lang->line('stock'); ?></strong>: 
			  <?php
				if($data_max_id_img_size->is_limit == 1){
					echo $data_max_id_img_size->size_stock;
				}
				else{
					echo $this->lang->line('limited_of_out_stock');
				}
			  ?><br />
              <strong><?php echo $this->lang->line('format'); ?></strong>: <?php echo $data_max_id_img_size->img_type; ?><br />
              <strong><?php echo $this->lang->line('price'); ?></strong>: <?php echo $data_max_id_img_size->price_size_point." ".$this->lang->line('point'); ?>
          </span>
          </div>
      </div>
    
      
      <span id="type_size_msg" class="warning" style="display: none;"></span>
    </div>
    
    <div class="detail-cart">
      <div class="detail-cart__btn">
      
        <?php
			
			if($user_id != ""){
				if($detail_product->user_id != $user_id){
					if($detail_product->img_is_join_compe != 1){
						if($time_remaining != -1 && ($size_stock > 0 || $detail_product->is_limit == 0)){
							echo '<a onclick="add_to_cart();">'.$this->lang->line('add_to_cart').'</a>';
						}
					}
				}
			}
			else{
				if($detail_product->img_is_join_compe != 1){
					if($time_remaining != -1 && ($size_stock > 0 || $detail_product->is_limit == 0)){
						echo '<a onclick="add_to_cart();">'.$this->lang->line('add_to_cart').'</a>';
					}
				}
			}
			
		?>
        
      </div>
      <div class="detail-cart__info">
        <p><a href="<?php echo base_url(); ?>page/foruse.html"><?php echo $this->lang->line('please_check_the_terms_of_service'); ?></a></p>
      </div>
    </div>
    
    <div>
    	
        <div class="pad-top-event">
        <div id="message_success2" class="red"></div>
     	<?php
			if($user_id > 0 && $user_id != $detail_product->user_id){
				if($is_get_img_sell == 1){
		?>
        			<span class="follow-btn button_invite_get_image_sell" onclick="remove_image_sell(<?php echo $detail_product->id_img; ?>, <?php echo $user_id; ?>);"><a><?php echo $this->lang->line('get_image_exist'); ?></a></span>&nbsp;&nbsp;&nbsp;
        <?php
				}
				else if($is_get_img_sell == 0){
		?>
        			<span class="follow-btn button_invite_get_image_sell" onclick="get_image_sell(<?php echo $detail_product->id_img; ?>, <?php echo $user_id; ?>);"><a><?php echo $this->lang->line('get_image'); ?></a></span>&nbsp;&nbsp;&nbsp;
        <?php
				}
			}
		?>
        
        <?php
			if($user_id > 0 && $user_id != $detail_product->user_id){
		?>
        <span id="invite" class="follow-btn button_invite_get_image_sell"  style="width:150px"><a><?php echo $this->lang->line('invite'); ?></a></span>
        <?php
			}
		?>
        </div>
        
        <div id="show_invite" style="display:none;">
      
      	<div class="mypage-form">

            <dl>

              <!--link share-->
              <dt>
              	<?php echo $this->lang->line('link'); ?>:&nbsp;<input type="text" id="link" name="link" value="<?php echo $link_invite; ?>" readonly="readonly">&nbsp;
              	<input type="button" id="btn_copy" name="btn_copy" title="<?php echo $this->lang->line('click_here_copy_link'); ?>" onclick="copyToClipboard('#copy')" value="&nbsp;&nbsp;<?php echo $this->lang->line('copy'); ?>&nbsp;&nbsp;" /></dt>
                <div id="copy" style="display:none;"><?php echo $link_invite; ?></div>
              <dd>
                
              </dd>
              <!--link share-->
              
           </dl>
           
           </div>
        
      </div>
        
    </div>
    <div class="timeline-share" style="text-align:left;background-color:#fff;"><ul>
            <li class="timeline-share__twitter">
            <a href="https://twitter.com/share?url=<?php echo (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";?>&hashtags=LuxyArt&text= - 素材の写真素材<?php echo $detail_product->img_title ?> ｜LuxyArt（ラクシーアート）" target="_blank"><?php echo $this->lang->line('to_tweet'); ?></a></li>
            <li class="timeline-share__facebook"><a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";?>" target="_blank"><?php echo $this->lang->line('to_share'); ?></a></li>
            <li  class="timeline-share__twitter" style="margin-left:6px"><div class="line-it-button" data-lang="ja" data-type="like" data-url="<?php echo (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";?>" data-share="true" style="display: none;"></div>
  <script src="https://d.line-scdn.net/r/web/social-plugin/js/thirdparty/loader.min.js" async="async" defer="defer"></script></li>
          </ul></div>
    <div class="detail-user">
    <a href="<?php echo base_url()?>timeline/<?php echo $detail_product->user_id; ?>.html">
      <div class="detail-user__icon">
      	<?php
			if($detail_product->user_avatar != ""){
		?>
      			<img alt="<?php echo $detail_product->display_name ?>" src="<?php echo base_url(); ?>publics/avatar/<?php echo $detail_product->user_avatar ?>">
        <?php
			}
			else{
		?>
        		<img alt="<?php echo $detail_product->display_name ?>" src="<?php echo base_url(); ?>publics/avatar/prof-img.png">
        <?php
			}
		?>
      </div>
      </a>
      <div class="detail-user__name">
        <p><a style="color:#008de7" href="<?php echo base_url()?>timeline/<?php echo $detail_product->user_id; ?>.html"><?php echo $user_fullname; ?></a></p>
      </div>
		<?php
			if($detail_product->user_id != $user_id){
				echo '<span class="detail-user__follow-btn">';
				if($is_follow == 1){
					echo '<a onclick="remove_to_follow()">'.$this->lang->line('to_follow_exits').'</a>';
				}
				else{
					echo '<a onclick="add_to_follow()">'.$this->lang->line('to_follow').'</a>';
				}
				echo '</span>';
			}
        ?>
      <div class="detail-user__comment">
      	<p id="message_success3" class="red"></p>
      </div>
      <div class="detail-user__comment">
        <p><?php echo nl2br($detail_product->user_introduction); ?></p>
      </div>
    </div>
    <div class="detail-information">
      <dl>
        <dt><?php echo $this->lang->line('author'); ?></dt>
        <dd>
        	<a href="<?php echo base_url()?>timeline/<?php echo $detail_product->user_id; ?>.html"><?php echo $this->function_model->get_fullname($detail_product->user_id, $this->lang_id); ?></a>
       	</dd>
        <dt><?php echo $this->lang->line('picture_id'); ?></dt>
        <dd><?php echo $detail_product->id_img ?></dd>
        <dt><?php echo $this->lang->line('posted_date'); ?></dt>
        <dd><?php echo date('Y-m-d', strtotime($detail_product->date_add_img)); ?></dd>
        <dt><?php echo $this->lang->line('category'); ?></dt>
        <dd>
        	<?php
            	foreach($img_category as $key_img_category => $value_img_category){
					
					echo '<a href="'.base_url().'category/'.$key_img_category.'.html">'.$CI->function_model->getRootCate($key_img_category,$this->lang_id)->cate_name.'</a>&gt;';
					
					for($i=0; $i<count($value_img_category[0]); $i++){
						
						if($i == count($value_img_category[0])-1){
							//echo '<a href="list.html">'.$value_img_category[0][$i].'・'.$value_img_category[1][$i].'</a>&nbsp;';
							
							if($value_img_category[0][$i] != ""){
								echo '<a href="'.base_url().'category/'.$value_img_category[0][$i].'.html">'.$CI->function_model->getParentCate($value_img_category[0][$i],$this->lang_id)->cate_name.'</a>&nbsp;&nbsp;&nbsp;';
							}
						}
						else{
							//echo '<a href="list.html">'.$value_img_category[0][$i].'・'.$value_img_category[1][$i].'</a>&nbsp;';
							if($value_img_category[0][$i] != ""){
								echo '<a href="'.base_url().'category/'.$value_img_category[0][$i].'.html">'.$CI->function_model->getParentCate($value_img_category[0][$i],$this->lang_id)->cate_name.'・</a>';
							}
						}
						
					}
					
				}
			?>
       	</dd>
        <dt><?php echo $this->lang->line('tag'); ?></dt>
        <dd>
          <ul>
          	<?php
				$img_tags_explode = explode(",",$detail_product->img_tags);
				for($i=0; $i<count($img_tags_explode); $i++){
					echo '<li><a target="_blank" href="'.base_url().'search?cate=all&key_search='.$img_tags_explode[$i].'">'.$img_tags_explode[$i].'</a></li></li>';
				}
			?>
          </ul>
        </dd>
      </dl>
    </div>
  </div>
  <div class="detail-related">
    <h2 class="detail-related__find">
    	<?php
			$user_fullname = $CI->function_model->get_fullname($detail_product->user_id, $this->lang_id);
			if($this->lang_id == 1){
				echo $user_fullname.$this->lang->line('other_picture');
			}
			else{
				echo $this->lang->line('other_picture')." ".$user_fullname;
			}
		?>
   	</h2>
    <div class="detail-related__more"><a href="<?php echo base_url()?>timeline/<?php echo $detail_product->user_id;?>.html"><?php echo $this->lang->line('view_more'); ?>>></a></div>
    <div class="detail-related-img">
      <ul>
        <?php
			foreach($list_image_other as $detail_image_other){
		?>
        <li class="detail-related-img__list">
          <div class="detail-related-img__wrapper">
            <div class="detail-related-img__inner"><a href="<?php echo base_url(); ?>detail/<?php echo $detail_image_other->img_code; ?>.html"><img alt="<?php echo $detail_image_other->img_title; ?>" src="<?php echo $detail_image_other->server_path_upload ?>/<?php echo $detail_image_other->file_name_watermark_img_1; ?>"></a></div>
            <p><?php echo $detail_image_other->img_title; ?></p>
          </div>
        </li>
        <?php
			}
		?>
      </ul>
    </div>
  </div>
  <div class="detail-related">
    <h2 class="detail-related__find"><?php echo $this->lang->line('related_images'); ?></h2>
    <div class="detail-related__more"><a href="<?php echo base_url()?>search?key_search=&cate=all"><?php echo $this->lang->line('view_more'); ?>>></a></div>
    <div class="detail-related-img">
      <ul>
        <?php
			foreach($list_image_relative as $detail_image_relative){
		?>
        <li class="detail-related-img__list">
          <div class="detail-related-img__wrapper">
            <div class="detail-related-img__inner"><a href="<?php echo base_url(); ?>detail/<?php echo $detail_image_relative->img_code; ?>.html"><img alt="<?php echo $detail_image_relative->img_title; ?>" src="<?php echo $detail_image_relative->server_path_upload ?>/<?php echo $detail_image_relative->file_name_watermark_img_1; ?>"></a></div>
            <p><?php echo $detail_image_relative->img_title; ?></p>
          </div>
        </li>
        <?php
			}
		?>
      </ul>
    </div>
  </div>
</div>
</div>

<script>
  // Thiết lập thời gian đích mà ta sẽ đếm
  
  time_remaining = "<?php echo $time_remaining; ?>";
  time_limit_sale = "<?php echo $time_limit_sale; ?>";
  
  if(time_remaining == 1){
	  document.getElementById("demo").innerHTML = "<?php echo $this->lang->line('limited_of_time_expired'); ?>";
  }
  else if(time_remaining == -1){
	  document.getElementById("demo").innerHTML = "<?php echo $this->lang->line('expired'); ?>";
  }
  else{
		
		 var countDownDate = new Date(time_limit_sale).getTime();

	  // cập nhập thời gian sau mỗi 1 giây
	  var x = setInterval(function() {
	
		// Lấy thời gian hiện tại
		var now = new Date().getTime();
	
		// Lấy số thời gian chênh lệch
		var distance = countDownDate - now;
	
		// Tính toán số ngày, giờ, phút, giây từ thời gian chênh lệch
		var days = Math.floor(distance / (1000 * 60 * 60 * 24));
		var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
		var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
		var seconds = Math.floor((distance % (1000 * 60)) / 1000);
		
		if(hours < 10){
			hours = "0"+hours;
		}
		
		if(minutes < 10){
			minutes = "0"+minutes;
		}
		
		if(seconds < 10){
			seconds = "0"+seconds;
		}
	
		// HIển thị chuỗi thời gian trong thẻ p
		if(days > 0){
			document.getElementById("demo").innerHTML = days + " <?php echo $this->lang->line('day'); ?> " + hours + ":" + minutes + ":" + seconds;
		}
		else{
			document.getElementById("demo").innerHTML = hours + ":" + minutes + ":" + seconds;
		}
	
		// Nếu thời gian kết thúc, hiển thị chuỗi thông báo
		if (distance < 0) {
		  clearInterval(x);
		  document.getElementById("demo").innerHTML = "<?php echo $this->lang->line('expired'); ?>";
		  $(".detail-cart__btn").hide();
		}
	  }, 1000);
			
  }
  
</script>

<script type="text/javascript" src="<?php echo base_url(); ?>publics/test/jquery.min.js" ></script>
<script type="text/javascript" src="<?php echo base_url(); ?>publics/test/imagesloaded.pkgd.min.js" ></script>
<script type="text/javascript" src="<?php echo base_url(); ?>publics/test/jquery.pinto.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>publics/test/main.js"></script>
<script src="<?php echo base_url(); ?>publics/js/jquery-1.9.1.js"></script>
<script type="text/javascript">

	var page =0;
	var count_comment = '<?php echo $count_comment?>';
	function loadMoreData(page){
		
		var url = '<?php echo base_url(); ?>detail/<?php echo $detail_product->img_code; ?>.html?page=' + page;
		
	  $.ajax(
	        {
	            url: '<?php echo base_url(); ?>detail/<?php echo $detail_product->img_code; ?>.html?page=' + page,
	            type: "get",
	            beforeSend: function()
	            {
	                $('.detail-image__more-btn').show();
	            }
	        })
	        .done(function(data)
	        {
	            if(data == " "){
	                $('.detail-image__more-btn').html("No more records found");
	                return;
	            }
				if((page*3)<(count_comment-3))
				{
	            	$('.detail-image__more-btn').show();
					var page_onlick = page + 1;
					document.getElementById('load_page').setAttribute("onClick","loadMoreData("+page_onlick+");");
				}
				else
				{
					$('.detail-image__more-btn').hide();
					$('.box-comment-answer-main').css('padding-top', '20px');
				}
	            $("#post-data").append(data);
	        })
	        .fail(function(jqXHR, ajaxOptions, thrownError)
	        {
	              alert('server not responding...');
	        });
	}
	
	function loadMoreDataSub(comment_id, comment_sub_id){
		
		var url = '<?php echo base_url(); ?>images/data_comment_sub_load?comment_id='+comment_id;
		
		$.ajax(
	        {
	            url: '<?php echo base_url(); ?>images/data_comment_sub_load?comment_id='+comment_id,
	            type: "get",
	            beforeSend: function()
	            {
	                $('.detail-image__more_sub-btn').show();
	            }
	        })
	        .done(function(data)
	        {
	            if(data == " "){
	                $('.detail-image__more_sub-btn').html("No more records found");
	                return;
	            }
				/*if((page*3)<(count_comment-3))
				{
	            	$('.detail-image__more_sub-btn').show();
					var page_onlick = page + 1;
					document.getElementById('load_page').setAttribute("onClick","loadMoreData("+page_onlick+");");
				}
				else
				{
					$('.detail-image__more_sub-btn').hide();
				}
	            $("#post-data").append(data);*/
				$('.detail-image__more_sub-btn').hide();
				$("#div-sub-comment-"+comment_sub_id).html(data);
	        })
	        .fail(function(jqXHR, ajaxOptions, thrownError)
	        {
	              alert('server not responding...');
	    });
		
	}
	
</script>
  <footer class="g-footer">
    <div class="g-footer__wrapper">
      <nav class="f-nav">
        <div class="f-nav__column">
          <p class="f-nav__find"><?php echo $this->lang->line('category'); ?></p>
          <div class="f-nav__list">
            <?php
				$whereCategory = array('parent_cate' => 0, 'lang_id' => $this->lang_id, 'level_cate' => 0, 'status_cate' => 1);
				$orderCategory = array();
				$list_category = $CI->main_model->getAllData("luxyart_tb_product_category", $whereCategory, $orderCategory)->result();
				if(!empty($list_category)){
					echo '<ul>';
					foreach($list_category as $detail_category){
						echo '<li><a href="'.base_url().'category/'.$detail_category->product_category_id.'.html">'.$detail_category->cate_name.'</a></li>';
					}
					echo '</ul>';
				}
			?>
          </div>
        </div>
        <div class="f-nav__column">
          <p class="f-nav__find"><?php echo $this->lang->line('other'); ?></p>
          <div class="f-nav__list">
            <ul>
              <?php
			  	$menu_bottom = $CI->function_model->getMenuBottom()->result();
                if(!empty($menu_bottom)){
                    foreach($menu_bottom as $detail_menu_bottom){
						
						if($_SESSION['lang'] == "ja"){
							if($detail_menu_bottom->id_menu == 11){
								echo '<li><a target="_blank" href="'.$detail_menu_bottom->link_url.'">'.$detail_menu_bottom->menu_ja.'</a></li>';
							}
							else{
								echo '<li><a href="'.base_url().$detail_menu_bottom->link_url.'">'.$detail_menu_bottom->menu_ja.'</a></li>';						
							}
						}
						else if($_SESSION['lang'] == "vi"){
							echo '<li><a href="'.base_url().$detail_menu_bottom->link_url.'">'.$detail_menu_bottom->menu_vi.'</a></li>';
						}
						else if($_SESSION['lang'] == "en"){
							echo '<li><a href="'.base_url().$detail_menu_bottom->link_url.'">'.$detail_menu_bottom->menu_en.'</a></li>';
						}
                    }
                }
              ?>
            </ul>
          </div>
        </div>
      </nav>
    </div>
    <div class="g-footer__bottom">
      <div class="g-footer__bottom-wrapper">
        <small class="copyright"> <?php echo $this->lang->line('copyright'); ?> </small> </div>
    </div>
  </footer>
</div>
<link rel="stylesheet" href="<?php echo base_url(); ?>publics/css/normalize.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>publics/css/main.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>publics/css/slidemenu.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>publics/css/flex-images.css">
<!--<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script> -->
<script src="<?php echo base_url(); ?>publics/js/luxyart.js"></script> 
<script src="<?php echo base_url(); ?>publics/js/slidemenu.js"></script> 
</body>
</html>
<script type="text/javascript">
$('#language').on('change', function() {
	language_id = this.value;
	$.ajax({
		type: "POST",cache: false,
		url: "<?php echo base_url(); ?>change_language",
		data: "language_id="+language_id,
		success: function(data) {
			location.reload();
		}
	});
})
</script>