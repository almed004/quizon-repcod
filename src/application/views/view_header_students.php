<!DOCTYPE html>
<html lang="en-us" translate="no">

<head>
	<title>Quizon</title>
	<link rel="shortcut icon" type="image/png" href="<?= base_url(); ?>img/favicon.png">

	<meta name="google" charset="utf-8" http-equiv="content-language" content="en">

	<meta name="viewport" content="width=device-width, initial-scale=1">

	<script nonce="**CSP_NONCE**" src="<?= base_url(); ?>js/jquery-3.4.1.js"></script>
	<script nonce="**CSP_NONCE**" src="<?= base_url(); ?>js/bootstrap/bootstrap.min.js"></script>

	<script nonce="**CSP_NONCE**" src="<?= base_url(); ?>fontawesomeweb/js/all.js"></script>
	<link rel="stylesheet" href="<?= base_url(); ?>fontawesomeweb/css/fontawesome.min.css">
	<link rel="stylesheet" href="<?= base_url(); ?>css/bootstrap/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="<?= base_url(); ?>css/prism.css">
	<link rel="stylesheet" href="<?= base_url(); ?>css/main.css">


	<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/10.3.1/styles/default.min.css">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/10.3.1/highlight.min.js"></script> -->

	<script nonce="**CSP_NONCE**">
		$('document').ready(function() {
			// console.log($(window).width());
			$('.nav-item').on('click', function() {
				$('.nav-item').removeClass('active');
				$(this).addClass('active');
			});
		});
	</script>

	<script nonce="**CSP_NONCE**" type="text/javascript" src="<?= base_url(); ?>js/loader.js"></script>
	<script nonce="**CSP_NONCE**" type="text/javascript">

	</script>
</head>

<body>

	<nav class="navbar navbar-expand-lg navbar-light bg-light border border-top-0 border-left-0 border-right-0 border-bottom-1 border-warning">
		<a class="navbar-brand" href="<?= base_url(); ?>index.php/students/modes"><i class="fas fa-check-circle mr-2 sizef"></i><strong>Quizon</strong></a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse" id="navbarNavAltMarkup">
			<div class="navbar-nav ml-auto">
				<?php
				if (isset($_SESSION['username'])) {
				?>
					<a class="nav-item nav-link" href="<?= base_url(); ?>index.php/students/modes">Modes <span class="sr-only">(current)</span></a>
					<a class="nav-item nav-link" href="<?= base_url() . 'index.php/students/changepassword'; ?>">Change Password</a>
					<a class="nav-item nav-link" href="<?= base_url() . 'index.php/students/logout'; ?>">Logout
						[<?= $_SESSION['username']; ?>]
					</a>
				<?php
				} else {
				?>
					<a class="nav-item nav-link active" href="<?= base_url(); ?>index.php/students/modes">Home <span class="sr-only">(current)</span></a>
				<?php
				}
				?>

			</div>
		</div>

	</nav>