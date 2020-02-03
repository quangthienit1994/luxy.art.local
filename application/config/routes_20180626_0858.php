<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
*
* Load menu header page
*
*/
$route['default_controller'] 										= "default_index";
// Langugage
$route['change_language']                                           = 'user/change_language';
// Category
$route['category']                                                  = 'default_index/page_category';
// Category theo ID		
$route['category/(:num)']											= 'default_index/page_category_theo_id/$1';
$route['category/(:num)/(:num)']									= 'default_index/page_category_theo_id/$1/$2';
$route['category/(:num)/page/(:num)'] = 'default_index/page_category_theo_id/$1/$2';
//$route['category/(:num)/(:num)/(:any)']								= 'default_index/page_category_theo_id/$1/$2/$3';


// 1. Dang ky thanh vien
// - page dang ky
$route['entry']                                                 	= 'user/dangky';
$route['creator-entry']                                             = 'user/dangky_creator';
// - kiem tra email
$route['check-email-register']                                      = 'user/check_email_register';
// - call ajax for register
$route['do-ajax-register-user']										= 'user/do_ajax_register_user';
$route['do-ajax-register-user-creator']								= 'user/do_ajax_register_user_creator';
// - register ok -> return notice
$route['register-success']											= 'user/notice_register_success';
$route['creator-register-success']									= 'user/notice_register_creator_success';
// login normal
$route['login']                                                		= 'user/login';
// lougout user
$route['logout']                                                	= 'user/logout';
// reset password
$route['remind']                                                	= 'user/remind';
// send email active
$route['activation']                                                = 'user/activation';
// - call ajax user reset password
$route['do-ajax-reset-password']									= 'user/do_ajax_reset_password';
// reset ok -> return notice
$route['remind-success']                                            = 'user/remind_success';
$route['activation-success']                                        = 'user/activation_success';
$route['notice']                                            		= 'user/notice';
// active email
$route['active-email/(:any)']										= 'user/active_email/$1';
$route['active-email-after-changed/(:any)']							= 'user/active_email_after_change/$1';
// 2 My Page
$route['mypage']	                                            	= 'user/mypage';
// History view image
$route['mypage/history-view-image']                                 = 'user/fn_history_user_view_img';
$route['mypage/history-view-image/(:num)']                          = 'user/fn_history_user_view_img/$1';
// Bookmark image list
$route['mypage/favorite-image']                                 	= 'user/fn_favorite_image';
$route['mypage/favorite-image/(:num)']                              = 'user/fn_favorite_image/$1';
// Bookmark image list
$route['mypage/favorite-user']                                 		= 'user/fn_favorite_user';
$route['mypage/favorite-user/(:num)']                              = 'user/fn_favorite_user/$1';
// Mypage order list
$route['mypage/list-order']                                 		= 'user/fn_list_order';
$route['mypage/list-order/(:num)']                              	= 'user/fn_list_order/$1';
// Mypage comment list + comment detail
$route['mypage/list-comment']                                 		= 'images/list_comment';
$route['mypage/list-comment/(:num)']                              	= 'images/list_comment/$1';
$route['mypage/detail-comment/(:num)/(:num)']                       = 'images/detail_comment/$1/$2';
$route['mypage/detail-comment/(:num)']                              = 'images/detail_comment/$1';
$route['mypage/detail-comment-test/(:num)']                         = 'images/detail_comment_test/$1';
$route['mypage/detail-comment-test/(:num)/(:num)']                  = 'images/detail_comment_test/$1/$2';
// Mypage config
$route['mypage/user-config']                                 		= 'user/user_config';
// Mypage user change email
$route['mypage/user-email']                                 		= 'user/user_email';
$route['mypage/user-email-comp']                                 	= 'user/user_email_comp';
// Mypage change password
$route['mypage/user-password']                                 		= 'user/user_pass';
$route['mypage/user-password-comp']                                 = 'user/user_pass_comp';
// - kiem tra pass for leave
$route['check-password-leave']                                      = 'user/check_password_leave';
$route['do-user-leave']                                      		= 'user/do_user_leave';
$route['notice-user-leave']											= 'user/user_leave_comp';
// User order point
$route['credit']													= 'user/credit';
$route['credit-select/(:num)']										= 'user/credit_select/$1';
// list user manager point
$route['mypage/user-manager-point']									= 'user/user_manager_point';
$route['mypage/export-money']										= 'user/user_export_money';
$route['mypage/user-change-bank-account']							= 'user/user_change_bank_account';
$route['mypage/user-manager-card']									= 'user/user_manager_card';
$route['mypage/user-edit-card/(:num)']								= 'user/user_edit_card/$1';
// history order point
$route['mypage/user-purchase-order']								= 'user/user_purchase_order';
$route['mypage/user-purchase-order/(:num)']							= 'user/user_purchase_order/$1';
// User notice
$route['mypage/user-message']										= 'user/user_message';
$route['mypage/user-message/(:num)']								= 'user/user_message/$1';
// Detail message
$route['mypage/user-message-detail/(:num)']							= 'user/user_message_detail/$1';
// Mypage leave
$route['mypage/user-leave']                                 		= 'user/user_leave';
$route['mypage/user-leave-comp']                                 	= 'user/user_leave_comp';
// user update account
$route['mypage/user-update-creator']                          		= 'user/user_update_creator';
$route['mypage/user-update-gold']                          			= 'user/user_update_gold';
$route['mypage/user-update-platinum']                          		= 'user/user_update_platinum';
$route['mypage/user-update-enterprise']                          	= 'user/user_update_enterprise';
$route['mypage/user-update-account']                          		= 'user/user_update_account';
// Notice update level ok
$route['mypage/notice-user-update-level/(:any)']                    = 'user/notice_user_update_level/$1';
// User manager adword
$route['mypage/user-manager-adwords']                          		= 'user/user_manager_adwords';
// Download img
$route['download-img-order']                                		= 'user/do_ajax_download_img';
$route['download-img-order/(:any)']                                	= 'user/do_ajax_download_img/$1';
// Image
$route['mypage/list-images']                                        = 'images/list_images';
$route['mypage/list-images/(:num)']                                 = 'images/list_images/$1';
$route['mypage/list-images-test']                                   = 'images/list_images_test';
$route['mypage/list-images-test/(:num)']                            = 'images/list_images_test/$1';
$route['mypage/list-images-choose']                                 = 'images/list_images_choose';
$route['mypage/list-images-choose/(:num)']                          = 'images/list_images_choose/$1';
$route['mypage/post-image']                                         = 'images/post_image';
$route['mypage/post-image-test']                                    = 'images/post_image_test';
$route['mypage/edit-image/(:num)']                                  = 'images/edit_image/$1';
$route['mypage/edit-image-test/(:num)']                             = 'images/edit_image_test/$1';
//Cart
$route['cart']                                         				= 'images/cart';
$route['carttest']                                         			= 'images/cart_test';
// Detail image
//$route['detail/(:num)']                                  			= 'images/detail_image/$1';
$route['detail/(:any)']                                  			= 'images/detail_image/$1';
$route['detailtest/(:any)']                                  		= 'images/detail_image_test/$1';
//Compe
$route['mypage/list-compe']											= 'compe/mypage_list_compe';
$route['mypage/list-compe/(:num)']									= 'compe/mypage_list_compe/$1';
$route['mypage/list-compe-join']									= 'compe/mypage_list_compe_join';
$route['mypage/list-compe-join/(:num)']								= 'compe/mypage_list_compe_join/$1';
$route['mypage/post-compe']											= 'compe/post_compe';
$route['mypage/post-compe-test']									= 'compe/post_compe_test';
$route['mypage/edit-compe/(:num)']									= 'compe/edit_compe/$1';	
$route['mypage/mypage-compe']										= 'compe/mypage_compe';
$route['compe-detail/(:num)']										= 'compe/compe_detail/$1';
$route['list-compe']												= 'compe/list_compe';
$route['list-compe/(:num)']											= 'compe/list_compe/$1';
$route['list-compe-test']											= 'compe/list_compe_test';
$route['list-compe-test/(:num)']									= 'compe/list_compe_test/$1';
$route['compe-check']												= 'compe/compe_check';
$route['compe-check/(:num)']										= 'compe/compe_check/$1';
$route['compe-check/(:num)/(:num)']									= 'compe/compe_check/$1/$2';
$route['compe-check-conf/(:num)']									= 'compe/compe_check_conf/$1';
$route['compe-post-list']											= 'compe/compe_post_list';
$route['compe-post-list/(:num)']									= 'compe/compe_post_list/$1';
$route['compe-check-comp']											= 'compe/compe_check_comp';
// Another page
$route['page/(:any)']												= 'user/static_page/$1';
$route['contact']													= 'user/page_contact';
$route['contact-complete']											= 'user/contact_complete';

