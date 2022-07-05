<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="Christos Karamolegkos">

    <!-- Favicon -->
    <link rel="icon" href="<?= base_url('/favicon.ico'); ?>" type="image/x-icon"/>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <!-- FontAwesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"
          integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g=="
          crossorigin="anonymous" referrerpolicy="no-referrer"/>

	<?php
    if (isset($tabulator) && $tabulator) : ?>
        <!-- Tabulator Assets -->
        <link rel="stylesheet" href="https://unpkg.com/tabulator-tables@5.2.5/dist/css/tabulator.min.css">
        <script type="text/javascript" src="https://unpkg.com/tabulator-tables@5.2.5/dist/js/tabulator.min.js"></script>
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
    if (isset($title) && ($title === lang('Text.log_in') || $title === lang('Text.sign_up') || $title === lang('Text.sign_up_admin'))) : ?>
        <!-- Log In / Register Form CSS -->
        <link rel="stylesheet" href="<?= base_url('/assets/css/login-clean.css'); ?>">
	<?php
    endif; ?>

    <title>iBoot<?= (isset($title)) ? ' - ' . $title : ''; ?></title>
</head>
