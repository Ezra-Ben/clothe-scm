/**
 * Notifications Management JavaScript
 * Handles marking notifications as read across the application
 */

function markAsRead(notificationId) {
    // Mark notification as read when action button is clicked
    fetch(`/notifications/${notificationId}/mark-read`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        }
    }).then(response => {
        if (response.ok) {
            // Optionally update UI without page refresh
            console.log(`Notification ${notificationId} marked as read`);
        }
    }).catch(error => {
        console.error('Error marking notification as read:', error);
    });
}

// Optional: Auto-refresh notification counts after marking as read
function refreshNotificationCount() {
    // This could be used to update badge counts dynamically
    // Implementation depends on your specific needs
}
