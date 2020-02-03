<?php
$CI =& get_instance();
$CI->config->load();
$this->lang->load('dich', 'ja');
?>

<section class="content_box">
  <h3><?=$type == 'edit'? $this->lang->line('edit') : $this->lang->line('create') ?></h3>
  <article class="member_edit">
    <h4><?=$type == 'edit'? $this->lang->line('edit') : $this->lang->line('create') ?> <?php echo $this->lang->line('information'); ?></h4>


    <form action="admin/add_edit_package" method="post" onsubmit="return submitAjax(this,{'load':2000,'redirect':'admin/page_list_package'})">
  <input type="hidden" name="id" value="<?=@$get->id_list?>">
  <table class="table table-hover bg-white">
      <div class="edit_conts">
        <p><?php echo $this->lang->line('point_credit'); ?></p>
        <p></p>
        <p><input class="form-control" required="" type="text" name="point_credit" value="<?=@$get->point_credit?>"></p>
      </div>
      <div class="edit_conts">
        <p><?php echo $this->lang->line('money_pay_credit'); ?></p>
        <p></p>
        <p><input class="form-control" required="" type="text" name="money_pay_credit" value="<?=@$get->money_pay_credit?>"></p>
      </div>
      <div class="edit_conts">
        <p><?php echo $this->lang->line('value_point_money'); ?></p>
        <p></p>
        <p><input class="form-control" required="" type="text" name="value_point_money" value="<?=@$get->value_point_money?>"></p>
      </div>
      <div class="edit_button">
        <p></p>
        <p><button class="btn btn-primary"><?=$type=='edit'? $this->lang->line('update') : $this->lang->line('create') ?></button></p>
      </div>
  </table>
</form>



</article>
</section>