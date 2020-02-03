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
<style type="text/css">
  .pagination {
  display: inline-block;
  padding-left: 0;
  margin: 20px 0;
  border-radius: 4px;
}
.pagination > li {
  display: inline;
}
.pagination > li > a,
.pagination > li > span {
  position: relative;
  float: left;
  padding: 6px 12px;
  margin-left: -1px;
  line-height: 1.42857143;
  color: #337ab7;
  text-decoration: none;
  background-color: #fff;
  border: 1px solid #ddd;
}
.pagination > li:first-child > a,
.pagination > li:first-child > span {
  margin-left: 0;
  border-top-left-radius: 4px;
  border-bottom-left-radius: 4px;
}
.pagination > li:last-child > a,
.pagination > li:last-child > span {
  border-top-right-radius: 4px;
  border-bottom-right-radius: 4px;
}
.pagination > li > a:hover,
.pagination > li > span:hover,
.pagination > li > a:focus,
.pagination > li > span:focus {
  z-index: 2;
  color: #23527c;
  background-color: #eee;
  border-color: #ddd;
}
.pagination > .active > a,
.pagination > .active > span,
.pagination > .active > a:hover,
.pagination > .active > span:hover,
.pagination > .active > a:focus,
.pagination > .active > span:focus {
  z-index: 3;
  color: #fff;
  cursor: default;
  background-color: #337ab7;
  border-color: #337ab7;
}
.pagination > .disabled > span,
.pagination > .disabled > span:hover,
.pagination > .disabled > span:focus,
.pagination > .disabled > a,
.pagination > .disabled > a:hover,
.pagination > .disabled > a:focus {
  color: #777;
  cursor: not-allowed;
  background-color: #fff;
  border-color: #ddd;
}
.pagination-lg > li > a,
.pagination-lg > li > span {
  padding: 10px 16px;
  font-size: 18px;
  line-height: 1.3333333;
}
.pagination-lg > li:first-child > a,
.pagination-lg > li:first-child > span {
  border-top-left-radius: 6px;
  border-bottom-left-radius: 6px;
}
.pagination-lg > li:last-child > a,
.pagination-lg > li:last-child > span {
  border-top-right-radius: 6px;
  border-bottom-right-radius: 6px;
}
.pagination-sm > li > a,
.pagination-sm > li > span {
  padding: 5px 10px;
  font-size: 12px;
  line-height: 1.5;
}
.pagination-sm > li:first-child > a,
.pagination-sm > li:first-child > span {
  border-top-left-radius: 3px;
  border-bottom-left-radius: 3px;
}
.pagination-sm > li:last-child > a,
.pagination-sm > li:last-child > span {
  border-top-right-radius: 3px;
  border-bottom-right-radius: 3px;
}
@media screen and (max-width:768px){
        #order_vitri_qa{
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
<div class="contents-qa">
  <div class="ttl-surport">
    <div class="ttl-surport__filter">
      <div class="ttl-surport__box">
        <h1><?=$title?></h1>
      </div>
    </div>
  </div>
  <div id="order_vitri_qa" class="contents-qa__wrapper clearfix">
    <div class="breadly">
      <ul>
        <li><a href="<?php echo base_url()?>"><span itemprop="title"><?=$this->lang->line('home')?></span></a></li>
        <li><a href="<?php echo base_url('qa.html')?>"><span itemprop="title">よくある質問</span></a></li>
        <?php
            $get_category = $this->db->get_where('luxyart_tb_qa_category',['lang_id'=>$this->lang_id,'group_id'=>$get->category_id])->row();
          ?>
          <?php if($get_category->group_id!='') { ?>
        <li><a href="<?php echo base_url('qa-category/'.$get_category->group_id.'.html')?>"><span itemprop="title"><?=$get_category->title?></span></a></li>
        <?php } ?>
        <li><span itemprop="title"><?=$title?></span></li>
      </ul>
    </div>
    <div id="a" class="contents-qa__sub">
      <div class="contents-qa__cat">
        <p>よくある質問のカテゴリー</p>
        <ul>
          <?php foreach ($this->db->get_where('luxyart_tb_qa_category',['lang_id'=>$this->lang_id,'status'=>1])->result() as $key_c => $value_c) { ?>
          <li><a href="<?= base_url('qa-category/'.$value_c->group_id)?>.html"><?=$value_c->title?></a></li>
          <?php } ?>
        </ul>
      </div>
    </div>
    <div id="b" class="contents-qa__main">
      <div class="qa-search-form">
        <p class="qa-search-form__find">よくある質問を検索する</p>
        <div class="qa-search-form__form">
          <form action="<?=base_url('qa-search')?>" method="get">
            <div class="qa-search-form__text">
              <input type="text" name="keyword" placeholder="調べたい項目を検索">
            </div>
            <div class="qa-search-form__btn">
              <input type="submit" value="">
            </div>
          </form>
        </div>
      </div>
      <div class="contents-qa__body">