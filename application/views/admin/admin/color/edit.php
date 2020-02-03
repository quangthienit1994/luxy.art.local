<section class="content_box">
  <h3><?=$type == 'edit'?'Edit':'Create' ?></h3>
  <article class="member_edit">
    <h4><?=$type == 'edit'?'Edit':'Create' ?> information</h4>




    <form action="admin/add_edit_color" method="post" onsubmit="return submitAjax(this,{'load':2000,'redirect':'admin/page_list_color'})">
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
              $this->db->where('product_color_id', $get->product_color_id);
              $this->db->where('lang_id', $value['key']);
              $item = $this->db->get('luxyart_tb_product_color')->row();
            ?>
            <input type="hidden" value="<?=@$get->product_color_id?>" name="product_color_id">
            <input type="hidden" value="<?=@$item->id_color?>" name="id_color[<?=$value['slug']?>]">
            <input type="hidden" value="<?=@$value['key']?>" name="lang_id[<?=$value['slug']?>]">
            <table class="table table-hover bg-white">
                <div class="edit_conts">
                  <p>Name key (<?=$value['slug']?>)</p>
                  <p></p>
                  <p><input type="text" class="form-control" value="<?=@$item->name?>" name="name[<?=$value['slug']?>]"></p>
                </div>
                <div class="edit_conts">
                  <p>Name color (<?=$value['slug']?>)</p>
                  <p></p>
                  <p><input type="text" class="form-control" value="<?=@$item->name_color?>" name="name_color[<?=$value['slug']?>]"></p>
                </div>
                <div class="edit_conts">
                  <p>Color code (<?=$value['slug']?>)</p>
                  <p></p>
                  <p><input type="text" class="form-control" value="<?=@$item->css_color_style?>" name="css_color_style[<?=$value['slug']?>]"></p>
                </div>
                <div class="edit_button">
                  <p></p>
                  <p><button class="btn btn-primary"><?=$type=='edit'?'Update':'Create'?></button></p>
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