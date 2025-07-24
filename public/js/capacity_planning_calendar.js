document.addEventListener('DOMContentLoaded', function () {
    const calendarEl = document.getElementById('calendar');
    if (!calendarEl) return;

    const events = window.calendarEvents || [];

    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'timeGridWeek', // or 'timeGridDay' or 'dayGridMonth'
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'timeGridDay,timeGridWeek,dayGridMonth'
        },
        plugins: [
            FullCalendar.dayGridPlugin,
            FullCalendar.timeGridPlugin,
            FullCalendar.interactionPlugin
        ],
        events: events,
        editable: true,
        slotMinTime: "06:00:00",
        slotMaxTime: "22:00:00",
        nowIndicator: true,
        eventColor: '#378006',

        eventDrop(info) {
            if (!confirm("Reschedule this assignment?")) {
                info.revert();
                return;
            }
            updateAssignment(info);
        },

        eventResize(info) {
            if (!confirm("Change assignment duration?")) {
                info.revert();
                return;
            }
            updateAssignment(info);
        }
    });

    calendar.render();

    function updateAssignment(info) {
        const event = info.event;
        const payload = {
            assignment_id: event.id,
            assigned_start_time: event.start.toISOString(),
            assigned_end_time: event.end.toISOString(),
            _token: window.csrfToken
        };

        fetch(window.assignmentUpdateUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(payload)
        })
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                alert("Error: " + data.message);
                info.revert();
            } else {
                alert("Assignment updated!");
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert("Something went wrong.");
            info.revert();
        });
    }
});
