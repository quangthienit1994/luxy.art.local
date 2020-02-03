<?php
if(!isset($_SESSION)){
	session_start();
}
$CI =& get_instance();
$CI->config->load();
if(!isset($_SESSION['lang'])){
	$_SESSION['lang'] = 'ja';
}
$config['language'] = $_SESSION['lang'];
$ngonngu = $config['language'];
$this->lang->load('dich', $ngonngu);
?>
<style>
.credit-order-fix__btn button {
    width: 100%;
    height: 44px;
    line-height: 44px;
    color: #fff;
    font-size: 15px;
    font-weight: bold;
    text-align: center;
    background: gradient(linear, left top, left bottom, from(#ff7949), to(#ff683f));
    background: -webkit-gradient(linear, left top, left bottom, from(#ff7949), to(#ff683f));
    background: linear-gradient(to bottom, #ff7949, #ff683f);
    border-radius: 3px;
    display: block;
}
@media screen and (max-width:768px){
        #simplify-payment-form{
          display: -webkit-box;
    display: -moz-box;
    display: box;

    -webkit-box-orient: vertical;
    -moz-box-orient: vertical;
    box-orient: vertical;
        }

		#b {
    -webkit-box-ordinal-group: 2;
    -moz-box-ordinal-group: 2;
    box-ordinal-group: 2;
}
#a {
    -webkit-box-ordinal-group: 3;
    -moz-box-ordinal-group: 3;
    box-ordinal-group: 3;
}
      }
</style>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript" src="https://www.simplify.com/commerce/v1/simplify.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>publics/js/jquery.creditCardValidator.js"></script>
<script src="https://js.stripe.com/v3/"></script>
<script type="text/javascript">
    function simplifyResponseHandler(data) {
		
        var $paymentForm = $("#simplify-payment-form");
        // Remove all previous errors
        $(".error").remove();
        // Check for errors
        if (data.error) {
            // Show any validation errors
            if (data.error.code == "validation") {
                var fieldErrors = data.error.fieldErrors,
                        fieldErrorsLength = fieldErrors.length,
                        errorList = "";
                for (var i = 0; i < fieldErrorsLength; i++) {
					if(fieldErrors[i].message == '')
					{
						errorList += "<div class='error' style='color:red'><?php echo $this->lang->line('card_error_exp'); ?></div>";
					}
					else if(fieldErrors[i].message == 'Field cannot be empty' || fieldErrors[i].message == 'Card is expired')
					{
						errorList += "<div class='error' style='color:red'><?php echo $this->lang->line('card_error_account_number_empty'); ?></div>";
					}
					else if(fieldErrors[i].message == 'CVC is invalid')
					{
						errorList += "<div class='error' style='color:red'><?php echo $this->lang->line('card_error_invalid_cvc'); ?></div>";
					}
					else if(fieldErrors[i].message == 'Invalid card bin')
					{
						errorList += "";
					}
					else if(fieldErrors[i].message == 'payments.command.api.APICardCommand.number.cardTypeBin.error.payments.command.api.APICardCommand.number')
					{
						errorList += "";
					}
					else if(fieldErrors[i].message == 'Enter a valid card number')
					{
						errorList += "";
					}
					else if(fieldErrors[i].message == 'Card number must be at least 13 digits')
					{
						errorList += "<div class='error' style='color:red'><?php echo $this->lang->line('card_error_at_leaset_13'); ?></div>";
					}
					else
					{
                    		errorList += "<div class='error' style='color:red'>Error: '" + fieldErrors[i].field +
                            "' is invalid - " + fieldErrors[i].message + "</div>";
					}
							
                }
                // Display the errors
                $("#error").html(errorList);
            }
            // Re-enable the submit button
            $("#process-payment-btn").removeAttr("disabled");
        } else {
            // The token contains id, last4, and card type
            var token = data["id"];
			
            // Insert the token into the form so it gets submitted to the server
            $paymentForm.append("<input type='hidden' name='token' value='tok_" + token + "' />");
			$('#cc-number').validateCreditCard(function(result) {
             var card_type = result.card_type.name;
			 $( "#cardname" ).remove();
			 $paymentForm.append("<input id='cardname' type='hidden' name='card_type' value='" + card_type + "' />"); 
        	});
            // Submit the form to the server
            $paymentForm.get(0).submit();
        }
    }
    $(document).ready(function() {
        $("#simplify-payment-form").on("submit", function() {
            // Disable the submit button
            $("#process-payment-btn").attr("disabled", "disabled");
			$val_check = $("input[name='payment']:checked").val();
			if($val_check=='new_card')
			{
            // Generate a card token & handle the response
            SimplifyCommerce.generateToken({
                key: "sbpb_ZDQ3ZjJlMjEtOWEzOC00ZTc0LTliODYtMjU5MzNhNjdmMWNl",
                card: {
                    number: $("#cc-number").val(),
                    cvc: $("#cc-cvc").val(),
                    expMonth: $("#cc-exp-month").val(),
                    expYear: $("#cc-exp-year").val()
                }
            }, simplifyResponseHandler);
            // Prevent the form from submitting
            return false;
			}
			else
			{
				return true;
			}
        });
    });
</script>
<div class="contents-cart">
        <div id="parent" class="contents-cart__wrapper clearfix">
          <div class="breadly">
            <ul>
              <li><a href="<?php echo base_url()?>"><span itemprop="title"><?php echo $this->lang->line('path_home'); ?></span></a></li>
              <li><span itemprop="title"><?php echo $this->lang->line('user_credit_order_h2'); ?></span></li>
            </ul>
          </div>
          <?php if($error_xuly==1){?>
           <?php if($card_pay==1){?>
          	<div class="alert--green">
              <p><?php echo $this->lang->line('user_credit_order_notice_ok_case_pay_by_credit_card'); ?></p>
              <div class="alert__delete">×</div>
            </div>
          <?php }if($card_pay==2){?>
          	<div class="alert--green">
              <p><?php echo $this->lang->line('user_credit_order_notice_ok_case_pay_by_bank_transfer'); ?></p>
              <div class="alert__delete">×</div>
            </div>
          <?php }if($card_pay_loi==0){ ?>
              <p style="color:red">
			  <?php 
			  if($Errors=="Your card number is incorrect.")
			  {
				  echo $this->lang->line('your_card_number_is_incorrect');
			  }
			  else if($Errors=="Your card was declined. Your request was in live mode, but used a known test card.")
			  {
				  echo $this->lang->line('your_card_number_is_declined');
				}
			else if($Errors=="Error Payment with Stripe")
			  {
				  echo $this->lang->line('error_connect_payment_with_stripe');
				}
			  else if($Errors=="Your card's expiration month is invalid.")
			  {
				  echo $this->lang->line('your_card_expiration_month_is_invalid');
			} 
			else {
				print_r($Errors);
			}?></p>
          <?php }?>
          <form id="simplify-payment-form" action="" method="POST">
            <div id="a" class="contents-cart__sub">
              <div class="credit-order-fix">
                <p class="credit-order-fix__info"><?php echo @$name_credit?> <?php echo $point_credit;?><?php echo $this->lang->line('user_credit_list_col_head_1');?><br /><span><?php echo $this->lang->line('user_credit_order_show_date_text'); ?>：<?php echo date('Y-m-d',strtotime(date("Y-m-d", mktime()) . " + 365 day"));?></span></p>
                <p class="credit-order-fix__price"><?php echo $this->lang->line('user_credit_order_show_text_point_select'); ?><span><?php echo $money_pay_credit;?><span><?php echo $this->lang->line('user_credit_list_show_currency'); ?></span></span></p>
                <div class="credit-order-fix__btn">
                <button id="process-payment-btn" type="submit" form="simplify-payment-form" value="<?php echo $this->lang->line('user_credit_order_show_button_buy_select'); ?>"><?php echo $this->lang->line('user_credit_order_show_button_buy_select'); ?></button></div>
              </div>
            </div>
            <div id="b" class="contents-cart__main"> 
              <h2 class="contents-cart__find"><?php echo $this->lang->line('user_credit_order_h2'); ?> <?php echo @$name_credit?></h2>
              <div class="payment-select">
              	<div id="error"></div>
                <div class="payment-select__list choice">
                  <label class="payment-select__inner">
                    <input type="radio" name="payment" value="bank_transfer" checked="checked">
                    <p class="payment-select__name"><?php echo $this->lang->line('user_credit_order_label_bank_transfer'); ?></p>
                    <div class="payment-select__name" style="font-size:12px">
                    <!--<strong><?php echo $this->lang->line('user_credit_order_label_bank_account_number'); ?></strong> : 0000000<br/>
                    <strong><?php echo $this->lang->line('user_credit_order_label_bank_account_branch_name'); ?></strong> : xxxxxx <br/>
                    <strong><?php echo $this->lang->line('user_credit_order_label_bank_account_name'); ?></strong> : xxxxxxxx<br/>
                    <strong><?php echo $this->lang->line('user_credit_order_label_bank_account_type'); ?></strong> : xxxxxxx-->
                    三井住友銀行（銀行コード：0009）<br/>
					銀座支店（支店コード：026）<br/>
					普通　8441432<br/>
					ｶ)ﾗｸｼｰｱｰﾄ</div>
                  </label>
                </div>
                <?php if(!empty($user_card)){ foreach($user_card as $load_list_user_card){?>
                <div class="payment-select__list">
                  <label class="payment-select__inner">
                    <input type="radio" name="payment" value="<?php echo $load_list_user_card->card_id;?>">
                    <p class="payment-select__name"><?php echo $this->lang->line('user_credit_order_label_credit_card_text_have_already'); ?><span class="credit-info">	<?php echo '************';?><?php echo substr($load_list_user_card->account_number, -4);?></span></p>
                  </label>
                </div>
                <?php }} ?>
                <div class="payment-select__list">
                  <label class="payment-select__inner">
                    <input type="radio" name="payment" value="new_card">
                    <p class="payment-select__name"><?php echo $this->lang->line('user_credit_order_label_credit_card_text_input_new'); ?></p>
                    <div class="payment-select__card">
                      <div class="card-name">
                        <label><?php echo $this->lang->line('user_credit_order_label_account_number_credit'); ?></label>
                        <input id="cc-number" name="cc-number" maxlength="20" autocomplete="off" type="text" placeholder="<?php echo $this->lang->line('user_credit_order_account_number_credit_input_text'); ?>">
                      </div>
                      <div class="card-num">
                        <label>CVC</label>
                        <input id="cc-cvc" name="cc-cvc" type="text" maxlength="4" autocomplete="off" placeholder="<?php echo $this->lang->line('user_credit_order_account_number_credit_input_cvc'); ?>">
                      </div>
                      <div class="card-remit">
                        <label>Exp(MM/YY)</label>
                        <select id="cc-exp-month" name="cc-exp-month">
                          <option varlue="01">01</option>
                          <option varlue="02">02</option>
                          <option varlue="03">03</option>
                          <option varlue="04">04</option>
                          <option varlue="05">05</option>
                          <option varlue="06">06</option>
                          <option varlue="07">07</option>
                          <option varlue="08">08</option>
                          <option varlue="09">09</option>
                          <option varlue="10">10</option>
                          <option varlue="11">11</option>
                          <option varlue="12">12</option>
                        </select>
                        <select id="cc-exp-year" name="cc-exp-year">
                        	<option value="18">2018</option>
                            <option value="19">2019</option>
                            <option value="20">2020</option>
                            <option value="21">2021</option>
                            <option value="22">2022</option>
                            <option value="23">2023</option>
                            <option value="24">2024</option>
                            <option value="25">2025</option>
                            <option value="26">2026</option>
                        </select>
                      </div>
                    </div>
                  </label>
                </div>
                <!--
                <div class="payment-select__list">
                  <label class="payment-select__inner">
                    <input type="radio" name="payment" value="bit_coin">
                    <p class="payment-select__name"><?php echo $this->lang->line('user_credit_order_label_bitcoin'); ?></p>
                  </label>
                </div>-->
              </div>
            </div>
          </form>
          <?php }else{?>
          <?php echo $this->lang->line('user_credit_order_error_card_or_package_not_found'); ?>
          <?php }?>
        </div>
      </div>