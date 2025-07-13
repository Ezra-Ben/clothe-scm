document.addEventListener('DOMContentLoaded', function () {
    const alert = document.getElementById('success-alert');
    if (alert) {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s ease-out';
            alert.style.opacity = '0';
            setTimeout(() => {
                alert.style.display = 'none';
            }, 500); //match fade time
        }, 2000); // how long to wait before starting fade
    }
});
