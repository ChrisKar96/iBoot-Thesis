<?= $this->extend('template') ?>

<?= $this->section('content') ?>

<?php
if (isset($columns, $apiTarget)): ?>

    <main role="main" class="py-5">
        <div class="container">

            <div class="my-2" id="calendar"></div>

            <script>

                function api_call(url, method, data) {
                    return new Promise(function(resolve, reject) {
                        let xhr = new XMLHttpRequest();
                        xhr.open(method, url, true);
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
                        await api_call("<?= $apiTarget ?>/" + data.id + "/update", "POST", data);
                    }
                }

                <?php
                if (isset($JS_bef_tb)) {
                    echo $JS_bef_tb;
                }
    ?>

                var eventlist = [{"title":"Conference","start":"2023-06-26","end":"2023-06-28"},{"title":"Meeting","start":"2023-06-27T10:30:00+00:00","end":"2023-06-27T12:30:00+00:00"},{"title":"Lunch","start":"2023-06-27T12:00:00+00:00"},{"title":"Birthday Party","start":"2023-06-28T07:00:00+00:00"},{"url":"http:\/\/google.com\/","title":"Click for Google","start":"2023-06-28"}];

                document.addEventListener('DOMContentLoaded', function() {
                    var calendarEl = document.getElementById('calendar');

                    var calendar = new FullCalendar.Calendar(calendarEl, {
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
                        editable: true,
                        events: eventlist,
						<?php if (session()->get('locale') === 'el') : ?>
                        locale: 'el',
                        <?php else: ?>
                        locale: 'en',
						<?php endif; ?>
                    });

                    calendar.render();
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