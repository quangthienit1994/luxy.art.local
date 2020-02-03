  <style type="text/css">

/*Credits: Dynamic Drive CSS Library */
/*URL: http://www.dynamicdrive.com/style/ */
.suckerdiv{
padding:3.8em 1.5em 0 0;
width:190px;
}
.suckerdiv ul{
margin: 0;
padding: 0;
list-style-type: none;
width: 190px; /* Width of Menu Items */
border-bottom: 1px solid #ccc;
text-align:left;
}
.suckerdiv ul li{
position: relative;
}
/*Sub level menu items */
.suckerdiv ul li ul{
position: absolute;
width: 170px; /*sub menu width*/
top: 0;
visibility: hidden;
z-index: 999;
}

/* Sub level menu links style */
.suckerdiv ul li a{
display: block;
overflow: auto; /*force hasLayout in IE7 */
color: black;
text-decoration: none;
background:#ccc;
padding: 5px 5px;
border: 1px solid #999;
border-bottom: 0;

color:#000;
}

.suckerdiv ul li a:visited{
color: black;
}

.suckerdiv ul li a:hover{
background-color: #000;
color:#fff;
}

.suckerdiv .subfolderstyle{
background: url(<?php echo base_url('publics/css/images/arrow-list.gif');?>) no-repeat center right;
background-color:#ccc;
}

	
/* Holly Hack for IE \*/
* html .suckerdiv ul li { float: left; height: 1%; }
* html .suckerdiv ul li a { height: 1%; }
/* End */
ul{border:0; margin:0; padding:0;}

#pagination-digg li.nomarl{
font-weight:bold;
display:block;
float:left;
padding:1px 1px;
color:#FFFFFF;
}
#pagination-digg a{
border:solid 1px #9aafe5
margin-right:2px;
}
#pagination-digg .previous-off,
#pagination-digg .next-off {
border:solid 1px #DEDEDE
color:#888888
display:block;
float:left;
font-weight:bold;
margin-right:2px;
padding:3px 4px;
}
#pagination-digg .next a,
#pagination-digg .previous a {
font-weight:bold;
}
#pagination-digg li.active{
background:#2e6ab1;
color:#FFFFFF;
font-weight:bold;
display:block;
float:left;
padding:4px 6px;
}
#pagination-digg a:link,
#pagination-digg a:visited {
color:#0e509e
display:block;
float:left;
padding:3px 6px;
text-decoration:none;
}
#pagination-digg a:hover{
border:solid 1px #0e509e
}

</style>
<script type="text/javascript">

//SuckerTree Vertical Menu 1.1 (Nov 8th, 06)
//By Dynamic Drive: http://www.dynamicdrive.com/style/

var menuids=["suckertree1"] //Enter id(s) of SuckerTree UL menus, separated by commas

function buildsubmenus(){
for (var i=0; i<menuids.length; i++){
  var ultags=document.getElementById(menuids[i]).getElementsByTagName("ul")
    for (var t=0; t<ultags.length; t++){
    ultags[t].parentNode.getElementsByTagName("a")[0].className="subfolderstyle"
		if (ultags[t].parentNode.parentNode.id==menuids[i]) //if this is a first level submenu
			ultags[t].style.left=ultags[t].parentNode.offsetWidth+"px" //dynamically position first level submenus to be width of main menu item
		else //else if this is a sub level submenu (ul)
		  ultags[t].style.left=ultags[t-1].getElementsByTagName("a")[0].offsetWidth+"px" //position menu to the right of menu item that activated it
    ultags[t].parentNode.onmouseover=function(){
    this.getElementsByTagName("ul")[0].style.display="block"
    }
    ultags[t].parentNode.onmouseout=function(){
    this.getElementsByTagName("ul")[0].style.display="none"
    }
    }
		for (var t=ultags.length-1; t>-1; t--){ //loop through all sub menus again, and use "display:none" to hide menus (to prevent possible page scrollbars
		ultags[t].style.visibility="visible"
		ultags[t].style.display="none"
		}
  }
}

if (window.addEventListener)
window.addEventListener("load", buildsubmenus, false)
else if (window.attachEvent)
window.attachEvent("onload", buildsubmenus)

