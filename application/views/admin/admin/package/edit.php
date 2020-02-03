<?php
$CI =& get_instance();
$CI->config->load();
$this->lang->load('dich', 'ja');
?>

<section class="content_box">
  <h3><?=$type == 'edit'? $this->lang->line('edit') : $this->lang->line('create') ?></h3>
  <article class="member_edit">
    <h4><?=$type == 'edit'? $this->lang->line('edit') : $this->lang->line('create') ?></h4>


    <form action="admin/add_edit_package" method="post" onsubmit="return submitAjax(this,{'load':2000,'redirect':'admin/page_list_package'})">
  <input type="hidden" name="id" value="<?=@$get->id_list?>">
  <table class="table table-hover bg-white">
      <div class="edit_conts">
        <p><?php echo $this->lang->line('package_name'); ?> <request></request></p>
        <p></p>
        <p><input class="form-control" required="" type="text" name="name_credit" value="<?=@$get->name_credit?>"></p>
      </div>
      <div class="edit_conts">
        <p><?php echo $this->lang->line('point'); ?> <request></request></p>
        <p></p>
        <p><input onkeyup="calPoint(this)" id="point_credit" min="1" class="form-control" required="" type="number" name="point_credit" value="<?=@$get->point_credit?>"></p>
      </div>
      <div class="edit_conts">
        <p><?php echo $this->lang->line('_price'); ?> <request></request></p>
        <p></p>
        <p><input onkeyup="calPoint(this)" id="money_pay_credit" min="1" class="form-control" required="" type="number" name="money_pay_credit" value="<?=@$get->money_pay_credit?>"></p>
      </div>
      <div class="edit_conts">
        <p><?php echo $this->lang->line('one_lux_unit_price'); ?> <request></request></p>
        <p></p>
        <p><input id="value_point_money" class="form-control" required="" type="text" name="value_point_money" value="<?=@$get->value_point_money?>"></p>
      </div>
      <div class="edit_button">
        <p></p>
        <p><button class="btn btn-primary"><?=$type=='edit'? $this->lang->line('update') : $this->lang->line('create') ?></button></p>
      </div>
  </table>
</form>

<script type="text/javascript">
  function calPoint(e) {
    var point_credit = $('#point_credit').val(); 
    var money_pay_credit = $('#money_pay_credit').val(); 
    var cal = Number((money_pay_credit/point_credit).toFixed(2));
    $('#value_point_money').val(cal);
  }
</script>

</article>
</section>