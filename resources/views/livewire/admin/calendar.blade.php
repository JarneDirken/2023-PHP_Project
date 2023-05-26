<div id="calendar"></div>
<x-help-modal>
    <x-slot name="title">Info kalender</x-slot>
    <x-slot name="content">
        <section class="border-b">
            <p>Op deze pagina kan je:</p>
            <ul class="ml-2 mt-2 list-disc">
                <li class="mb-2">De kalender bekijken</li>
            </ul>
        </section>
        <section class="mt-2 border-b">
            <p class="mt-2">Hierin kan je volgende dingen doen:</p>
            <ul class="mt-2 ml-2 list-disc [&>li]:mb-2">
                <li>Je kan rechtsboven op de knoppen klikken om de kalender te zien per maand, per week of per dag.</li>
                <li>Op de kalender staan alle activiteiten van de club.</li>
                <li>De activiteiten met een groene kleur zijn evenementen. Als je daar op klikt, zie je een pop-up met informatie over dat evenement en een <button class="bg-gray-800 hover:bg-gray-700 inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest focus:outline-none disabled:opacity-25 transition mt-2">Inschrijven evenement</button>-knop om je in te schrijven bij dat evenement.</li>
                <li>De activiteiten met een rood bolletje zijn ritten. Als je daar op klikt, zie je een pop-up met informatie over die rit en als er nog geen ritverkenner is voor die rit dan is er ook een <button class="bg-gray-800 hover:bg-gray-700 inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest focus:outline-none disabled:opacity-25 transition mt-2">Ritverkenner worden</button>-knop om ritverkenner te worden van die rit.</li>
            </ul>
        </section>
    </x-slot>
</x-help-modal>
<style>
    #calendar {
        width: 100%;
        height: 750px;
        text-align: center;
    }
    #left{
        text-align: left;
    }
</style>

@push('script')
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar-scheduler@6.1.5/index.global.min.js'></script>
    <script>
        function handleClick(id, route) {
            window.location.href = `/${route}?id=${id}`;
        }

        document.addEventListener('livewire:load', function() {
            var calendarEl = document.getElementById('calendar');
            var events = @json($events);
            // Initialiseren van de FullCalendar, alle informatie die getoond moet worden, het vertalen van buttons naar nederlands
            var calendar = new FullCalendar.Calendar(calendarEl, {
                schedulerLicenseKey: 'CC-Attribution-NonCommercial-NoDerivatives',
                nowIndicator:true,
                locale: 'nl',
                buttonText: {
                    month: "maand",
                    week: "week",
                    day: "dag",
                },
                allDayText: 'Hele dag',
                headerToolbar: {
                    center:"title",
                    left: "prev,next",
                    right: "dayGridMonth,timeGridWeek,timeGridDay"
                },

                timezone : 'Europe/Brussels',
                eventClick: function(info) {

                    var eventObj = info.event;
                    console.log("start = " + eventObj.start);
                    console.log('distance = ' + eventObj.extendedProps.distance);

                    console.log('users = ' + eventObj.users);

                    if (eventObj.extendedProps.distance){
                        if (eventObj.extendedProps.ritverkenner_first_name){
                            Swal.fire({
                                // Weergeven van informatie over het evenement met een toegewezen ritverkenner
                                title: eventObj.start.toLocaleDateString('nl', {  month:"long", day:"numeric"}),
                                html:'<p id="left"><b>' +eventObj.title + '</b>'+'<br>Locatie: '+eventObj.extendedProps.location+'<br>Beschrijving: ' + eventObj.extendedProps.description+ '<br>Afstand: '+eventObj.extendedProps.distance+'km<br>Ritverkenner: ' + eventObj.extendedProps.ritverkenner_first_name + ' ' + eventObj.extendedProps.ritverkenner_last_name + '</p>',
                                showConfirmButton:false
                            });
                        } else{
                            Swal.fire({
                                // Weergeven van informatie over het evenement zonder een toegewezen ritverkenner en een knop om een ritverkenner te worden
                                title: eventObj.start.toLocaleDateString('nl', { month:"long", day:"numeric" }),
                                html: '<p id="left"><b>' + eventObj.title + '</b>' + '<br>Locatie: ' + eventObj.extendedProps.location + '<br>Beschrijving: ' + eventObj.extendedProps.description + '<br>Afstand: ' + eventObj.extendedProps.distance + 'km<br>Ritverkenner: -<br><button class="bg-gray-800 hover:bg-gray-700 inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest focus:outline-none disabled:opacity-25 transition mt-2" onclick="handleClick(' + eventObj.id + ', `inschrijven-ritverkenner`)">Ritverkenner worden</button></p>',
                                showConfirmButton: false
                            });
                        }
                    } else{
                        // Weergeven van informatie over het evenement zonder afstand en een knop om in te schrijven voor het evenement
                        Swal.fire({
                            title: eventObj.start.toLocaleDateString('nl', { month:"long", day:"numeric"}),
                            html:'<p id="left"><b>' + eventObj.title + '</b>' +'<br>Locatie: '+eventObj.extendedProps.location+'<br>Beschrijving: ' + eventObj.extendedProps.description + '<br><button class="bg-gray-800 hover:bg-gray-700 inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest focus:outline-none disabled:opacity-25 transition mt-2" onclick="handleClick(' + eventObj.id + ', `inschrijven-evenement`)">Inschrijven evenement</button></p>',
                            showConfirmButton:false
                        });
                    }

                },
                initialView: 'dayGridMonth',
                slotMinTime: '8:00:00',
                slotMaxTime: '21:00:00',

                fixedWeekCount: false,
                eventSources:[{
                    events: @json($events),
                }]



            });


            calendar.render();
        });
    </script>
@endpush
