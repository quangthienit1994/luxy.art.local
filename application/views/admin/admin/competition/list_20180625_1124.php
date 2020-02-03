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

<button onclick="location.href='admin/page_add_edit_competition'" class='btn btn-primary'><?php echo $this->lang->line('post_compe'); ?></button>

<!-- <button onclick="location.href='admin/page_list_c_agree'" class='btn btn-primary'>selection_period_setting_for_winning_image</button> -->

<form action="admin/page_list_competition" method="get">
  
  <div class="input-group col-lg-3" style="float: right">
    <select class="form-control" name="status" style="width: 150px; position: absolute; right: 248px; top: 18px;">
      <option value="">--- <?php echo $this->lang->line('status'); ?> ---</option>
      <option <?=$status==0&&$status!=null?'selected=""':''?> value="0"><?php echo $this->lang->line('create'); ?></option>
      <option <?=$status==1?'selected=""':''?> value="1"><?php echo $this->lang->line('active'); ?></option>
      <option <?=$status==2?'selected=""':''?> value="2"><?php echo $this->lang->line('the_image_will_be_applied'); ?></option>
      <option <?=$status==3?'selected=""':''?> value="3"><?php echo $this->lang->line('finish'); ?></option>
      <option <?=$status==4?'selected=""':''?> value="4"><?php echo $this->lang->line('delete'); ?> </option>
      <option <?=$status==5?'selected=""':''?> value="5"><?php echo $this->lang->line('hide'); ?></option>
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
           <th><?php echo $this->lang->line('competition_title'); ?></th>
           <th><?php echo $this->lang->line('cover_photo'); ?></th>
           <th><?php echo $this->lang->line('username'); ?></th>
           <th><?php echo $this->lang->line('posted_date'); ?></th>
           <th style="width: 80px;"><?php echo $this->lang->line('count_applied'); ?></th>
           <th><?php echo $this->lang->line('image_search_status'); ?></th>
           <th class="text-center" style="width: 120px;"><?php echo $this->lang->line('operation'); ?></th>
       </tr>
    </thead>
    <tbody>
    	<?php foreach ($list as $key => $value) { ?>
     	<tr>
        	<td class="text-center"><?=$value->id_competition?></td>
        	<td class="fwb"><a href="admin/page_add_edit_competition/<?=$value->id_competition?>"><?=$value->title_com?> </a></td>
          <td><img src="<?=base_url('publics/competition_icon/'.$value->icon_com)?>" style="width:50px; height: 50px;" onerror="this.src='<?=base_url('publics/product_img/image_default.png')?>'"></td>
          <td><a href="admin/page_add_edit_user/<?=$value->user_id?>"><?=$value->user_firstname==''&&$value->user_lastname==''?$value->user_email:$value->user_lastname.' '.$value->user_firstname?></a></td>
        	<td><?=$value->date_add_com?></td>
          <td class="text-center">
            <?php
            $count_apply = count($this->db->get_where('luxyart_tb_competition_img_apply', ['id_competition'=>$value->id_competition])->result());
            if($count_apply==0) echo $count_apply." ".$this->lang->line('to_apply');
            else{
            ?>
            <a href="admin/page_list_image_apply?competition_id=<?=$value->id_competition?>">
              <strong><?=$count_apply." ".$this->lang->line('to_apply');?></strong>
            </a>
            <?php } ?>
          </td>
        	<td class="text-center">
            <?php if($value->status_com>=3){ ?>
              <span class="text-danger fwb">
                <?php
                switch ($value->status_com) {
                  case 3:
                    echo $this->lang->line('finish');
                    break;
                  case 4:
                    echo $this->lang->line('delete');
                    break;
                  case 5:
                    echo $this->lang->line('hide');
                    break;
                  default:
                    # code...
                    break;
                }
                ?>
              </span>
            <?php }else{ ?>
              <form action="admin/status_competition/<?=$value->id_competition?>" method="post" onsubmit="return submitAjax(this,{'load':1000})">
                  <select class="form-control" name="status" onchange="submitForm(this)">
                    <option <?=$value->status_com==0?'selected=""':''?> value="0"><?php echo $this->lang->line('create'); ?></option>
                    <option <?=$value->status_com==1?'selected=""':''?> value="1"><?php echo $this->lang->line('active'); ?></option>
                    <option <?=$value->status_com==2?'selected=""':''?> value="2"><?php echo $this->lang->line('the_image_will_be_applied'); ?></option>
                    <option <?=$value->status_com==3?'selected=""':''?> value="3"><?php echo $this->lang->line('finish'); ?></option>
                    <option <?=$value->status_com==4?'selected=""':''?> value="4"><?php echo $this->lang->line('delete'); ?> </option>
                    <option <?=$value->status_com==5?'selected=""':''?> value="5"><?php echo $this->lang->line('hide'); ?>  </option>
                  </select>
              </form>
              <?php } ?>
          </td>
        	<td class="text-center">
            <?php if($value->status_com<3){ ?>
        		<div class="btn-group">
        			<button type="button" onclick="location.href='admin/page_add_edit_competition/<?=$value->id_competition?>'" class="btn btn-info left"><i class="fa fa-eye"></i></button>
  				  	<button onclick="location.href='admin/page_add_edit_competition/<?=$value->id_competition?>'" type="button" class="btn btn-warning left"><i class="fa fa-pencil"></i></button>
  				  	
              <form class="left" action="admin/delete_competition/<?=$value->id_competition?>" method="post" onsubmit="return submitAjax(this,{'load':1000,'confirm':true})">
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
    <li <?=$i==$currentPage?"class='active'":''?>><a href="admin/page_list_competition/<?=$i?>?keyword=<?=$keyword?>&status=<?=$status?>"><?=$i?></a></li>
    <?php } ?>
  </ul>
</center>