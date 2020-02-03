<section class="content_box">
  <h3><?=$type == 'edit'?'Edit':'Create' ?></h3>
  <article class="member_edit">
    <h4><?=$type == 'edit'?'Edit':'Create' ?> information</h4>



    <form action="admin/add_edit_server" method="post" onsubmit="return submitAjax(this,{'load':2000,'redirect':'admin/page_list_server'})">
  <input type="hidden" name="id" value="<?=@$get->id_server?>">
  <table class="table table-hover bg-white">
      <div class="edit_conts">
        <p>Server name</p>
        <p></p>
        <p><input class="form-control" required="" type="text" name="server_name" value="<?=@$get->server_name?>"></p>
      </div>
      <div class="edit_conts">
        <p>Server path upload</p>
        <p></p>
        <p><input class="form-control" required="" type="text" name="server_path_upload" value="<?=@$get->server_path_upload?>"></p>
      </div>
      <div class="edit_conts">
        <p>Server path upload image</p>
        <p></p>
        <p><input class="form-control" required="" type="text" name="server_path_upload_img" value="<?=@$get->server_path_upload_img?>"></p>
      </div>
      <div class="edit_conts">
        <p>Server ftp account</p>
        <p></p>
        <p><input class="form-control" required="" type="text" name="server_ftp_account" value="<?=@$get->server_ftp_account?>"></p>
      </div>
      <div class="edit_conts">
        <p>Server ftp pass</p>
        <p></p>
        <p><input class="form-control" required="" type="text" name="server_ftp_pass" value="<?=@$get->server_ftp_pass?>"></p>
      </div>
      <div class="edit_conts">
        <p>Server port</p>
        <p></p>
        <p><input class="form-control" required="" type="number" name="server_port" value="<?=@$get->server_port?>"></p>
      </div>
      <div class="edit_conts">
        <p>Image store</p>
        <p></p>
        <p><input class="form-control" required="" type="number" name="img_store" value="<?=@$get->img_store?>"></p>
      </div>
      <div class="edit_conts">
        <p>Image count</p>
        <p></p>
        <p><input class="form-control" required="" type="number" name="img_count" value="<?=@$get->img_count?>"></p>
      </div>
      <div class="edit_conts">
        <p>Lang</p>
        <p></p>
        <p>
          <select class="form-control" name="lang_id">
            <option <?=@$get->lang_id==1?"selected=''":''?> value="1">Japan</option>
            <option <?=@$get->lang_id==2?"selected=''":''?> value="2">Vietnam</option>
            <option <?=@$get->lang_id==3?"selected=''":''?> value="3">English</option>
          </select>
        </p>
      </div> 
      <div class="edit_button">
        <p></p>
        <p><button class="btn btn-primary"><?=$type=='edit'?'Update':'Create'?></button></p>
      </div>
  </table>
</form>


</article>
</section>