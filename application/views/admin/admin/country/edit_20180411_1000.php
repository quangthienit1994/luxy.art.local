<section class="content_box">
  <h3><?=$type == 'edit'? $this->lang->line('edit') : $this->lang->line('create') ?></h3>
  <article class="member_edit">
    <h4><?=$type == 'edit'? $this->lang->line('edit') : $this->lang->line('create') ?> <?php echo $this->lang->line('information'); ?></h4>


    <form action="admin/add_edit_country" method="post" onsubmit="return submitAjax(this,{'load':2000,'redirect':'admin/page_list_country'})">
  <input type="hidden" name="id" value="<?=@$get->country_id?>">
  <table class="table table-hover bg-white">
      <div class="edit_conts">
        <p><?php echo $this->lang->line('country_name'); ?> <request></request></p>
        <p></p>
        <p><input required="" type="text" class="form-control" value="<?=@$get->country_name?>" name="country_name"></p>
      </div>

      <div class="edit_conts">
        <p><?php echo $this->lang->line('country_code'); ?> <request></request></p>
        <p></p>
        <p><input required="" type="text" class="form-control" value="<?=@$get->country_code?>" name="country_code"></p>
      </div>

      <div class="edit_conts">
        <p><?php echo $this->lang->line('currency_code'); ?> <request></request></p>
        <p></p>
        <p><input required="" type="text" class="form-control" value="<?=@$get->currency_code?>" name="currency_code"></p>
      </div>

      <div class="edit_conts">
        <p></p>
        <p><button class="btn btn-primary"><?=$type=='edit'? $this->lang->line('update') : $this->lang->line('create') ?></button></p>
      </div>
  </table>
</form>

</article>
</section>