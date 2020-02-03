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
<style>
.list-pagenav {background-color:#fff!important;}
@media (max-width: 768px)
{
	.mypage-purchase__list .method .notice_admin {float:right!important;margin-top:-50px!important}
}
.add-member{
	float:right;
	width: 106px;
	height: 26px;text-align: center;
	line-height: 26px;color: #FFF !important;
	font-size: 13px;
	display: block;
	background: #00cbb4;
	border-radius: 3px;
}
btn-invited-member{
	float:left;
	width: 106px;
	height: 26px;text-align: center;
	line-height: 26px;color: #FFF !important;
	font-size: 13px;
	display: block;
	background: #00cbb4;
	border-radius: 3px;
}
.invite-again{
	color:#F00;
}
</style>
<script>

var user_id = "<?php echo $user_id; ?>";

$(document).ready(function(){
    $("#tb1").show();
});

function invite_again(email_invite){
	
	var result = confirm("<?php echo $this->lang->line('do_you_want_to_invite_this_email_again'); ?>");
	if (result) {
		
		$.ajax({
			type: "POST",cache: false,
			url: "<?php echo base_url(); ?>ajax/insert_member",
			data: "email_invite="+email_invite+"&userid="+user_id,
			success: function(data) {
				if(data == 0){
					alert("<?php echo $this->lang->line('error_invite'); ?>");
				}
				else{
					window.location.href = "<?php echo base_url(); ?>mypage/listinvited";
				}
			},
			async: false
		})
		
	}
	
}

function delete_member(member_id){
	
	var result = confirm("<?php echo $this->lang->line('do_you_want_to_delete_member'); ?>");
	if (result) {
		
		$.ajax({
			type: "POST",cache: false,
			url: "<?php echo base_url(); ?>ajax/delete_member",
			data: "member_id="+member_id+"&userid="+user_id,
			success: function(data) {
				if(data == 0){
					alert("<?php echo $this->lang->line('error_invite'); ?>");
				}
				else{
					window.location.href = "<?php echo base_url(); ?>mypage/listmember";
				}
			},
			async: false
		})
		
	}
		
}

function copyToClipboard(element) {
  var $temp = $("<input>");
  $("body").append($temp);
  $temp.val($(element).text()).select();
  document.execCommand("copy");
  $temp.remove();
}

</script>
<div class="contents-mypage">
    <div class="contents-mypage__wrapper clearfix">
      <div class="breadly">
        <ul>
          <li><a href="<?php echo base_url()?>"><span itemprop="title"><?php echo $this->lang->line('path_home'); ?></span></a></li>
          <li><a href="<?php echo base_url()?>mypage.html"><span itemprop="title"><?php echo $this->lang->line('mypage_h1'); ?></span></a></li>
          <li><span itemprop="title"><?php echo $this->lang->line('user_page_purchase_order_h1'); ?></span></li>
        </ul>
      </div>
      <?php $this->load->view('user/sidebar_user.php') ?>
      <div class="mypage-main">
            <h1 class="contents-mypage__find">
				<?php echo $this->lang->line('list_member'); ?>
                <a class="add-member" href="<?php echo base_url()?>mypage/addmember"><?php echo $this->lang->line('add_member'); ?></a>
          	</h1>
            
            <style>
body {font-family: Arial;}

/* Style the tab */
.tab {
    overflow: hidden;
    border: 1px solid #ccc;
    background-color: #f1f1f1;
}

/* Style the buttons inside the tab */
.tab a {
    background-color: inherit;
    float: left;
    border: none;
    outline: none;
    cursor: pointer;
    padding: 14px 16px;
    transition: 0.3s;
    font-size: 17px;
	line-height: 0.8 !important;
}

/* Change background color of buttons on hover */
.tab a:hover {
    background-color: #ddd;
}

/* Create an active/current tablink class */
.tab a.active {
    background-color: #ccc;
}

/* Style the tab content */
.tabcontent {
    display: none;
    padding: 6px 12px;
    border: 1px solid #ccc;
    border-top: none;
}
/* More */
.copy-link{
	width:50%;
}
.copy{
	padding:10px;
	background-color:#00cbb4;
	color:#FFF;
}
</style>


<div class="mypage-form" style="padding-bottom:5px;">
    <dl>
      <!--link share-->
      <dt>
        <?php echo $this->lang->line('link'); ?>:&nbsp;<input class="copy-link" type="text" id="link" name="link" value="<?php echo $link_invite; ?>" readonly="readonly">&nbsp;
        <input class="copy" type="button" id="btn_copy" name="btn_copy" title="<?php echo $this->lang->line('click_here_copy_link'); ?>" onclick="copyToClipboard('#copy')" value="&nbsp;&nbsp;<?php echo $this->lang->line('copy'); ?>&nbsp;&nbsp;" />
   	  </dt>
        <div id="copy" style="display:none;"><?php echo $link_invite; ?></div>
      <dd>
      </dd>
      <!--link share-->
    </dl>
</div>

            
            <div class="tab">
  <a class="tablinks active" href="<?php echo base_url(); ?>mypage/listmember"><?php echo $this->lang->line('list_member'); ?></a>
  <a class="tablinks" href="<?php echo base_url(); ?>mypage/listinvited"><?php echo $this->lang->line('list_invited'); ?></a>
  <?php
  	if($user_level == 4){
  ?>
  <a class="tablinks" href="<?php echo base_url(); ?>mypage/listagent"><?php echo $this->lang->line('a_grade_agency'); ?></a>
  <?php
  	}
  ?>
</div>

<div id="tb1" class="tabcontent">
  <div class="alert--green" style="display:none">
              <p>??????????????</p>
              <div class="alert__delete">×</div>
            </div>
            <div class="mypage-purchase">
              <div class="mypage-purchase__header">
                <div class="date" style="width:110px !important;"><?php echo $this->lang->line('avatar'); ?></div>
                <div class="order"><?php echo $this->lang->line('display_name'); ?></div>
                <div class="method"><?php echo $this->lang->line('date_invited'); ?></div>
                <div class="detail"><?php echo $this->lang->line('event'); ?></div>
                <!--<div class="price">Event</div>-->
              </div>
              <div class="mypage-purchase__list">
                <ul>
                  <?php
					if(!empty($list_member)){
						foreach($list_member as $detail_member){?>
                	<li>
                    <div class="date" style="width:110px !important;"><img class="mypage-favorite-user__icon" width="48" height="48" alt="<?php echo $detail_member->display_name; ?>" src="<?php echo base_url(); ?>publics/avatar/<?php echo ($detail_member->user_avatar != "") ? $detail_member->user_avatar : "prof-img.png"; ?>"></div>
                    <div class="order"><?php echo $detail_member->display_name;?></div>
                    <div class="method"><?php echo $detail_member->date_create;?></div>
                    <div class="detail"><a style="color:#008de7;" onclick="delete_member(<?php echo $detail_member->member_id; ?>)"><?php echo $this->lang->line('delete'); ?></a></div>
                    
                    <!--<div class="price"></div>-->
                    
                  </li>
                <?php
                	}
				}
				else{
					echo $this->lang->line('no_record');
				}
				?>
                </ul>
              </div>
               <div class="mypage-purchase__pagenav"><?php echo $phantrang_member;?></div>
            </div>
</div>

<div id="tb2" class="tabcontent">
  <div class="alert--green" style="display:none">
              <p>??????????????</p>
              <div class="alert__delete">×</div>
            </div>
            <div class="mypage-purchase">
              <div class="mypage-purchase__header">
                <div class="date" style="width:110px !important;"><?php echo $this->lang->line('email'); ?></div>
                <div class="order"><?php echo $this->lang->line('count_invited'); ?></div>
                <div class="method"><?php echo $this->lang->line('date_invited'); ?></div>
                <div class="detail"><?php echo $this->lang->line('status'); ?></div>
                <!--<div class="price">Event</div>-->
              </div>
              <div class="mypage-purchase__list">
                <ul>
                  <?php
					if(!empty($list_invited)){
						foreach($list_invited as $detail_invited){?>
                	<li>
                    <div class="date" style="width:110px !important;"><?php echo $list_invited->email_invite; ?></div>
                    <div class="order">
						<?php
                        	echo $detail_invited->count;
							if($detail_invited->status == 0){
								echo "&nbsp;<a class='invite-again' onclick='invite_again(\"".$detail_invited->email_invite."\");'>[".$this->lang->line('invite_again')."]</a>";
							}
							else if($detail_invited->status == 1){
								echo "&nbsp;<a class='invite-again' onclick='invite_again(\"".$detail_invited->email_invite."\");'>[".$this->lang->line('invite_again')."]</a>";
							}
						?>
                   	</div>
                    <div class="method"><?php echo $detail_invited->date_create;?></div>
                    <div class="detail">
                    	<?php
							if($detail_invited->status == 0){
								echo $this->lang->line('invitation');
							}
							else if($detail_invited->status == 1){
								echo $this->lang->line('deny');
							}
							else if($detail_invited->status == 2){
								echo $this->lang->line('join');
							}
						?>
                    </div>
                    
                    <!--<div class="price"></div>-->
                    
                  </li>
                <?php
                	}
				}
				else{
					echo $this->lang->line('no_record');
				}
				?>
                </ul>
              </div>
               <div class="mypage-purchase__pagenav"><?php echo $phantrang_invited;?></div>
            </div>
</div>
            
            
      
    </div>
  </div>