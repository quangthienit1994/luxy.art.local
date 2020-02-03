<?php
$CI =& get_instance();
$CI->config->load();
$this->lang->load('dich', 'ja');
?>

<style type="text/css">
.menu-item {
	margin: 10px;
	border: 3px solid #7467ad;
	padding: 5px 10px;
	display: block;
	width: 220px;
	float: left;
	font-weight: bold;
	color: #000;
}
.menu-item:hover {
	background: #7467ad;
	color: #fff;
}
</style>

<a class="menu-item" href="admin/page_list_inbox"><?php echo $this->lang->line('list_inbox'); ?></a>
<a class="menu-item" href="admin/page_list_email_tpl"><?php echo $this->lang->line('list_email_template'); ?></a>
<a class="menu-item" href="admin/page_list_setting"><?php echo $this->lang->line('list_setting'); ?></a>
<a class="menu-item" href="admin/page_list_meta"><?php echo $this->lang->line('list_meta'); ?></a>
<a class="menu-item" href="admin/page_list_category"><?php echo $this->lang->line('list_category'); ?></a>
<a class="menu-item" href="admin/page_list_country"><?php echo $this->lang->line('list_country'); ?></a>
<a class="menu-item" href="admin/page_list_notification"><?php echo $this->lang->line('list_notification'); ?></a>
<a class="menu-item" href="admin/page_list_package"><?php echo $this->lang->line('list_package'); ?></a>
<a class="menu-item" href="admin/page_list_menu"><?php echo $this->lang->line('list_menu'); ?></a>
<a class="menu-item" href="admin/page_change_password_admin"><?php echo $this->lang->line('change_password_admin'); ?></a>
<a class="menu-item" href="admin/page_list_c_agree"><?php echo $this->lang->line('selection_period_setting_for_winning_image'); ?></a>
<a class="menu-item" href="admin/page_list_qa"><?php echo $this->lang->line('list_qa'); ?></a>
<a class="menu-item" href="admin/page_list_tags"><?php echo $this->lang->line('list_tags'); ?></a>
<a class="menu-item" href="admin/give_point"><?php echo $this->lang->line('add_point_to_all_user'); ?></a>
<a class="menu-item" href="admin/page_add_edit_lang?path=application/language/ja&code=2d793ca0cff6d3edd5b7cf5bbfd1f270"><?php echo $this->lang->line('translate'); ?></a>