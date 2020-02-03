<button onclick="location.href='admin/page_add_edit_c_agree'" class='btn btn-primary'>競争時間を加算する</button>

<form action="admin/page_list_c_agree" method="get">
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
           <th>名</th>
           <th>タイプ</th>
           <!-- <th>状態</th> -->
           <th class="text-center">アクション</th>
       </tr>
    </thead>
    <tbody>
    	<?php foreach ($list as $key => $value) { ?>
     	<tr>
        	<td class="text-center"><?=$value->id?></td>
        	<td><?=$value->name ?> </td>
          <td><?php if($value->type==1) echo '日'; elseif($value->type==2) echo 'Week'; elseif($value->type==3) echo '月' ; ?></td>
        	<!-- <td>
              <form class="left" action="admin/status_c_agree/<?=$value->id?>" method="post" onsubmit="return submitAjax(this)">
                  <select class="form-control" name="status" onchange="submitForm(this)">
                    <option <?=$value->status==0?'selected=""':''?> value="0">Use</option>
                    <option <?=$value->status==1?'selected=""':''?> value="1">Not use</option>
                  </select>
              </form>
          </td> -->
        	<td class="text-center">
        		<div class="btn-group">
  				  	<button onclick="location.href='admin/page_add_edit_c_agree/<?=$value->id?>'" type="button" class="btn btn-warning left"><i class="fa fa-pencil"></i></button>
  				  	
              <form class="left" action="admin/delete_c_agree/<?=$value->id?>" method="post" onsubmit="return submitAjax(this,{'load':1000,'confirm':true})">
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
    <li <?=$i==$currentPage?"class='active'":''?>><a href="admin/page_list_c_agree/<?=$i?>?keyword=<?=$keyword?>"><?=$i?></a></li>
    <?php } ?>
  </ul>
</center>