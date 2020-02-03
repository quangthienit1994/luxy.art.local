<?php
if(!isset($_SESSION)){
	session_start();
}
$CI =& get_instance();
$CI->config->load();
/*if(!isset($_SESSION['lang'])){
	$_SESSION['lang'] = 'ja';
}*/
$config['language'] = "ja";
$ngonngu = $config['language'];
$this->lang->load('dich', $ngonngu);

$return_msg = $_SESSION['return_msg'];
$return_link = $_SESSION['return_link'];
$_SESSION['return_msg'] = "";
$_SESSION['return_link'] = "";

$listImageStatus = [
    0=>"非活動中",
    1=>"アクティブ",
    2=>"悪い",
    //3=>"Report",
    4=>"削除",
];

?>

<style>
.table {
	border:0px !important;
}
.table th, .table td {
	border: 1px !important;
}
.table tr:nth-child(2n){
  background-color:#FFF !important;
}
.table input[type='text']{
  width: 100px !important;
}
.table input[disabled="disabled"] {
    background: #f0f0f0;
    border: none;
}
/*all*/
.warning{
	color:#F00;
}
.red{
	color:#F00;
}
</style>

<script>
	text_none_selected = "<?php echo $this->lang->line('none_selected') ?>";
	text_select_all = "<?php echo $this->lang->line('select_all') ?>";
	text_search = "<?php echo $this->lang->line('search') ?>";
	text_choose = "<?php echo $this->lang->line('choose') ?>";
</script>

<!--multiselect-->
<!--<link rel="stylesheet" href="<?php //echo base_url(); ?>publics/multiselect/css/bootstrap.css">-->
<link rel="stylesheet" href="<?php echo base_url(); ?>publics/multiselect/css/bootstrap-multiselect.css" type="text/css">
<link rel="stylesheet" href="<?php echo base_url(); ?>publics/multiselect/css/prettify.css" type="text/css">
<link rel="stylesheet" href="<?php echo base_url(); ?>publics/multiselect/css/jquerysctipttop.css" type="text/css">

<!--<script type="text/javascript" src="<?php //echo base_url(); ?>publics/multiselect/js/jquery.js"></script>-->
<script type="text/javascript" src="<?php echo base_url(); ?>publics/multiselect/js/bootstrap.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>publics/multiselect/js/bootstrap-multiselect.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>publics/multiselect/js/prettify.js"></script>
<!--<script type="text/javascript" src="<?php //echo base_url(); ?>publics/multiselect/js/show_ads.js"></script>-->
<!--end multiselect-->

<script src="<?php echo base_url(); ?>publics/js/auto-formatting-currency.js" type="text/javascript" charset="utf-8"></script>

<script type="text/javascript" src="<?php echo base_url(); ?>publics/js/browser-md5-file.js"></script>

<!-- Toastr -->
<script type="text/javascript" src="publics/admin/toastr/toastr.min.js"></script>
<script type="text/javascript">
	toastr.options = {
	  "closeButton": true,
	  "debug": true,
	  "newestOnTop": true,
	  "progressBar": true,
	  "positionClass": "toast-top-right",
	  "preventDuplicates": false,
	  "showDuration": "300",
	  "hideDuration": "1000",
	  "timeOut": "3000",
	  "extendedTimeOut": "1000",
	  "showEasing": "swing",
	  "hideEasing": "linear",
	  "showMethod": "fadeIn",
	  "hideMethod": "fadeOut"
	};
	function alertSuccess(message = '', title = '') {
	    if(title == '') title = 'Success !';
	    toastr.success(message, title);
	}

	function alertError(message = '', title = '') {
	    if(title == '') title = 'Error!';
	    toastr.error(message, title);
	}

	function alertWarning(message = '', title = '') {
	    if(title == '') title = 'Warning!';
	    toastr.warning(message, title);
	}

	function alertInfo(message = '', title = '') {
	    if(title == '') title = 'Information!';
	    toastr.info(message, title);
	}

	var return_msg = "<?php echo $return_msg; ?>";
	var return_link = "<?php echo $return_link; ?>";
	if(return_msg != ""){
		alertSuccess(''+return_msg);
		window.location = return_link;		
	}
	
</script>

<script type="text/javascript">

$('.dropdown input, .dropdown label').click(function (event) {
	event.stopPropagation();
});

