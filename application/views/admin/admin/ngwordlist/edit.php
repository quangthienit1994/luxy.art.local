<?php
$CI =& get_instance();
$CI->config->load();
$this->lang->load('dich', 'ja');
?>

<section class="content_box">
  <h3><?=$type == 'edit'? $this->lang->line('edit') : $this->lang->line('create') ?></h3>
  <article class="member_edit">
    <h4><?=$type == 'edit'? $this->lang->line('edit') : $this->lang->line('create') ?></h4>

    <form action="admin/add_edit_ngwordlist" method="post" onsubmit="return submitAjax(this,{'load':2000,'redirect':'admin/page_add_edit_ngwordlist'})" enctype="multipart/form-data">
    <!--<form action="admin/add_edit_ngwordlist" method="post" enctype="multipart/form-data">-->
      <table class="table table-hover bg-white">
    
          <div class="edit_conts">
            <p><?php echo $this->lang->line('ng_word_list'); ?></p>
            <p style="width: 500px;"><textarea class="form-control" name="word"><?=@$strWordList?></textarea>
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