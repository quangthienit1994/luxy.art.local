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
.img-new {
    position: absolute;
	margin: 0;
    width: 50px !important;
    height: 29px !important;
	right:0;
}
.g-nav a {
	padding: 0 20px !important;
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
<style>
#container-01,
#container-02 {
    margin:10px auto;}
.pinto {
    box-sizing: border-box;
	float:left;
	width:252px;
}
.pinto {
    margin-bottom:5px;
    color:#333;
    text-align:left;
    overflow:hidden;
}
.list-img__favorite:hover
{
	   display: block;
}
.pinto img {
    display:block;
    width:252px;
}
.pinto .info {
    padding:10px 15px;
    background-color:#eee;
}
.pinto .info h2 {
    margin:0;
    font-size:18px;
}
.pinto .info p {
    margin:10px 0 0;
    font-size:14px;
}
</style>
<script>
var user_id = "<?php echo $user_id; ?>";
var id_competition = "<?php echo $detail_compe->id_competition; ?>";
var date_start_com = "<?php echo $detail_compe->date_start_com; ?>";
var date_end_com = "<?php echo $detail_compe->date_end_com; ?>";
var is_apply_competition = "<?php echo $is_apply_competition; ?>";
var is_private = "<?php echo $detail_compe->is_private; ?>";
var user_level = "<?php echo $detail_user->user_level; ?>";
$(document).ready(function() {
	
});
function apply_competition(){
	if(user_id == ""){
		window.location.href = "<?php echo base_url(); ?>login.html";
		return;
	}
	
	if(is_private == 1){
		if(user_level < 3){
			msg = "<?php echo $this->lang->line('please_update_account_gold_for_apply_competition'); ?>";
			if(confirm(msg)){
				window.location.href = "<?php echo base_url(); ?>mypage/user-update-account.html";
			}
			return false;	
		}
	}
	else{
		if(user_level < 2){
			msg = "<?php echo $this->lang->line('please_update_account_creator_for_apply_competition'); ?>";
			if(confirm(msg)){
				window.location.href = "<?php echo base_url(); ?>mypage/user-update-account.html";
			}
			return false;
		}
	}
	if(is_apply_competition == 0){
		msg = "<?php echo $this->lang->line('please_apply_competition_within'); ?>";
		msg = msg.replace("{date_start_com}", date_start_com);
		msg = msg.replace("{date_end_com}", date_end_com);
		alert(msg);
		return false;
	}
	var result = confirm("<?php echo $this->lang->line('are_you_sure_apply_competition'); ?>");
	if(result){
		
		$.ajax({
			type: "POST",
			cache: false,
			url: "<?php echo base_url(); ?>ajax/add_code_apply_competition",
			data: "id_competition=" + id_competition + "&user_id=" + user_id,
			success: function(data) {
				if(data == 0){
					window.location.href = "<?php echo base_url(); ?>login.html";
				}
				else if(data == 1){
					window.location.href = "<?php echo base_url(); ?>mypage/list-images-choose.html";
				}
				else if(data == 2){
					window.location.href = "<?php echo base_url(); ?>mypage/user-update-creator.html";
				}
			}
		});
		
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
</script>
<div class="contents-compe-detail">
    <div style="background: url(<?php echo base_url(); ?>publics/competition_img/<?php echo $detail_compe->photo_des_com; ?>) center center;background-size: cover" class="ttl-list">
      <div class="ttl-list__filter">
        <div class="ttl-list__box">
          <h1><?php echo $detail_compe->title_com; ?></h1>
        </div>
      </div>
    </div>
    <div class="compe-detail-main">
      <div class="compe-detail-header">
        <div class="compe-detail-header__wrapper">
          <div class="compe-detail-header__user-meta">
            <div class="compe-detail-header__icon">
            	<img alt="<?php echo $detail_compe->display_name; ?>" src="<?php echo base_url(); ?>publics/avatar/<?php echo ($detail_compe->user_avatar != "") ? $detail_compe->user_avatar : "prof-img.png"; ?>">
           	</div>
            <div class="compe-detail-header__name"><a href="<?php echo base_url(); ?>timeline/<?php echo $detail_compe->user_id; ?>.html"><?php echo $detail_compe->display_name; ?></a></div>
          </div>
          <div class="compe-detail-header__info">
            <div class="compe-detail-header__price">
              <p><span><?php echo $this->lang->line('prize'); ?></span>&nbsp;<?php echo $detail_compe->point_img_com*$detail_compe->img_quantity_com; ?><?php echo $this->lang->line('point'); ?></p>
            </div>
            <div class="compe-detail-header__limit">
              <p><span><?php echo $this->lang->line('application_reriod'); ?></span>&nbsp;
			  	<?php
					/*if($hour < 0){
                		echo $this->lang->line('expired');
					}
					else{
						echo abs($hour).$this->lang->line('day');
					}*/
					
					echo $CI->manager_compe_model->get_time_remain_apply($detail_compe->date_end_com);
				?>
               </p>
              <p class="compe-detail-header__limit-time">
                <?php
					if($this->lang_id == 1){
				?>
              			<?php echo date('Y/m/d', strtotime($detail_compe->date_end_com)); ?>(
						<?php
                        	$w = date('w', strtotime($detail_compe->date_end_com));
							switch ($w) {
								case 0:
									echo $this->lang->line('sunday');
									break;
								case 1:
									echo $this->lang->line('monday');
									break;
								case 2:
									echo $this->lang->line('tuesday');
									break;
								case 3:
									echo $this->lang->line('wednesday');
									break;
								case 4:
									echo $this->lang->line('thursday');
									break;
								case 5:
									echo $this->lang->line('friday');
									break;
								case 6:
									echo $this->lang->line('saturday');
									break;
							} 
						?>
                        ) 23:59 <?php echo $this->lang->line('to'); ?>
                <?php
					}
					else{
				?>
                		<?php echo $this->lang->line('to'); ?> <?php echo date('Y/m/d', strtotime($detail_compe->date_end_com)); ?>&nbsp;(
						<?php
                        	$w = date('w', strtotime($detail_compe->date_end_com));
							switch ($w) {
								case 0:
									echo $this->lang->line('sunday');
									break;
								case 1:
									echo $this->lang->line('monday');
									break;
								case 2:
									echo $this->lang->line('tuesday');
									break;
								case 3:
									echo $this->lang->line('wednesday');
									break;
								case 4:
									echo $this->lang->line('thursday');
									break;
								case 5:
									echo $this->lang->line('friday');
									break;
								case 6:
									echo $this->lang->line('saturday');
									break;
							} 
						?>
                        ) 23:59
                <?php
					}
				?>
              </p>
            </div>
          </div>
        </div>
      </div>
      <div class="compe-detail-guide">
        
        <h3><?php echo $this->lang->line('application_requirements'); ?></h3>
        <p><?php echo nl2br($detail_compe->note_require_com); ?></p>
        
        <h3><?php echo $this->lang->line('application_condition'); ?></h3>
        <p><?php echo nl2br($detail_compe->note_description_com); ?></p>
        
        <h3><?php echo $this->lang->line('desired_condition'); ?></h3>
        <dl>
          <dt><?php echo $this->lang->line('direction'); ?>：</dt>
          <dd>
          	<?php
				if($detail_compe->img_type_com == 1){
					echo $this->lang->line('landscape');
				}
				elseif($detail_compe->img_type_com == 2){
					echo $this->lang->line('portrait');
				}
			?>
          </dd>
          <dt><?php echo $this->lang->line('color'); ?>：</dt>
          <dd>
          	<?php
				$img_color_id = $detail_compe->img_color_id;
				$img_color_id_explode = explode(",",$img_color_id);
				for($i=0; $i<count($img_color_id_explode); $i++){
					
					$whereProductColor = array('product_color_id' => $img_color_id_explode[$i],'lang_id' => $this->lang_id);
					$orderProductColor = array();
					$detail_product_color = $CI->main_model->getAllData("luxyart_tb_product_color", $whereProductColor, $orderProductColor)->row();
					
					echo '<span class="color-'.$detail_product_color->name.'">'.$detail_product_color->name_color.'</span>&nbsp;';
				}
			?>
          </dd>
        </dl>
        
        <h3><?php echo $this->lang->line('use_applications'); ?></h3>
        <p><?php echo nl2br($detail_compe->note_img_purpose_com); ?></p>
        
        <h3><?php echo $this->lang->line('prize'); ?></h3>
        <p><?php echo $detail_compe->point_img_com; ?>&nbsp;<?php echo $this->lang->line('point'); ?>×<?php echo $detail_compe->img_quantity_com; ?>&nbsp;<?php echo $this->lang->line('each'); ?> = <?php echo $detail_compe->point_img_com*$detail_compe->img_quantity_com; ?>&nbsp;<?php echo $this->lang->line('point'); ?></p>
        
        <h3><?php echo $this->lang->line('schedule_of_competition'); ?></h3>
        <p>
        	<?php
				if($this->lang_id == 1){
			?>
        			<?php echo date('Y/m/d', strtotime($detail_compe->date_start_com)); ?>〜<?php echo date('Y/m/d', strtotime('-1 day', strtotime($detail_compe->date_end_com))); ?> 23:59 <?php echo $this->lang->line('until_the_application_period'); ?>、<?php echo date('Y/m/d', strtotime($detail_compe->date_end_com)); ?> ~ <?php echo date('Y/m/d', strtotime('-1 day', strtotime($detail_compe->date_time_agree))) ?> 23:59 <?php echo $this->lang->line('the_examination_period_will_be_between'); ?><br />
        	<?php
				}
				else{
			?>
            		<?php echo $this->lang->line('until_the_application_period'); ?>〜<?php echo date('Y/m/d', strtotime('-1 day', strtotime($detail_compe->date_end_com))); ?> 23:59, <?php echo $this->lang->line('the_examination_period_will_be_between'); ?>: <?php echo date('Y/m/d', strtotime($detail_compe->date_end_com)); ?> ~ <?php echo date('Y/m/d', strtotime('-1 day', strtotime($detail_compe->date_time_agree))); ?> 23:59<br />
            <?php
				}
			?>
            <?php echo $this->lang->line('content_winning_results'); ?>
       	</p>
        
        <h3><?php echo $this->lang->line('applicant_information'); ?></h3>
        <p><a href="<?php echo $detail_compe->link_url_com; ?>"><?php echo $detail_compe->link_url_com; ?></a></p>
        
        <h3><?php echo $this->lang->line('other_notes'); ?></h3>
        <p><?php echo nl2br($detail_compe->note_another_des_com); ?></p>
        
      </div>
      
      	<?php
			if($user_id != $detail_compe->user_id){
				if($detail_compe->status_com == 1 || $detail_compe->status_com == 2){
					echo "<div class='compe-detail-btn'><a onclick='apply_competition()'>".$this->lang->line('apply_for_competition_from_application')."</a></div>";
				}
			}
		?>
      
      
      <div class="compe-detail-img">
        <h2><?php echo $this->lang->line('photos_posted_to_this_competition'); ?></h2>
        
        <?php
			if($detail_compe->is_private_img_apply != 1){
		?>
        
        		<div class="list-img">
          <?php
		  	if(!empty($list_image_apply)){
		  ?>
          <div class="list-img__wrapper flex-images" id="container-01">
          	<?php
				foreach($list_image_apply as $detail_image_apply){
					
			?>
            <div class="pinto">
        <a href="<?php echo base_url()?>detail/<?php echo $detail_image_apply->img_code; ?>.html"><img alt="<?php echo $detail_image_apply->img_title; ?>" src="<?php echo $detail_image_apply->server_path_upload;?>/<?php echo $detail_image_apply->file_name_watermark_img_1;?>"></a>
        </div>
            <?php
				}
			?>
          </div>
          <div class="list-img__next-btn"><a id="load_page" onclick="loadMoreData(<?php echo $_SESSION['scroll_detail_compe']?>);"><?php echo $this->lang->line('view_more'); ?></a></div>
          <?php
			}
			else{
				if($detail_compe->status_com == 3){
					echo $this->lang->line('competition_finished');
				}
				else{
					echo $this->lang->line('no_pictures_yet');
				}
			}
		  ?>
        </div>
        
        <?php
			}
			else{
				
				echo $this->lang->line('photos_apply_is_private');
				
			}
		?>
        
      </div>
      
      <div class="compe-detail-other">
        <h2><?php echo $this->lang->line('other_competitions_currently_in_progress'); ?></h2>
        <div class="compe-list">
         <ul>
          
          	<?php
				foreach($list_compe_other as $detail_compe_other){
					
					$user_fullname = $CI->function_model->get_fullname($detail_compe_other->user_id,$this->lang_id);
					$time_remain_competition = $CI->manager_compe_model->get_time_remain_competition($detail_compe_other->id_competition);
					
			?>
            		<li><a href="<?php echo base_url(); ?>compe-detail/<?php echo $detail_compe_other->id_competition; ?>">
                        <div class="compe-list__thumb">
                        	<?php
								if($detail_compe_other->is_private == 1){
									if($this->lang_id == 1){
										echo '<img alt="ja" class="img-new" src="'.base_url().'publics/img/icon-private-ja.png" />';
									}
									else if($this->lang_id == 2){
										echo '<img alt="vi" class="img-new" src="'.base_url().'publics/img/icon-private-vi.png" />';
									}
									else if($this->lang_id == 3){
										echo '<img alt="en" class="img-new" src="'.base_url().'publics/img/icon-private-en.png" />';
									}
								}
							?>
                            
                            <?php
								if($detail_compe_other->photo_des_com != ""){
							?>
								<img alt="<?php echo $detail_compe_other->title_com; ?>" src="<?php echo base_url(); ?>publics/competition_img/<?php echo $detail_compe_other->photo_des_com; ?>">
							<?php
								}
								else{
							?>
								<img alt="<?php echo $detail_compe_other->title_com; ?>" src="<?php echo base_url(); ?>publics/img/no_image_available.jpg">
							<?php	
								}
							?>
                            
                            </div>
                            
                        <div class="compe-list__meta">
                          <div class="compe-list__icon"><img alt="<?php echo $detail_compe_other->display_name; ?>" src="<?php echo base_url(); ?>publics/avatar/<?php echo ($detail_compe_other->user_avatar != "") ? $detail_compe_other->user_avatar : "prof-img.png"; ?>"></div>
                          <p class="compe-list__ttl"><?php echo $detail_compe_other->title_com; ?></p>
                          <p class="compe-list__name"><?php echo $user_fullname; ?></p>
                        </div>
                        <div class="compe-list__footer">
                          <div class="compe-list__price"><span><?php echo $this->lang->line('prize'); ?></span>&nbsp;<?php echo $detail_compe_other->point_img_com*$detail_compe_other->img_quantity_com ?><?php echo $this->lang->line('point'); ?></div>
                          <div class="compe-list__limit"><span><?php echo $this->lang->line('remaining'); ?></span>&nbsp;
						  <?php echo $CI->manager_compe_model->get_time_remain_apply($detail_compe_other->date_end_com); ?>
						  </div>
                        </div></a></li>
            <?php
				}
			?>
            
          </ul>
          <div class="compe-list__more-btn"><a href="<?php echo base_url(); ?>list-compe.html"><?php echo $this->lang->line('view_more'); ?></a></div>
        </div>
      </div>
    </div>
  </div>
<script type="text/javascript" src="<?php echo base_url(); ?>publics/test/imagesloaded.pkgd.min.js" ></script>
<script type="text/javascript" src="<?php echo base_url(); ?>publics/test/jquery.pinto.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>publics/test/main.js"></script>
<script type="text/javascript">
	var page =0;
	var count_compe = '<?php echo $count_compe?>';
	var loc = "";
	function loadMoreData(page){
		
	  $.ajax(
	        {
	            url: '<?php echo base_url(); ?>compe-detail/<?php echo $detail_compe->id_competition; ?>.html?page=' + page,
	            type: "get",
	            beforeSend: function()
	            {
	                $('.compe-list__more-btn').show();
	            }
	        })
	        .done(function(data)
	        {
				
				
				
	            if(data == " "){
	                $('.compe-list__more-btn').html("No more records found");
	                return;
	            }
				
				
				if((page*4)<(count_compe-4))
				{
	            	$('.compe-list__more-btn').show();
					var page_onlick = page + 1;
					document.getElementById('load_page').setAttribute("onClick","loadMoreData("+page_onlick+");");
				}
				else
				{
					$('#load_page').hide();
				}

	            $("#container-01").append(data).trigger( "create" );
				
				$("#container-01").imagesLoaded(function () {
        $("#container-01").pinto();
    });
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