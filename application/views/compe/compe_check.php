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



$by_popularity = isset($search_compe_check['by_popularity']) ? $search_compe_check['by_popularity'] : 1;

$horizontal_vertical = isset($search_compe_check['horizontal_vertical']) ? $search_compe_check['horizontal_vertical'] : 0;

$id_color = isset($search_compe_check['id_color']) ? $search_compe_check['id_color'] : "";

$num_page = isset($search_compe_check['num_page']) ? $search_compe_check['num_page'] : 50;



?>



<style>

.flex-images .item img{

	height: 240px !important;

}

.list-search-compe__color .label-select {

    border: 2px solid #fff;

    box-sizing: border-box;

}

.compe-check__btn input[type="button"] {

    width: inherit;

    height: inherit;

    color: #fff;

    font-size: 14px;

    text-align: center;

    border-radius: 3px;

    background: linear-gradient(to bottom, #00d2b9, #00c6ad);

}

</style>



<script>



var by_popularity = "<?php echo $by_popularity; ?>";

var horizontal_vertical = "<?php echo $horizontal_vertical; ?>";



var id_colors = [];

id_color = "<?php echo $id_color; ?>";

if(id_color != ""){

	id_colors = id_color.split(",");

}



var num_page = 50;

var id_competition = "<?php echo $detail_compe->id_competition; ?>";

var img_quantity_com = "<?php echo $detail_compe->img_quantity_com; ?>";



var id_img_chooses = [];

id_img_choose = "<?php echo $id_img_choose; ?>";

if(id_img_choose != ""){

	id_img_chooses = id_img_choose.split(",");
	is_have = 1;
}
else{
	is_have = 0;
}


$(document).ready(function() {

	

	for(i = 0; i < id_colors.length; i++) {

		$(".ul-box-color #color-"+id_colors[i]+" label").addClass("label-select");

	}

	

	for(i = 0; i < id_img_chooses.length; i++) {

		$('.choose_image[value="'+id_img_chooses[i]+'"]').prop('checked', true);

	}

	

	$("#by_popularity").change(function() {

		

		if($("#by_popularity").val() == "<?php echo $this->lang->line('by_popularity'); ?>"){

			by_popularity = 1;

		}

		if($("#by_popularity").val() == "<?php echo $this->lang->line('new_order'); ?>"){

			by_popularity = 2;

		}

		

		search_compe_check();

		

	});

	

	$("#horizontal_vertical").change(function() {

		

		if($("#horizontal_vertical").val() == "<?php echo $this->lang->line('horizontal_vertical'); ?>"){

			horizontal_vertical = 0;

		}

		else if($("#horizontal_vertical").val() == "<?php echo $this->lang->line('vertical'); ?>"){

			horizontal_vertical = 1;

		}

		else if($("#horizontal_vertical").val() == "<?php echo $this->lang->line('horizontal'); ?>"){

			horizontal_vertical = 2;

		}

		

		search_compe_check();

		

	});

	

	$(".ul-box-color li").click(function() {

		

		var index = $(this).index();

		var id = $('.ul-box-color li').get(index).id;

		id_color = id.replace("color-", "");

		

		if($(".ul-box-color li label:eq(" + index + ")").hasClass("label-select")){

			removeValue(id_colors, id_color);

		}

		else{

			id_colors.push(id_color);

		}

		

		search_compe_check();

		

	});

	

	$(".num_page").click(function() {

		

		num_page = $(this).text();

		search_compe_check();

		

	});

	

	$("#reset_search").click(function() {

		

		by_popularity = 1;

		horizontal_vertical = 0;

		id_colors = [];

		num_page = 50;

		search_compe_check();

		

	});

	

	$(".choose_image").click(function() {

		

		id_img_choose = $(this).val();

		

		if($(this).is(":checked")){

			if(id_img_chooses.length >= img_quantity_com ){
	
	
				msg = "<?php echo $this->lang->line('you_have_selected_all_num_images'); ?>";

				msg = msg.replace("{img_quantity_com}", img_quantity_com);

				alert(msg);

				$(this).prop('checked', false);

				return false;

			}

			id_img_chooses.push(id_img_choose);

		}

		else{

			removeValue(id_img_chooses, id_img_choose);

		}

		

		$.ajax({

			type: "POST",

			cache: false,

			url: "<?php echo base_url(); ?>ajax/set_value_choose_image_compe",

			data: "id_img_chooses="+id_img_chooses.join(",")+"&id_competition="+id_competition,

			success: function(data) {

				if(data == 1){

					location.reload();

				}

			}

		});



	});

	

	$("#reset_choose_image").click(function() {

		

		var result = confirm("<?php echo $this->lang->line('are_you_sure_delete_images'); ?>");

		if(result) {

			

			$.ajax({

				type: "POST",

				cache: false,

				url: "<?php echo base_url(); ?>ajax/reset_choose_image",

				data: "id_competition="+id_competition,

				success: function(data) {

					if(data == 1){

						location.reload();

					}

				}

			});

			

		}

		

	});

	

});



function search_compe_check(){

	

	$.ajax({

		type: "POST",

		cache: false,

		url: "<?php echo base_url(); ?>ajax/set_value_search_compe_check",

		data: "by_popularity="+by_popularity+"&horizontal_vertical="+horizontal_vertical+"&id_colors="+id_colors+"&num_page="+num_page+"&id_competition="+id_competition,

		success: function(data) {

			if(data == 1){

				//location.reload();

				location.replace("<?php echo base_url(); ?>compe-check/"+id_competition);

			}

		}

	});

	

}



function removeValue(arr, value) {

    for(var i = 0; i < arr.length; i++) {

        if(arr[i] === value) {

            arr.splice(i, 1);

            break;

        }

    }

    return arr;

}



function delete_image_choose(id_img_choose){

	

	var result = confirm("<?php echo $this->lang->line('are_you_sure_delete_image'); ?>");

	if(result) {

		

		$.ajax({

			type: "POST",

			cache: false,

			url: "<?php echo base_url(); ?>ajax/delete_image_choose",

			data: "id_img_choose="+id_img_choose+"&id_competition="+id_competition,

			success: function(data) {

				if(data == 1){

					location.reload();

				}

			}

		});

		

	}

	

}



function compe_check_conf(){

	

	location.replace('<?php echo base_url()?>compe-check-conf/<?php echo $detail_compe->id_competition; ?>.html');

	

}



</script>



<div class="contents-compe-post-list">

    <div style="background: url(<?php echo base_url(); ?>publics/competition_img/<?php echo $detail_compe->photo_des_com; ?>) center center;background-size: cover" class="ttl-list">

      <div class="ttl-list__filter">

        <div class="ttl-list__box">

          <h1><?php echo $detail_compe->title_com; ?></h1>

        </div>

      </div>

    </div>

    <div class="compe-post-main">

      <div class="breadly">

        <ul>

          <li><a href="<?php echo base_url(); ?>" itemtype="http://data-vocabulary.org/Breadcrumb"><span itemprop="title"><?php echo $this->lang->line('home'); ?></span></a></li>

          <li><a href="<?php echo base_url(); ?>list_compe.html" itemtype="http://data-vocabulary.org/Breadcrumb"><span itemprop="title"><?php echo $this->lang->line('competition'); ?></span></a></li>

          <li><a href="<?php echo base_url(); ?>compe-detail/<?php echo $detail_compe->id_competition; ?>.html" itemtype="http://data-vocabulary.org/Breadcrumb"><span itemprop="title"><?php echo $detail_compe->title_com; ?></span></a></li>

          <li><span itemprop="title"><?php echo $this->lang->line('list_image'); ?></span></li>

        </ul>

      </div>

      <div class="list-search-compe">

        <p class="list-search-compe__value"><?php echo $this->lang->line('count_image_apply'); ?>：<?php echo $count_compe_check; ?><?php echo $this->lang->line('item'); ?></p>

        <div class="list-search-detail__column">

          <span>

		  	<?php

				if($by_popularity == 1){

            		echo $this->lang->line('by_popularity');

				}

				elseif($by_popularity == 2){

					echo $this->lang->line('new_order');

				}

			?>

          </span>

          <div class="list-search-detail__select1">

            <select id="by_popularity" name="by_popularity">

              <option value="<?php echo $this->lang->line('by_popularity'); ?>" <?php echo ($by_popularity == 1) ? 'selected="selected"' : ''; ?>><?php echo $this->lang->line('by_popularity'); ?></option>

              <option value="<?php echo $this->lang->line('new_order'); ?>" <?php echo ($by_popularity == 2) ? 'selected="selected"' : ''; ?>><?php echo $this->lang->line('new_order'); ?></option>

            </select>

          </div>

        </div>

        <div class="list-search-detail__column">

          <span>

		  	<?php

				if($horizontal_vertical == 0){

            		echo $this->lang->line('horizontal_vertical');

				}

				elseif($horizontal_vertical == 1){

            		echo $this->lang->line('vertical');

				}

				elseif($horizontal_vertical == 2){

            		echo $this->lang->line('horizontal');

				}

			?>

          </span>

          <div class="list-search-detail__select2">

            <select id="horizontal_vertical" name="horizontal_vertical">

              <option value="<?php echo $this->lang->line('horizontal_vertical'); ?>" <?php echo ($horizontal_vertical == 0) ? 'selected="selected"' : ''; ?>><?php echo $this->lang->line('horizontal_vertical'); ?></option>

              <option value="<?php echo $this->lang->line('vertical'); ?>" <?php echo ($horizontal_vertical == 1) ? 'selected="selected"' : ''; ?>><?php echo $this->lang->line('vertical'); ?></option>

              <option value="<?php echo $this->lang->line('horizontal'); ?>" <?php echo ($horizontal_vertical == 2) ? 'selected="selected"' : ''; ?>><?php echo $this->lang->line('horizontal'); ?></option>

            </select>

          </div>

        </div>

        

        <div id="color-column" class="list-search-compe__column"><span><?php echo $this->lang->line('color'); ?></span>

          <div id="list-search-detail__color" class="list-search-compe__color">

            <?php

            	if(!empty($list_color)){

					echo '<ul class="ul-box-color">';

					foreach($list_color as $detail_color){

						echo '<li id="color-'.$detail_color->product_color_id.'">';

							echo '<label id="'.$detail_color->name.'" for="color-'.$detail_color->name.'">&nbsp;</label>';

							echo '<input type="checkbox" id="color-'.$detail_color->name.'_input" value="'.$detail_color->name_color.'">';

						echo '</li>';

					}

					echo '</ul>';

				}

			?>

          </div>

        </div>

        <div id="reset_search" class="list-search-compe__reset"><span><?php echo $this->lang->line('reset'); ?></span></div>

        <div class="list-search-compe__page-num">

          <p><?php echo $this->lang->line('display_number'); ?>：

          	<?php

				if($num_page == 50){

			?>

          	  <b><span class="num_page">50</span></b>｜<a><span class="num_page">100</span></a>｜<a><span class="num_page">200</span></a>

            <?php

				}

				elseif($num_page == 100){

			?>

              <a><span class="num_page">50</span></a>｜<b><span class="num_page">100</span></b>｜<a><span class="num_page">200</span></a>

            <?php

				}

				elseif($num_page == 200){

			?>

              <a><span class="num_page">50</span></a>｜<a><span class="num_page">100</span></a>｜<b><span class="num_page">200</span></b>

            <?php

				}

			?>

            

          </p>

        </div>

      </div>

      <div id="compe-check" class="list-img">

        <div class="list-img__wrapper flex-images">

          <?php

		  	foreach($list_image_apply as $detail_image_apply){

		  ?>

          	<div data-w="219" data-h="240" class="item">

				<?php
					if($detail_compe->date_time_agree < date('Y-m-d H:i:s')){
				?>

                <div class="list-img__check">

                  <label>

                    <input type="checkbox" class="choose_image" value="<?php echo $detail_image_apply->id_img;?>"><?php echo $this->lang->line('chooses'); ?>

                  </label>
                  
                </div>
                
                <?php
					}
				?>
                
                <a href="<?php echo base_url()?>detail/<?php echo $detail_image_apply->img_code; ?>.html"><img src="<?php echo $detail_image_apply->server_path_upload;?>/<?php echo $detail_image_apply->file_name_original_img;?>"></a>

              </div>

          <?php

			}

		  ?>

        </div>

        <!--<div class="list-img__next-btn"><a href="list.html"><?php //echo $this->lang->line('to_the_next_page'); ?></a></div>-->

      </div>

      <?php echo $phantrang;?>

      <!--<div class="list-pagenav"><a href="list.html" class="list-pagenav__prev"></a><span class="current">1</span><a href="list.html">2</a><a href="list.html">3</a><a href="list.html">4</a><a href="list.html" class="list-pagenav__next"></a></div>-->

    </div>

  </div>

  <?php
	if($detail_compe->date_time_agree < date('Y-m-d H:i:s')){
  ?>

  <div class="compe-check">

    <div class="compe-check__header">

      <p class="compe-check__find"><?php echo $this->lang->line('currently_selected_image'); ?></p>

      <p class="compe-check__select"><?php echo count($list_image_apply_choose); ?>&nbsp;<?php echo $this->lang->line('sheet'); ?>（<?php echo $this->lang->line('remaining4'); ?><?php echo $this->lang->line('each'); ?>）</p>

      <p class="compe-check__limit"><?php echo $this->lang->line('review_period'); ?>：〜<?php echo date('Y/m/d', strtotime('-1 day', strtotime($detail_compe->date_time_agree))) ?> 23:59</p>

      <div id="reset_choose_image" class="compe-check__reset"><?php echo $this->lang->line('reset_all_selected_control'); ?></div>

    </div>

    <div id="compe-checked" class="list-img">

      <div class="list-img__wrapper flex-images">

      	<?php

			foreach($list_image_apply_choose as $detail_image_apply_choose){

		?>

        	<div data-w="219" data-h="240" class="item">

              <div class="list-img__delete" onclick="delete_image_choose(<?php echo $detail_image_apply_choose->id_img;?>)"><?php echo $this->lang->line('deletes'); ?></div><a href="<?php echo base_url()?>detail/<?php echo $detail_image_apply_choose->img_code; ?>.html"><img src="<?php echo $detail_image_apply_choose->server_path_upload;?>/<?php echo $detail_image_apply_choose->file_name_original_img;?>"></a>

            </div>

        <?php

			}

		?>

      </div>

    </div>

    <div class="compe-check__btn">
    
    
      <?php
	  	if(count($list_image_apply_choose) > 0){
	  ?>

      <input onclick="compe_check_conf();" type="button" value="<?php echo $this->lang->line('proceed_to_competition_review_process'); ?>">
      
      <?php
		}
	  ?>

    </div>

  </div>
  
  <?php
	}
  ?>