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
.flex-images .item img{
	height: 240px !important;
}
</style>

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
	max-width:252px;
	float:left;
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

<div class="compe-top-mainvisual">
    <div class="compe-top-mainvisual__filter">
      <h2 class="compe-top-mainvisual__find"><?php echo $this->lang->line('full_original_image'); ?></h2>
      <p><?php echo $this->lang->line('content_full_original_image'); ?></p>
      <div class="compe-top-mainvisual__btn"><a href="<?php echo base_url(); ?>mypage/post-compe.html"><?php echo $this->lang->line('to_the_competition_holding_form'); ?></a></div>
    </div>
  </div>
  <div class="compe-top-guideline">
    <div class="compe-top-guideline__wrapper">
      <h2 class="compe-top__find"><?php echo $this->lang->line('luxyart_competition_features'); ?></h2>
      <div class="compe-top-guideline__list">
        <ul>
          <li>
            <div class="compe-top-guideline__box">
              <p class="compe-top-guideline__find"><span>1</span><?php echo $this->lang->line('cheap_at_reasonable_price'); ?></p>
              <div class="compe-top-guideline__thumb"><img alt="<?php echo $this->lang->line('cheap_at_reasonable_price'); ?>" src="<?php echo base_url(); ?>publics/img/compe-img1.jpg"></div>
              <p><?php echo $this->lang->line('content_cheap_at_reasonable_price'); ?></p>
            </div>
          </li>
          <li>
            <div class="compe-top-guideline__box">
              <p class="compe-top-guideline__find"><span>2</span><?php echo $this->lang->line('i_am_just_waiting'); ?></p>
              <div class="compe-top-guideline__thumb"><img alt="<?php echo $this->lang->line('i_am_just_waiting'); ?>" src="<?php echo base_url(); ?>publics/img/compe-img2.jpg"></div>
              <p><?php echo $this->lang->line('content_i_am_just_waiting'); ?></p>
            </div>
          </li>
          <li>
            <div class="compe-top-guideline__box">
              <p class="compe-top-guideline__find"><span>3</span><?php echo $this->lang->line('abundant_model'); ?></p>
              <div class="compe-top-guideline__thumb"><img alt="<?php echo $this->lang->line('abundant_model'); ?>" src="<?php echo base_url(); ?>publics/img/compe-img3.jpg"></div>
              <p><?php echo $this->lang->line('content_abundant_model'); ?></p>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </div>
  <div class="compe-top-compe">
    <div class="compe-top-compe__wrapper">
      <h2 class="compe-top__find"><?php echo $this->lang->line('competition_in_progress'); ?></h2>
      <div class="compe-list" >
        <ul id="post-data">
        <?php
		  $this->load->view('compe/data_compe_load', $list_compe_other);
		?>
        </ul>
        
        <?php
			if($count_compe > 3){
		?>
        <div id="load_page" class="compe-list__more-btn" onclick="loadMoreData(<?php echo $_SESSION['load_page']?>);"><a><?php echo $this->lang->line('view_more'); ?></a></div>
        <?php
			}
		?>
        
      </div>
    </div>
  </div>
  <div class="compe-top-list">
    <div class="compe-top-list__wrapper">
      <h2 class="compe-top__find"><?php echo $this->lang->line('new_photographs_currently_being_submitted_to_the_competition'); ?></h2>
      
      <!--<div class="list-img">
        <div class="list-img__wrapper flex-images">
          <?php
		  	/*if(!empty($list_image_apply)){
				foreach($list_image_apply as $detail_image_apply){
		  ?>
          		<div data-w="219" data-h="240" class="item"><a href="<?php echo base_url()?>detail/<?php echo $detail_image_apply->img_code; ?>.html"><img src="<?php echo $detail_image_apply->server_path_upload;?>/<?php echo $detail_image_apply->file_name_original_img;?>"></a></div>	
          <?php
				}
			}*/
		  ?>
        </div>
      </div>-->
      
      <div class="list-img">
        <div id="container-02" class="list-img__wrapper flex-images">
          <?php
		  	if(!empty($list_image_apply)){
				foreach($list_image_apply as $detail_image_apply){
		  ?>
        <div class="pinto">
        <a href="<?php echo base_url()?>detail/<?php echo $detail_image_apply->img_code;?>.html"><img alt="<?php echo $detail_image_apply->img_title;?>" src="<?php echo $detail_image_apply->server_path_upload;?>/<?php echo $detail_image_apply->file_name_watermark_img_1;?>"></a>
        </div>
        <?php }}?>
        </div>
        <!--<div class="list-img__next-btn"><a href="list.html">次のページへ</a></div>-->
      </div>
      
    </div>
  </div>
  
<script type="text/javascript" src="<?php echo base_url(); ?>publics/test/jquery.min.js" ></script>
<script type="text/javascript" src="<?php echo base_url(); ?>publics/test/imagesloaded.pkgd.min.js" ></script>
<script type="text/javascript" src="<?php echo base_url(); ?>publics/test/jquery.pinto.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>publics/test/main.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
<script type="text/javascript">

	var page =0;
	var count_compe = '<?php echo $count_compe?>';
	function loadMoreData(page){
	  $.ajax(
	        {
	            url: '<?php echo base_url(); ?>list-compe.html?page=' + page,
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
				if((page*3)<(count_compe-3))
				{
	            	$('.compe-list__more-btn').show();
					var page_onlick = page + 1;
					document.getElementById('load_page').setAttribute("onClick","loadMoreData("+page_onlick+");");
				}
				else
				{
					$('.compe-list__more-btn').hide();
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