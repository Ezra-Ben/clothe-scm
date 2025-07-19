 document.addEventListener('DOMContentLoaded', function () {
        const statusField = document.getElementById('status');
        const correctiveGroup = document.getElementById('corrective-action-group');

        function toggleCorrectiveField() {
            correctiveGroup.style.display = statusField.value === 'failed' ? 'block' : 'none';
        }

        statusField.addEventListener('change', toggleCorrectiveField);
        toggleCorrectiveField(); 
    });