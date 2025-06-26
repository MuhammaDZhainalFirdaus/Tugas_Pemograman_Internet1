<?php
session_start();
include 'config.php';

header('Content-Type: application/json');

// 1. Pastikan user sudah login
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Anda harus login untuk membuat pesanan.']);
    exit;
}

// 2. Terima ID produk dari request
$data = json_decode(file_get_contents("php://input"), true);
if (!isset($data['id_produk']) || !is_numeric($data['id_produk'])) {
    echo json_encode(['status' => 'error', 'message' => 'ID Produk tidak valid.']);
    exit;
}

$id_produk = $data['id_produk'];
$id_user = $_SESSION['user_id'];
$jumlah = 1; // Untuk saat ini, kita anggap jumlahnya selalu 1

// 3. Ambil harga produk dari database
$stmt_harga = $koneksi->prepare("SELECT harga FROM products WHERE id = ?");
$stmt_harga->bind_param("i", $id_produk);
$stmt_harga->execute();
$result_harga = $stmt_harga->get_result();
if ($produk = $result_harga->fetch_assoc()) {
    $total_harga = $produk['harga'] * $jumlah;
} else {
    echo json_encode(['status' => 'error', 'message' => 'Produk tidak ditemukan.']);
    $stmt_harga->close();
    $koneksi->close();
    exit;
}
$stmt_harga->close();

// 4. Simpan pesanan baru ke tabel 'orders'
$status = 'Proses'; // Status default saat pesanan dibuat
$stmt_order = $koneksi->prepare("INSERT INTO orders (id_user, id_produk, jumlah, total_harga, status) VALUES (?, ?, ?, ?, ?)");
$stmt_order->bind_param("iiids", $id_user, $id_produk, $jumlah, $total_harga, $status);

if ($stmt_order->execute()) {
    // 5. Kirim kembali ID pesanan yang baru dibuat
    $order_id = $stmt_order->insert_id;
    echo json_encode(['status' => 'success', 'message' => 'Pesanan berhasil dibuat!', 'order_id' => $order_id]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan pesanan.']);
}

$stmt_order->close();
$koneksi->close();
?> 