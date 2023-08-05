<?= $this->extend('template') ?>

<?= $this->section('content') ?>

<?php
if (isset($title, $columns, $apiTarget)): ?>

    <main role="main" class="py-5">
        <div class="container">
            <div class="mb-4 mt-2">
                <h1 class="text-center"><?= $title; ?></h1>
            </div>

            <?php if (isset($calendar) && $calendar) : ?>
            <div class="mb-4 mt-2" id="calendar"></div>
            <?php endif; ?>

            <?php if(isset($reloadable) && $reloadable) : ?>
            <div class="row justify-content-end">
                <div class="col-md-3">
                    <div class="input-group mb-3 justify-content-end">
                        <span class="input-group-text"><?= lang('Text.auto_reload'); ?></span>
                        <div class="input-group-text">
                            <input class="form-check-input mt-0" type="checkbox" onclick="ToggleReload()" id="reload_enabled" aria-label="auto-reload"/>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            <div class="row justify-content-end">
                <div class="col-md-3">
                    <div class="input-group mb-3 justify-content-end">
                        <button class="btn btn-secondary" onclick="table.clearFilter(true)" id="clear_filters"><?= lang('Text.clear_filters'); ?></button>
                    </div>
                </div>
            </div>

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

                <?php if (isset($calendar) && $calendar) : ?>
                    let eventlist = {};

                    let dow = {};
                    dow[0] = "<?= lang('Text.sunday'); ?>";
                    dow[1] = "<?= lang('Text.monday'); ?>";
                    dow[2] = "<?= lang('Text.tuesday'); ?>";
                    dow[3] = "<?= lang('Text.wednesday'); ?>";
                    dow[4] = "<?= lang('Text.thursday'); ?>";
                    dow[5] = "<?= lang('Text.friday'); ?>";
                    dow[6] = "<?= lang('Text.saturday'); ?>";

                    async function getSchedules(){
                        await api_call("<?= $apiTarget ?>", "GET").then(function(response) {
                            eventlist = {};
                            for (i = 0; i < response.length; ++i) {
                                eventlist[response[i].id] = {};
                                if(response[i].isActive === "1"){
                                    eventlist[response[i].id].title = 'BM: ' + response[i].boot_menu_id + ' for G: ' + response[i].group_id;
                                    if(response[i].day_of_week !== null){
                                        eventlist[response[i].id].startTime = response[i].time_from;
                                        eventlist[response[i].id].endTime = response[i].time_to;
                                        eventlist[response[i].id].daysOfWeek = [response[i].day_of_week];
                                    }
                                    else{
                                        eventlist[response[i].id].start = response[i].date + 'T' + response[i].time_from + '+00:00';
                                        eventlist[response[i].id].end = response[i].date + 'T' + response[i].time_to + '+00:00';
                                    }
                                }
                            }
                            calendar.removeAllEventSources();
                            calendar.addEventSource(Object.values(eventlist));
                        });
                    }

                    getSchedules();

                    let calendar = null;
                    document.addEventListener('DOMContentLoaded', function() {
                        let calendarEl = document.getElementById('calendar');

                        calendar = new FullCalendar.Calendar(calendarEl, {
                            schedulerLicenseKey: 'CC-Attribution-NonCommercial-NoDerivatives',
                            themeSystem: 'bootstrap5',
                            timeZone: '<?= app_timezone(); ?>',
                            initialView: 'timeGridWeek',
                            headerToolbar: {
                                left: 'prev,next',
                                center: 'title',
                                right: 'timeGridDay,timeGridWeek'
                            },
                            dayHeaderFormat: {
                                weekday: 'long'
                            },
                            allDaySlot: false,
                            nowIndicator: true,
                            editable: false,
                            <?php if (session()->get('locale') === 'el') : ?>
                            locale: 'el',
                            <?php else: ?>
                            locale: 'en',
                            <?php endif; ?>
                        });
                        calendar.render();
                    });

                function minMaxFilterEditor(cell, onRendered, success, cancel, editorParams){

                    let end;

                    let container = document.createElement("span");

                    //create and style inputs
                    let start = document.createElement("input");
                    start.setAttribute("type", editorParams.type);
                    start.setAttribute("placeholder", "Min");
                    start.style.padding = "4px";
                    start.style.width = "50%";
                    start.style.boxSizing = "border-box";

                    start.value = cell.getValue();

                    function buildValues(){
                        success({
                            start:start.value,
                            end:end.value,
                        });
                    }

                    function keypress(e){
                        if(e.keyCode == 13){
                            buildValues();
                        }

                        if(e.keyCode == 27){
                            cancel();
                        }
                    }

                    end = start.cloneNode();
                    end.setAttribute("placeholder", "Max");

                    start.addEventListener("change", buildValues);
                    start.addEventListener("blur", buildValues);
                    start.addEventListener("keydown", keypress);

                    end.addEventListener("change", buildValues);
                    end.addEventListener("blur", buildValues);
                    end.addEventListener("keydown", keypress);


                    container.appendChild(start);
                    container.appendChild(end);

                    return container;
                }

                //custom max min filter function
                function minMaxFilterFunction(headerValue, rowValue, rowData, filterParams){
                    //headerValue - the value of the header filter element
                    //rowValue - the value of the column in this row
                    //rowData - the data for the row being filtered
                    //filterParams - params object passed to the headerFilterFuncParams property

                    if(rowValue){
                        if(headerValue.start != ""){
                            if(headerValue.end != ""){
                                return rowValue >= headerValue.start && rowValue <= headerValue.end;
                            }else{
                                return rowValue >= headerValue.start;
                            }
                        }else{
                            if(headerValue.end != ""){
                                return rowValue <= headerValue.end;
                            }
                        }
                    }

                    return true; //must return a boolean, true if it passes the filter.
                }
                <?php endif; ?>

                let table = new Tabulator("#table", {
                    index: "id",
                    layout: "fitDataFill",
                    layoutColumnsOnNewData:true,
                    columnHeaderVertAlign:"bottom",
                    columns: [
                        {title: "id", field: "id", width:65, visible: false, frozen:true},
                        <?= $columns ?>
                        {
                            title: "<?= lang('Text.delete') ?>",
                            formatter: "buttonCross",
                            hozAlign: "center",
                            headerSort:false,
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
                                        <?php if (isset($calendar) && $calendar) : ?>
                                        getSchedules();
                                        <?php endif; ?>
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
                    placeholder:function(){
                        return this.getHeaderFilters().length ? "<?= lang('Text.no_results'); ?>" : "<?= lang('Text.no_data'); ?>";
                    },
                    persistence:{
                        headerFilter: true,
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
                    if(table.getEditedCells().length > 0){
                        table.getEditedCells().forEach(cell => {
                            if((Array.isArray(cell.getOldValue()) && (cell.getOldValue().sort().toString() === cell.getValue().sort().toString() || cell.getInitialValue().sort().toString() === cell.getValue().sort().toString()))
                            || (cell.getOldValue() === cell.getValue() || cell.getInitialValue() === cell.getValue())){cell.clearEdited();}})
                    }
                    if(table.getEditedCells().length > 0){
                        document.getElementById("save").disabled = false;
                        document.getElementById("reset").disabled = false;
                        document.getElementById("reset").style.display = "";
                        document.getElementById("save").style.display = "";
                    }
                    else {
                        document.getElementById("save").style.display = "none";
                        document.getElementById("reset").style.display = "none";
                        document.getElementById("save").disabled = true;
                        document.getElementById("reset").disabled = true;
                    }
                });

                function addnewrow(){
                    table.addRow({}, true);
                    table.redraw();
                }

                //Add row on "Add Row" button click
                document.getElementById("add-row").addEventListener("click", addnewrow);

                function save() {
                    document.getElementById("save").disabled = true;
                    document.getElementById("reset").disabled = true;
                    let rows = new Set(); //Place rows in Set to avoid duplicates
                    table.getEditedCells().forEach(function (cell) {
                        rows.add(cell.getRow());
                    })
                    rows = [...rows]; //Convert Set back to array
                    let updates = [];
                    rows.forEach(function (row) {
                        updates.push(postRow(row.getData()));
                    })
                    Promise.all(updates).then(function () {
                        table.setData();
                        document.getElementById("save").style.display = "none";
                        document.getElementById("reset").style.display = "none";
                        <?php if (isset($calendar) && $calendar) : ?>
                        getSchedules();
                        <?php endif; ?>
                    });
                }

                //Save changes to the table on "Save" button click
                document.getElementById("save").addEventListener("click", save);

                function reset(){
                    document.getElementById("save").style.display = "none";
                    document.getElementById("reset").style.display = "none";
                    document.getElementById("save").disabled = true;
                    document.getElementById("reset").disabled = true;
                    table.setData();
                    <?php if (isset($calendar) && $calendar) : ?>
                    getSchedules();
                    <?php endif; ?>
                }

                //Reset table contents on "Reset the table" button click
                document.getElementById("reset").addEventListener("click", reset);

                function multiListHeaderFilter(headerValue, rowValue, rowData, filterParams){
                    let flagEmpty = false;
                    let hv = headerValue;
                    if(hv.includes('null')){
                        flagEmpty = true;
                        hv = hv.filter(function(item) {
                            return item !== 'null'
                        });
                    }
                    let checker = (arr, target) => target.every(v => arr.includes(v));
                    if(flagEmpty){
                        if(hv.length === 0){
                            return rowValue.length === 0;
                        }
                        return checker(rowValue, hv) || rowValue.length === 0;
                    }
                    return checker(rowValue, hv);
                }

                function ListHeaderFilter(headerValue, rowValue, rowData, filterParams){
                    if(headerValue === 'null'){
                        return rowValue === null;
                    }
                    return rowValue === headerValue;
                }

                <?php if(isset($reloadable) && $reloadable) : ?>
                let interval;
                function ToggleReload() {
                    x = document.getElementById("reload_enabled").checked;
                    if (x) {
                        document.getElementById("save").style.display = "none";
                        document.getElementById("reset").style.display = "none";
                        document.getElementById("save").disabled = true;
                        document.getElementById("reset").disabled = true;
                        editable = false;
                        interval = setInterval(function(){table.setData();},5000);
                    }
                    else {
                        editable = true;
                        clearInterval(interval);
                    }
                }
                <?php endif; ?>
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
