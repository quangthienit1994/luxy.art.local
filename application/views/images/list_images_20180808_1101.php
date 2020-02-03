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

?>

<link rel="stylesheet" href="<?php echo base_url(); ?>publics/drag_and_drop/css/style.css" />

<!DOCTYPE html>
<html lang="<?php echo $_SESSION['lang']; ?>">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1, maximum-scale=1">
<meta name="description" content="<?php echo isset($meta_seo['description_page']) ? $meta_seo['description_page'] : ""; ?>">
<meta name="keywords" content="<?php echo isset($meta_seo['keywords']) ? $meta_seo['keywords'] : ""; ?>">
<meta property="og:title" content="<?php echo isset($meta_seo['title_page']) ? $meta_seo['title_page'] : ""; ?>">
<meta property="og:type" content="website">
<meta property="og:url" content="">
<meta property="og:site_name" content="<?php echo $this->lang->line('site_name'); ?>">
<meta property="og:description" content="<?php echo isset($meta_seo['description_page']) ? $meta_seo['description_page'] : ""; ?>">
<title><?php echo isset($meta_seo['title_page']) ? $meta_seo['title_page'] : ""; ?></title>

<link rel="stylesheet" href="<?php echo base_url(); ?>publics/css/normalize.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>publics/css/main.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>publics/css/font-awesome.min.css">
<link rel="shortcut icon" href="<?php echo base_url(); ?>favicon.ico">

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
.btn-view-more, .btn-post-image{
   width: 150px !important;
}
.mypage-img-list__inner{
 	height: 85% !important;
}
.mypage-img-list__info .delete{
	top: 4px !important;
}
.mypage-img-list__list {
    margin: 2px 0 30px!important;
}
</style>

<style>
.applied{
	text-align:center;
	color:#F00;
}
.applied-left{
	float:left;
}
.applied-delete span{
	background-color:#FFF !important;
}
@media only screen and (min-width: 600px) {
	.applied-delete{
		margin: 3px 15px 0 0;
	}
	.mypage-img-list__info .checked{
		margin: 0 0 0 -11px;
	}
}
</style>

<script>

var num_paging = "<?php echo $num_paging; ?>";
var user_id = "<?php echo $user_id; ?>";

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

<script type="text/javascript" src="<?php echo base_url(); ?>publics/test/jquery.min.js" ></script>
<script type="text/javascript" src="<?php echo base_url(); ?>publics/test/imagesloaded.pkgd.min.js" ></script>
<script type="text/javascript" src="<?php echo base_url(); ?>publics/test/jquery.pinto.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>publics/test/main.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>

<script>

$(document).ready(function(){
	
	/*$("#post-data li").each(function( index ) {
	  	//console.log( index + ": " + $( this ).text() );
		alert("index = " + index);
	});*/
	
});

</script>

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
          		<li><a href="<?php echo base_url(); ?>mypage.html"><i class="fa fa-user" aria-hidden="true"></i>&nbsp;<?php echo $user->display_name; ?></a></li>
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
          <?php 
		$CI =&get_instance();
