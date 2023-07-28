<?php

use Config\Services;

$request = Services::request();
$agent   = $request->getUserAgent();
if(($request->getPath() === 'boot' || $request->getPath() === 'initboot') && (str_contains($agent, 'iPXE') || (int) $request->getGet('overrideAgent') === 1)):?>
<?= $this->renderSection('bootmenu') ?>
<?php else: ?>
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
<?php endif; ?>
