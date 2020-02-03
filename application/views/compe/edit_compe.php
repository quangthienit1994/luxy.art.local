<?php

if(!isset($_SESSION)){

	session_start();

}

$CI =& get_instance();

$CI->config->load();

if(!isset($_SESSION['lang'])){

	$_SESSION['lang'] = 'ja';

}

if($_SESSION['lang'] == "ja"){

	$this->lang_id = 1;

}

else if($_SESSION['lang'] == "vi"){

	$this->lang_id = 2;

}

else if($_SESSION['lang'] == "en"){

	$this->lang_id = 3;

}

$config['language'] = $_SESSION['lang'];

$ngonngu = $config['language'];

$this->lang->load('dich', $ngonngu);

?>



<link rel="stylesheet" href="<?php echo base_url(); ?>publics/calendar/jquery-ui-1.12.1.css">

<script src="<?php echo base_url(); ?>publics/calendar/jquery-1.12.4.js"></script>

<script src="<?php echo base_url(); ?>publics/calendar/jquery-ui-1.12.1.js"></script>



<script src="<?php echo base_url(); ?>publics/js/auto-formatting-currency.js" type="text/javascript" charset="utf-8"></script>

<script>
	text_none_selected = "<?php $this->lang->line('none_selected') ?>";
	text_select_all = "<?php $this->lang->line('select_all') ?>";
	text_search = "<?php $this->lang->line('search') ?>";
</script>

<script>



var id_color = Array();

var user_point = "<?php echo $user_point; ?>";

var min_width_img_competition = "<?php echo $min_width_img_competition; ?>";

var min_height_img_competition = "<?php echo $min_height_img_competition; ?>";

var point_convert_money = "<?php echo $point_convert_money; ?>";



//check error when (upload image > 5 MB) && (width || height < 200)

var _URL = window.URL || window.webkitURL;



var msg_image = "";

var is_error_upload_image = 0;

var user_id = "<?php echo $user_id; ?>";

$(document).ready(function() {

	

	id_color = Array();

    

	$('.ul-box-color li input[type=checkbox]').each(function() {

	   if($(this).is(":checked")) {

		   id_color.push($(this).attr('value'));

		   $("#id_color").val(id_color);

	   }

	});

	$("input[id*='value-color-']").click(function(){
		
		if($("#"+this.id).is(":checked")) {
			if(this.id == "value-color-13"){
				$("input[id*='value-color-']").prop('checked',false);
				$("#value-color-13").prop('checked',true);	
			}
			else{
				$("#value-color-13").prop('checked',false);	
			}
		}
		else{
			if(this.id != "value-color-13"){
				$("#value-color-13").prop('checked',false);	
			}
		}
		
	});
	
	//is_private_img_apply
	$("#is_private_img_apply").click(function(){
		
		if($("#is_private_img_apply").is(":checked")) {
			
			$.ajax({
				type: "POST",cache: false,
				url: "<?php echo base_url(); ?>ajax/check_private_post_competition",
				data: "user_id="+user_id,
				success: function(data) {
					if(data == 0){
						var result = confirm("<?php echo $this->lang->line('please_update_account_enterprise_for_show img_apply_post_competition'); ?>");
						if(result){
							window.open('<?php echo base_url(); ?>/mypage/user-update-account.html','_blank');
						}
						$('#is_private_img_apply').prop('checked', false); 
					}
				},
				async: false
			})
			
		}
		
	});

	//color

	$(".ul-box-color li").click(function(){

		

		id_color = Array();

    

		$('.ul-box-color li input[type=checkbox]').each(function() {

		   if($(this).is(":checked")) {

			   id_color.push($(this).attr('value'));

			   $("#id_color").val(id_color);

		   }

		});

		

	});

	

	$("#point_img_com").keyup(function() {

		set_total_com();

	});

	

	$("#img_quantity_com").keyup(function() {

		set_total_com();

	});

	

	//image

	$('#photo_des_com').bind('change', function () {

	  //var file = e.target.files[0];

	  var file = this.files[0];

	  

	  checkExtension("photo_des_com");

	  displayPreview(file);

	  

	});

	

	//submit

	$("#btnEditCompe").click(function(){

		return validateEditCompe();

	});

	

});



