<?= $this->extend('template') ?>

<?= $this->section('bootmenu') ?>
#!ipxe
####       iBoot initial loader        ####
chain --replace --autofree <?= site_url('initboot'); ?>?uuid=${uuid} || echo boot failed...
exit
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<main role="main" class="py-5 text-center">
    <div class="container">
        <h1 class="text-center"><?= lang('Text.configure_your_dhcp'); ?></h1>

        <p><?= lang('Text.add_to_your_dhcpd'); ?></p>
        <pre><code>if exists user-class and ( option user-class = "iPXE" ) {
                &nbsp;&nbsp;&nbsp;&nbsp;filename "<?= site_url('boot'); ?>";
            }
            else if option client-arch != 00:00 {
                &nbsp;&nbsp;&nbsp;&nbsp;filename "ipxe.efi";
            }
            else {
                &nbsp;&nbsp;&nbsp;&nbsp;filename "undionly.kpxe";
            }</code></pre>
    </div>
</main>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Hide ipxe command at top
        const navbar = document.querySelector('.navbar');
        navbar.style.cssText += 'top:-24px;';
    });
</script>

<?= $this->endSection() ?>