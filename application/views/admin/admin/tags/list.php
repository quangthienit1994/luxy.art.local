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

<button onclick="location.href='admin/page_add_edit_tags'" class='btn btn-primary'><?php echo $this->lang->line('add_notification_message'); ?></button>

<form action="admin/page_list_tags" method="get">
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
           <th><?php echo $this->lang->line('name'); ?></th>
           <th><?php echo $this->lang->line('root_category'); ?></th>
           <th><?php echo $this->lang->line('parent_category'); ?></th>
           <th><?php echo $this->lang->line('status'); ?></th>
           <th class="text-center"><?php echo $this->lang->line('action'); ?></th>
       </tr>
    </thead>
    <tbody>
      <?php foreach ($list as $key => $value) { ?>
      <tr>
          <td class="text-center"><?=$value->id_product_tag?></td>
          <td><?=$value->tag_name?></td>
          <td>
            <?=$this->db->get_where('luxyart_tb_product_category', ['cate_id'=>$value->root_cate])->row()->cate_name?>
              
          </td>
          <td>
            <?=$this->db->get_where('luxyart_tb_product_category', ['cate_id'=>$value->parent_cate])->row()->cate_name?>
          </td>
          <td>
              <form class="left" action="admin/status_tags/<?=$value->id_product_tag?>" method="post" onsubmit="return submitAjax(this)">
                  <select class="form-control" name="status" onchange="submitForm(this)">
                    <option <?=$value->status==0?'selected=""':''?> value="0"><?php echo $this->lang->line('inactive'); ?></option>
                    <option <?=$value->status==1?'selected=""':''?> value="1"><?php echo $this->lang->line('active'); ?></option>
                  </select>
              </form>
          </td>
          <td class="text-center">
            <div class="btn-group">
              <button type="button" onclick="location.href='admin/page_add_edit_tags/<?=$value->id_product_tag?>'" class="btn btn-info left"><i class="fa fa-eye"></i></button>
              <button onclick="location.href='admin/page_add_edit_tags/<?=$value->id_product_tag?>'" type="button" class="btn btn-warning left"><i class="fa fa-pencil"></i></button>
              
              <form class="left" action="admin/delete_tags/<?=$value->id_product_tag?>" method="post" onsubmit="return submitAjax(this,{'load':1000,'confirm':true})">
                  <button class="btn btn-danger"><i class="fa fa-times"></i></button>
              </form>
            </div>
          </td>
        </tr>
        <?php } ?>
    </tbody>
</table>

<center>
<ul class="pagination">
 <?php
    echo $this->function_model->get_pagination_links($currentPage,$numPage,base_url()."admin/page_list_tags/{page}?keyword=".$keyword);
 ?>
</ul>
</center>