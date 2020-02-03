<button onclick="location.href='admin/page_add_edit_qa_category'" class='btn btn-primary'>Add new category</button>

<button onclick="location.href='admin/page_list_qa'" class='btn btn-primary mgr-10'>List article</button>

<button onclick="location.href='admin/page_add_edit_qa'" class='btn btn-primary mgr-10'>Add new article</button>

<form action="admin/page_list_qa_category" method="get">
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
           <th>Title</th>
           <th>Status</th>
           <th class="text-center">Action</th>
       </tr>
    </thead>
    <tbody>
      <?php foreach ($list as $key => $value) { ?>
      <tr>
          <td class="text-center"><?=$value->category_id?></td>
          <td><?=$value->title?></td>
          <td>
              <form class="left" action="admin/status_qa_category/<?=$value->category_id?>" method="post" onsubmit="return submitAjax(this)">
                  <select class="form-control" name="status" onchange="submitForm(this)">
                    <option <?=$value->status==0?'selected=""':''?> value="0">No Active</option>
                    <option <?=$value->status==1?'selected=""':''?> value="1">Active</option>
                  </select>
              </form>
          </td>
          <td class="text-center">
            <div class="btn-group">
              <button type="button" onclick="location.href='admin/page_add_edit_qa_category/<?=$value->category_id?>'" class="btn btn-info left"><i class="fa fa-eye"></i></button>
              <button onclick="location.href='admin/page_add_edit_qa_category/<?=$value->category_id?>'" type="button" class="btn btn-warning left"><i class="fa fa-pencil"></i></button>
              
              <form class="left" action="admin/delete_qa_category/<?=$value->category_id?>" method="post" onsubmit="return submitAjax(this,{'load':1000,'confirm':true})">
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
    <li <?=$i==$currentPage?"class='active'":''?>><a href="admin/page_list_qa_category/<?=$i?>?keyword=<?=$keyword?>"><?=$i?></a></li>
    <?php } ?>
  </ul>
</center>