function set_total_com(){

	

	point_img_com = $("#point_img_com").val();

	img_quantity_com = $("#img_quantity_com").val();

	

	point_img_com = point_img_com.replace(/,/g, "");

	img_quantity_com = img_quantity_com.replace(/,/g, "");

	

	total_com = parseFloat(point_img_com) * parseFloat(img_quantity_com);

	$("#total_com").text(Math.round(total_com));

	

	if(isNaN(total_com)){

		$("#total_com").text(0);
		$("#total_com_yen").text(0);

		total_com = 0;

	}

	else{

		$("#total_com").text(numberWithCommas(Math.round(total_com)));
		$("#total_com_yen").text(numberWithCommas(Math.round(point_convert_money*total_com)));

	}

	

	return total_com;

	

}



function validateEditCompe(){

		

	var msg = "";

	var flag = 1;

	var is_focus = 1;

	

	//title_com	

	title_com = $("#title_com").val().trim();

	if(title_com == ""){

		msg="<?php echo $this->lang->line('please_input_title_competition'); ?>";

		show_msg("title_com",msg,1);

		flag=0;

		//check focus

		if(is_focus == 1){

			set_focus("title_com");

			is_focus = 0;

		}

	}

	else{

		msg="";

		show_msg("title_com",msg,0);

	}

	

	//photo_des_com

	var allowed_image_formats="png,gif,bmp,jpg,jpeg";	

	var photo=$("#photo_des_com").val();

	var ph=photo.lastIndexOf('.');

	var ext=photo.substring(ph,photo.length);

	ext=ext.toLowerCase();

	ext=ext.replace(".","");

	var allowd_formats=allowed_image_formats.indexOf(ext);

	

	if(ext==""){

		/*msg="<?php //echo $this->lang->line('please_choose_image'); ?>";

		show_msg("photo_des_com",msg,1);

		flag=0;

		//check focus

		if(is_focus == 1){

			set_focus("photo_des_com");

			is_focus = 0;

		}*/

	}

	else{

		//check error when (upload image > 5 MB) && (width || height < 200)

		if(is_error_upload_image == 1){

		

			show_msg("photo_des_com",msg_image,1);

			flag=0;

			//check focus

			if(is_focus == 1){

				set_focus("photo_des_com");

				is_focus = 0;

			}

			

		}

		else{

			

			show_msg("photo_des_com",msg_image,0); 

		}

	}

	

	//icon_com

	var allowed_image_formats="png,gif,bmp,jpg,jpeg,ico";	

	var icon=$("#icon_com").val();

	var ph=icon.lastIndexOf('.');

	var ext=icon.substring(ph,icon.length);

	ext=ext.toLowerCase();

	ext=ext.replace(".","");

	var allowd_formats=allowed_image_formats.indexOf(ext);

	

	if(ext==""){

		/*msg="<?php //echo $this->lang->line('please_choose_icon'); ?>";

		show_msg("icon_com",msg,1);

		flag=0;

		//check focus

		if(is_focus == 1){

			set_focus("icon_com");

			is_focus = 0;

		}*/

	}

	else if(allowd_formats == -1){

		msg="<?php echo $this->lang->line('please_choose_icon_format_true_competition'); ?>";

		show_msg("icon_com",msg,1);

		flag=0;

		//check focus

		if(is_focus == 1){

			set_focus("icon_com");

			is_focus = 0;

		}

	}

	else{

		msg = "";

		show_msg("icon_com",msg,0); 

	}

	

	//date_start_com

	date_start_com = $("#date_start_com").val();

	if(date_start_com == ""){

		msg="<?php echo $this->lang->line('please_choose_date_start'); ?>";

		show_msg("date_start_com",msg,1);

		flag=0;

		//check focus

		if(is_focus == 1){

			set_focus("date_start_com");

			is_focus = 0;

		}

	}

	else{

		msg="";

		show_msg("date_start_com",msg,0);

	}

	

	//date_end_com

	date_end_com = $("#date_end_com").val();

	if(date_end_com == ""){

		msg="<?php echo $this->lang->line('please_choose_date_end'); ?>";

		show_msg("date_end_com",msg,1);

		flag=0;

		//check focus

		if(is_focus == 1){

			set_focus("date_end_com");

			is_focus = 0;

		}

	}

	else{

		msg="";

		show_msg("date_end_com",msg,0);

	}

	

	//note_require_com

	note_require_com = $("#note_require_com").val().trim();

	if(note_require_com == ""){

		msg="<?php echo $this->lang->line('please_input_require'); ?>";

		show_msg("note_require_com",msg,1);

		flag=0;

		//check focus

		if(is_focus == 1){

			set_focus("note_require_com");

			is_focus = 0;

		}

	}

	else{

		msg="";

		show_msg("note_require_com",msg,0);

	}

	

	//note_description_com

	note_description_com = $("#note_description_com").val().trim();

	if(note_description_com == ""){

		msg="<?php echo $this->lang->line('please_input_condition'); ?>";

		show_msg("note_description_com",msg,1);

		flag=0;

		//check focus

		if(is_focus == 1){

			set_focus("note_description_com");

			is_focus = 0;

		}

	}

	else{

		msg="";

		show_msg("note_description_com",msg,0);

	}

	

	//img_type_com

	

	//img_color_id

	if(id_color.length == 0){

		msg="<?php echo $this->lang->line('please_choose_color'); ?>";

		show_msg("img_color_id",msg,1);

		flag=0;

		//check focus

		if(is_focus == 1){

			set_focus("color-1");

			is_focus = 0;

		}

	}

	else{

		msg="";

		show_msg("img_color_id",msg,0);

	}

	

	//note_img_purpose_com

	note_img_purpose_com = $("#note_img_purpose_com").val().trim();

	if(note_img_purpose_com == ""){

		msg="<?php echo $this->lang->line('please_input_content_use_image'); ?>";

		show_msg("note_img_purpose_com",msg,1);

		flag=0;

		//check focus

		if(is_focus == 1){

			set_focus("note_img_purpose_com");

			is_focus = 0;

		}

	}

	else{

		msg="";

		show_msg("note_img_purpose_com",msg,0);

	}

	

	//point_img_com

	point_img_com = $("#point_img_com").val();

	if(point_img_com == ""){

		msg="<?php echo $this->lang->line('please_input_point'); ?>";

		show_msg("point_img_com",msg,1);

		flag=0;

		//check focus

		if(is_focus == 1){

			set_focus("point_img_com");

			is_focus = 0;

		}

	}

	else{

		msg="";

		show_msg("point_img_com",msg,0);

	}

	

	//img_quantity_com

	img_quantity_com = $("#img_quantity_com").val();

	if(img_quantity_com == ""){

		msg="<?php echo $this->lang->line('please_input_quantity'); ?>";

		show_msg("img_quantity_com",msg,1);

		flag=0;

		//check focus

		if(is_focus == 1){

			set_focus("img_quantity_com");

			is_focus = 0;

		}

	}

	else{

		msg="";

		show_msg("img_quantity_com",msg,0);

	}

	

	var total_com = set_total_com();

	

	total_com = parseFloat(total_com);

	user_point = parseFloat(user_point);

	user_paymoney_getpoint = parseFloat(user_paymoney_getpoint);

	

	if(total_com > user_point){

		msg = "<?php echo $this->lang->line('total_price_out_of'); ?>" + " " + (user_point + user_paymoney_getpoint);

		show_msg("img_quantity_com",msg,1);

		flag=0;

		//check focus

		if(is_focus == 1){

			set_focus("img_quantity_com");

			is_focus = 0;

		}

	}

	else{

		msg="";

		show_msg("img_quantity_com",msg,0);

	}

	

	//link_url_com

	link_url_com = $("#link_url_com").val().trim();

	var pattern = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/;

	if(link_url_com != ""){

		if(!pattern.test(link_url_com)){

			msg="<?php echo $this->lang->line('please_input_url_valid'); ?>";

			show_msg("link_url_com",msg,1);

			flag=0;

			//check focus

			if(is_focus == 1){

				set_focus("link_url_com");

				is_focus = 0;

			}

		}

		else{

			msg="";

			show_msg("link_url_com",msg,0);

		}

	}

	

	//note_another_des_com

	

	if(flag == 1){

		

		f = document.getElementById('f');

		if (f) {

			f.submit();

		}

		

		return true;

	}

	else{

		return false;	

	}

	

}



