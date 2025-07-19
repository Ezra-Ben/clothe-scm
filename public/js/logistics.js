// public/js/logistics.js

function filterCarriers() {
    const input = document.getElementById('carrierSearch').value.toLowerCase();
    const rows = document.querySelectorAll('#carrierTable tbody tr');

    rows.forEach(row => {
        const name = row.cells[0].textContent.toLowerCase();
        const serviceAreas = row.cells[2].textContent.toLowerCase();

        if (name.includes(input) || serviceAreas.includes(input)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

function filterPods() {
    const input = document.getElementById('podSearch').value.toLowerCase();
    const rows = document.querySelectorAll('#podTable tbody tr');

    rows.forEach(row => {
        const deliveredBy = row.cells[2].textContent.toLowerCase();
        const receivedBy = row.cells[3].textContent.toLowerCase();

        if (deliveredBy.includes(input) || receivedBy.includes(input)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}
