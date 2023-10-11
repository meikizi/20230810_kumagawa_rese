'use strict';

window.addEventListener('DOMContentLoaded', function () {
    const file = document.getElementById('file');
    const fileName = document.getElementById('file_name');
    file.addEventListener('change', (event) => {
        const files = event.target.files;
        const fileNames = [];
        if (files.length > 1) {
            fileName.textContent = '選択可能なファイル数は1つまでです。';
            return;
        }
        for (let i = 0; i < files.length; i++) {
            fileNames.push(files[i].name);
        }
        const fileNames_list = fileNames.join('\n');
        fileName.textContent = fileNames_list ? fileNames_list : '選択されていません';
    });

});
