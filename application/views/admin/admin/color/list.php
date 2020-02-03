<button onclick="location.href='admin/page_add_edit_color'" class='btn btn-primary'>Add Color</button>

<form action="admin/page_list_color" method="get">
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
           <th>Name key</th>
           <th>Name color</th>
           <th>Color</th>
           <th>Color code</th>
           <th class="text-center">Action</th>
       </tr>
    </thead>
    <tbody>
      <?php foreach ($list as $key => $value) { ?>
      <tr>
          <td class="text-center"><?=$value->id_color?></td>
          <td><?=$value->name?></td>
          <td class="fwb"><?=$value->name_color?></td>
          <td class="text-center"><div style="background: <?=$value->css_color_style ?>; width: 50px; height: 30px; cursor: pointer; display: inline-block;"></div></td>
          <td class="fwb"><?=$value->css_color_style ?></td>
          <td class="text-center">
            <div class="btn-group">
              <button type="button" onclick="location.href='admin/page_add_edit_color/<?=$value->id_color?>'" class="btn btn-info left"><i class="fa fa-eye"></i></button>
              <button onclick="location.href='admin/page_add_edit_color/<?=$value->id_color?>'" type="button" class="btn btn-warning left"><i class="fa fa-pencil"></i></button>
              
              <form class="left" action="admin/delete_color/<?=$value->id_color?>" method="post" onsubmit="return submitAjax(this,{'load':1000,'confirm':true})">
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
    <li <?=$i==$currentPage?"class='active'":''?>><a href="admin/page_list_color/<?=$i?>?keyword=<?=$keyword?>"><?=$i?></a></li>
    <?php } ?>
  </ul>
</center>