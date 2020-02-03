<!-- BEGIN: STYLES-->

<!-- Favicon -->
<link rel="shortcut icon" href="publics/admin/img/favicon.ico">

<!-- Font CSS (Via CDN) -->
<link rel='stylesheet' type='text/css' href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700'>
<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700">

<!-- Theme CSS -->
<link rel="stylesheet" type="text/css" href="publics/admin/skin/default_skin/css/theme.css">

<!-- Select -->
<link rel="stylesheet" type="text/css" href="publics/admin/vendor/plugins/select/core.css">

<!-- Admin Panels CSS -->
<link rel="stylesheet" type="text/css" href="publics/admin/admin-tools/admin-plugins/admin-panels/adminpanels.css">

<!-- Admin Forms CSS -->
<link rel="stylesheet" type="text/css" href="publics/admin/admin-tools/admin-forms/css/admin-forms.css">

<!-- Admin Modals CSS -->
<link rel="stylesheet" type="text/css" href="publics/admin/admin-tools/admin-plugins/admin-modal/adminmodal.css">

<!-- Magnific popup -->
<link rel="stylesheet" type="text/css" href="publics/admin/vendor/plugins/magnific/magnific-popup.css">

<!-- Font awesome -->
<link rel="stylesheet" type="text/css" href="publics/admin/font-awesome-4.7.0/css/font-awesome.min.css">

<!-- Toastr -->
<link rel="stylesheet" type="text/css" href="publics/admin/toastr/toastr.min.css">

<!-- Sweetalert -->
<link rel="stylesheet" type="text/css" href="publics/admin/sweetalert/dist/sweetalert.css">

<link rel="stylesheet" type="text/css" href="publics/calendar/jquery-ui-1.12.1.css">

<!-- Style LESS -->
<link rel="stylesheet/less" type="text/css" href="publics/admin/less/style.less"/>

<script type="text/javascript" src="publics/admin/less/less.js"></script>

<!-- END: STYLES-->

<style type="text/css">
	.form-control {
		height: inherit;
		-webkit-box-sizing: border-box;
	}
	/*select.form-control {
		-webkit-appearance: menulist;
	}*/
	th {
		text-align: center;
	}
	.table {
		border: 1px solid #000;
	}
	.table tr th {
		background: #7467ad;
    	color: #fff;
    	border: 1px solid #000;
	}
	.table tr td{
		border: 1px solid #000;
	}
	.table tr:nth-child(even) {
	    background-color: #ededed;
	}
	.table .btn {
		padding: 3px 8px;
		border-radius: 4px !important;
		color: #ffffff;
	    background-color: #645fbe;
	    margin-right: 5px;
	}
	.btn {
		padding: 3px 8px;
		color: #ffffff;
	    background-color: #645fbe;
	    margin-right: 5px;
	}
	.btn:hover {
	    background-color: #645fbe;
	    opacity: 0.7;
    	cursor: pointer;
	}
	.table .btn .fa-pencil::before{
		content: "編集";
	}
	.table .btn .fa-times::before{
		content: "削除";
	}
	.table .btn .fa-eye::before{
		content: "View";
	}
	.table .btn-info {
		display: none;
	}
	.btn-primary {
	    padding: 0 10px 0 10px;
	    background-color: #7467ad;
	    color: #fff;
	    line-height: 2.5em;
	}
	.u_cont > button {
		margin-bottom: 10px;
	}
	input.form-control {
		padding: 5px 10px 5px 10px;
    	border: 1px solid #7467ad;
	}
	select.form-control {
		min-width: 100px;
		padding: 5px 10px 5px 10px;
	    border: 1px solid #7467ad;
	    background-image: url(publics/luxyart/img/input/select_2.png);
	    background-position: right center;
	    background-size: contain;
	    background-repeat: no-repeat;
	}
	.form-control[name=keyword] {
		margin-bottom: 20px;
		margin-top: 20px;
		height: 27px;
	}
	.edit_conts p:nth-child(3) {
	    width: 300px;
	}
	.edit_conts p:nth-child(3) input[type="text"] {
		width: 100%;
	}
	.edit_conts p:nth-child(3) input[type="email"] {
		width: 100%;
	}
	.mb{
		width: 470px;
	}
	request::before {
		content: "必須";
		color: #fff;
		background: red;
		padding: 2px 5px;
		margin-left: 10px;
	}
	.content_box3a div:nth-child(odd) {
		width: 347px;
	}
	.note {
		color: red;
		font-weight: bold;
	}
	.note_active_bg {
		background: rgba(255, 0, 0, 0.11);
	}
</style>