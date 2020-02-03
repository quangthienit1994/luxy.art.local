<?php
$CI =& get_instance();
$CI->config->load();
$this->lang->load('dich', 'ja');
?>

<section class="content_box">
  <h3><?=$type == 'edit'? $this->lang->line('edit') : $this->lang->line('create') ?></h3>
  <article class="member_edit">
    <h4><?=$type == 'edit'? $this->lang->line('edit') : $this->lang->line('create') ?></h4>


    <form action="admin/add_edit_setting" method="post" onsubmit="return submitAjax(this,{'load':2000,'redirect':'admin/page_list_setting'})">
  <input type="hidden" name="id" value="<?=@$get->setting_id?>">
  <input type="hidden" value="<?=@$get->name?>" name="name">
  <table class="table table-hover bg-white">
      <div class="edit_conts">
        <p><?php echo $this->lang->line('item'); ?> <request></request></p>
        <p></p>
        <p><input required="" type="text" class="form-control" value="<?=@$get->value_text?>" name="value_text"></p>
      </div>

      <div class="edit_conts">
        <p><?php echo $this->lang->line('value'); ?></p>
        <p></p>
        <p><textarea class="form-control" rows="3" name="value"><?=@$get->value?></textarea></p>
      </div>
      <div class="edit_button">
        <p></p>
        <p><button class="btn btn-primary"><?=$type=='edit'? $this->lang->line('update') : $this->lang->line('create') ?></button></p>
      </div>
  </table>
</form>

</article>
</section>