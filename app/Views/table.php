<?= $this->extend('template') ?>

<?= $this->section('content') ?>

<?php
if (isset($columns, $apiTarget, $moreJS)): ?>

    <main role="main" class="py-5">
        <div class="container">
            <div id="table"></div>

            <script>

                function api_call(url, method, data) {
                    return new Promise(function(resolve, reject) {
                        let xhr = new XMLHttpRequest();
                        xhr.open(method, url, true);
                        xhr.setRequestHeader('Content-type','application/json; charset=utf-8');
                        xhr.responseType = 'json';
                        xhr.onload = function() {
                            if (xhr.status === 200) {
                                resolve(xhr.response);
                            } else {
                                reject(xhr.status);
                            }
                        };
                        if(typeof data !== "undefined") {
                            xhr.send(JSON.stringify(data));
                        }
                        else{
                            xhr.send();
                        }
                    });
                }
                <?= $moreJS ?>

                let table = new Tabulator("#table", {
                    index: "id",
                    layout: "fitColumns",
                    responsiveLayout: "hide",
                    columnHeaderVertAlign:"bottom",
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
        </div>
    </main>

<?php
endif; ?>

<?= $this->endSection() ?>