<?php



if (!defined('BASEPATH')) exit('No direct script access allowed');

$route['admin/import_user']         				= ADMIN_PATH . 'admin/import_user';

/* Admin */

$route['admin']           							= ADMIN_PATH . 'admin/index';

$route['admin/login']    							= ADMIN_PATH . 'admin/login';

$route['admin/logout'] 								= ADMIN_PATH . 'admin/logout';

// Menu

$route['admin/page_menu']         					= ADMIN_PATH . 'admin/page_menu';


// Statistic

$route['admin/page_statistic']         				= ADMIN_PATH . 'admin/page_statistic';

$route['admin/load_statistic']         				= ADMIN_PATH . 'admin/load_statistic';

// User

$route['admin/page_list_user']         				= ADMIN_PATH . 'admin/page_list_user';

$route['admin/page_list_user/(:num)']       		= ADMIN_PATH . 'admin/page_list_user/$1';

$route['admin/page_add_edit_user']         			= ADMIN_PATH . 'admin/page_add_edit_user';

$route['admin/page_add_edit_user/(:num)']   		= ADMIN_PATH . 'admin/page_add_edit_user/$1';

$route['admin/add_edit_user']         				= ADMIN_PATH . 'admin/add_edit_user';

$route['admin/delete_user/(:num)']         			= ADMIN_PATH . 'admin/delete_user/$1';

$route['admin/status_user/(:num)']         			= ADMIN_PATH . 'admin/status_user/$1';

$route['admin/level_user/(:num)']         			= ADMIN_PATH . 'admin/level_user/$1';

$route['admin/page_change_password_admin']      	= ADMIN_PATH . 'admin/page_change_password_admin';

$route['admin/change_password_admin']      			= ADMIN_PATH . 'admin/change_password_admin';

$route['admin/export']      						= ADMIN_PATH . 'admin/export';

$route['admin/export/(:any)']      					= ADMIN_PATH . 'admin/export/$1';



// User pickup

$route['admin/pickup_user/(:num)']         			= ADMIN_PATH . 'admin/pickup_user/$1';

$route['admin/page_list_user_pickup']         		= ADMIN_PATH . 'admin/page_list_user_pickup';

$route['admin/delete_user_pickup/(:num)']         	= ADMIN_PATH . 'admin/delete_user_pickup/$1';

$route['admin/position_user_pickup/(:num)']         = ADMIN_PATH . 'admin/position_user_pickup/$1';



// Order

$route['admin/page_list_order']         			= ADMIN_PATH . 'admin/page_list_order';

$route['admin/page_list_order/(:num)']      		= ADMIN_PATH . 'admin/page_list_order/$1';

$route['admin/page_view_order/(:num)']      		= ADMIN_PATH . 'admin/page_view_order/$1';

$route['admin/delete_order/(:num)']         		= ADMIN_PATH . 'admin/delete_order/$1';

$route['admin/status_order/(:num)']         		= ADMIN_PATH . 'admin/status_order/$1';



// Notification

$route['admin/page_list_notification']         		= ADMIN_PATH . 'admin/page_list_notification';

$route['admin/page_list_notification/(:num)']     	= ADMIN_PATH . 'admin/page_list_notification/$1';

$route['admin/page_add_edit_notification']        	= ADMIN_PATH . 'admin/page_add_edit_notification';

$route['admin/page_add_edit_notification/(:num)'] 	= ADMIN_PATH . 'admin/page_add_edit_notification/$1';

$route['admin/add_edit_notification']         		= ADMIN_PATH . 'admin/add_edit_notification';

$route['admin/delete_notification/(:num)']        	= ADMIN_PATH . 'admin/delete_notification/$1';

$route['admin/status_notification/(:num)']        	= ADMIN_PATH . 'admin/status_notification/$1';

$route['admin/info_check_notification/(:num)']      = ADMIN_PATH . 'admin/info_check_notification/$1';



// Notice

