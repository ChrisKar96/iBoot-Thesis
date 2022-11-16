<?= $this->extend('template') ?>

<?= $this->section('content') ?>
    <style>
        .tabulator-cell, .tabulator-tableholder, .tabulator-col-resize-handle {
            height: auto !important;
        }

        a.fa-solid:after {
            /* fa-magnifying-glass-plus */
            content: "\f00e";
        }

        a.fa-solid[aria-expanded="true"]:after {
            /* fa-magnifying-glass-minus */
            content: "\f010";
    </style>

    <main role="main" class="py-5">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12 col-md-3 col-lg-2 sidebar">
                    <div class="dropdown text-center">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1"
                                data-bs-toggle="dropdown" aria-expanded="false" style="width: 100%;">
							<?php if (! isset($currentFile)) {
							    $currentFile = lang('Text.no_log_files_found');
							} ?>
							<?= $currentFile; ?>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1"
                            style="overflow: auto; max-height: 75vh; width: 100%; text-align: center">
							<?php if (empty($files)): ?>
                                <a class="dropdown-item active" href="#"><?= lang('Text.no_log_files_found'); ?></a>
							<?php else: ?>
								<?php foreach ($files as $file): ?>
                                    <li>
                                        <a class="dropdown-item <?= ($currentFile === $file) ? 'active' : '' ?>" <?= ($currentFile === $file) ? 'aria-current="true"' : '' ?>
                                           href="?f=<?= base64_encode($file); ?>"><?= $file; ?></a></li>
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
                                    <td><div class="text-<?= $log['class']; ?>"><span class="<?= $log['icon']; ?>" aria-hidden="true"></span>&nbsp;<?= $log['level']; ?></div></td>
                                    <td><?= $log['date']; ?></td>
                                    <td><?= (array_key_exists('extra', $log)) ? '<a class="btn btn-default btn-xs fa-solid" data-bs-toggle="collapse" href="#collapse' . $key . '" role="button" aria-expanded="false"aria-controls="collapse' . $key . '"></a>' : ''; ?>
										<?= $log['content']; ?>
										<?= (array_key_exists('extra', $log)) ? '<div class="collapse" id="collapse' . $key . '"><div class="card card-body">' . $log['extra'] . '</div></div>' : ''; ?>
                                    </td>
                                </tr>
							<?php endforeach; ?>
                            </tbody>
                        </table>
					<?php endif; ?>
                    <div class="row text-center my-2">
						<?php if ($currentFile): ?>
                            <div class="col">
                                <a class="btn btn-primary" href="?dl=<?= base64_encode($currentFile); ?>">
                                    <i class="fa-solid fa-download"></i> <?= lang('Text.download_file'); ?>
                                </a>
                            </div>
                            <div class="col">
                                <a class="btn btn-warning" id="delete-log"
                                   href="?del=<?= base64_encode($currentFile); ?>">
                                    <i class="fa-solid fa-trash-can"></i> <?= lang('Text.delete_file'); ?>
                                </a>
                            </div>
							<?php if (isset($files) && count($files) > 1): ?>
                                <div class="col">
                                    <a class="btn btn-warning" id="delete-all-log"
                                       href="?del=<?= base64_encode('all'); ?>">
                                        <i class="fa-solid fa-trash-can"></i> <?= lang('Text.delete_all_files'); ?>
                                    </a>
                                </div>
							<?php endif; ?>
						<?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <script>
            document.addEventListener("DOMContentLoaded", function () {
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
                columns: [
                    {
                        title: "<?= lang('Text.level'); ?>",
                        field: "level",
                        formatter: "html",
                        headerFilter: "list",
                        headerFilterParams: {
                            multiselect: true,
                            values: {
                                '<div class="text-warning"><span class="fa-solid fa-triangle-exclamation" aria-hidden="true"></span>&nbsp;DEBUG</div>': 'DEBUG',
                                '<div class="text-info"><span class="fa-solid fa-circle-info" aria-hidden="true"></span>&nbsp;INFO</div>': 'INFO',
                                '<div class="text-warning"><span class="fa-solid fa-flag" aria-hidden="true"></span>&nbsp;NOTICE</div>': 'NOTICE',
                                '<div class="text-warning"><span class="fa-solid fa-circle-exclamation" aria-hidden="true"></span>&nbsp;WARNING</div>': 'WARNING',
                                '<div class="text-danger"><span class="fa-solid fa-xmark" aria-hidden="true"></span>&nbsp;ERROR</div>': 'ERROR',
                                '<div class="text-danger"><span class="fa-solid fa-bug" aria-hidden="true"></span>&nbsp;CRITICAL</div>': 'CRITICAL',
                                '<div class="text-danger"><span class="fa-solid fa-triangle-exclamation" aria-hidden="true"></span>&nbsp;ALERT</div>': 'ALERT',
                                '<div class="text-danger"><span class="fa-solid fa-dumpster-fire" aria-hidden="true"></span>&nbsp;EMERGENCY</div>': 'EMERGENCY',
                            },
                        },
                        headerFilterFunc: "in",
                        headerFilterLiveFilter: true,
                        widthGrow:1,
                    },
                    {
                        title: "<?= lang('Text.date'); ?>",
                        field: "date",
                        formatter: "html",
                        headerFilter: "input",
                        headerFilterLiveFilter: true,
                        widthGrow:1,
                    },
                    {
                        title: "<?= lang('Text.content'); ?>",
                        field: "content",
                        formatter: "html",
                        headerFilter: "input",
                        headerFilterLiveFilter: true,
                        widthGrow:4,
                    },
                ],
                columnHeaderVertAlign: "bottom",
                initialSort:[
                    {column:"date", dir:"desc"}, //sort initially by most recent date
                ],
                pagination: "local",
                paginationSize: 10,
                paginationSizeSelector: [10, 25, 50, 100],
                locale: true,
				<?php if (session()->get('locale') === 'el') : ?>
                langs: {
                    "el": {
                        "groups": {
                            "item": "αντικείμενο",
                            "items": "αντικείμενα",
                        },
                        "data": {
                            "loading": "Φόρτωση",
                            "error": "Σφάλμα",
                        },
                        "pagination": {
                            "page_size": "Μέγεθος Σελίδας",
                            "page_title": "Εμφάνιση Σελίδας",
                            "first": "Πρώτη",
                            "first_title": "Πρώτη Σελίδα",
                            "last": "Τελευταία",
                            "last_title": "Τελευταία Σελίδα",
                            "prev": "Προηγούμενη",
                            "prev_title": "Προηγούμενη Σελίδα",
                            "next": "Επόμενη",
                            "next_title": "Επόμενη Σελίδα",
                            "all": "Όλα",
                        },
                        "headerFilters": {
                            "default": "Φίλτρο Στήλης...",
                            "columns": {
                                "level": "Φίλτρο Επιπέδου...",
                                "date": "Φίλτρο Ημερομηνίας...",
                                "content": "Φίλτρο Περιεχομένου...",
                            }
                        }
                    },
                },
				<?php else: ?>
                langs: {
                    "default": {
                        "headerFilters": {
                            "default": "Filter Column...",
                            "columns": {
                                "level": "Filter Level...",
                                "date": "Filter Date...",
                                "content": "Filter Content...",
                            }
                        }
                    },
                },
				<?php endif; ?>
            });
        </script>
    </main>

<?= $this->endSection() ?>