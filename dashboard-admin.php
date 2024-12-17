<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
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
    <title>Dashboard Admin - Katalog Buku</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<header>
    <h1>Dashboard Admin</h1>
    <!-- Tombol Logout -->
    <a href="?logout=true" style="float: right; padding: 10px; background-color: #dc3545; color: white; border-radius: 5px; text-decoration: none;">Logout</a>
</header>

<main>

<h2>Kelola Data Buku</h2>

<form id="add-book-form" onsubmit="return addBook(event)">
    <input type="text" id="bookTitle" placeholder="Judul Buku" required /><br />
    <input type="text" id="author" placeholder="Pengarang" required /><br />
    <input type="text" id="genre" placeholder="Genre"><br />
    <input type="number" id="stock" placeholder="Stok Buku" required min="0"/><br /> <!-- Input untuk stok -->
    <input type="file" id="bookImage" accept="image/*" required /><br />
    <button type="submit">Tambah Buku</button>
</form>

<h3>Daftar Buku:</h3>
<table id="book-list">
    <thead>
        <tr>
            <th>Judul Buku</th>
            <th>Pengarang</th>
            <th>Genre</th>
            <th>Stok</th> <!-- Kolom untuk stok -->
            <th>Gambar</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>

<!-- Form untuk Memperbarui Stok -->
<div id="update-stock-form" style="display:none;">
    <h3>Perbarui Stok Buku</h3>
    <input type="hidden" id="update-index">
    <input type="number" id="new-stock" placeholder="Stok Baru" required min="0"/>
    <button onclick='updateStock()'>Simpan Perubahan</button>
    <button onclick='cancelUpdate()'>Batal</button>
</div>

<!-- Form untuk Mengedit Buku -->
<div id="edit-book-form" style="display:none;">
    <h3>Edit Buku</h3>
    <input type="hidden" id="edit-index">
    <input type="text" id="edit-bookTitle" placeholder="Judul Buku" required /><br />
    <input type="text" id="edit-author" placeholder="Pengarang" required /><br />
    <input type="text" id="edit-genre" placeholder="Genre"><br />
    <input type="number" id="edit-stock" placeholder="Stok Buku" required min="0"/><br /> <!-- Input untuk stok -->
    <input type="file" id="edit-bookImage" accept="image/*"/><br /> <!-- Gambar opsional -->
    <button onclick='saveEdit()'>Simpan Perubahan</button>
    <button onclick='cancelEdit()'>Batal</button>
</div>

</main>

<footer>&copy; 2024 Katalog Buku.</footer>

<script src="script.js"></script>

<script>
// Fungsi untuk menampilkan notifikasi
function showNotification(message) {
   const notification = document.getElementById('notification');
   notification.innerText = message;
   notification.style.display = 'block'; // Tampilkan notifikasi
   setTimeout(() => {
       notification.style.display = 'none'; // Sembunyikan setelah beberapa detik
   }, 3000); // Tampilkan selama 3 detik
}

// Fungsi untuk menambah buku
function addBook(event) {
   event.preventDefault();
   const title = document.getElementById('bookTitle').value;
   const author = document.getElementById('author').value;
   const genre = document.getElementById('genre').value;
   const stock = document.getElementById('stock').value; // Ambil nilai stok dari input
   const imageFile = document.getElementById('bookImage').files[0];

   const reader = new FileReader();
   reader.onload = function(e) {
       const newBook = { title, author, genre, stock, image: e.target.result }; // Tambahkan stok ke objek buku
       
       // Ambil data buku yang sudah ada dari localStorage
       let books = JSON.parse(localStorage.getItem('books')) || [];
       books.push(newBook); // Tambahkan buku baru ke array

       localStorage.setItem('books', JSON.stringify(books)); // Simpan kembali ke localStorage

       displayBooks(); // Tampilkan daftar buku
       document.getElementById('add-book-form').reset(); // Reset form input
       showNotification("Buku berhasil ditambahkan!"); // Tampilkan notifikasi
   };
   reader.readAsDataURL(imageFile); // Membaca file gambar sebagai data URL
}