$route['admin/page_list_notice']         			= ADMIN_PATH . 'admin/page_list_notice';

$route['admin/page_list_notice/(:num)']     		= ADMIN_PATH . 'admin/page_list_notice/$1';

$route['admin/page_add_edit_notice']        		= ADMIN_PATH . 'admin/page_add_edit_notice';

$route['admin/page_add_edit_notice/(:num)'] 		= ADMIN_PATH . 'admin/page_add_edit_notice/$1';

$route['admin/add_edit_notice']         			= ADMIN_PATH . 'admin/add_edit_notice';

$route['admin/delete_notice/(:num)']        		= ADMIN_PATH . 'admin/delete_notice/$1';

$route['admin/status_notice/(:num)']        		= ADMIN_PATH . 'admin/status_notice/$1';



// Email template

$route['admin/page_list_email_tpl']         		= ADMIN_PATH . 'admin/page_list_email_tpl';

$route['admin/page_list_email_tpl/(:num)']     		= ADMIN_PATH . 'admin/page_list_email_tpl/$1';

$route['admin/page_add_edit_email_tpl']        		= ADMIN_PATH . 'admin/page_add_edit_email_tpl';

$route['admin/page_add_edit_email_tpl/(:num)'] 		= ADMIN_PATH . 'admin/page_add_edit_email_tpl/$1';

$route['admin/add_edit_email_tpl']         			= ADMIN_PATH . 'admin/add_edit_email_tpl';

$route['admin/delete_email_tpl/(:num)']        		= ADMIN_PATH . 'admin/delete_email_tpl/$1';

$route['admin/status_email_tpl/(:num)']        		= ADMIN_PATH . 'admin/status_email_tpl/$1';



// Meta

$route['admin/page_list_meta']         				= ADMIN_PATH . 'admin/page_list_meta';

$route['admin/page_list_meta/(:num)']       		= ADMIN_PATH . 'admin/page_list_meta/$1';

$route['admin/page_add_edit_meta']         			= ADMIN_PATH . 'admin/page_add_edit_meta';

$route['admin/page_add_edit_meta/(:num)']   		= ADMIN_PATH . 'admin/page_add_edit_meta/$1';

$route['admin/add_edit_meta']         				= ADMIN_PATH . 'admin/add_edit_meta';

$route['admin/delete_meta/(:num)']        			= ADMIN_PATH . 'admin/delete_meta/$1';

$route['admin/status_meta/(:num)']        			= ADMIN_PATH . 'admin/status_meta/$1';



// SETTING

$route['admin/page_list_setting']         			= ADMIN_PATH . 'admin/page_list_setting';

$route['admin/page_list_setting/(:num)']     		= ADMIN_PATH . 'admin/page_list_setting/$1';

$route['admin/page_add_edit_setting']        		= ADMIN_PATH . 'admin/page_add_edit_setting';

$route['admin/page_add_edit_setting/(:num)'] 		= ADMIN_PATH . 'admin/page_add_edit_setting/$1';

$route['admin/add_edit_setting']         			= ADMIN_PATH . 'admin/add_edit_setting';

$route['admin/delete_setting/(:num)']        		= ADMIN_PATH . 'admin/delete_setting/$1';

$route['admin/status_setting/(:num)']        		= ADMIN_PATH . 'admin/status_setting/$1';



// IMAGE

$route['admin/page_list_image']         			= ADMIN_PATH . 'admin/page_list_image';

$route['admin/page_list_image/(:num)']     			= ADMIN_PATH . 'admin/page_list_image/$1';

$route['admin/page_add_edit_image']        			= ADMIN_PATH . 'admin/page_add_edit_image';

$route['admin/page_add_edit_image/(:num)'] 			= ADMIN_PATH . 'admin/page_add_edit_image/$1';

$route['admin/add_edit_image']         				= ADMIN_PATH . 'admin/add_edit_image';

$route['admin/delete_image/(:num)']        			= ADMIN_PATH . 'admin/delete_image/$1';

