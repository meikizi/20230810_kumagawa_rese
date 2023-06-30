'use strict';

window.addEventListener('DOMContentLoaded', function () {
    let input_date = document.getElementById('input_date');
    let select_time = document.getElementById('select_time');
    let select_number = document.getElementById('select_number');
    let date = document.getElementById('date');
    let time = document.getElementById('time');
    let number = document.getElementById('number');

    input_date.addEventListener("change", function () {
        date.value = this.value;
    });
    select_time.addEventListener("change", function () {
        time.value = this.value;
    });
    select_number.addEventListener("change", function () {
        number.value = this.value + '人';
    });
});
