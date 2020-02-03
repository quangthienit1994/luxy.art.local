	<style type="text/css">
	h2.bd2 {
		display: none;
	}
</style>
<article class="main_board">
	<div class="mb bd2">
		<h2 class="bdu2">会員管理</h2>
		<div class="mb_bd2_cont">
			<div class="mb_bd2_c">
				<p>■&nbsp;ダイヤモンド</p>
					<p><?=number_format(count($this->db->get_where('luxyart_tb_user',['user_level'=>4,'is_active'=>1,'user_status'=>1])->result()),0,",",",")?> 人</p>
			</div>
			<div class="mb_bd2_c">
				<p>■&nbsp;プラチナ</p>
					<p><?=number_format(count($this->db->get_where('luxyart_tb_user',['user_level'=>3,'is_active'=>1,'user_status'=>1])->result()),0,",",",")?> 人</p>
			</div>
			<div class="mb_bd2_c">
				<p>■&nbsp;クリエイター</p>
					<p><?=number_format(count($this->db->get_where('luxyart_tb_user',['user_level'=>2,'is_active'=>1,'user_status'=>1])->result()),0,",",",")?> 人</p>
			</div>
			<div class="mb_bd2_c">
				<p>■&nbsp;レギュラー会員数</p>
					<p><?=number_format(count($this->db->get_where('luxyart_tb_user',['user_status <='=>1])->result()),0,",",",")?> 人</p>
			</div>
			<div class="mb_bd2_c">
				<p>■&nbsp;法人</p>
					
					<p><?=number_format(count($this->db->get_where('luxyart_tb_user',['user_level'=>5,'is_active'=>1,'user_status'=>1])->result()),0,",",",")?> 人</p>
			</div>
		</div>
		
	</div>

<!--- 売上管理 Start   -->
	<div class="mb bd3">
		<h2 class="bdu3">売上管理</h2>
		<div class="mb_bd2_cont">
			<div class="mb_bd2_c">
				<?php
					$this->db->flush_cache();
					$this->db->select('SUM(user_point) as total_point, SUM(user_paymoney_getpoint) as total_user_paymoney_getpoint');
					$count = $this->db->get('luxyart_tb_user')->row();
				?>
				<p>■&nbsp;全会員の保有ポイント</p>
					<p><?=number_format($count->total_point+$count->total_user_paymoney_getpoint,0,",",",")?> LUX</p>
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
				<p>■&nbsp;日間利用ポイント数</p>
					<p><?=number_format($total_point,0,",",",")?> LUX</p>
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
				<p>■&nbsp;月間利用ポイント数</p>
					<p><?=number_format($total_point,0,",",",")?> LUX</p>
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
				<p>■&nbsp;本日の購入ポイント数</p>
					<p><?=number_format($total_point,0,",",",")?> LUX</p>
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
				<p>■&nbsp;月間購入ポイント数</p>
					<p><?=number_format($total_point,0,",",",")?> LUX</p>
			</div>
		</div>
	</div>
<!--- 売上管理 End   -->


	<div class="mb bd4">
		<h2 class="bdu4">ピックアップ</h2>
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
				<p><a href="admin/page_add_edit_user/<?=$value->user_id?>"><?=$value->user_firstname==''&&$value->user_lastname==''?$value->user_email:$value->user_firstname.' '.$value->user_lastname?></a><br>投稿数： 
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
		<h2 class="bdu5">登録画像（現在の登録枚数：<?=number_format($total,0,",",",")?>枚, アクティブ <?=number_format($active,0,",",",")?>, 非アクティブ <?=number_format($unactive,0,",",",")?>）</h2>
			<div class="mbds5"><h3>最新登録画像</h3></div>
				<div class="mb_bd5_cont">
					<div class="mb_bd5_c">
						<?php foreach ($register_img as $key => $value) { ?>
						<p><img style="width: 96px; height: 96px;" src="<?=$value->server_path_upload.'/'.$value->file_name_original_img?>"></p>
						<?php } ?>
					</div>
				</div>
	</div>


</article>		

