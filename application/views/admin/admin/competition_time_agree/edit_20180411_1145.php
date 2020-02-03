<section class="content_box">
  <h3><?=$type == 'edit'?'編集':'作成する' ?></h3>
  <article class="member_edit">
    <h4><?=$type == 'edit'?'編集':'作成する' ?></h4>

    <form action="admin/add_edit_c_agree" method="post" onsubmit="return submitAjax(this,{'load':2000,'redirect':'admin/page_list_c_agree'})">
  <input type="hidden" name="id" value="<?=@$get->id?>">
  <table class="table table-hover bg-white">
      <div class="edit_conts">
        <p>名 <request></request></p>
        <p></p>
        <p><input class="form-control" required="" type="text" name="name" value="<?=@$get->name?>"></p>
      </div>
      <div class="edit_conts">
        <p>タイプ <request></request></p>
        <p></p>
        <p>
          <select class="form-control" name="type">
            <option <?=@$get->type==1?"selected=''":''?> value="1">日</option>
            <option <?=@$get->type==2?"selected=''":''?> value="2">週</option>
            <option <?=@$get->type==3?"selected=''":''?> value="3">月</option>
          </select>
        </p>
      </div>
      <div class="edit_button">
        <p></p>
        <p><button class="btn btn-primary"><?=$type=='edit'?'更新':'作成する'?></button></p>
      </div>
  </table>
</form>

</article>
</section>