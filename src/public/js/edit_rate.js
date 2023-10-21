'use strict';

window.addEventListener('DOMContentLoaded', function () {
    const $rate = document.getElementById('rate');
    const $rate_input = document.getElementById('rate_input');

    $rate.addEventListener('click', function () {
        if (!$rate.classList.contains('hidden')) {
            $rate.classList.add('hidden');
        }
        else {
            $rate.classList.remove('hidden');
        }
        if ($rate_input.classList.contains('show')) {
            $rate_input.classList.remove('show');
        }
        else {
            $rate_input.classList.add('show');
        }
    });
});