$route['admin/status_image/(:num)']        			= ADMIN_PATH . 'admin/status_image/$1';

$route['admin/image_info/(:num)']        			= ADMIN_PATH . 'admin/image_info/$1';

// IMAGE REPORT

$route['admin/page_list_report_image']         		= ADMIN_PATH . 'admin/page_list_report_image';

$route['admin/page_list_report_image/(:num)']     	= ADMIN_PATH . 'admin/page_list_report_image/$1';

$route['admin/page_add_edit_report_image']        	= ADMIN_PATH . 'admin/page_add_edit_report_image';

$route['admin/page_add_edit_report_image/(:num)'] 	= ADMIN_PATH . 'admin/page_add_edit_report_image/$1';

$route['admin/add_edit_report_image']         		= ADMIN_PATH . 'admin/add_edit_report_image';

$route['admin/delete_report_image/(:num)']        	= ADMIN_PATH . 'admin/delete_report_image/$1';

$route['admin/status_report_image/(:num)']        	= ADMIN_PATH . 'admin/status_report_image/$1';

$route['admin/status_report_image_full']        	= ADMIN_PATH . 'admin/status_report_image_full';

$route['admin/report_image_info/(:num)']        	= ADMIN_PATH . 'admin/report_image_info/$1';


// Lang manager

$route['admin/page_list_lang']         				= ADMIN_PATH . 'admin/page_list_lang';

$route['admin/page_list_lang/(:num)']     			= ADMIN_PATH . 'admin/page_list_lang/$1';

$route['admin/page_add_edit_lang']        			= ADMIN_PATH . 'admin/page_add_edit_lang';

$route['admin/page_add_edit_lang/(:num)'] 			= ADMIN_PATH . 'admin/page_add_edit_lang/$1';

$route['admin/page_add_edit_language']        		= ADMIN_PATH . 'admin/page_add_edit_language';

$route['admin/page_add_edit_language/(:num)'] 		= ADMIN_PATH . 'admin/page_add_edit_language/$1';

$route['admin/add_edit_lang']         				= ADMIN_PATH . 'admin/add_edit_lang';

$route['admin/delete_lang/(:num)']        			= ADMIN_PATH . 'admin/delete_lang/$1';

$route['admin/status_lang/(:num)']        			= ADMIN_PATH . 'admin/status_lang/$1';



// Country

$route['admin/page_list_country']         			= ADMIN_PATH . 'admin/page_list_country';

$route['admin/page_list_country/(:num)']     		= ADMIN_PATH . 'admin/page_list_country/$1';

$route['admin/page_add_edit_country']        		= ADMIN_PATH . 'admin/page_add_edit_country';

$route['admin/page_add_edit_country/(:num)'] 		= ADMIN_PATH . 'admin/page_add_edit_country/$1';

$route['admin/add_edit_country']         			= ADMIN_PATH . 'admin/add_edit_country';

$route['admin/delete_country/(:num)']        		= ADMIN_PATH . 'admin/delete_country/$1';

$route['admin/status_country/(:num)']        		= ADMIN_PATH . 'admin/status_country/$1';



// State

$route['admin/page_list_state']         			= ADMIN_PATH . 'admin/page_list_state';

$route['admin/page_list_state/(:num)']     			= ADMIN_PATH . 'admin/page_list_state/$1';

$route['admin/page_add_edit_state']        			= ADMIN_PATH . 'admin/page_add_edit_state';

$route['admin/page_add_edit_state/(:num)'] 			= ADMIN_PATH . 'admin/page_add_edit_state/$1';

$route['admin/add_edit_state']         				= ADMIN_PATH . 'admin/add_edit_state';

$route['admin/delete_state/(:num)']        			= ADMIN_PATH . 'admin/delete_state/$1';

$route['admin/status_state/(:num)']        			= ADMIN_PATH . 'admin/status_state/$1';

$route['admin/load_state/(:num)']        			= ADMIN_PATH . 'admin/load_state/$1';



