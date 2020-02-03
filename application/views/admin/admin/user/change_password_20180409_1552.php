<?php
$CI =& get_instance();
$CI->config->load();
$this->lang->load('dich', 'ja');
?>

<section class="content_box">
  <h3><?php echo $this->lang->line('change_password_admin'); ?></h3>
  <article class="member_edit">
    <h4><?php echo $this->lang->line('change_password_admin'); ?></h4>

    <form action="admin/change_password_admin" method="post" onsubmit="return submitAjax(this,{'load':2000,'redirect':'admin/page_change_password_admin'})">
  <table class="table table-hover bg-white">

      <div class="edit_conts">
        <p><?php echo $this->lang->line('password'); ?> <request></request></p>
        <p></p>
        <p><input required="" class="form-control" type="password" name="admin_pass"></p>
      </div>

      <div class="edit_conts">
        <p><?php echo $this->lang->line('password_confirmation'); ?> <request></request></p>
        <p></p>
        <p><input required="" class="form-control" type="password" name="admin_pass_confirm"></p>
      </div>

      <div class="edit_button">
        <p></p>
        <p><button class="btn btn-primary"><?php echo $this->lang->line('update'); ?></button></p>
      </div>
  </table>
</form>

</article>
</section>