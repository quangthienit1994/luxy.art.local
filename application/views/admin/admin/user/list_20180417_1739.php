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

<section class="content_box">
  <h3><?php echo $this->lang->line('membership_management_menu'); ?></h3>
  <div class="edit_button2a">
      <div class="edit_button2">
        <script>
          $(function(){
            $('.btn_import').on('click', function(){
              $(this).hide();
              $('.form-import').show('slow');
            });
          });
        </script>
        <input type="submit" class="btn_import" value="<?php echo $this->lang->line('import_csv'); ?>">
        <form enctype="multipart/form-data" onsubmit="return submitAjax(this, {load:1000})" class="form-import" style="display: none;" method="post" action="admin/import_user">
          <input required="" type="file" name="file">
          <input type="submit" style="margin-top: 10px;" value="<?php echo $this->lang->line('import_csv'); ?>">
        </form>
      </div>
      <div class="edit_button2">
        <form method="get" action="admin/export/user">
          <input type="submit" value="<?php echo $this->lang->line('export_csv'); ?>">
        </form>
      </div>
      <div class="edit_button2">
        <form method="get" action="admin/page_add_edit_user">
          <input type="submit" value="<?php echo $this->lang->line('member_create'); ?>">
        </form>
      </div>

  </div>
</section>

<section class="content_box">
  <h3><?php echo $this->lang->line('member_search'); ?></h3>
  <article class="search_text">
    <p><?php echo $this->lang->line('please_set_search_conditions_and_search'); ?></p>
  </article>
  <form action="admin/page_list_user" method="get">
    <article class="search_area">
      <div class="search_area_cont">
        <p><?php echo $this->lang->line('membership_type'); ?></p>
        <p class="search_2">
          <select name="user_level">
            <option value=""><?php echo $this->lang->line('please_select'); ?></option>
            <option <?=$user_level==1?"selected=''":''?> value='1'><?php echo $this->lang->line('general_user'); ?></option>
            <option <?=$user_level==2?"selected=''":''?> value='2'><?php echo $this->lang->line('creator'); ?></option>
            <option <?=$user_level==3?"selected=''":''?> value='3'><?php echo $this->lang->line('platinum'); ?></option>
            <option <?=$user_level==4?"selected=''":''?> value='4'><?php echo $this->lang->line('diamond'); ?></option>
            <option <?=$user_level==5?"selected=''":''?> value='5'><?php echo $this->lang->line('corporate'); ?></option>
          </select></p>
      </div>
      <div class="search_area_cont">
        <p><?php echo $this->lang->line('keyword'); ?></p>
        <p class="search_2"><input type="text" placeholder="<?php echo $this->lang->line('please_enter_the_text'); ?>" name="keyword" value="<?=$keyword?>"></p>
      </div>
      <div class="search_area_cont">
        <p><?php echo $this->lang->line('pickup'); ?></p>
        <p class="search_2">
          <div class="checkbox-custom mb5">
            <input <?=@$_GET['pickup']==1?'checked':''?> id="check-active" type="checkbox" name="pickup" value="1">
            <label for="check-active"><?php echo $this->lang->line('pickup'); ?></label>
          </div>
        </p>
      </div>
      <div class="search_button">
        <p class="search_2"><input type="submit" value="<?php echo $this->lang->line('search_member'); ?>"></p>
      </div>
    </article>
  </form>
</section>

