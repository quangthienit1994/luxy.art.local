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
<div style="background: url(<?php echo base_url(); ?>publics/img/mainvisual.jpg) center; background-size: cover;" class="top-mainvisual">
  <div class="top-mainvisual__box">
    <p class="top-mainvisual__read">誰ともかぶらない<br />
      オリジナル素材を格安で</p>
    <div class="top-mainvisual__form">
      <form id="searchtop" action="<?php echo base_url(); ?>search" method="get">
        <div class="top-mainvisual__form-text">
          <input name="key_search" type="text" placeholder="キーワードで検索">
        </div>
        <div class="top-mainvisual__form-type"><span>絞り込み検索</span>
          <div class="top-detail-search">
            <dl>
              <dt>カテゴリー</dt>
              <dd>
                <?php
					if(!empty($list_category))
					{
						echo '<select name="cate">';
							echo '<option value="all">全て選択</option>';
							foreach($list_category as $detail_category)
							{
								echo '<option value="'.$detail_category->cate_id.'">'.$detail_category->cate_name.'</option>';
							}
						echo '</select>';
					}
				?>
              </dd>
              <dt>写真の向き</dt>
              <dd>
                <div class="top-detail-search__imgform">
                  <label>
                    <input type="radio" name="typeimage[]" value="0">
                    すべて </label>
                  <label>
                    <input type="radio" name="typeimage[]" value="2">
                    縦 </label>
                  <label>
                    <input type="radio" name="typeimage[]" value="1">
                    横 </label>
                </div>
              </dd>
              <dt>カラー</dt>
              <dd>
                <ul class="top-detail-search__color">
                  <?php if(!empty($list_color))
					{
						echo '<ul>';
						foreach($list_color as $detail_color)
						{
							echo '<li>';
							echo '<label id="'.$detail_color->name_color.'" for="color-'.$detail_color->name.'">&nbsp;</label>';
							echo '<input type="checkbox" name="color[]" id="color-'.$detail_color->name_color.'" value="'.$detail_color->id_color.'">';
							echo '</li>';
						}
						echo '</ul>';
					}?>
                </ul>
              </dd>
              <dt>限定</dt>
              <dd>
                <div class="top-detail-search__limit">
                  <label class="limit-time">
                    <input type="checkbox" name="limittime" value="1">
                    24間限定 </label>
                  <label class="limit-num">
                    <input type="checkbox" name="limitnum" value="2">
                    数量限定 </label>
                </div>
              </dd>
            </dl>
          </div>
        </div>
        <div class="top-mainvisual__form-submit"  onclick="document.getElementById('searchtop').submit();">
          <input type="submit" value="">
        </div>
      </form>
    </div>
    <div class="top-mainvisual__recommend">
      <dl>
        <dt>PICK UP!</dt>
        <dd>
		<?php foreach($list_pickup_category as $show_list_pickup_category)
{?>
					<a href="<?php echo base_url(); ?>category/<?php echo $show_list_pickup_category->product_category_id; ?>.html"><?php echo $show_list_pickup_category->cate_name; ?></a>
				<?php }?>
        </dd>
      </dl>
    </div>
  </div>
  <div class="top-mainvisual__info"></div>
</div>
<div class="top-guideline">
  <div class="top-guideline__filter">
    <div class="top-guideline__box">
      <h1 class="top-guideline__ttl"><?php echo $this->lang->line('site_title'); ?>で欲しい写真素材を手に入れる2つの方法！</h1>
      <div class="top-guideline__column">
        <div class="top-guideline__search">
          <p>出版、テレビCM、商品パッケージなどにも<br />
            ご利用可いただける写真素材をこちらで用意しました。<br />
            検索やカテゴリーから目当ての写真を探して購入できる、<br />
            通常タイプの写真素材です。</p>
          <div class="top-guideline__search-btn"><a href="<?php echo base_url(); ?>category.html">カテゴリー一覧から探す</a></div>
        </div>
      </div>
      <div class="top-guideline__column">
        <div class="top-guideline__compe">
          <p>「気に入った写真素材が見つからない…」<br />
            そんなときにご利用いただけるコンペ機能。<br />
            あなたの要望に合った写真をコンペ形式で募集。<br />
            格安でオリジナル素材を手に入れることができます。</p>
          <div class="top-guideline__compe-btn"><a href="<?php echo base_url()?>list-compe.html">コンペ機能について詳しく見る</a></div>
        </div>
      </div>
    </div>
  </div>
