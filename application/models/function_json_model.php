<?php
/**
 * Reverse bidding system Account_model Class
 *
 * Handles Account information in database.
 *
 * @package		Reverse bidding system
 * @subpackage	Models
 * @category	Settings 
 * @author		Flps Dev Team
 * @version		Version 1.0
 * @link		http://www.freelancephpscript.com
 
  <Reverse bidding system> 
    Copyright (C) <2009>  <Freelance PHP Script>

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>
    If you want more information, please email me at sabrina@freelancephpscript.com or 
    contact us from http://www.freelancephpscript.com/contact 

 
 */
 	class Function_json_model extends CI_Model {
 		
 	/**
 	 * Constructor
 	 * 
 	 */

 	function __contruction(){
		parent::__contruction();
   	}//Controller End
   	
   	
   	/**
   	 * Get User Level
   	 *
   	 * @access	public
   	 * @param	nil
   	 * @return	void
   	 * @author	dohuuthien 2017-09-14
   	 */
   	function getUserLevel($user_id){
   	
   		$this->db->from('luxyart_tb_user');
   		$this->db->select('user_level');
   		$this->db->where('user_id', $user_id);
   		$rs = $this->db->get();
   		$result = $rs->row();
   		
   		if(!empty($result)){
   			return $result->user_level;
   		}
   		else{
   			return 0;
   		}
   	
   	}
   	
   	/**
   	 * Get Manager Server
   	 *
   	 * @access	public
   	 * @param	nil
   	 * @return	void
   	 * @author	dohuuthien 2017-09-14
   	 */
   	function getManagerServer($id_server){
   		
   		$this->db->from('luxyart_tb_manager_server');
   		$this->db->select('*');
   		$this->db->where('id_server', $id_server);
   		$rs = $this->db->get();
   		$result = $rs->row();
   	
   		$result = array();
   		if(!empty($rs)){
   			$result = $rs->row();
   		}
   		return $result;
   	
   	}
   	
   	/**
   	 * Get Product Color
   	 *
   	 * @access	public
   	 * @param	nil
   	 * @return	void
   	 * @author	dohuuthien 2017-09-14
   	 */
   	function getProductColor($id_color){
   		 
   		$this->db->from('luxyart_tb_product_color');
   		$this->db->select('*');
   		$this->db->where('id_color', $id_color);
   		$rs = $this->db->get();
   	
   		$result = array();
   		if(!empty($rs)){
   			$result = $rs->row();
   		}
   		return $result;
   	
   	}
   	
   	function getProductImgSize($id_img){
   	
   		$this->db->from('luxyart_tb_product_img_size');
   		$this->db->select('*');
   		$this->db->where(array('id_img' => $id_img, 'status_img_size' => 0));
   		$rs = $this->db->get();
   		
   		$result = array();
   		if(!empty($rs)){
   			$result = $rs->result();
   		}
   		return $result;
   	
   	}
   	
   	function getRootCate($id, $lang){

   		$this->db->from('luxyart_tb_product_category');
   		$this->db->select('*');
   		$this->db->where(array('product_category_id' => $id, 'level_cate' => 0, 'lang_id' => $lang));
   		$rs = $this->db->get();
   		
   		$result = array();
   		if(!empty($rs)){
   			$result = $rs->row();
   		}
   		return $result;
   		
   	}
   	
   	function getParentCate($id, $lang){
   		
   		$this->db->from('luxyart_tb_product_category');
   		$this->db->select('*');
   		$this->db->where(array('product_category_id' => $id, 'level_cate' => 1, 'lang_id' => $lang));
   		$rs = $this->db->get();
   		$result = $rs->row();
   		
   		$result = array();
   		if(!empty($rs)){
   			$result = $rs->row();
   		}
   		return $result;
   		
   	}
   	
   	function getSubCate($id, $lang){
   		
   		$this->db->from('luxyart_tb_product_category');
   		$this->db->select('*');
   		$this->db->where(array('product_category_id' => $id, 'level_cate' => 2, 'lang_id' => $lang));
   		$rs = $this->db->get();
   		
   		$result = array();
   		if(!empty($rs)){
   			$result = $rs->row();
   		}
   		return $result;
   		 
   	}
   	
   	function getUser($user_id){
   		
   		$this->db->from('luxyart_tb_user');
   		$this->db->select('*');
   		$this->db->where('user_id', $user_id);
   		$rs = $this->db->get();
   		
   		$result = array();
   		if(!empty($rs)){
   			$result = $rs->row();
   		}
   		return $result;
   		
   	}
   	
   	function base64_to_jpeg( $base64_string, $output_file ) {
   	
   		$ifp = fopen( $output_file, "wb" );
   		fwrite( $ifp, base64_decode( $base64_string) );
   		fclose( $ifp );
   		return( $output_file );
   	
   	}
   	
   	function byte_convert_to_mb($size) {
   	
   		return $size/1048576;
   	
   	}
   	
   	function resize_image($file, $w, $h, $crop=FALSE) {
   		list($width, $height) = getimagesize($file);
   		$r = $width / $height;
   		if ($crop) {
   			if ($width > $height) {
   				$width = ceil($width-($width*abs($r-$w/$h)));
   			} else {
   				$height = ceil($height-($height*abs($r-$w/$h)));
   			}
   			$newwidth = $w;
   			$newheight = $h;
   		} else {
   			if ($w/$h > $r) {
   				$newwidth = $h*$r;
   				$newheight = $h;
   			} else {
   				$newheight = $w/$r;
   				$newwidth = $w;
   			}
   		}
   		$src = imagecreatefromjpeg($file);
   		$dst = imagecreatetruecolor($newwidth, $newheight);
   		imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
   	
   		return $dst;
   	}
   	
   	function resize($newWidth, $targetFile, $originalFile) {
   	
   		$info = getimagesize($originalFile);
   		$mime = $info['mime'];
   	
   		switch ($mime) {
   			case 'image/jpeg':
   				$image_create_func = 'imagecreatefromjpeg';
   				$image_save_func = 'imagejpeg';
   				$new_image_ext = 'jpg';
   				break;
   	
   			case 'image/png':
   				$image_create_func = 'imagecreatefrompng';
   				$image_save_func = 'imagepng';
   				$new_image_ext = 'png';
   				break;
   	
   			case 'image/gif':
   				$image_create_func = 'imagecreatefromgif';
   				$image_save_func = 'imagegif';
   				$new_image_ext = 'gif';
   				break;
   	
   			default:
   				throw new Exception('Unknown image type.');
   		}
   	
   		$img = $image_create_func($originalFile);
   		list($width, $height) = getimagesize($originalFile);
   	
   		$newHeight = ($height / $width) * $newWidth;
   		$tmp = imagecreatetruecolor($newWidth, $newHeight);
   		imagecopyresampled($tmp, $img, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
   	
   		if (file_exists($targetFile)) {
   			unlink($targetFile);
   		}
   		$image_save_func($tmp, "$targetFile.$new_image_ext");
   	}
   	
   	function main_color_image($file){

   		$image=imagecreatefromjpeg($file);
   		$thumb=imagecreatetruecolor(1,1); imagecopyresampled($thumb,$image,0,0,0,0,1,1,imagesx($image),imagesy($image));
   		$mainColor=strtoupper(dechex(imagecolorat($thumb,0,0)));
   		return $mainColor;
   		
   	}
   	
   	function watermark_image($target, $wtrmrk_file, $newcopy, $position) {
   		
   		$watermark = imagecreatefrompng($wtrmrk_file);
   		imagealphablending($watermark, false);
   		imagesavealpha($watermark, true);
   		$img = imagecreatefromjpeg($target);
   		$img_w = imagesx($img);
   		$img_h = imagesy($img);
   		$wtrmrk_w = imagesx($watermark);
   		$wtrmrk_h = imagesy($watermark);
   	
   		if($position == 0){
   	
   			$dst_x = ($img_w / 2) - ($wtrmrk_w / 2); // For centering the watermark on any image
   			$dst_y = ($img_h / 2) - ($wtrmrk_h / 2); // For centering the watermark on any image
   	
   		}
   		else if($position == 1){
   	
   			$dst_x = 0; // For centering the watermark on any image
   			$dst_y = 0; // For centering the watermark on any image
   	
   		}
   		else if($position == 2){
   	
   			$dst_x = ($img_w) - ($wtrmrk_w); // For centering the watermark on any image
   			$dst_y = 0; // For centering the watermark on any image
   	
   		}
   		else if($position == 3){
   	
   			$dst_x = 0; // For centering the watermark on any image
   			$dst_y = ($img_h) - ($wtrmrk_h); // For centering the watermark on any image
   	
   		}
   		else if($position == 4){
   	
   			$dst_x = ($img_w) - ($wtrmrk_w); // For centering the watermark on any image
   			$dst_y = ($img_h) - ($wtrmrk_h); // For centering the watermark on any image
   	
   		}
   	
   		imagecopy($img, $watermark, $dst_x, $dst_y, 0, 0, $wtrmrk_w, $wtrmrk_h);
   		imagejpeg($img, $newcopy, 100);
   		imagedestroy($img);
   		imagedestroy($watermark);
   	}
   	
   	function check_md5_file($md5_file){
   		
   		$this->db->from('luxyart_tb_product_img');
   		$this->db->select('id_img');
   		$this->db->where(array('img_file_md5' => trim($md5_file)));
   		$rs = $this->db->get();
   		$result = $rs->row();
   		
   		if(!empty($result)){
   			return 1;
   		}
   		else{
   			return 0;
   		} 		
   		
   	}
   	
   	function check_permission($type){
   	
   		$logged_in = @$this->session->userdata['logged_in'];
   	
   		if(!empty($logged_in)){
   			if($logged_in['user_level'] >= $type){
   				return 1;
   			}
   			else{
   				return 0;
   			}
   		}
   		else{
   			return 0;
   		}
   	
   	}
   	
   	function get_logged_in(){
   		
   		return $logged_in = @$this->session->userdata['logged_in'];
   		
   	}
	 
}
// End Main_model Class

/* End of file Main.php */
/* Location: ./app/models/Main.php */