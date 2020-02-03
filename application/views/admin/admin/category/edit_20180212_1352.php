<section class="content_box">
  <h3><?=$type == 'edit'?'編集':'作成する' ?></h3>
  <article class="member_edit">
    <h4><?=$type == 'edit'?'編集':'作成する' ?></h4>

    <form action="admin/add_edit_category" method="post" onsubmit="return submitAjax(this,{'load':2000,'redirect':'admin/page_list_category'})">
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
              $this->db->where('product_category_id', $get->product_category_id);
              $this->db->where('lang_id', $value['key']);
              $item = $this->db->get('luxyart_tb_product_category')->row();
            ?>
            <input type="hidden" value="<?=@$get->product_category_id?>" name="product_category_id">
            <input type="hidden" value="<?=@$item->cate_id?>" name="cate_id[<?=$value['slug']?>]">
            <input type="hidden" value="<?=@$value['key']?>" name="lang_id[<?=$value['slug']?>]">

                <div class="edit_conts">
                  <p>カテゴリ名 (<?=$value['slug']?>)</p>
                  <p><request></request></p>
                  <p><input type="text" required="" class="form-control" value="<?=@$item->cate_name?>" name="cate_name[<?=$value['slug']?>]"></p>
                </div>

                <div class="edit_conts">
                  <p>説明 (<?=$value['slug']?>)</p>
                  <p></p>
                  <p><textarea class="form-control"  name="note_description[<?=$value['slug']?>]"><?=@$item->note_description?></textarea></p>
                </div>

                <div class="edit_conts">
                  <p>Meta keyword (<?=$value['slug']?>)</p>
                  <p></p>
                  <p><textarea class="form-control"  name="meta_key_seo_cate[<?=$value['slug']?>]"><?=@$item->meta_key_seo_cate?></textarea></p>
                </div>
                                 
          </div> 
          
        <?php } ?> 

            <!-- <div class="edit_conts">
              <p>Level</p>
              <p></p>
              <p><input type="number" class="form-control" value="<?=@$item->level_cate!=''?$item->level_cate:0?>" min='0' max='2' name="level_cate"></p>
            </div> -->
            <div class="edit_conts">
              <p>ルートカテゴリ</p>
              <p></p>
              <p>
                <select class="form-control" name="root_cate" id="root_cate" data-state="<?=$get->parent_cate?>">
                  <option value="0">ルート</option>
                  <?php foreach ($list_category as $key => $valueCate) { ?>
                  <option <?=$valueCate->cate_id==$get->root_cate?"selected=''":''?> value="<?=$valueCate->cate_id?>"><?=$valueCate->cate_name?></option>
                  <?php } ?>
                </select>
              </p>
            </div>

            <!-- <div class="edit_conts hidden">
              <p>Parent category</p>
              <p></p>
              <p>
                <select class="form-control" name="parent_cate" id="parent_cate">
                  
                </select>
              </p>
            </div> -->
            <div class="edit_conts">
              <p>画像</p>
              <p></p>
              <p><input type="file" name="avatar" class="form-control"></p>
            </div>
            <div class="edit_conts">
              <p>ピックアップ</p>
              <p></p>
              <div class="checkbox-custom checkbox-primary mb5">
                <input type="checkbox" name="is_pickup" <?=$get->is_pickup==1?"checked=''":''?> value='1'  id="checkboxExample4">
                <label for="checkboxExample4">ピックアップ</label>
            </div>
            </div>
            <div class="edit_button">
              <p></p>
              <p><button class="btn btn-primary"><?=$type=='edit'?'更新':'作成する'?></button></p>
            </div>
                            
        </div>
      </div>
    </div>

  
</form>
</article>
</section>