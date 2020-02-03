<?php
$CI =& get_instance();
$CI->config->load();
$this->lang->load('dich', 'ja');
?>

<section class="content_box">
            <h3><?php echo $this->lang->line('sales_management_menu'); ?></h3>
            <div class="edit_button3a">
                <div class="edit_button3">
                  <form method="get" action="admin/export/user_order_day">
                    <input type="submit" value="<?php echo $this->lang->line('daily_csv_output'); ?>">
                  </form>
                </div>
                <div class="edit_button3">
                  <form method="get" action="admin/export/user_order_month">
                    <input type="submit" value="<?php echo $this->lang->line('monthly_csv_output'); ?>">
                  </form>
                </div>
                <div class="edit_button3">
                  <form method="get" action="admin/export/user_order_wait">
                    <input type="submit" value="<?php echo $this->lang->line('settlement_not_yet_csv_output'); ?>">
                  </form>
                </div>
                <div class="edit_button3">
                  <form method="get" action="admin/export/user_order_full">
                    <input type="submit" value="<?php echo $this->lang->line('total_sales_csv_output'); ?>">
                  </form>
                </div>
            </div>
          </section>

          <?php $this->load->view('admin/admin/statistic.php') ?>

          <section class="content_box">
            <h3><?php echo $this->lang->line('sales_board'); ?></h3>
            <div class="content_box3a">
              <div class="box3a_conts">
                <div class="">
                  <p>■ <?php echo $this->lang->line('all_members_holding_points'); ?></p>
                  <p>
                    <?php
                      $this->db->flush_cache();
                      $this->db->select('SUM(user_point) as total_point, SUM(user_paymoney_getpoint) as total_user_paymoney_getpoint');
                      $count = $this->db->get('luxyart_tb_user')->row();
                    ?>
                    <?=number_format($count->total_point+$count->total_user_paymoney_getpoint,0,",",",")?> <?php echo $this->lang->line('point'); ?>
                  </p>
                </div>
              </div>
              <div class="box3a_conts">
                <div class="">
                  <p>■ <?php echo $this->lang->line('number_of_points_used_per_day'); ?></p>
                  <p>
                    <?php
                      $this->db->flush_cache();
                      $this->db->where('date_add >=', date("Y-m-d")." 00:00:00");
                      $this->db->where('date_add <=', date("Y-m-d H:i:s"));
                      $this->db->where('(inbox_id = 13 OR inbox_id = 17)', null,false);
                      $this->db->where('status_change_point', 2);
                      $this->db->select('SUM(point) as total_point');
                      $total_point = $this->db->get('luxyart_tb_management_point')->row()->total_point;
                    ?>
                    <?=number_format($total_point,0,",",",")?> <?php echo $this->lang->line('point'); ?>
                </p>
                </div>
              </div>
              <div class="box3a_conts">
                <div class="">
                  <p>■ <?php echo $this->lang->line('number_of_monthly_points_used'); ?></p>
                  <p>
                   <?php
                      $this->db->flush_cache();
                      $this->db->where('date_add >=', date("Y-m")."-00 00:00:00");
                      $this->db->where('date_add <=', date("Y-m-d H:i:s"));
                      $this->db->where('(inbox_id = 13 OR inbox_id = 17)', null,false);
                      $this->db->where('status_change_point', 2);
                      $this->db->select('SUM(point) as total_point');
                      $total_point = $this->db->get('luxyart_tb_management_point')->row()->total_point;
                    ?>
                    <?=number_format($total_point,0,",",",")?> <?php echo $this->lang->line('point'); ?>
                  </p>
                </div>
              </div>
              <div class="box3a_conts">
                <div class="">
                  <p>■ <?php echo $this->lang->line('todays_purchase_point_number'); ?></p>
                  <p>
                    <?php
					  $this->db->flush_cache();
					  $this->db->join('luxyart_tb_list_credit', 'luxyart_tb_list_credit.id_list=luxyart_tb_user_order_credit.id_list','LEFT');
                      $this->db->where('luxyart_tb_user_order_credit.date_order_credit >=', date("Y-m-d")." 00:00:00");
                      $this->db->where('luxyart_tb_user_order_credit.date_order_credit <=', date("Y-m-d H:i:s"));
                     // $this->db->where('(inbox_id = 15)', null,false);
                      $this->db->where('luxyart_tb_user_order_credit.payment_status', 1);
                      $this->db->select('SUM(luxyart_tb_list_credit.point_credit) as total_point');
                      $total_point = $this->db->get('luxyart_tb_user_order_credit')->row()->total_point;
                    ?>
                    <?=number_format($total_point,0,",",",")?> <?php echo $this->lang->line('point'); ?>
                  </p>
                </div>
              </div>
              <div class="box3a_conts">
                <div class="">
                  <p>■ <?php echo $this->lang->line('number_of_points_purchased_monthly'); ?></p>
                  <p>
                   <?php
                      $this->db->flush_cache();
					  $this->db->join('luxyart_tb_list_credit', 'luxyart_tb_list_credit.id_list=luxyart_tb_user_order_credit.id_list','LEFT');
                      $this->db->where('luxyart_tb_user_order_credit.date_order_credit >=', date("Y-m")."-00 00:00:00");
                      $this->db->where('luxyart_tb_user_order_credit.date_order_credit <=', date("Y-m-d H:i:s"));
                     // $this->db->where('(inbox_id = 15)', null,false);
                      $this->db->where('luxyart_tb_user_order_credit.payment_status', 1);
                      $this->db->select('SUM(luxyart_tb_list_credit.point_credit) as total_point');
                      $total_point = $this->db->get('luxyart_tb_user_order_credit')->row()->total_point;
                    ?>
                    <?=number_format($total_point,0,",",",")?> <?php echo $this->lang->line('point'); ?>
                  </p>
                </div>
              </div>
            </div>
          </section>

          <section class="content_box">
            <h3><?php echo $this->lang->line('sales_information'); ?>（<?php echo $this->lang->line('daily_report'); ?>）</h3>
            <div class="content_box3b">
              <table>
                <tr>
                  <th><?php echo $this->lang->line('username'); ?></th>
                  <th><?php echo $this->lang->line('order_date'); ?></th>
                  <th><?php echo $this->lang->line('settlement_status'); ?></th>
                  <th><?php echo $this->lang->line('settlement_type'); ?></th>
                  <th><?php echo $this->lang->line('membership_type'); ?></th>
                  <th><?php echo $this->lang->line('holding_points'); ?></th>
                </tr>

                <?php foreach ($list as $key => $value) { ?>
                <tr>
                  <td><a href="admin/page_add_edit_user/<?=$value->user_id?>"><?=$value->user_firstname==''&& $value->user_lastname==''?$value->user_email:$value->user_lastname.' '.$value->user_firstname?></a></td>
                  <td><?=$value->date_order_credit?></td>
                  <td class="text-center text-danger">
                    <?php if($value->notice_status > 0){ ?>
                    <?php if($value->payment_status==1){ ?>
                      <?php echo $this->lang->line('payment_already_done'); ?>
                    <?php }else{ ?>
                    <form action="admin/status_user_order/<?=$value->id_ordercredit?>" method="post" onsubmit="return submitAjax(this,{'load':1000})">
                        <input type="hidden" name="user_id" value="<?=$value->user_id?>">
                        <input type="hidden" name="credit_id" value="<?=$value->id_list ?>">
                        <input type="hidden" name="point" value="<?=$value->point_credit ?>">
                        <select class="form-control" name="status" style="width: 100px; margin: auto;" onchange="submitForm(this)">
                          <option <?=$value->payment_status==0?'selected=""':''?> value="0"><?php echo $this->lang->line('payment_waiting'); ?></option>
                          <option <?=$value->payment_status==1?'selected=""':''?> value="1"><?php echo $this->lang->line('payment_already_done'); ?></option>
                        </select>
                    </form>
                    <?php } ?>
                    <?php } ?>
                </td>
                  <td>
                    <?php
                    if($value->payment_menthod==2) echo $this->lang->line('master_visa_card');
                    elseif($value->payment_menthod==3) echo $this->lang->line('payment_bit_coin');
                    else echo $this->lang->line('payment_bank_transfer');
                    ?>
                  </td>
                  <td>
                  <?php switch ($value->user_level) {
                  case 1:
                    echo $this->lang->line('regular');
                    break;
                  case 2:
                    echo $this->lang->line('creator');
                    break;
                  case 3:
                    echo $this->lang->line('gold');
                    break;
                  case 4:
                    echo $this->lang->line('platinum');
                    break;
                  case 5:
                    echo $this->lang->line('enterprise');
                    break;
                  
                  default:
                    # code...
                    break;
                } ?>
                  
                </td>
                  <td><?=$value->point_credit ?></td>
                </tr>
                <?php } ?>
              </table>
            </div>
          </section>

<center>
  <ul class="pagination">
    <?php for($i = 1; $i <= $numPage; $i++) { ?>
    <li <?=$i==$currentPage?"class='active'":''?>><a href="admin/page_list_user_order/<?=$i?>"><?=$i?></a></li>
    <?php } ?>
  </ul>
</center>