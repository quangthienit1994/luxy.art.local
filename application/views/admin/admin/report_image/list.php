﻿<?php
$CI =& get_instance();
$CI->config->load();
$this->lang->load('dich', 'ja');
?>

<section class="content_box5">
  <h3><?php echo $this->lang->line('unchecked_image'); ?></h3>
  <article class="content5a">
	
    <?php if(count($list)==0){ ?>
      <div class="alert alert-danger fade in"><strong><?php echo $this->lang->line('currently_photos_are_not_reported'); ?></strong></div>
      <?php }else{?>

    <?php foreach ($list as $key => $value) { ?>
<div class="imgs5">
    <p><a class="iframe" href="admin/report_image_info/<?=$value->report_image_id?>" target="_blank"><img src="<?=$value->server_path_upload.'/'.$value->file_name_original_img?>"  onerror="this.src='publics/product_img/image_default.png'"></a></p>
</div>
    <?php } ?>
    <?php } ?>

  </article>
</section>

<?php if(count($list)!=0){ ?>
<section class="mb5b_button">
  <form method="post" action="admin/status_report_image_full" onsubmit="return submitAjax(this, {load:2000})">
    <input type="hidden" name="status" value="1">
    <input type="submit" value="<?php echo $this->lang->line('all_images_check_ok'); ?>">
  </form>
</section>
<?php } ?>


<center>
  <ul class="pagination">
    <?php for($i = 1; $i <= $numPage; $i++) { ?>
    <li <?=$i==$currentPage?"class='active'":''?>><a href="admin/page_list_report_image/<?=$i?>?keyword=<?=$keyword?>"><?=$i?></a></li>
    <?php } ?>
  </ul>
</center>