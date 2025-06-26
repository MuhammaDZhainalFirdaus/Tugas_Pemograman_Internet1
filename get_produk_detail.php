<?php
include 'config.php';
header('Content-Type: application/json');

// Pastikan ID produk ada di URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo json_encode(['status' => 'error', 'message' => 'ID Produk tidak disediakan atau tidak valid.']);
    exit;
}

$id_produk = $_GET['id'];

$stmt = $koneksi->prepare("SELECT id, nama_produk, deskripsi, harga, gambar FROM products WHERE id = ?");
$stmt->bind_param("i", $id_produk);
$stmt->execute();
$result = $stmt->get_result();

if ($product = $result->fetch_assoc()) {
    // Jika produk ditemukan, kirim datanya
    $product['harga'] = (float)$product['harga'];
    echo json_encode(['status' => 'success', 'data' => $product]);
} else {
    // Jika tidak ada produk dengan ID tersebut
    echo json_encode(['status' => 'error', 'message' => 'Produk tidak ditemukan.']);
}

$stmt->close();
$koneksi->close();
?> 