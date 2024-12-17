<?php
$servername = "localhost";
$username = "root"; // default username di Laragon
$password = ""; // default password biasanya kosong
$database = "katalog_b";

// Membuat koneksi
$conn = mysqli_connect($servername, $username, $password, $database);

// Cek koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
