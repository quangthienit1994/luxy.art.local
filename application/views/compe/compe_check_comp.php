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

?>



<div class="contents-compe-form">
<div class="contents-compe-form__main">
  <div class="breadly">
    <ul>
      <li><a href="<?php echo base_url(); ?>" itemtype="http://data-vocabulary.org/Breadcrumb"><span itemprop="title"><?php echo $this->lang->line('home'); ?></span></a></li>
      <li><span itemprop="title"><?php echo $this->lang->line('competition_review_completed'); ?></span></li>
    </ul>
  </div>
  <div class="contents-other__body">
    <h2><?php echo $this->lang->line('title_the_competition_review_has_been_completed'); ?></h2>
    <p><?php echo $this->lang->line('content_the_competition_review_has_been_completed'); ?></p>
    <div class="compe-payment-btn"><a href="<?php echo base_url(); ?>mypage.html"><?php echo $this->lang->line('to_competition_prize_payment_procedure'); ?></a></div>
  </div>
</div>
</div>