<section class="content_box">
  <h3><?=$type == 'edit'?'編集':'作成する' ?></h3>
  <article class="member_edit">
    <h4><?=$type == 'edit'?'編集':'作成する' ?> 情報</h4>


       <form action="admin/add_edit_email_tpl" method="post" onsubmit="return submitAjax(this,{'load':2000,'redirect':'admin/page_list_email_tpl'})">
  <?php
  $lang = [
    ['key'=>1,'slug'=>'ja','name'=>"Japan"],
    ['key'=>2,'slug'=>'vi','name'=>"Vietnam"],
    ['key'=>3,'slug'=>'en','name'=>"English"]
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
        <div class="edit_conts">
          <p>名前電子メールコード <br><request></request></p>
          <p></p>
          <p><input <?=$type=='edit'?'readonly=""':''?> type="text" class="form-control" value="<?=@$get->name_email_code?>" name="name_email_code"></p>
        </div>
        <?php foreach ($lang as $key => $value) { ?>
          <div class="tab-pane  tab-<?=$value['slug']?> <?=$value['key']==$get->lang_id?'active':''?> <?=($key==0&&$get->lang_id=='')?'active':''?>">
            <?php
              $this->db->flush_cache();
              $this->db->where('group_id', $get->group_id);
              $this->db->where('lang_id', $value['key']);
              $item = $this->db->get('luxyart_tb_email_send')->row();
            ?>
            <input type="hidden" value="<?=@$get->group_id?>" name="group_id">
            <input type="hidden" value="<?=@$item->id_email?>" name="id_email[<?=$value['slug']?>]">
            <input type="hidden" value="<?=@$value['key']?>" name="lang_id[<?=$value['slug']?>]">
            <table class="table table-hover bg-white">
                <div class="edit_conts">
                  <p>件名 <request></request>(<?=$value['slug']?>)</p>
                  <p></p>
                  <p><input  type="text" class="form-control" value="<?=@$item->subject?>" name="subject[<?=$value['slug']?>]"></p>
                </div>
                <div class="edit_conts">
                  <p>内容 (<?=$value['slug']?>)</p>
                  <p style="width: 500px;"><textarea class="form-control ckeditor" rows="10" name="content_email[<?=$value['slug']?>]"><?=@$item->content_email?></textarea></p>
                </div>
                <div class="edit_button">
                  <p></p>
                  <p><button class="btn btn-primary"><?=$type=='edit'?'更新':'作成する'?></button></p>
                </div>
            </table>                   
          </div>   
        <?php } ?>                             
        </div>
      </div>
    </div>

    


</article>
</section>