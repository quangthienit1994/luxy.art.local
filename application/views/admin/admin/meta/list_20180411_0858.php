<!-- <button onclick="location.href='admin/page_add_edit_meta'" class='btn btn-primary'>Add Meta</button> -->

<form action="admin/page_list_meta" method="get">
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
           <th>ページタイトル</th>
           <th>コード</th>
           <th>meta keyword</th>
           <th class="text-center">アクション</th>
       </tr>
    </thead>
    <tbody>
      <?php foreach ($list as $key => $value) { ?>
      <tr>
          <td class="text-center"><?=$value->id_seo?></td>
          <td><a href="admin/page_add_edit_meta/<?=$value->id_seo?>" class='fwb'><?=$value->page_title_name?></a></td>
          <td><?=$value->name_call_code?></td>
          <td><?=$value->page_meta_key?></td>
          <td class="text-center">
            <div class="btn-group">
              <!-- <button type="button" onclick="location.href='admin/page_add_edit_meta/<?=$value->id_seo?>'" class="btn btn-info left"><i class="fa fa-eye"></i></button> -->
              <button onclick="location.href='admin/page_add_edit_meta/<?=$value->id_seo?>'" type="button" class="btn btn-warning left"><i class="fa fa-pencil"></i></button>
              
              <!-- <form class="left" action="admin/delete_meta/<?=$value->id_seo?>" method="post" onsubmit="return submitAjax(this,{'load':1000,'confirm':true})">
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
    <li <?=$i==$currentPage?"class='active'":''?>><a href="admin/page_list_meta/<?=$i?>?keyword=<?=$keyword?>"><?=$i?></a></li>
    <?php } ?>
  </ul>
</center>