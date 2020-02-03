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
<div class="contents-cart">
        <div class="contents-cart__wrapper clearfix">
          <div class="breadly">
            <ul>
              <li><a href="<?php echo base_url()?>"><span itemprop="title">ホーム</span></a></li>
              <li><span itemprop="title">クレジットの購入</span></li>
            </ul>
          </div>
          <?php
echo '<pre />';
print_r($PayPalResult);
?>
        </div>
      </div>