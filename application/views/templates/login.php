<!DOCTYPE html>
<html>

<head>
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <title>Admin</title>
    <base href="<?=base_url()?>">

    <?php $this->load->view('admin/layouts/css.php') ?>
</head>

<body class="external-page sb-l-c sb-r-c" ng-app="app">

	<?php echo $body;?>

	<?php $this->load->view('admin/layouts/script.php') ?>
	</body>
</html>