</div>
<section class="top-category">
  <div class="top-category__wrapper">
    <h2 class="top-category__find">カテゴリー一覧<span>Category</span></h2>
    <div class="category-list">
      <ul>
        <?php
			if(!empty($list_product_category))
			{
				foreach($list_product_category as $detail_product_category)
				{
			?>
        <li>
          <div class="category-list__thumb"><a href="<?php echo base_url(); ?>category/<?php echo $detail_product_category->product_category_id; ?>.html">
            <p><?php echo $detail_product_category->cate_name; ?></p>
            <img alt="<?php echo $detail_product_category->cate_name; ?>" src="<?php echo base_url(); ?>publics/cate_img/<?php echo $detail_product_category->img_category; ?>" style="height:170px!important"></a></div>
          <div class="category-list__cat-child">
            <ul>
              <?php
              $whereProductCategorySub = array('parent_cate' => $detail_product_category->product_category_id,'level_cate' => 1, 'lang_id' => 1, 'status_cate' => 1);
			  $orderProductCategorySub = array();
			  $productCategorySub = $CI->main_model->getAllData("luxyart_tb_product_category", $whereProductCategorySub, $orderProductCategorySub)->result();
			  if(!empty($productCategorySub))
			  {
				  foreach($productCategorySub as $detailProductCategorySub)
				  {
			?>
              <li><a href="<?php echo base_url(); ?>category/<?php echo $detailProductCategorySub->product_category_id; ?>.html"><?php echo $detailProductCategorySub->cate_name; ?></a></li>
              <?php }}?>
            </ul>
          </div>
        </li>
        <?php }}?>
      </ul>
    </div>
  </div>
</section>
<section class="top-popular-user">
  <div class="top-popular-user__wrapper">
    <h2 class="top-popular-user__find">人気クリエイター<span>Popular User</span></h2>
    <div class="timeline-related-user">
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
        <li><a href="<?php echo base_url(); ?>timeline/<?php echo $detail_user_follow->user_id; ?>.html">
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
          </a>
          <div class="timeline-related-user__meta">
            <p class="name"><a href="<?php echo base_url(); ?>timeline/<?php echo $detail_user_follow->user_id; ?>.html"><span><?php echo $user->display_name?></span></a></p>
            <p class="num">投稿数<a href="<?php echo base_url(); ?>timeline/<?php echo $detail_user_follow->user_id; ?>.html"><?php echo $countProduct + $CI->manager_user_model->getcountget_sell($user->user_id); ?></a></p>
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
                  					<img alt="<?php echo $detailProductImg->img_title; ?>" src="<?php echo $detailProductImg->server_path_upload?>/<?php echo $detailProductImg->file_name_watermark_img_1; ?>">
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
                  			<img alt="<?php echo $detailProductImg->img_title; ?>" src="<?php echo $detailProductImg->server_path_upload?>/<?php echo $detailProductImg->file_name_watermark_img_1; ?>">
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
</section>
<div class="top-creator-entry">
  <div class="top-creator-entry__filter">
    <h3 class="top-creator-entry__find">スマホで撮った写真で<br />
      お小遣い稼ぎ！</h3>
    <div class="top-creator-entry__text">
      <p><?php echo $this->lang->line('site_title'); ?>ではあなたのスマートフォンのフォルダの中で眠っている写真でお小遣い稼ぎすることが出来る画像売買サイトです！</p>
    </div>
    <div class="top-creator-entry__btn"><a href="<?php echo base_url()?>creator-entry.html">スマホ１つで簡単無料登録！</a></div>
  </div>
