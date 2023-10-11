'use strict';

window.addEventListener('DOMContentLoaded', function () {
    const $openButton = document.getElementById('open_menu_button');
    const $closeButton = document.getElementById('close_menu_button');
    const $menu = document.getElementById('navbar_supported_content');

    $openButton.addEventListener('click', function () {
        if ($menu.classList.contains('show')) {
            $menu.classList.remove('show');
        }
        else {
            $menu.classList.add('show');
        }
    });

    $closeButton.addEventListener('click', function () {
        if ($menu.classList.contains('show')) {
            $menu.classList.remove('show');
        }
        else {
            $menu.classList.add('show');
        }
    });

});
