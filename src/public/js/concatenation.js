'use strict';

function selectYmd() {
    let year = document.getElementById('year');
    let month = document.getElementById('month');
    let day = document.getElementById('day');
    let birthday = document.getElementById('birthday');
    birthday.value = year.value + '-' + month.value + '-' + day.value;
}

function inputNumber() {
    let phone_number1 = document.getElementById('phone_number1');
    let phone_number2 = document.getElementById('phone_number2');
    let phone_number3 = document.getElementById('phone_number3');
    let phone_number = document.getElementById('phone_number');
    phone_number.value = phone_number1.value + phone_number2.value + phone_number3.value;
}

function inputAddress() {
    let address = document.getElementById('address');
    let building_name = document.getElementById('building_name');
    let full_address = document.getElementById('full_address');
    full_address.value = address.value + building_name.value;
}
