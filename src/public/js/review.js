'use strict';

window.addEventListener('DOMContentLoaded', function () {
    let $star5 = document.getElementById('star5');
    let $star4 = document.getElementById('star4');
    let $star3 = document.getElementById('star3');
    let $star2 = document.getElementById('star2');
    let $star1 = document.getElementById('star1');
    let $star_value = document.getElementById('star_value');

    $star5.addEventListener('click', function () {
        $star_value.innerHTML = '星5';
    });

    $star4.addEventListener('click', function () {
        $star_value.innerHTML = '星4';
    });

    $star3.addEventListener('click', function () {
        $star_value.innerHTML = '星3';
    });

    $star2.addEventListener('click', function () {
        $star_value.innerHTML = '星2';
    });

    $star1.addEventListener('click', function () {
        $star_value.innerHTML = '星1';
    });

});
