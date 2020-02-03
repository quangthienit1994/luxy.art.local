<?php
$CI =& get_instance();
$CI->config->load();
$this->lang->load('dich', 'ja');
?>

<section class="content_box">
  <h3><?=$type == 'edit'? $this->lang->line('edit') : $this->lang->line('create') ?></h3>
  <article class="member_edit">
    <h4><?=$type == 'edit'? $this->lang->line('edit') : $this->lang->line('create') ?></h4>

    <form action="admin/add_edit_comment" method="post" onsubmit="return submitAjax(this,{'load':2000,'redirect':'admin/page_list_comment/1?status=-1&keyword='})" enctype="multipart/form-data">
    <!--<form action="admin/add_edit_comment" method="post" enctype="multipart/form-data">-->
  <input type="hidden" name="id" value="<?=@$get->comment_id  ?>">
  <table class="table table-hover bg-white">

      <div class="edit_conts">
        <p><?php echo $this->lang->line('comment'); ?></p>
        <p style="width: 500px;"><textarea class="form-control" name="comment"><?=@$get->comment?></textarea>
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