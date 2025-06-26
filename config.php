<?php
// Informasi koneksi database
$host = 'localhost';      // Server database, biasanya 'localhost'
$user = 'root';           // Username database
$password = '';           // Password database, kosongkan jika tidak ada
$database = 'toko_kopi_db'; // Nama database

// Membuat koneksi ke database
$koneksi = mysqli_connect($host, $user, $password, $database);
?> 