// Category

$route['admin/page_list_category']         			= ADMIN_PATH . 'admin/page_list_category';

$route['admin/page_list_category/(:num)']     		= ADMIN_PATH . 'admin/page_list_category/$1';

$route['admin/page_add_edit_category']        		= ADMIN_PATH . 'admin/page_add_edit_category';

$route['admin/page_add_edit_category/(:num)'] 		= ADMIN_PATH . 'admin/page_add_edit_category/$1';

$route['admin/add_edit_category']         			= ADMIN_PATH . 'admin/add_edit_category';

$route['admin/delete_category/(:num)']        		= ADMIN_PATH . 'admin/delete_category/$1';

$route['admin/status_category/(:num)']        		= ADMIN_PATH . 'admin/status_category/$1';

$route['admin/pickup_category/(:num)']        		= ADMIN_PATH . 'admin/pickup_category/$1';

$route['admin/load_menu/(:num)']        			= ADMIN_PATH . 'admin/load_menu/$1';



// Package

$route['admin/page_list_package']         			= ADMIN_PATH . 'admin/page_list_package';

$route['admin/page_list_package/(:num)']     		= ADMIN_PATH . 'admin/page_list_package/$1';

$route['admin/page_add_edit_package']        		= ADMIN_PATH . 'admin/page_add_edit_package';

$route['admin/page_add_edit_package/(:num)'] 		= ADMIN_PATH . 'admin/page_add_edit_package/$1';

$route['admin/add_edit_package']         			= ADMIN_PATH . 'admin/add_edit_package';

$route['admin/delete_package/(:num)']        		= ADMIN_PATH . 'admin/delete_package/$1';

$route['admin/status_package/(:num)']        		= ADMIN_PATH . 'admin/status_package/$1';



// Server

$route['admin/page_list_server']         			= ADMIN_PATH . 'admin/page_list_server';

$route['admin/page_list_server/(:num)']     		= ADMIN_PATH . 'admin/page_list_server/$1';

$route['admin/page_add_edit_server']        		= ADMIN_PATH . 'admin/page_add_edit_server';

$route['admin/page_add_edit_server/(:num)'] 		= ADMIN_PATH . 'admin/page_add_edit_server/$1';

$route['admin/add_edit_server']         			= ADMIN_PATH . 'admin/add_edit_server';

$route['admin/delete_server/(:num)']        		= ADMIN_PATH . 'admin/delete_server/$1';

$route['admin/status_server/(:num)']        		= ADMIN_PATH . 'admin/status_server/$1';



// Color

$route['admin/page_list_color']         			= ADMIN_PATH . 'admin/page_list_color';

$route['admin/page_list_color/(:num)']     			= ADMIN_PATH . 'admin/page_list_color/$1';

$route['admin/page_add_edit_color']        			= ADMIN_PATH . 'admin/page_add_edit_color';

$route['admin/page_add_edit_color/(:num)'] 			= ADMIN_PATH . 'admin/page_add_edit_color/$1';

$route['admin/add_edit_color']         				= ADMIN_PATH . 'admin/add_edit_color';

$route['admin/delete_color/(:num)']        			= ADMIN_PATH . 'admin/delete_color/$1';

$route['admin/status_color/(:num)']        			= ADMIN_PATH . 'admin/status_color/$1';



// Competition

$route['admin/page_list_competition']         		= ADMIN_PATH . 'admin/page_list_competition';

$route['admin/page_list_competition/(:num)']    	= ADMIN_PATH . 'admin/page_list_competition/$1';

$route['admin/page_add_edit_competition']       	= ADMIN_PATH . 'admin/page_add_edit_competition';

$route['admin/page_add_edit_competition/(:num)']	= ADMIN_PATH . 'admin/page_add_edit_competition/$1';

$route['admin/add_edit_competition']         		= ADMIN_PATH . 'admin/add_edit_competition';

