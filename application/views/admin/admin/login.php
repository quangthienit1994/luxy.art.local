<?php
$CI =& get_instance();
$CI->config->load();
$this->lang->load('dich', 'ja');
?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <title><?php echo $this->lang->line('title_login_admin'); ?></title>
        <meta charset="utf-8">
        <meta name="keywords" content="<?php echo $this->lang->line('keyword_login_admin'); ?>" />
        <meta name="description" content="<?php echo $this->lang->line('description_login_admin'); ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
        <base href="<?=base_url();?>">
        <link rel="stylesheet" href="publics/luxyart/css/reset.css">
        <link rel="stylesheet" href="publics/luxyart/css/common.css">
        <link rel="stylesheet" href="publics/luxyart/css/pc.css"><!-- PC Layout CSS -->

        
        <!--&#91;if lt IE 9&#93;>
        <script src="//cdn.jsdelivr.net/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/respond.js/1.4.2/respond.min.js"></script>
        <!&#91;endif&#93;-->
        <meta name="mobile-web-app-capable" content="yes">      
        <link rel="canonical" href=""> 
        <link rel="apple-touch-icon-precomposed" href="publics/luxyart/img/common/ico.png"> 

        <!--   JavaScript Area Start  -->




        <!--   JavaScript Area End  --> 
    </head>
<body>
    <div id="wrap">
        <div class="login_area">
            <div class="login_ttl logctxt">
                <p><img src="publics/luxyart/img/common/luxyart_logo.png" alt=""></p>
                <p><?php echo $this->lang->line('management_function'); ?></p>
            </div>
            <div style="text-align: center; color: red; font-size: 16px; margin-bottom: 10px;">
                <?php if(isset($checkLogin) && $checkLogin == false){ ?>

                    <div class="alert alert-danger"><?php echo $this->lang->line('login_admin_error'); ?></div>
                <?php } ?>
            </div>
            <form method="post" action="">
                <div class="login_cont">
                    <p class="login_conta noto800">
                        <?php echo $this->lang->line('admin_user'); ?>：
                    </p>
                    <p class="login_contb noto400">
                        <input name="username" required="" type="text" placeholder="<?php echo $this->lang->line('please_enter_login_id'); ?>">
                    </p>
                    <p class="login_conta noto800">
                        <?php echo $this->lang->line('admin_pass'); ?>：
                    </p>
                    <p class="login_contb noto400">
                        <input name="pwd" required="" type="password" placeholder="<?php echo $this->lang->line('please_enter_your_password'); ?>">
                    </p>
                </div>
                <input class="clsSubmitBt1" value="<?php echo $this->lang->line('login'); ?>" name="loginAdmin" type="hidden">
                <div class="login_button">
                    <p><input type="submit" value="<?php echo $this->lang->line('login'); ?>"></p>
                </div>
            </form>

            <footer class="login_foot noto400">
                <p><?php echo $this->lang->line('copyright'); ?></p>
            </footer>       
        </div>
    </div>
</body>
</html>