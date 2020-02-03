<?php
$CI =& get_instance();
$CI->config->load();
$this->lang->load('dich', 'ja');
?>

<section class="content_box">
  <h3><?=$type == 'edit'? $this->lang->line('edit') : $this->lang->line('create') ?></h3>
  <article class="member_edit">
    <h4><?=$type == 'edit'? $this->lang->line('edit') : $this->lang->line('create') ?></h4>


    <form action="admin/add_edit_meta" method="post" onsubmit="return submitAjax(this,{'load':2000,'redirect':'admin/page_list_meta'})">
  <?php
  $lang = [
    ['key'=>1,'slug'=>'ja','name'=>$this->lang->line('japan')],
    ['key'=>2,'slug'=>'vi','name'=>$this->lang->line('vietnam')],
    ['key'=>3,'slug'=>'en','name'=>$this->lang->line('english')]
  ];
  ?>
  <div class="panel panel-default panel-add-menu-item">
    <div class="panel-heading">
        <ul class="nav panel-tabs-border panel-tabs panel-tabs-left">
          <?php foreach ($lang as $key => $value) { ?>
          <li class="<?=$value['key']==$get->lang_id?'active':''?> <?=($key==0&&$get->lang_id=='')?'active':''?>">
                <a href=".tab-<?=$value['slug']?>" data-toggle="tab" aria-expanded="true">
                <?=$value['name']?></a>
          </li>
          <?php } ?>
        </ul>   
    </div>
    <div class="panel-body">                                
      <div class="tab-content tab-hidden pn br-n">
        <?php foreach ($lang as $key => $value) { ?>
          <div class="tab-pane  tab-<?=$value['slug']?> <?=$value['key']==$get->lang_id?'active':''?> <?=($key==0&&$get->lang_id=='')?'active':''?>">
            <?php
              $this->db->flush_cache();
              $this->db->where('page_seo_id', $get->page_seo_id);
              $this->db->where('lang_id', $value['key']);
              $item = $this->db->get('luxyart_tb_page_seo')->row();
            ?>
            <input type="hidden" value="<?=@$get->page_seo_id?>" name="page_seo_id">
            <input type="hidden" value="<?=@$item->id_seo?>" name="id_seo[<?=$value['slug']?>]">
            <input type="hidden" value="<?=@$value['key']?>" name="lang_id[<?=$value['slug']?>]">
            <table class="table table-hover bg-white">
                <div class="edit_conts">
                  <p><?php echo $this->lang->line('page_title'); ?> (<?=$value['slug']?>) <request></request></p>
                  <p></p>
                  <p><input type="text" class="form-control" value="<?=@$item->page_title_name?>" name="page_title_name[<?=$value['slug']?>]"></p>
                </div>
                <div class="edit_conts hidden">
                  <p><?php echo $this->lang->line('name_call_code'); ?> (<?=$value['slug']?>) <request></request></p>
                  <p></p>
                  <p><input <?=$type=='edit'?'readonly=""':''?> type="text" class="form-control" value="<?=@$item->name_call_code?>" name="name_call_code[<?=$value['slug']?>]"></p>
                </div>
                <div class="edit_conts">
                  <p><?php echo $this->lang->line('meta_keyword'); ?> (<?=$value['slug']?>) <request></request></p>
                  <p></p>
                  <p><input type="text" class="form-control" value="<?=@$item->page_meta_key?>" name="page_meta_key[<?=$value['slug']?>]"></p>
                </div>
                <div class="edit_conts">
                  <p><?php echo $this->lang->line('return_title'); ?> (<?=$value['slug']?>) <request></request></p>
                  <p></p>
                  <p><input type="text" class="form-control" value="<?=@$item->return_title?>" name="return_title[<?=$value['slug']?>]"></p>
                </div>
                <div class="edit_conts">
                  <p><?php echo $this->lang->line('return_message'); ?> (<?=$value['slug']?>) <request></request></p>
                  <p></p>
                  <p><input type="text" class="form-control" value="<?=@$item->return_msg?>" name="return_msg[<?=$value['slug']?>]"></p>
                </div>
                <div class="edit_conts">
                  <p><?php echo $this->lang->line('return_link'); ?> (<?=$value['slug']?>) <request></request></p>
                  <p></p>
                  <p><input type="text" class="form-control" value="<?=@$item->return_link?>" name="return_link[<?=$value['slug']?>]"></p>
                </div>
                <div class="edit_conts">
                  <p><?php echo $this->lang->line('page_description'); ?> (<?=$value['slug']?>) <request></request></p>
                  <p></p>
                  <p><textarea class="form-control" rows="10" name="page_description[<?=$value['slug']?>]"><?=@$item->page_description?></textarea></p>
                </div>
                <div class="edit_conts">
                  <p><?php echo $this->lang->line('h1'); ?> (<?=$value['slug']?>) <request></request></p>
                  <p></p>
                  <p><textarea class="form-control" rows="10" name="page_h1[<?=$value['slug']?>]"><?=@$item->page_h1?></textarea></p>
                </div>
                <div class="edit_button">
                  <p></p>
                  <p><button class="btn btn-primary"><?=$type=='edit'? $this->lang->line('update') : $this->lang->line('create') ?></button></p>
                </div>
            </table>                   
          </div>   
        <?php } ?>                             
        </div>
      </div>
    </div>

  
</form>

</article>
</section>