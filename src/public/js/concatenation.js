'use strict';

function selectYmd() {
    const year = document.getElementById('year');
    const month = document.getElementById('month');
    const day = document.getElementById('day');
    const birthday = document.getElementById('birthday');
    birthday.value = year.value + '-' + month.value + '-' + day.value;
}

function inputNumber() {
    const phoneNumber1 = document.getElementById('phone_number1');
    const phoneNumber2 = document.getElementById('phone_number2');
    const phoneNumber3 = document.getElementById('phone_number3');
    const phoneNumber = document.getElementById('phone_number');
    phoneNumber.value = phoneNumber1.value + phoneNumber2.value + phoneNumber3.value;
}

function inputAddress() {
    const address = document.getElementById('address');
    const buildingName = document.getElementById('building_name');
    const fullAddress = document.getElementById('full_address');
    fullAddress.value = address.value + buildingName.value;
}