$(document).ready(function() {
	
	window.prettyPrint() && prettyPrint();
	
	val_root_cate = "<?php echo implode(",", $root_cate);?>";
	val_parent_cate = "<?php echo implode(",", $parent_cate);?>";
	val_sub_cate = "<?php echo implode(",", $sub_cate);?>";
	
	$.ajax({
		type: "POST",cache: false,
		url: "<?php echo base_url(); ?>ajax/get_muti_sub_cate",
		data: "val_category="+val_root_cate+"&val_parent_cate="+val_parent_cate,
		success: function(data) {
			$("#sp_category_sub").html(data);
			$('#category_sub').multiselect({
				includeSelectAllOption: true,
				enableCaseInsensitiveFiltering: true
			});
		},
		async: false
	})
	
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
			},
			async: false
		})
		
	}); 
	
});
</script>
    
<script>

//check error when (upload image > 5 MB) && (width || height < 200)
var _URL = window.URL || window.webkitURL;

//var id_color = 0;
var msg_image = "";
var is_error_upload_image = 0;

var img_width_main = "<?php echo isset($detail_product->img_width) ? $detail_product->img_width : ""; ?>";
var img_height_main = "<?php echo isset($detail_product->img_height) ? $detail_product->img_height : ""; ?>";

var is_limit = "<?php echo isset($detail_product->is_limit) ? $detail_product->is_limit : ""; ?>";

id_img = "<?php echo isset($detail_product->id_img) ? $detail_product->id_img : ""; ?>";
//id_color = "<?php //echo isset($detail_product->id_color) ? $detail_product->id_color : ""; ?>";
	
val_type_size_width_s = "<?php echo isset($list_product_img_size_s->type_size_width_s) ? $list_product_img_size_s->type_size_width_s : ""; ?>";
val_type_size_height_s = "<?php echo isset($list_product_img_size_s->type_size_height_s) ? $list_product_img_size_s->type_size_height_s : ""; ?>"
val_type_size_width_m = "<?php echo isset($list_product_img_size_m->type_size_width_m) ? $list_product_img_size_m->type_size_width_m : ""; ?>"
val_type_size_height_m = "<?php echo isset($list_product_img_size_m->type_size_height_m) ? $list_product_img_size_m->type_size_height_m : ""; ?>"
val_type_size_width_l = "<?php echo isset($list_product_img_size_l->type_size_width_l) ? $list_product_img_size_l->type_size_width_l : ""; ?>"
val_type_size_height_l = "<?php echo isset($list_product_img_size_l->type_size_height_l) ? $list_product_img_size_l->type_size_height_l : ""; ?>"

var min_width_img = "<?php echo $min_width_img; ?>";
var min_height_img = "<?php echo $min_height_img; ?>";
var allowed_image_formats = "<?php echo $allowed_image_formats; ?>";
var max_mb = "<?php echo $max_mb; ?>";

