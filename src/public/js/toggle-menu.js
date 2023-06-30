'use strict';

window.addEventListener('DOMContentLoaded', function () {
    let $open_button = document.getElementById('openMenuButton');
    let $close_button = document.getElementById('closeMenuButton');
    let $menu = document.getElementById('navbarSupportedContent');
    $open_button.addEventListener('click', function () {
        if ($menu.classList.contains('show')) {
            $menu.classList.remove('show');
        }
        else {
            $menu.classList.add('show');
        }
    });

    $close_button.addEventListener('click', function () {
        if ($menu.classList.contains('show')) {
            $menu.classList.remove('show');
        }
        else {
            $menu.classList.add('show');
        }
    });
});
