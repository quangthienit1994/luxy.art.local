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
</style>

<!--multiselect-->
<link rel="stylesheet" href="<?php echo base_url(); ?>publics/multiselect/css/bootstrap.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>publics/multiselect/css/bootstrap-multiselect.css" type="text/css">
<link rel="stylesheet" href="<?php echo base_url(); ?>publics/multiselect/css/prettify.css" type="text/css">
<link rel="stylesheet" href="<?php echo base_url(); ?>publics/multiselect/css/jquerysctipttop.css" type="text/css">

<script type="text/javascript" src="<?php echo base_url(); ?>publics/multiselect/js/jquery.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>publics/multiselect/js/bootstrap.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>publics/multiselect/js/bootstrap-multiselect.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>publics/multiselect/js/prettify.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>publics/multiselect/js/show_ads.js"></script>
<!--end multiselect-->

<script src="<?php echo base_url(); ?>publics/js/auto-formatting-currency.js" type="text/javascript" charset="utf-8"></script>

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
		alert("val_category = " + val_category);
		
		$.ajax({
			type: "POST",cache: false,
			url: "ajax/get_muti_sub_category",
			data: "val_category="+val_category,
			success: function(data) {
				$("#sp_category_sub").html(data);
				$('#category_sub').multiselect({
					includeSelectAllOption: true,
					enableCaseInsensitiveFiltering: true
				});
			},
			async: false
		})
		
	}); 
	
});
</script>
    
<script>

//check error when (upload image > 5 MB) && (width || height < 200)
var _URL = window.URL || window.webkitURL;

var id_color = 0;
var msg_image = "";
var is_error_upload_image = 0;

