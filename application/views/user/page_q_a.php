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
<div class="contents-qa">
  <div class="ttl-surport">
    <div class="ttl-surport__filter">
      <div class="ttl-surport__box">
        <h1>よくある質問</h1>
      </div>
    </div>
  </div>
  <div class="contents-qa__wrapper clearfix">
    <div class="breadly">
      <ul>
        <li><a href="<?php echo base_url()?>"><span itemprop="title">ホーム</span></a></li>
        <li><span itemprop="title">よくある質問</span></li>
      </ul>
    </div>
    <div class="contents-qa__sub">
      <div class="contents-qa__cat">
        <p>よくある質問のカテゴリー</p>
        <ul>
          <li><a>会員登録について</a></li>
          <li><a>ログインについて</a></li>
          <li><a>退会について</a></li>
          <li><a>ポイントの購入について</a></li>
          <li><a>クリエイターについて</a></li>
          <li><a>その他</a></li>
        </ul>
      </div>
    </div>
    <div class="contents-qa__main">
      <div class="qa-search-form">
        <p class="qa-search-form__find">よくある質問を検索する</p>
        <div class="qa-search-form__form">
          <div class="qa-search-form__text">
            <input type="text" placeholder="調べたい項目を検索">
          </div>
          <div class="qa-search-form__btn">
            <input type="submit" value="">
          </div>
        </div>
      </div>
      <div class="contents-qa__body">
        <?php foreach ($list_category as $key => $value) { ?>
        <h2><?=$value['title']?></h2>
        <div class="qa-list">
          <ul>
            <li><a>ログイン時に、画面表示や接続ができない状態になります。</a></li>
            <li><a>動作対象のInternet Explorer8,9,10を使用していますが、「古いバージョンです」とメッセージが出ます。</a></li>
            <li><a>登録に費用はかかるのですか？</a></li>
            <li><a>仮登録時に誤ったメールアドレスを入力してしまいました。</a></li>
          </ul>
          <p class="qa-list__more"><a>会員登録についての質問をもっと見る>></a></p>
        </div>
        <?php } ?>
      </div>
    </div>
  </div>
</div>