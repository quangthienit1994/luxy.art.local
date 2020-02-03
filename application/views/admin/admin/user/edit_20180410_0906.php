<?php
$CI =& get_instance();
$CI->config->load();
$this->lang->load('dich', 'ja');
?>

<section class="content_box">
  <h3><?=$type == 'edit' ? $this->lang->line('edit') : $this->lang->line('create') ?></h3>
  <article class="member_edit">
    <h4><?=$type == 'edit'? $this->lang->line('edit_member_information') : $this->lang->line('create') ?> <?php echo $this->lang->line('information'); ?></h4>



    <div class="panel panel-default">
      <div class="panel-heading">
          <ul class="nav panel-tabs-border panel-tabs panel-tabs-left">
            <li class="active">
                <a href=".tab-1" data-toggle="tab" aria-expanded="true">
                <?php echo $this->lang->line('basic_information'); ?></a>
            </li>
            <?php if($type == 'edit') { ?>
            <li>
                <a href=".tab-2" data-toggle="tab" aria-expanded="true">
                <?php echo $this->lang->line('bank_account_information'); ?></a>
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
            <p><?php echo $this->lang->line('registration_date'); ?></p>
            <p></p>
            <p><?=@$get->user_dateregister?></p>
          </div>
          <?php } ?>

          <div class="edit_conts">
            <p><?php echo $this->lang->line('name'); ?> <request></request></p>
            <p></p>
            <p><input  required="" type="text" name="user_firstname" value="<?=@$get->user_firstname?>"></p>
          </div>
          <div class="edit_conts">
            <p><?php echo $this->lang->line('family_name'); ?> <request></request></p>
            <p></p>
            <p><input   required="" type="text" name="user_lastname" value="<?=@$get->user_lastname?>"></p>
          </div>

          <div class="edit_conts note_bg note_email_bg">
            <p><?php echo $this->lang->line('email'); ?> <request></request></p>
            <p></p>
            <p><input required="" type="email" name="user_email" value="<?=@$get->user_email?>"></p>
          </div>
          <label class="note note_email"></label>

          <div class="edit_conts">
            <p><?php echo $this->lang->line('password'); ?> <?=$type=='edit'?'<br/>('.$this->lang->line('if_it_is_empty_it_will_not_be_changed').')':'<request></request>'?></p>
            <p></p>
            <p><input <?=$type!='edit'?'required=""':''?>  type="text" name="user_pass"></p>
          </div>
          <div class="edit_conts">
            <p><?php echo $this->lang->line('city'); ?> <request></request></p>
            <p></p>
            <p><input  required="" type="text" name="cityname" value="<?=@$get->cityname?>"></p>
          </div>
          <div class="edit_conts">
            <p><?php echo $this->lang->line('zip_code'); ?> <request></request></p>
            <p></p>
            <p><input  required="" type="text" name="user_zipcode" value="<?=@$get->user_zipcode?>"></p>
          </div>
          <div class="edit_conts">
            <p><?php echo $this->lang->line('user_level'); ?><request></request></p>
            <p></p>
            <p class="edit_ch">
                <select  name="user_level">
                  <option <?=@$get->user_level==1?"selected=''":''?> value='1'><?php echo $this->lang->line('general_user'); ?></option>
                  <option <?=@$get->user_level==2?"selected=''":''?> value='2'><?php echo $this->lang->line('creator'); ?></option>
                  <option <?=@$get->user_level==3?"selected=''":''?> value='3'><?php echo $this->lang->line('platinum'); ?></option>
                  <option <?=@$get->user_level==4?"selected=''":''?> value='4'><?php echo $this->lang->line('diamond'); ?></option>
                  <option <?=@$get->user_level==5?"selected=''":''?> value='5'><?php echo $this->lang->line('corporate'); ?></option>
                </select>
            </p>
          </div>
          <div class="edit_conts">
            <p><?php echo $this->lang->line('coutry'); ?> <request></request></p>
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
            <p><?php echo $this->lang->line('state'); ?> <request></request></p>
            <p></p>
            <p class="edit_ch">
                <select  name="state_id" id="state_id">
                </select>
            </p>
          </div>
          <div class="edit_conts">
            <p><?php echo $this->lang->line('address'); ?> <request></request></p>
            <p></p>
            <p>
              <input type="text" name="user_address" value="<?=@$get->user_address?>">
            </p>
          </div>
          <div class="edit_conts">
            <p><?php echo $this->lang->line('address_building'); ?></p>
            <p></p>
            <p>
              <input type="text" name="user_address_building" value="<?=@$get->user_address_building?>">
            </p>
          </div>
          <div class="edit_conts">
            <p><?php echo $this->lang->line('introduce'); ?> <request></request></p>
            <p></p>
            <p>
              <textarea style="width: 320px;" class="form-control" name="user_introduction"><?=@$get->user_introduction?></textarea>
            </p>
          </div>
          <div class="edit_conts note_bg note_avatar_bg">
            <p><?php echo $this->lang->line('avatar'); ?> <?=$type == 'edit'?'':'<request></request>' ?></p>
            <p></p>
            <p><img src="publics/avatar/<?=@$get->user_avatar?>" onerror="this.src='publics/avatar/default_avatar.png'" style="width: 50px; height: 50px; border-radius: 100%; float: left;"><input <?=$type == 'edit'?'':'required=""' ?> style="margin-left: 80px;" type="file" name="avatar" >
            </p>
          </div>
          <label class="note note_avatar"></label>

          <div class="edit_conts note_bg note_banner_bg">
            <p><?php echo $this->lang->line('banner'); ?> <?=$type == 'edit'?'':'<request></request>' ?></p>
            <p></p>
            <p><img src="publics/banner/<?=@$get->user_banner?>" onerror="this.src='publics/avatar/default_avatar.png'" style="width: 50px; height: 50px; border-radius: 100%; float: left;"><input <?=$type == 'edit'?'':'required=""' ?> style="margin-left: 80px;" type="file" name="banner" >
            </p>
          </div>
          <label class="note note_banner"></label>

          <div class="edit_conts">
            <div class="checkbox-custom mb5">
                  <input <?=@$get->notice_luxyart == 1?'checked=""':'' ?> id="notice_luxyart" type="checkbox" name="notice_luxyart" value="1">
                  <label for="notice_luxyart"><?php echo $this->lang->line('notice_register_member'); ?></label>
              </div>
          </div> 

          <div class="edit_button">
            <input type="submit" value="<?=$type=='edit'? $this->lang->line('update') : $this->lang->line('create') ?>">
          </div>

          </form>
            </div>


            <div class="tab-pane  tab-2">
              <form enctype="multipart/form-data" action="admin/add_edit_user" method="post" onsubmit="return submitAjax(this,{'load':2000})">         
                <input type="hidden" name="id" value="<?=@$get->user_id?>">
                <input type="hidden" name="type" value="bank">
              <div class="edit_conts">
                <p><?php echo $this->lang->line('bank_name'); ?> <request></request></p>
                <p></p>
                <p>
                  <input required="" type="text" name="bank_name" value="<?=@$get->bank_name?>">
                </p>
              </div>

              <div class="edit_conts">
                <p><?php echo $this->lang->line('branch_name'); ?> <request></request></p>
                <p></p>
                <p>
                  <input required="" type="text" name="bank_branch_name" value="<?=@$get->bank_branch_name?>">
                </p>
              </div>

              <div class="edit_conts">
                <p><?php echo $this->lang->line('bank_account_name'); ?> <request></request></p>
                <p></p>
                <p>
                  <input required="" type="text" name="bank_account" value="<?=@$get->bank_account?>">
                </p>
              </div>

              <div class="edit_conts">
                <p><?php echo $this->lang->line('account_number'); ?> <request></request></p>
                <p></p>
                <p>
                  <input required="" type="text" name="bank_account_name" value="<?=@$get->bank_account_name?>">
                </p>
              </div> 

              <div class="edit_conts">
                <p><?php echo $this->lang->line('account_type'); ?> <request></request></p>  
                <p></p>
                <p class="edit_ch">
                    <select  name="bank_type_account">
                      <option value="1" <?=@$get->bank_type_account==1?"selected=''":''?>>account_type_person</option>
                      <option value="2" <?=@$get->bank_type_account==2?"selected=''":''?>>account_type_company</option>
                    </select>
                </p>
              </div> 


              <div class="edit_button">
                <input type="submit" value="<?=$type=='edit'? $this->lang->line('update') : $this->lang->line('create') ?>">
              </div>
              </form>
            </div>
        </div>  

      </div>
    </div>
  </article>
</section>

