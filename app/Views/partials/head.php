<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="Christos Karamolegkos">

    <!-- Favicon -->
    <link rel="icon" href="<?= base_url('/favicon.ico'); ?>" type="image/x-icon"/>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="<?= base_url('/assets/bootstrap/css/bootstrap.min.css'); ?>"/>

    <!-- FontAwesome CSS -->
    <link rel="stylesheet" href="<?= base_url('/assets/font-awesome/all.min.css'); ?>"/>

	<?php
    if (isset($tabulator) && $tabulator) : ?>
        <!-- Tabulator Assets -->
        <link rel="stylesheet" href="https://unpkg.com/tabulator-tables@5.3.1/dist/css/tabulator_bootstrap5.min.css">
        <script type="text/javascript" src="https://unpkg.com/tabulator-tables@5.3.1/dist/js/tabulator.min.js"></script>
	<?php
    endif; ?>

	<?php
    if (isset($calendar) && $calendar) : ?>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.3/font/bootstrap-icons.css">
        <!-- FullCalendar Assets -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5/main.min.css">
        <script src="https://cdn.jsdelivr.net/combine/npm/fullcalendar@5,npm/fullcalendar@5/locales-all.min.js"></script>
	<?php
    endif; ?>

    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= base_url('/assets/css/style.css'); ?>">

	<?php
    $form_pages = [
        lang('Text.log_in'),
        lang('Text.sign_up'),
        lang('Text.sign_up_admin'),
        lang('Text.forgot_credentials'),
        lang('Text.forgot_password'),
    ];
    if (isset($title) && in_array($title, $form_pages, true)) : ?>
        <!-- Log In / Register Form CSS -->
        <link rel="stylesheet" href="<?= base_url('/assets/css/login-clean.css'); ?>">
	<?php
    endif; ?>

    <title>iBoot<?= (isset($title)) ? ' - ' . $title : ''; ?></title>
</head>
