<?= $this->renderSection('bootmenu') ?>

<!doctype html>
<html lang="en" class="h-100">

<?= $this->include('partials/head') ?>

<body class="d-flex flex-column h-100">

<?= $this->include('partials/navbar') ?>

<?= $this->renderSection('content') ?>

<?= $this->include('partials/footer') ?>

<!-- Bootstrap JS -->
<script src="<?= base_url('/assets/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>

</body>
</html>