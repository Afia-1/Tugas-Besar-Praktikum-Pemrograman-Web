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
   <title>Dashboard User - Katalog Buku</title>
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
       .link-reading-list {
           float: right; /* Memindahkan link ke kanan */
           font-size: 12px; /* Ukuran font lebih kecil */
           margin-top: 10px; /* Margin atas untuk pemisahan */
           text-decoration: none; /* Menghilangkan garis bawah */
           color: #007bff; /* Warna teks link */
       }
       .link-reading-list:hover {
           text-decoration: underline; /* Garis bawah saat hover */
       }
   </style>
</head>
<body>

<header><h1>Dashboard User</h1></header>

<main>

<!-- Link ke Daftar Bacaan -->
<h2><a href="reading-list.php" class="link-reading-list">Lihat Daftar Bacaan</a></h2>

<!-- Notifikasi -->
<div id="notification" class="notification"></div>

<!-- Form Pencarian Start -->
<h2>Cari Buku</h2>
<form id="search-form" onsubmit="return searchBooks(event)">
   <input type="text" id="searchQuery" placeholder="Cari buku..." required />
   <button type="submit">Cari</button>
</form>
<!-- Form Pencarian End -->

<h2>Daftar Buku:</h2>

<table id="user-book-list">
   <thead>
       <tr>
           <th>Judul Buku</th>
           <th>Pengarang</th>
           <th>Genre</th>
           <th>Stok</th>
           <th>Gambar</th>
           <th>Aksi</th> <!-- Kolom untuk aksi -->
       </tr>
   </thead>
   <tbody></tbody><!-- Daftar buku akan ditampilkan di sini --></table>

<h2>Profil Saya:</h2>

<form method="POST" action="">
   <input type="text" name="username" placeholder="Username" value="<?php echo $_SESSION['username']; ?>" required /><br />
   <input type="email" name="email" placeholder="Email"><br />
   <button type="submit">Perbarui Profil</button>
</form>

<!-- Tombol Logout -->
<a href="?logout=true">Logout</a>

</main>

<footer>&copy; 2024 Katalog Buku.</footer>

<script>
// Fungsi untuk menampilkan daftar buku dari local storage 
function displayUserBooks() {
   const userBookList = document.querySelector('#user-book-list tbody');
   userBookList.innerHTML = '';

   const books = JSON.parse(localStorage.getItem('books')) || []; // Ambil data buku dari local storage

   books.forEach((book, index) => {
       const row = document.createElement('tr');
       row.innerHTML = `
           <td>${book.title}</td>
           <td>${book.author}</td>
           <td>${book.genre}</td>
           <td>${book.stock}</td> <!-- Tampilkan stok -->
           <td><img src="${book.image}" alt="${book.title}" style='width: 50px; height: auto;'></td>
           <td><button onclick='addToReadingList(${index})'>Tambah ke Daftar Bacaan</button></td>`; // Tombol untuk menambah ke daftar bacaan
       userBookList.appendChild(row);
   });
}

// Fungsi untuk menambah buku ke daftar bacaan
function addToReadingList(index) {
    const books = JSON.parse(localStorage.getItem('books')) || []; // Ambil data buku dari local storage
    const bookToAdd = books[index]; // Ambil buku yang dipilih

    if (bookToAdd.stock <= 0) {
        alert("Stok buku tidak mencukupi!"); // Notifikasi jika stok tidak mencukupi
        return;
    }

    let readingList = JSON.parse(localStorage.getItem('readingList')) || []; // Ambil daftar bacaan dari local storage
    readingList.push(bookToAdd); // Tambahkan buku ke daftar bacaan

    // Kurangi stok buku
    bookToAdd.stock -= 1; // Kurangi stok sebanyak 1
    localStorage.setItem('books', JSON.stringify(books)); // Simpan kembali data buku yang sudah diperbarui

    localStorage.setItem('readingList', JSON.stringify(readingList)); // Simpan kembali ke local storage
    showNotification(`${bookToAdd.title} telah ditambahkan ke daftar bacaan!`); // Notifikasi berhasil ditambahkan

    displayUserBooks(); // Tampilkan daftar buku setelah penambahan
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

// Fungsi untuk mencari buku di dashboard user 
function searchBooks(event) {
     event.preventDefault(); 
     const query = document.getElementById('searchQuery').value.toLowerCase(); 
     const userBookList = document.querySelector('#user-book-list tbody'); 

     const books = JSON.parse(localStorage.getItem('books')) || []; 

     userBookList.innerHTML = ''; 

     const filteredBooks = books.filter(book => 
         book.title.toLowerCase().includes(query) || 
         book.author.toLowerCase().includes(query) || 
         book.genre.toLowerCase().includes(query)
     );

     if (filteredBooks.length > 0) {
         filteredBooks.forEach(book => {
             const row = document.createElement('tr');
             row.innerHTML = `
                 <td>${book.title}</td>
                 <td>${book.author}</td>
                 <td>${book.genre}</td>
                 <td>${book.stock}</td> <!-- Tampilkan stok -->
                 <td><img src="${book.image}" alt="${book.title}" style='width: 50px; height: auto;'></td>`;
             userBookList.appendChild(row);
         });
     } else {
         const row = document.createElement('tr');
         row.innerHTML = `<td colspan='6' style='text-align:center;'>Tidak ada hasil ditemukan.</td>`;
         userBookList.appendChild(row);
     }
}

// Panggil fungsi untuk menampilkan buku saat halaman dimuat 
displayUserBooks();
</script>

</body>
</html>

