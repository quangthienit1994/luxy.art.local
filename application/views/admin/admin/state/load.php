<?php foreach ($list as $key => $value): ?>
	<option <?=$value->state_id==$state_id?"selected=''":""?> value="<?=$value->state_id?>"><?=$value->state_name?></option>
<?php endforeach ?>