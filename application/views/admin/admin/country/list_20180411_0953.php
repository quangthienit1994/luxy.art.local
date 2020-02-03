<div class="btn-group">
  <button onclick="location.href='admin/page_add_edit_country'" class='btn btn-primary mgr-10'>国を追加</button>
  <button onclick="location.href='admin/page_list_state'" class='btn btn-primary mgr-10'>マネージャの状態</button>
  <button onclick="location.href='admin/page_add_edit_state'" class='btn btn-primary mgr-10'>状態を追加する</button>
</div>


<form action="admin/page_list_country" method="get">
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
           <th>国名</th>
           <th>省略コード</th>
           <th>通貨コード</th>
           <th class="text-center">操作</th>
       </tr>
    </thead>
    <tbody>
    	<?php foreach ($list as $key => $value) { ?>
     	<tr>
        	<td class="text-center"><?=$value->country_id?></td>
          <td><a href="admin/page_add_edit_country/<?=$value->country_id?>" class='fwb'><?=$value->country_name?></a></td>
        	<td><?=$value->country_code?></td>
          <td><?=$value->currency_code?></td>
        	<td class="text-center">
        		<div class="btn-group">
        			<button type="button" onclick="location.href='admin/page_add_edit_country/<?=$value->country_id?>'" class="btn btn-info left"><i class="fa fa-eye"></i></button>
  				  	<button onclick="location.href='admin/page_add_edit_country/<?=$value->country_id?>'" type="button" class="btn btn-warning left"><i class="fa fa-pencil"></i></button>
  				  	
              <form class="left" action="admin/delete_country/<?=$value->country_id?>" method="post" onsubmit="return submitAjax(this,{'load':1000,'confirm':true})">
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
    <li <?=$i==$currentPage?"class='active'":''?>><a href="admin/page_list_country/<?=$i?>?keyword=<?=$keyword?>"><?=$i?></a></li>
    <?php } ?>
  </ul>
</center>