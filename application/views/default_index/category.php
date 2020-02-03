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

<div class="contents-category">
  <div style="background: url(<?php echo base_url(); ?>publics/img/img-ttl-category.png) center center;background-size: cover" class="ttl-category">
    <div class="ttl-category__filter">
      <div class="ttl-category__box">
        <h1><?php echo $meta_seo['page_h1']; ?></h1>
      </div>
    </div>
  </div>
  <div class="contents-category__wrapper clearfix">
    <div class="breadly">
      <ul>
        <li><a href="index.html" itemtype=""><span itemprop="title">ホーム</span></a></li>
        <li><span itemprop="title"><?php echo $meta_seo['page_h1']; ?></span></li>
      </ul>
    </div>
    <div class="category-sub">
      <div class="category-sub__cat">
        <p>写真のカテゴリー</p>
        <ul>
          <?php
              if(!empty($list_product_category))
			  {
				  foreach($list_product_category as $detail_product_category)
				  {
			  ?>
          <li> <a href="<?php echo base_url(); ?>category/<?php echo $detail_product_category->product_category_id; ?>.html"><?php echo $detail_product_category->cate_name; ?></a> 
            <!--<ul>
              <?php

								/*$whereProductCategorySub = array('parent_cate' => $detail_product_category->product_category_id,'level_cate' => 1, 'lang_id' => 1, 'status_cate' => 1);
								$orderProductCategorySub = array();
								$productCategorySub = $CI->main_model->getAllData("luxyart_tb_product_category", $whereProductCategorySub, $orderProductCategorySub)->result();
								if(!empty($productCategorySub)){
									foreach($productCategorySub as $detailProductCategorySub){
							?>
              							<li><a><?php echo $detailProductCategorySub->cate_name; ?></a></li>
              				<?php
								}
							}*/?>
            </ul>--> 
          </li>
          <?php }}?>
        </ul>
      </div>
      <div class="category-sub__sp-cat">
        <select onchange="getval(this);">
          <option value="0">写真素材のカテゴリー</option>
          <?php
              if(!empty($list_product_category))
			  {
				  foreach($list_product_category as $detail_product_category)
				  {
				?>
          <option  value="<?php echo $detail_product_category->product_category_id; ?>"><?php echo $detail_product_category->cate_name; ?></option>
          <?php } }?>
        </select>
      </div>
    </div>
    <div class="category-main">
      <h2 class="contents-category__find">人物の写真素材<span>(<?php echo $count_all_product;?>件)</span></h2>
      <div class="category-main__text">
        <p>人物カテゴリーでは、「人」に関するあらゆる写真素材を、わかりやすく分類してご紹介しています。日本人、外国人、家族やビジネスなど、求めるテーマに沿った人物写真が見つけやすくなっています。また、年齢、構図、感情、行動、季節などでの検索も可能。人物全体像だけでなく、手や顔、目、足、肌、指先など、ピンポイントで強調して使える体のパーツ素材も取り揃っています。</p>
      </div>
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
              <img alt="<?php echo $detail_product_category->cate_name; ?>" src="<?php echo base_url(); ?>publics/cate_img/<?php echo $detail_product_category->img_category; ?>"  style="height:170px!important"></a></div>
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
      <div class="category-tab">
        <ul>
          <li class="current">
            <div class="popular"><span>人物の</span>人気素材</div>
          </li>
          <li>
            <div class="recent"><span>人物の</span>新着素材</div>
          </li>
        </ul>
      </div>
      <div class="category-img">
        <div class="category-img__popular">
          <ul>
            <?php if(!empty($list_product_img_popular)){ foreach($list_product_img_popular as $load_list_img_popular){?>
            <li class="category-img__list">
              <div class="category-img__wrapper">
                <div class="category-img__inner"><a href="<?php echo base_url(); ?>detail/<?php echo $load_list_img_popular->img_code;?>.html"><img alt="<?php echo $load_list_img_popular->img_title;?>" src="<?php echo $load_list_img_popular->server_path_upload;?>/<?php echo $load_list_img_popular->file_name_watermark_img_1;?>"></a></div>
                <p><?php echo $load_list_img_popular->img_title;?></p>
              </div>
            </li>
            <?php }}?>
          </ul>
        </div>
        <div class="category-img__recent">
          <ul>
            <?php if(!empty($list_product_img_moinhat)){ foreach($list_product_img_moinhat as $load_list_img_moi_nhat){?>
            <li class="category-img__list">
              <div class="category-img__wrapper">
                <div class="category-img__inner"><a href="<?php echo base_url(); ?>detail/<?php echo $load_list_img_moi_nhat->img_code;?>.html"><img alt="<?php echo $load_list_img_moi_nhat->img_title;?>" src="<?php echo $load_list_img_moi_nhat->server_path_upload;?>/<?php echo $load_list_img_moi_nhat->file_name_watermark_img_1;?>"></a></div>
                <p><?php echo $load_list_img_moi_nhat->img_title;?></p>
              </div>
            </li>
            <?php }}?>
          </ul>
        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
function getval(sel)
{
    //alert(sel.value);
	if(sel.value!=0)
	{
		window.location.href = "<?php echo base_url(); ?>category/"+ sel.value +".html";
	}
}
</script>