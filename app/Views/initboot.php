<?= $this->extend('template') ?>

<?= $this->section('bootmenu') ?>
<?php

use CodeIgniter\I18n\Time;
use Config\Services;
use iBoot\Models\ComputerModel;

if (! (isset($_GET['uuid'], $_GET['mac']))) : ?>
There was an error. This page should be loaded using a GET request to provide the UUID and the MAC address of the computer. Make sure your DHCP server / iPXE installation can provide the UUID property.
<?php else :
    $uuid             = $_GET['uuid'];
    $mac              = $_GET['mac'];
    $computerModel    = new ComputerModel();
    $computer         = $computerModel->where('uuid', $uuid)->where('mac', $mac)->first();
    $request          = Services::request();
    $IP               = $request->getIPAddress();
    $current_datetime = Time::now();
    if (! $computer) {
        if(! $computerModel->insert(['name' => null, 'mac' => $mac, 'uuid' => $uuid, 'notes' => "Added from {$IP} at {$current_datetime->toDateTimeString()}.", 'lab' => null])) {
            $error_message = "There was a problem registering the computer.\nitem Maybe its' UUID or MAC address are already used.\nitem Try to resolve the issue before retrying.\n";
            log_message('warning', "Error registering computer from initboot\n{errors}", ['errors' => var_export(($computerModel->errors()), true)]);
        }

    } else {
        $groups = $computer->getGroupObjs();

        if (! empty($groups)) {
            $schedule = null;

            foreach ($groups as $g) {
                $schedule = $g->getScheduleObj($current_datetime);
                if(! empty($schedule)) {
                    break;
                }
            }

            if (! empty($schedule)) {
                $bootmenu        = $schedule->getBootMenuObj();
                $bootmenu_blocks = $bootmenu->getBootMenuBlockObjs();
            }
        }
    }
    ?>
#!ipxe

:start

# The main menu title
set main_menu_title iBoot iPXE Menu

set space:hex 20:20
set space ${space:string}

iseq ${cls} serial && goto ignore_cls ||
set cls:hex 1b:5b:4a  # ANSI clear screen sequence - "^[[J"
set cls ${cls:string}
:ignore_cls

isset ${arch} && goto skip_arch_detect ||
cpuid --ext 29 && set arch x86_64 || set arch i386
iseq ${arch} i386   && set arch5 i586   || set arch5 ${arch}
iseq ${arch} x86_64 && set arch_a amd64 || set arch_a ${arch}
:skip_arch_detect

isset ${menu} && goto ${menu} ||

isset ${ip} || dhcp || echo DHCP failed

isset ${post_boot} || chain --replace --autofree boot.ipxe ||

:main_menu
isset ${main_menu_cursor} || set main_menu_cursor exit
clear version
menu ${main_menu_title} UUID: ${uuid} MAC: ${netX/mac}
<?php if (isset($error_message)):?>
item --gap <?= $error_message; ?>
<?php else:?>
item --gap Use the UUID and MAC shown to verify and configure this computer in iBoot
<?php endif; ?>
item
item --gap Default:
item --key x exit ${space} Boot from hard disk [x]
item
item --gap Main menu:
<?php if (! empty($bootmenu)) {
    echo $bootmenu->ipxeblock;
}?>
<?php if (! empty($bootmenu_blocks)) {
    foreach ($bootmenu_blocks as $b) {
        printf('item ');
        if(! empty($b->key)) {
            printf('--key %s ', $b->key);
        }
        printf('%s ${space} %s' . "\n", str_replace(' ', '_', $b->getBlock()->name), str_replace(' ', '_', $b->getBlock()->name));
    }
    printf("\n");
}?>
item
item --gap Tools:
item sysinfo ${space} System info
item reboot ${space} Reboot
item shell ${space} iPXE shell

isset ${menu} && set timeout 0 || set timeout 4000
choose --timeout ${timeout} --default ${main_menu_cursor} menu || goto cancel
set main_menu_cursor ${menu}
set timeout 0
goto ${menu} ||
chain ${menu}.ipxe || goto error
goto main_menu

:cancel
echo You cancelled the menu, dropping you to a shell

:shell
echo Type "exit" to return to menu.
set menu main_menu
shell
goto main_menu

:reboot
reboot

:exit
echo ${cls}
exit

:error
echo Error occured, press any key to return to menu ...
prompt
goto main_menu

:reload
echo Reloading menu.ipxe ...
chain menu.ipxe

:sysinfo
menu System info
item --gap UUID:
item mac ${space} ${uuid}
item --gap MAC:
item mac ${space} ${netX/mac}
item --gap IP/mask:
item ip ${space} ${netX/ip}/${netX/netmask}
item --gap Gateway:
item gw ${space} ${netX/gateway}
item --gap Hostname:
item hostname ${space} ${hostname}
item --gap Domain:
item domain ${space} ${netX/domain}
item --gap DNS:
item dns ${space} ${netX/dns}
item --gap DHCP server:
item dhcpserver ${space} ${netX/dhcp-server}
item --gap Next-server:
item nextserver ${space} ${next-server}
item --gap Filename:
item filename ${space} ${netX/filename}
choose empty ||
goto main_menu

<?php if (! empty($bootmenu_blocks)) {
    foreach ($bootmenu_blocks as $b) {
        printf(":%s\n%s\n\n", str_replace(' ', '_', $b->getBlock()->name), $b->getBlock()->ipxe_block);
    }
}?>
<?php endif; ?>

<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <main role="main" class="py-5 text-center">
        <div class="container">
            <h1 class="text-center"><?= lang('Text.onlyIPXE'); ?></h1>
        </div>
    </main>
<?= $this->endSection() ?>