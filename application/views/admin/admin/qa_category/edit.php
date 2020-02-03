<?php
$CI =& get_instance();
$CI->config->load();
$this->lang->load('dich', 'ja');
?>

<section class="content_box">
  <h3><?=$type == 'edit'? $this->lang->line('edit') : $this->lang->line('create') ?></h3>
  <article class="member_edit">
    <h4><?=$type == 'edit'? $this->lang->line('edit') : $this->lang->line('create') ?> <?php echo $this->lang->line('add_new_category_qa'); ?><?php echo $this->lang->line('information'); ?></h4>



    <form action="admin/add_edit_qa_category" method="post" onsubmit="return submitAjax(this,{'load':2000,'redirect':'admin/page_list_qa_category'})">
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
              $this->db->where('group_id', $get->group_id);
              $this->db->where('lang_id', $value['key']);
              $item = $this->db->get('luxyart_tb_qa_category')->row();
            ?>
            <input type="hidden" value="<?=@$get->group_id?>" name="group_id">
            <input type="hidden" value="<?=@$item->category_id?>" name="category_id[<?=$value['slug']?>]">
            <input type="hidden" value="<?=@$value['key']?>" name="lang_id[<?=$value['slug']?>]">
            <table class="table table-hover bg-white">
                <div class="edit_conts">
                  <p><?php echo $this->lang->line('title'); ?> (<?=$value['slug']?>) <request></request></p>
                  <p></p>
                  <p><input  type="text" class="form-control" value="<?=@$item->title?>" name="title[<?=$value['slug']?>]"></p>
                </div>
                <div class="edit_conts">
                  <p><?php echo $this->lang->line('title_seo'); ?> (<?=$value['slug']?>) <request></request></p>
                  <p></p>
                  <p><input  type="text" class="form-control" value="<?=@$item->category_title_seo?>" name="category_title_seo[<?=$value['slug']?>]"></p>
                </div>
                <div class="edit_conts">
                  <p><?php echo $this->lang->line('keyword_seo'); ?> (<?=$value['slug']?>) <request></request></p>
                  <p style="width: 500px;"><textarea class="form-control"  rows="4" name="category_keyword_seo[<?=$value['slug']?>]"><?=@$item->category_keyword_seo?></textarea></p>
                </div>
                <div class="edit_conts">
                  <p><?php echo $this->lang->line('description_seo'); ?> (<?=$value['slug']?>)</p>
                  <p style="width: 500px;"><textarea class="form-control"  rows="4" name="category_description_seo[<?=$value['slug']?>]"><?=@$item->category_description_seo?></textarea></p>
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