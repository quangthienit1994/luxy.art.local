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
<div class="contents-other">
        <div class="ttl-surport">
          <div class="ttl-surport__filter">
            <div class="ttl-surport__box">
              <h1><?php echo $this->lang->line('page_contact_h1')?></h1>
            </div>
          </div>
        </div>
        <div class="contents-other__main">
          <div class="breadly">
            <ul>
              <li><a href="<?php echo base_url()?>"><span itemprop="title"><?php echo $this->lang->line('path_home'); ?></span></a></li>
              <li><span itemprop="title"><?php echo $this->lang->line('page_contact_h1')?></span></li>
            </ul>
          </div>
          <div class="contents-other__body">
            <h2><?php echo $this->lang->line('page_contact_h2_notice_contact_complete')?></h2>
            <?php echo $this->lang->line('page_contact_notice_contact_complete_text')?>
          </div>
        </div>
      </div>