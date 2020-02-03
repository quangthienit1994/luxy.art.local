<div class="btn-group">
  <button onclick="location.href='admin/page_add_edit_state'" class='btn btn-primary mgr-10'>追加する情報</button>

  <button onclick="location.href='admin/page_list_country'" class='btn btn-primary mgr-10'>マネージャ国</button>
  <button onclick="location.href='admin/page_add_edit_country'" class='btn btn-primary mgr-10'>国を追加</button>
  
</div>


<form action="admin/page_list_state" method="get">
  <div class="input-group col-lg-3" style="float: right">
    <select class="form-control" name="country_id" style="width: 150px; position: absolute; right: 250px; top: 17px;">
    <option value="">-- 国 --</option>
    <?php foreach ($this->db->get_where('luxyart_tb_country',[])->result() as $key => $value) { ?>
    <option <?=$value->country_id==$country_id?"selected=''":''?> value="<?=$value->country_id?>"><?=$value->country_name?></option>
    <?php } ?>
  </select>

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
           <th>都道府県名</th>
           <th>国</th>
           <th class="text-center">アクション</th>
       </tr>
    </thead>
    <tbody>
    	<?php foreach ($list as $key => $value) { ?>
     	<tr>
        	<td class="text-center"><?=$value->state_id?></td>
          <td><?=$value->state_name?></td>
          <td><?=$value->country_name?></td>
        	<td class="text-center">
        		<div class="btn-group">
        			<button type="button" onclick="location.href='admin/page_add_edit_state/<?=$value->state_id?>'" class="btn btn-info left"><i class="fa fa-eye"></i></button>
  				  	<button onclick="location.href='admin/page_add_edit_state/<?=$value->state_id?>'" type="button" class="btn btn-warning left"><i class="fa fa-pencil"></i></button>
  				  	
              <form class="left" action="admin/delete_state/<?=$value->state_id?>" method="post" onsubmit="return submitAjax(this,{'load':1000,'confirm':true})">
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
    <li <?=$i==$currentPage?"class='active'":''?>><a href="admin/page_list_state/<?=$i?>?keyword=<?=$keyword?>&country_id=<?=$country_id?>"><?=$i?></a></li>
    <?php } ?>
  </ul>
</center>