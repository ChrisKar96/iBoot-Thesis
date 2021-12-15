<?= $this->extend('template') ?>

<?= $this->section('bootmenu') ?>
    #!ipxe
    ####       iBoot initial loader        ####
    chain --autofree <?= site_url('initboot.php'); ?> || echo boot failed...
    exit
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<main role="main" class="py-5 text-center">
    <div class="container">
        <h1 class="text-center"><?= lang('Text.configure_your_dhcp'); ?></h1>

        <p><?= lang('Text.add_to_your_dhcpd'); ?></p>
<pre class="text-center"><code>if exists user-class and ( option user-class = "iPXE" ) {
    filename "<?= site_url('boot.php'); ?>";
}
else {
    filename "undionly.kpxe";
}
</code></pre>
    </div>
</main>

<?= $this->endSection() ?>