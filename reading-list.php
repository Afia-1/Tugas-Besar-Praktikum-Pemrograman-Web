<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'user') {
    header("Location: index.php"); // Arahkan ke halaman login jika tidak memiliki akses
    exit();
}

// Logout jika diminta
if (isset($_GET['logout'])) {
    session_destroy(); // Menghancurkan session
    header("Location: index.php"); // Arahkan kembali ke halaman login
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Daftar Bacaan - Katalog Buku</title>
   <link rel="stylesheet" href="styles.css">
   <style>
       .notification {
           display: none;
           background-color: #28a745; /* Warna hijau */
           color: white;
           padding: 10px;
           margin: 10px 0;
           border-radius: 5px;
           text-align: center;
       }
   </style>
</head>
<body>

<header><h1>Daftar Bacaan Saya</h1></header>

<main>

<!-- Notifikasi -->
<div id="notification" class="notification"></div>

<h2>Daftar Bacaan:</h2>

<table id="reading-list">
   <thead>
       <tr><th>Judul Buku</th><th>Pengarang</th><th>Genre</th><th>Aksi</th></tr>
   </thead>
   <tbody></tbody><!-- Daftar bacaan akan ditampilkan di sini --></table>

<!-- Tombol Logout -->
<a href="?logout=true">Logout</a>

</main>

<footer>&copy; 2024 Katalog Buku.</footer>

<script>
// Fungsi untuk menampilkan daftar bacaan dari local storage 
function displayReadingList() {
    const readingListTable = document.querySelector('#reading-list tbody');
    readingListTable.innerHTML = '';

    const readingList = JSON.parse(localStorage.getItem('readingList')) || []; // Ambil data daftar bacaan dari local storage

    readingList.forEach((book, index) => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${book.title}</td>
            <td>${book.author}</td>
            <td>${book.genre}</td>
            <td><button onclick='confirmRemoveFromReadingList(${index})'>Hapus dari Daftar Bacaan</button></td>`; // Tombol untuk menghapus dari daftar bacaan
        readingListTable.appendChild(row);
    });
}

// Fungsi untuk mengonfirmasi penghapusan buku dari daftar bacaan
function confirmRemoveFromReadingList(index) {
    if (confirm("Apakah Anda yakin ingin menghapus buku ini dari daftar bacaan?")) {
        removeFromReadingList(index); // Jika ya, panggil fungsi untuk menghapus
    }
}

// Fungsi untuk menghapus buku dari daftar bacaan
function removeFromReadingList(index) {
    let readingList = JSON.parse(localStorage.getItem('readingList')) || []; // Ambil data daftar bacaan dari local storage
    readingList.splice(index, 1); // Hapus buku dari array berdasarkan index

    localStorage.setItem('readingList', JSON.stringify(readingList)); // Simpan kembali ke local storage
    displayReadingList(); // Tampilkan kembali daftar bacaan setelah penghapusan

    showNotification("Buku berhasil dihapus dari daftar bacaan!"); // Tampilkan notifikasi setelah penghapusan
}

// Fungsi untuk menampilkan notifikasi
function showNotification(message) {
    const notification = document.getElementById('notification');
    notification.innerText = message;
    notification.style.display = 'block'; // Tampilkan notifikasi
    setTimeout(() => {
        notification.style.display = 'none'; // Sembunyikan setelah beberapa detik
    }, 3000); // Tampilkan selama 3 detik
}

// Panggil fungsi untuk menampilkan daftar bacaan saat halaman dimuat 
displayReadingList();
</script>

</body>
</html>