$CI->config->load();
$logged_in = $CI->function_model->get_logged_in();
$user_id = $logged_in['user_id'];
$user_firstname = $logged_in['user_firstname'];
$user_lastname = $logged_in['user_lastname'];
$user_level = $logged_in['user_level'];
$CI->load->model('manager_user_model');
$user = $CI->manager_user_model->get_user_id($user_id);
	$get_name_email = explode('@',$user->user_email);
	if($user_lastname=="")
	{
		$name_call = $get_name_email[0];
	}
	else
	{
		$name_call = $user_lastname;
	}
	if($user_id!='')
	{?>
          <ul>
          <?php if($user_level>=1){?><li><a href="<?php echo base_url()?>mypage.html" class="menu-top"><?php echo $this->lang->line('my_page_top'); ?></a></li><?php }?>
            <?php // account level 2:Creator, 3:Gold, 4:Platinum, 5:Enterprise
			if($user_level ==1){?>
                <li <?php if($active_menu_sidebar=="mypage-list-compe"){echo 'class="active"';} else {echo '';}?>><a href="<?php echo base_url()?>mypage/list-compe.html"><?php echo $this->lang->line('list_compe'); ?></a></li>
                <li <?php if($active_menu_sidebar=="mypage-post-compe"){echo 'class="active"';} else {echo '';}?>><a href="<?php echo base_url()?>mypage/post-compe.html"><?php echo $this->lang->line('post_compe'); ?></a></li>
                <li <?php if($active_menu_sidebar=="mypage-list-compe-join"){echo 'class="active"';} else {echo '';}?>><a href="<?php echo base_url()?>mypage/list-compe-join.html"><?php echo $this->lang->line('list_picture_join_competition'); ?></a></li>
            <?php }
			elseif($user_level ==2){?>
                <li <?php if($active_menu_sidebar=="mypage-list-images"){echo 'class="active"';} else {echo '';}?>><a href="<?php echo base_url()?>mypage/list-images.html"><?php echo $this->lang->line('list_picture'); ?></a></li>
                <li <?php if($active_menu_sidebar=="mypage-post-image"){echo 'class="active"';} else {echo '';}?>><a href="<?php echo base_url()?>mypage/post-image.html"><?php echo $this->lang->line('add_picture'); ?></a></li>
                <li <?php if($active_menu_sidebar=="mypage-list-compe"){echo 'class="active"';} else {echo '';}?>><a href="<?php echo base_url()?>mypage/list-compe.html"><?php echo $this->lang->line('list_compe'); ?></a></li>
                <li <?php if($active_menu_sidebar=="mypage-post-compe"){echo 'class="active"';} else {echo '';}?>><a href="<?php echo base_url()?>mypage/post-compe.html"><?php echo $this->lang->line('post_compe'); ?></a></li>
                <li <?php if($active_menu_sidebar=="mypage-list-compe-join"){echo 'class="active"';} else {echo '';}?>><a href="<?php echo base_url()?>mypage/list-compe-join.html"><?php echo $this->lang->line('list_picture_join_competition'); ?></a></li>
            <?php }elseif ($user_level ==3){?>
                <li <?php if($active_menu_sidebar=="mypage-list-images"){echo 'class="active"';} else {echo '';}?>><a href="<?php echo base_url()?>mypage/list-images.html"><?php echo $this->lang->line('list_picture'); ?></a></li>
                <li <?php if($active_menu_sidebar=="mypage-post-image"){echo 'class="active"';} else {echo '';}?>><a href="<?php echo base_url()?>mypage/post-image.html"><?php echo $this->lang->line('add_picture'); ?></a></li>
                <li <?php if($active_menu_sidebar=="mypage-list-compe"){echo 'class="active"';} else {echo '';}?>><a href="<?php echo base_url()?>mypage/list-compe.html"><?php echo $this->lang->line('list_compe'); ?></a></li>
                <li <?php if($active_menu_sidebar=="mypage-post-compe"){echo 'class="active"';} else {echo '';}?>><a href="<?php echo base_url()?>mypage/post-compe.html"><?php echo $this->lang->line('post_compe'); ?></a></li>
                <li <?php if($active_menu_sidebar=="mypage-list-compe-join"){echo 'class="active"';} else {echo '';}?>><a href="<?php echo base_url()?>mypage/list-compe-join.html"><?php echo $this->lang->line('list_picture_join_competition'); ?></a></li>
            <?php }elseif ($user_level ==4){?>
                <li <?php if($active_menu_sidebar=="mypage-list-images"){echo 'class="active"';} else {echo '';}?>><a href="<?php echo base_url()?>mypage/list-images.html"><?php echo $this->lang->line('list_picture'); ?></a></li>
                <li <?php if($active_menu_sidebar=="mypage-post-image"){echo 'class="active"';} else {echo '';}?>><a href="<?php echo base_url()?>mypage/post-image.html"><?php echo $this->lang->line('add_picture'); ?></a></li>
                <li <?php if($active_menu_sidebar=="mypage-list-compe"){echo 'class="active"';} else {echo '';}?>><a href="<?php echo base_url()?>mypage/list-compe.html"><?php echo $this->lang->line('list_compe'); ?></a></li>
                <li <?php if($active_menu_sidebar=="mypage-post-compe"){echo 'class="active"';} else {echo '';}?>><a href="<?php echo base_url()?>mypage/post-compe.html"><?php echo $this->lang->line('post_compe'); ?></a></li>
                <li <?php if($active_menu_sidebar=="mypage-list-compe-join"){echo 'class="active"';} else {echo '';}?>><a href="<?php echo base_url()?>mypage/list-compe-join.html"><?php echo $this->lang->line('list_picture_join_competition'); ?></a></li>
            <?php }elseif ($user_level ==5){?>
                <li <?php if($active_menu_sidebar=="mypage-list-images"){echo 'class="active"';} else {echo '';}?>><a href="<?php echo base_url()?>mypage/list-images.html"><?php echo $this->lang->line('list_picture'); ?></a></li>
                <li <?php if($active_menu_sidebar=="mypage-post-image"){echo 'class="active"';} else {echo '';}?>><a href="<?php echo base_url()?>mypage/post-image.html"><?php echo $this->lang->line('add_picture'); ?></a></li>
                <li <?php if($active_menu_sidebar=="mypage-list-compe"){echo 'class="active"';} else {echo '';}?>><a href="<?php echo base_url()?>mypage/list-compe.html"><?php echo $this->lang->line('list_compe'); ?></a></li>
                <li <?php if($active_menu_sidebar=="mypage-post-compe"){echo 'class="active"';} else {echo '';}?>><a href="<?php echo base_url()?>mypage/post-compe.html"><?php echo $this->lang->line('post_compe'); ?></a></li>
                <li <?php if($active_menu_sidebar=="mypage-list-compe-join"){echo 'class="active"';} else {echo '';}?>><a href="<?php echo base_url()?>mypage/list-compe-join.html"><?php echo $this->lang->line('list_picture_join_competition'); ?></a></li>
            <?php }?>
                <li <?php if($active_menu_sidebar=="mypage-list-order"){echo 'class="active"';} else {echo '';}?>><a href="<?php echo base_url()?>mypage/list-order.html"><?php echo $this->lang->line('list_order'); ?></a></li>
                <li <?php if($active_menu_sidebar=="mypage-user-purchase-order"){echo 'class="active"';} else {echo '';}?>><a href="<?php echo base_url()?>mypage/user-purchase-order.html"><?php echo $this->lang->line('credit_purchase_history'); ?></a></li>
                <?php if(($user->user_paymoney_getpoint + $user->user_point)>0) {?>
                <li <?php if($active_menu_sidebar=="mypage-user-manager-point"){echo 'class="active"';} else {echo '';}?>><a href="<?php echo base_url()?>mypage/user-manager-point.html"><?php echo $this->lang->line('sidebar_manager_point'); ?></a></li>
                <?php }?>
                <li <?php if($active_menu_sidebar=="mypage-user-change-bank_account"){echo 'class="active"';} else {echo '';}?>><a href="<?php echo base_url()?>mypage/user-change-bank-account.html"><?php echo $this->lang->line('sidebar_manager_bank_transfer'); ?></a></li>
                <li <?php if($active_menu_sidebar=="mypage-user-manager-card"){echo 'class="active"';} else {echo '';}?>><a href="<?php echo base_url()?>mypage/user-manager-card.html"><?php echo $this->lang->line('sidebar_manager_card_buy_point'); ?></a></li>
                <li <?php if($active_menu_sidebar=="mypage-history-view-image"){echo 'class="active"';} else {echo '';}?>><a href="<?php echo base_url()?>mypage/history-view-image.html"><?php echo $this->lang->line('list_picture_history'); ?></a></li>
                <li <?php if($active_menu_sidebar=="mypage-favorite-image"){echo 'class="active"';} else {echo '';}?>><a href="<?php echo base_url()?>mypage/favorite-image.html"><?php echo $this->lang->line('list_picture_favorite'); ?></a></li>
                <li <?php if($active_menu_sidebar=="mypage-favorite-user"){echo 'class="active"';} else {echo '';}?>><a href="<?php echo base_url()?>mypage/favorite-user.html"><?php echo $this->lang->line('list_user_favorite'); ?></a></li>
            <li <?php if($active_menu_sidebar=="mypage-user-message"){echo 'class="active"';} else {echo '';}?>><a href="<?php echo base_url()?>mypage/user-message.html" class="menu-message"><?php echo $this->lang->line('notice'); ?></a></li>
                <li <?php if($active_menu_sidebar=="mypage-user-config"){echo 'class="active"';} else {echo '';}?>><a href="<?php echo base_url()?>mypage/user-config.html"><?php echo $this->lang->line('edit_information_registered'); ?></a></li>
                <li <?php if($active_menu_sidebar=="mypage-user-email"){echo 'class="active"';} else {echo '';}?>><a href="<?php echo base_url()?>mypage/user-email.html"><?php echo $this->lang->line('change_email_address'); ?></a></li>
                <li <?php if($active_menu_sidebar=="mypage-user-pass"){echo 'class="active"';} else {echo '';}?>><a href="<?php echo base_url()?>mypage/user-password.html"><?php echo $this->lang->line('change_password'); ?></a></li>
                <li <?php if($active_menu_sidebar=="mypage-user-adwords"){echo 'class="active"';} else {echo '';}?>><a href="<?php echo base_url()?>mypage/user-manager-adwords.html"><?php echo $this->lang->line('user_manager_adwords'); ?></a></li>
                <li <?php if($active_menu_sidebar=="mypage-user-leave"){echo 'class="active"';} else {echo '';}?>><a href="<?php echo base_url()?>mypage/user-leave.html"><?php echo $this->lang->line('withdraw'); ?></a></li>
            <li <?php 
			// account level 2:Creator, 3:Gold, 4:Platinum, 5:Enterprise
			if($active_menu_sidebar=="mypage-user-update-account"){echo 'class="active"';} else {echo '';}?>><a href="<?php echo base_url()?>mypage/user-update-account.html" class="menu-creator"><?php echo $this->lang->line('sider_bar_update_account'); ?></a></li>
                            <li><a href="<?php echo base_url(); ?>logout.html"><?php echo $this->lang->line('logout'); ?></a></li>

          </ul>
    <?php } else {?>
    		<ul>
            <li><a href="<?php echo base_url(); ?>login.html"><?php echo $this->lang->line('login'); ?></a></li>
            <li><a href="<?php echo base_url(); ?>entry.html"><?php echo $this->lang->line('new_member_registration'); ?></a></li>
            <li><a href="<?php echo base_url(); ?>creator-entry.html"><?php echo $this->lang->line('creator_member_registration'); ?></a></li>
          </ul>
    <?php } ?>
        </div>
      </nav>
    </div>
  </header>





