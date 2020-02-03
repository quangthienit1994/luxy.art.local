<?php
$CI =& get_instance();
$CI->config->load();
$this->lang->load('dich', 'ja');
?>

<script>
var are_you_sure = "<?= $this->lang->line('are_you_sure'); ?>";
var you_will_not_be_able_to_recover = "<?= $this->lang->line('you_will_not_be_able_to_recover'); ?>";
var yes = "<?= $this->lang->line('yes'); ?>";
var cancel = "<?= $this->lang->line('cancel'); ?>";
</script>

<!-- Toastr -->
<script type="text/javascript" src="publics/admin/toastr/toastr.min.js"></script>
<script type="text/javascript">
	toastr.options = {
	  "closeButton": true,
	  "debug": true,
	  "newestOnTop": true,
	  "progressBar": true,
	  "positionClass": "toast-top-right",
	  "preventDuplicates": false,
	  "showDuration": "300",
	  "hideDuration": "1000",
	  "timeOut": "3000",
	  "extendedTimeOut": "1000",
	  "showEasing": "swing",
	  "hideEasing": "linear",
	  "showMethod": "fadeIn",
	  "hideMethod": "fadeOut"
	};
	function alertSuccess(message = '', title = '') {
	    if(title == '') title = 'Success !';
	    toastr.success(message, title);
	}

	function alertError(message = '', title = '') {
	    if(title == '') title = 'Error!';
	    toastr.error(message, title);
	}

	function alertWarning(message = '', title = '') {
	    if(title == '') title = 'Warning!';
	    toastr.warning(message, title);
	}

	function alertInfo(message = '', title = '') {
	    if(title == '') title = 'Information!';
	    toastr.info(message, title);
	}

	var return_msg = "<?php echo $return_msg; ?>";
	var return_link = "<?php echo $return_link; ?>";
	if(return_msg != ""){
		alertSuccess(''+return_msg);
		setTimeout(function () {
			window.location = return_link;
		}, 50000);
	}
	
</script>

<script>

$(document).ready(function(){
	
	$("#is_export_list_email").click(function(){
		check_radio_list_email();
	});
	
	$("#is_not_export_list_email").click(function(){
		check_radio_list_email();
	});
	
	$("#is_export_user_type").click(function(){
		check_radio_user_type();
	});
	
	$("#is_not_export_user_type").click(function(){
		check_radio_user_type();
	});
	
});

function check_radio_list_email(){
	
	if($('#is_export_list_email').is(":checked")){
		$("#add_point_list_email").attr("step", "0.1");
	}
	if($('#is_not_export_list_email').is(":checked")){
		$("#add_point_list_email").attr("step", "1");
	}
	
}

function check_radio_user_type(){
	
	if($('#is_export_user_type').is(":checked")){
		$("#add_point_membership_type").attr("step", "0.1");
	}
	if($('#is_not_export_user_type').is(":checked")){
		$("#add_point_membership_type").attr("step", "1");
	}
	
}

</script>

<!--<script>

var value = "{lux}";

$(document).ready(function(){
	
	$("#add_point_list_email").keyup(function(){
		content = $("#content_list_email").val();
		value_new = $("#add_point_list_email").val();
		content = content.replace(value, value_new);
		if(value_new != ""){
			$("#content_list_email").val(content);
			value = value_new;
		}
		else{
			$("#content_list_email").val("<?//=$this->lang->line('content_add_point_to_all_user'); ?>");
			value = "{lux}";
		}
	});
	
	$("#add_point_membership_type").keyup(function(){
		content = $("#content_membership_type").val();
		value_new = $("#add_point_membership_type").val();
		content = content.replace(value, value_new);
		if(value_new != ""){
			$("#content_membership_type").val(content);
			value = value_new;
		}
		else{
			$("#content_membership_type").val("<?//=$this->lang->line('content_add_point_to_all_user'); ?>");
			value = "{lux}";
		}
	});
	
});

</script>-->

<!-- <button onclick="location.href='admin/page_list_c_agree'" class='btn btn-primary'>selection_period_setting_for_winning_image</button> -->

