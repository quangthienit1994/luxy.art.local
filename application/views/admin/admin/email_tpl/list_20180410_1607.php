<!-- <button onclick="location.href='admin/page_add_edit_email_tpl'" class='btn btn-primary'>Add Email Template</button> -->

<form action="admin/page_list_email_tpl" method="get">
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
           <th>メールコード</th>
           <th>件名</th>
           <th class="text-center">アクション</th>
       </tr>
    </thead>
    <tbody>
    	<?php foreach ($list as $key => $value) { ?>
     	<tr>
        	<td class="text-center"><?=$value->id_email?></td>
          <td><?=$value->name_email_code?></td>
        	<td><?=$value->subject?></td>
        	<td class="text-center">
        		<div class="btn-group">
        			<button type="button" onclick="location.href='admin/page_add_edit_email_tpl/<?=$value->id_email?>'" class="btn btn-info left"><i class="fa fa-eye"></i></button>
  				  	<button onclick="location.href='admin/page_add_edit_email_tpl/<?=$value->id_email?>'" type="button" class="btn btn-warning left"><i class="fa fa-pencil"></i></button>
  				  	
              <form class="left" action="admin/delete_email_tpl/<?=$value->id_email?>" method="post" onsubmit="return submitAjax(this,{'load':1000,'confirm':true})">
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
    <li <?=$i==$currentPage?"class='active'":''?>><a href="admin/page_list_email_tpl/<?=$i?>?keyword=<?=$keyword?>"><?=$i?></a></li>
    <?php } ?>
  </ul>
</center>