function checkExtension(name){

	

	flag = 1;

  	is_focus = 1;

	

	msg_image="";

	is_error_upload_image = 0;

	

	if(is_error_upload_image == 0){

		

		var allowed_image_formats="png,gif,bmp,jpg,jpeg";

		var photo=$("#"+name).val();

		var ph=photo.lastIndexOf('.');

		var ext=photo.substring(ph,photo.length);

		ext=ext.toLowerCase();

		ext=ext.replace(".","");

		var allowd_formats=allowed_image_formats.indexOf(ext);

		

		if(allowd_formats == -1){

			msg_image="<?php echo $this->lang->line('image_not_true_format'); ?>";

			show_msg(name,msg_image,1);

			flag=0;

			//check focus

			if(is_focus == 1){

				set_focus(name);

				is_focus = 0;

			}

			is_error_upload_image = 1;

		}

		else{

			show_msg(name,"",0);

			flag=1;

			is_error_upload_image = 0;

		}

		

	}

	

}



function displayPreview(files) {

	

	var img = new Image(),

		fileSize=Math.round(files.size / 1024);

	

	img.onload = function () {

			var width=this.width,

				height=this.height,

				imgsrc=this.src;  

	  

		doSomething(fileSize,width,height,imgsrc); //call function

		

	};   

	img.src = _URL.createObjectURL(files);

	

}