<!--<form enctype="multipart/form-data" action="admin/add_point_user" method="post" onsubmit="return submitAjax(this,{'load':2000})">-->
<form enctype="multipart/form-data" action="admin/add_point_user" method="post">
  
  <div class="input-group col-lg-3">
    <textarea required="" style="width: 500px;" type="text" name="list_email" class="form-control" placeholder="chanchin39@gmail.com,nohara@wacontre.com" ></textarea>
    <div style="clear:both">&nbsp;</div>
    <textarea required="" style="width: 500px;" type="text" id="content_list_email" name="content" class="form-control" placeholder="<?=$this->lang->line('please_input_content_give_point'); ?>" ></textarea>&nbsp;&nbsp;
    <!--<span class="input-group-btn">
      <button class="btn btn-secondary" style="z-index: 1000;"><i class="fa fa-search"></i></button>
    </span>-->
  </div>
  
  <div class="edit_conts">
  	<div class="radio-custom mb5" style="float:left;width:100%;">
        <input id="is_export_list_email" name="type_point" value="1" checked="" type="radio">
        <label for="is_export_list_email"><?=$this->lang->line('lux_can_export'); ?></label>
        <input id="is_not_export_list_email" name="type_point" value="2" type="radio">
        <label for="is_not_export_list_email"><?=$this->lang->line('lux_cannot_export'); ?></label>
      </div>
  </div>
  
  <input required="" class="form-control" style="float:left;width:100px !important;" type="number" step="0.1" id="add_point_list_email" name="add_point" value="add_point" />&nbsp;<?=$this->lang->line('point'); ?>&nbsp;&nbsp;
<input type="submit" value="<?=$this->lang->line('add_point_to_all_user'); ?>">
  
</form><br />

<div style="border-top:1px #666 dashed;"></div><br />

<form enctype="multipart/form-data" action="admin/add_point_user_type" method="post">
  
  <div class="input-group col-lg-3">
    <p><?php echo $this->lang->line('membership_type'); ?></p>
    <p class="search_2">
      <select required="" name="user_level">
        <option value=""><?php echo $this->lang->line('please_select'); ?></option>
        <option <?=$user_level==1?"selected=''":''?> value='1'><?php echo $this->lang->line('general_user'); ?></option>
        <option <?=$user_level==2?"selected=''":''?> value='2'><?php echo $this->lang->line('creator'); ?></option>
        <option <?=$user_level==3?"selected=''":''?> value='3'><?php echo $this->lang->line('platinum'); ?></option>
        <option <?=$user_level==4?"selected=''":''?> value='4'><?php echo $this->lang->line('diamond'); ?></option>
        <option <?=$user_level==5?"selected=''":''?> value='5'><?php echo $this->lang->line('corporate'); ?></option>
      </select></p>
      <div style="clear:both">&nbsp;</div>
    <textarea required="" style="width: 500px;" type="text" id="content_membership_type" name="content" class="form-control" placeholder="<?=$this->lang->line('please_input_content_give_point'); ?>" ></textarea>&nbsp;&nbsp;
  </div>
  
  <div class="edit_conts">
  	<div class="radio-custom mb5" style="float:left;width:100%;">
        <input id="is_export_user_type" name="type_point" value="1" checked="" type="radio">
        <label for="is_export_user_type"><?=$this->lang->line('lux_can_export'); ?></label>
        <input id="is_not_export_user_type" name="type_point" value="2" type="radio">
        <label for="is_not_export_user_type"><?=$this->lang->line('lux_cannot_export'); ?></label>
      </div>
  </div>
  
  <input required="" class="form-control" style="float:left;width:100px !important;" type="number" step="0.1" id="add_point_membership_type" name="add_point" value="add_point" />&nbsp;<?=$this->lang->line('point'); ?>&nbsp;&nbsp;
<input type="submit" value="<?=$this->lang->line('add_point_to_all_user'); ?>">
  
</form>

<!--<table class="table table-hover bg-white">
    <thead>
        <tr>
           <th class="text-center"><?php echo $this->lang->line('id'); ?></th>
           <th><?php echo $this->lang->line('email'); ?></th>
           <th><?php echo $this->lang->line('username'); ?></th>
       </tr>
    </thead>
    <tbody>
    	<?php foreach ($list as $key => $value) { ?>
        <?php
			//echo "<pre>";
			//print_r($value);
		?>
     	<tr>
        	<td class="text-center"><?=$value->user_id?></td>
        	<td class="fwb"><a href="admin/page_add_edit_competition/<?=$value->user_id?>"><?=$value->user_email?> </a></td>
          <td><a href="admin/page_add_edit_user/<?=$value->user_id?>"><?=$value->display_name?></a></td>
        	
          
        	
        	
       	</tr>
       	<?php } ?>
    </tbody>
</table>-->
<center>
  <ul class="pagination">
  <?php
	//echo $this->function_model->get_pagination_links($currentPage,$numPage,base_url()."admin/give_point/{page}?keyword=".$keyword."&user_level=".$user_level);
 ?>
 </ul>
</center>