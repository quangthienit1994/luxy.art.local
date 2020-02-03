<?php
$CI =& get_instance();
$CI->config->load();
$this->lang->load('dich', 'ja');
?>

<form action="admin/add_edit_lang" method="post" onsubmit="return submitAjax(this)">
  <input type="hidden" name="path" value="<?=@$path?>">
<!--   <label>キー</label> -->
  <textarea class="form-control" name="code" rows="20"><?=$code?></textarea>
  <br>
  <button class="btn btn-primary right"><?php echo $this->lang->line('update'); ?></button>
</form>