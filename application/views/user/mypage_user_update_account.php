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
<script>
$(document).ready(function() {
	$('.leave-popup__btn--leave').on('click','input',function(){
		$.ajax({
					type: "POST",cache: false,
					url: "<?php echo base_url(); ?>user/do_user_update_account",
					data: "type_account="+3,
					success: function(data) {
						var msg=data.split("{return_do_user_update_account}");
						if(msg[0] == 1){
							location.replace("<?php echo base_url()?>mypage/notice-user-update-level/"+ msg[1] +".html");
						}
						if(msg[0] == 0){
							$('.alert--gray p').html('Loi trong qua trinh dang ky account. Vui long thu lai');
						}
					},
					async: false
				});
	});
	$('.leave-popup__btn--cancel').on('click','span',function(){
		location.replace("<?php echo base_url()?>mypage.html");
	});
	$('.leave-popup__btn--buypoint').on('click','input',function(){
		$.ajax({
					type: "POST",cache: false,
					url: "<?php echo base_url(); ?>user/add_session_return_update_level",
					data: "level_update="+3,
					success: function(data) {
						var msg=data.split("{tieptuc_do_update}");
						if(msg[0] == 1){
							location.replace("<?php echo base_url()?>credit.html");
						}
					},
					async: false
				});
		
	});
});
</script>
<style>
/*--------------UpgradeAccount-------------*/
.upgrade-account{width: 100%;box-sizing: border-box;margin: 20px 0 0 0;}
.upgrade-account .name{width: 430px;float:left}
.upgrade-account .regular-user, .upgrade-account .creator, .upgrade-account .gold, .upgrade-account .platinum, .upgrade-account .enterprise{width: 125px;}

.upgrade-account li{display: inline-block;}
.upgrade-account__header{
	height: 50px;
    line-height: 28px;
    font-size: 12px;
    font-weight: bold;
    text-align: center;
    background: #eff4f5;border-top-left-radius: 15px;
    border-top-right-radius: 15px;padding-top: 20px;}
