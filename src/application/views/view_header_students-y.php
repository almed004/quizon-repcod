<!DOCTYPE html>
<html>

<head>
	<title>Quizon</title>
	<link rel="shortcut icon" type="image/png" href="<?= base_url(); ?>img/favicon.png">

	<meta charset="utf-8">

	<meta name="viewport" content="width=device-width, initial-scale=1">

	<script nonce="**CSP_NONCE**" src="<?= base_url(); ?>js/jquery-3.4.1.js"></script>
	<script nonce="**CSP_NONCE**" src="<?= base_url(); ?>js/bootstrap/bootstrap.min.js"></script>

	<script nonce="**CSP_NONCE**" src="<?= base_url(); ?>fontawesomeweb/js/all.js"></script>
	<link rel="stylesheet" href="<?= base_url(); ?>fontawesomeweb/css/fontawesome.min.css">
	<link rel="stylesheet" href="<?= base_url(); ?>css/bootstrap/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="<?= base_url(); ?>css/prism.css">
	<link rel="stylesheet" href="<?= base_url(); ?>css/main.css">

	<script nonce="**CSP_NONCE**">
		$('document').ready(function() {
			console.log($(window).width());
			$('.nav-item').on('click', function() {
				$('.nav-item').removeClass('active');
				$(this).addClass('active');
			});
		});
	</script>

</head>

<body>
	