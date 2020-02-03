<?php
if(!isset($_SESSION)){
	session_start();
}
$CI =& get_instance();
$CI->config->load();
if(!isset($_SESSION['lang'])){
	$_SESSION['lang'] = 'english';
}
$config['language'] = $_SESSION['lang'];
$ngonngu = $config['language'];
$this->lang->load('dich', $ngonngu);
//load model
$this->load->model('main_model');
$dir = str_replace("/school/", "", $_SERVER['REQUEST_URI']);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php if (isset($title_page)) echo $title_page ?></title>
<meta name="description" content="<?php if (isset($description_page)) echo $description_page ?>" />
<meta name="keywords" content="<?php if (isset($keywords)) echo $keywords ?>" />

<meta content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=no;" name="viewport">

<link href="<?php echo base_url() ?>publics/css_main/main_mobile.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url() ?>publics/css_main/application_mobile.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="<?php echo base_url() ?>publics/css_main/global_mobile.css"  type="text/css">

<script type="text/javascript" src="<?php echo base_url() ?>publics/js_main/jquery-1.6.min.js"></script>
<script src="<?php echo base_url() ?>publics/js_main/slides.min.jquery.js"></script>
<link href="<?php echo base_url() ?>publics/treeview/css/style.css" rel="stylesheet" type="text/css" />

<script>
$(function(){
$('#slides').slides({
preload: true,
preloadImage: 'img/loading.gif',
play: 3000,
pause: 2500,
hoverPause: true,
animationStart: function(current){
	$('.caption').animate({
		bottom:-35
	},100);
	if (window.console && console.log) {
		// example return of current slide number
		console.log('animationStart on slide: ', current);
	};
},
animationComplete: function(current){
	$('.caption').animate({
		bottom:0
	},200);
	if (window.console && console.log) {
		// example return of current slide number
		console.log('animationComplete on slide: ', current);
	};
},
slidesLoaded: function() {
	$('.caption').animate({
		bottom:0
	},200);
}
});
});
</script>

<script type="text/javascript">

$(function() {
    $('.nav').toggle(
    function() {
        $('.nav').text("x");
        $('#nav').css({ display: 'block', opacity: 1});
    },
    function() {
        $('.nav').html($('#nav_button').attr('label'));
        $('#nav').css({ display: ''});
    });
});

$(function() {
    $('.nav2').toggle(
    function() {
        $('.nav2').text("x");
        $('#nav2').css({ display: 'block', opacity: 1});
    },
    function() {
        $('.nav2').html($('#nav_button2').attr('label'));
        $('#nav2').css({ display: ''});
    });
});

</script>

<script type="text/javascript">

$( document ).ready( function( ) {
		$( '.tree li' ).each( function() {
				if( $( this ).children( 'ul' ).length > 0 ) {
						$( this ).addClass( 'parent' );
				}
		});

		$( '.tree li.parent > a' ).click( function( ) {
				$( this ).parent().toggleClass( 'active' );
				$( this ).parent().children( 'ul' ).slideToggle( 'fast' );
		});

		$( '#all' ).click( function() {

			$( '.tree li' ).each( function() {
				$( this ).toggleClass( 'active' );
				$( this ).children( 'ul' ).slideToggle( 'fast' );
			});
		});

});

</script>

<script>
function getCity(city_id){
    $.ajax({
	    url: '<?php echo base_url(); ?>getDistinctFromCity',
		type: 'POST',
		cache: false,
		data: {city_id : city_id},
		success: function(list_distinct){
			var str = list_distinct
			$('#list_district').html(str);
		}
	})
};
</script>

<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v2.3&appId=485553871614694";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

</head>

<body>