// Fungsi untuk menampilkan daftar buku
function displayBooks() {
   const bookList = document.querySelector('#book-list tbody');
   bookList.innerHTML = '';

   // Ambil data buku dari localStorage
   const books = JSON.parse(localStorage.getItem('books')) || []; 

   books.forEach((book, index) => {
       const row = document.createElement('tr');
       row.innerHTML = `
           <td>${book.title}</td>
           <td>${book.author}</td>
           <td>${book.genre}</td>
           <td>${book.stock}</td> <!-- Tampilkan stok -->
           <td><img src="${book.image}" alt="${book.title}" style='width: 50px; height: auto;'></td>
           <td><button onclick='confirmDelete(${index})'>Hapus</button></td>
           <td><button onclick='showEditBookForm(${index})'>Edit</button></td>`; // Tombol untuk mengedit buku
       bookList.appendChild(row);
   });
}

// Fungsi untuk konfirmasi penghapusan
function confirmDelete(index) {
   if (confirm("Apakah Anda yakin ingin menghapus buku ini?")) {
       let books = JSON.parse(localStorage.getItem('books')) || []; // Ambil data buku dari localStorage
       books.splice(index, 1); // Menghapus buku dari array berdasarkan index
       localStorage.setItem('books', JSON.stringify(books)); // Update local storage

       displayBooks(); // Menampilkan kembali daftar buku setelah penghapusan
       showNotification("Buku berhasil dihapus!"); // Tampilkan notifikasi penghapusan
   }
}

// Fungsi untuk menampilkan form edit buku
function showEditBookForm(index) {
    const books = JSON.parse(localStorage.getItem('books')) || []; 
    const bookToEdit = books[index]; 

    document.getElementById('edit-index').value = index; 
    document.getElementById('edit-bookTitle').value = bookToEdit.title; 
    document.getElementById('edit-author').value = bookToEdit.author; 
    document.getElementById('edit-genre').value = bookToEdit.genre; 
    document.getElementById('edit-stock').value = bookToEdit.stock; 

    document.getElementById('edit-book-form').style.display = 'block'; 
}

// Fungsi untuk menyimpan perubahan edit buku
function saveEdit() {
    const index = document.getElementById('edit-index').value; 
    const updatedTitle = document.getElementById('edit-bookTitle').value; 
    const updatedAuthor = document.getElementById('edit-author').value; 
    const updatedGenre = document.getElementById('edit-genre').value; 
    const updatedStock = document.getElementById('edit-stock').value; 

    let books = JSON.parse(localStorage.getItem('books')) || []; 

    if (index >= 0 && index < books.length) {
        books[index].title = updatedTitle; 
        books[index].author = updatedAuthor; 
        books[index].genre = updatedGenre; 
        books[index].stock = updatedStock;

        localStorage.setItem('books', JSON.stringify(books)); 

        displayBooks(); 
        showNotification("Buku berhasil diperbarui!"); 

        cancelEdit(); 
    }
}

// Fungsi untuk membatalkan edit dan menyembunyikan form edit
function cancelEdit() {
    document.getElementById('edit-book-form').style.display = 'none'; 
}

// Fungsi untuk menampilkan form pembaruan stok
function showUpdateStockForm(index, currentStock) {
    document.getElementById('update-index').value = index; 
    document.getElementById('new-stock').value = currentStock; 
    document.getElementById('update-stock-form').style.display = 'block'; 
}

// Fungsi untuk memperbarui stok buku
function updateStock() {
    const index = document.getElementById('update-index').value; 
    const newStock = document.getElementById('new-stock').value;

    let books = JSON.parse(localStorage.getItem('books')) || []; 

    if (index >= 0 && index < books.length) {
        books[index].stock = newStock;

        localStorage.setItem('books', JSON.stringify(books)); 

        displayBooks(); 

        showNotification("Stok berhasil diperbarui!"); 

        cancelUpdate(); 
    }
}

// Fungsi untuk membatalkan pembaruan stok dan menyembunyikan form
function cancelUpdate() {
    document.getElementById('update-stock-form').style.display = 'none'; 
}

// Panggil fungsi untuk menampilkan daftar buku saat halaman dimuat 
displayBooks();
</script>

</body>
</html>

