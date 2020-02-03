<?php 
$CI =& get_instance();
$CI->config->load();
$logged_in = $CI->session->userdata('logged_in');
$user_id = $logged_in['user_id'];
$user_firstname = $logged_in['user_firstname'];
$user_lastname = $logged_in['user_lastname'];
$user_level = $logged_in['user_level'];
$CI->load->model('manager_user_model');
$user = $this->manager_user_model->get_user_id($user_id);
	$get_name_email = explode('@',$user->user_email);
	if($user_lastname=="")
	{
		$name_call = $get_name_email[0];
	}
	else
	{
		$name_call = $user_lastname;
	}
?>


<div class="mypage-sub">
        <div class="mypage-sub__user-info">
          <p class="name"><?php echo $this->lang->line('welcome'); ?><span><?php echo $name_call;?></span><?php echo $this->lang->line('san'); ?></p>
          <p class="id"><?php echo $this->lang->line('user_id'); ?> <?php echo $user->user_id;?></p>
        </div>
        <div class="mypage-sub__credit">
          <p class="credit"><?php echo $this->lang->line('balance'); ?>：<span><?php echo $user->user_paymoney_getpoint + $user->user_point;?></span></p>
          <p class="link"><a href="<?php echo base_url()?>credit.html"><?php echo $this->lang->line('purchase_credit'); ?>>></a></p>
        </div>
        <div class="mypage-sub__menu">
          <ul>
          	
            <?php if($user_level>=1){?>
            	<li><a href="<?php echo base_url()?>mypage.html" class="menu-top"><?php echo $this->lang->line('my_page_top'); ?></a><?php }?>
            
            <?php // account level 2:Creator, 3:Gold, 4:Platinum, 5:Enterprise
			if($user_level ==1){?>
            	<ul>
                <li <?php if($active_menu_sidebar=="mypage-list-compe"){echo 'class="active"';} else {echo '';}?>><a href="<?php echo base_url()?>mypage/list-compe.html"><?php echo $this->lang->line('list_compe'); ?></a></li>
                <li <?php if($active_menu_sidebar=="mypage-post-compe"){echo 'class="active"';} else {echo '';}?>><a href="<?php echo base_url()?>mypage/post-compe.html"><?php echo $this->lang->line('post_compe'); ?></a></li>
                <li <?php if($active_menu_sidebar=="mypage-list-compe-join"){echo 'class="active"';} else {echo '';}?>><a href="<?php echo base_url()?>mypage/list-compe-join.html"><?php echo $this->lang->line('list_picture_join_competition'); ?></a></li>
              	</ul>
            <? }
			elseif($user_level ==2){?>
            	<ul>
                <li <?php if($active_menu_sidebar=="mypage-list-images"){echo 'class="active"';} else {echo '';}?>><a href="<?php echo base_url()?>mypage/list-images.html"><?php echo $this->lang->line('list_picture'); ?></a></li>
                <li <?php if($active_menu_sidebar=="mypage-post-image"){echo 'class="active"';} else {echo '';}?>><a href="<?php echo base_url()?>mypage/post-image.html"><?php echo $this->lang->line('add_picture'); ?></a></li>
                <li <?php if($active_menu_sidebar=="mypage-list-compe"){echo 'class="active"';} else {echo '';}?>><a href="<?php echo base_url()?>mypage/list-compe.html"><?php echo $this->lang->line('list_compe'); ?></a></li>
                <li <?php if($active_menu_sidebar=="mypage-post-compe"){echo 'class="active"';} else {echo '';}?>><a href="<?php echo base_url()?>mypage/post-compe.html"><?php echo $this->lang->line('post_compe'); ?></a></li>
                <li <?php if($active_menu_sidebar=="mypage-list-compe-join"){echo 'class="active"';} else {echo '';}?>><a href="<?php echo base_url()?>mypage/list-compe-join.html"><?php echo $this->lang->line('list_picture_join_competition'); ?></a></li>
              	</ul>
            <? }elseif ($user_level ==3){?>
            	<ul>
                <li <?php if($active_menu_sidebar=="mypage-list-images"){echo 'class="active"';} else {echo '';}?>><a href="<?php echo base_url()?>mypage/list-images.html"><?php echo $this->lang->line('list_picture'); ?></a></li>
                <li <?php if($active_menu_sidebar=="mypage-post-image"){echo 'class="active"';} else {echo '';}?>><a href="<?php echo base_url()?>mypage/post-image.html"><?php echo $this->lang->line('add_picture'); ?></a></li>
                <li <?php if($active_menu_sidebar=="mypage-list-compe"){echo 'class="active"';} else {echo '';}?>><a href="<?php echo base_url()?>mypage/list-compe.html"><?php echo $this->lang->line('list_compe'); ?></a></li>
                <li <?php if($active_menu_sidebar=="mypage-post-compe"){echo 'class="active"';} else {echo '';}?>><a href="<?php echo base_url()?>mypage/post-compe.html"><?php echo $this->lang->line('post_compe'); ?></a></li>
                <li <?php if($active_menu_sidebar=="mypage-list-compe-join"){echo 'class="active"';} else {echo '';}?>><a href="<?php echo base_url()?>mypage/list-compe-join.html"><?php echo $this->lang->line('list_picture_join_competition'); ?></a></li>
              	</ul>
            <? }elseif ($user_level ==4){?>
            	<ul>
                <li <?php if($active_menu_sidebar=="mypage-list-images"){echo 'class="active"';} else {echo '';}?>><a href="<?php echo base_url()?>mypage/list-images.html"><?php echo $this->lang->line('list_picture'); ?></a></li>
                <li <?php if($active_menu_sidebar=="mypage-post-image"){echo 'class="active"';} else {echo '';}?>><a href="<?php echo base_url()?>mypage/post-image.html"><?php echo $this->lang->line('add_picture'); ?></a></li>
                <li <?php if($active_menu_sidebar=="mypage-list-compe"){echo 'class="active"';} else {echo '';}?>><a href="<?php echo base_url()?>mypage/list-compe.html"><?php echo $this->lang->line('list_compe'); ?></a></li>
                <li <?php if($active_menu_sidebar=="mypage-post-compe"){echo 'class="active"';} else {echo '';}?>><a href="<?php echo base_url()?>mypage/post-compe.html"><?php echo $this->lang->line('post_compe'); ?></a></li>
                <li <?php if($active_menu_sidebar=="mypage-list-compe-join"){echo 'class="active"';} else {echo '';}?>><a href="<?php echo base_url()?>mypage/list-compe-join.html"><?php echo $this->lang->line('list_picture_join_competition'); ?></a></li>
              	</ul>
            <? }elseif ($user_level ==5){?>
            	<ul>
                <li <?php if($active_menu_sidebar=="mypage-list-images"){echo 'class="active"';} else {echo '';}?>><a href="<?php echo base_url()?>mypage/list-images.html"><?php echo $this->lang->line('list_picture'); ?></a></li>
                <li <?php if($active_menu_sidebar=="mypage-post-image"){echo 'class="active"';} else {echo '';}?>><a href="<?php echo base_url()?>mypage/post-image.html"><?php echo $this->lang->line('add_picture'); ?></a></li>
                <li <?php if($active_menu_sidebar=="mypage-list-compe"){echo 'class="active"';} else {echo '';}?>><a href="<?php echo base_url()?>mypage/list-compe.html"><?php echo $this->lang->line('list_compe'); ?></a></li>
                <li <?php if($active_menu_sidebar=="mypage-post-compe"){echo 'class="active"';} else {echo '';}?>><a href="<?php echo base_url()?>mypage/post-compe.html"><?php echo $this->lang->line('post_compe'); ?></a></li>
                <li <?php if($active_menu_sidebar=="mypage-list-compe-join"){echo 'class="active"';} else {echo '';}?>><a href="<?php echo base_url()?>mypage/list-compe-join.html"><?php echo $this->lang->line('list_picture_join_competition'); ?></a></li>
              	</ul>
            <?php }?>
            <li><span class="menu-buy"><?php echo $this->lang->line('purchase'); ?></span>
              <ul>
                <li <?php if($active_menu_sidebar=="mypage-list-order"){echo 'class="active"';} else {echo '';}?>><a href="<?php echo base_url()?>mypage/list-order.html"><?php echo $this->lang->line('list_order'); ?></a></li>
                <li <?php if($active_menu_sidebar=="mypage-user-purchase-order"){echo 'class="active"';} else {echo '';}?>><a href="<?php echo base_url()?>mypage/user-purchase-order.html"><?php echo $this->lang->line('credit_purchase_history'); ?></a></li>
                <?php if(($user->user_paymoney_getpoint + $user->user_point)>0) {?>
                <li <?php if($active_menu_sidebar=="mypage-user-manager-point"){echo 'class="active"';} else {echo '';}?>><a href="<?php echo base_url()?>mypage/user-manager-point.html"><?php echo $this->lang->line('sidebar_manager_point'); ?></a></li>
                <?php }?>
                <li <?php if($active_menu_sidebar=="mypage-user-change-bank_account"){echo 'class="active"';} else {echo '';}?>><a href="<?php echo base_url()?>mypage/user-change-bank-account.html"><?php echo $this->lang->line('sidebar_manager_bank_transfer'); ?></a></li>
                <li <?php if($active_menu_sidebar=="mypage-user-manager-card"){echo 'class="active"';} else {echo '';}?>><a href="<?php echo base_url()?>mypage/user-manager-card.html"><?php echo $this->lang->line('sidebar_manager_card_buy_point'); ?></a></li>
              </ul>
            </li>
            <li><span class="menu-search"><?php echo $this->lang->line('search'); ?></span>
              <ul>
                <li <?php if($active_menu_sidebar=="mypage-history-view-image"){echo 'class="active"';} else {echo '';}?>><a href="<?php echo base_url()?>mypage/history-view-image.html"><?php echo $this->lang->line('list_picture_history'); ?></a></li>
                <li <?php if($active_menu_sidebar=="mypage-favorite-image"){echo 'class="active"';} else {echo '';}?>><a href="<?php echo base_url()?>mypage/favorite-image.html"><?php echo $this->lang->line('list_picture_favorite'); ?></a></li>
                <li <?php if($active_menu_sidebar=="mypage-favorite-user"){echo 'class="active"';} else {echo '';}?>><a href="<?php echo base_url()?>mypage/favorite-user.html"><?php echo $this->lang->line('list_user_favorite'); ?></a></li>
              </ul>
            </li>
            <li <?php if($active_menu_sidebar=="mypage-user-message"){echo 'class="active"';} else {echo '';}?>><a href="<?php echo base_url()?>mypage/user-message.html" class="menu-message"><?php echo $this->lang->line('notice'); ?></a></li>
            <li><span class="menu-config"><?php echo $this->lang->line('change_information_registered'); ?></span>
              <ul>
                <li <?php if($active_menu_sidebar=="mypage-user-config"){echo 'class="active"';} else {echo '';}?>><a href="<?php echo base_url()?>mypage/user-config.html"><?php echo $this->lang->line('edit_information_registered'); ?></a></li>
                <li <?php if($active_menu_sidebar=="mypage-user-email"){echo 'class="active"';} else {echo '';}?>><a href="<?php echo base_url()?>mypage/user-email.html"><?php echo $this->lang->line('change_email_address'); ?></a></li>
                <li <?php if($active_menu_sidebar=="mypage-user-pass"){echo 'class="active"';} else {echo '';}?>><a href="<?php echo base_url()?>mypage/user-password.html"><?php echo $this->lang->line('change_password'); ?></a></li>
                <li <?php if($active_menu_sidebar=="mypage-user-adwords"){echo 'class="active"';} else {echo '';}?>><a href="<?php echo base_url()?>mypage/user-manager-adwords.html"><?php echo $this->lang->line('user_manager_adwords'); ?></a></li>
                <li <?php if($active_menu_sidebar=="mypage-user-leave"){echo 'class="active"';} else {echo '';}?>><a href="<?php echo base_url()?>mypage/user-leave.html"><?php echo $this->lang->line('withdraw'); ?></a></li>
              </ul>
            </li>
            <li <?php 
			// account level 2:Creator, 3:Gold, 4:Platinum, 5:Enterprise
			if($active_menu_sidebar=="mypage-user-update-account"){echo 'class="active"';} else {echo '';}?>><a href="<?php echo base_url()?>mypage/user-update-account.html" class="menu-creator"><?php echo $this->lang->line('sider_bar_update_account'); ?></a></li>
          </ul>
        </div>
      </div>