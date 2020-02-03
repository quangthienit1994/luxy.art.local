<form action="admin/page_list_user_view_img" method="get">
  <div class="input-group col-lg-3" style="float: right">
    <input type="text" name="keyword" class="form-control" placeholder="Search for..." value="<?=$keyword?>">
    <span class="input-group-btn">
      <button class="btn btn-secondary" style="z-index: 1000;"><i class="fa fa-search"></i></button>
    </span>
  </div>
</form>

<table class="table table-hover bg-white">
    <thead>
        <tr>
           <th class="text-center">ID</th>
           <th>User</th>
           <th>Image</th>
           <th>Date</th>
           <th class="text-center">Action</th>
       </tr>
    </thead>
    <tbody>
    	<?php foreach ($list as $key => $value) { ?>
     	<tr>
        	<td class="text-center"><?=$value->id_view?></td>
        	<td><a href="admin/page_add_edit_user/<?=$value->user_id_abc?>"><?=$value->user_firstname ?> <?=$value->user_lastname ?></a> </td>
          <td><a target="_blank" href="admin/images/edit_image/<?=$value->id_img?>"><?=$value->img_title ?></a></td>
          <td><?=$value->date_view ?></td>
        	<td class="text-center">
        		<div class="btn-group">
              <form class="left" action="admin/delete_user_view_img/<?=$value->id_view?>" method="post" onsubmit="return submitAjax(this,{'load':1000,'confirm':true})">
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
    <li <?=$i==$currentPage?"class='active'":''?>><a href="admin/page_list_user_view_img/<?=$i?>?keyword=<?=$keyword?>"><?=$i?></a></li>
    <?php } ?>
  </ul>
</center>