<section class="content_box">
    <article class="member_info">
      <?php if(count($list)==0){ ?>
      <div class="alert alert-danger fade in"><strong><?php echo $this->lang->line('data_not_found'); ?></strong></div>
      <?php }else{?>
      <table class="">
          <tbody>
            <tr>
                <th><?php echo $this->lang->line('member_grade'); ?></th>
                <th><?php echo $this->lang->line('username'); ?></th>
                <th><?php echo $this->lang->line('registration_date'); ?></th>
                <th><?php echo $this->lang->line('holding_points'); ?></th>
                <th><?php echo $this->lang->line('number_of_registered_images'); ?></th>
                <th><?php echo $this->lang->line('number_of_images_sold'); ?></th>
                <th<?php echo $this->lang->line('number_of_images_purchased'); ?>></th>
                <th><?php echo $this->lang->line('operation'); ?></th>
                <th width="215"><?php echo $this->lang->line('status'); ?></th>
                <th></th>
            </tr>
          	<?php foreach ($list as $key => $value) { ?>
           	<tr>
              	<td>
                <?php switch ($value->user_level) {
                  case 1:
                    echo $this->lang->line('general_user');
                    break;
                  case 2:
                    echo $this->lang->line('creator');
                    break;
                  case 3:
                    echo $this->lang->line('platinum');
                    break;
                  case 4:
                    echo $this->lang->line('diamond');
                    break;
                  case 5:
                    echo $this->lang->line('corporate');
                    break;
                  
                  default:
                    # code...
                    break;
                } ?>
                </td>
              	<td class="fwb text-left"><?=$value->user_firstname==''&&$value->user_lastname==''?$value->user_email:$value->user_firstname.' '.$value->user_lastname?></td>
                <td><?=date('d-m-Y', strtotime($value->user_dateregister))?></td>
                <td style="width: 70px;"><?=$value->user_point+$value->user_paymoney_getpoint?></td>
                <td style="width: 70px;"><?=count($this->db->get_where('luxyart_tb_product_img',['user_id'=>$value->user_id])->result());?></td>
                <td style="width: 70px;"><?php
                    $count = 0;
                    foreach ($this->db->get_where('luxyart_tb_product_img_order',['user_buy_img'=>$value->user_id])->result() as $key2 => $value2) {
                      $count += count($this->db->get_where('luxyart_tb_product_img_order_detail',['id_or_detail'=>$value2->id_order_img])->result());
                    }
                    echo $count;
                  ?>
                  
                </td>
                <td style="width: 70px;"><?=count($this->db->get_where('luxyart_tb_product_img_order_detail',['user_owner'=>$value->user_id])->result());?></td>
              	<td style="display: flex;">
                  <a  href="admin/page_add_edit_user/<?=$value->user_id?>"><?php echo $this->lang->line('edit'); ?></a>
                  <form style="padding-top: 2px;" action="admin/status_user/<?=$value->user_id?>" method="post" onsubmit="return submitAjax(this,{'load':1000,'confirm':true})">
                        <input type="hidden" name="status" value="2">
                        <a style="padding: 6px 5px 4px 8px;" onclick="submitForm(this)" href="javascript:void(0)"><?php echo $this->lang->line('delete'); ?></a>
                  </form>
                  <?php 
                  $pickup = count($this->db->get_where('luxyart_tb_user_pickup',['user_id'=>$value->user_id])->result());
                  ?>
  

                  <form class="left" action="admin/pickup_user/<?=$value->user_id?>" method="post" onsubmit="return submitAjax(this,{'load':1000})">
                    <?php if($pickup>0){ ?>
                    <input type="hidden" name="status" value="0">
                    <button class="btn btn-danger" style="background: #fff; color: #645fbe; border: 1px solid #645fbe; margin-left: 5px; padding: 3px 5px;"><?php echo $this->lang->line('unpickup'); ?></button>
                    <?php }else{ ?>
                    <input type="hidden" name="status" value="1">
                    <button style="padding: 3px 5px; margin-left: 5px;" class="btn btn-danger"><?php echo $this->lang->line('pickup'); ?></button>
                    
                    <?php } ?>
                </form>
                
                	

              	</td>
                <td>
                	<select id="user_status" name="user_status" onchange="set_user_status(this.value,<?php echo $value->user_id; ?>)">
                        <option value="0" <?php if($value->user_status == 0) echo 'selected="selected"'; ?>><?php echo $this->lang->line('user_inactive'); ?><option>
                        <option value="1" <?php if($value->user_status == 1) echo 'selected="selected"'; ?>><?php echo $this->lang->line('user_active'); ?><option>
                        <option value="2" <?php if($value->user_status == 2) echo 'selected="selected"'; ?>><?php echo $this->lang->line('user_delete'); ?><option>
                        <option value="3" <?php if($value->user_status == 3) echo 'selected="selected"'; ?>><?php echo $this->lang->line('user_bad'); ?><option>
                    </select>
                </td>
                
                <td>
                	<a onclick="delete_user("<?=$value->user_id?>");">Delete</a>
                </td>
                
             	</tr>
             	<?php } ?>
          </tbody>
      </table>
      <center>
        <ul class="pagination">
          <?php for($i = 1; $i <= $numPage; $i++) { ?>
          <li <?=$i==$currentPage?"class='active'":''?>><a href="admin/page_list_user/<?=$i?>?keyword=<?=$keyword?>&user_level=<?=$user_level?>"><?=$i?></a></li>
          <?php } ?>
        </ul>
      </center>
      <?php } ?>
    </article>
  </section>
  
<script>

function set_user_status(status, user_id){
	
	$.ajax({
		type: "POST",cache: false,
		url: "<?php echo base_url(); ?>admin/admin/set_user_status",
		data: "status="+status+"&user_id="+user_id,
		success: function(data) {
			if(data == 1){
				location.reload();
			}
		}
		, async: false
	});
	
}

function delete_user(user_id){
	
	alert("user_id = " + user_id);
	
}

</script>