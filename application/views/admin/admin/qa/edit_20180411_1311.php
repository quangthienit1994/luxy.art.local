<section class="content_box">
  <h3><?=$type == 'edit'?'Edit':'Create' ?></h3>
  <article class="member_edit">
    <h4><?=$type == 'edit'?'Edit':'Create' ?> information</h4>



    <form action="admin/add_edit_qa" method="post" onsubmit="return submitAjax(this,{'load':2000,'redirect':'admin/page_list_qa'})">
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
        <?php foreach ($lang as $key => $value) { ?>
          <div class="tab-pane  tab-<?=$value['slug']?> <?=$value['key']==$get->lang_id?'active':''?> <?=($key==0&&$get->lang_id=='')?'active':''?>">
            <?php
              $this->db->flush_cache();
              $this->db->where('group_id', $get->group_id);
              $this->db->where('lang_id', $value['key']);
              $item = $this->db->get('luxyart_tb_qa')->row();
            ?>
            <input type="hidden" value="<?=@$get->group_id?>" name="group_id">
            <input type="hidden" value="<?=@$item->qa_id?>" name="qa_id[<?=$value['slug']?>]">
            <input type="hidden" value="<?=@$value['key']?>" name="lang_id[<?=$value['slug']?>]">
            <table class="table table-hover bg-white">
                <div class="edit_conts">
                  <p>Title (<?=$value['slug']?>) <request></request></p>
                  <p></p>
                  <p><input  <?=$key==0?'required=""':''?>  type="text" class="form-control" value="<?=@$item->title?>" name="title[<?=$value['slug']?>]"></p>
                </div>
                <div class="edit_conts">
                  <p>Content (<?=$value['slug']?>) <request></request></p>
                  <p style="width: 500px;"><textarea  class="form-control ckeditor"  rows="10" name="content[<?=$value['slug']?>]"><?=@$item->content?></textarea></p>
                </div>
                <div class="edit_conts">
                  <p>Title seo (<?=$value['slug']?>) <request></request></p>
                  <p></p>
                  <p><input <?=$key==0?'required=""':''?>  type="text" class="form-control" value="<?=@$item->title_seo?>" name="title_seo[<?=$value['slug']?>]"></p>
                </div>
                <div class="edit_conts">
                  <p>Keyword seo (<?=$value['slug']?>) <request></request></p>
                  <p style="width: 500px;"><textarea <?=$key==0?'required=""':''?> class="form-control"  rows="4" name="keyword_seo[<?=$value['slug']?>]"><?=@$item->keyword_seo?></textarea></p>
                </div>
                <div class="edit_conts">
                  <p>Description seo (<?=$value['slug']?>)</p>
                  <p style="width: 500px;"><textarea class="form-control"  rows="4" name="description_seo[<?=$value['slug']?>]"><?=@$item->description_seo?></textarea></p>
                </div>
                <?php if($key==0){ ?>
                <div class="edit_conts">
                  <p>Category <request></request></p>
                  <p></p>
                  <p>
                      <?php
                        $category = $this->db->get_where('luxyart_tb_qa_category',['lang_id'=>$value['key']])->result();
                      ?>
                      <select required="" class="form-control" name="category_id">
                        <option value="">--- Choose category ---</option>
                        <?php foreach($category as $value_c) { ?>
                        <option value="<?=$value_c->group_id?>" <?=$value_c->category_id==@$get->category_id ?"selected=''":''?>><?=$value_c->title?></option>
                        <?php } ?>
                      </select>
                  </p>
                </div>
                <?php } ?>
            </table>                   
          </div>   
        <?php } ?>

        

        <div class="edit_button">
          <p></p>
          <p><button class="btn btn-primary"><?=$type=='edit'?'Update':'Create'?></button></p>
        </div>

        </div>
      </div>
    </div>

  
</form>

</article>
</section>