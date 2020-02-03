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
<div class="contents-guideline">
        <div class="ttl-guideline" style="background: url('<?php echo base_url().'publics/page/'.$page_show_img_banner;?>') center center;background-size: cover;">
          <div class="ttl-guideline__filter">
            <div class="ttl-guideline__box">
              <h1><?php echo $page_h1;?></h1>
            </div>
          </div>
        </div>
        <div class="contents-guideline__main">
          <div class="breadly">
            <ul>
              <li><a href="<?php echo base_url()?>"><span itemprop="title"><?php echo $this->lang->line('path_home'); ?></span></a></li>
              <li><span itemprop="title"><?php echo $title;?></span></li>
            </ul>
          </div>
          <?php echo $content_page_show;?>
        </div>
      </div>