<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="Christos Karamolegkos">

    <!-- Favicon -->
    <link rel="icon" href="<?= base_url(); ?>/favicon.ico" type="image/x-icon" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">

    <!-- Tabulator CSS -->
    <!-- <link href="https://unpkg.com/tabulator-tables@4.9.3/dist/css/tabulator.min.css" rel="stylesheet"> -->

    <!-- Custom CSS -->
    <link href="<?= base_url(); ?>/assets/css/style.css" rel="stylesheet">

    <?php if (isset($title) && ($title === 'Log In' || $title === 'Register')) :?>
        <!-- Log In / Register Form CSS -->
        <link href="<?= base_url(); ?>/assets/css/login-clean.css" rel="stylesheet">
    <?php endif; ?>

    <title>iBoot<?= (isset($title)) ? ' - ' . $title : ''; ?></title>
</head>
