<?= $this->extend('template') ?>

<?= $this->section('content') ?>

<?php
if (isset($columns, $apiTarget, $moreJS)): ?>

    <main role="main" class="py-5">
        <div class="container">
            <div id="table"></div>

            <script>

                function api_call(url, method) {
                    return new Promise(function(resolve, reject) {
                        let xhr = new XMLHttpRequest();
                        xhr.open(method, url, true);
                        xhr.responseType = 'json';
                        xhr.onload = function() {
                            let status = xhr.status;
                            if (status == 200) {
                                resolve(xhr.response);
                            } else {
                                reject(status);
                            }
                        };
                        xhr.send();
                    });
                }
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
                                "first":"Πρώτο",
                                "first_title":"Πρώτη Σελίδα",
                                "last":"Τελευταίο",
                                "last_title":"Τελευταία Σελίδας",
                                "prev":"Προηγούμενο",
                                "prev_title":"Προηγούμενη Σελίδα",
                                "next":"Επόμενο",
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
        </div>
    </main>

<?php
endif; ?>

<?= $this->endSection() ?>