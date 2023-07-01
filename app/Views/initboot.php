<?php

use iBoot\Models\ComputerModel;

if (! (isset($_GET['uuid'], $_GET['mac']))) : ?>
There was an error. This page should be loaded using a GET request to provide the MAC and UUID of the machine. Make sure your DHCP server / iPXE installation can provide the uuid property.
<?php else :
    $uuid     = $_GET['uuid'];
    $mac      = $_GET['mac'];
    $computer = new ComputerModel();
    $computer->builder()->select('id');
    $id = $computer->where($computer->db->DBPrefix . 'computers.uuid', $uuid)->first()['id'];
    if (! $id) {
        try {
            $computer->insert(['id' => null, 'name' => null, 'mac' => $mac, 'uuid' => $uuid, 'notes' => null, 'lab' => null]); ?>
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
item --gap Use the UUID and MAC shown to verify and configure this computer in iBoot
item
item --gap Default:
item --key x exit ${space} Boot from hard disk [x]
item
item --gap Main menu:

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
<?php
        } catch (ReflectionException $e) {
            echo $e->getMessage();
        }
    } else {
        $computer->builder()->select(
            $computer->db->DBPrefix . 'computers.*, GROUP_CONCAT(DISTINCT(' . $computer->db->DBPrefix . 'computer_groups.group_id)) as groups',
            false
        );
        $computer->builder()->join(
            'computer_groups',
            'computers.id = computer_groups.computer_id',
            'LEFT'
        );
        $computer->builder()->groupBy('computers.id');
        $computer = $computer->where([$computer->db->DBPrefix . 'computers.id' => $id])->first();
        if ($computer) {
            echo 'This would be the computer specific (based on group and time) boot menu.';
        }
    }
    ?>

<?php endif; ?>