$route['admin/delete_competition/(:num)']       	= ADMIN_PATH . 'admin/delete_competition/$1';

$route['admin/status_competition/(:num)']       	= ADMIN_PATH . 'admin/status_competition/$1';

//Comment
$route['admin/page_list_comment']         			= ADMIN_PATH . 'admin/page_list_comment';

$route['admin/page_list_comment/(:num)']    		= ADMIN_PATH . 'admin/page_list_comment/$1';

$route['admin/status_comment/(:num)']       		= ADMIN_PATH . 'admin/status_comment/$1';

$route['admin/delete_comment/(:num)']       		= ADMIN_PATH . 'admin/delete_comment/$1';

$route['admin/page_add_edit_comment/(:num)']		= ADMIN_PATH . 'admin/page_add_edit_comment/$1';

$route['admin/add_edit_comment']	         		= ADMIN_PATH . 'admin/add_edit_comment';

$route['admin/page_add_edit_ngwordlist']       		= ADMIN_PATH . 'admin/page_add_edit_ngwordlist';

$route['admin/add_edit_ngwordlist']       			= ADMIN_PATH . 'admin/add_edit_ngwordlist';

$route['admin/set_status_comment_by_admin']       	= ADMIN_PATH . 'admin/set_status_comment_by_admin';

// INBOX

$route['admin/page_list_inbox']         			= ADMIN_PATH . 'admin/page_list_inbox';

$route['admin/page_list_inbox/(:num)']    			= ADMIN_PATH . 'admin/page_list_inbox/$1';

$route['admin/page_add_edit_inbox']       			= ADMIN_PATH . 'admin/page_add_edit_inbox';

$route['admin/page_add_edit_inbox/(:num)']			= ADMIN_PATH . 'admin/page_add_edit_inbox/$1';

$route['admin/add_edit_inbox']         				= ADMIN_PATH . 'admin/add_edit_inbox';

$route['admin/delete_inbox/(:num)']       			= ADMIN_PATH . 'admin/delete_inbox/$1';

$route['admin/status_inbox/(:num)']       			= ADMIN_PATH . 'admin/status_inbox/$1';

// TAGS

$route['admin/page_list_tags']         			= ADMIN_PATH . 'admin/page_list_tags';

$route['admin/page_list_tags/(:num)']    			= ADMIN_PATH . 'admin/page_list_tags/$1';

$route['admin/page_add_edit_tags']       			= ADMIN_PATH . 'admin/page_add_edit_tags';

$route['admin/page_add_edit_tags/(:num)']			= ADMIN_PATH . 'admin/page_add_edit_tags/$1';

$route['admin/add_edit_tags']         				= ADMIN_PATH . 'admin/add_edit_tags';

$route['admin/delete_tags/(:num)']       			= ADMIN_PATH . 'admin/delete_tags/$1';

$route['admin/status_tags/(:num)']       			= ADMIN_PATH . 'admin/status_tags/$1';

// COMPETITION TIME AGREE

$route['admin/page_list_c_agree']         			= ADMIN_PATH . 'admin/page_list_c_agree';

$route['admin/page_list_c_agree/(:num)']    		= ADMIN_PATH . 'admin/page_list_c_agree/$1';

$route['admin/page_add_edit_c_agree']       		= ADMIN_PATH . 'admin/page_add_edit_c_agree';

$route['admin/page_add_edit_c_agree/(:num)']		= ADMIN_PATH . 'admin/page_add_edit_c_agree/$1';

$route['admin/add_edit_c_agree']         			= ADMIN_PATH . 'admin/add_edit_c_agree';

$route['admin/delete_c_agree/(:num)']       		= ADMIN_PATH . 'admin/delete_c_agree/$1';

$route['admin/status_c_agree/(:num)']       		= ADMIN_PATH . 'admin/status_c_agree/$1';



// MENU

$route['admin/page_list_menu']         				= ADMIN_PATH . 'admin/page_list_menu';