<script>

$(document).ready(function() {

	$('#all').change(function() {
		var checkboxes = $(this).closest('myform').find(':checkbox');
		if($(this).is(':checked')) {
			$('.checked').attr('checked', 'checked');
		} else {
			$('.checked').removeAttr('checked');
		}
	});	

	$("#btnDeleteAll").click(function(){

		

		var id_imgs = [];

		$('div.mypage-img-list input[type=checkbox]').each(function() {

		   if ($(this).is(":checked")) {

			   id_imgs.push($(this).attr('value'));

		   }

		});
				

		if(id_imgs.length > 0){

			

			var result = confirm("<?php echo $this->lang->line('are_you_sure_delete_image'); ?>");

			if(result){

				

				$.ajax({

					type: "POST",cache: false,

					url: "<?php echo base_url(); ?>ajax/delete_all_image",

					data: "id_imgs="+id_imgs,

					success: function(data) {

						location.reload();

					},

					async: false

				})

				

			}

				

		}

		else{

			alert("<?php echo $this->lang->line('please_choose_image'); ?>");

		}

	

	});

		

});



function delete_image(id_img){

	

	var result = confirm("<?php echo $this->lang->line('are_you_sure_delete_image'); ?>");

	if(result){

		

		$.ajax({

			type: "POST",cache: false,

			url: "<?php echo base_url(); ?>ajax/delete_all_image",

			data: "id_imgs="+id_img,

			success: function(data) {

				location.reload();

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
					window.location.reload();
				}
			},
			async: false
		})
		
	}
	
}

