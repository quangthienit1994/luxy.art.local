<section class="content_box">
  <h3><?=$type == 'edit'?'Edit':'Create' ?></h3>
  <article class="member_edit">
    <h4><?=$type == 'edit'?'Edit':'Create' ?> information</h4>




    <form action="admin/add_edit_notice" method="post" onsubmit="return submitAjax(this,{'load':2000,'redirect':'admin/page_list_notice'})">
  <input type="hidden" name="id" value="<?=@$get->id_message?>">
  <table class="table table-hover bg-white">
      <?php if($type == 'edit') { ?>
      <div class="edit_conts">
        <p>Date create</p>
        <p></p>
        <p><?=@$get->date_message?></p>
      </div>
      <?php } ?>
      <div class="edit_conts">
        <p>User receive</p>
        <p></p>
        <p>
            <select class="form-control" name="user_id" >
              <?php foreach($user as $value) { ?>
              <option value="<?=$value->user_id?>" <?=$value->user_id==@$get->user_id?"selected=''":''?>><?=$value->user_firstname==''&&$value->user_lastname==''?$value->user_email:$value->user_firstname.' '.$value->user_lastname?></option>
              <?php } ?>
            </select>
        </p>
      </div>
      <div class="edit_conts">
        <p>Subject</p>
        <p></p>
        <p><input type="text" class="form-control" value="<?=@$get->ms_subject?>" name="ms_subject"></p>
      </div>

      <div class="edit_conts">
        <p>Content</p>
        <p style="width: 500px;"><textarea class="form-control ckeditor" rows="10" name="ms_content"><?=@$get->ms_content?></textarea></p>
      </div>

      <div class="edit_button">
        <p></p>
        <p><button class="btn btn-primary"><?=$type=='edit'?'Update':'Create'?></button></p>
      </div>
  </table>
</form>

</article>
</section>