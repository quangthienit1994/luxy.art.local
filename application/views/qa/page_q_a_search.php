<?php $this->load->view('qa/layouts/header.php');?>

<?php if(count($list)==0) { ?>
	Not found !
<?php }else{ ?>
<div class="qa-list">
  <ul>
    <?php foreach ($list as $key => $value) {?>
    <li><a href="<?= base_url('qa/'.$value->group_id)?>.html"><?=$value->title?></a></li>
    <?php } ?>
  </ul>
</div>
<?php } ?>

<?php if($count_page>1) { ?>
<div style="display: flex; justify-content: center;">
	<ul class="pagination">
		<?php for ($i = 1; $i <= $count_page; $i++) { ?>
		  	<li <?=$i==$current_page?"class='active'":''?>><a href="?keyword=<?=$_GET['keyword']?>&page=<?=$i?>"><?=$i?></a></li>
		  <?php } ?>
	</ul>
</div>
<?php } ?>

<?php $this->load->view('qa/layouts/footer.php');?>