</script>

<div class="contents-mypage">

    <div class="contents-mypage__wrapper clearfix">

      <div class="breadly">

        <ul>

          <li><a href="<?php echo base_url()?>"><span itemprop="title"><?php echo $this->lang->line('home'); ?></span></a></li>

          <li><a href="<?php echo base_url()?>mypage.html"><span itemprop="title"><?php echo $this->lang->line('mypage'); ?></span></a></li>

          <li><span itemprop="title"><?php echo $this->lang->line('mypage_favorite'); ?></span></li>

        </ul>

      </div>

      <?php $this->load->view('user/sidebar_user.php') ?>

      <style>
		@media only screen and (max-width: 600px) {
			.mypage-img-list__info .checkbox{
				width: 33.33% !important;
			}
			.mypage-img-list__info .delete{
				width: 33.33% !important;
				border-right: 1px solid #e6eced;
				padding: 9px 0 0 !important;
			}
		}
      </style>

      <div class="mypage-main">

      <h1 class="contents-mypage__find"><?php echo $this->lang->line('list_picture'); ?></h1>
      
      <?php
if(!empty($list_images)){
?>            

      <div class="mypage-favorite-menu">

              <div class="mypage-favorite-menu__select">

                <label id="favorite-all-select">

                  <input type="checkbox" id="all" name="all"><?php echo $this->lang->line('select_all'); ?>

                </label>

              </div>

              <div id="btnDeleteAll" class="mypage-favorite-menu__delete"><span><?php echo $this->lang->line('del'); ?></span></div>

            </div>

<div class="mypage-img-list">

    <div>You've selected: <span id="selected_num">0</span> images.</div>
    
    <ul class="image_list" id="post-data">
    
    	<?php foreach($list_images as $detail_list_images){?>
    	
        <li class="mypage-img-list__list" id="li_<?php echo $detail_list_images->id_img;?>">
        	
            <div class="mypage-img-list__wrapper">
            	<div class="mypage-img-list__inner">
                	<a href="<?php echo base_url()?>detail/<?php echo $detail_list_images->img_code; ?>.html"><img src="<?php echo $detail_list_images->server_path_upload;?>/<?php echo $detail_list_images->file_name_watermark_img_1; ?>" alt="<?php echo $detail_list_images->img_title;?>"></a>
              	</div>
                <div class="mypage-img-list__info">
                <?php
					echo "user_id1 = ".$user_id."<br>";
					echo "user_id2 = ".$detail_list_images->user_id."<br>";
					if($user_id == $detail_list_images->user_id){//hinh anh cua ban	
						if($detail_list_images->img_is_join_compe == 0){//chua applied
							$time_remaining = $this->manager_image_model->get_time_remaining($detail_list_images->id_img);
							if($time_remaining == -1){//het han
								$str = $this->lang->line('expired');
							}
							else if($detail_list_images->img_is_sold == 1){//khong con san pham de ban
								$str = $this->lang->line('out_of_stock');
							}
							else{
								$str = "";
							}
				?>
                    <p class="applied applied-left"><?php echo $str; ?></p><br>
                    <label class="checkbox">
                    	<input type="checkbox" class="checked" name="check[]" value="<?php echo $detail_list_images->id_img;?>" >
                    </label>
                    <div class="delete" onclick="delete_image(<?php echo $detail_list_images->id_img;?>)"><span>x</span></div>
                    <div class="delete applied-delete">
                    	<span><a href="<?php echo base_url()?>mypage/edit-image/<?php echo $detail_list_images->id_img;?>"><i class="fa fa-pencil" aria-hidden="true"></i>
</a></span>
                    </div>
                <?php
						}
						else{
							$str = $this->lang->line('applied');
				?>
                			<p class="applied"><?php echo $this->lang->line('applied'); ?></p><br>
                            <!--<label class="checkbox">
                            	<input type="checkbox" class="checked" name="check[]" value="" style="" >
                            </label>-->
                            <!--<div class="delete" onclick="remove_image_sell(264, 2)"><span>x</span></div>-->
                            <!--<div class="delete applied-delete">
                            	<span><i class="fas fa-pencil-alt" style="color:#000"></i></span>
                            </div>-->
                <?php
						}
					}
					else{//hinh anh cua nguoi khac => co the reove get
					
						$str = $this->lang->line('image_got');
					
				?>
                	<p class="applied applied-left"><?php echo $this->lang->line('image_got'); ?></p><br>
                    <!--<label class="checkbox">
                    	<input type="checkbox" class="checked" name="check[]" value="" style="" >
                    </label>-->
                    <div class="delete">
                    	<span onclick="remove_image_sell(<?php echo $detail_list_images->id_img;?>, <?php echo $user_id; ?>)">x</span>
                  	</div>
                    <!--<div class="delete applied-delete">
                    	<span><i class="fas fa-pencil-alt" style="color:#000"></i></span>
                    </div>-->
                <?php
					}
				?>
                	
                    <?php
						$is_mobile = $CI->function_model->isMobile();
						if($is_mobile == 1){
					?>
                	<p class="show-mobile" style="margin:-7px 0px 0px 10px;display:block;position:absolute;color:red;"><?php echo $str; ?></p>
                	<?php
						}
					?>
                
                </div>
          	</div>
            
        </li>
        
        <?php
			}
		?>
        
    </ul>


</div>

						<?php
							if($count_image > $num_paging){
						?>

                    
                    <div class="btn-view-more list-img__next-btn"><a id="load_page" onclick="loadMoreData(<?php echo $_SESSION['scroll_list_images']?>);"><?php echo $this->lang->line('view_more'); ?></a></div>
                    
 <?php
							}

						}
						else{
					?>
                    		
                            <p class="red"><?php echo $this->lang->line('you_do_not_have_image'); ?></p>
                            <div class="btn-post-image list-img__next-btn"><a href="<?php echo base_url(); ?>mypage/post-image" target="_blank"><?php echo $this->lang->line('post_image'); ?></a></div>
                            
                    <?php
						}

					?> 
        
        
        
        

             			 
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                  
                    
                    
                    

          </div>
			
            
            
      

    </div>

  </div>
  
  
