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

<!-- <button onclick="location.href='admin/page_list_c_agree'" class='btn btn-primary'>selection_period_setting_for_winning_image</button> -->

<!--<form enctype="multipart/form-data" action="admin/add_point_user" method="post" onsubmit="return submitAjax(this,{'load':2000})">-->
<form enctype="multipart/form-data" action="admin/add_point_user" method="post">
  
  <div class="input-group col-lg-3">
    <textarea required="" style="width: 500px;" type="text" name="list_email" class="form-control" placeholder="chanchin39@gmail.com,nohara@wacontre.com" ><?=$keyword?></textarea>&nbsp;&nbsp;
    <!--<span class="input-group-btn">
      <button class="btn btn-secondary" style="z-index: 1000;"><i class="fa fa-search"></i></button>
    </span>-->
  </div>
  
  <input required="" class="form-control" style="float:left;width:100px !important;" type="number" name="add_point" value="add_point" />&nbsp;<?=$this->lang->line('point'); ?>&nbsp;&nbsp;
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