<?= $this->extend('template') ?>

<?= $this->section('content') ?>

    <main role="main" class="py-5">
        <div class="container">
            <div class="row">
                <?php
                $items = [
                    [lang('Text.computers'), 'computer.png', 'computers'],
                    [lang('Text.groups'), 'groups.png', 'groups'],
                    [lang('Text.labs'), 'lab.png', 'labs'],
                    [lang('Text.ipxe_blocks'), 'osimage.png', 'ipxeblocks'],
                    [lang('Text.boot_menu'), 'boot-menu.png', 'boot_menu'],
                    [lang('Text.schedules'), 'schedule.png', 'schedules'],
                ];

                $item_num = count($items);
                for ($i = 0; $i < $item_num; $i++) {
                    if ($i % 2 === 0) {
                        echo '<div class="col-xs-12 col-md-4"><ul class="list-group h-100">';
                    } ?>
                    <li class="card m-4 square h-50">
                        <a href="<?= $items[$i][2]; ?>" class="text-dark text-decoration-none box-shadow">
                            <div class="row h-75 justify-content-center align-items-center">
                                <div class="col">
                                    <img class="card-img-top img-fluid w-50" alt="Thumbnail <?= $items[$i][0]; ?>"
                                         src="<?= base_url('/assets/img/' . $items[$i][1]); ?>">
                                </div>
                            </div>
                            <div class="row h-25 justify-content-center align-items-center">
                                <div class="col">
                                    <div class="card-body d-flex flex-column">
                                        <h3 style="float: none; text-align: center;">
                                            <?= $items[$i][0]; ?>
                                        </h3>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </li>
                    <?= ($i % 2 === 1) ? '</ul> </div>' : '';
                } ?>
            </div>
        </div>
    </main>

<?= $this->endSection() ?>