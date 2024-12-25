import Echo from 'laravel-echo';

import Pusher from 'pusher-js';
window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
    wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
    enabledTransports: ['ws', 'wss'],
});


// window.Echo.channel(`chat.${chatChannel_id}`)
//     .listen('MessageEvent', (e) => {
//         console.log(e);
//         // console.log(chatChannel_id == e.message.chat_channel_id);
        
//     });
window.Echo.channel(`chat.${chatChannel_id}`)
    .listen('MessageEvent', (e) => {
        // Extract message details
        const { text, file_path } = e.message;

        // Generate the new message HTML
        const newMessageHtml = `
            <li class="list-group-item">
                ${text}
                ${file_path ? `<a href="/storage/${file_path}" target="_blank">Download File</a>` : ''}
            </li>
        `;

        // Append the new message to the container
        const messagesContainer = document.getElementById('messages-container');
        if (messagesContainer) {
            messagesContainer.insertAdjacentHTML('beforeend', newMessageHtml);
        } else {
            console.error('Messages container not found!');
        }
    });


window.Echo.channel('users')
    .listen('NewUsersEvent', (e) => {
        // Get the user details from the event
        const { id, name } = e;

        console.log(e);
        

        // Generate the new user list item HTML
        const newUserHtml = `
            <li class="list-group-item">
                <a href="/messageTo/${id}">
                    ${name}
                </a>
            </li>
        `;

        // Append the new user to the list (you might want to prepend it to the top)
        const usersListContainer = document.querySelector('#users-list');
        if (usersListContainer) {
            usersListContainer.insertAdjacentHTML('beforeend', newUserHtml);
        } else {
            console.error('Users list container not found!');
        }
    });




