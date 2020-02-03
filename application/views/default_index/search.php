<?php
if(!isset($_SESSION)){
	session_start();
}
$CI =& get_instance();
$CI->config->load();
if(!isset($_SESSION['lang'])){
	$_SESSION['lang'] = 'ja';
}
$config['language'] = $_SESSION['lang'];
$ngonngu = $config['language'];
$this->lang->load('dich', $ngonngu);
?>
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
.breadly_mobile{display:none}
.am-wrapper{display:none}
@media (max-width: 768px)
{	
	.breadly {display:none!important}
	.list-search__text
	{
		width:calc(100% - 20px)!important;
	}
	.breadly_mobile {
  width: 100%;
  height: 32px;
}
.list-sub {margin: 7px 0 0!important;}
.breadly_mobile ul {
  font-size: 0;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}
.breadly_mobile li {
  line-height: 32px;
  margin: 0 0 0 12px;
  padding: 0 0 0 12px;
  display: inline-block;
  position: relative;
}
.breadly_mobile li:before {
  margin: -4px 0 0;
  content: "";
  display: block;
  position: absolute;
  top: 50%;
  left: 0;
  background-image: url(../publics/img/sprite.png)!important;
  background-position: -308px -258px;
  width: 3px;
  height: 5px;
}

.breadly_mobile li:first-child {
  margin: 0;
  padding: 0;
}
.breadly_mobile li:first-child:before {
  content: none;
}
.breadly_mobile span {
  line-height: 32px;
  font-size: 12px;
}
.breadly_mobile a {
  line-height: 32px;
  font-size: 12px;
}
.breadly_mobile a span {
  color: #008de7;
}
	.breadly_mobile {display:block!important;padding:0px 15px!important;width: calc(100% - 30px)!important;}
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
}
@media (-webkit-min-device-pixel-ratio: 2), (min-resolution: 192dpi) {
	
  .breadly_mobile li:before {
    background-image: url(../img/sprite@2x.png);
    background-size: 312px 346px;
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
<div class="contents-list">
        <div style="background: url(<?php echo base_url(); ?>publics/cate_img/<?php echo $meta_seo['cate_img_id']; ?>) center center;background-size: cover" class="ttl-list">
          <div class="ttl-list__filter">
            <div class="ttl-list__box">
              <h1><?php echo $meta_seo['page_h1']; ?></h1>
            </div>
          </div>
        </div>
        <div class="breadly_mobile">
            <ul>
              <li><a href="<?php echo base_url(); ?>"><span itemprop="title">ホーム</span></a></li>
              <?php if($cate_path_level!=0){?>
              <li><a href="<?php echo base_url(); ?>category/<?php echo $cate_path_level?>.html"><span itemprop="title">
              <?php echo $cate_path_level_name?>の写真素材</span></a></li>
              <li><span itemprop="title"><?php echo $meta_seo['page_h1'];?></span></li>
			  <?php }else {?>
              <li><span itemprop="title"><?php echo $meta_seo['page_h1']; ?>の写真素材</span></li>
              <?php }?>
            </ul>
          </div>
        <div class="list-sub">
        <div class="list-sub__cat">
        <p>写真のカテゴリー</p>
        <ul>
          <?php
              if(!empty($list_product_category))
			  {
				  foreach($list_product_category as $detail_product_category)
				  {
			  ?>
          <li> <a href="<?php echo base_url(); ?>category/<?php echo $detail_product_category->product_category_id; ?>.html"><?php echo $detail_product_category->cate_name; ?></a>
          <?php if($detail_product_category->root_cate ==0 && $detail_product_category->product_category_id == $sosanh_cate_id || $detail_product_category->product_category_id == $meta_seo['load_parent_cate_id'])
		  {?>
            <ul>
              <?php

								$whereProductCategorySub = array('parent_cate' => $detail_product_category->product_category_id,'level_cate' => 1, 'lang_id' => 1, 'status_cate' => 1);
								$orderProductCategorySub = array();
								$productCategorySub = $CI->main_model->getAllData("luxyart_tb_product_category", $whereProductCategorySub, $orderProductCategorySub)->result();
								if(!empty($productCategorySub)){
									foreach($productCategorySub as $detailProductCategorySub){
							?>
              							<li><a href="<?php echo base_url(); ?>category/<?php echo $detailProductCategorySub->product_category_id; ?>.html">
										<?php if($detailProductCategorySub->product_category_id == $sosanh_cate_id){echo '<b>'.$detailProductCategorySub->cate_name.'</b>';}else {echo $detailProductCategorySub->cate_name;} ?></a></li>
              				<?php
								}
							}?>
            </ul>
            <?php }?> 
          </li>
          <?php }}?>
        </ul>
      </div>
          <div class="category-sub__sp-cat">
            <select onchange="getval(this);">
              <option value="0"><a href="<?php echo base_url(); ?>category.html">写真素材のカテゴリー</option>
              <?php
              if(!empty($list_product_category))
			  {
				  if($cate_path_level==0)
					 {
						 $cate_sosanh = $sosanh_cate_id;
					 }
					 else
					 {
						  $cate_sosanh = $cate_path_level;
					 }
				  foreach($list_product_category as $detail_product_category)
				  {
			  ?>
           <option value="<?php echo $detail_product_category->product_category_id; ?>" <?php if($detail_product_category->product_category_id == $cate_sosanh){echo 'selected="selected"';}?>><a href="<?php echo base_url(); ?>category/<?php echo $detail_product_category->product_category_id; ?>.html"><?php echo $detail_product_category->cate_name; ?></a></option> 
            <?php } }?>
            </select>
          </div>
          <div class="category-sub__sp-cat" style="margin-top:20px">
            <select onchange="getval(this);">
             <?php //if($detail_product_category->root_cate ==0 && $detail_product_category->product_category_id == $sosanh_cate_id || $detail_product_category->product_category_id == $meta_seo['load_parent_cate_id'])
			 if($cate_path_level==0)
			 {
				 $cate_load = $sosanh_cate_id;
			 }
			 else
			 {
				  $cate_load = $cate_path_level;
			 }
		  //{?>
          <option value="<?php echo $cate_load?>">写真素材のカテゴリー</option>
              <?php

								$whereProductCategorySub = array('parent_cate' => $cate_load,'level_cate' => 1, 'lang_id' => 1, 'status_cate' => 1);
								$orderProductCategorySub = array();
								$productCategorySub = $CI->main_model->getAllData("luxyart_tb_product_category", $whereProductCategorySub, $orderProductCategorySub)->result();
								if(!empty($productCategorySub)){
									foreach($productCategorySub as $detailProductCategorySub){
										if($detailProductCategorySub->cate_name!="")
										{
							?>
              							<option value="<?php echo $detailProductCategorySub->product_category_id; ?>" <?php if($detailProductCategorySub->product_category_id == $sosanh_cate_id){echo 'selected="selected"';}?>><?php echo $detailProductCategorySub->cate_name; ?></a></option>
              				<?php
								}
									}
							}?>
           
            </select>
          </div>
        </div>
        <div class="list-main" style="min-height:1000px">
          <div class="breadly">
            <ul>
              <li><a href="<?php echo base_url(); ?>"><span itemprop="title">ホーム</span></a></li>
              <?php if($cate_path_level!=0){?>
              <li><a href="<?php echo base_url(); ?>category/<?php echo $cate_path_level?>.html"><span itemprop="title">
              <?php echo $cate_path_level_name?>の写真素材</span></a></li>
              <li><span itemprop="title"><?php echo $meta_seo['page_h1'];?></span></li>
			  <?php }else {?>
              <li><span itemprop="title"><?php echo $meta_seo['page_h1']; ?>の写真素材</span></li>
              <?php }?>
            </ul>
          </div>
          <div class="list-search">
          <form id="form" action="<?php echo base_url(); ?>search" method="get">
            <div class="list-search__form">
              <div class="list-search__text">
                <input name="key_search" type="text" placeholder="キーワードで検索" value="<?php if($search_key!="none" && $search_key!=""){echo $search_key;} else {echo '';}?>">
              </div>
              <div class="list-search__submit"  onclick="document.getElementById('form').submit();">
                <input type="submit">
              </div>
            </div>
            <div class="list-search__radio">
              <div>
                <label>
                  <input type="radio" name="cate" value="<?php echo $sosanh_cate_id?>" <?php if($_GET['cate']!='all'){echo 'checked';}?>>カテゴリ内を検索
                </label>
              </div>
              <div>
                <label>
                  <input type="radio" name="cate" value="all" <?php if($_GET['cate']=='all'){echo 'checked';}?>>すべてから検索
                </label>
              </div>
              <div id="detail-search-btn" class="detail-btn">詳細</div>
            </div>
          </div>
          <div id="list-search-detail" class="list-search-detail">
            <div class="list-search-detail__column"><span><?php if($search_option1!=""){echo $search_option1;} else {echo '人気順';}?></span>
              <div id="get_option_1" class="list-search-detail__select1">
                <select>
                  <option value="人気順">人気順</option>
                  <option value="新着順">新着順</option>
                </select>
              </div>
            </div>
            <div class="list-search-detail__column"><span><?php if($search_option2==2){echo '縦';} elseif($search_option2==1){echo '横';} else {echo '縦横';}?></span>
              <div id="get_option_2" class="list-search-detail__select2">
                <select name="typeimage[]">
                  <option value="0">縦横</option>
                  <option value="2" <?php if($search_option2==2){echo 'selected';}?>>縦</option>
                  <option value="1" <?php if($search_option2==1){echo 'selected';}?>>横</option>
                </select>
              </div>
            </div>
            <div id="color-column" class="list-search-detail__column"><span>カラー</span>
              <div id="list-search-detail__color" class="list-search-detail__color">
               <?php if(!empty($list_color))
					{
						echo '<ul>';
						foreach($list_color as $detail_color =>$load_color)
						{
							echo '<li>';
								if (in_array($load_color->id_color, $search_color))
								  {
								 		echo '<label class="label-select" id="'.$load_color->name_color.'" for="color-'.$load_color->name.'">&nbsp;</label>';
										echo '<input checked="checked" name="color[]" type="checkbox" id="color-'.$load_color->name_color.'" value="'.$load_color->id_color.'">';
								  }
								else
								  {
								  		echo '<label id="'.$load_color->name_color.'" for="color-'.$load_color->name.'">&nbsp;</label>';
										echo '<input type="checkbox" name="color[]" id="color-'.$load_color->name_color.'" value="'.$load_color->id_color.'">';
								  }
								
							echo '</li>';
						}
						echo '</ul>';
					}?>
              </div>
            </div>
            <div id="list-search-detail__limit" class="list-search-detail__limit"><span id="time" class="limit-time <?php if($search_ltime==1){echo 'select';}?>">24時間限定</span><?php if($search_ltime==1){echo '<input id="inputtime" type="hidden" name="limittime" value="1">';}?><span  id="num" class="limit-num <?php if($search_lnum==2){echo 'select';}?>">数量限定</span><?php if($search_lnum==2){echo '<input id="inputnum" type="hidden" name="limitnum" value="2">';}?></div>
            <div class="list-search-detail__reset"><span>リセット</span></div>
            <div id="pagesort" class="list-search-detail__page-num">
              <p>表示数：<?php if($search_page==50){echo'<b>50</b>';} else {echo '<a id="50">50</a>';}?>｜<?php if($search_page==100){echo'<b>100</b>';} else {echo '<a id="100">100</a>';}?>｜<?php if($search_page==200){echo'<b>200</b>';} else {echo '<a id="200">200</a>';}?>
              <input id="sort_page" type="hidden" name="numpage" value="<?php echo $search_page?>"></p>
            </div>
          </div>
          </form>
          <div class="list-category">
            <dl>
              <dt>カテゴリー：</dt>
              <dd> 
                <ul>
                  <?php foreach($list_pickup_category as $show_list_pickup_category)
{?>
					<li><a href="<?php echo base_url(); ?>category/<?php echo $show_list_pickup_category->product_category_id; ?>.html"><?php echo $show_list_pickup_category->cate_name; ?></a></li>
				<?php }?>
                </ul>
              </dd>
            </dl>
          </div>
          <div class="list-img">
            <div id="container-02" class="list-img__wrapper flex-images">
              <?php if(!empty($load_product_with_condition_search)){ foreach($load_product_with_condition_search as $load_list_img_condition_search){?>
            <div class="pinto"><div id="img_in_list_<?php echo $load_list_img_condition_search->id_img;?>" class="list-img__favorite <?php if($is_login_user==1){if (in_array($load_list_img_condition_search->id_img, $check_bookmark_images)){echo 'set';}}?>"></div>
            <a href="<?php echo base_url(); ?>detail/<?php echo $load_list_img_condition_search->img_code;?>.html"><img alt="<?php echo $load_list_img_condition_search->img_title;?>" src="<?php echo $load_list_img_condition_search->server_path_upload;?>/<?php echo $load_list_img_condition_search->file_name_watermark_img_1;?>"></a>
			</div>
            <?php }}?>
            </div>
            <div class="am-container" id="am-container">
            <?php if(!empty($load_product_with_condition_search)){ foreach($load_product_with_condition_search as $load_list_img_condition_search){?>
            <a href="<?php echo base_url(); ?>detail/<?php echo $load_list_img_condition_search->img_code;?>.html"><img alt="<?php echo $load_list_img_condition_search->img_title;?>" src="<?php echo $load_list_img_condition_search->server_path_upload;?>/<?php echo $load_list_img_condition_search->file_name_watermark_img_1;?>"></a>
            <?php }}?>
            </div>
            <!--<div class="list-img__next-btn"><a href="list.html">次のページへ</a></div>-->
          </div>
          <?php if(@$phantrang && $phantrang!=""){echo $phantrang;}?>
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

function getval(sel)
{
    //alert(sel.value);
	if(sel.value!=0)
	{
		window.location.href = "<?php echo base_url(); ?>category/"+ sel.value +".html";
	}
}
$('#list-search-detail__limit').on('click','span',function(){
		var id = this.id;
		if(id=='time')
		{
			var value=1;
		}
		if(id=='num')
		{
			var value=2;
		}
		if($(this).hasClass('select') )
		{
  			$("#input"+id).remove();
			$(this).toggleClass('select');
		}
		else
		{
			var check_id=$("#input"+id).length;
			if(check_id==0)
			{
				$(this).append('<input id="input'+id+'" type="hidden" name="limit'+id+'" value="'+value+'">');
				$('#form').append('<input id="input'+id+'" type="hidden" name="limit'+id+'" value="'+value+'">');
			}
			$(this).toggleClass('select');
		}
	});
$('#pagesort').on('click','a',function(){
			var id_pagesort='';
			id_pagesort = this.id;
			$('#sort_page').val(id_pagesort);
			var url = window.location.href.split("&numpage=");
			$(location).attr('href',url[0]+'&numpage='+id_pagesort);
	});
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
</script>