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
<!DOCTYPE html>
<html lang="<?php echo $_SESSION['lang']; ?>">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1, maximum-scale=1">
<?php if(isset($meta_seo['code_call']) && @$meta_seo['code_call']=='law') {?>
<meta name="robots" content="noindex,follow" />
<?php } ?>
<?php if(isset($meta_seo['code_call']) && @$meta_seo['code_call']=='company') {?>
<meta name="robots" content="noindex,follow" />
<?php } ?>
<meta name="description" content="<?php echo isset($meta_seo['description_page']) ? $meta_seo['description_page'] : ""; ?>">
<meta name="keywords" content="<?php echo isset($meta_seo['keywords']) ? $meta_seo['keywords'] : ""; ?>">
<meta property="og:title" content="<?php echo isset($meta_seo['title_page']) ? $meta_seo['title_page'] : ""; ?>">
<?php if(isset($noindex) && @$noindex=='1') {?>
<meta name="robots" content="noindex" />
<?php } ?>
<meta name="google-site-verification" content="j16YBWHZvpnCXtl9hXM242usQBwPwUBjsOfsW8O49dQ" />
<meta property="og:url" content="<?php echo (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";?>" />
<meta property="og:type" content="website">
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

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-119422182-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-119422182-1');
</script>

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
nav #login-trigger,
nav #signup a {
  display: inline-block;
}
nav #login-content {
  display: none;
  position: absolute;
  top: 64px;
  right: 0;
  z-index: 999;    
  background: #eee;
  padding: 15px;
  -moz-box-shadow: 0 2px 2px -1px rgba(0,0,0,.9);
  -webkit-box-shadow: 0 2px 2px -1px rgba(0,0,0,.9);
  box-shadow: 0 2px 2px -1px rgba(0,0,0,.9);
  -moz-border-radius: 3px 0 3px 3px;
  -webkit-border-radius: 3px 0 3px 3px;
  border-radius: 3px 0 3px 3px;
}
.g-nav a{
  padding: 0 19px !important;
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
<div id="container">
  <header class="g-header">
    <div class="g-header__wrapper">
      <h1 class="site-name"><a href="<?php echo base_url(); ?>"><img src="<?php echo base_url(); ?>publics/img/logo.png" alt="LUXYART"></a></h1>
      <nav class="g-nav">
        <ul>
        	<?php
		  	if(!empty($logged_in)){ echo '<li><a href="'.base_url().'timeline/'.$logged_in['user_id'].'.html">タイムライン</a></li>'; }
		  ?>
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
          		<li><a href="<?php echo base_url(); ?>mypage.html"><i class="fa fa-user" aria-hidden="true"></i>&nbsp;<?php echo $logged_in['user_display_name']; ?></a></li>
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
                
                <li id="g-nav-login-btn">
                	
                    <a id="login-trigger" href="#">
                        <span><?php echo $this->lang->line('login'); ?>&#x25BC;</span>
                    </a>
                    <div id="login-content">
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
    $name_call = $logged_in['user_display_name'];
    $CI->load->model('manager_user_model');
    $user = $CI->manager_user_model->get_user_id($user_id);
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
                <?php //if(($user->user_paymoney_getpoint + $user->user_point)>0) {?>
                <li <?php if($active_menu_sidebar=="mypage-user-manager-point"){echo 'class="active"';} else {echo '';}?>><a href="<?php echo base_url()?>mypage/user-manager-point.html"><?php echo $this->lang->line('sidebar_manager_point'); ?></a></li>
                <?php //}?>
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
            <li><a href="<?php echo base_url()?>logout.html"><?php echo $this->lang->line('logout'); ?></a></li>
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
  <?php echo $body; ?>
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
        <!--<div class="g-footer__language"><span><?php echo $this->lang->line('language'); ?></span>
          <select id="language" name="language">
            <option value="1" <?php echo ($_SESSION['lang'] == "ja") ? "selected='selected'" : ""; ?>><?php echo $this->lang->line('ja'); ?></option>
            <option value="2" <?php echo ($_SESSION['lang'] == "vi") ? "selected='selected'" : ""; ?>><?php echo $this->lang->line('vi'); ?></option>
            <option value="3" <?php echo ($_SESSION['lang'] == "en") ? "selected='selected'" : ""; ?>><?php echo $this->lang->line('en'); ?></option>
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
          
          <?php
		  	if(!empty($logged_in)){ echo '<li><a href="'.base_url().'timeline/'.$logged_in['user_id'].'.html">タイムライン</a></li>'; }
		  ?>
          
          <li><a href="<?php echo base_url(); ?>category.html"><?php echo $this->lang->line('photo_mypage_category'); ?></a></li>
          <li><a href="<?php echo base_url(); ?>page/guideline.html"><?php echo $this->lang->line('user_guide'); ?></a></li>
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

<script>
  	$(document).ready(function(){
		$('#login-trigger').click(function(){
			$(this).next('#login-content').slideToggle();
			$(this).toggleClass('active');
			if ($(this).hasClass('active')){
				$(this).find('span').html('<?php echo $this->lang->line('login'); ?>&#x25B2;')
			}
			else{
				$(this).find('span').html('<?php echo $this->lang->line('login'); ?>&#x25BC;')
			}
		})
	});
</script>