<?php
            if($load_user_timeline['user_banner'] != "")
			{
				$user_banner = base_url().'publics/banner/'.$load_user_timeline['user_banner'];
			}
			else
			{
				$user_banner = base_url().'publics/banner/user-bg_no_image.jpg';
			}
			?>
<meta property="og:image" content="<?php echo $user_banner;?>" />
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
$user_id_login = $logged_in['user_id'];
?>

<!--dhthien-->
<script>

var user_id_get_img = "<?php echo $load_user_timeline['user_id'];?>";

function detail_img(id_img){
	
	$.ajax({

			type: "POST",

			cache: false,

			url: "<?php echo base_url(); ?>ajax/add_user_timeline",

			data: "id_img="+id_img+"&user_id_get_img="+user_id_get_img,

			success: function(data) {
				
				window.location.assign("<?php echo base_url(); ?>detail/" + data + ".html");

			}

		});
	
}

</script>
<!--end dhthien-->

<script>
/*var user_id = "<?php echo $user_id_login ?>";

function add_to_follow(){
if(user_id == ""){
	window.location.href = "<?php echo base_url(); ?>login.html";
	return;
}
var result = confirm("<?php echo $this->lang->line('are_you_want_follow_this_user'); ?>");
if(result){
		var user_id_follow = "<?php echo $load_user_timeline['user_id'];?>"; 
		$.ajax({
			type: "POST",
			cache: false,
			url: "<?php echo base_url(); ?>ajax/do_follow_user_timeline",
			data: "user_id=" + user_id_follow,
			success: function(data) {
				if(data == 1){
					location.reload();
				}
			}
		});
		
	}
}*/
</script>
<style>
#container-01,
#container-02 {
    margin:10px auto;}
