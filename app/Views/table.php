<?= $this->extend('template') ?>

<?= $this->section('content') ?>

<?php
if (isset($columns, $apiTarget)): ?>

    <main role="main" class="py-5">
        <div class="container">
            <div class="row">
                <div id="table"></div>

                <script>
                    let table = new Tabulator("#table", {
                        index: "id",
                        layout: "fitColumns",
                        responsiveLayout: "hide",
                        width: "100%",
                        columns: [
                            {title: "id", field: "id", visible: false},
                            <?= $columns ?>
                            {
                                title: "<?= lang('Text.delete') ?>",
                                formatter: "buttonCross",
                                hozAlign: "center",
                                cellClick: function (e, cell) {
                                    cell.getRow().delete();
                                }
                            },
                        ],
                        ajaxURL: "<?= $apiTarget ?>",
                        ajaxResponse: function (url, params, response) {
                            return response.data;
                        },
                    });

                </script>
            </div>
        </div>
    </main>

<?php
endif; ?>

<?= $this->endSection() ?>