<?php
$CI =& get_instance();
$CI->config->load();
$this->lang->load('dich', 'ja');
?>

<style type="text/css">
h2.bd2 {
	display: none;
}
</style>

<article class="main_board">
	<div class="mb bd2">
		<h2 class="bdu2"><?php echo $this->lang->line('membership_management'); ?></h2>
		<div class="mb_bd2_cont">
			<div class="mb_bd2_c">
				<p>■&nbsp;<?php echo $this->lang->line('diamond'); ?></p>
					<p><?=number_format(count($this->db->get_where('luxyart_tb_user',['user_level'=>4,'is_active'=>1,'user_status'=>1])->result()),0,",",",")?> <?php echo $this->lang->line('people'); ?></p>
			</div>
			<div class="mb_bd2_c">
				<p>■&nbsp;<?php echo $this->lang->line('platinum'); ?></p>
					<p><?=number_format(count($this->db->get_where('luxyart_tb_user',['user_level'=>3,'is_active'=>1,'user_status'=>1])->result()),0,",",",")?> <?php echo $this->lang->line('people'); ?></p>
			</div>
			<div class="mb_bd2_c">
				<p>■&nbsp;<?php echo $this->lang->line('creator'); ?></p>
					<p><?=number_format(count($this->db->get_where('luxyart_tb_user',['user_level'=>2,'is_active'=>1,'user_status'=>1])->result()),0,",",",")?> <?php echo $this->lang->line('people'); ?></p>
			</div>
			<div class="mb_bd2_c">
				<p>■&nbsp;<?php echo $this->lang->line('regular_membership_number'); ?></p>
					<p><?=number_format(count($this->db->get_where('luxyart_tb_user',['user_status <='=>1])->result()),0,",",",")?> <?php echo $this->lang->line('people'); ?></p>
			</div>
			<div class="mb_bd2_c">
				<p>■&nbsp;<?php echo $this->lang->line('corporate'); ?></p>
					
					<p><?=number_format(count($this->db->get_where('luxyart_tb_user',['user_level'=>5,'is_active'=>1,'user_status'=>1])->result()),0,",",",")?> <?php echo $this->lang->line('people'); ?></p>
			</div>
		</div>
		
	</div>

<!--- 売上管理 Start   -->
	<div class="mb bd3">
		<h2 class="bdu3"><?php echo $this->lang->line('sales_control'); ?></h2>
		<div class="mb_bd2_cont">
			<div class="mb_bd2_c">
				<?php
					$this->db->flush_cache();
					$this->db->select('SUM(user_point) as total_point, SUM(user_paymoney_getpoint) as total_user_paymoney_getpoint');
					$count = $this->db->get('luxyart_tb_user')->row();
				?>
				<p>■&nbsp;<?php echo $this->lang->line('all_members_holding_points'); ?></p>
					<p><?=number_format($count->total_point+$count->total_user_paymoney_getpoint,0,",",",")?> <?php echo $this->lang->line('credit'); ?></p>
			</div>
			<div class="mb_bd2_c">
				<?php
					$this->db->flush_cache();
					$this->db->where('date_add >=', date("Y-m-d")." 00:00:00");
					$this->db->where('date_add <=', date("Y-m-d H:i:s"));
					$this->db->where('(content LIKE "%Shopping cart%" OR content LIKE "%Update level account%")', null,false);
					$this->db->where('status_change_point', 2);
					$this->db->select('SUM(point) as total_point');
					$total_point = $this->db->get('luxyart_tb_management_point')->row()->total_point;
				?>
				<p>■&nbsp;<?php echo $this->lang->line('number_of_points_used_per_day'); ?></p>
					<p><?=number_format($total_point,0,",",",")?> <?php echo $this->lang->line('credit'); ?></p>
			</div>
			<div class="mb_bd2_c">
				<?php
					$this->db->flush_cache();
					$this->db->where('date_add >=', date("Y-m")."-00 00:00:00");
					$this->db->where('date_add <=', date("Y-m-d H:i:s"));
					$this->db->where('(content LIKE "%Shopping cart%" OR content LIKE "%Update level account%")', null,false);
					$this->db->where('status_change_point', 2);
					$this->db->select('SUM(point) as total_point');
					$total_point = $this->db->get('luxyart_tb_management_point')->row()->total_point;
				?>
				<p>■&nbsp;<?php echo $this->lang->line('number_of_monthly_points_used'); ?></p>
					<p><?=number_format($total_point,0,",",",")?> <?php echo $this->lang->line('credit'); ?></p>
			</div>
			<div class="mb_bd2_c">
				<?php
					$this->db->flush_cache();
					$this->db->where('date_add >=', date("Y-m-d")." 00:00:00");
					$this->db->where('date_add <=', date("Y-m-d H:i:s"));
					$this->db->where('(content LIKE "%Buy Package Point%")', null,false);
					$this->db->where('status_change_point', 1);
					$this->db->select('SUM(point) as total_point');
					$total_point = $this->db->get('luxyart_tb_management_point')->row()->total_point;
				?>
				<p>■&nbsp;<?php echo $this->lang->line('todays_purchase_point_number'); ?></p>
					<p><?=number_format($total_point,0,",",",")?> <?php echo $this->lang->line('credit'); ?></p>
			</div>
			<div class="mb_bd2_c">
				<?php
					$this->db->flush_cache();
					$this->db->where('date_add >=', date("Y-m")."-00 00:00:00");
					$this->db->where('date_add <=', date("Y-m-d H:i:s"));
					$this->db->where('(content LIKE "%Buy Package Point%")', null,false);
					$this->db->where('status_change_point', 1);
					$this->db->select('SUM(point) as total_point');
					$total_point = $this->db->get('luxyart_tb_management_point')->row()->total_point;
				?>
				<p>■&nbsp;<?php echo $this->lang->line('number_of_points_purchased_monthly'); ?></p>
					<p><?=number_format($total_point,0,",",",")?> <?php echo $this->lang->line('credit'); ?></p>
			</div>
		</div>
	</div>
