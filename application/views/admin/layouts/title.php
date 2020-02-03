<header id="topbar">
	<div class="topbar-left">
		<ol class="breadcrumb">
			<li class="crumb-active">
				<a href="<?=base_url(uri_string())?>"> <?=$title?></a>
			</li>
			<li class="crumb-icon">
				<a href="<?=base_url('admin')?>">
					<span class="glyphicon glyphicon-home"></span>
				</a>
			</li>
			<li class="crumb-link">
				<a href="<?=base_url('admin')?>">Home</a>
			</li>
			<li class="crumb-trail"><?=$title?></li>
		</ol>
	</div>
</header>