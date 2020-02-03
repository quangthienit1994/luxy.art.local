<?php
$CI =& get_instance();
$CI->config->load();
$this->lang->load('dich', 'ja');
?>

<section class="content_box">
  <h3><?=$type == 'edit'? $this->lang->line('edit') : $this->lang->line('create') ?></h3>
  <article class="member_edit">
    <h4><?=$type == 'edit'? $this->lang->line('edit') : $this->lang->line('create') ?></h4>


    <form action="admin/add_edit_tags" method="post" onsubmit="return submitAjax(this,{'load':2000,'redirect':'admin/page_list_tags'})">
  <input type="hidden" name="id" value="<?=@$get->id_product_tag?>">
  <input type="hidden" value="<?=@$get->name?>" name="name">
  <table class="table table-hover bg-white">
      <div class="edit_conts">
        <p><?php echo $this->lang->line('item'); ?> <request></request></p>
        <p></p>
        <p><input required="" type="text" class="form-control" value="<?=@$get->tag_name?>" name="tag_name"></p>
      </div>

      <div class="edit_conts">
        <p><?php echo $this->lang->line('root'); ?></p>
        <p></p>
        <p>
          <select class="form-control" name="root_cate" >
            <?php foreach ($this->db->get_where('luxyart_tb_product_category', ['lang_id'=>1])->result() as $key => $value) { ?>
            <option <?=$value->cate_id==@$get->root_cate?'selected=""':''?> value="<?=$value->cate_id?>"><?=$value->cate_name?></option>
            <?php } ?>
          </select>
        </p>
      </div>
      <div class="edit_conts">
        <p><?php echo $this->lang->line('parent'); ?></p>
        <p></p>
        <p>
          <select class="form-control" name="parent_cate" >
            <?php foreach ($this->db->get_where('luxyart_tb_product_category', ['lang_id'=>1])->result() as $key => $value) { ?>
            <option <?=$value->cate_id==@$get->parent_cate?'selected=""':''?> value="<?=$value->cate_id?>"><?=$value->cate_name?></option>
            <?php } ?>
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