<script type="text/javascript">

	var page = 0;
	var count_image = '<?php echo $count_image; ?>';
	var page_now = 1;
	
	function loadMoreData(page){
		
	  $.ajax(
	        {
	            url: '<?php echo base_url(); ?>mypage/list-images.html?page=' + page,
	            type: "get",
	            beforeSend: function()
	            {
	                $('.btn-view-more').show();
	            }
	        })
	        .done(function(data)
	        {
				
	            if(data == ""){
	                $('.btn-view-more').html("No more records found");
	                return;
	            }
				
				if((page*num_paging)<(count_image-num_paging))
				{
	            	$('.btn-view-more').show();
					var page_onlick = page + 1;
					page_now = page_onlick;
					document.getElementById('load_page').setAttribute("onClick","loadMoreData("+page_onlick+");");
				}
				else
				{
					$('#load_page').hide();
				}
				
			
	            $("#post-data").append(data);
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
							echo '<li><a href="'.base_url().$detail_menu_bottom->link_url.'">'.$detail_menu_bottom->menu_ja.'</a></li>';
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
        <!--<div class="g-footer__language"><span><?php //echo $this->lang->line('language'); ?></span>
          <select id="language" name="language">
            <option value="1" <?php //echo ($_SESSION['lang'] == "ja") ? "selected='selected'" : ""; ?>><?php //echo $this->lang->line('ja'); ?></option>
            <option value="2" <?php //echo ($_SESSION['lang'] == "vi") ? "selected='selected'" : ""; ?>><?php //echo $this->lang->line('vi'); ?></option>
            <option value="3" <?php //echo ($_SESSION['lang'] == "en") ? "selected='selected'" : ""; ?>><?php //echo $this->lang->line('en'); ?></option>
          </select>
        </div>-->
        <small class="copyright"> <?php echo $this->lang->line('copyright'); ?> </small> </div>
    </div>
  </footer>
</div>
<div class="sp-menu">
  <div class="sp-menu__wrapper">
    <div class="sp-menu__search">
      <form action="<?php echo base_url(); ?>search" method="get">
        <input name="key_search" type="text" placeholder="&nbsp;&nbsp;検索">
        <input name="cate" type="hidden" value="all">
      </form>
    </div>
    <div class="sp-menu__inner">
      <div class="sp-menu__list">
        <ul>
          <li class="sp-menu__cart"><a href="<?php echo base_url(); ?>cart.html"><?php echo $this->lang->line('cart'); ?></a></li>
          <li class="sp-menu__favorite"><a href="<?php echo base_url(); ?>mypage/favorite-image.html"><?php echo $this->lang->line('mypage_favorite'); ?></a></li>
          <li><a href="<?php echo base_url(); ?>mypage.html"><?php echo $this->lang->line('mypage'); ?></a></li>
          <li><a href="<?php echo base_url(); ?>category.html"><?php echo $this->lang->line('photo_mypage_category'); ?></a></li>
          <li><a href="<?php echo base_url(); ?>guide.html"><?php echo $this->lang->line('user_guide'); ?></a></li>
          <li><a href="<?php echo base_url(); ?>qa.html"><?php echo $this->lang->line('frequently_asked_questions'); ?></a></li>
        </ul>
      </div>
    </div>
  </div>
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

<!--drag and drop-->
<script type="text/javascript" src="<?php echo base_url(); ?>publics/drag_and_drop/js/main.js" charset="UTF-8"></script>
<script src="<?php echo base_url(); ?>publics/drag_and_drop/js/jquery-1.11.0.min.js"></script>
<script src="<?php echo base_url(); ?>publics/drag_and_drop/js/jquery-migrate-1.2.1.min.js"></script>
<script src="<?php echo base_url(); ?>publics/drag_and_drop/js/jquery-ui.min.js"></script>

<script>

$(document).ready(function () {

	var selected_image_ids = new Array;

	// keep track of what images we checked off
	$(".image_checkbox").click(function () {

		if ($(this).attr("checked") == "checked")
		{		
			selected_image_ids.push($(this).attr("id"));
		}
		else {
			// remove the items from the array - far too complex for what it needs to do.
			for(var i = selected_image_ids.length-1; i >= 0; i--)
			{
				if(selected_image_ids[i] == $(this).attr("id")){
					selected_image_ids.splice(i,1);
				}
			}
		}

		$("#selected_num").html(selected_image_ids.length);
	})


	$(".drop_div ul li").droppable({
		
		accept: ".image_list li",
        hoverClass: "active",
		revert: true,
		drop: function( event, ui ) {


			$(ui.helper).remove(); //destroy clone
            $(ui.draggable).remove(); //remove from list

			var dropped_object = $(this);
			var image_collection = dropped_object.context.innerText;

			$("#status").html("Success! You've moved: " + selected_image_ids.length + " images to: " + image_collection);

			for(var i = selected_image_ids.length-1; i >= 0; i--)
			{
				$("#image_" + selected_image_ids[i]).parent().parent().fadeOut();
			}

			selected_image_ids = new Array;

		}
	});


	$(".image_list").sortable({

		refreshPositions: true,
		scroll: true,
		delay: 100,
		cursor: "move",
		revert: true,
		helper: "clone",

		start: function (event, ui)
		{
			
			
			
			if (selected_image_ids.length > 1) {

				var image_string = "";
				var max_images = 2;
				var class_counter = 0;

				if (selected_image_ids.length == 2) {
					max_images = 1;
				}

				for (var i=0; i<=max_images; i++)
				{
					var image_link = $("#image_" + selected_image_ids[i]).attr("src");
					class_counter = i+1;
					image_string += '<img src="' + image_link + '" class="photo_helper" id="photo_helper' + class_counter + '" />'
				}

				$(ui.helper).html('<div id="photoHelper">' + image_string + '</div>');
			}
			

		},
		
		stop: function(event, ui) {
			
			var str_image_list = $(".image_list").sortable('toArray');
			
			$.ajax({
				type: "POST",cache: false,
				url: "<?php echo base_url(); ?>ajax/drag_and_drop",
				data: "str_image_list="+str_image_list+"&page_now="+page_now+"&user_id="+user_id,
				success: function(data) {
					//alert(data);
				},
				async: false
			})
			
		}
		
	});

})

</script>

<!--end drag and drop-->