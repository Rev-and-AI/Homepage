// service.js

// モーダルを表示する関数
function showDetails(title, content) {
    document.getElementById('details-title').innerText = title;
    document.getElementById('details-content').innerText = content;
    document.getElementById('details-modal').style.display = 'flex';
}

// モーダルを閉じる関数
function closeDetails() {
    document.getElementById('details-modal').style.display = 'none';
}
