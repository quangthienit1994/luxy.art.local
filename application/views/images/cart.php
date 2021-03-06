<?php

if(!isset($_SESSION)){

	session_start();

}

$CI =& get_instance();

$CI->config->load();

if(!isset($_SESSION['lang'])){

	$_SESSION['lang'] = 'ja';

}

if($_SESSION['lang'] == "ja"){

	$this->lang_id = 1;

}

else if($_SESSION['lang'] == "vi"){

	$this->lang_id = 2;

}

else if($_SESSION['lang'] == "en"){

	$this->lang_id = 3;

}

$config['language'] = $_SESSION['lang'];

$ngonngu = $config['language'];

$this->lang->load('dich', $ngonngu);

?>

<script>

var user_id = "<?php echo $user_id; ?>";

function remove_cart(rowid){

	var result = confirm("<?php echo $this->lang->line('are_you_want_remove_the_cart'); ?>");

	if(result){

		$.ajax({

			type: "POST",

			cache: false,

			url: "<?php echo base_url(); ?>shopping/remove_cart",

			data: "rowid="+rowid,

			success: function(data) {

				if(data == 1){

					location.reload();

				}

			}

		});

		

	}

	

}

function update_cart(rowid, id_img){

	

	id_img_size = $("#id_img_size_"+id_img).val();

	

	$.ajax({

		type: "POST",

		cache: false,

		url: "<?php echo base_url(); ?>shopping/update_cart",

		data: "rowid="+rowid+"&id_img_size="+id_img_size,

		success: function(data) {

			if(data == 1){

				location.reload();

			}

		}

	});

	

}

function buy_picture(id_img_size, rowid){

	save_to_cart = 0;

	if(user_id == ""){

		window.location.href = "<?php echo base_url(); ?>login.html";

		return;

	}

	$.ajax({

			type: "POST",

			cache: false,

			url: "<?php echo base_url(); ?>shopping/check_image_bought_ajax",

			data: "id_img_size=" + id_img_size+"&rowid=" + rowid,

			success: function(data) {

				if(data != 1){
					
					msg1 = data.split("{luxyart}");
					if(msg1[1] != ""){
						alert(msg1[1]);
						save_to_cart = 1;
					}
					else{
						window.location.href = "<?php echo base_url(); ?>login.html";
						return;
					}
					
					
				}

			}
			,
			async: false

		});	
		
		
		if(save_to_cart == 0){

	var result = confirm("<?php echo $this->lang->line('are_you_want_pay_for_buy_this_image'); ?>");

	if(result){

		$("#btnBuyPicture_"+id_img_size).text("<?php echo $this->lang->line('processing'); ?>");

		$('#btnBuyPicture_'+id_img_size).attr("disabled", true);
		
		$.ajax({

			type: "POST",

			cache: false,

			url: "<?php echo base_url(); ?>shopping/save_to_cart",

			data: "id_img_size=" + id_img_size+"&rowid=" + rowid,

			success: function(data) {

				$("#btnBuyPicture_"+id_img_size).html('<?php echo $this->lang->line('continue'); ?>');

				$("#btnBuyPicture_"+id_img_size).removeAttr("disabled");

				if(data == 1){

					location.reload();

				}
				else{
					
					msg1 = data.split("{luxyart}");
					
					alert(msg1[1]);
					
				}

			}

		});

		

			

	}
	
		}

	

}

