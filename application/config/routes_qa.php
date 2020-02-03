<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');


$route['qa']					= 'qa/page_q_a';
$route['qa/(:num)']				= 'qa/page_q_a_detail/$1';
$route['qa-category/(:num)']	= 'qa/page_q_a_category/$1';
$route['qa-search']				= 'qa/page_q_a_search';
