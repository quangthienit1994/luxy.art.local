<table class="table table-hover bg-white">
    <thead>
        <tr>
           <th class="text-center">ID</th>
           <th>画像投稿者</th>
           <th>画像タイトル</th>
           <th>画像</th>
           <th>サイズ</th>
           <th>ユーザーが獲得LUX</th>
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