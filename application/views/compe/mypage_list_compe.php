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

.btn-post-competition{
   width: 150px !important;
}

</style>

<script>



function download_picture(id_competition){

	

	$.ajax({

		type: "POST",cache: false,

		url: "<?php echo base_url(); ?>ajax/download_picture",

		data: "id_competition="+id_competition,

		success: function(data) {

		},

		async: false

	})

	

}



</script>



<div class="contents-mypage">

    <div class="contents-mypage__wrapper clearfix">

      <div class="breadly">

        <ul>

          <li><a href="<?php echo base_url(); ?>" itemtype="http://data-vocabulary.org/Breadcrumb"><span itemprop="title"><?php echo $this->lang->line('home'); ?></span></a></li>

          <li><a href="<?php echo base_url(); ?>mypage" itemtype="http://data-vocabulary.org/Breadcrumb"><span itemprop="title"><?php echo $this->lang->line('mypage'); ?></span></a></li>

          <li><span itemprop="title"><?php echo $this->lang->line('list_competition'); ?></span></li>

        </ul>

      </div>

      <?php $this->load->view('user/sidebar_user.php') ?>

      <div class="mypage-main">

            <h2 class="contents-mypage__find"><?php echo $this->lang->line('management_competition'); ?></h2>

            <div class="mypage-compe-list">

              <ul>

              

              	<?php
					
					if(!empty($list_compe)){
					
					foreach($list_compe as $detail_list_compe){

				?>

                		

                        <li>

                          <div class="mypage-compe-list__thumb">

                            <div class="mypage-compe-list__thumb-inner"><img src="<?php echo base_url(); ?>publics/competition_img/<?php echo $detail_list_compe->photo_des_com; ?>"></div>

                          </div>

                          <div class="mypage-compe-list__info">

                            <div class="mypage-compe-list__ttl">

                              <p>

                              	<span>

							  		<?php

										if($detail_list_compe->status_com == 0){

                                    		echo $this->lang->line('pending');//dung

										}

										else if($detail_list_compe->status_com == 1){

											//echo $this->lang->line('hiring');
											//don't allow apply => can edit
											//allow apply => can edit
											
											$date_start_com = $detail_list_compe->date_start_com;
											
											if($date_start_com > date('Y-m-d H:i:s')){

												echo $this->lang->line('do_not_allow_apply');

											}

											else{

												echo $this->lang->line('allow_apply');

											}

										}

										else if($detail_list_compe->status_com == 2){

											$date_time_agree = $detail_list_compe->date_time_agree;

											if($date_time_agree > date('Y-m-d H:i:s')){

												echo $this->lang->line('reviewing');

											}

											else{

												echo $this->lang->line('choosing_image');

											}

										}

										else if($detail_list_compe->status_com == 3){

											echo $this->lang->line('end');

										}

										else if($detail_list_compe->status_com == 5){

											echo $this->lang->line('lock');

										}

									?>

                                </span>

                                <a href="<?php echo base_url(); ?>compe-detail/<?php echo $detail_list_compe->id_competition; ?>.html"><?php echo $detail_list_compe->title_com; ?></a></p>

                            </div>

                            <div class="mypage-compe-list__meta">

                              <p><?php echo $this->lang->line('prize_money_of_competition'); ?>：<?php echo $detail_list_compe->point_img_com; ?><?php echo $this->lang->line('point'); ?>×<?php echo $detail_list_compe->img_quantity_com; ?></p>

                              <p class="limit"><?php echo $this->lang->line('competition_period'); ?>：<?php echo date('Y/m/d', strtotime($detail_list_compe->date_start_com)); ?>〜<?php echo date('Y/m/d', strtotime($detail_list_compe->date_end_com)); ?></p>

                            </div>

                          </div>

                          <div class="mypage-compe-list__btn">

                            <div class="mypage-compe-list__btn-inner">

                            	<?php
								
									$count_image_apply = $CI->manager_compe_model->get_list_image_apply_autorun_finish($detail_list_compe->id_competition)->num_rows();

									if($detail_list_compe->status_com == 0){

										echo '<a href="'.base_url().'mypage/edit-compe/'.$detail_list_compe->id_competition.'.html" class="mypage-compe-list__btn--white">'.$this->lang->line('edit_content_competition').'</a>';

									}

									else if($detail_list_compe->status_com == 1){

										echo '<a href="'.base_url().'mypage/edit-compe/'.$detail_list_compe->id_competition.'.html" class="mypage-compe-list__btn--white">'.$this->lang->line('edit_content_competition').'</a>';

									}

									else if($detail_list_compe->status_com == 2){

										echo '<a href="'.base_url().'compe-check/'.$detail_list_compe->id_competition.'.html">'.$this->lang->line('the_content_is_reviewing').'</a>';

									}

									else if($detail_list_compe->status_com == 3){
										
										if($count_image_apply > 0){
											echo '<a onclick="download_picture('.$detail_list_compe->id_competition.');" class="mypage-compe-list__btn--green">'.$this->lang->line('download_picture').'</a>';
										}
										else{
											echo '<a class="mypage-compe-list__btn--green">'.$this->lang->line('finish_no_apply').'</a>';
										}

									}

									else if($detail_list_compe->status_com == 5){

										echo '<a class="mypage-compe-list__btn--orange">'.$this->lang->line('lock').'</a>';

									}

								?>

                           	</div>

                          </div>

                        </li>

                        

                <?php

					}
					
				}
				else{
					
				?>
                
                	<p class="red"><?php echo $this->lang->line('you_do_not_have_compe'); ?></p>
                            <div class="btn-post-competition list-img__next-btn"><a href="<?php echo base_url(); ?>mypage/post-compe.html"><?php echo $this->lang->line('post_compe'); ?></a></div>
                
                <?php
					
				}

				?>

                
				<?php echo $phantrang;?>
                

              </ul>

            </div>

          </div>

    </div>

  </div>