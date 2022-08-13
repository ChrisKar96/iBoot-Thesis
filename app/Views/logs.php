<?= $this->extend('template') ?>

<?= $this->section('content') ?>

    <main role="main" class="py-5">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12 col-md-3 col-lg-2 sidebar">
                    <div class="dropdown text-center">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false" style="width: 100%;">
                            <?php if (! isset($currentFile)) {
                                $currentFile = lang('Text.no_log_files_found');
                            } ?>
							<?= $currentFile; ?>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1" style="overflow: auto; max-height: 75vh; width: 100%; text-align: center">
							<?php if (empty($files)): ?>
                                <a class="dropdown-item active" href="#"><?= lang('Text.no_log_files_found'); ?></a>
							<?php else: ?>
							<?php foreach ($files as $file): ?>
                            <li><a class="dropdown-item <?= ($currentFile === $file) ? 'active' : '' ?>" <?= ($currentFile === $file) ? 'aria-current="true"' : '' ?> href="?f=<?= base64_encode($file); ?>"><?= $file; ?></a></li>
							<?php endforeach; ?>
							<?php endif; ?>
                        </ul>
                    </div>
                </div>
                <div class="col-sm-12 col-md-9 col-lg-10 table-container">
                    <?php if (! isset($logs)) {
                        $logs = null;
                    } ?>
					<?php if ($logs === null): ?>
                        <div>
                            <br><br>
                            <strong><?= lang('Text.log_too_big'); ?></strong>
                            <br><br>
                        </div>
					<?php else: ?>
                        <table id="table-log" class="table-striped table-sm">
                            <thead>
                            <tr>
                                <th><?= lang('Text.level'); ?></th>
                                <th><?= lang('Text.date'); ?></th>
                                <th><?= lang('Text.content'); ?></th>
                            </tr>
                            </thead>
                            <tbody>

							<?php foreach ($logs as $key => $log): ?>
                                <tr>
                                    <td>
                                        <div class="text-<?= $log['class']; ?>">
                                            <span class="<?= $log['icon']; ?>" aria-hidden="true"></span>
                                            &nbsp;<?= $log['level']; ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="date"><?= $log['date']; ?></div>
                                    </td>
                                    <td>
                                        <div class="text">
                                            <?= $log['content']; ?>
                                            <?php if (array_key_exists('extra', $log)): ?>
                                                <?= $log['extra'] ?>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
							<?php endforeach; ?>
                            </tbody>
                        </table>
					<?php endif; ?>
                    <div>
						<?php if ($currentFile): ?>
                            <a class="btn btn-primary" href="?dl=<?= base64_encode($currentFile); ?>">
                                <i class="fa-solid fa-download"></i> <?= lang('Text.download_file'); ?>
                            </a>
                            <a class="btn btn-warning" id="delete-log" href="?del=<?= base64_encode($currentFile); ?>">
                                <i class="fa-solid fa-trash-can"></i> <?= lang('Text.delete_file'); ?>
                            </a>
							<?php if (isset($files) && count($files) > 1): ?>
                                <a class="btn btn-warning" id="delete-all-log" href="?del=<?= base64_encode('all'); ?>">
                                    <i class="fa-solid fa-trash-can"></i> <?= lang('Text.delete_all_files'); ?>
                                </a>
							<?php endif; ?>
						<?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                document.querySelector('#delete-log').addEventListener('click', event => {
                    return confirm('Are you sure?');
                });
                document.querySelector('#delete-all-log').addEventListener('click', event => {
                    return confirm('Are you sure?');
                });
            });

            let table = new Tabulator("#table-log", {
                layout: "fitColumns",
                responsiveLayout: "collapse",
                columns:[
                    {title:"<?= lang('Text.level'); ?>", field:"level", formatter:"html", headerFilter:"input", headerFilterLiveFilter:true, width:"10%"},
                    {title:"<?= lang('Text.date'); ?>", field:"date", formatter:"html", headerFilter:"input", headerFilterLiveFilter:true, width:"15%"},
                    {title:"<?= lang('Text.content'); ?>", field:"content", formatter:"html", headerFilter:"input", headerFilterLiveFilter:true, width:"74.9%"},
                ],
                columnHeaderVertAlign:"bottom",
                pagination:"local",
                paginationSize:10,
                paginationSizeSelector:[10, 25, 50, 100],
				<?php if (session()->get('locale') === 'el') : ?>
                locale:true,
                langs:{
                    "el":{
                        "groups":{
                            "item":"αντικείμενο",
                            "items":"αντικείμενα",
                        },
                        "data":{
                            "loading":"Φόρτωση",
                            "error":"Σφάλμα",
                        },
                        "pagination":{
                            "page_size":"Μέγεθος Σελίδας",
                            "page_title":"Εμφάνιση Σελίδας",
                            "first":"Πρώτη",
                            "first_title":"Πρώτη Σελίδα",
                            "last":"Τελευταία",
                            "last_title":"Τελευταία Σελίδα",
                            "prev":"Προηγούμενη",
                            "prev_title":"Προηγούμενη Σελίδα",
                            "next":"Επόμενη",
                            "next_title":"Επόμενη Σελίδα",
                            "all":"Όλα",
                        },
                        "headerFilters":{
                            "default":"φίλτρο στήλης...",
                        }
                    },
                },
				<?php endif; ?>
            });
        </script>
    </main>

<?= $this->endSection() ?>