$(document).ready(function() {
	
	set_product_size(1);
	
	//color
	$(".ul-box-color li").click(function(){
    
		var index = $(this).index();
		var id = $('.ul-box-color li').get(index).id;
		
		$(".ul-box-color li label").removeClass("label-select");
		$(".ul-box-color li label:eq(" + index + ")").addClass("label-select");
		
		id_color = id.replace("color-", "");
		$("#id_color").val(id_color);
		
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
		$("#type_size_height_s").val(Math.round(height_s));
		
	});
	
	$("#type_size_height_s").keyup(function() {
		
		img_width = $("#img_width").val();
		img_height = $("#img_height").val();
		
		height_s = $("#type_size_height_s").val();
		height_s = height_s.replace(/,/g, "");
		
		width_s = img_width * height_s / img_height;
		$("#type_size_width_s").val(Math.round(width_s));
		
	});
	
	$("#type_size_width_m").keyup(function() {
		
		img_width = $("#img_width").val();
		img_height = $("#img_height").val();
		
		width_m = $("#type_size_width_m").val();
		width_m = width_m.replace(/,/g, "");
		
		height_m = img_height * width_m / img_width;
		$("#type_size_height_m").val(Math.round(height_m));
		
	});
	
	$("#type_size_height_m").keyup(function() {
		
		img_width = $("#img_width").val();
		img_height = $("#img_height").val();
		
		height_m = $("#type_size_height_m").val();
		height_m = height_m.replace(/,/g, "");
		
		width_m = img_width * height_m / img_height;
		$("#type_size_width_m").val(Math.round(width_m));
		
	});
	
	$("#type_size_width_l").keyup(function() {
		
		img_width = $("#img_width").val();
		img_height = $("#img_height").val();
		
		width_l = $("#type_size_width_l").val();
		width_l = width_l.replace(/,/g, "");
		
		height_l = img_height * width_l / img_width;
		$("#type_size_height_l").val(Math.round(height_l));
		
	});
	
	$("#type_size_height_l").keyup(function() {
		
		img_width = $("#img_width").val();
		img_height = $("#img_height").val();
		
		height_l = $("#type_size_height_l").val();
		height_l = height_l.replace(/,/g, "");
		
		width_l = img_width * height_l / img_height;
		$("#type_size_width_l").val(Math.round(width_l));
		
	});
	
	//image
	$("#file_name_original_img").change(function () {
		var file = this.files[0];
		checkExtension("file_name_original_img");
		displayPreview(file);
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
			msg_image="Hình ảnh không đúng định dạng";
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
	is_error_upload_image = 0;
	
	$("#img_width").val(width);
	$("#img_height").val(height);
	
	if(is_error_upload_image == 0){
		
		if(size > 1024 * 20){
			
			msg_image="Hình ảnh vượt quá 20 MB cho phép";
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
		
		if(width < 200 || height < 200){
			
			msg_image="Chiều cao và rộng tối thiểu 200px";
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

function validatePostImage(){
	
	var msg = "";
	var flag = 1;
	var is_focus = 1;
	
	//img_title	
	img_title = $("#img_title").val();
	if(img_title == ""){
		msg="Vui lòng nhập tiêu đề hình ảnh";
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
		msg="Vui lòng chọn phân loại (cấp cha) hình ảnh";
		show_msg("category",msg,1);
		flag=0;
		//check focus
		if(is_focus == 1){
			set_focus("category");
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
		msg="Vui lòng chọn phân loại (cấp con) hình ảnh";
		show_msg("category_sub",msg,1);
		flag=0;
		//check focus
		if(is_focus == 1){
			set_focus("category_sub");
			is_focus = 0;
		}
	}
	else{
		msg="";
		show_msg("category_sub",msg,0);
		$("#val_category_sub").val(category_sub);
	}
	
	//file_name_original_img
	var allowed_image_formats="png,gif,bmp,jpg,jpeg";	
	var photo=$("#file_name_original_img").val();
	var ph=photo.lastIndexOf('.');
	var ext=photo.substring(ph,photo.length);
	ext=ext.toLowerCase();
	ext=ext.replace(".","");
	var allowd_formats=allowed_image_formats.indexOf(ext);
	
	if(ext==""){
		msg="Vui lòng chọn hình ảnh";
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
	if(id_color == 0){
		msg="Vui lòng chọn màu chủ đạo ảnh";
		show_msg("id_color",msg,1);
		flag=0;
	}
	else{
		msg="";
		show_msg("id_color",msg,0);
	}
	
	//product_tags
	product_tags = $("#product_tags").val();
	if(product_tags == null){
		msg="Vui lòng nhập từ khóa hình ảnh";
		show_msg("product_tags",msg,1);
		flag=0;
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
		msg="Vui lòng chọn kích cỡ hình ảnh";
		show_msg("product_size",msg,1);
		flag=0;
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
		
		if($('#type_size_s').is(":checked")){
			if(type_size_width_s == ""){
				msg="Vui lòng nhập chiều dài hình ảnh kích thước S";
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
				msg="Vui lòng nhập chiều rộng hình ảnh kích thước S";
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
				msg="Vui lòng nhập điểm hình ảnh kích thước S";
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
				msg="Vui lòng nhập số lượng ảnh bán hình ảnh kích thước S";
				show_msg("product_size",msg,1);
				flag=0;
				//check focus
				if(is_focus == 1){
					set_focus("type_size_stock_s");
					is_focus = 0;
				}
				return false;
			}
			else{
				show_msg("product_size","",0);
			}
		}
		if($('#type_size_m').is(":checked")){
			if(type_size_width_m == ""){
				msg="Vui lòng nhập chiều dài hình ảnh kích thước M";
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
				msg="Vui lòng nhập chiều rộng hình ảnh kích thước M";
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
				msg="Vui lòng nhập điểm hình ảnh kích thước M";
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
				msg="Vui lòng nhập số lượng ảnh bán hình ảnh kích thước M";
				show_msg("product_size",msg,1);
				flag=0;
				//check focus
				if(is_focus == 1){
					set_focus("type_size_stock_m");
					is_focus = 0;
				}
				return false;
			}
			else{
				show_msg("product_size","",0);
			}
		}
		if($('#type_size_l').is(":checked")){
			if(type_size_width_l == ""){
				msg="Vui lòng nhập chiều dài hình ảnh kích thước L";
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
				msg="Vui lòng nhập chiều rộng hình ảnh kích thước L";
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
				msg="Vui lòng nhập điểm hình ảnh kích thước L";
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
				msg="Vui lòng nhập số lượng ảnh bán hình ảnh kích thước L";
				show_msg("product_size",msg,1);
				flag=0;
				//check focus
				if(is_focus == 1){
					set_focus("type_size_stock_l");
					is_focus = 0;
				}
				return false;
			}
			else{
				show_msg("product_size","",0);
			}
		}
		
		//price
		if($('#type_size_s').is(":checked") && $('#type_size_m').is(":checked")){
			
			if(parseInt(type_size_width_s) >= parseInt(type_size_width_m)){
				msg="Kích thước S với M không đúng";
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
				msg="Kích thước S với L không đúng";
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
				msg="Kích thước M với L không đúng";
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
				msg="Kích thước S với M không đúng";
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
				msg="Kích thước M với L không đúng";
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
		
	}
	
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
          <li><a href="index.html" itemtype="http://data-vocabulary.org/Breadcrumb"><span itemprop="title">ホーム</span></a></li>
          <li><a href="mypage.html" itemtype="http://data-vocabulary.org/Breadcrumb"><span itemprop="title">マイページ</span></a></li>
          <li><span itemprop="title">登録情報の変更</span></li>
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
        <h1 class="contents-mypage__find">Post Image</h1>
        <div class="mypage-form">
          <form name="f" method="post" action="<?php echo base_url(); ?>post-image" enctype="multipart/form-data">
            
            <dl>
            
              <!--img_title-->
              <dt>Title<span class="mypage-form__label">必須</span></dt>
              <dd>
                <input type="text" id="img_title" name="img_title" value="">
                <span id="img_title_msg" class="warning" style="display: none;"></span>
              </dd>
              <!--end img_title-->
              
              <!--category-->
              <dt>Category<span class="mypage-form__label">必須</span></dt>
              <dd>
              	
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
              <dt>Category sub<span class="mypage-form__label">必須</span></dt>
              <dd>
                <span id="sp_category_sub">
                <select id="category_sub" name="category_sub" multiple="multiple" style="display: none;">
                </select>
                </span>
                <input type="hidden" id="val_category_sub" name="val_category_sub" value="" />
                <span id="category_sub_msg" class="warning" style="display: none;"></span>
              </dd>
              <!--end category sub-->
              
              <!--file_name_original_img-->
              <dt>Image<span class="mypage-form__label">必須</span></dt>
              <dd>
                <input type="file" id="file_name_original_img" name="file_name_original_img" value="">
                <span id="file_name_original_img_msg" class="warning" style="display: none;"></span>
              </dd>
              <!--end category-->
              
              <!--type_image-->
              <dt>Type Image<span class="mypage-form__label">必須</span></dt>
              <dd>
                <label><input type="radio" id="is_watermark" name="is_export_image" value="1" checked="checked">&nbsp;Watermark</label>
                <label><input type="radio" id="is_blur" name="is_export_image" value="2">&nbsp;Blur</label>
              </dd>
              <!--end type_image-->
              
         	</dl>
            
            <div class="form-name">
            
              <!--img_width-->
              <div class="form-name__column">
                <p>Width</p>
                <input type="text" id="img_width" name="img_width" value="" readonly="readonly">
              </div>
              <!--end img_width-->
              
              <!--img_height-->
              <div class="form-name__column">
                <p>Height</p>
                <input type="text" id="img_height" name="img_height" value="" readonly="readonly">
              </div>
              <!--end img_height-->
              
            </div>
            
            <dl>
            
              <!--id_color-->
              <dt>Choose color<span class="mypage-form__label">必須</span></dt>
              <dd>
                <div class="box-color">
                    <ul class="ul-box-color">
                        <?php
							foreach($list_product_color as $detail_product_color){
								echo '<li id="color-'.$detail_product_color->id_color.'"><label for="color-'.$detail_product_color->name_color.'" style="background:'.$detail_product_color->css_color_style.'">&nbsp;</label><input id="color-'.$detail_product_color->name_color.'" value="'.$detail_product_color->id_color.'" type="checkbox"></li>';
							}
						?>
                    </ul>
                    <input type="hidden" id="id_color" name="id_color" value="" />
                    <span id="id_color_msg" class="warning" style="display: none;"></span>
                </div>
                
              </dd>
              <!--end id_color-->
              
              <!--option_sale-->
              <dt>Option sale<span class="mypage-form__label">必須</span></dt>
              <dd>
                <label><input type="radio" name="option_sale" value="1" checked="checked"> 24 hour</label>&nbsp;
                <label><input type="radio" name="option_sale" value="2"> 1 week</label>&nbsp;
                <label><input type="radio" name="option_sale" value="3"> 1 month</label>
              </dd>
              <!--end option_sale-->
              
              <!--is_limit-->
              <dt>Limit<span class="mypage-form__label">必須</span></dt>
              <dd>
                <label><input type="checkbox" name="is_limit" value="1"> Is limit</label>
              </dd>
              <!--end is_limit-->
              
              <!--img_tags-->
              <dt>Tags<span class="mypage-form__label">必須</span></dt>
              <dd>
                <select id="product_tags" name="product_tags[]" data-placeholder="Chọn từ khóa hình ảnh" multiple class="chosen-select-product_tags" tabindex="8">
                	<?php
						foreach($list_product_tags as $detail_product_tags){
							echo "<option>".$detail_product_tags->tag_name."</option>";
						}
					?>
                </select>
                <span id="product_tags_msg" class="warning" style="display: none;"></span>
              </dd>
              <!--end img_tags-->
              
              <!--product img size-->
              
              <dt>Product Size<span class="mypage-form__label">必須</span></dt>
              <dd>
              
              	<div class="form-name">
            		
                  <label style="padding:0 10px 0 0;"><input type="checkbox" id="type_size_s" name="type_size_s" value="1"> S&nbsp;&nbsp;</label>  
                  <!--img_width-->
                  <div class="form-name__column">
                    <p>W</p>
                    <input type="text" class="price product-size" id="type_size_width_s" name="type_size_width_s" value="">
                  </div>
                  <!--end img_width-->
                  <!--img_height-->
                  <div class="form-name__column">
                    <p>H</p>
                    <input type="text" class="price product-size" id="type_size_height_s" name="type_size_height_s" value="">
                  </div>
                  <!--end img_height-->
                  <!--img_width-->
                  <div class="form-name__column">
                    <p>Point</p>
                    <input type="text" class="price product-size" id="type_size_point_s" name="type_size_point_s" value="">
                  </div>
                  <!--end img_width-->
                  <!--img_height-->
                  <div class="form-name__column">
                    <p>Stock</p>
                    <input type="text" class="price product-size" id="type_size_stock_s" name="type_size_stock_s" value="">
                  </div>
                  <!--end img_height-->
                  
                  <br /><label style="padding:0 10px 0 0;"><input type="checkbox" id="type_size_m" name="type_size_m" value="2"> S&nbsp;&nbsp;</label>  
                  <!--img_width-->
                  <div class="form-name__column">
                    <p>W</p>
                    <input type="text" class="price product-size" id="type_size_width_m" name="type_size_width_m" value="">
                  </div>
                  <!--end img_width-->
                  <!--img_height-->
                  <div class="form-name__column">
                    <p>H</p>
                    <input type="text" class="price product-size" id="type_size_height_m" name="type_size_height_m" value="">
                  </div>
                  <!--end img_height-->
                  <!--img_width-->
                  <div class="form-name__column">
                    <p>Point</p>
                    <input type="text" class="price product-size" id="type_size_point_m" name="type_size_point_m" value="">
                  </div>
                  <!--end img_width-->
                  <!--img_height-->
                  <div class="form-name__column">
                    <p>Stock</p>
                    <input type="text" class="price product-size" id="type_size_stock_m" name="type_size_stock_m" value="">
                  </div>
                  <!--end img_height-->
                  
                  <br /><label style="padding:0 10px 0 0;"><input type="checkbox" id="type_size_l" name="type_size_l" value="3"> S&nbsp;&nbsp;</label>  
                  <!--img_width-->
                  <div class="form-name__column">
                    <p>W</p>
                    <input type="text" class="price product-size" id="type_size_width_l" name="type_size_width_l" value="">
                  </div>
                  <!--end img_width-->
                  <!--img_height-->
                  <div class="form-name__column">
                    <p>H</p>
                    <input type="text" class="price product-size" id="type_size_height_l" name="type_size_height_l" value="">
                  </div>
                  <!--end img_height-->
                  <!--img_width-->
                  <div class="form-name__column">
                    <p>Point</p>
                    <input type="text" class="price product-size" id="type_size_point_l" name="type_size_point_l" value="">
                  </div>
                  <!--end img_width-->
                  <!--img_height-->
                  <div class="form-name__column">
                    <p>Stock</p>
                    <input type="text" class="price product-size" id="type_size_stock_l" name="type_size_stock_l" value="">
                  </div>
                  <!--end img_height-->
                  
                </div>
                
                <span id="product_size_msg" class="warning"></span>
                
              </dd>
              <!--end product img size-->
              
         	</dl>
            
            <div class="mypage-form__btn">
              <input id="btnPostImage" name="btnPostImage" type="submit" value="Post Image">
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
  '.chosen-select-product_tags-no-results': {no_results_text:'Không có từ khóa trong hệ thống'},
  '.chosen-select-product_tags-width'     : {width:"95%"}
}
for (var selector in config) {
  $(selector).chosen(config[selector]);
}
</script>