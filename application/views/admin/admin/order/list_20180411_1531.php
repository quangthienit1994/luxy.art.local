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

<table class="table table-hover bg-white">
    <thead>
        <tr>
           <th class="text-center"><?php echo $this->lang->line('id'); ?></th>
           <th><?php echo $this->lang->line('purchased_user'); ?></th>
           <th><?php echo $this->lang->line('point'); ?></th>
           <th><?php echo $this->lang->line('order_date'); ?></th>
           <th><?php echo $this->lang->line('download_end_date'); ?></th>
           <th class="text-center"><?php echo $this->lang->line('operation'); ?></th>
       </tr>
    </thead>
    <tbody>
    	<?php foreach ($order as $key => $value) { ?>
     	<tr>
        	<td class="text-center"><?=$value->id_order_img?></td>
        	<td><a href="admin/page_add_edit_user/<?=$value->user_id?>" target="_blank"><?=$value->user_firstname?> <?=$value->user_lastname?></a></td>
        	<td><?=$value->total_point_order?></td>
          <td><?=$value->date_order_img?></td>
          <td><?=$value->date_end_download?></td>
        	<td class="text-center">
        		<div class="btn-group">
        			<button type="button" onclick="location.href='admin/page_view_order/<?=$value->id_order_img?>'" class="btn btn-danger left"><i class="fa "><?php echo $this->lang->line('order_detail'); ?></i></button>
  				  	<!-- <button onclick="location.href='admin/page_add_edit_order/<?=$value->id_order_img?>'" type="button" class="btn btn-warning left"><i class="fa fa-pencil"></i></button> -->
  				  	
              <form class="left" action="admin/delete_order/<?=$value->id_order_img?>" method="post" onsubmit="return submitAjax(this,{'load':1000,'confirm':true})">
                  <button class="btn btn-danger"><i class="fa fa-times"></i></button>
              </form>
				    </div>
        	</td>
       	</tr>
       	<?php } ?>
    </tbody>
</table>
<center>
  <ul class="pagination">
    <?php for($i = 1; $i <= $numPage; $i++) { ?>
    <li <?=$i==$currentPage?"class='active'":''?>><a href="admin/page_list_order/<?=$i?>"><?=$i?></a></li>
    <?php } ?>
  </ul>
</center>