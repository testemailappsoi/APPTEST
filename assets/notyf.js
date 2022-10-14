import { Notyf } from 'notyf';
import 'notyf/notyf.min.css'; // for React, Vue and Svelte

// Create an instance of Notyf
const notyf = new Notyf({
    duration: 3000,
    position: {
        x: 'center',
        y: 'top',
    },
    types: [
        {
            type: 'info',
            background: '#00bfff',
            icon: false
        },
        {
            type: 'warning',
            background: '#ffd700',
            icon: false
        },
    ]
});

let messages = document.querySelectorAll('#notyf-message');

messages.forEach(message => {
    if (message.className === 'success') {
        notyf.success(message.innerHTML);
    }

    if (message.className === 'error') {
        notyf.error(message.innerHTML);
    }

    if (message.className === 'info') {
        notyf.open({
            type: 'info',
            message: '<b>Info</b> - ' + message.innerHTML,
        });
    }

    if (message.className === 'vous') {
        notyf.open({
            type: 'info',
            message: '<b>Info</b> - ' + message.innerHTML,
        });
    }

    if (message.className === 'warning') {
        notyf.open({
            type: 'warning',
            message: message.innerHTML
        });
    }
});

