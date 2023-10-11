'use strict';

window.addEventListener('DOMContentLoaded', function () {
    const inputDate = document.getElementById('input_date');
    const selectTime = document.getElementById('select_time');
    const selectNumber = document.getElementById('select_number');
    const date = document.getElementById('date');
    const time = document.getElementById('time');
    const number = document.getElementById('number');

    inputDate.addEventListener("change", function () {
        date.value = this.value;
    });
    selectTime.addEventListener("change", function () {
        time.value = this.value;
    });
    selectNumber.addEventListener("change", function () {
        number.value = this.value + '人';
    });
});
