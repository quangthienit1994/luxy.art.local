<?php
$CI =& get_instance();
$CI->config->load();
$this->lang->load('dich', 'ja');
?>

<section class="content_box">
  <h3><?=$type == 'edit'? $this->lang->line('edit') : $this->lang->line('to_add') ?></h3>
  <article class="member_edit">
    <h4><?=$type == 'edit'? $this->lang->line('edit') : $this->lang->line('to_add') ?></h4>


    <form action="admin/add_edit_state" method="post" onsubmit="return submitAjax(this,{'load':2000,'redirect':'admin/page_list_state'})">
  <input type="hidden" name="id" value="<?=@$get->state_id?>">
  <table class="table table-hover bg-white">
      <div class="edit_conts">
        <p><?php echo $this->lang->line('state_name'); ?></p>
        <p></p>
        <p><input required="" type="text" class="form-control" value="<?=@$get->state_name?>" name="state_name"></p>
      </div>

      <div class="edit_conts">
        <p><?php echo $this->lang->line('coutry'); ?></p>
        <p></p>
        <p>
          <select class="form-control" name="country_id">
            <?php foreach ($country as $key => $value) { ?>
              <option <?=@$get->country_id==$value->country_id?"selected=''":''?> value="<?=$value->country_id?>"><?=$value->country_name?></option>
            <?php } ?>
          </select>
        </p>
      </div>

      <div class="edit_button">
        <p></p>
        <p><button class="btn btn-primary"><?=$type=='edit'? $this->lang->line('update') : $this->lang->line('to_add') ?></button></p>
      </div>
  </table>
</form>


</article>
</section>