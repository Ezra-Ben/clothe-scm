
    document.addEventListener('DOMContentLoaded', function() {
        const editScheduleModal = document.getElementById('editScheduleModal');
        editScheduleModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const scheduleId = button.getAttribute('data-schedule-id');
            const description = button.getAttribute('data-description');
            const startDate = button.getAttribute('data-start-date');
            const endDate = button.getAttribute('data-end-date');
            const status = button.getAttribute('data-status');

            const modalForm = editScheduleModal.querySelector('#editScheduleForm');
            modalForm.action = `/production/schedules/${scheduleId}`;

            editScheduleModal.querySelector('#editScheduleId').value = scheduleId;
            editScheduleModal.querySelector('#editScheduleDescription').value = description;
            editScheduleModal.querySelector('#editScheduleStartDate').value = startDate;
            editScheduleModal.querySelector('#editScheduleEndDate').value = endDate;
            editScheduleModal.querySelector('#editScheduleStatus').value = status;
        });
    });