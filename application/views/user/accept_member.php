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

.entry-page__btn input[type="button"] {
    width: inherit;
    height: inherit;
    color: #fff;
    font-size: 15px;
    font-weight: bold;
    background: gradient(linear, left top, left bottom, from(#00d2b9), to(#00c6ad));
    background: -webkit-gradient(linear, left top, left bottom, from(#00d2b9), to(#00c6ad));
    background: linear-gradient(to bottom, #00d2b9, #00c6ad);
    border-radius: 3px;
    text-align: center;
}

</style>

<script>

var user_id = "<?php echo $user_id ?>";
var is_exist_user = "<?php echo $is_exist_user ?>";

function not_accept_member(member_id, md5){
	
	var result = confirm("<?php echo $this->lang->line('do_you_want_to_not_accept_member'); ?>");
	if (result) {
		
		window.location.href = "<?php echo base_url(); ?>not_accept_member/"+member_id+"-"+md5;
			
	}
}

function accept_member(member_id, md5){
	
	if(is_exist_user == 1){
		
		var result = confirm("<?php echo $this->lang->line('do_you_want_to_accept_member'); ?>");
		if (result) {
			
			$("#btnAcceptMember").attr('disabled','disabled');
			$("#btnAcceptMember").val('<?php echo $this->lang->line('processing'); ?>');
			
			$.ajax({
				type: "POST",cache: false,
				url: "<?php echo base_url(); ?>ajax/accept_member",
				data: "member_id="+member_id+"&userid="+user_id,
				success: function(data) {
					if(data == 0){
						location.reload("<?php echo $this->lang->line('error_invite'); ?>");
					}
					else{
						window.location.href = "<?php echo base_url(); ?>notice.html";
					}
				},
				async: false
			})
				
		}
		
	}
	else{
		
		$.ajax({
			type: "POST",cache: false,
			url: "<?php echo base_url(); ?>ajax/set_member_id",
			data: "member_id="+member_id+"&userid="+user_id,
			success: function(data) {
				if(data == 0){
					location.reload("<?php echo $this->lang->line('error_invite'); ?>");
				}
				else{
					window.location.href = "<?php echo base_url(); ?>entry.html";
				}
			},
			async: false
		})
			
	}
	
}

</script>

<div class="entry-page">
        <div class="entry-page__user">
          <div class="entry-page__filter">
            <div class="entry-page__box">
              <div class="entry-page__box-inner">
                <h2 class="entry-page__find"><?php echo $this->lang->line('accept_member'); ?></h2>
                <!--<p><?php //echo $this->lang->line('p_dang_ky_page__head_form1'); ?><a href="<?php //echo base_url('creator-entry.html');?>"><?php //echo $this->lang->line('p_dang_ky_page__head_form2'); ?><a href="<?php //echo base_url('login.html');?>"><?php //echo $this->lang->line('p_dang_ky_page__head_form3'); ?></p>-->
                <p><?php echo $this->lang->line('content_invited'); ?></p>
                <div id="error_register" class="warning" style="display: none;"></div>
                <form name="f" method="post" action="" enctype="multipart/form-data">
                <div class="entry-page__form">
                    <div class="entry-page__btn">
                      <input style="float:left;width:48%;margin-right:5px;" name="submit" type="button" id="btnAcceptMember"  value="<?php echo $this->lang->line('accept_member'); ?>" onclick="accept_member('<?php echo $member_id; ?>','<?php echo $md5; ?>')">
                      <input style="float:left;width:48%;margin-left:5px;" type="button" id="btnNotAcceptMember"  value="<?php echo $this->lang->line('not_accept_member'); ?>" onclick="not_accept_member('<?php echo $member_id; ?>','<?php echo $md5; ?>')">
                      <input type="hidden" name="member_id" value="<?php echo $member_id; ?>" />
                    </div>
                </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div> 
