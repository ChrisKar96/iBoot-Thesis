<?= $this->extend('template') ?>

<?= $this->section('bootmenu') ?>
<?php

use CodeIgniter\I18n\Time;
use Config\Services;
use iBoot\Entities\BootMenuBlocks;
use iBoot\Models\BootMenuModel;
use iBoot\Models\ComputerModel;
use iBoot\Models\IpxeBlockModel;

if (! (isset($_GET['uuid'], $_GET['mac'])) && ! isset($_GET['menu']) && ! isset($_GET['block'])) : ?>
#!ipxe
echo There was an error. This page should be loaded using a GET request to provide the UUID and the MAC address of the computer. Make sure your DHCP server / iPXE installation can provide the UUID property.
exit
<?php else :
    $uuid = isset($_GET['uuid']) ? (int) $_GET['uuid'] : null;
    $mac  = isset($_GET['mac']) ? (int) $_GET['mac'] : null;
    $computerModel = new ComputerModel();
    if (! empty($uuid) && ! empty($mac)) {
        $computer      = $computerModel->where('uuid', $uuid)->where('mac', $mac)->first();
    } else {
        $computer = null;
    }
    $request          = Services::request();
    $IP               = $request->getIPAddress();
    $current_datetime = Time::now();
    $menu             = isset($_GET['menu']) ? (int) $_GET['menu'] : null;
    $block            = isset($_GET['block']) ? (int) $_GET['block'] : null;

    if (! empty($menu)) {
        $bootmenuModel   = new BootMenuModel();
        $bootmenu        = $bootmenuModel->find($menu);
        $bootmenu_blocks = $bootmenu->getBootMenuBlockObjs();
    } elseif (! empty($block)) {
        $bootMenuBlock   = new BootMenuBlocks(['id' => 0, 'boot_menu_id' => 0, 'block_id' => $block, 'key' => '']);
        $bootmenu_blocks = [$bootMenuBlock];
    } else {
        if (! $computer && ! empty($uuid) && ! empty($mac)) {
            if (! $computerModel->insert(['name' => null, 'mac' => $mac, 'uuid' => $uuid, 'notes' => "Added from {$IP} at {$current_datetime->toDateTimeString()}.", 'lab' => null])) {
                $error_message = "There was a problem registering the computer.\nitem Maybe its' UUID or MAC address are already used.\nitem Try to resolve the issue before retrying.\n";
                log_message('warning', "Error registering computer at initboot\nUUID: {uuid}\nMAC: {mac}\nIP: {ip}\nErrors: {errors}", ['uuid' => $uuid, 'mac' => $mac, 'ip' => $IP, 'errors' => json_encode($computerModel->errors(), JSON_PRETTY_PRINT)]);
            }
            $configure = true;
        } else {
            $groups = $computer->getGroupObjs();

            if (! empty($groups)) {
                $schedule = null;

                foreach ($groups as $g) {
                    $schedule = $g->getScheduleObj($current_datetime);
                    if (! empty($schedule)) {
                        $group = $g;
                        break;
                    }
                }

                if (! empty($schedule)) {
                    $bootmenu        = $schedule->getBootMenuObj();
                    $bootmenu_blocks = $bootmenu->getBootMenuBlockObjs();
                } else {
                    $ipxeBlockModel = new IpxeBlockModel();
                    $default_block          = $ipxeBlockModel->where('name', 'default')->first();
                    if (! empty($default_block)) {
                        $bootMenuBlock   = new BootMenuBlocks(['id' => 0, 'boot_menu_id' => 0, 'block_id' => $default_block->id, 'key' => '']);
                        $bootmenu_blocks = [$bootMenuBlock];
                    }
                }
            } else {
                $configure = true;
            }
        }
    }
    ?>
#!ipxe

:start

# The main menu title
set main_menu_title Karamolegkos-Dasygenis iBoot iPXE Menu

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
<?php elseif (isset($configure) && $configure):?>
item --gap Use the UUID and MAC shown to verify and configure this computer in iBoot
<?php endif; ?>
<?php if (! empty($bootmenu) || ! empty($bootmenu_blocks)):?>
item
item --gap Main menu:
<?php endif; ?>
<?php if (! empty($bootmenu)) {
    $ipxeblock = $bootmenu->ipxeblock;
    $ipxeblock = ! empty($group) ? str_replace(['${group.ip}', '${group.prefix}'], [$group->image_server_ip, $group->image_server_path_prefix], $bootmenu->ipxe_block) : $bootmenu->ipxe_block;
    echo $ipxeblock;
}?>
<?php if (! empty($bootmenu_blocks)) {
    foreach ($bootmenu_blocks as $b) {
        printf('item ');
        if(! empty($b->key)) {
            printf('--key %s ', $b->key);
        }
        $block_name = str_replace(' ', '_', $b->getBlock()->name);
        printf('%s ${space} %s', $block_name, $block_name);
        if(! empty($b->key)) {
            printf(' [%s]', $b->key);
        }
        printf("\n");
    }
    printf("\n");
}?>
item
item --gap Local Boot:
item --key x exit ${space} Boot from hard disk [x]
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
        $b_block_name = str_replace(' ', '_', $b->getBlock()->name);
        $b_ipxeblock  = ! empty($group) ? str_replace(['${group.ip}', '${group.prefix}'], [$group->image_server_ip, $group->image_server_path_prefix], $b->getBlock()->ipxe_block) : $b->getBlock()->ipxe_block;
        printf(":%s\n%s\n\n", $b_block_name, $b_ipxeblock);
    }
}?>
<?php endif; ?>

<?php if (empty($error_message) && empty($configure)) {
    $message = 'Computer ';
    if (! empty($computer)) {
        $message .= "with uuid {$computer->uuid} ";
    }
    $message .= "from IP: {$IP} received menu ";
    if(! empty($bootmenu)) {
        $message .= "{$bootmenu->name} ";
    }
    if(! empty($menu)) {
        $message .= "{$menu} ";
    }
    if(! empty($block)) {
        $message .= "with block {$block} ";
    }
    $message .= 'from initboot';
    if(! empty($bootmenu_blocks)) {
        $attr = [];

        foreach ($bootmenu_blocks as $bmb) {
            $attr[] = $bmb->toArray();
        }
        $message .= "\nBlocks: " . json_encode($attr, JSON_PRETTY_PRINT);
    }
    log_message('info', $message);
}?>

<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <main role="main" class="py-5 text-center">
        <div class="container">
            <h1 class="text-center"><?= lang('Text.onlyIPXE'); ?></h1>
        </div>
    </main>
<?= $this->endSection() ?>