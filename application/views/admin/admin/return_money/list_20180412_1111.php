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

<form action="admin/page_list_return_money" method="get">
  <div class="input-group col-lg-3" style="float: right">
    <input type="text" name="keyword" class="form-control" placeholder="<?php echo $this->lang->line('search_for'); ?>" value="<?=$keyword?>">
    <span class="input-group-btn">
      <button class="btn btn-secondary" style="z-index: 1000;"><i class="fa fa-search"></i></button>
    </span>
  </div>
</form>

<table class="table table-hover bg-white">
    <thead>
        <tr>
           <th class="text-center"><?php echo $this->lang->line('id'); ?></th>
           <th><?php echo $this->lang->line('user_who_requested_withdrawal'); ?></th>
           <th><?php echo $this->lang->line('avatar'); ?></th>
           <th><?php echo $this->lang->line('point'); ?></th>
           <th><?php echo $this->lang->line('user_page_purchase_order_price'); ?></th>
           <th><?php echo $this->lang->line('reception_date'); ?></th>
           <th><?php echo $this->lang->line('status'); ?></th>
           <th class="text-center"><?php echo $this->lang->line('operation'); ?></th>
       </tr>
    </thead>
    <tbody>
    	<?php foreach ($list as $key => $value) { ?>
     	<tr>
        	<td class="text-center"><?=$value->tranfer_id?></td>
        	<td><a target="_blank" href="admin/page_add_edit_user/<?=$value->user_id?>"><?=$value->user_firstname==''&&$value->user_lastname==''?$value->user_email:$value->user_firstname.' '.$value->user_lastname?></a></td>
          <td class="text-center"><img style="width: 50px; height: 50px; border-radius: 100%;" onerror="this.src='<?=base_url('publics/avatar/default_avatar.png')?>'" src="publics/avatar/<?=$value->user_avatar?>"></td>
          <td class="text-center fwb"><?=$value->tranfer_point_require?></td>
          <td class="text-center fwb"><?=number_format($value->money_user_get,0,",",",")?></td>
          <td class="text-center"><?=$value->date_add_require?></td>
        	<td class="text-center text-danger">
              <?php if($value->tranfer_status==2){ ?>
                      <?php echo $this->lang->line('transferred'); ?>
              <?php }else{ ?>
              <form style="display: inline-block;" action="admin/status_return_money/<?=$value->tranfer_id?>" method="post" onsubmit="return submitAjax(this,{'load':1000})">
                  <select class="form-control" style="min-width: 110px;" name="status" style="width: 100px; margin: auto;" onchange="submitForm(this)">
                    <option <?=$value->tranfer_status==1?'selected=""':''?> value="1"><?php echo $this->lang->line('not_compatible'); ?></option>
                    <option <?=$value->tranfer_status==2?'selected=""':''?> value="2"><?php echo $this->lang->line('remitted'); ?></option>
                  </select>
              </form>
              <?php } ?>
              <a class="iframe" href="admin/bank_info/<?=$value->user_id?>" target="_blank"><button class="btn btn-danger mgl-10"><?php echo $this->lang->line('account_information'); ?></button></a>
          </td>
        	<td class="text-center">
        		<div class="btn-group">
  				  	<!-- <button onclick="location.href='admin/page_add_edit_return_money/<?=$value->tranfer_id?>'" type="button" class="btn btn-warning left"><i class="fa fa-pencil"></i></button> -->
  				  	<?php if($value->tranfer_status==2){ ?>
              <form class="left" action="admin/delete_return_money/<?=$value->tranfer_id?>" method="post" onsubmit="return submitAjax(this,{'load':1000,'confirm':true})">
                  <button class="btn btn-danger"><i class="fa fa-times"></i></button>
              </form>
              <?php } ?>
				    </div>
        	</td>
       	</tr>
       	<?php } ?>
    </tbody>
</table>
<center>
  <ul class="pagination">
    <?php for($i = 1; $i <= $numPage; $i++) { ?>
    <li <?=$i==$currentPage?"class='active'":''?>><a href="admin/page_list_return_money/<?=$i?>?keyword=<?=$keyword?>"><?=$i?></a></li>
    <?php } ?>
  </ul>
</center>