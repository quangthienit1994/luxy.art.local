<?php
$CI =& get_instance();
$CI->config->load();
$this->lang->load('dich', 'ja');
?>

<script>
var are_you_sure = "<?= $this->lang->line('are_you_sure'); ?>";
var you_will_not_be_able_to_recover = "<?= $this->lang->line('you_will_not_be_able_to_recover'); ?>";
var yes = "<?= $this->lang->line('yes'); ?>";
var cancel = "<?= $this->lang->line('cancel'); ?>";
</script>

<button onclick="location.href='admin/page_add_edit_category'" class='btn btn-primary'><?php echo $this->lang->line('add_category'); ?></button>

<form action="admin/page_list_category" method="get" style="position: relative;">
  <select class="form-control" name="root_cate" style="width: 185px; position: absolute; right: 357px; top: 16px;">
    <option value="">-- <?php echo $this->lang->line('all_categories'); ?> --</option>
    <?php foreach ($this->db->get_where('luxyart_tb_product_category',['lang_id'=>1,'root_cate'=>0])->result() as $key => $value) { ?>
    <option <?=$value->product_category_id==$root_cate?"selected=''":''?> value="<?=$value->product_category_id?>"><?=$value->cate_name?></option>
    <?php } ?>
  </select>

  <div class="checkbox-custom checkbox-primary mb5" style="position: absolute; top: 23px; right: 232px;">
      <input type="checkbox" name="is_pickup" <?=$is_pickup==1?"checked=''":''?> value='1'  id="checkboxExample4">
      <label for="checkboxExample4"><?php echo $this->lang->line('pickup'); ?></label>
  </div>
  <div class="input-group col-lg-3" style="float: right">
    <input type="text" name="keyword" class="form-control" placeholder="<?php echo $this->lang->line('search_for'); ?>" value="<?=$keyword?>">
    <span class="input-group-btn">
      <button class="btn btn-secondary" style="z-index: 1000;"><i class="fa fa-search"></i></button>
    </span>
  </div>
</form>


<table class="table table-hover bg-white">
    <thead>
        <tr>
           <th class="text-center"><?php echo $this->lang->line('id'); ?></th>
           <th><?php echo $this->lang->line('category_name'); ?></th>
           <th><?php echo $this->lang->line('picture'); ?></th>
           <th><?php echo $this->lang->line('description'); ?></th>
           <th><?php echo $this->lang->line('parent_category'); ?></th>
           <!-- <th>Parent name</th> -->
           <th class="text-center"><?php echo $this->lang->line('action'); ?></th>
       </tr>
    </thead>
    <tbody>
      <?php foreach ($list as $key => $value) { ?>
      <tr>
          <td class="text-center"><?=$value->cate_id?></td>
          <td class="fwb"><a href="admin/page_add_edit_category/<?=$value->cate_id?>"><?=$value->cate_name?></a></td>
          <td><img src="<?=base_url('publics/cate_img/'.$value->img_category)?>" onerror="this.src='<?=base_url('publics/product_img/image_default.png')?>'" style="width: 50px; height: 50px; border-radius: 100%"></td>
          <td><?=$value->note_description?></td>
          <?php
            $root_name = $this->db->get_where('luxyart_tb_product_category', ['cate_id'=>$value->root_cate])->row();
            $parent_name = $this->db->get_where('luxyart_tb_product_category', ['cate_id'=>$value->parent_cate])->row();
          ?>
          <td><?=$root_name->cate_name?></td>
          <!-- <td><?=$root_name->cate_id==$parent_name->cate_id?'':$parent_name->cate_name?></td> -->
          <td class="text-center">
            <div class="btn-group">
              <button type="button" onclick="location.href='admin/page_add_edit_category/<?=$value->cate_id?>'" class="btn btn-info left"><i class="fa fa-eye"></i></button>
              <button onclick="location.href='admin/page_add_edit_category/<?=$value->cate_id?>'" type="button" class="btn btn-warning left"><i class="fa fa-pencil"></i></button>
              
              <form class="left" action="admin/delete_category/<?=$value->cate_id?>" method="post" onsubmit="return submitAjax(this,{'load':1000,'confirm':true})">
                  <button class="btn btn-danger"><i class="fa fa-times"></i></button>
              </form>

              <form class="left" action="admin/pickup_category/<?=$value->cate_id?>" method="post" onsubmit="return submitAjax(this,{'load':1000})">
                  <?php if($value->is_pickup==1){ ?>
                  <input type="hidden" name="status" value="0">
                  <button class="btn btn-danger" style="background: #fff; color: #645fbe; border: 1px solid #645fbe;"><?php echo $this->lang->line('unpickup'); ?></button>
                  <?php }else{ ?>
                  <input type="hidden" name="status" value="1">
                  <button class="btn btn-danger"><?php echo $this->lang->line('pickup'); ?></button>
                  <?php } ?>
              </form>
            </div>
          </td>
        </tr>
        <?php } ?>
    </tbody>
</table>


<center>
  <ul class="pagination">
    <?php for($i = 1; $i <= $numPage; $i++) { ?>
    <li <?=$i==$currentPage?"class='active'":''?>><a href="admin/page_list_category/<?=$i?>?keyword=<?=$keyword?>&is_pickup=<?=$is_pickup?>&root_cate=<?=$root_cate?>"><?=$i?></a></li>
    <?php } ?>
  </ul>
</center>