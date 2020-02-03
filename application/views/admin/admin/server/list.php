<button onclick="location.href='admin/page_add_edit_server'" class='btn btn-primary'>Add server</button>

<table class="table table-hover bg-white">
    <thead>
        <tr>
           <th class="text-center">ID</th>
           <th>Server name</th>
           <th>Path upload</th>
           <!-- <th>Path image</th> -->
           <th>Status</th>
           <th class="text-center">Action</th>
       </tr>
    </thead>
    <tbody>
    	<?php foreach ($list as $key => $value) { ?>
     	<tr>
        	<td class="text-center"><?=$value->id_server?></td>
        	<td><?=$value->server_name ?> </td>
          <td><?=$value->server_path_upload ?></td>
          <!-- <td><?=$value->server_path_upload_img  ?></td> -->
        	<td>
              <form class="left" action="admin/status_server/<?=$value->id_server?>" method="post" onsubmit="return submitAjax(this)">
                  <select class="form-control" name="status" onchange="submitForm(this)">
                    <option <?=$value->server_status==0?'selected=""':''?> value="0">Use</option>
                    <option <?=$value->server_status==1?'selected=""':''?> value="1">Not use</option>
                  </select>
              </form>
          </td>
        	<td class="text-center">
        		<div class="btn-group">
  				  	<button onclick="location.href='admin/page_add_edit_server/<?=$value->id_server?>'" type="button" class="btn btn-warning left"><i class="fa fa-pencil"></i></button>
  				  	
              <form class="left" action="admin/delete_server/<?=$value->id_server?>" method="post" onsubmit="return submitAjax(this,{'load':1000,'confirm':true})">
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
    <li <?=$i==$currentPage?"class='active'":''?>><a href="admin/page_list_server/<?=$i?>?keyword=<?=$keyword?>"><?=$i?></a></li>
    <?php } ?>
  </ul>
</center>