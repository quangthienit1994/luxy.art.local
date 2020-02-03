<section class="content_box">
  <h3><?=$type == 'edit'?'編集':'作成する' ?></h3>
  <article class="member_edit">
    <h4><?=$type == 'edit'?'会員情報編集':'作成する' ?> 情報</h4>



    <div class="panel panel-default">
      <div class="panel-heading">
          <ul class="nav panel-tabs-border panel-tabs panel-tabs-left">
            <li class="active">
                <a href=".tab-1" data-toggle="tab" aria-expanded="true">
                基本情報</a>
            </li>
            <?php if($type == 'edit') { ?>
            <li>
                <a href=".tab-2" data-toggle="tab" aria-expanded="true">
                銀行口座情報</a>
            </li>
            <?php } ?>
          </ul>   
      </div>
      <div class="panel-body"> 
                            
        <div class="tab-content tab-hidden pn br-n">
            <div class="tab-pane  tab-1 active">
              

              
      <form enctype="multipart/form-data" action="admin/add_edit_user" method="post" onsubmit="return submitAjax(this,{'load':2000})">         
      <input type="hidden" name="id" value="<?=@$get->user_id?>">

          <?php if($type == 'edit') { ?>
          <div class="edit_conts">
            <p>登録日</p>
            <p></p>
            <p><?=@$get->user_dateregister?></p>
          </div>
          <?php } ?>

          <div class="edit_conts">
            <p>名 <request></request></p>
            <p></p>
            <p><input  required="" type="text" name="user_firstname" value="<?=@$get->user_firstname?>"></p>
          </div>
          <div class="edit_conts">
            <p>氏 <request></request></p>
            <p></p>
            <p><input   required="" type="text" name="user_lastname" value="<?=@$get->user_lastname?>"></p>
          </div>

          <div class="edit_conts note_bg note_email_bg">
            <p>Email <request></request></p>
            <p></p>
            <p><input required="" type="email" name="user_email" value="<?=@$get->user_email?>"></p>
          </div>
          <label class="note note_email"></label>

          <div class="edit_conts">
            <p>パスワード <?=$type=='edit'?'<br/>(空の場合は変更されません)':'<request></request>'?></p>
            <p></p>
            <p><input <?=$type!='edit'?'required=""':''?>  type="text" name="user_pass"></p>
          </div>
          <div class="edit_conts">
            <p>市区町村 <request></request></p>
            <p></p>
            <p><input  required="" type="text" name="cityname" value="<?=@$get->cityname?>"></p>
          </div>
          <div class="edit_conts">
            <p>郵便番号 <request></request></p>
            <p></p>
            <p><input  required="" type="text" name="user_zipcode" value="<?=@$get->user_zipcode?>"></p>
          </div>
          <div class="edit_conts">
            <p>User level<request></request></p>
            <p></p>
            <p class="edit_ch">
                <select  name="user_level">
                  <option <?=@$get->user_level==1?"selected=''":''?> value='1'>一般ユーザー</option>
                  <option <?=@$get->user_level==2?"selected=''":''?> value='2'>クリエイター</option>
                  <option <?=@$get->user_level==3?"selected=''":''?> value='3'>プラチナ</option>
                  <option <?=@$get->user_level==4?"selected=''":''?> value='4'>ダイヤモンド</option>
                  <option <?=@$get->user_level==5?"selected=''":''?> value='5'>法人</option>
                </select>
            </p>
          </div>
          <div class="edit_conts">
            <p>国 <request></request></p>
            <p></p>
            <p class="edit_ch">
                <select  name="country_id" id="country_id" data-state='<?=@$get->state_id?>'>
                  <?php foreach($country as $value) { ?>
                  <option value="<?=$value->country_id?>" <?=$value->country_id==@$get->country_id?"selected=''":''?>><?=$value->country_name?></option>
                  <?php } ?>
                </select>
            </p>
          </div>
          <div class="edit_conts">
            <p>都道府県 <request></request></p>
            <p></p>
            <p class="edit_ch">
                <select  name="state_id" id="state_id">
                </select>
            </p>
          </div>
          <div class="edit_conts">
            <p>住所（番地まで） <request></request></p>
            <p></p>
            <p>
              <input type="text" name="user_address" value="<?=@$get->user_address?>">
            </p>
          </div>
          <div class="edit_conts">
            <p>建物名</p>
            <p></p>
            <p>
              <input type="text" name="user_address_building" value="<?=@$get->user_address_building?>">
            </p>
          </div>
          <div class="edit_conts">
            <p>紹介文 <request></request></p>
            <p></p>
            <p>
              <textarea style="width: 320px;" class="form-control" name="user_introduction"><?=@$get->user_introduction?></textarea>
            </p>
          </div>
          <div class="edit_conts note_bg note_avatar_bg">
            <p>プロフィール画像 <?=$type == 'edit'?'':'<request></request>' ?></p>
            <p></p>
            <p><img src="publics/avatar/<?=@$get->user_avatar?>" onerror="this.src='publics/avatar/default_avatar.png'" style="width: 50px; height: 50px; border-radius: 100%; float: left;"><input <?=$type == 'edit'?'':'required=""' ?> style="margin-left: 80px;" type="file" name="avatar" >
            </p>
          </div>
          <label class="note note_avatar"></label>

          <div class="edit_conts note_bg note_banner_bg">
            <p>背景画像 <?=$type == 'edit'?'':'<request></request>' ?></p>
            <p></p>
            <p><img src="publics/banner/<?=@$get->user_banner?>" onerror="this.src='publics/avatar/default_avatar.png'" style="width: 50px; height: 50px; border-radius: 100%; float: left;"><input <?=$type == 'edit'?'':'required=""' ?> style="margin-left: 80px;" type="file" name="banner" >
            </p>
          </div>
          <label class="note note_banner"></label>

          <div class="edit_conts">
            <div class="checkbox-custom mb5">
                  <input <?=@$get->notice_luxyart == 1?'checked=""':'' ?> id="notice_luxyart" type="checkbox" name="notice_luxyart" value="1">
                  <label for="notice_luxyart">ニュースレター、特典情報およびその他の役立つ情報の配信を希望します。</label>
              </div>
          </div> 

          <div class="edit_button">
            <input type="submit" value="<?=$type=='edit'?'更新':'作成する'?>">
          </div>

          </form>
            </div>


            <div class="tab-pane  tab-2">
              <form enctype="multipart/form-data" action="admin/add_edit_user" method="post" onsubmit="return submitAjax(this,{'load':2000})">         
                <input type="hidden" name="id" value="<?=@$get->user_id?>">
                <input type="hidden" name="type" value="bank">
              <div class="edit_conts">
                <p>銀行名 <request></request></p>
                <p></p>
                <p>
                  <input required="" type="text" name="bank_name" value="<?=@$get->bank_name?>">
                </p>
              </div>

              <div class="edit_conts">
                <p>支店名 <request></request></p>
                <p></p>
                <p>
                  <input required="" type="text" name="bank_branch_name" value="<?=@$get->bank_branch_name?>">
                </p>
              </div>

              <div class="edit_conts">
                <p>口座番号 <request></request></p>
                <p></p>
                <p>
                  <input required="" type="text" name="bank_account" value="<?=@$get->bank_account?>">
                </p>
              </div>

              <div class="edit_conts">
                <p>口座名義 <request></request></p>
                <p></p>
                <p>
                  <input required="" type="text" name="bank_account_name" value="<?=@$get->bank_account_name?>">
                </p>
              </div> 

              <div class="edit_conts">
                <p>口座種別 <request></request></p>  
                <p></p>
                <p class="edit_ch">
                    <select  name="bank_type_account">
                      <option value="1" <?=@$get->bank_type_account==1?"selected=''":''?>>普通</option>
                      <option value="2" <?=@$get->bank_type_account==2?"selected=''":''?>>当座</option>
                    </select>
                </p>
              </div> 


              <div class="edit_button">
                <input type="submit" value="<?=$type=='edit'?'更新':'作成する'?>">
              </div>
              </form>
            </div>
        </div>  

      </div>
    </div>
  </article>
</section>

