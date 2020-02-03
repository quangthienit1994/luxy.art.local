<?php
$CI =& get_instance();
$CI->config->load();
$this->lang->load('dich', 'ja');
?>

<!-- <form action="admin/page_list_image" method="get">
  <div class="input-group col-lg-3" style="float: right">
    <input type="text" name="keyword" class="form-control" placeholder="Search for..." value="<?=$keyword?>">
    <span class="input-group-btn">
      <button class="btn btn-secondary" style="z-index: 1000;"><i class="fa fa-search"></i></button>
    </span>
  </div>
</form> -->
<?php
  $listUserLevel = [
    1=>$this->lang->line('general_user'),
    2=>$this->lang->line('creator'),
    3=>$this->lang->line('platinum'),
    4=>$this->lang->line('diamond'),
    5=>$this->lang->line('corporate'),
  ];
  $listImageStatus = [
    0=>$this->lang->line('inactive'),
    1=>$this->lang->line('user_active'),
    //2=>$this->lang->line('user_bad'),
    //3=>"report",
    4=>$this->lang->line('user_delete'),
  ];
?>

<style>
.search_5 input[type="button"] {
    width: 150px;
    padding: 0 10px 0 10px;
    background-color: #009944;
    color: #fff;
    line-height: 2.5em;
}
</style>

<script>

$(document).ready(function(){
    $("#post_image").click(function(){
        window.open('<?php echo base_url(); ?>admin/images/post_image','_blank');
    });
});

</script>

<section class="content_box">
  <h3><?php echo $this->lang->line('image_search'); ?></h3>
  <form action="admin/page_list_image" method="get">
    <article class="search_area">
      <div class="search_area_cont">
        <p><?php echo $this->lang->line('membership_type'); ?></p>
        <p class="search_5">
          <select name="user_level">
            <option value=""><?php echo $this->lang->line('please_select'); ?></option>
            <?php foreach ($listUserLevel as $key => $value) { ?>
            <option <?=$user_level==$key?"selected=''":''?> value="<?=$key?>"><?=$value?></option>
            <?php } ?>
          </select>
        </p>
      </div>

      <div class="search_area_cont">
        <p><?php echo $this->lang->line('image_search_status'); ?></p>
        <p class="search_5">
          <select name="img_check_status">
            <option value=""><?php echo $this->lang->line('please_select'); ?></option>
            <?php foreach ($listImageStatus as $key => $value) { ?>
            <option <?=$img_check_status==$key&&$img_check_status!=''?"selected=''":''?> value="<?=$key?>"><?=$value?></option>
            <?php } ?>
          </select>
        </p>
      </div>

      <div class="search_area_cont">
        <p><?php echo $this->lang->line('category'); ?></p>
        <p class="search_5">
          <select name="cate_id">
            <option value=""><?php echo $this->lang->line('please_select'); ?></option>
            <?php foreach ($this->db->get_where('luxyart_tb_product_category',['lang_id'=>1])->result() as $key => $value) {?>
            <option <?=$cate_id==$value->cate_id?"selected=''":''?> value="<?=$value->cate_id?>"><?=$value->cate_name?></option>    
            <?php } ?>
        </select>
        </p>
      </div>


      <div class="search_area_cont">
        <p><?php echo $this->lang->line('keyword'); ?></p>
        <p class="search_5"><input type="text" name="keyword" placeholder="<?php echo $this->lang->line('please_enter_the_text'); ?>" value="<?=$keyword?>"></p>
      </div>
      <div class="search_button">
        <p class="search_5"><input type="submit" value="<?php echo $this->lang->line('search_member'); ?>"></p>
      </div>
    </article>
  </form>
</section>

<section class="content_box5">
    <h3><?php echo $this->lang->line('last_registered_image'); ?></h3>
    <p id="post_image" class="search_5"><input value="<?php echo $this->lang->line('add_picture'); ?>" type="button"></p>
    
    <article class="content5a">
      <?php foreach ($list as $key => $value) { ?>
      <?php
      $filename = $value->file_name_original_img;
      if($value->file_name_watermark_img_1!=''){
        $filename = $value->file_name_watermark_img_1;
      }
      ?>
      <div id="img-<?=$value->id_img_abc?>" class="imgs5"><p><a class="iframe" href="admin/image_info/<?=$value->id_img_abc?>" target="_blank"><img src="<?=$value->server_path_upload.'/'.$filename?>"  onerror="this.src='publics/product_img/image_default.png'"></a></p></div>
      <?php } ?>
    </article>
  </section>

<?php /*
<table class="table table-hover bg-white hidden">
    <thead>
        <tr>
           <th class="text-center">ID</th>
           <th>Title</th>
           <th>Image</th>
           <th>Owner</th>
           <th>Date upload</th>
           <th>Status</th>
           <th class="text-center">Action</th>
       </tr>
    </thead>
    <tbody>
    	<?php foreach ($list as $key => $value) { ?>
     	<tr>
        	<td class="text-center"><?=$value->id_img?></td>
        	<td class="fwb"><a href="admin/page_add_edit_image/<?=$value->id_img?>"><?=$value->img_title?></a> </td>
          <td><img src="<?=$value->server_path_upload.'/'.$value->file_name_original_img?>" style="width:70px; height: 70px; border-radius: 100%;" onerror="this.src='<?=base_url('publics/product_img/image_default.png')?>'"></td>
          <td><a href="admin/page_add_edit_user/<?=$value->user_id?>"><?=$value->user_firstname==''&&$value->user_lastname==''?$value->user_email:$value->user_firstname.' '.$value->user_lastname?></a></td>
          
        	<td><?=$value->date_add_img?></td>
        	<td>
              <form class="left" action="admin/status_image/<?=$value->id_img?>" method="post" onsubmit="return submitAjax(this)">
                  <select class="form-control" name="status" onchange="submitForm(this)">
                    <option <?=$value->img_check_status==0?'selected=""':''?> value="0">No Active</option>
                    <option <?=$value->img_check_status==1?'selected=""':''?> value="1">Active</option>
                    <option <?=$value->img_check_status==2?'selected=""':''?> value="2">Bad</option>
                    <option <?=$value->img_check_status==3?'selected=""':''?> value="3">Report</option>
                    <option <?=$value->img_check_status==4?'selected=""':''?> value="4">Delete</option>
                  </select>
              </form>
          </td>
        	<td class="text-center">
        		<div class="btn-group">
        			<button type="button" onclick="location.href='admin/page_add_edit_image/<?=$value->id_img?>'" class="btn btn-info left"><i class="fa fa-eye"></i></button>
  				  	<button onclick="location.href='admin/page_add_edit_image/<?=$value->id_img?>'" type="button" class="btn btn-warning left"><i class="fa fa-pencil"></i></button>
  				  	
              <form class="left" action="admin/delete_image/<?=$value->id_img?>" method="post" onsubmit="return submitAjax(this,{'load':1000,'confirm':true})">
                  <button class="btn btn-danger"><i class="fa fa-times"></i></button>
              </form>
				    </div>
        	</td>
       	</tr>
       	<?php } ?>
    </tbody>
</table>

*/?>


<center>
  <ul class="pagination">
    <?php for($i = 1; $i <= $numPage; $i++) { ?>
    <li <?=$i==$currentPage?"class='active'":''?>><a href="admin/page_list_image/<?=$i?>?keyword=<?=$keyword?>"><?=$i?></a></li>
    <?php } ?>
  </ul>
</center>