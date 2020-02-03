<form action="admin/page_list_user_follow" method="get">
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
           <th>User follow</th>
           <th>Date</th>
           <th class="text-center">Action</th>
       </tr>
    </thead>
    <tbody>
    	<?php foreach ($list as $key => $value) { ?>
     	<tr>
        	<td class="text-center"><?=$value->id_follow?></td>
        	<td><a href="admin/page_add_edit_user/<?=$value->user_id?>"><?=$value->user_firstname ?> <?=$value->user_lastname ?></a> </td>
          <td><a href="admin/page_add_edit_user/<?=$value->user_follow_id?>"><?=$value->user_follow_firstname ?> <?=$value->user_follow_lastname ?></a> </td>
          <td><?=$value->date_follow ?></td>
        	<td class="text-center">
        		<div class="btn-group">
              <form class="left" action="admin/delete_user_follow/<?=$value->id_follow?>" method="post" onsubmit="return submitAjax(this,{'load':1000,'confirm':true})">
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
    <li <?=$i==$currentPage?"class='active'":''?>><a href="admin/page_list_user_follow/<?=$i?>?keyword=<?=$keyword?>"><?=$i?></a></li>
    <?php } ?>
  </ul>
</center>