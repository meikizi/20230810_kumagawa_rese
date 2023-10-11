'use strict';

window.addEventListener('DOMContentLoaded', function () {
    const $star5 = document.getElementById('star5');
    const $star4 = document.getElementById('star4');
    const $star3 = document.getElementById('star3');
    const $star2 = document.getElementById('star2');
    const $star1 = document.getElementById('star1');
    const $starValue = document.getElementById('star_value');

    $star5.addEventListener('click', function () {
        $starValue.innerHTML = '星5';
    });

    $star4.addEventListener('click', function () {
        $starValue.innerHTML = '星4';
    });

    $star3.addEventListener('click', function () {
        $starValue.innerHTML = '星3';
    });

    $star2.addEventListener('click', function () {
        $starValue.innerHTML = '星2';
    });

    $star1.addEventListener('click', function () {
        $starValue.innerHTML = '星1';
    });

});
