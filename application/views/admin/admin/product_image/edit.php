<section class="content_box">
  <h3><?=$type == 'edit'?'Edit':'Create' ?></h3>
  <article class="member_edit">
    <h4><?=$type == 'edit'?'Edit':'Create' ?> information</h4>


    <form action="admin/add_edit_user" method="post" onsubmit="return submitAjax(this,{'load':2000,'redirect':'admin/page_list_user'})">
  <input type="hidden" name="id" value="<?=@$get->user_id?>">
  <table class="table table-hover bg-white">
      <?php if($type == 'edit') { ?>
      <div class="edit_conts">
        <p>Date register</p>
        <p></p>
        <p><?=@$get->user_dateregister?></p>
      </div>
      <?php } ?>

      <div class="edit_conts">
        <p>First name</p>
        <p></p>
        <p><input class="form-control" required="" type="text" name="user_firstname" value="<?=@$get->user_firstname?>"></p>
      </div>
      <div class="edit_conts">
        <p>Last name</p>
        <p></p>
        <p><input  class="form-control" required="" type="text" name="user_lastname" value="<?=@$get->user_lastname?>"></p>
      </div>
      <div class="edit_conts">
        <p>Email</p>
        <p></p>
        <p><input required="" class="form-control" <?=$type=='edit'?'disabled=""':''?> type="email" name="user_email" value="<?=@$get->user_email?>"></p>
      </div>
      <div class="edit_conts">
        <p>Password <?=$type=='edit'?'<br/>(Not change if empty)':''?></p>
        <p></p>
        <p><input <?=$type!='edit'?'required=""':''?> class="form-control" type="text" name="user_pass"></p>
      </div>
      <div class="edit_conts">
        <p>City name</p>
        <p></p>
        <p><input class="form-control" type="text" name="cityname" value="<?=@$get->cityname?>"></p>
      </div>
      <div class="edit_conts">
        <p>Country</p>
        <p></p>
        <p>
            <select class="form-control" name="country_id">
              <?php foreach($country as $value) { ?>
              <option value="<?=$value->country_id?>" <?=$value->country_id==@$get->country_id?"selected=''":''?>><?=$value->country_name?></option>
              <?php } ?>
            </select>
        </p>
      </div>
      <div class="edit_conts">
        <p>State</p>
        <p></p>
        <p>
            <select class="form-control" name="state_id">
              <?php foreach($country_state as $value) { ?>
              <option value="<?=$value->state_id?>" <?=$value->state_id==@$get->state_id?"selected=''":''?>><?=$value->state_name?></option>
              <?php } ?>
            </select>
        </p>
      </div>
       <div class="edit_conts">
        <p>Address</p>
        <p></p>
        <p><textarea class="form-control" name="user_address"><?=@$get->user_address?></textarea>
        </p>
      </div>
      <div class="edit_conts">
        <p>Introduction</p>
        <p></p>
        <p><textarea class="form-control" name="user_introduction"><?=@$get->user_introduction?></textarea>
        </p>
      </div>
      <div class="edit_conts">
        <p>Avatar</p>
        <p></p>
        <p><input type="file" name="avatar" class="form-control">
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