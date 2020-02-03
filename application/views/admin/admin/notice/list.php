<button onclick="location.href='admin/page_add_edit_notice'" class='btn btn-primary'>Add Notice</button>

<form action="admin/page_list_notice" method="get">
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
           <th>Name Send</th>
           <th>Name receive</th>
           <th>Title</th>
           <th>Date</th>
           <th>Is read</th>
           <th class="text-center">Action</th>
       </tr>
    </thead>
    <tbody>
    	<?php foreach ($list as $key => $value) { ?>
     	<tr>
        	<td class="text-center"><?=$value->id_message?></td>
        	<td>
            <?php if($value->user_send_id<=0){?>
              Admin
            <?php }else{?>
              <a href="admin/page_add_edit_user/<?=$value->user_send_id?>"><?=$value->user_send_firstname==''&&$value->user_send_lastname==''?$value->user_email:$value->user_send_firstname.' '.$value->user_send_lastname?></a>
            <?php } ?>
          </td>
          <td><a href="admin/page_add_edit_user/<?=$value->user_id?>"><?=$value->user_firstname==''&&$value->user_lastname==''?$value->user_email:$value->user_firstname.' '.$value->user_lastname?></a></td>
        	<td><a <?=$value->ms_is_read==0?"class='fwb'":''?> href="admin/page_add_edit_notice/<?=$value->id_message?>"><?=$value->ms_subject?></a></td>
          <td><?=$value->date_message?></td>
          <td>
            <?php if($value->ms_is_read==1) {?>
            <i class="fa fa-check text-center text-success"></i>
            <?php } ?>
          </td>
        	<td class="text-center">
        		<div class="btn-group">
        			<button type="button" onclick="location.href='admin/page_add_edit_notice/<?=$value->id_message?>'" class="btn btn-info left"><i class="fa fa-eye"></i></button>
  				  	<button onclick="location.href='admin/page_add_edit_notice/<?=$value->id_message?>'" type="button" class="btn btn-warning left"><i class="fa fa-pencil"></i></button>
  				  	
              <form class="left" action="admin/delete_notice/<?=$value->id_message?>" method="post" onsubmit="return submitAjax(this,{'load':1000,'confirm':true})">
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
    <li <?=$i==$currentPage?"class='active'":''?>><a href="admin/page_list_notice/<?=$i?>?keyword=<?=$keyword?>"><?=$i?></a></li>
    <?php } ?>
  </ul>
</center>