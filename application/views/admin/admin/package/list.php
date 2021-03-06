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

<button onclick="location.href='admin/page_add_edit_package'" class='btn btn-primary'><?php echo $this->lang->line('add_package'); ?></button>

<table class="table table-hover bg-white">
    <thead>
        <tr>
           <th class="text-center"><?php echo $this->lang->line('id'); ?></th>
           <th><?php echo $this->lang->line('package_name'); ?></th>
           <th><?php echo $this->lang->line('point'); ?></th>
           <th><?php echo $this->lang->line('_price'); ?></th>
           <th><?php echo $this->lang->line('one_lux_unit_price'); ?></th>
           <th><?php echo $this->lang->line('stattus'); ?></th>
           <th class="text-center"><?php echo $this->lang->line('action'); ?></th>
       </tr>
    </thead>
    <tbody>
    	<?php foreach ($list as $key => $value) { ?>
     	<tr>
        	<td class="text-center"><?=$value->id_list?></td>
          <td><?=$value->name_credit ?></td>
        	<td><?=$value->point_credit ?> </td>
          <td><?=$value->money_pay_credit ?></td>
          <td><?=$value->value_point_money  ?></td>
        	<td>
              <form class="left" action="admin/status_package/<?=$value->id_list?>" method="post" onsubmit="return submitAjax(this)">
                  <select class="form-control" name="status" onchange="submitForm(this)">
                    <option <?=$value->status_credit==0?'selected=""':''?> value="0"><?php echo $this->lang->line('use'); ?></option>
                    <option <?=$value->status_credit==1?'selected=""':''?> value="1"><?php echo $this->lang->line('do_not_use'); ?></option>
                    <option <?=$value->status_credit==2?'selected=""':''?> value="2"><?php echo $this->lang->line('delete'); ?></option> 
                  </select>
              </form>
          </td>
        	<td class="text-center">
        		<div class="btn-group">
        			<button type="button" onclick="location.href='admin/page_add_edit_package/<?=$value->id_list?>'" class="btn btn-info left"><i class="fa fa-eye"></i></button>
  				  	<button onclick="location.href='admin/page_add_edit_package/<?=$value->id_list?>'" type="button" class="btn btn-warning left"><i class="fa fa-pencil"></i></button>
  				  	
              <form class="left" action="admin/delete_package/<?=$value->id_list?>" method="post" onsubmit="return submitAjax(this,{'load':1000,'confirm':true})">
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
    <li <?=$i==$currentPage?"class='active'":''?>><a href="admin/page_list_package/<?=$i?>?keyword=<?=$keyword?>"><?=$i?></a></li>
    <?php } ?>
  </ul>
</center>