// Do what you want in this function

function doSomething(size,width,height,imgsrc){

	

	flag = 1;

  	is_focus = 1;

	

	msg_image = "";

	is_error_upload_image = 0;

	

	if(is_error_upload_image == 0){

		

		if(size > 1024 * 20){

			

			msg_image="<?php echo $this->lang->line('image_exceeds_20_mb_allowed'); ?>";

			show_msg("photo_des_com",msg_image,1);

			flag=0;

			//check focus

			if(is_focus == 1){

				set_focus("photo_des_com");

				is_focus = 0;

			}

			is_error_upload_image = 1;

			

		}

		else{

			show_msg("photo_des_com","",0);

			flag=1;

			is_error_upload_image = 0;

		}

	}

	if(is_error_upload_image == 0){

		

		if(width < min_width_img_competition){

			

			msg_image = "<?php echo $this->lang->line('minimum_length'); ?>" + " " + min_width_img_competition + "px";

			show_msg("photo_des_com",msg_image,1);

			flag=0;

			//check focus

			if(is_focus == 1){

				set_focus("photo_des_com");

				is_focus = 0;

			}

			is_error_upload_image = 1;

			

		}
		
		else if(height < min_height_img_competition){

			

			msg_image = "<?php echo $this->lang->line('minimum_width'); ?>" + " " + min_height_img_competition + "px";

			show_msg("photo_des_com",msg_image,1);

			flag=0;

			//check focus

			if(is_focus == 1){

				set_focus("photo_des_com");

				is_focus = 0;

			}

			is_error_upload_image = 1;

			

		}

		else{

			show_msg("photo_des_com","",0);

			flag=1;

			is_error_upload_image = 0;

		}

	}

	

}



