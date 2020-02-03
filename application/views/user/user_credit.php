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
              <li><a href="<?php echo base_url()?>"><span itemprop="title"><?php echo $this->lang->line('path_home'); ?></span></a></li>
              <li><span itemprop="title"><?php echo $this->lang->line('user_credit_list_h2'); ?></span></li>
            </ul>
          </div>
          <div class="contents-cart__sub">
            <div class="cart-credit">
              <div class="cart-credit__all">
                <p><?php echo $this->lang->line('user_credit_list_text_show_point'); ?><span><span><?php echo $user_point;?></span></span></p>
              </div>
            </div>
          </div>
          <div class="contents-cart__main">
            <h2 class="contents-cart__find"><?php echo $this->lang->line('user_credit_list_h2'); ?></h2>
            <div class="credit-table">
              <div class="credit-table__header">
                <p class="w260"><?php echo $this->lang->line('user_credit_list_col_head_1'); ?></p>
                <p class="w160"><?php echo $this->lang->line('user_credit_list_col_head_2'); ?></p>
                <p class="w160"><?php echo $this->lang->line('user_credit_list_col_head_3'); ?></p>
              </div>
              
              <div class="credit-table__body">
              <?php
			  function check_num($num){
    if (preg_match("/.00/", $num)){
        echo number_format($num);
    }else{
        echo $num;
    }
}
					if(!empty($list_buy_point)){
						foreach($list_buy_point as $show_list_buy_point){?>
                <div class="credit-table__list">
                  <p class="w260"><?php echo $show_list_buy_point->name_credit;?> <?php echo number_format($show_list_buy_point->point_credit);?><?php echo $this->lang->line('user_credit_list_col_head_1'); ?></p>
                  <p class="w160"><?php echo number_format($show_list_buy_point->money_pay_credit);?><?php echo $this->lang->line('user_credit_list_show_currency'); ?></p>
                  <p class="w160"><?php check_num($show_list_buy_point->value_point_money);?></p>
                  <div class="credit-table__btn"><a href="<?php echo base_url()?>credit-select/<?php echo $show_list_buy_point->id_list;?>.html"><?php echo $this->lang->line('user_credit_list_button_buy_point'); ?></a></div>
                </div>
                <?php }}?>
              </div>
            </div>
            <div class="credit-guide">
              <p class="credit-guide__find"><?php echo $this->lang->line('user_credit_list_text_show_bottom1'); ?></p>
              <div class="credit-guide__text">
                <p><?php echo $this->lang->line('user_credit_list_text_show_bottom2'); ?></p>
              </div>
            </div>
          </div>
        </div>
      </div>