$(document).ready(function() {
	
	set_product_size(0);
	set_is_limit(is_limit);
	
	$("#type_size_width_s").val(val_type_size_width_s);
	$("#type_size_height_s").val(val_type_size_height_s);
	$("#type_size_width_m").val(val_type_size_width_m);
	$("#type_size_height_m").val(val_type_size_height_m);
	$("#type_size_width_l").val(val_type_size_width_l);
	$("#type_size_height_l").val(val_type_size_height_l);
	
	if(val_type_size_width_s != "" && val_type_size_height_s != ""){
		$("#type_size_s").prop("checked", true);
	}
	
	if(val_type_size_width_m != "" && val_type_size_height_m != ""){
		$("#type_size_m").prop("checked", true);
	}
	
	if(val_type_size_width_l != "" && val_type_size_height_l != ""){
		$("#type_size_l").prop("checked", true);
	}
	
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
	  is_error_upload_image = 0;
	  //var file = e.target.files[0];
	  var file = this.files[0];
	  
	  checkExtension("file_name_original_img");
	  displayPreview(file);
	  
	  browserMD5File(file, function (err, md5) {
		console.log(md5); // 97027eb624f85892c69c4bcec8ab0f11
		$("#file_md5").val(md5);
		
		$.ajax({
			type: "POST",cache: false,
			url: "<?php echo base_url(); ?>ajax/check_file_md5_exits",
			data: "file_md5="+md5+"&id_img=id_img"+id_img,
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
				else{
					//show_msg("file_name_original_img","",1);
					//is_error_upload_image = 0;
				}
				
			},
			async: false
		})
		
	  });
	});
	
	//submit
	$("#btnEditImage").click(function(){	
		return validateEditImage();
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
		
		if(width < min_width_img){
			
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
		if(height < min_height_img){
			
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
			is_error_upload_image = 0;
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
		
		is_limit = 1;
		
	}
	
}

function validateEditImage(){
	
	var msg = "";
	var flag = 1;
	var is_focus = 1;
	
	//user_id	
	/*user_id = $("#user_id").val();
	if(user_id == ""){
		msg="ユーザーを選択してください";
		show_msg("user_id",msg,1);
		flag=0;
		//check focus
		if(is_focus == 1){
			set_focus("user_id");
			is_focus = 0;
		}
	}
	else{
		msg="";
		show_msg("user_id",msg,0);
	}*/
	
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
	var allowed_image_formats="png,gif,bmp,jpg,jpeg";	
	var photo=$("#file_name_original_img").val();
	var ph=photo.lastIndexOf('.');
	var ext=photo.substring(ph,photo.length);
	ext=ext.toLowerCase();
	ext=ext.replace(".","");
	var allowd_formats=allowed_image_formats.indexOf(ext);
	
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

<section class="content_box">
  <h3>画像編集</h3>
  <article class="member_edit">
    <h4>情報を編集する</h4>

    <form name="f" method="post" action="" enctype="multipart/form-data">
    
  <table class="table table-hover bg-white">
  
  	  <!--user id-->
      <div class="edit_conts">
        <p>ユーザーを選択 <request></request></p>
        <p></p>
        <p>
           <select id="user_id" name="user_id" class="form-control" disabled="disabled">
           	<option value="">---ユーザーを選択---</option>
            <?php
				foreach($list_user as $detail_user){
					if($detail_user->user_id == $detail_product->user_id){
						echo "<option value='".$detail_user->user_id."' selected='selected'>".$detail_user->user_email."</option>";
					}
					else{
						echo "<option value='".$detail_user->user_id."'>".$detail_user->user_email."</option>";
					}
				}
			?>
           </select>
           <input type="hidden" name="id" value="<?php echo $detail_product->user_id; ?>" />
           <span id="user_id_msg" class="warning" style="display: none;"></span>
        </p>
      </div>
      <!--user id-->
  
  	  <!--img_title-->
  	   <div class="edit_conts">
        <p><?php echo $this->lang->line('title'); ?> <request></request></p>
        <p></p>
        <p>
           <input type="text" id="img_title" name="img_title" value="<?php echo isset($detail_product->img_title) ? $detail_product->img_title : ""; ?>">
           <span id="img_title_msg" class="warning" style="display: none;"></span>
        </p>
      </div>
      <!--end img_title-->
      
      <!--category-->
      <div class="edit_conts">
        <p><?php echo $this->lang->line('category'); ?> <request></request></p>
        <p></p>
        <p id="dd_category">
          <select id="category" name="category" multiple="multiple" style="display: none;">
			<?php
				foreach($list_product_category as $detail_product_category){
					if (in_array($detail_product_category->product_category_id, $root_cate)){
						echo '<option value="'.$detail_product_category->product_category_id.'" selected="selected">'.$detail_product_category->cate_name.'</option>';
					}
					else{
						echo '<option value="'.$detail_product_category->product_category_id.'">'.$detail_product_category->cate_name.'</option>';
					}
				}
			?>
          </select>
            <input type="hidden" id="val_category" name="val_category" value="" />
            <span id="category_msg" class="warning" style="display: none;"></span>
        </p>
      </div>
      <!--end category-->
      
      <!--category sub-->
      <div class="edit_conts">
        <p><?php echo $this->lang->line('category_sub'); ?> <request></request></p>
        <p></p>
        <p id="dd_category_sub">
           <span id="sp_category_sub">
           <select id="category_sub" name="category_sub" multiple="multiple" style="display: none;">
           </select>
           </span>
           <input type="hidden" id="val_category_sub" name="val_category_sub" value="" />
           <span id="category_sub_msg" class="warning" style="display: none;"></span>
        </p>
      </div>
      <!--end category sub-->
      
      <div class="edit_conts">
        <p><?php echo $this->lang->line('image'); ?> <request></request></p>
        <p></p>
        <p>
           <img height="150" src="<?php echo $detail_product->server_path_upload; ?>/<?php echo $detail_product->file_name_original_img; ?>" />
           <input type="file" id="file_name_original_img" name="file_name_original_img" value="">
           <input type="hidden" id="file_md5" value=""/>
           <span id="file_name_original_img_msg" class="warning" style="display: none;"></span>
        </p>
      </div>
      
      <div class="edit_conts">
        <p><?php echo $this->lang->line('type_image'); ?> <request></request></p>
        <p></p>
          <div class="radio-custom mb5">
            <?php
				if($detail_product->option_img_watermark == 1){
					echo '<input type="radio" id="is_blur" name="type_export_image" value="1" checked><label for="is_blur">'.$this->lang->line('blur').'</label>';
					echo '<input type="radio" id="is_watermar" name="type_export_image" value="2"><label for="is_watermar">'.$this->lang->line('watermark').'</label>';
				}
				else if($detail_product->option_img_watermark == 2){
					echo '<input type="radio" id="is_blur" name="type_export_image" value="1"><label for="is_blur">'.$this->lang->line('blur').'</label>';
					echo '<input type="radio" id="is_watermar" name="type_export_image" value="2" checked><label for="is_watermar">'.$this->lang->line('watermark').'</label>';
				}
			?><br />
            <input type="hidden" name="type_export_image_old" value="<?php echo $detail_product->option_img_watermark; ?>"/>
            <img height="150" src="<?php echo $detail_product->server_path_upload; ?>/<?php echo $detail_product->file_name_watermark_img; ?>" />
          </div>
      </div>
      
      <div class="edit_conts">
        <p>サイズ </p>
        <p></p>
        <div class="form-name">
        
          <!--img_width-->
          <div class="form-name__column">
            <p><?php echo $this->lang->line('width'); ?></p>
            <input type="text" id="img_width" name="img_width" value="<?php echo isset($detail_product->img_width) ? str_replace("px", "", $detail_product->img_width) : ""; ?>" readonly="readonly">
          </div>
          <!--end img_width-->
          
          <!--img_height-->
          <div class="form-name__column">
            <p><?php echo $this->lang->line('height'); ?></p>
            <input type="text" id="img_height" name="img_height" value="<?php echo isset($detail_product->img_height) ? str_replace("px", "", $detail_product->img_height) : ""; ?>" readonly="readonly">
          </div>
          <!--end img_height-->

          
        </div>
      </div>
      
      <!--option_sale-->
      <div class="edit_conts">
        <p><?php echo $this->lang->line('option_sale'); ?> <request></request></p>
        <p></p>
           <div class="radio-custom mb5">
            <?php
				if($detail_product->option_sale == 1){
					echo '<input type="radio" id="hour" name="option_sale" value="1" checked><label for="hour">24 '.$this->lang->line('hour').'</label>';
					echo '<input type="radio" id="week" name="option_sale" value="2"><label for="week">1 '.$this->lang->line('week').'</label>';
					echo '<input type="radio" id="month" name="option_sale" value="3"><label for="month">1 '.$this->lang->line('month').'</label>';
					echo '<input type="radio" id="no_limit" name="option_sale" value="4"><label for="no_limit">'.$this->lang->line('no_limit').'</label>';
				}
				else if($detail_product->option_sale == 2){
					echo '<input type="radio" id="hour" name="option_sale" value="1" checked><label for="hour">24 '.$this->lang->line('hour').'</label>';
					echo '<input type="radio" id="week" name="option_sale" value="2" checked><label for="week">1 '.$this->lang->line('week').'</label>';
					echo '<input type="radio" id="month" name="option_sale" value="3"><label for="month">1 '.$this->lang->line('month').'</label>';
					echo '<input type="radio" id="no_limit" name="option_sale" value="4"><label for="no_limit">'.$this->lang->line('no_limit').'</label>';
				}
				else if($detail_product->option_sale == 3){
					echo '<input type="radio" id="hour" name="option_sale" value="1" checked><label for="hour">24 '.$this->lang->line('hour').'</label>';
					echo '<input type="radio" id="week" name="option_sale" value="2"><label for="week">1 '.$this->lang->line('week').'</label>';
					echo '<input type="radio" id="month" name="option_sale" value="3" checked><label for="month">1 '.$this->lang->line('month').'</label>';
					echo '<input type="radio" id="no_limit" name="option_sale" value="4"><label for="no_limit">'.$this->lang->line('no_limit').'</label>';
				}
				else if($detail_product->option_sale == 4){
					echo '<input type="radio" id="hour" name="option_sale" value="1" checked><label for="hour">24 '.$this->lang->line('hour').'</label>';
					echo '<input type="radio" id="week" name="option_sale" value="2"><label for="week">1 '.$this->lang->line('week').'</label>';
					echo '<input type="radio" id="month" name="option_sale" value="3"><label for="month">1 '.$this->lang->line('month').'</label>';
					echo '<input type="radio" id="no_limit" name="option_sale" value="4" checked><label for="no_limit">'.$this->lang->line('no_limit').'</label>';
				}
				else{
					echo '<input type="radio" id="hour" name="option_sale" value="1"><label for="hour">24 '.$this->lang->line('hour').'</label>';
					echo '<input type="radio" id="week" name="option_sale" value="2"><label for="week">1 '.$this->lang->line('week').'</label>';
					echo '<input type="radio" id="month" name="option_sale" value="3"><label for="month">1 '.$this->lang->line('month').'</label>';
					echo '<input type="radio" id="no_limit" name="option_sale" value="4"><label for="no_limit">'.$this->lang->line('no_limit').'</label>';
				}
			?>
          </div>
      </div>
      <!--option_sale-->
      
      <!--is_limit-->
      <div class="edit_conts">
        <p><?php echo $this->lang->line('limit'); ?> <request></request></p>
        <p></p>
        <div class="checkbox-custom mb5">
            <?php
				if($detail_product->is_limit == 0){
					echo '<input type="checkbox" id="is_limit" name="is_limit" value="1"><label for="is_limit">'.$this->lang->line('is_limit').'</label>';
				}
				else if($detail_product->is_limit == 1){
					echo '<input type="checkbox" id="is_limit" name="is_limit" value="1" checked="checked"><label for="is_limit">'.$this->lang->line('is_limit').'</label>';
				}
			?>
        </div>
      </div>
      <!--end is_limit-->
      
      <div class="edit_conts">
        <p><?php echo $this->lang->line('tags'); ?> <request></request></p>
        <p></p>
        <p>
          <select id="product_tags" name="product_tags[]" data-placeholder="<?php echo $this->lang->line('please_choose_tags_image'); ?>" multiple class="chosen-select-product_tags" tabindex="8">
			<?php
				$img_tags = $detail_product->img_tags;
				$img_tags_explode = explode(",", $detail_product->img_tags);
				for($i=0; $i<count($img_tags_explode); $i++){
					echo '<option selected="selected">'.$img_tags_explode[$i].'</option>';
				}
				foreach($list_product_tags_sub as $detail_product_tags_sub){
					echo "<option>".$detail_product_tags_sub->tag_name."</option>";
				}
			?>
        </select>
        <span id="product_tags_msg" class="warning" style="display: none;"></span>
        </p>
      </div>
      
      <div class="edit_conts">
        <p>ステータス <request></request></p>
        <p></p>
        <p class="search_5">
          <select name="img_check_status">
            <option value="">--- 選択してください ---</option>
            <?php foreach ($listImageStatus as $key => $value) { ?>
            <option <?=$detail_product->img_check_status==$key?"selected=''":''?> value="<?=$key?>"><?=$value?></option>
            <?php } ?>
          </select>
        </p>
      </div>
      
      <div class="edit_conts">
        <p>画像サイズ <request></request></p>
        <p></p>
        <p>&nbsp;</p>
      </div>
      
      <table class="table">
        <tbody>
          <tr>
            <td>&nbsp;</td>
            <td><strong>W</strong></td>
            <td><strong>H</strong></td>
            <td><strong><?php echo $this->lang->line('point'); ?></strong></td>
            <td><strong><?php echo $this->lang->line('stock'); ?></strong></td>
          </tr>
          <tr>
            <td>
              <div class="checkbox-custom mb5" style="display:none;">
                <input type="checkbox" id="type_size_s" name="type_size_s" value="1">
              </div>
              <label for="type_size_s"><?php echo $this->lang->line('size_s'); ?></label>
           	</td>
            <td><input type="text" class="price product-size" id="type_size_width_s" name="type_size_width_s" value="<?php echo isset($list_product_img_size_s->type_size_width_s) ? $list_product_img_size_s->type_size_width_s : ""; ?>"></td>
            <td><input type="text" class="price product-size" id="type_size_height_s" name="type_size_height_s" value="<?php echo isset($list_product_img_size_s->type_size_height_s) ? $list_product_img_size_s->type_size_height_s : ""; ?>"></td>
            <td><input type="text" class="price product-size" id="type_size_point_s" name="type_size_point_s" value="<?php echo isset($list_product_img_size_s->price_size_point) ? $list_product_img_size_s->price_size_point : ""; ?>"></td>
            <td><input type="text" class="price product-size" id="type_size_stock_s" name="type_size_stock_s" value="<?php echo isset($list_product_img_size_s->size_stock) ? $list_product_img_size_s->size_stock : ""; ?>"></td>
          </tr>
          <tr>
            <td>
              <div class="checkbox-custom mb5" style="display:none;">
                <input type="checkbox" id="type_size_m" name="type_size_m" value="2">
              </div>
              <label for="type_size_m"><?php echo $this->lang->line('size_m'); ?></label>
            </td>
            <td><input type="text" class="price product-size" id="type_size_width_m" name="type_size_width_m" value="<?php echo isset($list_product_img_size_m->type_size_width_m) ? $list_product_img_size_m->type_size_width_m : ""; ?>"></td>
            <td><input type="text" class="price product-size" id="type_size_height_m" name="type_size_height_m" value="<?php echo isset($list_product_img_size_m->type_size_height_m) ? $list_product_img_size_m->type_size_height_m : ""; ?>"></td>
            <td><input type="text" class="price product-size" id="type_size_point_m" name="type_size_point_m" value="<?php echo isset($list_product_img_size_m->price_size_point) ? $list_product_img_size_m->price_size_point : ""; ?>"></td>
            <td><input type="text" class="price product-size" id="type_size_stock_m" name="type_size_stock_m" value="<?php echo isset($list_product_img_size_m->size_stock) ? $list_product_img_size_m->size_stock : ""; ?>"></td>
          </tr>
          <tr>
            <td>
              <div class="checkbox-custom mb5" style="display:none;">
                <input type="checkbox" id="type_size_l" name="type_size_l" value="3">
              </div>
              <label for="type_size_l"><?php echo $this->lang->line('size_l'); ?></label>
          	</td>
            <td><input type="text" class="price product-size" id="type_size_width_l" name="type_size_width_l" value="<?php echo isset($list_product_img_size_l->type_size_width_l) ? $list_product_img_size_l->type_size_width_l : ""; ?>"></td>
            <td><input type="text" class="price product-size" id="type_size_height_l" name="type_size_height_l" value="<?php echo isset($list_product_img_size_l->type_size_height_l) ? $list_product_img_size_l->type_size_height_l : ""; ?>"></td>
            <td><input type="text" class="price product-size" id="type_size_point_l" name="type_size_point_l" value="<?php echo isset($list_product_img_size_l->price_size_point) ? $list_product_img_size_l->price_size_point : ""; ?>"></td>
            <td><input type="text" class="price product-size" id="type_size_stock_l" name="type_size_stock_l" value="<?php echo isset($list_product_img_size_l->size_stock) ? $list_product_img_size_l->size_stock : ""; ?>"></td>
          </tr>
        </tbody>
      </table>
      <span id="product_size_msg" class="warning"></span>
      
      <div style="clear:both;">&nbsp;</div>
      
      <div class="edit_button">
        <p></p>
        <p><input id="btnEditImage" name="btnEditImage" class="btn btn-primary" type="submit" value="<?php echo $this->lang->line('edit_image'); ?>"></p>
      </div>
  </table>
</form>


</article>
</section>

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
  '.chosen-select-product_tags-no-results': {no_results_text:'<?php echo $this->lang->line('each'); ?>do_not_have_keyword_in_system'},
  '.chosen-select-product_tags-width'     : {width:"95%"}
}
for (var selector in config) {
  $(selector).chosen(config[selector]);
}
</script>