<form action="admin/add_edit_lang" method="post" onsubmit="return submitAjax(this)">
  <input type="hidden" name="path" value="<?=@$path?>">
  <label>Code lang</label>
  <textarea class="form-control" name="code" rows="20"><?=$code?></textarea>
  <br>
  <button class="btn btn-primary right">Update</button>
</form>