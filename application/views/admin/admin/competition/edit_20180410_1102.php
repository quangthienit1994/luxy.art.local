<section class="content_box">
  <h3><?=$type == 'edit'?'編集':'作成する' ?></h3>
  <article class="member_edit">
    <h4><?=$type == 'edit'?'編集':'作成する' ?></h4>




    <form action="admin/add_edit_competition" method="post" onsubmit="return submitAjax(this,{'load':2000,'redirect':'admin/page_list_competition'})" enctype="multipart/form-data">
  <input type="hidden" name="id" value="<?=@$get->id_competition  ?>">
  <table class="table table-hover bg-white">
      <div class="edit_conts">
        <p>ユーザー名 <request></request></p>
        <p></p>
        <p>
            <select required="" class="form-control" name="user_id">
              <option value="">--- ユーザー名 ---</option>
              <?php foreach($user as $value) { ?>
              <option value="<?=$value->user_id?>" <?=$value->user_id==@$get->user_id ?"selected=''":''?>><?=$value->user_firstname==''&&$value->user_lastname==''?$value->user_email:$value->user_firstname.' '.$value->user_lastname?></option>
              <?php } ?>
            </select>
        </p>
      </div>
      <div class="edit_conts">
        <p>タイトル <request></request></p>
        <p></p>
        <p><input class="form-control" required="" type="text" name="title_com" value="<?=@$get->title_com?>"></p>
      </div>

      <div class="edit_conts note_bg note_icon_bg">
        <p>カバー写真 <?=$type == 'edit'?'':'<request></request>' ?></p>
        <p></p>
        <p>
          <img src="publics/competition_icon/<?=@$get->icon_com ?>" onerror="this.src='publics/product_img/image_default.png'" style="width: 50px; height: 50px; float: left;">
          <input <?=$type == 'edit'?'':'required=""' ?> style="width: 71%; float: right; margin-top: 10px;" class="form-control" type="file" name="icon_com"></p>
      </div>
      <label class="note note_icon"></label>

      <div class="edit_conts note_bg note_photo_bg">
        <p>イメージ画像 <?=$type == 'edit'?'':'<request></request>' ?></p>
        <p></p>
        <p>
          <img src="publics/competition_img/<?=@$get->photo_des_com?>" onerror="this.src='publics/product_img/image_default.png'" style="width: 50px; height: 50px; float: left;">
          <input <?=$type == 'edit'?'':'required=""' ?> style="width: 71%; float: right; margin-top: 10px;" class="form-control" type="file" name="photo_des_com"></p>
      </div>
      <label class="note note_photo"></label>

      <div class="edit_conts">
        <p>掲載開始日 <request></request></p>
        <p></p>
        <p><input class="form-control" id="date_start_com" required="" type="text"  name="date_start_com" value="<?=@$get->date_start_com==null?'':date('d-m-Y', strtotime(str_replace('-', "/", @$get->date_start_com)))?>"></p>
      </div>
      <div class="edit_conts">
        <p>締切日 <request></request></p>
        <p></p>
        <p><input class="form-control" id="date_end_com" required="" type="text" name="date_end_com" value="<?=@$get->date_end_com==null?'':date('d-m-Y', strtotime(str_replace('-', "/", @$get->date_end_com)))?>">
        </p>
      </div>

      <div class="edit_conts">
        <p>当選画像選定期間 <request></request></p>
        <p></p>
        <p>
            <select class="form-control" name="competition_time_agree_id">
              <?php foreach($competition_time_agree_id as $value) { ?>
              <option value="<?=$value->id?>" <?=$value->id==@$get->competition_time_agree_id?"selected=''":''?>><?=$value->name?> <?=$value->type==1?'days':''?><?=$value->type==2?'week':''?><?=$value->type==3?'month':''?></option>
              <?php } ?>
            </select>
        </p>
      </div>

      <div class="edit_conts">
        <p>非公開コンペ</p>
        <p></p>
          <div class="checkbox-custom mb5">
                <input <?=$get->is_private==1?"checked=''":''?> id="is_private" type="checkbox" name="is_private"  value="1">
                <label for="is_private">プライベート</label>
            </div>
      </div>

      <div class="edit_conts">
        <p>応募条件 <request></request></p>
        <p style="width: 500px;"><textarea required="" class="form-control" name="note_require_com"><?=@$get->note_require_com ?></textarea></p>
      </div>

      <div class="edit_conts">
        <p>募集要項 <request></request></p>
        <p style="width: 500px;"><textarea required=""  class="form-control" name="note_description_com"><?=@$get->note_description_com ?></textarea></p>
      </div>

      <div class="edit_conts">
        <p>画像の向き <request></request></p>
        <p></p>
        <p>
            <select class="form-control" name="img_type_com">
              <option value="0">いいえ</option>
              <option value="1" <?=@$get->img_type_com==1?"selected=''":''?>>横向き</option>
              <option value="2" <?=@$get->img_type_com==2?"selected=''":''?>>縦向き</option>
            </select>
        </p>
      </div>

      <div class="edit_conts note_bg note_color_bg">
        <p>カラー <request></request></p>
        <div style="width: 500px;">
            <?php foreach($color as $key => $value) { ?>
            <?php
              $ex = explode(',', @$get->img_color_id);
            ?>
            <div class="col-lg-6">
            <div class="checkbox-custom mb5">
                <input value="<?=$value->id_color?>" <?php if($value->id_color==13) { ?> class="item_check_all" onchange="uncheck_all()" <?php }else{ ?> onchange="check_all()" class="item_uncheck_all" <?php } ?><?=in_array($value->id_color,$ex) ?"checked=''":''?> type="checkbox" id="color_<?=$key?>" name="img_color_id[]">

                <label for="color_<?=$key?>"><span style="color:#fff; margin-right: 10px; width: 40px;padding: 1px 3px; height: 20px;     border: 1px solid #08080885; background: <?=$value->css_color_style;?>; color: <?=$value->css_color_style;?>">Color</span><?=$value->name_color?></label>
            </div>
          </div>
            <?php } ?>  

            
          </div>
      </div>
      <label class="note note_color"></label>

      <div class="edit_conts">
        <p>写真の利用用途 <request></request></p>
        <p style="width: 500px;"><textarea  required="" class="form-control" name="note_img_purpose_com"><?=@$get->note_img_purpose_com ?></textarea></p>
      </div>
      
      
      <div class="note_bg note_point_bg edit_conts" ng-init="Point=<?=@$get->point_img_com==''?0:$get->point_img_com?>;Quantity=<?=@$get->img_quantity_com==''?0:$get->img_quantity_com?>">
        <p>当選者への付与LUX <request></request></p>
        <p></p>
        <p><input onkeypress='return event.charCode >= 48 && event.charCode <= 57' <?=$type == 'edit'?'readonly=""':'' ?> required="" min="1" max="1000000" class="form-control" ng-model="Point" type="number" name="point_img_com" value=""></p>
      </div>
      <div class="note_bg note_point_bg edit_conts">
        <p>当選者総数 <request></request></p>
        <p></p>
        <p><input onkeypress='return event.charCode >= 48 && event.charCode <= 57' <?=$type == 'edit'?'readonly=""':'' ?> required="" min="1" max="1000000" class="form-control" ng-model="Quantity" type="number" name="img_quantity_com" value=""></p>
      </div>
      <div class="note_bg note_point_bg edit_conts">
        <p>合計LUX</p>
        <p></p>
        <p class="fwb">{{Point*Quantity}}</p>
      </div>
      <label class="note note_point"></label>


      <div class="edit_conts">
        <p>応募者情報</p>
        <p></p>
        <p><input pattern="(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?" class="form-control" type="text" name="link_url_com" value="<?=@$get->link_url_com ?>"></p>
      </div>

      <div class="edit_conts">
        <p>注意事項</p>
        <p style="width: 500px;"><textarea class="form-control" name="note_another_des_com"><?=@$get->note_another_des_com?></textarea>
        </p>
      </div>
      
      
      
      <div class="edit_button">
        <p></p>
        <p><button class="btn btn-primary"><?=$type=='edit'?'更新':'作成する'?></button></p>
      </div>
  </table>
</form>


</article>
</section>