<?php
if($dir == ""){
?>

<div class="main">

    <div class="header">
        <a href="<?php echo base_url(); ?>"><div class="logo">&nbsp;</div></a>
        <!--<div class="register">Login | Register</div>-->
    </div>

    <!--<div class="clear5">&nbsp;</div>-->

    <!--<div class="menu_main">
        <div class="menu">
            <ul>
            	<li class="active"><a title="About us" href="#">About us</a></li>
            	<li><a title="Search" href="#">Search</a></li>
            	<li><a title="School" href="#">School</a></li>
            	<li><a title="Trial Lesson" href="#">Trial Lesson</a></li>
                <li><a title="News" href="#">News</a></li>
                <li><a title="Contact" href="#">Contact</a></li>
        	</ul>
        </div>
    </div>-->

    <!--<div class="clear5">&nbsp;</div>-->

    <div class="menu">
        <a href="javascript:void(0);" class="nav" label="Danh mục" id="nav_button">Danh mục</a>
    </div>
    <div style="opacity: 1;" id="nav">
        <ul>
            <div class="tip-border"></div>
            <li><a href="http://google.com">About us</a></li>
            <li><a href="#">Search</a></li>
            <li><a href="#">School</a></li>
            <li><a href="#">Trial Lesson</a></li>
            <li><a href="#">News</a></li>
            <li><a href="#">Contact</a></li>
        </ul>
    </div>

    <div class="menu2">
        <a href="javascript:void(0);" class="nav2" label="Tài khoản" id="nav_button2">Tài khoản</a>
    </div>
    <div style="opacity: 1;" id="nav2">
        <ul>
            <div class="tip-border"></div>
            <li><a href="<?php echo base_url(); ?>login">Đăng nhập</a></li>
            <li><a href="<?php echo base_url(); ?>register">Đăng ký</a></li>
        </ul>
    </div>

    <!--<div class="slideshow">
        <div id="slides">
		<div class="slides_container">
			<div class="slide">
				<img src="<?php echo base_url() ?>publics/css/images/image-slider-1.png" alt="Slide 1">
			</div>
			<div class="slide">
				<img src="<?php echo base_url() ?>publics/css/images/image-slider-2.png" alt="Slide 2">
			</div>
                  <div class="slide">
				<img src="<?php echo base_url() ?>publics/css/images/image-slider-3.png" alt="Slide 3">
			</div>
                  <div class="slide">
				<img src="<?php echo base_url() ?>publics/css/images/image-slider-4.png" alt="Slide 4">
			</div>
                  <div class="slide">
				<img src="<?php echo base_url() ?>publics/css/images/image-slider-5.png" alt="Slide 5">
			</div>
		</div>
              <div style="float: left;width: 100%;height: 43px;top: 107px;z-index: 9999;">
                  <a style="float:left;" href="#" class="prev"><img src="<?php echo base_url() ?>publics/img/arrow-prev.png" width="24" height="43" alt="Arrow Prev"></a>
                  <a  style="float:right;" href="#" class="next"><img src="<?php echo base_url() ?>publics/img/arrow-next.png" width="24" height="43" alt="Arrow Next"></a>
              </div>
	    </div>
    </div>-->

    <div class="clear5">&nbsp;</div>

    <div class="content">
    <div class="slidebar_left">

        <div class="choose_place">
            <label>Chọn thành phố: </label>
            <div class="city">
                <div class="city_left">
                    <div class="city_content" onclick='getCity(30)'>Hồ Chí Minh</div>
                </div>
                <div class="city_right">
                    <div class="city_content" onclick='getCity(24)'>Hà Nội</div>
                </div>
            </div>
            <div id="list_district"></div>
        </div>

        <div class="clear">&nbsp;</div>

        <div class="banner">
            <?php
                //get banner type 1
              	$whereBannerType1 = array('banner.banner_type' => 1);
              	$orderBannerType1 = array();
              	$banner_type_1 = $CI->main_model->getAllData("banner", $whereBannerType1, $orderBannerType1)->result();
                foreach($banner_type_1 as $detail_banner_type_1){
                    echo "<img src='".base_url()."publics/img/banner/".$detail_banner_type_1->banner_image."' alt='".$detail_banner_type_1->banner_name."' />";
                }
            ?>
        </div>
        <div class="clear">&nbsp;</div>
        <div class="category">

            <div class="tree">
                <label>Danh mục</label>
                <?php
                    //get group
                  	$whereGroup = array('group.group_status' => 1);
                  	$orderGroup = array();
                  	$group = $CI->main_model->getAllData("group", $whereGroup, $orderGroup)->result();
                    echo '<ul>';
                    foreach($group as $detail_group){
                        $whereCategory = array('category.group_id' => $detail_group->group_id, 'category.category_status' => 1);
              		    $orderCategory = array();
              		    $category = $CI->main_model->getAllData("category", $whereCategory, $orderCategory)->result();
                        echo "<li>";
                            if(!empty($category)){
                                echo "<a>".$detail_group->group_name."</a>";
                            }
                            foreach($category as $detail_category){
                                echo "<ul>";
                                    echo "<li>";
                                        echo "<a href=".base_url()."/category/".$detail_category->category_id.">".$detail_category->category_name."</a>";
                                    echo "</li>";
                                echo "</ul>";
                            }
                        echo "</li>";
                    }
                    echo '</ul>';
                ?>
            </div>

        </div>

        <!--<div class="find_us_on_facebook">
             <fb:like-box href="https://www.facebook.com/pages/H%C3%A0i-wacontre/387045084819805?ref=hl" width="300" show_faces="true" stream="false" header="true"></fb:like-box>
        </div>-->
        <div class="clear">&nbsp;</div>
        <div class="school_raking">
            <label>School Raking</label>
            <div class="raking">
                <div class="num">
                    <div class="button_num">
                        <div class="button_num_text">1</div>
                    </div>
                </div>
                <div class="text">1. Name 1 of the school in here</div>
            </div>
            <div class="raking">
                <div class="num">
                    <div class="button_num">
                        <div class="button_num_text">2</div>
                    </div>
                </div>
                <div class="text">2. Name 2 of the school in here</div>
            </div>
            <div class="raking">
                <div class="num">
                    <div class="button_num">
                        <div class="button_num_text">3</div>
                    </div>
                </div>
                <div class="text">3. Name 3 of the school in here</div>
            </div>
            <div class="raking">
                <div class="num">
                    <div class="button_num">
                        <div class="button_num_text">4</div>
                    </div>
                </div>
                <div class="text">4. Name 4 of the school in here</div>
            </div>
            <div class="raking_end">
                <div class="num">
                    <div class="button_num">
                        <div class="button_num_text">5</div>
                    </div>
                </div>
                <div class="text">5. Name 5 of the school in here</div>
            </div>
        </div>
        <div class="clear">&nbsp;</div>
        <div class="help_center">
            Help<br />Center
        </div>
    </div>

    <div class="content_main">
        <!--<div class="clear">&nbsp;</div>
        <div class="search_box">
            <label>Search Box</label>
            <div class="search_box_input">
                <select name="place">
                    <option value="">---Choose---</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                </select>
                <input type="text" name="keyword" value="keyword" />
            </div>
            <label>Category</label>
            <div class="search_box_input">
              <select name="category">
                  <option value="">---Choose---</option>
                  <option value="1">1</option>
                  <option value="2">2</option>
                  <option value="3">3</option>
              </select>
            </div>
            <div class="clear">&nbsp;</div>
            <div class="button_search">
                <a class="but-color-medium-upload">Search</a>
            </div>
            <div class="clear">&nbsp;</div>
        </div>-->
        <div class="advertise">
            <div class="advertise_left">
                <img alt="banner03" src="http://truyenmieng.com/school/publics/img/banner/banner03.png">
            </div>
            <div class="advertise_right">
                <img alt="banner04" src="http://truyenmieng.com/school/publics/img/banner/banner04.png">
            </div>
        </div>
        <div class="clear">&nbsp;</div>
        <div class="hot_school">
            <div class="title_listjob"><h2>Hot School</h2></div>
            <div class="list_job">
        	 	<div class="company_job">
        			<div class="company_job_description">
        		   		<div class="company-list-job">
        		   			<div class="list-job-left">
        						<a href="#">
        							<img src="http://truyenmieng.com/school/publics/img/no-image.gif" alt="PG01">
        						</a>
        						<!--<span class="date_post">Ngày đăng : 02-06-2015</span>-->
        		   			</div>
        		   			<div class="list-job-right">
        		   				<div class="title_hot_school">Name of school 1</div>
        		   				<div class="content">
                                    Note note note note note note note note note note note note note note note note note note...
        		   				</div>
        		   				<div class="event">
        		   					<a href="#">View more</a>
        		   				</div>
        		   			</div>
        		   		</div>
        			</div>
        	   	</div>
          	</div>
            <!---->
            <div class="list_job">
        	 	<div class="company_job">
        			<div class="company_job_description">
        		   		<div class="company-list-job">
        		   			<div class="list-job-left">
        						<a href="#">
        							<img src="http://truyenmieng.com/school/publics/img/no-image.gif" alt="PG01">
        						</a>
        		   			</div>
        		   			<div class="list-job-right">
        		   				<div class="title_hot_school">Name of school 1</div>
        		   				<div class="content">
                                    Note note note note note note note note note note note note note note note note note note...
        		   				</div>
        		   				<div class="event">
        		   					<a href="#">View more</a>
        		   				</div>
        		   			</div>
        		   		</div>
        			</div>
        	   	</div>
          	</div>
            <!---->
            <div class="list_job">
        	 	<div class="company_job">
        			<div class="company_job_description">
        		   		<div class="company-list-job">
        		   			<div class="list-job-left">
        						<a href="#">
        							<img src="http://truyenmieng.com/school/publics/img/no-image.gif" alt="PG01">
        						</a>
        		   			</div>
        		   			<div class="list-job-right">
        		   				<div class="title_hot_school">Name of school 1</div>
        		   				<div class="content">
                                    Note note note note note note note note note note note note note note note note note note...
        		   				</div>
        		   				<div class="event">
        		   					<a href="#">View more</a>
        		   				</div>
        		   			</div>
        		   		</div>
        			</div>
        	   	</div>
          	</div>
        </div>
        <div class="clear">&nbsp;</div>
        <div class="trial_lesson_pickup">
            <div class="title_listjob"><h2>Trial Lesson Pickup</h2></div>
            <div class="list_job">
        	 	<div class="company_job">
        			<div class="company_job_description">
        		   		<div class="company-list-job">
        		   			<div class="list-job-left">
        						<a href="#">
        							<img src="http://truyenmieng.com/school/publics/img/no-image.gif" alt="PG01">
        						</a>
        		   			</div>
        		   			<div class="list-job-right">
        		   				<div class="title_trial_lesson_pickup">01/05/2015<span class="hour">18:00</span></div>
        		   				<div class="content">
                                    Note note note note note note note note note note note note note note note note note note...
        		   				</div>
        		   				<div class="event">
        		   					<a href="#">View more</a>
        		   				</div>
        		   			</div>
        		   		</div>
        			</div>
        	   	</div>
          	</div>
            <!---->
            <div class="list_job">
        	 	<div class="company_job">
        			<div class="company_job_description">
        		   		<div class="company-list-job">
        		   			<div class="list-job-left">
        						<a href="#">
        							<img src="http://truyenmieng.com/school/publics/img/no-image.gif" alt="PG01">
        						</a>
        		   			</div>
        		   			<div class="list-job-right">
        		   				<div class="title_trial_lesson_pickup">01/05/2015<span class="hour">18:00</span></div>
        		   				<div class="content">
                                    Note note note note note note note note note note note note note note note note note note...
        		   				</div>
        		   				<div class="event">
        		   					<a href="#">View more</a>
        		   				</div>
        		   			</div>
        		   		</div>
        			</div>
        	   	</div>
          	</div>
            <!---->
            <div class="list_job">
        	 	<div class="company_job">
        			<div class="company_job_description">
        		   		<div class="company-list-job">
        		   			<div class="list-job-left">
        						<a href="#">
        							<img src="http://truyenmieng.com/school/publics/img/no-image.gif" alt="PG01">
        						</a>
        		   			</div>
        		   			<div class="list-job-right">
        		   				<div class="title_trial_lesson_pickup">01/05/2015<span class="hour">18:00</span></div>
        		   				<div class="content">
                                    Note note note note note note note note note note note note note note note note note note...
        		   				</div>
        		   				<div class="event">
        		   					<a href="#">View more</a>
        		   				</div>
        		   			</div>
        		   		</div>
        			</div>
        	   	</div>
          	</div>
        </div>
        <div class="clear">&nbsp;</div>
        <div class="news_article_lits">
             <div class="title_listjob"><h2>News Article Lits</h2></div>
             <div class="list_job">
        	 	<div class="company_job">
        			<div class="news_description">
        		   		<div class="company-list-job">
        		   			<div class="list-job-left">
        						<a href="#">
        							<img src="http://truyenmieng.com/school/publics/img/no-image.gif" alt="PG01">
        						</a>
        		   			</div>
        		   			<div class="list-job-right">
        		   				<div class="title_news_article_lits">Article 1</div>
        		   				<div class="content_news_article_lits">
                                    Note note note note note note note note note note note note note note note note note note...
        		   				</div>
        		   				<div class="event_news_article_lits">
        		   					<a href="#">View more</a>
        		   				</div>
        		   			</div>
        		   		</div>
        			</div>
        	   	</div>
          	</div>
            <!---->
            <div class="list_job">
        	 	<div class="company_job">
        			<div class="news_description">
        		   		<div class="company-list-job">
        		   			<div class="list-job-left">
        						<a href="#">
        							<img src="http://truyenmieng.com/school/publics/img/no-image.gif" alt="PG01">
        						</a>
        		   			</div>
        		   			<div class="list-job-right">
        		   				<div class="title_news_article_lits">Article 2</div>
        		   				<div class="content_news_article_lits">
                                    Note note note note note note note note note note note note note note note note note note...
        		   				</div>
        		   				<div class="event_news_article_lits">
        		   					<a href="#">View more</a>
        		   				</div>
        		   			</div>
        		   		</div>
        			</div>
        	   	</div>
          	</div>
            <!---->
            <div class="list_job">
        	 	<div class="company_job">
        			<div class="news_description">
        		   		<div class="company-list-job">
        		   			<div class="list-job-left">
        						<a href="#">
        							<img src="http://truyenmieng.com/school/publics/img/no-image.gif" alt="PG01">
        						</a>
        		   			</div>
        		   			<div class="list-job-right">
        		   				<div class="title_news_article_lits">Article 3</div>
        		   				<div class="content_news_article_lits">
                                    Note note note note note note note note note note note note note note note note note note...
        		   				</div>
        		   				<div class="event_news_article_lits">
        		   					<a href="#">View more</a>
        		   				</div>
        		   			</div>
        		   		</div>
        			</div>
        	   	</div>
          	</div>
        </div>
    </div>

    </div>

    <?php echo $body; ?>

    <div class="clear5">&nbsp;</div>

    <div class="footer">
        <a href="#">About us</a>&nbsp&nbsp|&nbsp&nbsp
        <a href="#">Search</a>&nbsp&nbsp|&nbsp&nbsp
        <a href="#">School</a>&nbsp&nbsp|&nbsp&nbsp
        <a href="#">Trial Lesson</a>&nbsp&nbsp|&nbsp&nbsp
        <a href="#">News</a>&nbsp&nbsp|&nbsp&nbsp
        <a href="#">Contact</a>
    </div>

</div>

<?php
}
else{
?>

<div class="main">

    <div class="header">
        <a href="<?php echo base_url(); ?>"><div class="logo">&nbsp;</div></a>
        <!--<div class="register">Login | Register</div>-->
    </div>

    <!--<div class="clear5">&nbsp;</div>-->

    <!--<div class="menu_main">
        <div class="menu">
            <ul>
            	<li class="active"><a title="About us" href="#">About us</a></li>
            	<li><a title="Search" href="#">Search</a></li>
            	<li><a title="School" href="#">School</a></li>
            	<li><a title="Trial Lesson" href="#">Trial Lesson</a></li>
                <li><a title="News" href="#">News</a></li>
                <li><a title="Contact" href="#">Contact</a></li>
        	</ul>
        </div>
    </div>-->

    <!--<div class="clear5">&nbsp;</div>-->

    <div class="menu">
        <a href="javascript:void(0);" class="nav" label="Danh mục" id="nav_button">Danh mục</a>
    </div>
    <div style="opacity: 1;" id="nav">
        <ul>
            <div class="tip-border"></div>
            <li><a href="http://google.com">About us</a></li>
            <li><a href="#">Search</a></li>
            <li><a href="#">School</a></li>
            <li><a href="#">Trial Lesson</a></li>
            <li><a href="#">News</a></li>
            <li><a href="#">Contact</a></li>
        </ul>
    </div>

    <div class="menu2">
        <a href="javascript:void(0);" class="nav2" label="Tài khoản" id="nav_button2">Tài khoản</a>
    </div>
    <div style="opacity: 1;" id="nav2">
        <ul>
            <div class="tip-border"></div>
            <li><a href="<?php echo base_url(); ?>login">Đăng nhập</a></li>
            <li><a href="<?php echo base_url(); ?>register">Đăng ký</a></li>
        </ul>
    </div>

    <!--<div class="slideshow">
        <div id="slides">
		<div class="slides_container">
			<div class="slide">
				<img src="<?php echo base_url() ?>publics/css/images/image-slider-1.png" alt="Slide 1">
			</div>
			<div class="slide">
				<img src="<?php echo base_url() ?>publics/css/images/image-slider-2.png" alt="Slide 2">
			</div>
                  <div class="slide">
				<img src="<?php echo base_url() ?>publics/css/images/image-slider-3.png" alt="Slide 3">
			</div>
                  <div class="slide">
				<img src="<?php echo base_url() ?>publics/css/images/image-slider-4.png" alt="Slide 4">
			</div>
                  <div class="slide">
				<img src="<?php echo base_url() ?>publics/css/images/image-slider-5.png" alt="Slide 5">
			</div>
		</div>
              <div style="float: left;width: 100%;height: 43px;top: 107px;z-index: 9999;">
                  <a style="float:left;" href="#" class="prev"><img src="<?php echo base_url() ?>publics/img/arrow-prev.png" width="24" height="43" alt="Arrow Prev"></a>
                  <a  style="float:right;" href="#" class="next"><img src="<?php echo base_url() ?>publics/img/arrow-next.png" width="24" height="43" alt="Arrow Next"></a>
              </div>
	    </div>
    </div>-->

    <div class="clear5">&nbsp;</div>

    <div class="content">
    <div class="slidebar_left">

        <div class="choose_place">
            <label>Chọn thành phố: </label>
            <div class="city">
                <div class="city_left">
                    <div class="city_content" onclick='getCity(30)'>Hồ Chí Minh</div>
                </div>
                <div class="city_right">
                    <div class="city_content" onclick='getCity(24)'>Hà Nội</div>
                </div>
            </div>
            <div id="list_district"></div>
        </div>

        <div class="clear">&nbsp;</div>

        <div class="banner">
            <?php
                //get banner type 1
              	$whereBannerType1 = array('banner.banner_type' => 1);
              	$orderBannerType1 = array();
              	$banner_type_1 = $CI->main_model->getAllData("banner", $whereBannerType1, $orderBannerType1)->result();
                foreach($banner_type_1 as $detail_banner_type_1){
                    echo "<img src='".base_url()."publics/img/banner/".$detail_banner_type_1->banner_image."' alt='".$detail_banner_type_1->banner_name."' />";
                }
            ?>
        </div>
        <div class="clear">&nbsp;</div>
        <div class="category">

            <div class="tree">
                <label>Danh mục</label>
                <?php
                    //get group
                  	$whereGroup = array('group.group_status' => 1);
                  	$orderGroup = array();
                  	$group = $CI->main_model->getAllData("group", $whereGroup, $orderGroup)->result();
                    echo '<ul>';
                    foreach($group as $detail_group){
                        $whereCategory = array('category.group_id' => $detail_group->group_id, 'category.category_status' => 1);
              		    $orderCategory = array();
              		    $category = $CI->main_model->getAllData("category", $whereCategory, $orderCategory)->result();
                        echo "<li>";
                            if(!empty($category)){
                                echo "<a>".$detail_group->group_name."</a>";
                            }
                            foreach($category as $detail_category){
                                echo "<ul>";
                                    echo "<li>";
                                        echo "<a href=".base_url()."/category/".$detail_category->category_id.">".$detail_category->category_name."</a>";
                                    echo "</li>";
                                echo "</ul>";
                            }
                        echo "</li>";
                    }
                    echo '</ul>';
                ?>
            </div>

        </div>

        <?php echo $body; ?>

        <!--<div class="find_us_on_facebook">
             <fb:like-box href="https://www.facebook.com/pages/H%C3%A0i-wacontre/387045084819805?ref=hl" width="300" show_faces="true" stream="false" header="true"></fb:like-box>
        </div>-->
        <div class="clear">&nbsp;</div>
    </div>

    <div class="content_main">
        <!--<div class="clear">&nbsp;</div>
        <div class="search_box">
            <label>Search Box</label>
            <div class="search_box_input">
                <select name="place">
                    <option value="">---Choose---</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                </select>
                <input type="text" name="keyword" value="keyword" />
            </div>
            <label>Category</label>
            <div class="search_box_input">
              <select name="category">
                  <option value="">---Choose---</option>
                  <option value="1">1</option>
                  <option value="2">2</option>
                  <option value="3">3</option>
              </select>
            </div>
            <div class="clear">&nbsp;</div>
            <div class="button_search">
                <a class="but-color-medium-upload">Search</a>
            </div>
            <div class="clear">&nbsp;</div>
        </div>-->
        <div class="advertise">
            <div class="advertise_left">
                <img alt="banner03" src="http://truyenmieng.com/school/publics/img/banner/banner03.png">
            </div>
            <div class="advertise_right">
                <img alt="banner04" src="http://truyenmieng.com/school/publics/img/banner/banner04.png">
            </div>
        </div>
        <div class="clear">&nbsp;</div>

    </div>

    <div class="clear5">&nbsp;</div>

    <div class="footer">
        <a href="#">About us</a>&nbsp&nbsp|&nbsp&nbsp
        <a href="#">Search</a>&nbsp&nbsp|&nbsp&nbsp
        <a href="#">School</a>&nbsp&nbsp|&nbsp&nbsp
        <a href="#">Trial Lesson</a>&nbsp&nbsp|&nbsp&nbsp
        <a href="#">News</a>&nbsp&nbsp|&nbsp&nbsp
        <a href="#">Contact</a>
    </div>

</div>

<?php
}
?>

</body>
</html>