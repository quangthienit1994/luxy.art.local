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
    0=>$this->lang->line('user_inactive'),
    1=>$this->lang->line('user_active'),
    2=>$this->lang->line('user_bad'),
    //3=>report,
    4=>$this->lang->line('delete'),
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

<!--paging-->
<style>
.pagination {
    display: inline-block;
}
.pagination a {
    color: black;
    float: left;
    padding: 8px 16px;
    text-decoration: none;
    transition: background-color .3s;
    border: 1px solid #ddd;
    margin: 0 4px;
}
.pagination a.active {
    background-color: #4CAF50;
    color: white;
    border: 1px solid #4CAF50;
}
.pagination a:hover:not(.active) {
	background-color: #ddd;
}
</style>
<!--end paging-->

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

<link rel="stylesheet" href="<?php echo base_url(); ?>publics/css/comment.css">

<style>
.list-pagenav {background-color:#fff!important;}
.txt_666 a{
	color: #004f8b !important;
}
</style>

<script type="text/javascript">

$(document).ready(function() {
	
});

</script>

<section class="content_box">
  <h3><?php echo $this->lang->line('list_comment'); ?></h3>
  <article class="member_edit">
    <h4><?php echo $this->lang->line('list_comment'); ?></h4>

    <form name="f" method="post" action="" enctype="multipart/form-data">
    
  <table class="table table-hover bg-white">
  		
        <div class="mypage-main">
            <!--<h1 class="contents-mypage__find"><?php //echo $this->lang->line('list_detail_image_comment'); ?></h1>-->
            <div class="mypage-list">
              <?php
					if(!empty($list_comment)){
						foreach($list_comment as $detail_comment){
			  ?>
              <div class="mypage-list__list">
                <ul>
                
                
                
                <div class="comment_item width_common">
                    <p class="full_content"><?php echo $detail_comment->comment; ?></p>
                    <div class="user_status width_common" data-user-type="8">
                        <a class="avata_coment" href="<?php echo base_url(); ?>timeline/<?php echo $detail_comment->user_id; ?>.html">
                            <img class="img_avatar" src="<?php echo base_url(); ?>publics/avatar/<?php echo ($detail_comment->user_avatar!="") ? $detail_comment->user_avatar : "prof-img.png" ?>">
                        </a>
                        <span class="left txt_666 txt_14">
                            <a class="nickname txt_666" href="<?php echo base_url(); ?>timeline/<?php echo $detail_comment->user_id; ?>.html" title="Xem trang ý kiến của <?php echo $detail_comment->display_name; ?>" target="_blank"><b><?php echo $detail_comment->display_name; ?></b></a> - <?php echo date('Y-m-d H:i', strtotime($detail_comment->date_create)); ?>
                        </span>
                        <p class="txt_666 right block_like_web">
                            <?php
                                if($detail_comment->status == 1){
                                    echo '<span id="status_'.$detail_comment->comment_id.'"><a class="cursor" onclick="set_status('.$detail_comment->comment_id.',1,1)">'.$this->lang->line('active').'</a></span>';
                                }
                                else{
                                    echo '<span id="status_'.$detail_comment->comment_id.'"><a class="cursor" onclick="set_status('.$detail_comment->comment_id.',1,0)">'.$this->lang->line('inactive').'</a></span>';
                                }
                            ?>&nbsp;
                            <i class="ic ic-caret-down"></i>
                       </p>
                    </div>
                    
                    <?php
                        //$list_comment_sub = $CI->manager_comment_model->get_list_comment_by_level_sub($detail_comment->comment_id)->result();
                        $count_comment_sub = $CI->manager_comment_model->get_list_comment_by_level_sub_paging($detail_comment->comment_id,$user_id)->num_rows();
                        $list_comment_sub = $CI->manager_comment_model->get_list_comment_by_level_sub_paging($detail_comment->comment_id,$user_id,3,0)->result();
                        if(count($count_comment_sub) > 0){
                            foreach($list_comment_sub as $detail_comment_sub){						
                    ?>
                    
                    <div class="div-sub-comment" id="div-sub-comment-<?php echo $detail_comment_sub->comment_id; ?>">
                        <div class="div-sub-comment-left">&nbsp;</div>
                        <div class="div-sub-comment-right">
                            <div class="sub_comment width_common clearfix">
                                <div class="sub_comment_item width_common">
                                    <p class="full_content"><?php echo $detail_comment_sub->comment; ?></p>
                                    <div class="user_status width_common" data-user-type="0">
                                        <span class="left txt_666 txt_14"><a href="<?php echo base_url(); ?>timeline/<?php echo $detail_comment_sub->user_id; ?>.html"><img src="<?php echo base_url(); ?>publics/avatar/<?php echo ($detail_comment_sub->user_avatar!="") ? $detail_comment_sub->user_avatar : "prof-img.png" ?>" alt="Đang tải ảnh đại diện"></a>&nbsp;&nbsp;&nbsp;<a class="nickname txt_666" href="<?php echo base_url(); ?>timeline/<?php echo $detail_comment_sub->user_id; ?>.html" title="Xem trang ý kiến của <?php echo $detail_comment_sub->display_name; ?>" target="_blank"><b><?php echo $detail_comment_sub->display_name; ?></b></a> - <?php echo date('Y-m-d H:i', strtotime($detail_comment_sub->date_create)); ?></span>
                                        <p class="txt_666 right block_like_web">
                                            <?php
                                                if($detail_comment_sub->status == 1){
                                                    echo '<span id="status_'.$detail_comment_sub->comment_id.'"><a class="cursor" onclick="set_status('.$detail_comment_sub->comment_id.',2,1)">'.$this->lang->line('active').'</a></span>';
                                                }
                                                else{
                                                     echo '<span id="status_'.$detail_comment_sub->comment_id.'"><a class="cursor" onclick="set_status('.$detail_comment_sub->comment_id.',2,0)">'.$this->lang->line('inactive').'</a></span>';
                                                }
                                            ?>&nbsp;
                                            <i class="ic ic-caret-down"></i>
                                       </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <?php
                            }
                    ?>
                    
                    <?php
                         if($count_comment_sub > 3){
                    ?>	
                    <div class="txt_view_more width_common">
                        <a id="load_page_sub" class="txt_blue view_all_reply detail-image__more_sub-btn cursor" onclick="loadMoreDataSub(<?php echo $detail_comment->comment_id; ?>,<?php echo $detail_comment_sub->comment_id; ?>);"><?php echo $this->lang->line('view_all_comment'); ?> <?php echo $count_comment_sub; ?> <?php echo $this->lang->line('reply'); ?></a>
                    </div>
                    <?php
                         }
                    ?>    
                        
                    <?php
                        }
                    ?>
                    
                </div>
      
      
      
    </ul>
  </div>
  <?php
        }
  ?>
    <div class="clearfix"></div>
    <div class="mypage-list__pagenav pagination"><?php echo $phantrang;?></div>
  <?php
    }
    else {
  ?>
    <?php echo $this->lang->line('mypage_show_text_no_order'); ?>
  <?php
    }
  ?>
  
</div>
</div>
        
  </table>
  
</form>


</article>
</section>

<script>

function set_status(comment_id,level,status){
	
	id_img = "<?php echo $id_img ?>";
	
	if(status == 0){
		
		$.ajax({
			type: "POST",cache: false,
			url: "<?php echo base_url(); ?>admin/set_status_comment_by_admin",
			data: "comment_id="+comment_id+"&level="+level+"&status="+status+"&id_img="+id_img,
			success: function(data) {
				if(data == 1){
					$('#status_'+comment_id).html('<a class="cursor" onclick="set_status('+comment_id+','+level+',1)"><?php echo $this->lang->line('active'); ?></span>');
				}
			},
			async: false
		})
		
	}
	else if(status == 1){
		
		$.ajax({
			type: "POST",cache: false,
			url: "<?php echo base_url(); ?>admin/set_status_comment_by_admin",
			data: "comment_id="+comment_id+"&level="+level+"&status="+status+"&id_img="+id_img,
			success: function(data) {
				if(data == 1){
					$('#status_'+comment_id).html('<a class="cursor" onclick="set_status('+comment_id+','+level+',0)"><?php echo $this->lang->line('inactive'); ?></span>');
				}
			},
			async: false
		})
		
	}
	
}

function loadMoreDataSub(comment_id, comment_sub_id){
		
	$.ajax(
		{
			url: '<?php echo base_url(); ?>images/manager_comment_sub_load_by_admin?comment_id='+comment_id,
			type: "get",
			beforeSend: function()
			{
				$('.detail-image__more_sub-btn').show();
			}
		})
		.done(function(data)
		{
			if(data == " "){
				$('.detail-image__more_sub-btn').html("No more records found");
				return;
			}
			/*if((page*3)<(count_comment-3))
			{
				$('.detail-image__more_sub-btn').show();
				var page_onlick = page + 1;
				document.getElementById('load_page').setAttribute("onClick","loadMoreData("+page_onlick+");");
			}
			else
			{
				$('.detail-image__more_sub-btn').hide();
			}
			$("#post-data").append(data);*/
			$('.detail-image__more_sub-btn').hide();
			$("#div-sub-comment-"+comment_sub_id).html(data);
		})
		.fail(function(jqXHR, ajaxOptions, thrownError)
		{
			  alert('server not responding...');
	});
	
}

</script>