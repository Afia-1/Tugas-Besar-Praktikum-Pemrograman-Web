<?php
session_start(); // Memulai session

// Proses pendaftaran jika form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_username = $_POST['username'];
    $new_password = $_POST['password'];
    
    // Simulasi penyimpanan pengguna baru (seharusnya disimpan dalam database)
    // Untuk contoh ini, kita simpan dalam localStorage (jika menggunakan PHP, ini hanya simulasi)
    
    // Ambil data pengguna yang sudah ada dari localStorage
    echo "<script>var users = JSON.parse(localStorage.getItem('users')) || [];</script>";
    
    echo "<script>users.push({ username: '$new_username', password: '$new_password' }); localStorage.setItem('users', JSON.stringify(users));</script>";
    
    header("Location: index.php"); // Arahkan kembali ke halaman login setelah pendaftaran
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Daftar - Katalog Buku</title>
   <link rel="stylesheet" href="styles.css">
</head>
<body>

<div class="register-container">
   <h2>Daftar Akun Baru</h2>
   <form method="POST" action="">
       <input type="text" name="username" placeholder="Username" required>
       <input type="password" name="password" placeholder="Password" required>
       <button type="submit">Daftar</button>
   </form>

   <!-- Link kembali ke login -->
   <p>Sudah punya akun? <a href="index.php">Login di sini</a></p>
</div>

<script src="script.js"></script>
</body>
</html>

