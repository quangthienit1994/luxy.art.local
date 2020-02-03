<?php
$CI =& get_instance();
$CI->config->load();
$this->lang->load('dich', 'ja');
?>

<?php
  $count= count($list);
?>
<section class="content_box4">
  <article class="content_box4a">
<?php foreach ($list as $key => $value) { ?>
    <div class="content4ac">
      <p><?=$key+1?></p>
      <p><img style="width:80px; height: 80px; border-radius: 100%;" src="publics/avatar/<?=$value->user_avatar?>"
onerror="this.src='<?=base_url('publics/avatar/default_avatar.png')?>'"></p>
      <p><?=$value->user_firstname==''&&$value->user_lastname==''?$value->user_email:$value->user_firstname.' '.$value->user_lastname?><br><?php echo $this->lang->line('time_line_count_image_user'); ?>:<?=count($this->db->get_where('luxyart_tb_product_img',['user_id'=>$value->user_id])->result());?></p>
      <p>
        <form class="left" action="admin/position_user_pickup/<?=$value->pickup_id?>" method="post" onsubmit="return submitAjax(this)">
            <select class="form-control" name="status" onchange="submitForm(this)" style="padding: 0px 10px; display: inline-block; min-width: 49px;">
              <?php for($i=1; $i<=$count;$i++) { ?>
              <option <?=$value->position==$i?'selected=""':''?> value="<?=$i?>"><?=$i?></option> 
              <?php } ?>
            </select>
        </form>
        <a href="admin/page_add_edit_user/<?=$value->user_id?>"><?php echo $this->lang->line('edit'); ?></a>
    </div>
<?php } ?>
  </article>



  <article class="content_box4b">
    <p><img src="publics/luxyart/img/common/pick_ups.png"></p>
  </article>      
</section>


<?php /*
<table class="table table-hover bg-white">
    <thead>
        <tr>
           <th class="text-center">ID</th>
           <th>Name</th>
           <th>Avatar</th>
           <th>Position</th>
           <th class="text-center">Action</th>
       </tr>
    </thead>
    <tbody>
      <?php
      $count= count($list);
      ?>
    	<?php foreach ($list as $key => $value) { ?>
     	<tr>
        	<td class="text-center"><?=$value->pickup_id?></td>
        	<td><a href="admin/page_add_edit_user/<?=$value->user_id?>"><?=$value->user_firstname==''&&$value->user_lastname==''?$value->user_email:$value->user_firstname.' '.$value->user_lastname?></a></td>
          <td class="text-center"><img src="publics/avatar/<?=$value->user_avatar?>" style="width:50px; height: 50px; border-radius: 100%;" onerror="this.src='<?=base_url('publics/avatar/default_avatar.png')?>'"></td>
        	<td>
              <form class="left" action="admin/position_user_pickup/<?=$value->pickup_id?>" method="post" onsubmit="return submitAjax(this)">
                  <select class="form-control" name="status" onchange="submitForm(this)">
                    <?php for($i=1; $i<=$count;$i++) { ?>
                    <option <?=$value->position==$i?'selected=""':''?> value="<?=$i?>"><?=$i?></option> 
                    <?php } ?>
                  </select>
              </form>
          </td>
        	<td class="text-center">
        		<div class="btn-group">  				  	
              <form class="left" action="admin/delete_user_pickup/<?=$value->pickup_id?>" method="post" onsubmit="return submitAjax(this,{'load':1000,'confirm':true})">
                  <button class="btn btn-danger"><i class="fa fa-times"></i></button>
              </form>
				    </div>
        	</td>
       	</tr>
       	<?php } ?>
    </tbody>
</table>
*/?>
