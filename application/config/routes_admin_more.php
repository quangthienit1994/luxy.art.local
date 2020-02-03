<?php



if (!defined('BASEPATH')) exit('No direct script access allowed');



// IMAGES

$route['post_image']         					= ADMIN_PATH . 'images/post_picture';

$route['edit_image/(:num)']    					= ADMIN_PATH . 'images/edit_picture/$1';

$route['admin/detail_comment/(:num)'] 			= ADMIN_PATH . 'images/detail_comment/$1';

$route['admin/detail_comment/(:num)/(:num)'] 	= ADMIN_PATH . 'images/detail_comment/$1/$2';

$route['admin/list_comment'] 					= ADMIN_PATH . 'images/list_comment';

$route['admin/list_comment/(:num)'] 			= ADMIN_PATH . 'images/list_comment/$1';

