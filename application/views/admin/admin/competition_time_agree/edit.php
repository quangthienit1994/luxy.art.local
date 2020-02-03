<?php
$CI =& get_instance();
$CI->config->load();
$this->lang->load('dich', 'ja');
?>

<section class="content_box">
  <h3><?=$type == 'edit'? $this->lang->line('edit') : $this->lang->line('create') ?></h3>
  <article class="member_edit">
    <h4><?=$type == 'edit'? $this->lang->line('edit') : $this->lang->line('create') ?></h4>

    <form action="admin/add_edit_c_agree" method="post" onsubmit="return submitAjax(this,{'load':2000,'redirect':'admin/page_list_c_agree'})">
  <input type="hidden" name="id" value="<?=@$get->id?>">
  <table class="table table-hover bg-white">
      <div class="edit_conts">
        <p><?php echo $this->lang->line('name'); ?> <request></request></p>
        <p></p>
        <p><input class="form-control" required="" type="text" name="name" value="<?=@$get->name?>"></p>
      </div>
      <div class="edit_conts">
        <p><?php echo $this->lang->line('type'); ?> <request></request></p>
        <p></p>
        <p>
          <select class="form-control" name="type">
            <option <?=@$get->type==1?"selected=''":''?> value="1"><?php echo $this->lang->line('_day'); ?></option>
            <option <?=@$get->type==2?"selected=''":''?> value="2"><?php echo $this->lang->line('_week'); ?></option>
            <option <?=@$get->type==3?"selected=''":''?> value="3"><?php echo $this->lang->line('_month'); ?></option>
          </select>
        </p>
      </div>
      <div class="edit_button">
        <p></p>
        <p><button class="btn btn-primary"><?=$type=='edit'? $this->lang->line('update') : $this->lang->line('create') ?></button></p>
      </div>
  </table>
</form>

</article>
</section>