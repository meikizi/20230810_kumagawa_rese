'use strict';

window.addEventListener('DOMContentLoaded', function () {
    const file = document.getElementById('file');
    const file_name = document.getElementById('file_name');
    file.addEventListener('change', (event) => {
        const files = event.target.files;
        const file_names = [];
        if (files.length > 1) {
            file_name.textContent = '選択可能なファイル数は1つまでです。';
            return;
        }
        for (let i = 0; i < files.length; i++) {
            file_names.push(files[i].name);
        }
        const file_names_list = file_names.join('\n');
        file_name.textContent = file_names_list ? file_names_list : '選択されていません';
    });

});
