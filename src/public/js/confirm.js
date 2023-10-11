'use strict';

window.addEventListener('DOMContentLoaded', function () {
    const confirmButton = document.getElementById('confirm_button');
    const closeConfirmButton = document.getElementById('close_confirm_button');
    const confirm = document.getElementById('buy_confirm_modal');

    confirmButton.addEventListener('click', function () {
        if (confirm.classList.contains('show')) {
            confirm.classList.remove('show');
        }
        else {
            confirm.classList.add('show');
        }
    });

    closeConfirmButton.addEventListener('click', function () {
        if (confirm.classList.contains('show')) {
            confirm.classList.remove('show');
        }
        else {
            confirm.classList.add('show');
        }
    });
});
