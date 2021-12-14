<?= $this->extend('template') ?>

<?= $this->section('content') ?>

<?php
if (isset($columns, $apiTarget, $moreJS)): ?>

    <main role="main" class="py-5">
        <div class="container">
            <div id="table"></div>

            <script>
                <?= $moreJS ?>

                let table = new Tabulator("#table", {
                    index: "id",
                    layout: "fitColumns",
                    responsiveLayout: "hide",
                    columns: [
                        {title: "id", field: "id", visible: false},
                        <?= $columns ?>
                        {
                            title: "<?= lang('Text.delete') ?>",
                            formatter: "buttonCross",
                            hozAlign: "center",
                            cellClick: function (e, cell) {
                                cell.getRow().delete();
                            },
                            width:140
                        },
                    ],
                    ajaxURL: "<?= $apiTarget ?>",
                    ajaxResponse: function (url, params, response) {
                        return response.data;
                    },
                });

            </script>
        </div>
    </main>

<?php
endif; ?>

<?= $this->endSection() ?>