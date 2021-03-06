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

<button onclick="location.href='admin/page_add_edit_menu'" class='btn btn-primary'><?php echo $this->lang->line('add_menu'); ?></button>

<form action="admin/page_list_menu" method="get">
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
           <th><?php echo $this->lang->line('japan'); ?></th>
           <th><?php echo $this->lang->line('vietnam'); ?></th>
           <th><?php echo $this->lang->line('english'); ?></th>
           <th class="text-center"><?php echo $this->lang->line('action'); ?></th>
       </tr>
    </thead>
    <tbody>
    	<?php foreach ($list as $key => $value) { ?>
     	<tr>
        	<td class="text-center"><?=$value->id_menu?></td>
        	<td><?=$value->menu_ja ?> </td>
          <td><?=$value->menu_vi  ?></td>
          <td><?=$value->menu_en  ?></td>
        	<!-- <td>
              <form class="left" action="admin/status_menu/<?=$value->id_menu?>" method="post" onsubmit="return submitAjax(this)">
                  <select class="form-control" name="status" onchange="submitForm(this)">
                    <option <?=$value->status==0?'selected=""':''?> value="0">Use</option>
                    <option <?=$value->status==1?'selected=""':''?> value="1">Not use</option>
                  </select>
              </form>
          </td> -->
        	<td class="text-center">
        		<div class="btn-group">
  				  	<button onclick="location.href='admin/page_add_edit_menu/<?=$value->id_menu?>'" type="button" class="btn btn-warning left"><i class="fa fa-pencil"></i></button>
  				  	
              <form class="left" action="admin/delete_menu/<?=$value->id_menu?>" method="post" onsubmit="return submitAjax(this,{'load':1000,'confirm':true})">
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
    <li <?=$i==$currentPage?"class='active'":''?>><a href="admin/page_list_menu/<?=$i?>?keyword=<?=$keyword?>"><?=$i?></a></li>
    <?php } ?>
  </ul>
</center>