</div>
<div class="top-compe">
  <h2 class="top-compe__find">開催中コンペ一覧<span>Competition</span></h2>
  <div class="top-compe__wrapper">
    <div class="compe-list">
      <ul>
        <?php
			if(!empty($list_competition))
			{	
				foreach($list_competition as $detail_list_competition)
				{
					$whereUser = array('user_id' => $detail_list_competition->user_id,'user_status' => 1);
					$orderUser = array();
					$user = $CI->main_model->getAllData("luxyart_tb_user", $whereUser, $orderUser)->row();
			?>
        <li><a href="<?php echo base_url(); ?>compe-detail/<?php echo $detail_list_competition->id_competition ?>.html">
          <div class="compe-list__thumb"><img src="<?php echo base_url(); ?>publics/competition_img/<?php echo $detail_list_competition->photo_des_com; ?>"></div>
          <div class="compe-list__meta">
            <div class="compe-list__icon">
              <?php 
			  if($user->user_avatar != ""){?>
              <img alt="Avatar" src="<?php echo base_url(); ?>publics/avatar/<?php echo $user->user_avatar; ?>">
              <?php } else{?>
              <img alt="No avatar" src="<?php echo base_url(); ?>publics/avatar/prof-img.png">
              <?php }?>
            </div>
            <p class="compe-list__ttl"><?php echo $detail_list_competition->title_com; ?></p>
            <p class="compe-list__name"><?php echo $user->display_name?></p>
          </div>
          <div class="compe-list__footer">
            <div class="compe-list__price"><span>賞金</span><?php echo $detail_list_competition->point_img_com*$detail_list_competition->img_quantity_com; ?>円</div>
            <div class="compe-list__limit"><span>残り</span><?php echo $CI->manager_compe_model->get_time_remain_apply($detail_list_competition->date_end_com); ?>
			</div>
          </div>
          </a></li>
        <?php }}?>
      </ul>
      <div class="compe-list__more-btn"><a href="<?php echo base_url()?>list-compe.html">もっと見る</a></div>
    </div>
  </div>
</div>
<div class="top-app">
  <div class="top-app__wrapper">
    <h3 class="top-app__find"><?php echo $this->lang->line('site_title'); ?>アプリでスマホから簡単操作!</h3>
    <div class="top-app__text">
      <p><?php echo $this->lang->line('site_title'); ?>に画像を投稿したり、お気に入りの画像を簡単管理できる公式アプリです！</p>
      <p style="margin-bottom:75px">※アプリは現在開発中</p>
    </div>
    <div class="top-app__btn">
      <ul>
        <li><a href="#"><img src="<?php echo base_url(); ?>publics/img/btn-app.png" alt="AppStoreからダウンロード"></a></li>
        <li><a href="#"><img src="<?php echo base_url(); ?>publics/img/btn-gp.png" alt="GooglePlayからダウンロード"></a></li>
      </ul>
    </div>
  </div>
</div>
<div class="top-app-sp"><a class="top-app-sp__link">
  <div class="top-app-sp__icon"><img alt="AppStoreからダウンロード-GooglePlayからダウンロード" src="<?php echo base_url(); ?>publics/img/icon-app.png"></div>
  <div class="top-app-sp__text">
    <p><span><?php echo $this->lang->line('site_title'); ?>アプリ</span>を<br />
      今すぐダウンロード！</p>
  </div>
  </a></div>
<script type="text/javascript">
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
											 $('#show2_text_follow'+user_id_bi_click).html('<?php echo $this->lang->line('to_follow_exits'); ?>');
					                    }
					                    else if(msg[1]==1)
					                    {
											$('#show2_text_follow'+user_id_bi_click).html('<?php echo $this->lang->line('to_follow_exits'); ?>');
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
											 $('#show2_text_follow'+user_id_bi_click).html('<?php echo $this->lang->line('to_follow'); ?>');
					                    }
					                    else if(msg[1]==1)
					                    {
											$('#show2_text_follow'+user_id_bi_click).html('<?php echo $this->lang->line('to_follow'); ?>');
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