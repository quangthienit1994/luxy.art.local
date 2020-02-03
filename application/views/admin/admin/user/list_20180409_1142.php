<section class="content_box">
  <h3>会員管理メニュー</h3>
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
        <input type="submit" class="btn_import" value="Import CSV">
        <form enctype="multipart/form-data" onsubmit="return submitAjax(this, {load:1000})" class="form-import" style="display: none;" method="post" action="admin/import_user">
          <input required="" type="file" name="file">
          <input type="submit" style="margin-top: 10px;" value="Import CSV">
        </form>
      </div>
      <div class="edit_button2">
        <form method="get" action="admin/export/user">
          <input type="submit" value="CSV出力">
        </form>
      </div>
      <div class="edit_button2">
        <form method="get" action="admin/page_add_edit_user">
          <input type="submit" value="新規作成">
        </form>
      </div>

  </div>
</section>

<section class="content_box">
  <h3>会員検索</h3>
  <article class="search_text">
    <p>検索条件を設定し検索してください。</p>
  </article>
  <form action="admin/page_list_user" method="get">
    <article class="search_area">
      <div class="search_area_cont">
        <p>会員種別</p>
        <p class="search_2">
          <select name="user_level">
            <option value="">--- 選択してください ---</option>
            <!-- <option <?=$user_level==1?"selected=''":''?> value='1'>レギュラー</option>
            <option <?=$user_level==2?"selected=''":''?> value='2'>クリエイター</option>
            <option <?=$user_level==3?"selected=''":''?> value='3'>ゴールド</option>
            <option <?=$user_level==4?"selected=''":''?> value='4'>プラチナ</option>
            <option <?=$user_level==5?"selected=''":''?> value='5'>エンタープライズ</option> -->
            <option <?=$user_level==1?"selected=''":''?> value='1'>一般ユーザー</option>
            <option <?=$user_level==2?"selected=''":''?> value='2'>クリエイター</option>
            <option <?=$user_level==3?"selected=''":''?> value='3'>プラチナ</option>
            <option <?=$user_level==4?"selected=''":''?> value='4'>ダイヤモンド</option>
            <option <?=$user_level==5?"selected=''":''?> value='5'>法人</option>
          </select></p>
      </div>
      <div class="search_area_cont">
        <p>フリーワード</p>
        <p class="search_2"><input type="text" placeholder="テキストを入力してください" name="keyword" value="<?=$keyword?>"></p>
      </div>
      <div class="search_area_cont">
        <p>Pickup</p>
        <p class="search_2">
          <div class="checkbox-custom mb5">
            <input <?=@$_GET['pickup']==1?'checked':''?> id="check-active" type="checkbox" name="pickup" value="1">
            <label for="check-active">Pickup</label>
          </div>
        </p>
      </div>
      <div class="search_button">
        <p class="search_2"><input type="submit" value="検索"></p>
      </div>
    </article>
  </form>
</section>

<section class="content_box">
    <article class="member_info">
      <?php if(count($list)==0){ ?>
      <div class="alert alert-danger fade in"><strong>データが見つかりません！</strong></div>
      <?php }else{?>
      <table class="">
          <tbody>
            <tr>
                <th>会員グレード</th>
                <th>ユーザー名</th>
                <th>登録日</th>
                <th>保有ポイント</th>
                <th>登録画像数</th>
                <th>販売画像数</th>
                <th>購入画像数</th>
                <th style="width: 170px;">操作</th>
                <th style="width: 50px;">Status</th>
            </tr>
          	<?php foreach ($list as $key => $value) { ?>
           	<tr>
              	<td>
                <?php switch ($value->user_level) {
                  case 1:
                    echo " 一般ユーザー";
                    break;
                  case 2:
                    echo "クリエイター";
                    break;
                  case 3:
                    echo "プラチナ";
                    break;
                  case 4:
                    echo "ダイヤモンド";
                    break;
                  case 5:
                    echo "法人";
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
                  <a  href="admin/page_add_edit_user/<?=$value->user_id?>">編集</a>
                  <form style="padding-top: 2px;" action="admin/status_user/<?=$value->user_id?>" method="post" onsubmit="return submitAjax(this,{'load':1000,'confirm':true})">
                        <input type="hidden" name="status" value="2">
                        <a style="padding: 6px 5px 4px 8px;" onclick="submitForm(this)" href="javascript:void(0)">削除</a>
                  </form>
                  <?php 
                  $pickup = count($this->db->get_where('luxyart_tb_user_pickup',['user_id'=>$value->user_id])->result());
                  ?>
  

                  <form class="left" action="admin/pickup_user/<?=$value->user_id?>" method="post" onsubmit="return submitAjax(this,{'load':1000})">
                    <?php if($pickup>0){ ?>
                    <input type="hidden" name="status" value="0">
                    <button class="btn btn-danger" style="background: #fff; color: #645fbe; border: 1px solid #645fbe; margin-left: 5px; padding: 3px 5px;">unpick</button>
                    <?php }else{ ?>
                    <input type="hidden" name="status" value="1">
                    <button style="padding: 3px 5px; margin-left: 5px;" class="btn btn-danger">Pickup</button>
                    
                    <?php } ?>
                </form>
                
                	

              	</td>
                <td>
                	<select id="user_status" name="user_status" onchange="set_user_status(this.value,<?php echo $value->user_id; ?>)">
                        <option value="0" <?php if($value->user_status == 0) echo 'selected="selected"'; ?>>非活動中<option>
                        <option value="1" <?php if($value->user_status == 1) echo 'selected="selected"'; ?>>アクティブ<option>
                        <option value="2" <?php if($value->user_status == 2) echo 'selected="selected"'; ?>>削除<option>
                        <option value="3" <?php if($value->user_status == 3) echo 'selected="selected"'; ?>>悪い<option>
                    </select>
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

</script>