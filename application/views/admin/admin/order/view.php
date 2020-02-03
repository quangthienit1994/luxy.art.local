<?php
$CI =& get_instance();
$CI->config->load();
$this->lang->load('dich', 'ja');
?>

<table class="table table-hover bg-white">
    <thead>
        <tr>
           <th class="text-center"><?php echo $this->lang->line('id'); ?></th>
           <th><?php echo $this->lang->line('image_contributor'); ?></th>
           <th><?php echo $this->lang->line('image_title'); ?></th>
           <th><?php echo $this->lang->line('picture'); ?></th>
           <th><?php echo $this->lang->line('product_size'); ?></th>
           <th><?php echo $this->lang->line('user_acquired_lux'); ?></th>
       </tr>
    </thead>
    <tbody>
    	<?php foreach ($list as $key => $value) { ?>
     	<tr>
        	<td class="text-center"><?=$value->id_or_detail?></td>
        	<td><a href="admin/page_add_edit_user/<?=$value->user_id?>" target="_blank"><?=$value->user_firstname?> <?=$value->user_lastname?></a></td>
        	<td><a href="admin/images/edit_image/<?=$value->id_img?>" target="_blank"><?=$value->img_title?></a></td>
          <td><img style="width: 50px; height: 50px;" src="publics/product_img/<?=$value->file_name_original_img?>"></td>
          <td><?=$value->type_size_description?></td>
          <td><?=$value->point_user_owner_get?></td>       	
       	</tr>
       	<?php } ?>
    </tbody>
</table>