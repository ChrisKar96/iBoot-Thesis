<!doctype html>
<html lang="en" class="h-100">

<?= $this->include('partials/head') ?>

<body class="d-flex flex-column h-100">

<?= $this->include('partials/navbar') ?>

<?= $this->renderSection('content') ?>

<?= $this->include('partials/footer') ?>

<!-- Bootstrap JS -->
<script src="<?= base_url(); ?>/assets/vendor/bootstrap/bootstrap.bundle.min.js"></script>

<!-- Tabulator JS -->
<!-- <script type="text/javascript" src="https://unpkg.com/tabulator-tables@4.9.3/dist/js/tabulator.min.js"></script> -->

</body>
</html>