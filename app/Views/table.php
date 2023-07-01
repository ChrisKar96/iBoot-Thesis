<?= $this->extend('template') ?>

<?= $this->section('content') ?>

<?php
if (isset($columns, $apiTarget)): ?>

    <main role="main" class="py-5">
        <div class="container">
            <div class="my-2">
                <button class="btn btn-primary" id="add-row"><?= lang('Text.add_row'); ?></button>
                <button class="btn btn-danger" style="float: right; display: none;" id="reset" disabled><?= lang('Text.reset_table'); ?></button>
            </div>

            <div class="my-2 table-bordered" id="table"></div>

            <div class="my-2">
                <button class="btn btn-success" style="float: right; display: none;" id="save" disabled><?= lang('Text.save_table'); ?></button>
            </div>

            <script>

                function api_call(url, method, data) {
                    return new Promise(function(resolve, reject) {
                        let xhr = new XMLHttpRequest();
                        xhr.open(method, url, true);
                        xhr.setRequestHeader('Authorization',"Bearer <?= session()->get('user')->token ?>");
                        xhr.setRequestHeader('Content-type','application/json; charset=utf-8');
                        xhr.responseType = 'json';
                        xhr.onload = function() {
                            if (xhr.status >= 200 && xhr.status < 300) {
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

                async function deleteRow(id){
                    await api_call("<?= $apiTarget ?>/" + id + "/delete", "POST");
                }

                async function postRow(data){
                    if(typeof data.id === "undefined"){
                        await api_call("<?= $apiTarget ?>", "POST", data);
                    }
                    else{
                        await api_call("<?= $apiTarget ?>/" + data.id, "POST", data);
                    }
                }

                <?php
                if (isset($JS_bef_tb)) {
                    echo $JS_bef_tb;
                }
    ?>

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
                                if(typeof cell.getRow().getIndex() === "undefined") {
                                    cell.getRow().delete();
                                    table.redraw();
                                }
                                else {
                                    let cell_previous_bg = cell.getRow().getElement().style.backgroundColor;
                                    cell.getRow().getElement().style.backgroundColor = "indianred";
                                    if (confirm('<?= lang('Text.confirm_delete'); ?>')) {
                                        cell.getRow().delete();
                                        table.redraw();
                                        deleteRow(cell.getRow().getIndex());
                                    } else {
                                        cell.getRow().getElement().style.backgroundColor = cell_previous_bg;
                                    }
                                }
                            },
                            width:140
                        },
                    ],
                    ajaxURL: "<?= $apiTarget ?>",
                    ajaxConfig: {
                        headers: {
                            "Authorization": "Bearer <?= session()->get('user')->token ?>"
                        },
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

                table.on("cellEdited", function(){
                    if(table.getEditedCells()){
                        document.getElementById("save").disabled = false;
                        document.getElementById("reset").disabled = false;
                        document.getElementById("reset").style.display = "";
                        document.getElementById("save").style.display = "";
                    }
                });

                //Add row on "Add Row" button click
                document.getElementById("add-row").addEventListener("click", function(){
                    table.addRow({}, true);
                    table.redraw();
                });

                //Save changes to the table on "Save" button click
                document.getElementById("save").addEventListener("click", function(){
                    document.getElementById("save").disabled = true;
                    document.getElementById("reset").disabled = true;
                    let rows = new Set(); //Place rows in Set to avoid duplicates
                    table.getEditedCells().forEach(function(cell){
                        rows.add(cell.getRow());
                    })
                    rows = [...rows]; //Convert Set back to array
                    let updates = [];
                    rows.forEach(function(row){
                        updates.push(postRow(row.getData()));
                    })
                    Promise.all(updates).then(function() {
                        table.setData("<?= $apiTarget ?>");
                        document.getElementById("save").style.display = "none";
                        document.getElementById("reset").style.display = "none";
                    });

                });

                //Reset table contents on "Reset the table" button click
                document.getElementById("reset").addEventListener("click", function(){
                    document.getElementById("save").style.display = "none";
                    document.getElementById("reset").style.display = "none";
                    document.getElementById("save").disabled = true;
                    document.getElementById("reset").disabled = true;
                    table.setData("<?= $apiTarget ?>");
                });

				<?php
    if (isset($JS_aft_tb)) {
        echo $JS_aft_tb;
    }
    ?>

            </script>
        </div>
    </main>

<?php
endif; ?>

<?= $this->endSection() ?>