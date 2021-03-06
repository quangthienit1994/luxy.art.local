<style type="text/css">
	.active-menu {
		font-weight: bold;
		color: #fff !important;
	}
	.not_box_shadow {
		text-shadow: none !important;
	}
</style>
<?php
function get_active_menu($num, $key = null, $key_page) {
	$class = "not_box_shadow bd".$num;
	if($key == $key_page){
		$class .= " bg".$num." active-menu";
	}
	return $class;
}
?>

<li class="<?=get_active_menu(1,'page_main',$key_page)?>">
	<a href="admin"></a>メイン</li>
<li class="<?=get_active_menu(2,'page_list_user',$key_page)?>">
	<a href="admin/page_list_user"></a>ユーザー管理</li>
<li class="<?=get_active_menu(3,'page_list_user_pickup',$key_page)?>">
	<a href="admin/page_list_user_pickup"></a>ピックアップ選択</li>
<li class="<?=get_active_menu(4,'page_list_image',$key_page)?>">
	<a href="admin/page_list_image"></a>登録画像検索</li>
<li class="<?=get_active_menu(5,'page_list_report_image',$key_page)?>">
	<a href="admin/page_list_report_image"></a>画像チェック</li>
<li class="<?=get_active_menu(6,'page_list_order',$key_page)?>">
	<a href="admin/page_list_order"></a>売れた画像</li>
<li class="<?=get_active_menu(7,'page_list_user_order',$key_page)?>">
	<a href="admin/page_list_user_order"></a>売上管理</li>
<li class="<?=get_active_menu(1,'page_list_return_money',$key_page)?>">
	<a href="admin/page_list_return_money"></a>出金依頼管理</li>
<li class="<?=get_active_menu(2,'page_list_competition',$key_page)?>">
	<a href="admin/page_list_competition"></a>コンペ管理</li>
<li class="<?=get_active_menu(4,'page_menu',$key_page)?>">
	<a href="admin/page_menu"></a>各種設定</li>
<!---- menu con nam trong setting -->
<!--
<a href="admin/page_list_lang"></a>翻訳管理
<a href="admin/page_list_inbox"></a>通知メッセージ
<a href="admin/page_list_email_tpl"></a>Emailテンプレート管理
<a href="admin/page_list_setting"></a>各種設
<a href="admin/page_list_meta"></a>メタ情報管理
<a href="admin/page_list_category"></a>カテゴリ管理
<a href="admin/page_list_country"></a>都道府県管理
<a href="admin/page_list_notification"></a>お知らせ管理
<a href="admin/page_list_package"></a>ポイントパック管理
<a href="admin/page_list_menu"></a>メニュー
<a href="admin/page_change_password_admin"></a>パスワード変更
<!---- end menu con nam trong setting -->
<!--<li class="<?=get_active_menu(2,'page_statistic',$key_page)?>">
	<a href="admin/page_statistic"></a>統計</li>-->



<li class="<?=get_active_menu(5,'logout',$key_page)?>">
	<a href="admin/logout"></a>ログアウト</li>

<!-- <li class="<?=get_active_menu(1,$key_page)?>"><a href="admin/page_list_server"></a>画像の保存サーバー選択</li> -->
<!-- <li class="<?=get_active_menu(1,$key_page)?>"><a href="admin/page_list_image_apply"></a>Image apply</li> -->
<!-- <li class="<?=get_active_menu(1,$key_page)?>"><a href="admin/page_list_bookmark_img"></a>Bookmark image</li> -->
<!-- <li class="<?=get_active_menu(1,$key_page)?>"><a href="admin/page_list_user_follow"></a>User follow</li> -->
<!-- <li class="<?=get_active_menu(1,$key_page)?>"><a href="admin/page_list_user_view_img"></a>User view image</li> -->
<!-- <li class="<?=get_active_menu(1,$key_page)?>"><a href="admin/page_list_color"></a>Color</li> -->
<!-- <li class="<?=get_active_menu(1,$key_page)?>"><a href="admin/page_list_notice"></a>Notice list user</li> -->
<!-- <li class="<?=get_active_menu(1,$key_page)?>"><a href="admin/page_list_qa"></a>QA</li> -->
