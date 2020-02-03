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
$logged_in = $CI->function_model->get_logged_in();
$user_id = $logged_in['user_id'];
$CI->load->model('manager_user_model');
$user = $CI->manager_user_model->get_user_id($user_id);
?>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript" src="https://www.simplify.com/commerce/v1/simplify.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>publics/js/jquery.creditCardValidator.js"></script>
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
			var carnumber = $('#cc-number').val();
			var cvc = $('#cc-cvc').val();
			var exp_month = $("#cc-exp-month").val();
			var exp_year = $("#cc-exp-year").val();
			var card_name = $("#cardname").val();
			var card_id = '<?php echo $card_id;?>';
            $.ajax({
					type: "POST",cache: false,
					url: "<?php echo base_url(); ?>user/do_user_edit_card_bank",
					data: "card_id="+card_id+"&carnumber="+carnumber+"&cvc="+cvc+"&exp_month="+exp_month+"&exp_year=20"+exp_year+"&card_name="+card_name,
					success: function(data) {
						var msg=data.split("{return_do_user_edit_card_bank}");
						if(msg[0] == 1){
							$('.alert--green').css('display','block');
							$('.alert--green p').html("<?php echo $this->lang->line('notice_user_edit_card_ok'); ?>");
							setTimeout(function() {
							   window.location.reload();
							}, 5000);
						}
						if(msg[0] == 0){
							$('.alert--green').css('display','block');
							$('.alert--green p').html("<?php echo $this->lang->line('notice_user_edit_card__not_ok'); ?>");
							setTimeout(function() {
							   $('.alert--green').fadeIn('slow');
							}, 5000);
						}
					},
					async: false
				})
        }
    }
    $(document).ready(function() {
		$('.alert--green').css('display','none');
        $("#simplify-payment-form").on("submit", function() {
            // Disable the submit button
            $("#process-payment-btn").attr("disabled", "disabled");
			
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
        });
    });
</script>
<div class="contents-mypage">
    <div class="contents-mypage__wrapper clearfix">
      <div class="breadly">
        <ul>
          <li><a href="<?php echo base_url()?>"><span itemprop="title"><?php echo $this->lang->line('home'); ?></span></a></li>
          <li><a href="<?php echo base_url()?>mypage.html"><span itemprop="title"><?php echo $this->lang->line('mypage'); ?></span></a></li>
          <li><span itemprop="title"><?php echo $this->lang->line('title_user_manager_card_edit'); ?></span></li>
        </ul>
      </div>
      <?php $this->load->view('user/sidebar_user.php') ?>
      <div class="mypage-main">
            <h2 class="contents-mypage__find"><?php echo $this->lang->line('title_user_manager_card_edit'); ?></h2>
            <div class="alert--green">
              <p></p>
            </div>
            <div id="error"></div>
            <div class="mypage-form">
            <?php if($error_edit==0){?>
            <form id="simplify-payment-form" action="" method="POST">
              	<div id="error"></div>
                <dt><?php echo $this->lang->line('user_credit_order_label_account_number_credit'); ?><span class="mypage-form__label"><?php echo $this->lang->line('p_dang_ky_form_yeucau')?></span></dt>
                <dd>
                    <input id="cc-number" name="cc-number" maxlength="20" autocomplete="off" type="text" value="<?php if(@$load_bank_user['card_number']) {echo $load_bank_user['card_number'];}?>" placeholder="<?php echo $this->lang->line('user_credit_order_account_number_credit_input_text'); ?>">
                </dd>
                <dt>CVC<span class="mypage-form__label"><?php echo $this->lang->line('p_dang_ky_form_yeucau')?></span></dt>
                <dd>
                    <input id="cc-cvc" name="cc-cvc" type="text" maxlength="4" autocomplete="off" value="<?php if(@$load_bank_user['card_cvc']) {echo $load_bank_user['card_cvc'];}?>"  placeholder="<?php echo $this->lang->line('user_credit_order_account_number_credit_input_cvc'); ?>">
                </dd>
                <dt><?php echo $this->lang->line('card_exp_mm')?><span class="mypage-form__label"><?php echo $this->lang->line('p_dang_ky_form_yeucau')?></span></dt>
                <dd>
                    <select id="cc-exp-month" name="cc-exp-month">
                      <option value="01" <?php if(@$load_bank_user['exp_month']==1) {echo 'selected';}?> >01</option>
                      <option value="02" <?php if(@$load_bank_user['exp_month']==2) {echo 'selected';}?> >02</option>
                      <option value="03" <?php if(@$load_bank_user['exp_month']==3) {echo 'selected';}?> >03</option>
                      <option value="04" <?php if(@$load_bank_user['exp_month']==4) {echo 'selected';}?> >04</option>
                      <option value="05" <?php if(@$load_bank_user['exp_month']==5) {echo 'selected';}?> >05</option>
                      <option value="06" <?php if(@$load_bank_user['exp_month']==6) {echo 'selected';}?> >06</option>
                      <option value="07" <?php if(@$load_bank_user['exp_month']==7) {echo 'selected';}?> >07</option>
                      <option value="08" <?php if(@$load_bank_user['exp_month']==8) {echo 'selected';}?> >08</option>
                      <option value="09" <?php if(@$load_bank_user['exp_month']==9) {echo 'selected';}?> >09</option>
                      <option value="10" <?php if(@$load_bank_user['exp_month']==10) {echo 'selected';}?> >10</option>
                      <option value="11" <?php if(@$load_bank_user['exp_month']==11) {echo 'selected';}?> >11</option>
                      <option value="12" <?php if(@$load_bank_user['exp_month']==12) {echo 'selected';}?> >12</option>
                    </select>
                    
                </dd>
                <dt><?php echo $this->lang->line('card_exp_yyyy')?><span class="mypage-form__label"><?php echo $this->lang->line('p_dang_ky_form_yeucau')?></span></dt>
                <dd>
                    <select id="cc-exp-year" name="cc-exp-year">
                      <option value="18" <?php if(@$load_bank_user['exp_year']==2018) {echo 'selected';}?> >2018</option>
                      <option value="19" <?php if(@$load_bank_user['exp_year']==2019) {echo 'selected';}?> >2019</option>
                      <option value="20" <?php if(@$load_bank_user['exp_year']==2020) {echo 'selected';}?> >2020</option>
                      <option value="21" <?php if(@$load_bank_user['exp_year']==2021) {echo 'selected';}?> >2021</option>
                      <option value="22" <?php if(@$load_bank_user['exp_year']==2022) {echo 'selected';}?> >2022</option>
                      <option value="23" <?php if(@$load_bank_user['exp_year']==2023) {echo 'selected';}?> >2023</option>
                      <option value="24" <?php if(@$load_bank_user['exp_year']==2024) {echo 'selected';}?> >2024</option>
                      <option value="25" <?php if(@$load_bank_user['exp_year']==2025) {echo 'selected';}?> >2025</option>
                      <option value="26" <?php if(@$load_bank_user['exp_year']==2026) {echo 'selected';}?> >2026</option>
                    </select>
                </dd>
                <div class="mypage-form__btn">
                 <input id="process-payment-btn" form="simplify-payment-form"  type="submit" name="submit" value="<?php echo $this->lang->line('button_save_card_edit')?>">
                </div>
          </form>
            <?php }else{?>
            <?php echo $this->lang->line('error_card_edit_not_found')?>
            <?php }?>
            </div>
          </div>
    </div>
</div>