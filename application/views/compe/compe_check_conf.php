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



<style>

.compe-check-conf-list__submit input[type="button"] {

    width: inherit;

    height: 52px;

    line-height: 52px;

    color: #fff;

    font-size: 14px;

    text-align: center;

    background: #00cbb4;

    border-radius: 3px;

}

</style>



<script>



var id_competition = "<?php echo $detail_compe->id_competition; ?>";
var img_quantity_com = "<?php echo $detail_compe->img_quantity_com; ?>";
var count_image_apply_choose = "<?php echo count($list_image_apply_choose); ?>";


$(document).ready(function() {

	

	$("#btn_review_competition").click(function() {
		
		
		if(count_image_apply_choose != img_quantity_com){
			
			msg = "<?php echo $this->lang->line('please_choose_more_num_image'); ?>";
			msg = msg.replace("{num}", (img_quantity_com-count_image_apply_choose));
			alert(msg);
			return false;
			
		}
		

		var result = confirm("<?php echo $this->lang->line('are_you_sure_finish_apply_competition'); ?>");

		if(result) {

			

			$.ajax({

				type: "POST",

				cache: false,

				url: "<?php echo base_url(); ?>ajax/review_competition",

				data: "id_competition="+id_competition,

				success: function(data) {

					if(data == 1){

						location.replace("<?php echo base_url(); ?>compe-check-comp.html");

					}

				}

			});

			

		}

		

	});

	

});



</script>



<div class="contents-compe-check-conf">

    <div style="background: url(<?php echo base_url(); ?>publics/competition_img/<?php echo $detail_compe->photo_des_com; ?>) center center;background-size: cover" class="ttl-list">

      <div class="ttl-list__filter">

        <div class="ttl-list__box">

          <h1><?php echo $detail_compe->title_com; ?></h1>

        </div>

      </div>

    </div>

    <div class="compe-check-conf-main">

      <div class="compe-check-conf-main__header">

        <div>

          <dl>

            <dt><?php echo $this->lang->line('prize'); ?></dt>

            <dd><?php echo $detail_compe->point_img_com; ?><?php echo $this->lang->line('point'); ?><span>×<?php echo $detail_compe->img_quantity_com; ?><?php echo $this->lang->line('each'); ?></span></dd>

          </dl>

        </div>

        <div>

          <dl>

          	<?php

				if($this->lang_id == 1){

			?>

                <dt><?php echo $this->lang->line('review_period'); ?></dt>

                <dd><?php echo date('Y-m-d', strtotime($detail_compe->date_time_agree)); ?>(<?php echo $this->lang->line('day'); ?>)<br />23:59 <?php echo $this->lang->line('to'); ?></dd>

            <?php

				}

				else{

			?>

            	<dt><?php echo $this->lang->line('review_period'); ?></dt>

                <dd><?php echo $this->lang->line('to'); ?>&nbsp;<?php echo date('Y-m-d', strtotime($detail_compe->date_time_agree)); ?>&nbsp;(<?php echo $this->lang->line('day'); ?>)&nbsp;23:59</dd>

            <?php

				}

			?>

          </dl>

        </div>

      </div>

      <div class="compe-check-conf-list">

        <ul>

          <?php

			foreach($list_image_apply_choose as $detail_image_apply_choose){

		  ?>

          		<li>

                <div class="compe-check-conf-list__thumb">

                  <div class="compe-check-conf-list__inner"><a href="<?php echo base_url()?>detail/<?php echo $detail_image_apply_choose->img_code; ?>.html"><img src="<?php echo $detail_image_apply_choose->server_path_upload;?>/<?php echo $detail_image_apply_choose->file_name_original_img;?>"></a></div>

                </div>

              </li>

          <?php

			}

		  ?>

        </ul>

      </div>

      

      <?php

	  	if($detail_compe->status_com == 2){

	  ?>

      

      <div class="compe-check-conf-list__btn">

        <div class="compe-check-conf-list__return">

        	<a href="<?php echo base_url()?>compe-check/<?php echo $detail_compe->id_competition; ?>.html"><?php echo $this->lang->line('select_a_photo_again'); ?></a>

      	</div>

        <div class="compe-check-conf-list__submit">

          <?php

			if($detail_compe->date_time_agree < date('Y-m-d H:i:s')){

		  ?>

          <input id="btn_review_competition" type="button" value="<?php echo $this->lang->line('complete_competition_review'); ?>">

          <?php

			}

		  ?>

        </div>

      </div>

      

      <?php

		}

	  ?>

      

    </div>

  </div>