$route['admin/page_list_menu/(:num)']    			= ADMIN_PATH . 'admin/page_list_menu/$1';

$route['admin/page_add_edit_menu']       			= ADMIN_PATH . 'admin/page_add_edit_menu';

$route['admin/page_add_edit_menu/(:num)']			= ADMIN_PATH . 'admin/page_add_edit_menu/$1';

$route['admin/add_edit_menu']         				= ADMIN_PATH . 'admin/add_edit_menu';

$route['admin/delete_menu/(:num)']       			= ADMIN_PATH . 'admin/delete_menu/$1';

$route['admin/status_menu/(:num)']       			= ADMIN_PATH . 'admin/status_menu/$1';



// User follow

$route['admin/page_list_user_follow']         		= ADMIN_PATH . 'admin/page_list_user_follow';

$route['admin/page_list_user_follow/(:num)']    	= ADMIN_PATH . 'admin/page_list_user_follow/$1';

$route['admin/page_add_edit_user_follow']       	= ADMIN_PATH . 'admin/page_add_edit_user_follow';

$route['admin/page_add_edit_user_follow/(:num)']	= ADMIN_PATH . 'admin/page_add_edit_user_follow/$1';

$route['admin/add_edit_user_follow']         		= ADMIN_PATH . 'admin/add_edit_user_follow';

$route['admin/delete_user_follow/(:num)']       	= ADMIN_PATH . 'admin/delete_user_follow/$1';

$route['admin/status_user_follow/(:num)']       	= ADMIN_PATH . 'admin/status_user_follow/$1';



// Bookmark image

$route['admin/page_list_bookmark_img']         		= ADMIN_PATH . 'admin/page_list_bookmark_img';

$route['admin/page_list_bookmark_img/(:num)']   	= ADMIN_PATH . 'admin/page_list_bookmark_img/$1';

$route['admin/page_add_edit_bookmark_img']      	= ADMIN_PATH . 'admin/page_add_edit_bookmark_img';

$route['admin/page_add_edit_bookmark_img/(:num)']	= ADMIN_PATH . 'admin/page_add_edit_bookmark_img/$1';

$route['admin/add_edit_bookmark_img']         		= ADMIN_PATH . 'admin/add_edit_bookmark_img';

$route['admin/delete_bookmark_img/(:num)']      	= ADMIN_PATH . 'admin/delete_bookmark_img/$1';

$route['admin/status_bookmark_img/(:num)']      	= ADMIN_PATH . 'admin/status_bookmark_img/$1';



// User view img

$route['admin/page_list_user_view_img']         	= ADMIN_PATH . 'admin/page_list_user_view_img';

$route['admin/page_list_user_view_img/(:num)']  	= ADMIN_PATH . 'admin/page_list_user_view_img/$1';

$route['admin/page_add_edit_user_view_img']     	= ADMIN_PATH . 'admin/page_add_edit_user_view_img';

$route['admin/page_add_edit_user_view_img/(:num)']	= ADMIN_PATH . 'admin/page_add_edit_user_view_img/$1';

$route['admin/add_edit_user_view_img']         		= ADMIN_PATH . 'admin/add_edit_user_view_img';

$route['admin/delete_user_view_img/(:num)']     	= ADMIN_PATH . 'admin/delete_user_view_img/$1';

$route['admin/status_user_view_img/(:num)']     	= ADMIN_PATH . 'admin/status_user_view_img/$1';



// Image apply

$route['admin/page_list_image_apply']         		= ADMIN_PATH . 'admin/page_list_image_apply';

$route['admin/page_list_image_apply/(:num)']  		= ADMIN_PATH . 'admin/page_list_image_apply/$1';

$route['admin/page_add_edit_image_apply']     		= ADMIN_PATH . 'admin/page_add_edit_image_apply';

$route['admin/page_add_edit_image_apply/(:num)']	= ADMIN_PATH . 'admin/page_add_edit_image_apply/$1';

$route['admin/add_edit_image_apply']         		= ADMIN_PATH . 'admin/add_edit_image_apply';

