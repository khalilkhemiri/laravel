import _ from 'lodash';
window._ = _;

import axios from 'axios';
window.axios = axios;

// Get CSRF token from the meta tag
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
window.axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

function createEchoInstance() {
    return new Echo({
        broadcaster: 'pusher',
        key: import.meta.env.VITE_PUSHER_APP_KEY,
        cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
        encrypted: true,
        debug: true,
        authEndpoint: '/broadcasting/auth', // Use the default auth endpoint
        auth: {
            headers: {
                'X-CSRF-TOKEN': csrfToken,
            },
        },
    });
}

// Create the Echo instance
window.Echo = createEchoInstance();

if (window.Echo) {
    // Check if the user is authenticated
    if (window.Laravel && window.Laravel.user) {
        console.log('User is authenticated:', window.Laravel.user);

        window.Echo.join(`users.${window.Laravel.user.id}`)
            .here((users) => {
                console.log('Current users:', users);
                users.forEach(user => {
                    updateUserStatus(user.id, true);
                });
            })
            .joining((user) => {
                console.log('User joined:', user);
                updateUserStatus(user.id, true);
            })
            .leaving((user) => {
                console.log('User left:', user);
                updateUserStatus(user.id, false);
            })
            .error((error) => {
                console.error('Error joining the presence channel:', error);
            });
    } else {
        console.log('User is not authenticated.');
    }
} else {
    console.error('Echo is not initialized.');
}

// Function to update user status in the UI
function updateUserStatus(userId, isOnline) {
    const userElement = document.querySelector(`[data-user-id="${userId}"]`);
    if (userElement) {
        const statusSpan = userElement.querySelector('.user-status');
        if (statusSpan) {
            statusSpan.textContent = isOnline ? '(Online)' : '(Offline)';
            statusSpan.className = isOnline ? 'text-success' : 'text-danger';
        }
    }
}


setInterval(() => {
    axios.post('/update-activity')
        .then(response => {
            console.log('User activity updated');
        })
        .catch(error => {
            console.error('Error updating activity:', error);
        });
}, 60000); // Ping the server every 60 seconds

