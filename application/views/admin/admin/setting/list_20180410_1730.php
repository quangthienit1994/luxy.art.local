<!-- <button onclick="location.href='admin/page_add_edit_setting'" class='btn btn-primary'>Add Setting</button> -->

<form action="admin/page_list_setting" method="get">
  <div class="input-group col-lg-3" style="float: right">
    <input type="text" name="keyword" class="form-control" placeholder="検索する..." value="<?=$keyword?>">
    <span class="input-group-btn">
      <button class="btn btn-secondary" style="z-index: 1000;"><i class="fa fa-search"></i></button>
    </span>
  </div>
</form>


<table class="table table-hover bg-white">
    <thead>
        <tr>
           <th class="text-center">ID</th>
           <th>項目</th>
           <th>値</th>
           <th class="text-center">操作</th>
       </tr>
    </thead>
    <tbody>
    	<?php foreach ($list as $key => $value) { ?>
     	<tr>
        	<td class="text-center"><?=$value->setting_id?></td>
          <td><?=$value->value_text?></td>
        	<td><?=$value->value?></td>
        	<td class="text-center">
        		<div class="btn-group">
        			<!-- <button type="button" onclick="location.href='admin/page_add_edit_setting/<?=$value->setting_id?>'" class="btn btn-info left"><i class="fa fa-eye"></i></button> -->
  				  	<button onclick="location.href='admin/page_add_edit_setting/<?=$value->setting_id?>'" type="button" class="btn btn-warning left"><i class="fa fa-pencil"></i></button>
  				  	
              <!-- <form class="left" action="admin/delete_setting/<?=$value->setting_id?>" method="post" onsubmit="return submitAjax(this,{'load':1000,'confirm':true})">
                  <button class="btn btn-danger"><i class="fa fa-times"></i></button>
              </form> -->
				    </div>
        	</td>
       	</tr>
       	<?php } ?>
    </tbody>
</table>


<center>
  <ul class="pagination">
    <?php for($i = 1; $i <= $numPage; $i++) { ?>
    <li <?=$i==$currentPage?"class='active'":''?>><a href="admin/page_list_setting/<?=$i?>?keyword=<?=$keyword?>"><?=$i?></a></li>
    <?php } ?>
  </ul>
</center>