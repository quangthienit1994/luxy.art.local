<section class="content_box">
  <h3><?=$type == 'edit'?'編集':'作成する' ?></h3>
  <article class="member_edit">
    <h4><?=$type == 'edit'?'編集':'作成する' ?></h4>


    <form action="admin/add_edit_package" method="post" onsubmit="return submitAjax(this,{'load':2000,'redirect':'admin/page_list_package'})">
  <input type="hidden" name="id" value="<?=@$get->id_list?>">
  <table class="table table-hover bg-white">
      <div class="edit_conts">
        <p>パッケージ名 <request></request></p>
        <p></p>
        <p><input class="form-control" required="" type="text" name="name_credit" value="<?=@$get->name_credit?>"></p>
      </div>
      <div class="edit_conts">
        <p>LUX <request></request></p>
        <p></p>
        <p><input onkeyup="calPoint(this)" id="point_credit" min="1" class="form-control" required="" type="number" name="point_credit" value="<?=@$get->point_credit?>"></p>
      </div>
      <div class="edit_conts">
        <p>価格 <request></request></p>
        <p></p>
        <p><input onkeyup="calPoint(this)" id="money_pay_credit" min="1" class="form-control" required="" type="number" name="money_pay_credit" value="<?=@$get->money_pay_credit?>"></p>
      </div>
      <div class="edit_conts">
        <p>1LUX単価 <request></request></p>
        <p></p>
        <p><input id="value_point_money" class="form-control" required="" type="text" name="value_point_money" value="<?=@$get->value_point_money?>"></p>
      </div>
      <div class="edit_button">
        <p></p>
        <p><button class="btn btn-primary"><?=$type=='edit'?'更新':'作成する'?></button></p>
      </div>
  </table>
</form>

<script type="text/javascript">
  function calPoint(e) {
    var point_credit = $('#point_credit').val(); 
    var money_pay_credit = $('#money_pay_credit').val(); 
    var cal = Number((money_pay_credit/point_credit).toFixed(2));
    $('#value_point_money').val(cal);
  }
</script>

</article>
</section>