.upgrade-account__body{
	height: 40px;
    line-height: 40px;
    font-size: 13px;
    text-align: left;
    border-bottom: 1px solid #e6eced;
    padding-left: 15px;
	border-left: 1px solid #e6eced;
    border-right: 1px solid #e6eced; 
}
.upgrade-account-end__body{
	height: 40px;
    line-height: 40px;
    font-size: 13px;
    text-align: left;
   
    padding-left: 15px;
	
}
.upgrade-account-regular-user__header{
	height: 50px;
    line-height: 16px;
    font-size: 12px;
    font-weight: bold;
    text-align: center;
    background: #eff4f5;border-top-left-radius: 15px;
    border-top-right-radius: 15px;padding-top: 20px;
	background-color: #a1d290;
	color: #fff;
	margin-left: -3px;
	margin-right: -1px;
}
.upgrade-account-regular-user__body{
	height: 40px;
    line-height: 40px;
    font-size: 13px;
    text-align: center;
    border-bottom: 1px solid #e6eced;
    border-right: 1px solid #e6eced;
    margin-left: -3px;   
	
}
.btn-regular-user-nangcap{border-radius: 5px;
    background-color: #bcd275;
    text-transform: uppercase;
    color: #fff;font-weight: bold;}
	
.upgrade-account-creator__header{
	height: 50px;
    line-height: 16px;
    font-size: 12px;
    font-weight: bold;
    text-align: center;
    background: #eff4f5;border-top-left-radius: 15px;
    border-top-right-radius: 15px;padding-top: 20px;
	background-color: #bcd275;
	color: #fff;
	margin-left: -3px;
	margin-right: -1px;
}
.upgrade-account-creator__body{
	height: 40px;
    line-height: 40px;
    font-size: 13px;
    text-align: center;
    border-bottom: 1px solid #e6eced;
    border-right: 1px solid #e6eced;
    margin-left: -3px;   
	
}
.btn-creator-nangcap{border-radius: 5px;
    background-color: #a1d290;
    text-transform: uppercase;
    color: #fff;font-weight: bold;}

.upgrade-account-gold__header{
	height: 50px;
    line-height: 16px;
    font-size: 12px;
    font-weight: bold;
    text-align: center;
    background: #5dc69f;border-top-left-radius: 15px;
    border-top-right-radius: 15px;padding-top: 20px;
	background-color: #5dc69f;
	color: #fff;
	margin-left: -3px;
	margin-right: -1px;
}
.upgrade-account-gold__body{
	height: 40px;
    line-height: 40px;
    font-size: 13px;
    text-align: center;
    border-bottom: 1px solid #e6eced;
    border-right: 1px solid #e6eced;
    margin-left: -3px;   
	
}
.btn-gold-nangcap{border-radius: 5px;
    background-color: #5dc69f;
    text-transform: uppercase;
    color: #fff;font-weight: bold;}
/*platinum*/
.upgrade-account-platinum__header{
	height: 50px;
    line-height: 16px;
    font-size: 12px;
    font-weight: bold;
    text-align: center;
    background: #14918f;border-top-left-radius: 15px;
    border-top-right-radius: 15px;padding-top: 20px;
	background-color: #14918f;
	color: #fff;
	margin-left: -3px;
	margin-right: -1px;
}
.upgrade-account-platinum__body{
	height: 40px;
    line-height: 40px;
    font-size: 13px;
    text-align: center;
    border-bottom: 1px solid #e6eced;
    border-right: 1px solid #e6eced;
    margin-left: -3px;   
	
}
.btn-platinum-nangcap{border-radius: 5px;
    background-color: #14918f;
    text-transform: uppercase;
    color: #fff;font-weight: bold;}
/*enterprise*/
.upgrade-account-enterprise__header{
	height: 50px;
    line-height: 16px;
    font-size: 12px;
    font-weight: bold;
    text-align: center;
    background: #1f6586;border-top-left-radius: 15px;
    border-top-right-radius: 15px;padding-top: 20px;
	background-color: #1f6586;
	color: #fff;
	margin-left: -3px;
	margin-right: -1px;
}
.upgrade-account-enterprise__body{
	height: 40px;
    line-height: 40px;
    font-size: 13px;
    text-align: center;
    border-bottom: 1px solid #e6eced;
    border-right: 1px solid #e6eced;
    margin-left: -3px;   
	
}
.btn-enterprise-nangcap{border-radius: 5px;
    background-color: #1f6586;
    text-transform: uppercase;
    color: #fff;font-weight: bold;}
.outerDiv {float:left;margin-left:3px;}
@media all and (max-width: 768px) {
	.upgrade-account .name {display: block;width:50%;float:left}
	.upgrade-account .regular-user, .upgrade-account .creator, .upgrade-account .gold, .upgrade-account .platinum, .upgrade-account .enterprise{width: 165px;margin-bottom: 10px;display: inline-block;}
	 .outerDiv {
      width: 46%;
      overflow: scroll; 
	  position:absolute;
	  left:50%; 
    }
    
    .innerDiv {
      width: 990px;
    }
}
</style>

<!--Start UpgradeAccount--> 
<div class="contents-detail">
	<div class="contents-detail__wrapper clearfix">
		<div class="breadly">
			<ul>
				<li><a href="<?php echo base_url()?>"><span itemprop="title"><?php echo $this->lang->line('home'); ?></span></a></li>
				<li><a href="<?php echo base_url()?>mypage.html"><span itemprop="title"><?php echo $this->lang->line('mypage'); ?></span></a></li>				
				<li><span itemprop="title"><?php echo $this->lang->line('title_page_list_package_update_account'); ?></span></li>
			</ul>
		</div> 
		<div class="upgrade-account">
			<ul>
				<li class="name">
					<p class="upgrade-account__header">機能</p>
					<p class="upgrade-account__body">素材検索</p>
					<p class="upgrade-account__body">購入</p>
					<p class="upgrade-account__body">コンペ閲覧</p>
					<p class="upgrade-account__body">コンペ参加</p>
					<p class="upgrade-account__body">コンペ開催</p>
					<p class="upgrade-account__body">クローズドコンペ閲覧</p>
					<p class="upgrade-account__body">クローズドコンペ参加</p>
					<p class="upgrade-account__body">クローズドコンペ開催</p>
					<p class="upgrade-account__body">写真販売</p>
                    <p class="upgrade-account__body">アップグレードに必要なLUX</p>
					<p class="upgrade-account-end__body">&nbsp;</p>
				</li>
                <div class="outerDiv">
      <div class="innerDiv">
				 <?php if($user_level==2 || $user_level==1 || $user_level==5){?>
                 <li class="regular-user">
					<p class="upgrade-account-regular-user__header">一般ユーザー<br> Regular user</p>
					<p class="upgrade-account-regular-user__body"><img src="<?php echo base_url()?>publics/img/icon-tick.png"/></p>
					<p class="upgrade-account-regular-user__body"><img src="<?php echo base_url()?>publics/img/icon-tick.png"/></p>
					<p class="upgrade-account-regular-user__body"><img src="<?php echo base_url()?>publics/img/icon-tick.png"/></p>
					<p class="upgrade-account-regular-user__body"><img src="<?php echo base_url()?>publics/img/icon-no-tick.png"/></p>
					<p class="upgrade-account-regular-user__body"><img src="<?php echo base_url()?>publics/img/icon-tick.png"/></p>
					<p class="upgrade-account-regular-user__body"><img src="<?php echo base_url()?>publics/img/icon-no-tick.png"/></p>
					<p class="upgrade-account-regular-user__body"><img src="<?php echo base_url()?>publics/img/icon-no-tick.png"/></p>
					<p class="upgrade-account-regular-user__body"><img src="<?php echo base_url()?>publics/img/icon-no-tick.png"/></p>
					<p class="upgrade-account-regular-user__body"><img src="<?php echo base_url()?>publics/img/icon-no-tick.png"/></p>

                    <p class="upgrade-account-regular-user__body">無料</p>
					<p class="upgrade-account-regular-user__body btn-creator-nangcap" style="border:none;cursor: no-drop;"><?php echo $this->lang->line('button_update_account_free');?></p>
				</li>
				<li class="creator">
					<p class="upgrade-account-creator__header">クリエイター<br>Creator</p>
					<p class="upgrade-account-creator__body"><img src="<?php echo base_url()?>publics/img/icon-tick.png"/></p>
					<p class="upgrade-account-creator__body"><img src="<?php echo base_url()?>publics/img/icon-tick.png"/></p>
					<p class="upgrade-account-creator__body"><img src="<?php echo base_url()?>publics/img/icon-tick.png"/></p>
					<p class="upgrade-account-creator__body"><img src="<?php echo base_url()?>publics/img/icon-tick.png"/></p>
					<p class="upgrade-account-creator__body"><img src="<?php echo base_url()?>publics/img/icon-tick.png"/></p>
					<p class="upgrade-account-creator__body"><img src="<?php echo base_url()?>publics/img/icon-tick.png"/></p>
					<p class="upgrade-account-creator__body"><img src="<?php echo base_url()?>publics/img/icon-no-tick.png"/></p>
					<p class="upgrade-account-creator__body"><img src="<?php echo base_url()?>publics/img/icon-no-tick.png"/></p>
					
					<p class="upgrade-account-creator__body">12枚</p>
                    <p class="upgrade-account-creator__body">無料</p>
					<?php if($user_level<2){?><a href="<?php echo base_url()?>mypage/user-update-creator.html"><p class="upgrade-account-creator__body btn-regular-user-nangcap"><?php echo $this->lang->line('button_update_account_use_point');?></p></a><?php } else {?><p class="upgrade-account-creator__body btn-regular-user-nangcap"  style="border:none;cursor: no-drop;"><?php echo $this->lang->line('button_update_account_free');?></p><?php }?>
				</li>
                <li class="enterprise">
					<p class="upgrade-account-enterprise__header">法人<br>Enterprise</p>
					<p class="upgrade-account-enterprise__body"><img src="<?php echo base_url()?>publics/img/icon-tick.png"/></p>
					<p class="upgrade-account-enterprise__body"><img src="<?php echo base_url()?>publics/img/icon-tick.png"/></p>
					<p class="upgrade-account-enterprise__body"><img src="<?php echo base_url()?>publics/img/icon-tick.png"/></p>
					<p class="upgrade-account-enterprise__body"><img src="<?php echo base_url()?>publics/img/icon-tick.png"/></p>
					<p class="upgrade-account-enterprise__body"><img src="<?php echo base_url()?>publics/img/icon-tick.png"/></p>
					<p class="upgrade-account-enterprise__body"><img src="<?php echo base_url()?>publics/img/icon-tick.png"/></p>
					<p class="upgrade-account-enterprise__body"><img src="<?php echo base_url()?>publics/img/icon-tick.png"/></p>
					<p class="upgrade-account-enterprise__body"><img src="<?php echo base_url()?>publics/img/icon-tick.png"/></p>
					<p class="upgrade-account-enterprise__body">25枚</p>
                    <p class="upgrade-account-enterprise__body">
                    -</p>
                    <?php if($user_level<5){?><a href="<?php echo base_url()?>mypage/user-update-enterprise.html"><p class="upgrade-account-enterprise__body btn-enterprise-nangcap"><?php echo $this->lang->line('button_update_account_use_point');?></p></a><?php } else {?><p class="upgrade-account-enterprise__body btn-enterprise-nangcap"  style="border:none;cursor: no-drop;"><?php echo $this->lang->line('button_update_account_use_point');?></p><?php }?>
				</li>
                <?php }?>
                <?php if($user_level==3 ||$user_level==4){?>
                <li class="regular-user">
					<p class="upgrade-account-regular-user__header">一般ユーザー<br> Regular user</p>
					<p class="upgrade-account-regular-user__body"><img src="<?php echo base_url()?>publics/img/icon-tick.png"/></p>
					<p class="upgrade-account-regular-user__body"><img src="<?php echo base_url()?>publics/img/icon-tick.png"/></p>
					<p class="upgrade-account-regular-user__body"><img src="<?php echo base_url()?>publics/img/icon-tick.png"/></p>
					<p class="upgrade-account-regular-user__body"><img src="<?php echo base_url()?>publics/img/icon-no-tick.png"/></p>
					<p class="upgrade-account-regular-user__body"><img src="<?php echo base_url()?>publics/img/icon-tick.png"/></p>
					<p class="upgrade-account-regular-user__body"><img src="<?php echo base_url()?>publics/img/icon-no-tick.png"/></p>
					<p class="upgrade-account-regular-user__body"><img src="<?php echo base_url()?>publics/img/icon-no-tick.png"/></p>
					<p class="upgrade-account-regular-user__body"><img src="<?php echo base_url()?>publics/img/icon-no-tick.png"/></p>
					<p class="upgrade-account-regular-user__body"><img src="<?php echo base_url()?>publics/img/icon-no-tick.png"/></p>

                    <p class="upgrade-account-regular-user__body">無料</p>
					<p class="upgrade-account-regular-user__body btn-creator-nangcap" style="border:none;cursor: no-drop;"><?php echo $this->lang->line('button_update_account_free');?></p>
				</li>
				<li class="creator">
					<p class="upgrade-account-creator__header">クリエイター<br>Creator</p>
					<p class="upgrade-account-creator__body"><img src="<?php echo base_url()?>publics/img/icon-tick.png"/></p>
					<p class="upgrade-account-creator__body"><img src="<?php echo base_url()?>publics/img/icon-tick.png"/></p>
					<p class="upgrade-account-creator__body"><img src="<?php echo base_url()?>publics/img/icon-tick.png"/></p>
					<p class="upgrade-account-creator__body"><img src="<?php echo base_url()?>publics/img/icon-tick.png"/></p>
					<p class="upgrade-account-creator__body"><img src="<?php echo base_url()?>publics/img/icon-tick.png"/></p>
					<p class="upgrade-account-creator__body"><img src="<?php echo base_url()?>publics/img/icon-tick.png"/></p>
					<p class="upgrade-account-creator__body"><img src="<?php echo base_url()?>publics/img/icon-no-tick.png"/></p>
					<p class="upgrade-account-creator__body"><img src="<?php echo base_url()?>publics/img/icon-no-tick.png"/></p>
					
					<p class="upgrade-account-creator__body">12枚</p>
                    <p class="upgrade-account-creator__body">無料</p>
					<p class="upgrade-account-creator__body btn-regular-user-nangcap"  style="border:none;cursor: no-drop;"><?php echo $this->lang->line('button_update_account_free');?></p>
				</li>
				<li class="gold">
					<p class="upgrade-account-gold__header">プラチナ<br>Platinum</p>
					<p class="upgrade-account-gold__body"><img src="<?php echo base_url()?>publics/img/icon-tick.png"/></p>
					<p class="upgrade-account-gold__body"><img src="<?php echo base_url()?>publics/img/icon-tick.png"/></p>
					<p class="upgrade-account-gold__body"><img src="<?php echo base_url()?>publics/img/icon-tick.png"/></p>
					<p class="upgrade-account-gold__body"><img src="<?php echo base_url()?>publics/img/icon-tick.png"/></p>
					<p class="upgrade-account-gold__body"><img src="<?php echo base_url()?>publics/img/icon-tick.png"/></p>
					<p class="upgrade-account-gold__body"><img src="<?php echo base_url()?>publics/img/icon-tick.png"/></p>
					<p class="upgrade-account-gold__body"><img src="<?php echo base_url()?>publics/img/icon-tick.png"/></p>
					<p class="upgrade-account-gold__body"><img src="<?php echo base_url()?>publics/img/icon-no-tick.png"/></p>
					
					<p class="upgrade-account-gold__body">50枚</p>
                    <p class="upgrade-account-gold__body">-</p>
                    <p class="upgrade-account-gold__body btn-gold-nangcap"  style="border:none;cursor: no-drop;">特別会員</p>
				</li>
                
                <li class="platinum">
					<p class="upgrade-account-platinum__header">ダイヤモンド<br>Diamond</p>
					<p class="upgrade-account-platinum__body"><img src="<?php echo base_url()?>publics/img/icon-tick.png"/></p>
					<p class="upgrade-account-platinum__body"><img src="<?php echo base_url()?>publics/img/icon-tick.png"/></p>
					<p class="upgrade-account-platinum__body"><img src="<?php echo base_url()?>publics/img/icon-tick.png"/></p>
					<p class="upgrade-account-platinum__body"><img src="<?php echo base_url()?>publics/img/icon-tick.png"/></p>
					<p class="upgrade-account-platinum__body"><img src="<?php echo base_url()?>publics/img/icon-tick.png"/></p>
					<p class="upgrade-account-platinum__body"><img src="<?php echo base_url()?>publics/img/icon-tick.png"/></p>
					<p class="upgrade-account-platinum__body"><img src="<?php echo base_url()?>publics/img/icon-tick.png"/></p>
					<p class="upgrade-account-platinum__body"><img src="<?php echo base_url()?>publics/img/icon-no-tick.png"/></p>
					
					<p class="upgrade-account-platinum__body">250枚</p>
                    <p class="upgrade-account-platinum__body">-</p>
                    
                    <p class="upgrade-account-platinum__body btn-platinum-nangcap"  style="border:none;cursor: no-drop;">特別会員</p>
                    
				</li>
                <li class="enterprise">
					<p class="upgrade-account-enterprise__header">法人<br>Enterprise</p>
					<p class="upgrade-account-enterprise__body"><img src="<?php echo base_url()?>publics/img/icon-tick.png"/></p>
					<p class="upgrade-account-enterprise__body"><img src="<?php echo base_url()?>publics/img/icon-tick.png"/></p>
					<p class="upgrade-account-enterprise__body"><img src="<?php echo base_url()?>publics/img/icon-tick.png"/></p>
					<p class="upgrade-account-enterprise__body"><img src="<?php echo base_url()?>publics/img/icon-tick.png"/></p>
					<p class="upgrade-account-enterprise__body"><img src="<?php echo base_url()?>publics/img/icon-tick.png"/></p>
					<p class="upgrade-account-enterprise__body"><img src="<?php echo base_url()?>publics/img/icon-tick.png"/></p>
					<p class="upgrade-account-enterprise__body"><img src="<?php echo base_url()?>publics/img/icon-tick.png"/></p>
					<p class="upgrade-account-enterprise__body"><img src="<?php echo base_url()?>publics/img/icon-tick.png"/></p>
					<p class="upgrade-account-enterprise__body">25枚</p>
                    <p class="upgrade-account-enterprise__body">
                    -</p>
                    <p class="upgrade-account-enterprise__body btn-enterprise-nangcap"  style="border:none;cursor: no-drop;"><?php echo $this->lang->line('button_update_account_use_point');?></p>
				</li>
                <?php } ?>
                
               
                
                </div></div>

			</ul>
            <?php if($user_level==5){echo '<p style="float:left;width:100%;color:red">'.$this->lang->line('show_text_your_account_already_hight_level').'</p>';}?>
		</div>
	</div>
</div> 
<!--end UpgradeAccount-->