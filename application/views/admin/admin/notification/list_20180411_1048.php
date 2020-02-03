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
  <h3><?=$type=='edit'? $this->lang->line('update') : $this->lang->line('registration') ?></h3>
    <form action="admin/add_edit_notification" method="post" onsubmit="return submitAjax(this,{'load':2000,'redirect':'admin/page_list_notification'})">
      <?php
      $lang = [
        ['key'=>1,'slug'=>'ja','name'=>$this->lang->line('japan')],
        ['key'=>2,'slug'=>'vi','name'=>$this->lang->line('vietnam')],
        ['key'=>3,'slug'=>'en','name'=>$this->lang->line('english')]
      ];
      ?>
      <div class="info_area">
        <div class="panel panel-default panel-add-menu-item">
          <div class="panel-heading">
              <ul class="nav panel-tabs-border panel-tabs panel-tabs-left">
                <?php foreach ($lang as $key => $value) { ?>
                <li class="<?=$value['key']==$get->lang_id?'active':''?> <?=($key==0&&$get->lang_id=='')?'active':''?>">
                      <a href=".tab-<?=$value['slug']?>" data-toggle="tab" aria-expanded="true">
                      <?=$value['name']?></a>
                </li>
                <?php } ?>
              </ul>   
          </div>
          <div class="panel-body">                                
            <div class="tab-content tab-hidden pn br-n">
            <?php foreach ($lang as $key => $value) { ?>
              <div class="tab-pane  tab-<?=$value['slug']?> <?=$value['key']==$get->lang_id?'active':''?> <?=($key==0&&$get->lang_id=='')?'active':''?>">
                <?php
                  $this->db->flush_cache();
                  $this->db->where('group_id', $get->group_id);
                  $this->db->where('lang_id', $value['key']);
                  $item = $this->db->get('luxyart_tb_notification')->row();
                ?>
                <input type="hidden" value="<?=@$get->group_id?>" name="group_id">
                <input type="hidden" value="<?=@$item->note_id?>" name="note_id[<?=$value['slug']?>]">
                <input type="hidden" value="<?=@$value['key']?>" name="lang_id[<?=$value['slug']?>]">



                <p class="fwb"><?php echo $this->lang->line('notice_title'); ?> (<?=$value['slug']?>) <request></request></p>
                  <p><input required="" value="<?=@$item->title?>" name="title[<?=$value['slug']?>]" style="width: 96%;" type="text" placeholder="<?php echo $this->lang->line('please_enter_an_announcement_title'); ?>"></p>
                <p style="margin-top: 20px; margin-bottom: 5px;"><?php echo $this->lang->line('notice_content'); ?> (<?=$value['slug']?>)</p>
                  <p><textarea class="ckeditor" name="content[<?=$value['slug']?>]" style="width: 100%;" placeholder="<?php echo $this->lang->line('please_enter_notice_content'); ?>"><?=@$item->content?></textarea></p>
                            
              </div>   
            <?php } ?>  
            <p><?php echo $this->lang->line('release_date_time'); ?></p>
                  <p>
                    <select name="year">
                      <?php for($i=2017; $i<=2020; $i++){ ?>
                      <option <?php if($type!='edit') {?> <?=date('Y')==$i?"selected=''":''?><?php }else{ ?> <?=date('Y', strtotime($item->date))==$i?"selected=''":''?><?php } ?> value="<?=$i?>"><?=$i?></option>
                      <?php } ?>
                    </select>&nbsp;<?php echo $this->lang->line('_year'); ?>&nbsp;
                    <select name="month">
                      <?php for($i=1; $i<=12; $i++){ ?>
                      <option <?php if($type!='edit') {?> <?=date('m')==$i?"selected=''":''?><?php }else{ ?> <?=date('m', strtotime($item->date))==$i?"selected=''":''?><?php } ?> value="<?=$i?>"><?=$i?></option>
                      <?php } ?>
                    </select>&nbsp;<?php echo $this->lang->line('_month'); ?>&nbsp;
                    <select name="day">
                      <?php for($i=1; $i<=31; $i++){ ?>
                      <option <?php if($type!='edit') {?> <?=date('d')==$i?"selected=''":''?><?php }else{ ?> <?=date('d', strtotime($item->date))==$i?"selected=''":''?><?php } ?> value="<?=$i?>"><?=$i?></option>
                      <?php } ?>
                    </select>&nbsp;<?php echo $this->lang->line('_day'); ?>&nbsp;<?php echo $this->lang->line('release'); ?>
                </p>
                <div class="info_button">
                  <input type="submit" value="<?=$type=='edit'? $this->lang->line('update') : $this->lang->line('registration') ?>">
                </div>                            
            </div>
          </div>
        </div>
      </div>

    </form>
</section>

<?php if(@$type!='edit') { ?>
<section class="content_box">
  <h3><?php echo $this->lang->line('notice_list'); ?></h3>
  <article class="info_tablde">
    <table class="table table-hover bg-white">
      <thead>
        <tr>
          <th><?php echo $this->lang->line('release_date_time'); ?></th>
          <th><?php echo $this->lang->line('title'); ?></th>
          <th><?php echo $this->lang->line('information_contents'); ?></th>
          <th style="width: 170px;"><?php echo $this->lang->line('control'); ?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($list as $key => $value) { ?>
        <tr>
          <td><?=date('Y年m月d日',strtotime($value->date))?></td>
          <td><?=$value->title?></td>
          <td><?=$value->content?></td>
          <td>
              <a class="btn left" href="admin/page_add_edit_notification/<?=$value->group_id?>"><?php echo $this->lang->line('edit'); ?></a>
              <a class="iframe btn left" href="admin/info_check_notification/<?=$value->note_id?>"><?php echo $this->lang->line('confirmation'); ?></a>
              <form class="left" action="admin/delete_notification/<?=$value->group_id?>" method="post" onsubmit="return submitAjax(this,{'load':1000,'confirm':true})">
                    <button class="btn btn-danger"><i class="fa fa-times"></i></button>
              </form>

          </td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
  </article>
</section>
<?php } ?>