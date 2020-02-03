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
.mypage-list__info{
	width: 446px !important;
}
.mypage-list__date{
	width: 141px !important;
}
.mypage-list__header .w530 {
    width: 530px;
}
.mypage-list__header .w135 {
    width: 135px;
}
</style>

<div class="contents-mypage">

    <div class="contents-mypage__wrapper clearfix">

      <div class="breadly">

        <ul>

          <li><a href="<?php echo base_url()?>"><span itemprop="title"><?php echo $this->lang->line('home'); ?></span></a></li>

          <li><a href="<?php echo base_url()?>mypage.html"><span itemprop="title"><?php echo $this->lang->line('mypage'); ?></span></a></li>

          <li><span itemprop="title"><?php echo $this->lang->line('list_competition_join'); ?></span></li>

        </ul>

      </div>

      <?php $this->load->view('user/sidebar_user.php') ?>

      <div class="mypage-main">

            <h1 class="contents-mypage__find"><?php echo $this->lang->line('list_picture_join_competition'); ?></h1>

            <div class="mypage-list">

              <!--<div class="mypage-list__num">

                <p>表示件数：<?php //if($num_page==10){echo "<span class='curent'>10</span>";}else{ echo '<a onclick="view_page(10);">10</a>';}?>｜<?php //if($num_page==20){echo "<span class='curent'>20</span>";}else{ echo '<a onclick="view_page(20);">20</a>';}?>｜<?php //if($num_page==50){echo "<span class='curent'>50</span>";}else{ echo '<a onclick="view_page(50);">50</a>';}?></p>

              </div>-->

              <div class="mypage-list__header">

                <div class="w530"><?php echo $this->lang->line('picture'); ?></div>

                <div class="w135"><?php echo $this->lang->line('competition_period'); ?></div>

                <div class="w135"><?php echo $this->lang->line('status'); ?></div>

              </div>

              <?php

					if(!empty($list_compe_join))

					{?>

              <div class="mypage-list__list">

                <ul>

                <?php

                	foreach($list_compe_join as $detail_compe_join){

						

				?>

                  <li>

                    <div class="mypage-list__thumb">

                      <div class="mypage-list__thumb-inner"><img src="<?php echo $detail_compe_join->server_path_upload;?>/<?php echo $detail_compe_join->file_name_original_img;?>"></div>

                    </div>

                    <div class="mypage-list__info">

                      <p class="mypage-list__id"><a href="<?php echo base_url()?>detail/<?php echo $detail_compe_join->img_code; ?>.html"><?php echo $detail_compe_join->img_title;?></a></p>

                      <p class="mypage-list__user">&nbsp;(<a href="<?php echo base_url()?>compe-detail/<?php echo $detail_compe_join->id_competition;?>.html"><?php echo $detail_compe_join->title_com; ?></a>)</p>

                    </div>

                    <div class="mypage-list__date"><?php echo date('d/m/Y', strtotime($detail_compe_join->date_start_com)); ?>〜<?php echo date('d/m/Y', strtotime($detail_compe_join->date_end_com)); ?></div>

                    <div class="mypage-list__date">

                    	<?php
							
							if($detail_compe_join->result_img_apply == 0){

								echo $this->lang->line('join');

							}

							else if($detail_compe_join->result_img_apply == 1){

								echo $this->lang->line('eliminated');

							}

							else if($detail_compe_join->result_img_apply == 2){

								echo $this->lang->line('selected');

							}

							else if($detail_compe_join->result_img_apply == 3){

								echo $this->lang->line('delete_by_user_apply');

							}

						?>

                    </div>

                  </li>

                  <?php

				  	

					}

			  ?>

                </ul>

              </div>

              <?php

				  	

					}

			  ?>

              <?php echo $phantrang;?>

              

            </div>

          </div>

      

    </div>

  </div>