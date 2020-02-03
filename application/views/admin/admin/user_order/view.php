<table class="table table-hover bg-white">
    <thead>
        <tr>
           <th class="text-center">ID</th>
           <th>User owner</th>
           <th>Name Image</th>
           <th>Image</th>
           <th>Size</th>
           <th>Point User owner</th>
       </tr>
    </thead>
    <tbody>
    	<?php foreach ($list as $key => $value) { ?>
     	<tr>
        	<td class="text-center"><?=$value->id_or_detail?></td>
        	<td><?=$value->user_firstname?> <?=$value->user_lastname?></td>
        	<td><?=$value->img_title?></td>
          <td><img style="width: 50px; height: 50px;" src="publics/product_img/<?=$value->file_name_original_img?>"></td>
          <td><?=$value->type_size_description?></td>
          <td><?=$value->point_user_owner_get?></td>       	
       	</tr>
       	<?php } ?>
    </tbody>
</table>