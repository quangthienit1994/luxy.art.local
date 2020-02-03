<?php
if(!isset($_SESSION)){
	session_start();
}
$CI =& get_instance();
$CI->config->load();
if(!isset($_SESSION['lang'])){
	$_SESSION['lang'] = 'ja';
}
$config['language'] = $_SESSION['lang'];
$ngonngu = $config['language'];
$this->lang->load('dich', $ngonngu);
?>
<style>
/*mypage-form*/
.mypage-form .form-name{
	width: 100% !important;
}
.mypage-form__btn input[type="button"] {
    background: rgba(0, 0, 0, 0) linear-gradient(to bottom, #00d2b9, #00c6ad) repeat scroll 0 0;
    border-radius: 3px;
    color: #fff;
    cursor: pointer;
    font-size: 15px;
    font-weight: bold;
    height: inherit;
    text-align: center;
    width: inherit;
}
#product_tags_chosen {
    width: 360px !important;
}
/*box-color*/
.box-color {
  width: 100%;
  margin: 1px 0 0;
  box-sizing: border-box;
  position: relative;
}
.box-color .ul-box-color li {
  margin: 0 2px 4px 0;
  border: 1px solid #ddd;
  display: inline-block;
  vertical-align: top;
}
.box-color .ul-box-color label {
  width: 16px;
  height: 16px;
  display: block;
}
.box-color .ul-box-color .label-select {
  border: 2px solid #fff;
  box-sizing: border-box;
}
.box-color .ul-box-color input[type="checkbox"] {
  display: none;
}
label {
    display: inline-block;
    font-weight: bold;
    margin-bottom: 0 !important;
}
/*all*/
.warning{
	color:#F00;
}
.red{
	color:#F00;
}
.multiselect-container{
	overflow-x: none !important;
	overflow-y: scroll !important;
	height: 300px;
}
.mypage-form .form-name__column {
    width: calc(90% / 4)!important;
    display: inline-block;
}
.mypage-form__label {margin-top:0!important}
#post_image .form-name__column p{padding:0;margin:0}
@media (max-width: 768px)
{
	#dd_category .btn-group {width:100%;text-align:left}
	#dd_category .btn-group button {width:100%!important;text-align:left}
	#dd_category .btn-group button b{    text-align: right;float: right;margin-top: 8px;}
	#dd_category_sub .btn-group {width:100%;text-align:left}
	#dd_category_sub .btn-group button {width:100%!important;text-align:left}
	#dd_category_sub .btn-group button b{    text-align: right;float: right;margin-top: 8px;}
}
</style>
<script>
	text_none_selected = "<?php echo $this->lang->line('none_selected') ?>";
	text_select_all = "<?php echo $this->lang->line('select_all') ?>";
	text_search = "<?php echo $this->lang->line('search') ?>";
	text_choose = "<?php echo $this->lang->line('choose') ?>";
	default_multiple_text = "<?php echo $this->lang->line('default_multiple_text') ?>";
	default_single_text = "<?php echo $this->lang->line('default_single_text') ?>";
	default_no_result_text = "<?php echo $this->lang->line('default_no_result_text') ?>";
</script>
<!--multiselect-->
<link rel="stylesheet" href="<?php echo base_url(); ?>publics/multiselect/css/bootstrap.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>publics/multiselect/css/bootstrap-multiselect.css" type="text/css">
<link rel="stylesheet" href="<?php echo base_url(); ?>publics/multiselect/css/prettify.css" type="text/css">
<link rel="stylesheet" href="<?php echo base_url(); ?>publics/multiselect/css/jquerysctipttop.css" type="text/css">
<script type="text/javascript" src="<?php echo base_url(); ?>publics/multiselect/js/jquery.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>publics/multiselect/js/bootstrap.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>publics/multiselect/js/bootstrap-multiselect.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>publics/multiselect/js/prettify.js"></script>
<!--<script type="text/javascript" src="<?php echo base_url(); ?>publics/multiselect/js/show_ads.js"></script>-->
<!--end multiselect-->
<script src="<?php echo base_url(); ?>publics/js/auto-formatting-currency.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>publics/js/browser-md5-file.js"></script>
<script type="text/javascript">
$('.dropdown input, .dropdown label').click(function (event) {
	event.stopPropagation();
});
$(document).ready(function() {
	
	window.prettyPrint() && prettyPrint();
	
	$('#category').multiselect({
		includeSelectAllOption: true,
		enableCaseInsensitiveFiltering: true
	});
	
	$('#category_sub').multiselect({
		includeSelectAllOption: true,
		enableCaseInsensitiveFiltering: true
	});
	
	$("#category").change(function(){
		
		var val_category = $('#category').val();
		load_product_tags(val_category, "");
		
		$.ajax({
			type: "POST",cache: false,
			url: "<?php echo base_url(); ?>ajax/get_muti_sub_cate",
			data: "val_category="+val_category,
			success: function(data) {
				
				$("#sp_category_sub").html(data);
				$('#category_sub').multiselect({
					includeSelectAllOption: true,
					enableCaseInsensitiveFiltering: true
				});
				
				$("#category_sub").change(function(){
		
					var val_category_sub = $('#category_sub').val();
					load_product_tags(val_category, val_category_sub);
					
				});
				
			},
			async: false
		})
		
	});
	
});

function load_product_tags(val_category, val_category_sub){
	
	$.ajax({
		type: "POST",cache: false,
		url: "<?php echo base_url(); ?>ajax/load_product_tags",
		data: "val_category="+val_category+"&val_category_sub="+val_category_sub,
		success: function(data) {
			$("#sp_product_tags").html(data);
			var config = {
			  '.chosen-select-product_tags'      	  : {max_selected_options:5},
			  '.chosen-select-product_tags-deselect'  : {allow_single_deselect:true},
			  '.chosen-select-product_tags-no-single' : {disable_search_threshold:10},
			  '.chosen-select-product_tags-no-results': {no_results_text:'<?php echo $this->lang->line('do_not_have_keyword_in_system'); ?>'},
			  '.chosen-select-product_tags-width'     : {width:"95%"}
			}
			for (var selector in config) {
			  $(selector).chosen(config[selector]);
			}
		},
		async: false
	})
	
}