</script>
<style>
@media (max-width: 768px)
{
		.breadly {display:block!important;padding:15px 0px !important}
		.contents-cart__sub{margin:0!important}
}
}
.cart-list__btn input[type="button"] {
    width: 100%;
    height: 32px;
    line-height: 32px;
    color: #fff;
    font-size: 14px;
    text-align: center;
    background: #00cbb4 !important;
    border-radius: 3px;
    display: block;
    cursor: pointer;
}
</style>
<div class="contents-cart">

    <div class="contents-cart__wrapper clearfix">

      <div class="breadly">

        <ul>

          <li><a href="<?php echo base_url(); ?>" itemtype="http://data-vocabulary.org/Breadcrumb	"><span itemprop="title"><?php echo $this->lang->line('home'); ?></span></a></li>

          <li><span itemprop="title"><?php echo $this->lang->line('inside_the_cart'); ?></span></li>

        </ul>

      </div>

      <div class="contents-cart__sub">

        <div class="cart-credit">

          <div class="cart-credit__all">

            <p><?php echo $this->lang->line('total'); ?><span><span><?php echo $subtotal; ?></span>&nbsp;<?php echo $this->lang->line('point'); ?></span></p>

          </div>

          <?php

		  	if($user_id != ""){

		  ?>

          <div class="cart-credit__have">

            <p>

				<?php echo $this->lang->line('available_credits'); ?>

            	<span><?php echo round($detail_user->user_paymoney_getpoint + $detail_user->user_point,2); ?></span>

            </p>

          </div>

          <?php

			}

		  ?>

        </div>

        <div class="credit-buy">

          <div class="credit-buy__btn"><a href="<?php echo base_url()?>credit.html"><?php echo $this->lang->line('purchase_credit'); ?></a></div>

          <div class="credit-buy__link"><a href="<?php echo base_url()?>page/guideline.html"><?php echo $this->lang->line('click_here_for_view_more'); ?></a></div>

        </div>

      </div>

      <div class="contents-cart__main">

        <h2 class="contents-cart__find"><?php echo $this->lang->line('inside_the_cart'); ?></h2>

        <div class="cart-list">

        	

          <?php
		  
		  	if($is_save_to_cart == 1){
				echo '<p class="red">'.$this->lang->line('you_already_pay_bought_image_success').'</p>';
			}

		  	if(!empty($cart_check)){

				foreach($cart_check as $detail_cart_check){

					

					$add_cart = $CI->shopping_model->get_data_add_cart($detail_cart_check['id'])->row();

					$user_fullname = $CI->function_model->get_fullname($add_cart->user_id, $this->lang_id);

					$id_img = $add_cart->id_img;

					$type_size_description = $add_cart->type_size_description;

					$list_product_img_size = $CI->function_model->getProductImgSize($id_img);
					
					$is_check_image_bought = $CI->shopping_model->check_image_bought($id_img,$detail_cart_check["id"],$user_id);
						
					if($is_check_image_bought == 1){
						
						$bought_buy = $this->lang->line('you_bought_the_image_size_then');
						$bought_buy = str_replace("{type_size_description}", $type_size_description, $bought_buy);
						echo "<span class='red'>".$bought_buy."</span>";
						
					}

			  ?>

				

				<div class="cart-list__list">

				<div class="cart-list__left">

				  <div class="cart-list__thumb">

					<div><img src="<?php echo $add_cart->server_path_upload ?>/<?php echo $add_cart->file_name_watermark_img ?>"></div>

				  </div>

				  <div class="cart-list__meta">

					<p>

                    	<a href="<?php echo base_url()?>detail/<?php echo $add_cart->img_code ?>.html"><?php echo $add_cart->id_img ?> : <?php echo $add_cart->img_title ?></a><br />

						<?php echo $this->lang->line('by'); ?> <a href="<?php echo base_url()?>timeline/<?php echo $add_cart->user_id;?>.html"><?php echo $user_fullname; ?></a><br />
                        
                        <?php
							if($add_cart->img_is_sold == 0){
						?>

                        <select id="id_img_size_<?php echo $add_cart->id_img; ?>" name="product_img_size" onchange="update_cart('<?php echo $detail_cart_check["rowid"]; ?>', <?php echo $add_cart->id_img; ?>)">

                        	<?php

								foreach($list_product_img_size as $detail_product_img_size){
									
										if($detail_product_img_size->size_stock > 0 || $add_cart->is_limit == 0){
	
											if($type_size_description == $detail_product_img_size->type_size_description){
		
												echo '<option selected="selected" value="'.$detail_product_img_size->id_img_size.'">'.$detail_product_img_size->type_size_description.'</option>';
		
											}
		
											else{
		
												echo '<option value="'.$detail_product_img_size->id_img_size.'">'.$detail_product_img_size->type_size_description.'</option>';							
		
											}
										
										}
									
									}

							?>

                        </select>
                        
                        <?php
							}
						?>

                  	</p>

					<div class="delete" onclick="remove_cart('<?php echo $detail_cart_check["rowid"]; ?>');"><?php echo $this->lang->line('delete'); ?></div>

					<p class="cart-list__credit"><span><?php echo $detail_cart_check['price']; ?></span>&nbsp;<?php echo $this->lang->line('point'); ?></p>

				  </div>

				</div>

				<div class="cart-list__right">

				  <div class="cart-list__btn">

					<?php
						
						if($add_cart->img_is_sold == 1){
					?>
                    		<span class="shotage"><?php echo $this->lang->line('this_image_is_out_of_stock'); ?></span>
                    <?php
							
						}
						else{

						if($user_id == "" || $detail_cart_check['price'] <= ($detail_user->user_paymoney_getpoint + $detail_user->user_point)){
								
							$is_check_image_bought = $CI->shopping_model->check_image_bought($id_img,$add_cart->id_img_size,$user_id);
							
							
							if($is_check_image_bought == 0){

					?>

						<button style="width: 100%;height: 32px;line-height: 32px;color: #fff;font-size: 14px;text-align: center;background: #00cbb4 !important;border-radius: 3px;display: block;cursor: pointer;" id="btnBuyPicture_<?php echo $add_cart->id_img_size ?>" onclick="buy_picture(<?php echo $add_cart->id_img_size ?>, '<?php echo $detail_cart_check["rowid"]; ?>');"><?php echo $this->lang->line('buy_picture'); ?></button>

					<?php
							}

						}

						else{

					?>

						<span class="shotage"><?php echo $this->lang->line('i_do_not_have_enough_credit'); ?></span>

					<?php
					
							}

						}

					?>

				  </div>

				</div>

			  </div>

				

			  <?php

				}

			}

			else{

				echo $this->lang->line('you_are_not_buying');

			}

		  ?>

          

        </div>

      </div>

    </div>

  </div>