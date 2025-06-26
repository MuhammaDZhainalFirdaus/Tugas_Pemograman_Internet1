<?php
session_start();
include 'config.php';

// Sebaiknya ada pengecekan role admin di sini, tapi untuk sekarang kita fokus mengambil data
// if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
//     header('Content-Type: application/json');
//     echo json_encode(['status' => 'error', 'message' => 'Akses ditolak. Hanya admin yang dapat mengakses.']);
//     exit;
// }

header('Content-Type: application/json');

$products = [];

$query = "SELECT id, nama_produk, deskripsi, harga, gambar, tanggal_upload FROM products ORDER BY tanggal_upload DESC";
$result = mysqli_query($koneksi, $query);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        // Mengonversi tipe data jika perlu, misal harga ke float
        $row['harga'] = (float)$row['harga'];
        $products[] = $row;
    }
    mysqli_free_result($result);
} else {
    // Jika ada error saat query
    echo json_encode(['status' => 'error', 'message' => 'Gagal mengambil data produk: ' . mysqli_error($koneksi)]);
    mysqli_close($koneksi);
    exit;
}

mysqli_close($koneksi);
echo json_encode($products);
?> 