$route['admin/delete_image_apply/(:num)']     		= ADMIN_PATH . 'admin/delete_image_apply/$1';

$route['admin/status_image_apply/(:num)']     		= ADMIN_PATH . 'admin/status_image_apply/$1';



// User order

$route['admin/page_list_user_order']         		= ADMIN_PATH . 'admin/page_list_user_order';

$route['admin/page_list_user_order/(:num)']  		= ADMIN_PATH . 'admin/page_list_user_order/$1';

$route['admin/page_add_edit_user_order']     		= ADMIN_PATH . 'admin/page_add_edit_user_order';

$route['admin/page_add_edit_user_order/(:num)']		= ADMIN_PATH . 'admin/page_add_edit_user_order/$1';

$route['admin/add_edit_user_order']         		= ADMIN_PATH . 'admin/add_edit_user_order';

$route['admin/delete_user_order/(:num)']     		= ADMIN_PATH . 'admin/delete_user_order/$1';

$route['admin/status_user_order/(:num)']     		= ADMIN_PATH . 'admin/status_user_order/$1';


// QA

$route['admin/page_list_qa']         				= ADMIN_PATH . 'admin/page_list_qa';

$route['admin/page_list_qa/(:num)']     			= ADMIN_PATH . 'admin/page_list_qa/$1';

$route['admin/page_add_edit_qa']        			= ADMIN_PATH . 'admin/page_add_edit_qa';

$route['admin/page_add_edit_qa/(:num)'] 			= ADMIN_PATH . 'admin/page_add_edit_qa/$1';

$route['admin/add_edit_qa']         				= ADMIN_PATH . 'admin/add_edit_qa';

$route['admin/delete_qa/(:num)']        			= ADMIN_PATH . 'admin/delete_qa/$1';

$route['admin/status_qa/(:num)']        			= ADMIN_PATH . 'admin/status_qa/$1';


// QA Category

$route['admin/page_list_qa_category']         		= ADMIN_PATH . 'admin/page_list_qa_category';

$route['admin/page_list_qa_category/(:num)']     	= ADMIN_PATH . 'admin/page_list_qa_category/$1';

$route['admin/page_add_edit_qa_category']        	= ADMIN_PATH . 'admin/page_add_edit_qa_category';

$route['admin/page_add_edit_qa_category/(:num)'] 	= ADMIN_PATH . 'admin/page_add_edit_qa_category/$1';

$route['admin/add_edit_qa_category']         		= ADMIN_PATH . 'admin/add_edit_qa_category';

$route['admin/delete_qa_category/(:num)']        	= ADMIN_PATH . 'admin/delete_qa_category/$1';

$route['admin/status_qa_category/(:num)']        	= ADMIN_PATH . 'admin/status_qa_category/$1';


// QA Category

$route['admin/page_list_return_money']         		= ADMIN_PATH . 'admin/page_list_return_money';

$route['admin/page_list_return_money/(:num)']     	= ADMIN_PATH . 'admin/page_list_return_money/$1';

$route['admin/page_add_edit_return_money']        	= ADMIN_PATH . 'admin/page_add_edit_return_money';

$route['admin/page_add_edit_return_money/(:num)'] 	= ADMIN_PATH . 'admin/page_add_edit_return_money/$1';

$route['admin/add_edit_return_money']         		= ADMIN_PATH . 'admin/add_edit_return_money';

$route['admin/delete_return_money/(:num)']        	= ADMIN_PATH . 'admin/delete_return_money/$1';

$route['admin/status_return_money/(:num)']        	= ADMIN_PATH . 'admin/status_return_money/$1';

$route['admin/bank_info/(:num)']        			= ADMIN_PATH . 'admin/bank_info/$1';

// Give point
$route['admin/give_point']        					= ADMIN_PATH . 'admin/give_point';

$route['admin/add_point_user']       				= ADMIN_PATH . 'admin/add_point_user';