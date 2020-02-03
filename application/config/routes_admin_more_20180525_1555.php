<?php



if (!defined('BASEPATH')) exit('No direct script access allowed');



// IMAGES

$route['post_image']         					= ADMIN_PATH . 'images/post_picture';

$route['edit_image/(:num)']    					= ADMIN_PATH . 'images/edit_picture/$1';

$route['post_image_test']         				= ADMIN_PATH . 'images/post_picture_test';

$route['edit_image_test/(:num)']    			= ADMIN_PATH . 'images/edit_picture_test/$1';

$route['admin/page_add_edit_lang_test']        	= ADMIN_PATH . 'images/page_add_edit_lang';

$route['admin/page_add_edit_lang_test/(:num)'] 	= ADMIN_PATH . 'images/page_add_edit_lang/$1';