// Autorun
$route['autorun/compe-expired']										= 'ajax/autorun_compe_expired';
$route['extend-compe/(:any)']										= 'ajax/extend_compe/$1';
$route['autorun/compe-finish']										= 'ajax/autorun_compe_finish';
// Invite
$route['invite/(:any)']												= 'images/invite/$1';
// User time line
$route['timeline/(:num)']											= 'user/user_timeline/$1';
$route['timeline/(:num)/(:num)']									= 'user/user_timeline/$1/$2';
$route['timeline_test/(:num)']										= 'user/user_timeline_test/$1';
$route['timeline_test/(:num)/(:num)']								= 'user/user_timeline_test/$1/$2';
$route['search']													= 'default_index/search';
$route['mypage/history-ask-return-money']							= 'user/history_ask_return_money';
$route['mypage/history-ask-return-money/(:num)']					= 'user/history_ask_return_money/$1';
$route['mypage/history-user-use-point']								= 'user/history_user_use_point';
$route['mypage/history-user-use-point/(:num)']						= 'user/history_user_use_point/$1';
// List member
$route['mypage/listmember']											= 'user/listmember';
$route['mypage/listmember/(:num)']									= 'user/listmember/$1';
$route['mypage/listinvited']										= 'user/listinvited';
$route['mypage/listinvited/(:num)']									= 'user/listinvited/$1';
// Json
$route['json']                                                		= 'json/index';
$route['notice-transfer-money/(:num)']								= 'user/notice_transfer_money/$1';
$route['404_override'] = 'user/error_404';
/* Admin */
include 'routes_admin.php';
include 'routes_admin_more.php';
include 'routes_qa.php';