<?php

?>
<?= $this->extend('template') ?>

<?= $this->section('bootmenu') ?>
#!ipxe
####       iBoot initial loader        ####
chain --replace --autofree <?= site_url('initboot'); ?>?mac=${netX/mac}&uuid=${uuid} || echo boot failed...
exit
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<main role="main" class="py-5 text-center">
    <div class="container">
        <h1 class="text-center"><?= lang('Text.configure_your_dhcp'); ?></h1>

        <?php if(ENVIRONMENT !== 'production') :?>
            <h5 style="color: red"><?= lang('Text.dev_env_warning'); ?></h5>
        <?php endif; ?>

        <h3 class="text-center"><?= lang('Text.dhcp_server_configuration'); ?></h3>
        <ul class="nav nav-tabs nav-fill">
            <li class="nav-item">
                <a href="#dhcpd" class="nav-link active" data-bs-toggle="tab">ICS DHCPD</a>
            </li>
            <li class="nav-item">
                <a href="#dnsmasq" class="nav-link" data-bs-toggle="tab">Dnsmasq</a>
            </li>
            <li class="nav-item">
                <a href="#other" class="nav-link" data-bs-toggle="tab">Other</a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade show active" id="dhcpd">
                <p><?= lang('Text.add_to_your_dhcpd'); ?></p>
                <pre style="max-width: 100%;"><code>if exists user-class and ( option user-class = "iPXE" ) {
                            &nbsp;&nbsp;&nbsp;&nbsp;filename "<?= site_url('boot'); ?>";
                        }
                        else if option client-arch != 00:00 {
                            &nbsp;&nbsp;&nbsp;&nbsp;filename "ipxe.efi";
                        }
                        else {
                            &nbsp;&nbsp;&nbsp;&nbsp;filename "undionly.kpxe";
                        }</code></pre>
            </div>
            <div class="tab-pane fade" id="dnsmasq">
                <p><?= lang('Text.add_to_your_dnsmasq'); ?></p>
                <pre style="max-width: 100%;"><code># Tag dhcp request from iPXE
                dhcp-match=set:ipxe,175

                # inspect the vendor class string and tag BIOS client
                dhcp-vendorclass=BIOS,PXEClient:Arch:00000

                # 1st boot file - Legacy BIOS client
                dhcp-boot=tag:!ipxe,tag:BIOS,undionly.kpxe,<?= site_url('boot'); ?>

                # 1st boot file - EFI client
                # at the moment all non-BIOS clients are considered
                # EFI client
                dhcp-boot=tag:!ipxe,tag:!BIOS,ipxe.efi,<?= site_url('boot'); ?></code></pre>
            </div>
            <div class="tab-pane fade" id="other">
                <p><?= lang('Text.configure_other_dhcp'); ?></p>
            </div>
        </div>

        <?php if(explode(':', base_url(), 2)[0] === 'https') :?>
        <p style="color: red"><?= lang('Text.ipxe_https_warning'); ?></p>
        <?php endif; ?>
    </div>
</main>

<?= $this->endSection() ?>