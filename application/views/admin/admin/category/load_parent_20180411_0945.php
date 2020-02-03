	<option value="0">Parent</option>
<?php foreach ($list as $key => $value) { ?>
	<option <?=$value->cate_id==$parent_cate?"selected=''":""?> <?=$value->cate_id==$get->parent_cate?"selected=''":''?> value="<?=$value->cate_id?>"><?=$value->cate_name?></option>
<?php } ?>