</script>
<div id="sideBar">
  <div class="sideBar1 clsFloatLeft">
      <div class="suckerdiv">
        <ul id="suckertree1">
          <li><a href="<?php echo base_url('admin'); ?>">Thống kê</a></li>

          <li>
          	<a href="#">Cấu hình Website</a>
            <ul>
                <li><a href="<?php echo base_url('admin'); ?>/list_banner">Banner</a></li>
                <li><a href="<?php echo base_url('admin'); ?>/list_email_templates">Email template</a></li>
                <li><a href="<?php echo base_url('admin'); ?>/list_manager_page">Quản lý trang</a></li>
            	<li><a href="<?php echo base_url('admin'); ?>/change_password">Đổi mật khẩu</a></li>
            	<li><a href="<?php echo base_url('admin'); ?>/change_language">Ngôn ngữ</a></li>
                <li><a href="<?php echo base_url('admin'); ?>/management_download">Quản lý download</a></li>
            </ul>
          </li>

          <li><a href="#">Danh mục</a>
          	<ul>
                <li><a href="<?php echo base_url('admin'); ?>/list_group">Danh sách nhóm danh mục</a></li>
                <li><a href="<?php echo base_url('admin'); ?>/add_group">Thêm nhóm danh mục</a></li>
                <li><a href="<?php echo base_url('admin'); ?>/list_category">Danh sách danh mục</a></li>
                <li><a href="<?php echo base_url('admin'); ?>/add_category">Thêm danh mục</a></li>
             </ul>
          </li>

          <li><a href="#">Tài khoản</a>
            <ul>
              <li><a href="<?php echo base_url('admin'); ?>/list_user">Danh sách tài khoản</a></li>
              <li><a href="<?php echo base_url('admin'); ?>/add_user">Thêm tài khoản</a></li>
			</ul>
          </li>
          
          <li><a href="#">Sản phẩm</a>
            <ul>
              <li><a href="<?php echo base_url('admin'); ?>/list_product">Danh sách sản phẩm</a></li>
              <li><a href="<?php echo base_url('admin'); ?>/add_product">Thêm sản phẩm</a></li>
			</ul>
          </li>

          <li><a href="#">Trường học</a>
            <ul>
              <li><a href="<?php echo base_url('admin'); ?>/list_school">Danh sách trường học</a></li>
              <li><a href="<?php echo base_url('admin'); ?>/add_school">Thêm trường học</a></li>
			</ul>
          </li>

          <li><a href="#">Lớp học</a>
            <ul>
              <li><a href="<?php echo base_url('admin'); ?>/list_class">Danh sách lớp học</a></li>
              <li><a href="<?php echo base_url('admin'); ?>/add_class">Thêm lớp học</a></li>
			</ul>
          </li>

          <li><a href="#">Lớp học thử nghiệm</a>
            <ul>
              <li><a href="<?php echo base_url('admin'); ?>/list_trial_lesson">Danh sách lớp học thử nghiệm</a></li>
              <li><a href="<?php echo base_url('admin'); ?>/add_trial_lesson">Thêm lớp học thử nghiệm</a></li>
			</ul>
          </li>

          <li><a href="#">Tin tức</a>
            <ul>
              <li><a href="<?php echo base_url('admin'); ?>/list_news_type">Loại tin tức</a></li>
              <li><a href="<?php echo base_url('admin'); ?>/add_news_type">Thêm loại tin tức</a></li>
              <li><a href="<?php echo base_url('admin'); ?>/list_news">Tin tức</a></li>
              <li><a href="<?php echo base_url('admin'); ?>/add_news">Thêm tin tức</a></li>
			</ul>
          </li>

          <li><a href="#">Tỉnh/Thành</a>
            <ul>
              <li><a href="<?php echo base_url('admin'); ?>/list_city">Danh sách tỉnh/thành</a></li>
              <li><a href="<?php echo base_url('admin'); ?>/add_city">Thêm tỉnh/thành</a></li>
              <li><a href="<?php echo base_url('admin'); ?>/list_district">Danh sách quận/huyện</a></li>
              <li><a href="<?php echo base_url('admin'); ?>/add_district">Thêm quận/huyện</a></li>
			</ul>
          </li>
          
          <li><a href="#">Gói dịch vụ</a>
            <ul>
              <li><a href="<?php echo base_url('admin'); ?>/list_user_extension">Gói dịch vụ người dùng</a></li>
              <li><a href="<?php echo base_url('admin'); ?>/list_extension_history">Lịch sử gói dịch vụ</a></li>
              <li><a href="<?php echo base_url('admin'); ?>/list_extension">Danh sách gói dịch vụ</a></li>
			</ul>
          </li>

        </ul>
      </div>
  </div>
</div>