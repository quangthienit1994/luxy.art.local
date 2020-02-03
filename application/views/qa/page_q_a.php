<?php $this->load->view('qa/layouts/header.php');?>

<?php foreach ($this->db->get_where('luxyart_tb_qa_category',['lang_id'=>$this->lang_id,'status'=>1])->result() as $key_c => $value_c) { ?>
<h2><?=$value_c->title?></h2>
<div class="qa-list">
  <?php
      $more = false;
      $this->db->order_by('qa_id','desc');
      $list_qa = $this->db->get_where('luxyart_tb_qa',['lang_id'=>$this->lang_id,'category_id'=>$value_c->group_id,'status'=>1])->result();
    ?>
  <?php if(count($list_qa)==0){?>
  Hiện tại chưa có câu hỏi nào !
  <?php } ?>
  <ul>

    <?php foreach ($list_qa as $key => $value) {?>
    <li><a href="<?= base_url('qa/'.$value->group_id)?>.html"><?=$value->title?></a></li>
    <?php if($key==1){$more = true;break;} ?>
    <?php } ?>
  </ul>
  <?php if($more==true) { ?>
  <p class="qa-list__more"><a href="<?= base_url('qa-category/'.$value_c->group_id)?>.html">会員登録についての質問をもっと見る>></a></p>
  <?php  } ?>
</div>
<?php } ?>

<?php $this->load->view('qa/layouts/footer.php');?>