<!--- 売上管理 End   -->


	<div class="mb bd4">
		<h2 class="bdu4"><?php echo $this->lang->line('pickup'); ?></h2>
		<div class="mb_bd4_cont">
			<?php
				$this->db->flush_cache();
				$this->db->from('luxyart_tb_user_pickup as up');
				$this->db->join('luxyart_tb_user as user','up.user_id=user.user_id');
				$this->db->order_by('position','ASC');
				$this->db->limit(6);
				$list = $this->db->get()->result();
			?>
			<?php foreach ($list as $key => $value) { ?>
			<div class="mb_bd4_c">
				<p><img onerror="this.src='<?=base_url('publics/avatar/default_avatar.png')?>'" src="publics/avatar/<?=$value->user_avatar?>"></p>
				<p><a href="admin/page_add_edit_user/<?=$value->user_id?>"><?=$value->user_firstname==''&&$value->user_lastname==''?$value->user_email:$value->user_firstname.' '.$value->user_lastname?></a><br><?php echo $this->lang->line('time_line_count_image_user'); ?>： 
					<?=count($this->db->get_where('luxyart_tb_product_img',['user_id'=>$value->user_id])->result());?></p>
			</div>
			<?php } ?>
		</div>
	</div>


	<div class="mb bd5">
		<?php
		$total = count($this->db->get_where('luxyart_tb_product_img')->result());
		$active = count($this->db->get_where('luxyart_tb_product_img',['img_check_status'=>1])->result());
		$unactive = count($this->db->get_where('luxyart_tb_product_img',['img_check_status'=>0])->result());
		?>
		<h2 class="bdu5"><?php echo $this->lang->line('registered_image'); ?>（<?php echo $this->lang->line('current_number_of_registered_pages'); ?>：<?=number_format($total,0,",",",")?><?php echo $this->lang->line('each'); ?>, <?php echo $this->lang->line('active'); ?> <?=number_format($active,0,",",",")?>, <?php echo $this->lang->line('inactive'); ?> <?=number_format($unactive,0,",",",")?>）</h2>
			<div class="mbds5"><h3><?php echo $this->lang->line('last_registered_image'); ?></h3></div>
				<div class="mb_bd5_cont">
					<div class="mb_bd5_c">
						<?php foreach ($register_img as $key => $value) { ?>
						<p><img style="width: 96px; height: 96px;" src="<?=$value->server_path_upload.'/'.$value->file_name_original_img?>"></p>
						<?php } ?>
					</div>
				</div>
	</div>


</article>		

