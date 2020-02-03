<?php if($competition_id!=null) { ?>
  <?php 
    $this->db->from('luxyart_tb_user_competition as c');
    $this->db->join('luxyart_tb_user as u','c.user_id=u.user_id','LEFT');
    $this->db->where('id_competition', $competition_id);
    $competition = $this->db->get()->row();
  ?>
<section class="content_box">
  <h3>コンペ情報: <a href="admin/page_add_edit_competition/<?=$competition->id_competition?>"><?=$competition->title_com?></a></h3>
  <div class="info_area">
    
    <style type="text/css">
      .aaa {
        border: 0px;
      }
      .aaa tr td {
        border: 0px;
      }

    </style>
    <table class="table table-hover aaa">
      <tr>
        <td class="fwb">アイコン: </td>
        <td class="fwb"><img src="publics/competition_icon/<?=$competition->icon_com ?>" onerror="this.src='<?=base_url('publics/product_img/image_default.png')?>'" style="width: 50px; height: 50px;"></td>
      </tr>
      <tr>
        <td class="fwb" style="width: 140px;">Photo description: </td>
        <td class="fwb"><img src="publics/competition_img/<?=$competition->photo_des_com ?>" onerror="this.src='<?=base_url('publics/product_img/image_default.png')?>'" style="width: 50px; height: 50px;"></td>
      </tr>
      <tr>
        <td class="fwb">タイトル: </td>
        <td class="fwb"><a href="admin/page_add_edit_competition/<?=$competition->id_competition?>"><?=$competition->title_com?></a></td>
      </tr>
      <tr>
        <td class="fwb">Owner: </td>
        <td class="fwb"><a href="admin/page_add_edit_user/<?=$competition->user_id?>"><?=$competition->user_firstname==''&&$competition->user_lastname==''?$competition->user_email:$competition->user_firstname.' '.$competition->user_lastname?></a></td>
      </tr>
      <tr>
        <td class="fwb">募集開始日: </td>
        <td class="fwb"><?=date('d-m-Y',strtotime($competition->date_start_com))?></td>
      </tr>
      <tr>
        <td class="fwb">締切日: </td>
        <td class="fwb"><?=date('d-m-Y',strtotime($competition->date_end_com))?></td>
      </tr>
       <tr>
        <td class="fwb">希望条件: </td>
        <td class="fwb">
          <?php foreach($this->db->get_where('luxyart_tb_product_color',['lang_id'=>1])->result() as $key => $value) { ?>
            <?php
              $ex = explode(',', @$competition->img_color_id);
              if(in_array($value->id_color,$ex)){ 
            ?>
            <div class="col-lg-3">
              <div class="checkbox-cdustom mb5">
                  <label for="color_<?=$key?>"><span style="color:#fff; margin-right: 10px; width: 40px;padding: 1px 3px; height: 20px; background: <?=$value->css_color_style;?>; color: <?=$value->css_color_style;?>">Color</span><?=$value->name?></label>
              </div>
            </div>
            <?php }} ?>  
        </td>
      </tr>
      <tr>
        <td colspan="2"><strong>募集要項:</strong> <br>
          <div></div><?=$competition->note_require_com ?>
        </td>
      </tr>
      <tr>
        <td colspan="2"><strong>応募条件:</strong> <br>
          <div></div><?=$competition->note_description_com ?>
        </td>
      </tr>
      <tr>
        <td colspan="2"><strong>その他注意事項:</strong> <br>
          <div></div><?=$competition->note_another_des_com ?>
        </td>
      </tr>
      <tr>
        <td colspan="2"><strong>コンペの日程:</strong> <br>
          <div></div><?=$competition->note_img_purpose_com ?>
        </td>
      </tr>
      <tr>
        <td class="fwb">賞金: </td>
        <td class="fwb"><?=$competition->point_img_com?>Lux x <?=$competition->img_quantity_com?>枚
          = <?=$competition->img_quantity_com*$competition->point_img_com?> Lux
        </td>
      </tr>
      <tr>
        <td class="fwb">応募者情報: </td>
        <td class="fwb"><a href="<?=$competition->link_url_com ?>"><?=$competition->link_url_com ?></a></td>
      </tr>
    </table>
  </div>
</section>
<?php } ?>

<form action="admin/page_list_image_apply" method="get">
  <div class="input-group col-lg-3" style="float: right">
    <input type="text" name="keyword" class="form-control" placeholder="Search for..." value="<?=$keyword?>">
    <input type="hidden" name="competition_id" value="<?=$competition_id?>">
    <span class="input-group-btn">
      <button class="btn btn-secondary" style="z-index: 1000;"><i class="fa fa-search"></i></button>
    </span>
  </div>
</form>

<table class="table table-hover bg-white">
    <thead>
        <tr>
           <th class="text-center">ID</th>
           <th>User</th>
           <th>Image</th>
           <th>Image</th>
           <th>Competition</th>
           <th>Status</th>
           <th>Date</th>
           <th class="text-center">Action</th>
       </tr>
    </thead>
    <tbody>
    	<?php foreach ($list as $key => $value) { ?>
     	<tr>
        	<td class="text-center"><?=$value->id_apply?></td>
        	<td><a target="_blank" href="admin/page_add_edit_user/<?=$value->user_id_abc?>"><?=$value->user_firstname ?> <?=$value->user_lastname ?></a> </td>
          <td><a target="_blank" href="admin/images/edit_image/<?=$value->id_img?>"><?=$value->img_title ?></a></td>
          <td class="text-center"><img src="<?=$value->server_path_upload.'/'.$value->file_name_original_img?>" onerror="this.src='<?=base_url('publics/product_img/image_default.png')?>'" style="width: 50px; height: 50px; "></td>
          <td><a target="_blank" href="admin/page_add_edit_competition/<?=$value->id_competition_abc?>"><?=$value->title_com ?></a></td>
          <td>
            <?php
            switch ($value->result_img) {
              case 0:
                echo "応募";
                break;
              case 1:
                echo "採用";
                break;
              case 2:
                echo "不採用";
                break;
              case 3:
                echo "Delete by user apply";
                break;
              
              default:
                echo "応募";
                break;
            }
            ?>
          </td>
          <td><?=$value->date_join ?></td>
        	<td class="text-center">
        		<div class="btn-group">
              <form class="left" action="admin/delete_image_apply/<?=$value->id_apply?>" method="post" onsubmit="return submitAjax(this,{'load':1000,'confirm':true})">
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
    <?php for($i = 1; $i <= $numPage; $i++) { ?>
    <li <?=$i==$currentPage?"class='active'":''?>><a href="admin/page_list_image_apply/<?=$i?>?keyword=<?=$keyword?>&competition_id=<?=$competition_id?>"><?=$i?></a></li>
    <?php } ?>
  </ul>
</center>