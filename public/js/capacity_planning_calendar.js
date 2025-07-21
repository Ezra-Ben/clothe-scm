document.addEventListener('DOMContentLoaded', function () {
    const calendarEl = document.getElementById('calendar');
    if (!calendarEl) return;

    const events = window.calendarEvents || [];
    const resources = window.calendarResources || [];

    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'resourceTimeGridDay',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'resourceTimeGridDay,resourceTimeGridWeek,dayGridMonth'
        },
        schedulerLicenseKey: 'CC-Attribution-NonCommercial-NoDerivatives',
        resources: resources,
        events: events,
        editable: true,
        resourceAreaWidth: '15%',
        slotMinTime: "06:00:00",
        slotMaxTime: "22:00:00",
        nowIndicator: true,
        eventColor: '#378006',

        eventDrop: function (info) {
            if (!confirm("Are you sure you want to reschedule " + info.event.title + "?")) {
                info.revert();
            } else {
                alert('Assignment rescheduled! (Implement AJAX)');
            }
        },

        eventResize: function (info) {
            if (!confirm("Are you sure you want to resize " + info.event.title + "?")) {
                info.revert();
            } else {
                alert('Assignment duration changed! (Implement AJAX)');
            }
        }
    });

    calendar.render();
});