.pinto {
    box-sizing: border-box;
	max-width:1000px;
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
    width:100%;
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
.am-wrapper{display:none}
@media (max-width: 768px)
{
	.list-img {padding:0!important}	
		#container-02{display:none!important}
	.am-wrapper{
	float:left;
	position:relative;
	overflow:hidden;
	display:block;
	height:auto;
}
.am-wrapper img{
	position:absolute;
	outline:none;
}
.list-pagenav {
    height: 48px;
    float: left;
    width: 100%;
}
}
@media (max-width: 500px)
{	
	#container-02{height:auto!important}
	.pintomobile {float:left;width:50%}
	.pintomobile img {
	display: block;
    width: 160px;
    max-width: 160px;
    text-align: center;
    padding: 4px;
    height: auto;
	}
	.pintomobile .info {
		padding:10px 15px;
		background-color:#eee;
	}
	.pintomobile .info h2 {
		margin:0;
		font-size:18px;
	}
	.pintomobile .info p {
		margin:10px 0 0;
		font-size:14px;
	}
}
</style>
<script type="text/javascript" src="<?php echo base_url(); ?>publics/test/jquery.min.js" ></script>
<script type="text/javascript" src="<?php echo base_url(); ?>publics/js/jquery.montage.min.js"></script>
		<script type="text/javascript">
			$(function() {
				var $container 	= $('#am-container'),
					$imgs		= $container.find('img').hide(),
					totalImgs	= $imgs.length,
					cnt			= 0;
				
				$imgs.each(function(i) {
					var $img	= $(this);
					$('<img/>').load(function() {
						++cnt;
						if( cnt === totalImgs ) {
							$imgs.show();
							$container.montage({
								fillLastRow				: true,
								alternateHeight			: true,
								alternateHeightRange	: {
									min	: 90,
									max	: 150,
								},
								margin : 2,
							});
						}
					}).attr('src',$img.attr('src'));
				});	
				
			});
		</script>
<div class="contents-timeline">
        <div style="background-image: url(<?php echo $user_banner; ?>);background-size: cover" class="timeline-header">
          <div class="timeline-header__filter">
            <div class="timeline-header__wrapper">
              <div class="timeline-header__icon">
              <?php
            if($load_user_timeline['user_avatar'] != "")
			{
			?>
            <img alt="avatar" src="<?php echo base_url(); ?>publics/avatar/<?php echo $load_user_timeline['user_avatar']; ?>">
            <?php }else{?>
            <img alt="no avatar" src="<?php echo base_url(); ?>publics/avatar/prof-img.png">
            <?php }?>
              </div>
              <div class="timeline-header__meta">
                <p class="name"><?php echo $CI->function_model->get_fullname($load_user_timeline['user_id'], $this->lang_id); ?></p>
                <?php if($is_login_user==1){?>
                	<?php if(in_array($load_user_timeline['user_id'], $id_user_check)){?>
                    	<div class="follow-btn" onclick="remove_follow(<?php echo $load_user_timeline['user_id']?>)"><span id="show_text_follow<?php echo $load_user_timeline['user_id']?>"><?php echo $this->lang->line('to_follow_exits'); ?></span></div>
                    <?php } else {?>
                    	<div class="follow-btn" onclick="add_follow(<?php echo $load_user_timeline['user_id']?>)"><span id="show_text_follow<?php echo $load_user_timeline['user_id']?>"><?php echo $this->lang->line('to_follow'); ?></span></div>
                    <?php } ?>
                	
                <?php } else {?>
                	<div class="follow-btn" onclick="add_follow(<?php echo $load_user_timeline['user_id']?>)"><span id="show_text_follow<?php echo $load_user_timeline['user_id']?>"><?php echo $this->lang->line('to_follow'); ?></span></div>
                <?php } ?>
                <div class="timeline-header__text">
                  <p><?php echo $this->lang->line('time_line_count_image_user'); ?>：<?php echo $load_user_timeline['count_product']  + $CI->manager_user_model->getcountget_sell($load_user_timeline['user_id']);?></p>
                  <div class="comment">
                    <p style="display: block;white-space: nowrap;overflow: hidden;text-overflow: ellipsis;width:1000px;"><?php echo $load_user_timeline['user_introduction']; ?></p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="timeline-header__sp-comment">
          <p><?php // echo $CI->subtext($load_user_timeline['user_introduction'],20);?><?php echo $load_user_timeline['user_introduction'];?></p>
         
        </div>
        <div class="timeline-share">
          <p>このページをシェアして<br />クリエイターを応援しよう！</p>
          <ul>
            <li class="timeline-share__twitter">
            <a href="https://twitter.com/share?url=<?php echo $linkshare;?>&hashtags=LuxyArt&text=<?php echo $CI->function_model->get_fullname($load_user_timeline['user_id'], $this->lang_id); ?> - のマイページ ｜LuxyArt（ラクシーアート）" target="_blank">ツイートする</a></li>
            <li class="timeline-share__facebook"><a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $linkshare;?>" target="_blank">シェアする</a></li>
            <li style="margin: 0 0 0 6px;"><div class="line-it-button" data-lang="ja" data-type="like" data-url="<?php echo $linkshare;?>" data-share="true" style="display: none;"></div>
 <script src="https://d.line-scdn.net/r/web/social-plugin/js/thirdparty/loader.min.js" async="async" defer="defer"></script></li>
          </ul>
        </div>
        <div class="contents-timeline__wrapper">
          <div class="list-img">
            <div id="container-02" class="list-img__wrapper flex-images">
            
            <?php if(!empty($list_images)){ foreach($list_images as $load_list_img_condition_search){
				?>
            <div class="pinto"><div id="img_in_list_<?php echo $load_list_img_condition_search->id_img;?>" class="list-img__favorite <?php if($is_login_user==1){if (in_array($load_list_img_condition_search->id_img, $check_bookmark_images)){echo 'set';}}?>"></div>
            <a <?php if($load_list_img_condition_search->user_id!=$load_user_timeline['user_id']) {?>onclick="detail_img(<?php echo $load_list_img_condition_search->id_img;?>)"<?php } else {?>href="<?php echo base_url(); ?>detail/<?php echo $load_list_img_condition_search->img_code;?>.html"<?php } ?>><img alt="<?php echo $load_list_img_condition_search->img_title;?>" src="<?php echo $load_list_img_condition_search->server_path_upload;?>/<?php echo $load_list_img_condition_search->file_name_watermark_img_1;?>"></a>
			</div>
            <?php }}?>
            </div>
            <div class="am-container" id="am-container">
            
            <?php if(!empty($list_images)){ foreach($list_images as $load_list_img_condition_search){
				?>
            <a <?php if($load_list_img_condition_search->user_id!=$load_user_timeline['user_id']) {?>onclick="detail_img(<?php echo $load_list_img_condition_search->id_img;?>)"<?php } else {?>href="<?php echo base_url(); ?>detail/<?php echo $load_list_img_condition_search->img_code;?>.html"<?php } ?>><img alt="<?php echo $load_list_img_condition_search->img_title;?>" src="<?php echo $load_list_img_condition_search->server_path_upload;?>/<?php echo $load_list_img_condition_search->file_name_watermark_img_1;?>"></a>
            <?php }}?>
            </div>
             <?php if(@$phantrang && $phantrang!=""){echo $phantrang;}?>
             <div class="clear clearfix"></div>
             <?php if(@$banner_load && $banner_load!=""){echo $banner_load;}?>
          </div>
           
            <div class="timeline-related-user">
             <h2 class="timeline-related-user__find"><?php echo $this->lang->line('h2_text_show_another_user_time_line');?></h2>
      <ul>
        <?php
        if(!empty($list_user_follow))
		{
			foreach($list_user_follow as $detail_user_follow)
			{
				$whereUser = array('user_id' => $detail_user_follow->user_id,'user_status' => 1);
				$orderUser = array();
				$user = $CI->main_model->getAllData("luxyart_tb_user", $whereUser, $orderUser)->row();
				$countProduct = $CI->manager_user_model->getCountProduct($detail_user_follow->user_id);
		?>
        <li><a href="<?php echo base_url(); ?>timeline/<?php echo $detail_user_follow->user_id; ?>.html" class="sp-link"></a>
          <div class="timeline-related-user__icon">
            <?php
            if($user->user_avatar != "")
			{
			?>
            <img alt="avatar" src="<?php echo base_url(); ?>publics/avatar/<?php echo $user->user_avatar; ?>">
            <?php }else{?>
            <img alt="no avatar" src="<?php echo base_url(); ?>publics/avatar/prof-img.png">
            <?php }?>
          </div>
          <div class="timeline-related-user__meta">
            <p class="name"><a href="<?php echo base_url(); ?>timeline/<?php echo $detail_user_follow->user_id; ?>.html"><span><?php echo $CI->function_model->get_fullname($detail_user_follow->user_id, $this->lang_id); ?></span></a></p>
            <p class="num"><?php echo $this->lang->line('time_line_count_image_user');?><a href="<?php echo base_url(); ?>timeline/<?php echo $detail_user_follow->user_id; ?>.html"><?php echo $countProduct   + $CI->manager_user_model->getcountget_sell($detail_user_follow->user_id); ?></a></p>
            <?php if($is_login_user==1){?>
            	<?php if(in_array($detail_user_follow->user_id, $id_user_check)){?>
            				<div class="timeline-related-user__follow-btn" onclick="remove_follow2(<?php echo $detail_user_follow->user_id?>)"><span class="follow" id="show2_text_follow<?php echo $detail_user_follow->user_id?>"><?php echo $this->lang->line('to_follow_exits'); ?></span></div>
            	<?php } else {?>
                			<div class="timeline-related-user__follow-btn" onclick="add_follow2(<?php echo $detail_user_follow->user_id?>)"><span class="follow" id="show2_text_follow<?php echo $detail_user_follow->user_id?>"><?php echo $this->lang->line('to_follow'); ?></span></div>
                <?php } ?>
            <?php } else {?>
            				<div class="timeline-related-user__follow-btn" onclick="add_follow2(<?php echo $detail_user_follow->user_id?>)"><span class="follow" id="show2_text_follow<?php echo $detail_user_follow->user_id?>"><?php echo $this->lang->line('to_follow'); ?></span></div>
            <?php } ?>
          </div>
          <div class="timeline-related-user__img">
            <ul>
              <?php
					$productImg = $CI->manager_user_model->get_product_user_top_page($user->user_id)->result();
					$i=0;
					if(!empty($productImg))
					{
						foreach($productImg as $detailProductImg)
						{
					?>
              				<li>
                			<div>
                            <a href="<?php echo base_url(); ?>detail/<?php echo $detailProductImg->img_code?>.html">
                  			<?php
								if($detailProductImg->file_name_watermark_img != "")
								{
								?>
                  					<img alt="<?php echo $detailProductImg->img_title?>" src="<?php echo $detailProductImg->server_path_upload?>/<?php echo $detailProductImg->file_name_watermark_img_1; ?>">
                  				<?php }else{?>
                  				<img alt="No Image" src="<?php echo base_url(); ?>publics/img/no_image.jpg">
                  			<?php } ?>
                  			</a>
                            </div>
              				</li>
              		<?php 
							$i++;
							}}?>
              <?php 
			  if($i<3){
			  $lay_img_get_sell = (3-$i);
			  if($lay_img_get_sell >0)
			  	{
					$productImg = $CI->manager_user_model->get_more_from_image_get_sell($user->user_id,$lay_img_get_sell)->result();
					if(!empty($productImg))
					{
						foreach($productImg as $detailProductImg)
						{
						?>
              <li <?php echo $i;?>>
                <div> <a href="<?php echo base_url(); ?>detail/<?php echo $detailProductImg->img_code?>.html">
                  <?php
						if($detailProductImg->file_name_watermark_img != "")
						{
						?>
                  			<img alt="<?php echo $detailProductImg->img_title?>" src="<?php echo $detailProductImg->server_path_upload?>/<?php echo $detailProductImg->file_name_watermark_img_1; ?>">
                  <?php }else{?>
                  		<img alt="No Image" src="<?php echo base_url(); ?>publics/img/no_image.jpg">
                  <?php } ?>
                  </a> </div>
              </li>
              <?php } 
			  }
				}
			  }
			  ?>
            </ul>
          </div>
        </li>
        <?php }}?>
      </ul>
    </div>
        </div>
      </div>
<script type="text/javascript" src="<?php echo base_url(); ?>publics/test/imagesloaded.pkgd.min.js" ></script>
<script type="text/javascript" src="<?php echo base_url(); ?>publics/test/jquery.pinto.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>publics/test/main.js"></script>
<script type="text/javascript">
var width = $(window).width();
if(width<=500)
{
	$( "#container-02" ).css( "height","auto!important");
	$( "#container-02 div" ).removeClass( "pinto" ).addClass( "pintomobile" );
}
$('.list-img__favorite').on('click',function(){
		var is_login = '<?php echo $is_login_user?>';
		if(is_login==1)
		{
			var get_id = this.id.split("img_in_list_");
			var id_img = get_id[1];
			$.ajax({
					type: "POST",cache: false,
					url: '<?php echo base_url(); ?>user/do_bookmark_image',
					data: "id_image="+id_img,
					async:false,
					success: function(data) {
										var msg=data.split("{return_do_bookmark_image}");
										if(msg[1]==0)
					                    {	
											 $('#img_in_list_'+id_img).addClass('set');
					                    }
					                    else if(msg[1]==1)
					                    {
											$('#img_in_list_'+id_img).removeClass('set');
					                    }
						}

				   }); 
			
		}
		else
		{
			window.location.href = "<?php echo base_url(); ?>login.html";
			return;
		}
		
	});
function add_follow(user_id){
	var is_login = '<?php echo $is_login_user?>';
		if(is_login==1)
		{
			var user_id_bi_click = user_id;
			var user_follow_id = '<?php echo $user_login_id?>';
			$.ajax({
					type: "POST",cache: false,
					url: '<?php echo base_url(); ?>user/do_bookmark_follow_user',
					data: "user_id_bi_click="+user_id,
					async:false,
					success: function(data) {
										var msg=data.split("{return_do_bookmark_follow_user}");
										if(msg[1]==0)
					                    {	
											 $('#show_text_follow'+user_id_bi_click).html("<?php echo $this->lang->line('to_follow_exits'); ?>");
					                    }
					                    else if(msg[1]==1)
					                    {
											$('#show_text_follow'+user_id_bi_click).html("<?php echo $this->lang->line('to_follow_exits'); ?>");
					                    }
						}

				   }); location.reload();
			
		}
		else
		{
			window.location.href = "<?php echo base_url(); ?>login.html";
			return;
		}
}
function add_follow2(user_id){
	var is_login = '<?php echo $is_login_user?>';
		if(is_login==1)
		{
			var user_id_bi_click = user_id;
			var user_follow_id = '<?php echo $user_login_id?>';
			$.ajax({
					type: "POST",cache: false,
					url: '<?php echo base_url(); ?>user/do_bookmark_follow_user',
					data: "user_id_bi_click="+user_id,
					async:false,
					success: function(data) {
										var msg=data.split("{return_do_bookmark_follow_user}");
										if(msg[1]==0)
					                    {	
											 $('#show2_text_follow'+user_id_bi_click).html("<?php echo $this->lang->line('to_follow_exits'); ?>");
					                    }
					                    else if(msg[1]==1)
					                    {
											$('#show2_text_follow'+user_id_bi_click).html("<?php echo $this->lang->line('to_follow_exits'); ?>");
					                    }
						}

				   }); location.reload();
			
		}
		else
		{
			window.location.href = "<?php echo base_url(); ?>login.html";
			return;
		}
}
function remove_follow(user_id){
	var is_login = '<?php echo $is_login_user?>';
		if(is_login==1)
		{
			var user_id_bi_click = user_id;
			var user_follow_id = '<?php echo $user_login_id?>';
			$.ajax({
					type: "POST",cache: false,
					url: '<?php echo base_url(); ?>user/do_remove_bookmark_follow_user',
					data: "user_id_bi_click="+user_id,
					async:false,
					success: function(data) {
										var msg=data.split("{return_do_remove_bookmark_follow_user}");
										if(msg[1]==0)
					                    {	
											 $('#show_text_follow'+user_id_bi_click).html("<?php echo $this->lang->line('to_follow'); ?>");
					                    }
					                    else if(msg[1]==1)
					                    {
											$('#show_text_follow'+user_id_bi_click).html("<?php echo $this->lang->line('to_follow'); ?>");
					                    }
						}

				   });
				   location.reload();
			
		}
		else
		{
			window.location.href = "<?php echo base_url(); ?>login.html";
			return;
		}
}
function remove_follow2(user_id){
	var is_login = '<?php echo $is_login_user?>';
		if(is_login==1)
		{
			var user_id_bi_click = user_id;
			var user_follow_id = '<?php echo $user_login_id?>';
			$.ajax({
					type: "POST",cache: false,
					url: '<?php echo base_url(); ?>user/do_remove_bookmark_follow_user',
					data: "user_id_bi_click="+user_id,
					async:false,
					success: function(data) {
										var msg=data.split("{return_do_remove_bookmark_follow_user}");
										if(msg[1]==0)
					                    {	
											 $('#show2_text_follow'+user_id_bi_click).html("<?php echo $this->lang->line('to_follow'); ?>");
					                    }
					                    else if(msg[1]==1)
					                    {
											$('#show2_text_follow'+user_id_bi_click).html("<?php echo $this->lang->line('to_follow'); ?>");
					                    }
						}

				   }); 
				   location.reload();
			
		}
		else
		{
			window.location.href = "<?php echo base_url(); ?>login.html";
			return;
		}
}
</script>