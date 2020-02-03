<?php
$CI =& get_instance();
$CI->config->load();
$this->lang->load('dich', 'ja');
?>

<section class="content_box">
  <h3><?=$type == 'edit'? $this->lang->line('edit') : $this->lang->line('create') ?></h3>
  <article class="member_edit">
    <h4><?=$type == 'edit'? $this->lang->line('edit') : $this->lang->line('create') ?> <?php echo $this->lang->line('information'); ?></h4>


    <form action="admin/add_edit_menu" method="post" onsubmit="return submitAjax(this,{'load':2000,'redirect':'admin/page_list_menu'})">
  <input type="hidden" name="id" value="<?=@$get->id_menu?>">
  <table class="table table-hover bg-white">
      <div class="edit_conts">
        <p><?php echo $this->lang->line('japan'); ?><request></request></p>
        <p></p>
        <p><input class="form-control" required="" type="text" name="menu_ja" value="<?=@$get->menu_ja?>"></p>
      </div>
      <div class="edit_conts">
        <p><?php echo $this->lang->line('vietnam'); ?><request></request></p>
        <p></p>
        <p><input class="form-control" required="" type="text" name="menu_vi" value="<?=@$get->menu_vi?>"></p>
      </div>
      <div class="edit_conts">
        <p><?php echo $this->lang->line('english'); ?><request></request></p>
        <p></p>
        <p><input class="form-control" required="" type="text" name="menu_en" value="<?=@$get->menu_en?>"></p>
      </div>
      <div class="edit_conts">
        <p><?php echo $this->lang->line('url'); ?><request></request></p>
        <p></p>
        <p><input class="form-control" required="" type="text" name="link_url" value="<?=@$get->link_url?>"></p>
      </div>
      <div class="edit_conts">
        <p><?php echo $this->lang->line('position'); ?><request></request></p>
        <p></p>
        <p><input class="form-control" required="" type="number" name="position_menu" value="<?=@$get->position_menu?>"></p>
      </div>
      <div class="edit_conts">
        <p><?php echo $this->lang->line('header'); ?></p>
        <p></p>
        <p>
            <select name="is_header" class="form-control">
              <option <?=@$get->is_header==0?"selected=''":""?> value="0"><?php echo $this->lang->line('no'); ?></option>
              <option <?=@$get->is_header==1?"selected=''":""?> value="1"><?php echo $this->lang->line('yes'); ?></option>
            </select>
        </p>
      </div>
      <div class="edit_conts">
        <p><?php echo $this->lang->line('footer'); ?></p>
        <p></p>
        <p>
            <select name="is_footer" class="form-control">
              <option <?=@$get->is_footer==0?"selected=''":""?> value="0"><?php echo $this->lang->line('no'); ?></option>
              <option <?=@$get->is_footer==1?"selected=''":""?> value="1"><?php echo $this->lang->line('yes'); ?></option>
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