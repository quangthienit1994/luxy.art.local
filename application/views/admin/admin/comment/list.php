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

<a target="_blank" href="admin/page_add_edit_ngwordlist" class='btn btn-primary'><?php echo $this->lang->line('ng_word_list'); ?><?=$status?></a>

<!-- <button onclick="location.href='admin/page_list_c_agree'" class='btn btn-primary'>selection_period_setting_for_winning_image</button> -->

<form action="admin/page_list_comment" method="get">
  
  <div class="input-group col-lg-3" style="float: right">
    <select class="form-control" name="status" style="width: 150px; position: absolute; right: 248px; top: 18px;">
      <option <?=$status==-1?'selected=""':''?> value="-1">--- <?php echo $this->lang->line('status'); ?> ---</option>
      <option <?=$status==0?'selected=""':''?> value="0"><?php echo $this->lang->line('inactive'); ?></option>
      <option <?=$status==1?'selected=""':''?> value="1"><?php echo $this->lang->line('active'); ?></option>
    </select>
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
           <th><?php echo $this->lang->line('username'); ?></th>
           <th><?php echo $this->lang->line('image'); ?></th>
           <th><?php echo $this->lang->line('comment'); ?></th>
           <th><?php echo $this->lang->line('level'); ?></th>
           <th><?php echo $this->lang->line('posted_date'); ?></th>
           <th><?php echo $this->lang->line('status'); ?></th>
           <th class="text-center" style="width: 120px;"><?php echo $this->lang->line('operation'); ?></th>
       </tr>
    </thead>
    <tbody>
    	<?php foreach ($list as $key => $value) { ?>
     	<tr>
        	<td class="text-center"><?=$value->comment_id?></td>
        	<td class="fwb"><a href="admin/page_add_edit_user/<?=$value->a_user_id?>"><?=$value->display_name?> </a></td>
          	<td>
            	<img src="<?= $value->server_path_upload."/".$value->file_name_original_img; ?>" style="width:50px; height: 50px;" onerror="this.src='<?=base_url('publics/product_img/image_default.png')?>'"><br />
                <a href="admin/images/edit_image/<?=$value->id_img?>"><?=$value->img_title?></a>
           	</td>
            <td class="fwb"><a target="_blank" href="admin/detail_comment/<?=$value->id_img?>"><?=$value->comment?> </a></td>
          	<td><?=$value->level; ?></td>
        	<td><?=$value->date_create?></td>
        	<td class="text-center">
              <!--<span class="text-danger fwb">
                <?php
                /*switch ($value->status) {
                  case 0:
                    echo $this->lang->line('inactive');
                    break;
                  case 1:
                    echo $this->lang->line('active');
                    break;
                  case 2:
                    echo $this->lang->line('delete');
                    break;
                  default:
                    # code...
                    break;
                }*/
                ?>
              </span>-->
              <form action="admin/status_comment/<?=$value->comment_id?>" method="post" onsubmit="return submitAjax(this,{'load':1000})">
              <!--<form action="admin/status_comment/<?//=$value->comment_id?>" method="post">-->
                  <select class="form-control" name="status" onchange="submitForm(this)">
                    <option <?=$value->status==0?'selected=""':''?> value="0"><?php echo $this->lang->line('inactive'); ?></option>
                    <option <?=$value->status==1?'selected=""':''?> value="1"><?php echo $this->lang->line('active'); ?></option>
                  </select>
              </form>
          </td>
        	<td class="text-center">
            <?php if($value->status_com<3){ ?>
        		<div class="btn-group">
        			<button type="button" onclick="location.href='admin/page_add_edit_comment/<?=$value->comment_id?>'" class="btn btn-info left"><i class="fa fa-eye"></i></button>
  				  	<button onclick="location.href='admin/page_add_edit_comment/<?=$value->comment_id?>'" type="button" class="btn btn-warning left"><i class="fa fa-pencil"></i></button>
  				  	
              <form class="left" action="admin/delete_comment/<?=$value->comment_id?>" method="post" onsubmit="return submitAjax(this,{'load':1000,'confirm':true})">
              <!--<form class="left" action="admin/delete_comment/<?//=$value->comment_id?>" method="post">-->
                  <button class="btn btn-danger"><i class="fa fa-times"></i></button>
              </form>
				    </div>
            <?php } ?>
        	</td>
       	</tr>
       	<?php } ?>
    </tbody>
</table>
<center>
  <ul class="pagination">
    <?php for($i = 1; $i <= $numPage; $i++) { ?>
    <li <?=$i==$currentPage?"class='active'":''?>><a href="admin/page_list_comment/<?=$i?>?status=<?=$status?>&keyword=<?=$keyword?>"><?=$i?></a></li>
    <?php } ?>
  </ul>
</center>