</script>
    
<script>
//check error when (upload image > 5 MB) && (width || height < 200)
var _URL = window.URL || window.webkitURL;
//var id_color = 0;
var msg_image = "";
var is_error_upload_image = 0;
var img_width_main;
var img_height_main;
var is_limit = 0;
var min_width_img = "<?php echo $min_width_img; ?>";
var min_height_img = "<?php echo $min_height_img; ?>";
var allowed_image_formats = "<?php echo $allowed_image_formats; ?>";
var max_mb = "<?php echo $max_mb; ?>";
$(document).ready(function() {
	set_product_size(1);
	set_is_limit(0);
	//color
	/*$(".ul-box-color li").click(function(){
    
		var index = $(this).index();
		var id = $('.ul-box-color li').get(index).id;
		
		$(".ul-box-color li label").removeClass("label-select");
		$(".ul-box-color li label:eq(" + index + ")").addClass("label-select");
		
		id_color = id.replace("color-", "");
		$("#id_color").val(id_color);
		
	});*/
	
	$("#is_limit").click(function(){
        if($('#is_limit').is(':checked')){
			set_is_limit(1);
		}
		else{
			set_is_limit(0);
		}
    });
	
	//product size
	$(".product-size").keyup(function() {
		set_product_img_size();
	});
	
	//cal product size
	$("#type_size_width_s").keyup(function() {
		
		img_width = $("#img_width").val();
		img_height = $("#img_height").val();
		
		width_s = $("#type_size_width_s").val();
		width_s = width_s.replace(/,/g, "");
		
		height_s = img_height * width_s / img_width;
		
		if(height_s > 0){
			$("#type_size_height_s").val(Math.round(height_s));
		}
		else{
			$("#type_size_height_s").val("");
		}
		
	});
	
	$("#type_size_height_s").keyup(function() {
		
		img_width = $("#img_width").val();
		img_height = $("#img_height").val();
		
		height_s = $("#type_size_height_s").val();
		height_s = height_s.replace(/,/g, "");
		
		width_s = img_width * height_s / img_height;
		if(width_s > 0){
			$("#type_size_width_s").val(Math.round(width_s));	
		}
		else{
			$("#type_size_width_s").val("");
		}
		
	});
	
	$("#type_size_width_m").keyup(function() {
		
		img_width = $("#img_width").val();
		img_height = $("#img_height").val();
		
		width_m = $("#type_size_width_m").val();
		width_m = width_m.replace(/,/g, "");
		
		height_m = img_height * width_m / img_width;
		if(height_m > 0){
			$("#type_size_height_m").val(Math.round(height_m));
		}
		else{
			$("#type_size_height_m").val("");
		}
		
	});
	
	$("#type_size_height_m").keyup(function() {
		
		img_width = $("#img_width").val();
		img_height = $("#img_height").val();
		
		height_m = $("#type_size_height_m").val();
		height_m = height_m.replace(/,/g, "");
		
		width_m = img_width * height_m / img_height;
		
		
		if(width_m > 0){
			$("#type_size_width_m").val(Math.round(width_m));	
		}
		else{
			$("#type_size_width_m").val("");
		}
		
	});
	
	$("#type_size_width_l").keyup(function() {
		
		img_width = $("#img_width").val();
		img_height = $("#img_height").val();
		
		width_l = $("#type_size_width_l").val();
		width_l = width_l.replace(/,/g, "");
		
		height_l = img_height * width_l / img_width;
		if(height_l > 0){
			$("#type_size_height_l").val(Math.round(height_l));	
		}
		else{
			$("#type_size_height_l").val("");
		}
		
	});
	
	$("#type_size_height_l").keyup(function() {
		
		img_width = $("#img_width").val();
		img_height = $("#img_height").val();
		
		height_l = $("#type_size_height_l").val();
		height_l = height_l.replace(/,/g, "");
		
		width_l = img_width * height_l / img_height;
		
		
		if(width_l > 0){
			$("#type_size_width_l").val(Math.round(width_l));
		}
		else{
			$("#type_size_width_l").val("");
		}
		
	});
	
	//image
	$('#file_name_original_img').bind('change', function () {
	  //var file = e.target.files[0];
	  
	  is_error_upload_image = 0;
	  var file = this.files[0];
	  checkExtension("file_name_original_img");
	  displayPreview(file);
	  
	  browserMD5File(file, function (err, md5) {
		console.log("md5.md511 = " + md5); // 97027eb624f85892c69c4bcec8ab0f11
		$("#file_md5").val(md5);
		
		$.ajax({
			type: "POST",cache: false,
			url: "<?php echo base_url(); ?>ajax/check_file_md5_exits",
			data: "file_md5="+md5,
			success: function(data) {
				
				if(data == 1){
					msg_image="<?php echo $this->lang->line('this_image_has_been_used'); ?>";
					show_msg("file_name_original_img",msg_image,1);
					flag=0;
					//check focus
					if(is_focus == 1){
						set_focus("file_name_original_img");
						is_focus = 0;
					}
					is_error_upload_image = 1;
				}
				
			},
			async: false
		})
		
	  });
	  
	  width = parseInt(img_width_main);
	  height = parseInt(img_width_main);
	
	  if(width > 0 && height > 0){
	
		$("#type_size_width_s").val(round(width/3));
		$("#type_size_height_s").val(round(height/3));
		
		$("#type_size_width_m").val(round(width/2));
		$("#type_size_height_m").val(round(height/2));
		
		$("#type_size_width_l").val(width);
		$("#type_size_height_l").val(height/3);
	
	 }
	  
	 $("#type_size_point_s").val(30);
	 $("#type_size_point_m").val(50);
	 $("#type_size_point_l").val(100);
	  
	  
	});
	
	//submit
	$("#btnPostImage").click(function(){	
	
		return validatePostImage();
	});
	
});
function set_product_img_size(){
	
	type_size_width_s = $("#type_size_width_s").val();
	type_size_height_s = $("#type_size_height_s").val();
	type_size_point_s = $("#type_size_point_s").val();
	type_size_stock_s = $("#type_size_stock_s").val();
	
	type_size_width_m = $("#type_size_width_m").val();
	type_size_height_m = $("#type_size_height_m").val();
	type_size_point_m = $("#type_size_point_m").val();
	type_size_stock_m = $("#type_size_stock_m").val();
	
	type_size_width_l = $("#type_size_width_l").val();
	type_size_height_l = $("#type_size_height_l").val();
	type_size_point_l = $("#type_size_point_l").val();
	type_size_stock_l = $("#type_size_stock_l").val();
	
	if(type_size_width_s != "" || type_size_height_s != ""){
		$("#type_size_s").prop("checked", true);
	}
	else{
		$("#type_size_s").prop("checked", false);	
	}
	
	if(type_size_width_m != "" || type_size_height_m != ""){
		$("#type_size_m").prop("checked", true);
	}
	else{
		$("#type_size_m").prop("checked", false);	
	}
	
	if(type_size_width_l != "" || type_size_height_l != ""){
		$("#type_size_l").prop("checked", true);
	}
	else{
		$("#type_size_l").prop("checked", false);	
	}
	
}
function checkExtension(name){
	
	jp_flag = 1;
  	is_focus = 1;
	
	msg_image="";
	//is_error_upload_image = 0;
	
	if(is_error_upload_image == 0){
		
		var allowed_image_formats="<?php echo $allowed_image_formats; ?>";
		var photo=$("#"+name).val();
		var ph=photo.lastIndexOf('.');
		var ext=photo.substring(ph,photo.length);
		ext=ext.toLowerCase();
		ext=ext.replace(".","");
		var allowd_formats=allowed_image_formats.indexOf(ext);
		
		if(allowd_formats == -1){
			msg_image="<?php echo $this->lang->line('image_not_true_format'); ?>";
			show_msg(name,msg_image,1);
			jp_flag=0;
			//check focus
			if(is_focus == 1){
				set_focus(name);
				is_focus = 0;
			}
			is_error_upload_image = 1;
			set_product_size(1);
		}
		else{
			show_msg(name,"",0);
			jp_flag=1;
			is_error_upload_image = 0;
			set_product_size(0);
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
	
	jp_flag = 1;
  	is_focus = 1;
	
	msg_image = "";
	//is_error_upload_image = 0;
	
	$("#img_width").val(width);
	$("#img_height").val(height);
	
	img_width_main = parseInt(width);
	img_height_main = parseInt(height);
	
	if(is_error_upload_image == 0){
		
		if(size > 1024 * max_mb){
			
			msg_image="<?php echo $this->lang->line('image_exceeds_num_mb_allowed'); ?>";
			msg_image = msg_image.replace("{num}", max_mb);
			show_msg("file_name_original_img",msg_image,1);
			jp_flag=0;
			//check focus
			if(is_focus == 1){
				set_focus("file_name_original_img");
				is_focus = 0;
			}
			is_error_upload_image = 1;
			set_product_size(1);
			
		}
		else{
			show_msg("file_name_original_img","",0);
			jp_flag=1;
			is_error_upload_image = 0;
			set_product_size(0);
		}
	}
	
	if(is_error_upload_image == 0){
		
		if(width < min_width_img && height < min_height_img){
			msg_image="<?php echo $this->lang->line('minimum_width_num1_px_minimum_height_num2_px'); ?>";
			msg_image = msg_image.replace("{num1}", min_width_img);
			msg_image = msg_image.replace("{num2}", min_height_img);
			show_msg("file_name_original_img",msg_image,1);
			jp_flag=0;
			//check focus
			if(is_focus == 1){
				set_focus("file_name_original_img");
				is_focus = 0;
			}
			is_error_upload_image = 1;
			set_product_size(1);
		}
		
		else if(width < min_width_img){
			msg_image="<?php echo $this->lang->line('minimum_width_num_px'); ?>";
			msg_image = msg_image.replace("{num}", min_width_img);
			show_msg("file_name_original_img",msg_image,1);
			jp_flag=0;
			//check focus
			if(is_focus == 1){
				set_focus("file_name_original_img");
				is_focus = 0;
			}
			is_error_upload_image = 1;
			set_product_size(1);
		}
		
		else if(height < min_height_img){
			msg_image="<?php echo $this->lang->line('minimum_height_num_px'); ?>";
			msg_image = msg_image.replace("{num}", min_height_img);
			show_msg("file_name_original_img",msg_image,1);
			jp_flag=0;
			//check focus
			if(is_focus == 1){
				set_focus("file_name_original_img");
				is_focus = 0;
			}
			is_error_upload_image = 1;
			set_product_size(1);
		}
		else{
			show_msg("file_name_original_img","",0);
			jp_flag=1;
			//is_error_upload_image = 0;
			set_product_size(0);
		}
	}
	
	$("#type_size_width_s").val(""+Math.round(img_width_main/3));
	$("#type_size_height_s").val(""+Math.round(img_height_main/3));
	
	$("#type_size_width_m").val(""+Math.round(img_width_main/2));
	$("#type_size_height_m").val(""+Math.round(img_height_main/2));
	
	$("#type_size_width_l").val(""+img_width_main);
	$("#type_size_height_l").val(""+img_height_main);
	
	$("#type_size_s").prop("checked", true);
	$("#type_size_m").prop("checked", true);
	$("#type_size_l").prop("checked", true);
	
}
function set_product_size(is_disabled){
	
	$("#type_size_width_s").val('');
	$("#type_size_height_s").val('');
	$("#type_size_width_m").val('');
	$("#type_size_height_m").val('');
	$("#type_size_width_l").val('');
	$("#type_size_height_l").val('');	
	
	$("#type_size_s").prop("checked", false);
	$("#type_size_m").prop("checked", false);
	$("#type_size_l").prop("checked", false);
	
	if(is_disabled == 0){
		$("#type_size_width_s").removeAttr('disabled');
		$("#type_size_height_s").removeAttr('disabled');
		$("#type_size_width_m").removeAttr('disabled');
		$("#type_size_height_m").removeAttr('disabled');
		$("#type_size_width_l").removeAttr('disabled');
		$("#type_size_height_l").removeAttr('disabled');
	}
	else{
		$("#type_size_width_s").attr('disabled','disabled');
		$("#type_size_height_s").attr('disabled','disabled');
		$("#type_size_width_m").attr('disabled','disabled');
		$("#type_size_height_m").attr('disabled','disabled');
		$("#type_size_width_l").attr('disabled','disabled');
		$("#type_size_height_l").attr('disabled','disabled');
	}
	
}
function set_is_limit(val_limit){
	
	if(val_limit == 0){
		
		$("#type_size_stock_s").val('');
		$("#type_size_stock_m").val('');
		$("#type_size_stock_l").val('');
		
		$("#type_size_stock_s").attr('disabled','disabled');
		$("#type_size_stock_m").attr('disabled','disabled');
		$("#type_size_stock_l").attr('disabled','disabled');
		
		is_limit = 0;
		
	}
	else if(val_limit == 1){
		
		$("#type_size_stock_s").removeAttr('disabled');
		$("#type_size_stock_m").removeAttr('disabled');
		$("#type_size_stock_l").removeAttr('disabled');
		
		$("#type_size_stock_s").val(1);
		$("#type_size_stock_m").val(1);
		$("#type_size_stock_l").val(1);
		
		is_limit = 1;
		
	}
	
}
function validatePostImage(){
	
	var msg = "";
	var flag = 1;
	var is_focus = 1;
	
	//img_title	
	img_title = $("#img_title").val();
	if(img_title == ""){
		msg="<?php echo $this->lang->line('please_input_title_image'); ?>";
		show_msg("img_title",msg,1);
		flag=0;
		//check focus
		if(is_focus == 1){
			set_focus("img_title");
			is_focus = 0;
		}
	}
	else{
		msg="";
		show_msg("img_title",msg,0);
	}
	
	//category
	var category = $('#category').val();
	if(category == null){
		msg="<?php echo $this->lang->line('please_choose_root_category'); ?>";
		show_msg("category",msg,1);
		flag=0;
		//check focus
		if(is_focus == 1){
			set_focus("dd_category");
			is_focus = 0;
		}
	}
	else{
		msg="";
		show_msg("category",msg,0);
		$("#val_category").val(category);
	}
	
	//category_sub
	var category_sub = $('#category_sub').val();
	if(category_sub == null){
		msg="<?php echo $this->lang->line('please_choose_sub_category'); ?>";
		show_msg("category_sub",msg,1);
		flag=0;
		//check focus
		if(is_focus == 1){
			set_focus("dd_category_sub");
			is_focus = 0;
		}
	}
	else{
		msg="";
		show_msg("category_sub",msg,0);
		$("#val_category_sub").val(category_sub);
	}
	
	//file_name_original_img
	var allowed_image_formats="<?php echo $allowed_image_formats; ?>";
	var photo=$("#file_name_original_img").val();
	var ph=photo.lastIndexOf('.');
	var ext=photo.substring(ph,photo.length);
	ext=ext.toLowerCase();
	ext=ext.replace(".","");
	var allowd_formats=allowed_image_formats.indexOf(ext);
	if(ext==""){
		msg="<?php echo $this->lang->line('please_choose_image'); ?>";
		show_msg("file_name_original_img",msg,1);
		flag=0;
		//check focus
		if(is_focus == 1){
			set_focus("file_name_original_img");
			is_focus = 0;
		}
	}
	else{
		//check error when (upload image > 5 MB) && (width || height < 200)
		if(is_error_upload_image == 1){		
			show_msg("file_name_original_img",msg_image,1);
			flag=0;
			//check focus
			if(is_focus == 1){
				set_focus("file_name_original_img");
				is_focus = 0;
			}
			
		}
		else{
			
			show_msg("file_name_original_img",msg_image,0); 
		}
	}
	
	//id_color
	/*if(id_color == 0){
		msg="Vui lòng chọn màu chủ đạo ảnh";
		show_msg("id_color",msg,1);
		flag=0;
	}
	else{
		msg="";
		show_msg("id_color",msg,0);
	}*/
	
	//product_tags
	product_tags = $("#product_tags").val();
	if(product_tags == null){
		msg="<?php echo $this->lang->line('please_choose_image_tags'); ?>";
		show_msg("product_tags",msg,1);
		flag=0;
		
		//check focus
		if(is_focus == 1){
			set_focus("product_tags_chosen");
			is_focus = 0;
		}
	}
	else{
		msg="";
		show_msg("product_tags",msg,0);
	}
	
	//type_size
	count_type_size = 0;
	
	if($('#type_size_s').is(":checked")){
		count_type_size++;
	}
	
	if($('#type_size_m').is(":checked")){
		count_type_size++;
	}
	
	if($('#type_size_l').is(":checked")){
		count_type_size++;
	}
	
	if(count_type_size == 0){
		msg="<?php echo $this->lang->line('please_select_image_size'); ?>";
		show_msg("product_size",msg,1);
		
		flag=0;
		
		//check focus
		if(is_focus == 1){
			set_focus("type_size_width_s");
			is_focus = 0;
		}
		return false;
		
	}
	else{
		//input
		type_size_width_s = $("#type_size_width_s").val().trim().replace(/,/g, "");
		type_size_height_s = $("#type_size_height_s").val().trim().replace(/,/g, "");
		type_size_point_s = $("#type_size_point_s").val().trim().replace(/,/g, "");
		type_size_stock_s = $("#type_size_stock_s").val().trim().replace(/,/g, "");
		
		type_size_width_m = $("#type_size_width_m").val().trim().replace(/,/g, "");
		type_size_height_m = $("#type_size_height_m").val().trim().replace(/,/g, "");
		type_size_point_m = $("#type_size_point_m").val().trim().replace(/,/g, "");
		type_size_stock_m = $("#type_size_stock_m").val().trim().replace(/,/g, "");
		
		type_size_width_l = $("#type_size_width_l").val().trim().replace(/,/g, "");
		type_size_height_l = $("#type_size_height_l").val().trim().replace(/,/g, "");
		type_size_point_l = $("#type_size_point_l").val().trim().replace(/,/g, "");
		type_size_stock_l = $("#type_size_stock_l").val().trim().replace(/,/g, "");
		
		if($('#type_size_s').is(":checked") || 1 == 1){
			if(type_size_width_s == ""){
				msg="<?php echo $this->lang->line('please_input_image_length_s_size'); ?>";
				show_msg("product_size",msg,1);
				flag=0;
				//check focus
				if(is_focus == 1){
					set_focus("type_size_width_s");
					is_focus = 0;
				}
				return false;
			}
			else if(type_size_height_s == ""){
				msg="<?php echo $this->lang->line('please_input_image_width_s_size'); ?>";
				show_msg("product_size",msg,1);
				flag=0;
				//check focus
				if(is_focus == 1){
					set_focus("type_size_height_s");
					is_focus = 0;
				}
				return false;
			}
			else if(type_size_point_s == ""){
				msg="<?php echo $this->lang->line('please_input_image_point_s_size'); ?>";
				show_msg("product_size",msg,1);
				flag=0;
				//check focus
				if(is_focus == 1){
					set_focus("type_size_point_s");
					is_focus = 0;
				}
				return false;
			}
			else if(type_size_stock_s == ""){
				if(is_limit == 1){
					msg="<?php echo $this->lang->line('please_input_the_number_of_images_that_sell_images_of_s_size'); ?>";
					show_msg("product_size",msg,1);
					flag=0;
					//check focus
					if(is_focus == 1){
						set_focus("type_size_stock_s");
						is_focus = 0;
					}
					return false;
				}
			}
			else{
				show_msg("product_size","",0);
			}
		}
		if($('#type_size_m').is(":checked") || 1 == 1){
			if(type_size_width_m == ""){
				msg="<?php echo $this->lang->line('please_input_image_length_m_size'); ?>";
				show_msg("product_size",msg,1);
				flag=0;
				//check focus
				if(is_focus == 1){
					set_focus("type_size_width_m");
					is_focus = 0;
				}
				return false;
			}
			else if(type_size_height_m == ""){
				msg="<?php echo $this->lang->line('please_input_image_width_m_size'); ?>";
				show_msg("product_size",msg,1);
				flag=0;
				//check focus
				if(is_focus == 1){
					set_focus("type_size_height_m");
					is_focus = 0;
				}
				return false;
			}
			else if(type_size_point_m == ""){
				msg="<?php echo $this->lang->line('please_input_image_point_m_size'); ?>";
				show_msg("product_size",msg,1);
				flag=0;
				//check focus
				if(is_focus == 1){
					set_focus("type_size_point_m");
					is_focus = 0;
				}
				return false;
			}
			else if(type_size_stock_m == ""){
				if(is_limit == 1){
					msg="<?php echo $this->lang->line('please_input_the_number_of_images_that_sell_images_of_m_size'); ?>";
					show_msg("product_size",msg,1);
					flag=0;
					//check focus
					if(is_focus == 1){
						set_focus("type_size_stock_m");
						is_focus = 0;
					}
					return false;
				}
			}
			else{
				show_msg("product_size","",0);
			}
		}
		if($('#type_size_l').is(":checked") || 1 == 1){
			if(type_size_width_l == ""){
				msg="<?php echo $this->lang->line('please_input_image_length_l_size'); ?>";
				show_msg("product_size",msg,1);
				flag=0;
				//check focus
				if(is_focus == 1){
					set_focus("type_size_width_l");
					is_focus = 0;
				}
				return false;
			}
			else if(type_size_height_l == ""){
				msg="<?php echo $this->lang->line('please_input_image_width_l_size'); ?>";
				show_msg("product_size",msg,1);
				flag=0;
				//check focus
				if(is_focus == 1){
					set_focus("type_size_height_l");
					is_focus = 0;
				}
				return false;
			}
			else if(type_size_point_l == ""){
				msg="<?php echo $this->lang->line('please_input_image_point_l_size'); ?>";
				show_msg("product_size",msg,1);
				flag=0;
				//check focus
				if(is_focus == 1){
					set_focus("type_size_point_l");
					is_focus = 0;
				}
				return false;
			}
			else if(type_size_stock_l == ""){
				if(is_limit == 1){
					msg="<?php echo $this->lang->line('please_input_the_number_of_images_that_sell_images_of_l_size'); ?>";
					show_msg("product_size",msg,1);
					flag=0;
					//check focus
					if(is_focus == 1){
						set_focus("type_size_stock_l");
						is_focus = 0;
					}
					return false;
				}
			}
			else{
				show_msg("product_size","",0);
			}
		}
		
		//price
		if($('#type_size_s').is(":checked") && $('#type_size_m').is(":checked")){
			
			if(parseInt(type_size_width_s) >= parseInt(type_size_width_m)){
				msg="<?php echo $this->lang->line('size_s_with_m_is_incorrect'); ?>";
				show_msg("product_size",msg,1);
				flag=0;
				//check focus
				if(is_focus == 1){
					set_focus("type_size_width_m");
					is_focus = 0;
				}
				return false;
			}
			else{
				show_msg("product_size","",0);
			}
		}
		
		if($('#type_size_s').is(":checked") && $('#type_size_l').is(":checked")){
			if(parseInt(type_size_width_s) >= parseInt(type_size_width_l)){
				msg="<?php echo $this->lang->line('size_s_with_l_is_incorrect'); ?>";
				show_msg("product_size",msg,1);
				flag=0;
				//check focus
				if(is_focus == 1){
					set_focus("type_size_width_l");
					is_focus = 0;
				}
				return false;
			}
			else{
				show_msg("product_size","",0);
			}
		}
		
		if($('#type_size_m').is(":checked") && $('#type_size_l').is(":checked")){
			if(parseInt(type_size_width_m) >= parseInt(type_size_width_l)){
				msg="<?php echo $this->lang->line('size_m_with_l_is_incorrect'); ?>";
				show_msg("product_size",msg,1);
				flag=0;
				//check focus
				if(is_focus == 1){
					set_focus("type_size_width_l");
					is_focus = 0;
				}
				return false;
			}
			else{
				show_msg("product_size","",0);
			}
		}
		
		if($('#type_size_s').is(":checked") && $('#type_size_m').is(":checked") && $('#type_size_l').is(":checked")){
			
			if(parseInt(type_size_width_s) >= parseInt(type_size_width_m)){
				msg="<?php echo $this->lang->line('size_s_with_m_is_incorrect'); ?>";
				show_msg("product_size",msg,1);
				flag=0;
				//check focus
				if(is_focus == 1){
					set_focus("type_size_width_s");
					is_focus = 0;
				}
				return false;
			}
			else{
				show_msg("product_size","",0);
			}
			
			if(parseInt(type_size_width_m) >= parseInt(type_size_width_l)){
				msg="<?php echo $this->lang->line('size_m_with_l_is_incorrect'); ?>";
				show_msg("product_size",msg,1);
				flag=0;
				//check focus
				if(is_focus == 1){
					set_focus("type_size_width_l");
					is_focus = 0;
				}
				return false;
			}
			else{
				show_msg("product_size","",0);
			}
			
		}
		
		
		//type_size_point
		if($('#type_size_s').is(":checked") && $('#type_size_m').is(":checked")){
			if(parseInt(type_size_point_s) >= parseInt(type_size_point_m)){
				msg="<?php echo $this->lang->line('point_m_must_be_greater_than_point_s'); ?>";
				show_msg("product_size",msg,1);
				flag=0;
				//check focus
				if(is_focus == 1){
					set_focus("type_size_point_m");
					is_focus = 0;
				}
				return false;
			}
			else{
				show_msg("product_size","",0);
			}
		}
		
		if($('#type_size_s').is(":checked") && $('#type_size_l').is(":checked")){
			if(parseInt(type_size_point_s) >= parseInt(type_size_point_l)){
				msg="<?php echo $this->lang->line('point_l_must_be_greater_than_point_s'); ?>";
				show_msg("product_size",msg,1);
				flag=0;
				//check focus
				if(is_focus == 1){
					set_focus("type_size_point_l");
					is_focus = 0;
				}
				return false;
			}
			else{
				show_msg("product_size","",0);
			}
		}
		
		if($('#type_size_m').is(":checked") && $('#type_size_l').is(":checked")){
			if(parseInt(type_size_point_m) >= parseInt(type_size_point_l)){
				msg="<?php echo $this->lang->line('point_l_must_be_greater_than_point_m'); ?>";
				show_msg("product_size",msg,1);
				flag=0;
				//check focus
				if(is_focus == 1){
					set_focus("type_size_point_l");
					is_focus = 0;
				}
				return false;
			}
			else{
				show_msg("product_size","",0);
			}
		}
	}
	
	//type_size_width_l <==> img_width
	img_width = parseInt($("#img_width").val().replace(/,/g, ""));
	if(type_size_width_l > img_width){
		msg="<?php echo $this->lang->line('size_l_is_not_larger_than_original'); ?>";
		show_msg("product_size",msg,1);
		flag=0;
		//check focus
		if(is_focus == 1){
			set_focus("type_size_width_l");
			is_focus = 0;
		}
		return false;
	}
	
	if(flag == 1){
		
		f = document.getElementById('f');
		if (f) {
			f.submit();
			$('#btnPostImage').attr('disabled','disabled');
		}
		
		return true;
	}
	else{
		return false;	
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
</script>
<div class="contents-mypage">
    <div class="contents-mypage__wrapper clearfix">
      <div class="breadly">
        <ul>
          <li><a href="<?php echo base_url(); ?>" itemtype="http://data-vocabulary.org/Breadcrumb"><span itemprop="title"><?php echo $this->lang->line('home'); ?></span></a></li>
          <li><a href="<?php echo base_url(); ?>mypage" itemtype="http://data-vocabulary.org/Breadcrumb"><span itemprop="title"><?php echo $this->lang->line('mypage'); ?></span></a></li>
          <li><span itemprop="title"><?php echo $this->lang->line('post_image'); ?></span></li>
        </ul>
      </div>
      <?php $this->load->view('user/sidebar_user.php') ?>
      <div id="post_image" class="mypage-main">
        <h1 class="contents-mypage__find">
			<?php echo $this->lang->line('post_image'); ?>
            <span style="color:#F00;float:right;font-size:12px;">
				<?php
					$the_remaining_allow = $this->lang->line('the_remaining_count_remain_count_allow_photos');
					$num_allow_post_image_explode = explode("/",$num_allow_post_image);
					$count_remain = $num_allow_post_image_explode[0];
					$count_allow = $num_allow_post_image_explode[1];
					$the_remaining_allow = str_replace("{count_remain}",$count_remain,$the_remaining_allow);
					$the_remaining_allow = str_replace("{count_allow}",$count_allow,$the_remaining_allow);
                	echo $the_remaining_allow;
				?>
          	</span>
       	</h1>
        <div class="mypage-form">
          <form name="f" method="post" action="" enctype="multipart/form-data">
            
            <dl>
            
              <!--img_title-->
              <dt><?php echo $this->lang->line('title'); ?><span class="mypage-form__label"><?php echo $this->lang->line('required'); ?></span></dt>
              <dd>
                <input type="text" id="img_title" name="img_title" maxlength="100" value="" placeholder="<?php echo $this->lang->line('please_input_title_image'); ?>">
                <span id="img_title_msg" class="warning" style="display: none;"></span>
              </dd>
              <!--end img_title-->
              
              <!--category-->
              <dt><?php echo $this->lang->line('category'); ?><span class="mypage-form__label"><?php echo $this->lang->line('required'); ?></span></dt>
              <dd id="dd_category">
              	
                <select id="category" name="category" multiple="multiple" style="display: none;">
              	<?php
					foreach($list_product_category as $detail_product_category){
						echo '<option value="'.$detail_product_category->product_category_id.'">'.$detail_product_category->cate_name.'</option>';
					}
				?>
                </select>
                <input type="hidden" id="val_category" name="val_category" value="" />
                <span id="category_msg" class="warning" style="display: none;"></span>
                 
              </dd>
              <!--end category-->
              
              <!--category sub-->
              <dt><?php echo $this->lang->line('category_sub'); ?><span class="mypage-form__label"><?php echo $this->lang->line('required'); ?></span></dt>
              <dd id="dd_category_sub">
                <span id="sp_category_sub">
                <select id="category_sub" name="category_sub" multiple="multiple" style="display: none;">
                </select>
                </span>
                <input type="hidden" id="val_category_sub" name="val_category_sub" value="" />
                <span id="category_sub_msg" class="warning" style="display: none;"></span>
              </dd>
              <!--end category sub-->
              
              <!--file_name_original_img-->
              <dt><?php echo $this->lang->line('image'); ?><span class="mypage-form__label"><?php echo $this->lang->line('required'); ?></span></dt>
              <dd>
                <input type="file" id="file_name_original_img" name="file_name_original_img" value="">
                <input type="hidden" id="file_md5" value=""/>
                <span id="file_name_original_img_msg" class="warning" style="display: none;"></span>
              </dd>
              <!--end category-->
              
              <!--type_image-->
              <dt><?php echo $this->lang->line('type_image'); ?><span class="mypage-form__label"><?php echo $this->lang->line('required'); ?></span></dt>
              <dd>
                <label><input type="radio" id="is_blur" name="type_export_image" value="1">&nbsp;<?php echo $this->lang->line('blur'); ?>&nbsp;</label>
                <label><input type="radio" id="is_watermar" name="type_export_image" value="2" checked="checked">&nbsp;<?php echo $this->lang->line('watermark'); ?>&nbsp;</label>
              </dd>
              <!--end type_image-->
              
         	</dl>
            <input type="hidden" id="img_width" name="img_width" value="" readonly="readonly">
            <input type="hidden" id="img_height" name="img_height" value="" readonly="readonly">
            <!--<div class="form-name">
              <div class="form-name__column">
                <p><?php //echo $this->lang->line('width'); ?></p>
                <input type="hidden" id="img_width" name="img_width" value="" readonly="readonly">
              </div>
              <div class="form-name__column">
                <p><?php //echo $this->lang->line('height'); ?></p>
                <input type="hidden" id="img_height" name="img_height" value="" readonly="readonly">
              </div>
            </div>-->
            
            <dl>
            
              <!--id_color-->
              <!--<dt>Choose color<span class="mypage-form__label"><?php echo $this->lang->line('required'); ?></span></dt>
              <dd>
                <div class="box-color">
                    <ul class="ul-box-color">
                        <?php
							/*foreach($list_product_color as $detail_product_color){
								echo '<li id="color-'.$detail_product_color->product_color_id.'"><label for="color-'.$detail_product_color->name_color.'" style="background:'.$detail_product_color->css_color_style.'">&nbsp;</label><input id="color-'.$detail_product_color->name_color.'" value="'.$detail_product_color->product_color_id.'" type="checkbox"></li>';
							}*/
						?>
                    </ul>
                    <input type="hidden" id="id_color" name="id_color" value="" />
                    <span id="id_color_msg" class="warning" style="display: none;"></span>
                </div>
                
              </dd>-->
              <!--end id_color-->
              
              <!--option_sale-->
              <dt><?php echo $this->lang->line('option_sale'); ?><span class="mypage-form__label"><?php echo $this->lang->line('required'); ?></span></dt>
              <dd>
                <label><input type="radio" name="option_sale" value="1"> 24 <?php echo $this->lang->line('hour'); ?></label>&nbsp;
                <label><input type="radio" name="option_sale" value="2"> 1 <?php echo $this->lang->line('week'); ?></label>&nbsp;
                <label><input type="radio" name="option_sale" value="3"> 1 <?php echo $this->lang->line('month'); ?></label>
                <label><input type="radio" name="option_sale" value="4" checked="checked"> <?php echo $this->lang->line('no_limit'); ?></label>
              </dd>
              <!--end option_sale-->
              
              <!--is_limit-->
              <dt><?php echo $this->lang->line('limit'); ?><span class="mypage-form__label"><?php echo $this->lang->line('required'); ?></span></dt>
              <dd>
                <label><input type="checkbox" id="is_limit" name="is_limit" value="1"> <?php echo $this->lang->line('is_limit'); ?></label>
              </dd>
              <!--end is_limit-->
              
              <!--img_tags-->
              <dt><?php echo $this->lang->line('tags'); ?><span class="mypage-form__label"><?php echo $this->lang->line('required'); ?></span></dt>
              <dd>
                <span id="sp_product_tags">
                <select id="product_tags" name="product_tags[]" data-placeholder="<?php echo $this->lang->line('please_choose_tags_image'); ?>" multiple class="chosen-select-product_tags" tabindex="8">
                	<?php
						foreach($list_product_tags as $detail_product_tags){
							echo "<option>".$detail_product_tags->tag_name."</option>";
						}
					?>
                </select>
                </span>
                <span id="product_tags_msg" class="warning" style="display: none;"></span>
              </dd>
              <!--end img_tags-->
              
              <!--product img size-->
              
              <dt><?php echo $this->lang->line('product_size'); ?><span class="mypage-form__label"><?php echo $this->lang->line('required'); ?></span></dt>
              <dd>
              
              	<div class="form-name">
            		
                  <p style="padding:0;margin:10px 0px 0px 0px;width:100%;float:left"><input style="display:none;" type="checkbox" id="type_size_s" name="type_size_s" value="1"> <?php echo $this->lang->line('size_s'); ?>&nbsp;&nbsp;</p>  
                  <!--img_width-->
                  <div class="form-name__column">
                    <p><?php echo $this->lang->line('unit_w'); ?></p>
                    <input type="text" class="price product-size" id="type_size_width_s" name="type_size_width_s" value="">
                  </div>
                  <!--end img_width-->
                  <!--img_height-->
                  <div class="form-name__column">
                    <p><?php echo $this->lang->line('unit_h'); ?></p>
                    <input type="text" class="price product-size" id="type_size_height_s" name="type_size_height_s" value="">
                  </div>
                  <!--end img_height-->
                  <!--img_width-->
                  <div class="form-name__column">
                    <p><?php echo $this->lang->line('unit_lux'); ?></p>
                    <input type="text" class="price product-size" id="type_size_point_s" name="type_size_point_s" value="">
                  </div>
                  <!--end img_width-->
                  <!--img_height-->
                  <div class="form-name__column">
                    <p><?php echo $this->lang->line('stock'); ?></p>
                    <input type="text" class="price product-size" id="type_size_stock_s" name="type_size_stock_s" value="">
                  </div>
                  <!--end img_height-->
                  
                  <br /><p style="padding:0;margin:10px 0px 0px 0px;width:100%;float:left"><input style="display:none;" type="checkbox" id="type_size_m" name="type_size_m" value="2"> <?php echo $this->lang->line('size_m'); ?>&nbsp;</p>  
                  <!--img_width-->
                  <div class="form-name__column">
                    <p><?php echo $this->lang->line('unit_w'); ?></p>
                    <input type="text" class="price product-size" id="type_size_width_m" name="type_size_width_m" value="">
                  </div>
                  <!--end img_width-->
                  <!--img_height-->
                  <div class="form-name__column">
                    <p><?php echo $this->lang->line('unit_h'); ?></p>
                    <input type="text" class="price product-size" id="type_size_height_m" name="type_size_height_m" value="">
                  </div>
                  <!--end img_height-->
                  <!--img_width-->
                  <div class="form-name__column">
                    <p><?php echo $this->lang->line('unit_lux'); ?></p>
                    <input type="text" class="price product-size" id="type_size_point_m" name="type_size_point_m" value="">
                  </div>
                  <!--end img_width-->
                  <!--img_height-->
                  <div class="form-name__column">
                    <p><?php echo $this->lang->line('stock'); ?></p>
                    <input type="text" class="price product-size" id="type_size_stock_m" name="type_size_stock_m" value="">
                  </div>
                  <!--end img_height-->
                  
                  <br /><p style="padding:0;margin:10px 0px 0px 0px;width:100%;float:left"><input style="display:none;" type="checkbox" id="type_size_l" name="type_size_l" value="3"> <?php echo $this->lang->line('size_l'); ?>&nbsp;&nbsp;</p>  
                  <!--img_width-->
                  <div class="form-name__column">
                    <p><?php echo $this->lang->line('unit_w'); ?></p>
                    <input type="text" class="price product-size" id="type_size_width_l" name="type_size_width_l" value="">
                  </div>
                  <!--end img_width-->
                  <!--img_height-->
                  <div class="form-name__column">
                    <p><?php echo $this->lang->line('unit_h'); ?></p>
                    <input type="text" class="price product-size" id="type_size_height_l" name="type_size_height_l" value="">
                  </div>
                  <!--end img_height-->
                  <!--img_width-->
                  <div class="form-name__column">
                    <p><?php echo $this->lang->line('unit_lux'); ?></p>
                    <input type="text" class="price product-size" id="type_size_point_l" name="type_size_point_l" value="">
                  </div>
                  <!--end img_width-->
                  <!--img_height-->
                  <div class="form-name__column">
                    <p><?php echo $this->lang->line('stock'); ?></p>
                    <input type="text" class="price product-size" id="type_size_stock_l" name="type_size_stock_l" value="">
                  </div>
                  <!--end img_height-->
                  
                </div>
                
                <span id="product_size_msg" class="warning"></span>
                
              </dd>
              <!--end product img size-->
              
         	</dl>
            
            <div class="mypage-form__btn">
              <input id="btnPostImage" name="btnPostImage" type="submit" value="<?php echo $this->lang->line('post_image'); ?>">
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
<!--tokenfield-tag-auto-->
<link href="<?php echo base_url(); ?>publics/tokenfield-tag-auto/jquery-ui.css" type="text/css" rel="stylesheet">
<link href="<?php echo base_url(); ?>publics/tokenfield-tag-auto/tokenfield-typeahead.css" type="text/css" rel="stylesheet">
<link href="<?php echo base_url(); ?>publics/tokenfield-tag-auto/bootstrap-tokenfield.css" type="text/css" rel="stylesheet">
<link href="<?php echo base_url(); ?>publics/tokenfield-tag-auto/pygments-manni.css" type="text/css" rel="stylesheet">
<link href="<?php echo base_url(); ?>publics/tokenfield-tag-auto/docs.css" type="text/css" rel="stylesheet">
<link href="<?php echo base_url(); ?>publics/tokenfield-tag-auto/new_prism.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>publics/tokenfield-tag-auto/new_chosen.css" rel="stylesheet">
<script type="text/javascript" src="<?php echo base_url(); ?>publics/tokenfield-tag-auto/jquery-ui.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>publics/tokenfield-tag-auto/bootstrap-tokenfield.js" charset="UTF-8"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>publics/tokenfield-tag-auto/scrollspy.js" charset="UTF-8"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>publics/tokenfield-tag-auto/affix.js" charset="UTF-8"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>publics/tokenfield-tag-auto/typeahead.bundle.min.js" charset="UTF-8"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>publics/tokenfield-tag-auto/docs.min.js" charset="UTF-8"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>publics/tokenfield-tag-auto/new_chosen.jquery.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>publics/tokenfield-tag-auto/new_prism.js" charset="utf-8"></script>
<!--end tokenfield-tag-auto-->
<script type="text/javascript">
var config = {
  '.chosen-select-product_tags'      	  : {max_selected_options:5},
  '.chosen-select-product_tags-deselect'  : {allow_single_deselect:true},
  '.chosen-select-product_tags-no-single' : {disable_search_threshold:10},
  '.chosen-select-product_tags-no-results': {no_results_text:'<?php echo $this->lang->line('do_not_have_keyword_in_system'); ?>'},
  '.chosen-select-product_tags-width'     : {width:"95%"}
}
for (var selector in config) {
  $(selector).chosen(config[selector]);
}
</script>