function show_msg(id,msg,show_flag){

	

	if(show_flag == 1){



		$("#"+id+"_msg").show();

		$("#"+id+"_msg").html("<br>"+msg);

	}

	else{

		$("#"+id+"_msg").hide();

	   	$("#"+id+"_msg").html('');

	}

	

}	

  

function set_focus(id){

		

	$("#"+id).focus();

	var offset = $("#"+id).offset();

	$("html,body").scrollTop(offset.top-150);

	

}

function numberWithCommas(x) {
    x = x.toString();
    var pattern = /(-?\d+)(\d{3})/;
    while (pattern.test(x))
        x = x.replace(pattern, "$1,$2");
    return x;
}



</script>



<div class="contents-mypage">

<div class="contents-mypage__wrapper clearfix">

  <div class="breadly">

    <ul>

      <li><a href="index.html" itemtype="http://data-vocabulary.org/Breadcrumb"><span itemprop="title">ホーム</span></a></li>

      <li><a href="mypage.html" itemtype="http://data-vocabulary.org/Breadcrumb"><span itemprop="title">マイページ</span></a></li>

      <li><span itemprop="title">コンペ編集</span></li>

    </ul>

  </div>

  <!--<div class="mypage-sub">

    <div class="mypage-sub__user-info">

      <p class="name">ようこそ<span>山本</span>さん</p>

      <p class="id">ユーザーID 599079</p>

    </div>

    <div class="mypage-sub__credit">

      <p class="credit">残高：<span>10000</span></p>

      <p class="link"><a href="credit.html">クレジットを購入する>></a></p>

    </div>

    <div class="mypage-sub__menu">

      <ul>

        <li><a href="mypage.html" class="menu-top">マイページトップ</a></li>

        <li><span class="menu-buy">購入</span>

          <ul>

            <li><a href="mypage-list.html">購入素材一覧</a></li>

            <li><a href="mypage-purchase.html">クレジット購入履歴</a></li>

          </ul>

        </li>

        <li><span class="menu-search">探す</span>

          <ul>

            <li><a href="mypage-history.html">素材閲覧履歴</a></li>

            <li><a href="mypage-favorite.html">お気に入り素材一覧</a></li>

            <li><a href="mypage-favorite-user.html">お気に入りユーザー一覧</a></li>

          </ul>

        </li>

        <li><a href="mypage-message.html" class="menu-message">お知らせ</a></li>

        <li><span class="menu-config">登録情報変更</span>

          <ul>

            <li><a href="mypage-config.html">登録情報の確認・変更</a></li>

            <li><a href="mypage-mail.html">メールアドレスの変更</a></li>

            <li><a href="mypage-password.html">パスワードの変更</a></li>

            <li><a href="mypage-laeve.html">退会する</a></li>

          </ul>

        </li>

        <li><a href="mypage-compe.html" class="menu-compe">コンペ一覧</a></li>

        <li><a href="creator-entry.html" class="menu-creator">クリエイター登録</a></li>

      </ul>

    </div>

  </div>-->

  <?php $this->load->view('user/sidebar_user.php') ?>

  <div class="mypage-main">

    <h1 class="contents-mypage__find"><?php echo $this->lang->line('content_edit_content_competition'); ?></h1>

    <div class="mypage-form">

      <form name="f" method="post" action="" enctype="multipart/form-data">

          <dl>

            <dt><?php echo $this->lang->line('title_of_the_competition_to_be_held'); ?></dt>

            <dd>

              <input type="text" id="title_com" name="title_com" maxlength="100" value="<?php echo $detail_competition->title_com; ?>" disabled="disabled" placeholder="<?php echo $this->lang->line('please_enter_title_competition'); ?>">

              <span id="title_com_msg" class="warning"></span>

            </dd>

            <dt><?php echo $this->lang->line('upload_cover_photo'); ?></dt>
            
            <dd>
            	<img width="150" height="150" src="<?php echo base_url(); ?>publics/competition_img/<?php echo $detail_competition->photo_des_com; ?>" />
            </dd>

            <dd>

              <div class="file">

				<input type="file" id="photo_des_com" name="photo_des_com">

                <input type="hidden" name="photo_des_com_old" value="<?php echo $detail_competition->photo_des_com; ?>">

              </div>

              <span id="photo_des_com_msg" class="warning"></span>

            </dd>

            <dt><?php echo $this->lang->line('upload_icon'); ?></dt>
            
            <?php if($detail_competition->icon_com != ""){ ?>
            <dd>
            	<img width="150" height="150" src="<?php echo base_url(); ?>publics/competition_icon/<?php echo $detail_competition->icon_com; ?>" />
            </dd>
            <?php } ?>

            <dd>

              <div class="file">

				<input type="file" id="icon_com" name="icon_com">

                <input type="hidden" name="icon_com_old" value="<?php echo $detail_competition->icon_com; ?>">

              </div>

              <span id="icon_com_msg" class="warning"></span>

            </dd>

            <dt><?php echo $this->lang->line('posting_start_date_deadline'); ?></dt>

            <dd>

            

              <div class="form-limit">

                  <div class="form-limit__column">

                    <input type="text" name="date_start_com" id="date_start_com" value="01/08/2017" disabled="disabled" placeholder="<?php echo $this->lang->line('select_publication_start_date'); ?>">

                  </div>

                  <div class="form-limit__column">

                    <input type="text" name="date_end_com" id="date_end_com" value="01/08/2017" disabled="disabled" placeholder="<?php echo $this->lang->line('select_the_deadline_date'); ?>">

                  </div>

                </div>

                    

                    <script "text/javascript" >

				

					$(document).ready(function(){

						

						$('#date_start_com').datepicker({

							changeYear:true,

							minDate: 0,

							onClose: function(selectedDate){

								$('#date_end_com').datepicker("option", "minDate", selectedDate);

							}

						});

						$("#date_start_com").datepicker( "option", "dateFormat", "dd/mm/yy");

						$("#date_start_com").val("<?php echo $detail_competition->date_start_com; ?>");

						

						$('#date_end_com').datepicker({

							changeYear:true,

							minDate: 0,

							onClose: function( selectedDate ){

								$('#date_start_com').datepicker("option", "maxDate", selectedDate);

							}

						});

						$("#date_end_com").datepicker( "option", "dateFormat", "dd/mm/yy");

						$("#date_end_com").val("<?php echo $detail_competition->date_end_com; ?>");

						

					});

				

				</script>	

                

              <span id="date_start_com_msg" class="warning"></span>

              <span id="date_end_com_msg" class="warning"></span>

            </dd>

            

            <dt><?php echo $this->lang->line('time_agree_competition'); ?><span class="contact-form__label"><?php echo $this->lang->line('required'); ?></span></dt>

            <dd>

              <select id="competition_time_agree" name="competition_time_agree">

              	<?php

					foreach($competition_time_agree as $detail_time_agree){

				?>

              	<option value="<?php echo $detail_time_agree->id; ?>" <?php echo ($detail_competition->competition_time_agree_id == $detail_time_agree->id) ? "selected='selected'": "" ?>>

					<?php

                    	echo $detail_time_agree->name."&nbsp;";

						if($detail_time_agree->type == 1){

							echo $this->lang->line('days');

						}

						elseif($detail_time_agree->type == 2){

							echo $this->lang->line('week');

						}

						elseif($detail_time_agree->type == 3){

							echo $this->lang->line('month');

						}

					?>

                </option>

                <?php

					}

				?>

              </select>

              <span id="time_agree_msg" class="warning"></span>

            </dd>

            

            <dt><?php echo $this->lang->line('private'); ?></dt>
            <dd>
              <?php
				if($user_level == 5){
			  		if($detail_competition->is_private == 1){
			  ?>
              		<label><input type="checkbox" id="is_private" name="is_private" value="1" checked="checked" disabled="disabled" />&nbsp;<?php echo $this->lang->line('private_text'); ?></label>
              <?php
				}
				else{
			  ?>
              		<label><input type="checkbox" id="is_private" name="is_private" value="1" disabled="disabled" />&nbsp;<?php echo $this->lang->line('private_text'); ?></label>
              <?php
					}
				}
			  ?>
            </dd>
            
            <dt><?php echo $this->lang->line('private_img_apply'); ?></dt>
            <dd>
              <?php
			  	if($detail_competition->is_private_img_apply == 1){
			  ?>
              		<label><input type="checkbox" id="is_private_img_apply" name="is_private_img_apply" value="1" checked="checked" />&nbsp;<?php echo $this->lang->line('private_img_apply_text'); ?></label>
              <?php
				}
				else{
			  ?>
              		<label><input type="checkbox" id="is_private_img_apply" name="is_private_img_apply" value="1" />&nbsp;<?php echo $this->lang->line('private_img_apply_text'); ?></label>
              <?php
				}
			  ?>
            </dd>

            

            <dt><?php echo $this->lang->line('application_requirements'); ?><span class="contact-form__label"><?php echo $this->lang->line('required'); ?></span></dt>

            <dd>

              <textarea id="note_require_com" name="note_require_com" placeholder="<?php echo $this->lang->line('application_requirements_placeholder'); ?>"><?php echo $detail_competition->note_require_com; ?></textarea>

              <span id="note_require_com_msg" class="warning"></span>

            </dd>

            <dt><?php echo $this->lang->line('application_condition'); ?><span class="contact-form__label"><?php echo $this->lang->line('required'); ?></span></dt>

            <dd>

              <textarea id="note_description_com" name="note_description_com" placeholder="<?php echo $this->lang->line('application_condition_placeholder'); ?>"><?php echo $detail_competition->note_description_com; ?></textarea>

              <span id="note_description_com_msg" class="warning"></span>

            </dd>

            <dt><?php echo $this->lang->line('required_of_shape'); ?><span class="contact-form__label"><?php echo $this->lang->line('required'); ?></span></dt>

            <dd>

              <select id="img_type_com" name="img_type_com">

              	<?php

					if($detail_competition->img_type_com == 0){

				?>

                		<option value="0" selected="selected"><?php echo $this->lang->line('no_vertical_and_horizontal_designation'); ?></option>

                        <option value="1"><?php echo $this->lang->line('landscape'); ?></option>

                        <option value="2"><?php echo $this->lang->line('portrait'); ?></option>

                <?php		

					}

					elseif($detail_competition->img_type_com == 1){

				?>

                		<option value="0"><?php echo $this->lang->line('no_vertical_and_horizontal_designation'); ?></option>

                        <option value="1" selected="selected"><?php echo $this->lang->line('landscape'); ?></option>

                        <option value="2"><?php echo $this->lang->line('portrait'); ?></option>

                <?php

					}

					elseif($detail_competition->img_type_com == 2){

				?>

                		<option value="0"><?php echo $this->lang->line('no_vertical_and_horizontal_designation'); ?></option>

                        <option value="1"><?php echo $this->lang->line('landscape'); ?></option>

                        <option value="2" selected="selected"><?php echo $this->lang->line('portrait'); ?></option>

                <?php

					}

				?>

              </select>

              <span id="img_type_msg" class="warning"></span>

            </dd>

            <dt><?php echo $this->lang->line('required_of_color'); ?><span class="contact-form__label"><?php echo $this->lang->line('required'); ?></span></dt>

            <dd>

              <!--img_color_id-->

              <div class="form-color">

                <ul class="ul-box-color">

                  <?php

				  	foreach($list_product_color as $detail_product_color){

				  ?>

                  <li id="color-<?php echo $detail_product_color->product_color_id; ?>">

                    <label class="form-color__<?php echo $detail_product_color->name; ?>">

                      <?php

					  	if (in_array($detail_product_color->product_color_id, $product_color)){

					  ?>

                      		<input id="value-color-<?php echo $detail_product_color->product_color_id; ?>" type="checkbox" checked="checked" value="<?php echo $detail_product_color->product_color_id; ?>" ><span>&nbsp;</span><?php echo $detail_product_color->name_color; ?>

                      <?php

						}

						else{

					  ?>

                      		<input id="value-color-<?php echo $detail_product_color->product_color_id; ?>" type="checkbox" value="<?php echo $detail_product_color->product_color_id; ?>" ><span>&nbsp;</span><?php echo $detail_product_color->name_color; ?>

                      <?php

						}

					  ?>

                    </label>

                  </li>

                  <?php

					}

				  ?>

                </ul>

              </div>

              <input type="hidden" id="id_color" name="id_color" value="<?php echo $detail_competition->img_color_id; ?>" />

              <span id="img_color_id_msg" class="warning"></span>

            </dd>

            <dt><?php echo $this->lang->line('usage_of_photograph'); ?><span class="contact-form__label"><?php echo $this->lang->line('required'); ?></span></dt>

            <dd>

              <textarea id="note_img_purpose_com" name="note_img_purpose_com" placeholder="<?php echo $this->lang->line('usage_of_photograph_placeholder'); ?>"><?php echo $detail_competition->note_img_purpose_com; ?></textarea>

              <span id="note_img_purpose_com_msg" class="warning"></span>

            </dd>

            <dt><?php echo $this->lang->line('prize'); ?></dt>

            <dd>

              <div class="form-present">

                <div class="form-present__price">

                  <input type="text" id="point_img_com" name="point_img_com" class="price" value="<?php echo $detail_competition->point_img_com; ?>" placeholder="3000" disabled="disabled">

                </div>

                <div class="form-present__text"><?php echo $this->lang->line('point'); ?>×</div>

                <div class="form-present__value">

                  <input type="text" id="img_quantity_com" name="img_quantity_com" class="price" value="<?php echo $detail_competition->img_quantity_com; ?>" placeholder="10" disabled="disabled">

                </div>

                <div class="form-present__text"><?php echo $this->lang->line('each'); ?></div>

                <div class="form-present__text">
                	<b>
						<?php echo $this->lang->line('total_prize_money'); ?> <span>
                        <span id="total_com"><?php echo $detail_competition->point_img_com*$detail_competition->img_quantity_com; ?></span>
						<?php echo $this->lang->line('point'); ?></span>
                        =
                        <span><span id="total_com_yen"><?php echo number_format($point_convert_money*$detail_competition->point_img_com*$detail_competition->img_quantity_com,0,".",","); ?></span>
						<?php echo $this->lang->line('unit_yen'); ?></span></span>
                        
                    </b></div>

              </div>

              <span id="point_img_com_msg" class="warning"></span>

              <span id="img_quantity_com_msg" class="warning"></span>

            </dd>

            <dt><?php echo $this->lang->line('applicant_information'); ?></dt>

            <dd>

              <input type="text" id="link_url_com" name="link_url_com" value="<?php echo $detail_competition->link_url_com; ?>" placeholder="<?php echo $this->lang->line('company_information_sns_account_url_etc'); ?>">

              <span id="link_url_com_msg" class="warning"></span>

            </dd>

            <dt><?php echo $this->lang->line('notes'); ?></dt>

            <dd>

              <textarea id="note_another_des_com" name="note_another_des_com" placeholder="<?php echo $this->lang->line('other_notes'); ?>"><?php echo $detail_competition->note_another_des_com; ?></textarea>

            </dd>

          </dl>

          <div class="mypage-form__btn">

            <input type="submit" id="btnEditCompe" name="btnEditCompe" value="<?php echo $this->lang->line('save'); ?>">

          </div>

        </form>

    </div>

  </div>

</div>

</div>