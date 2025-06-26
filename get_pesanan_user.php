<?php
session_start();
include 'config.php';

// Pastikan user sudah login
if (!isset($_SESSION['user_id'])) {
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'Akses ditolak. Silakan login terlebih dahulu.']);
    exit;
}

header('Content-Type: application/json');

$user_id = $_SESSION['user_id'];
$orders = [];

// Query untuk mengambil data pesanan beserta nama produk
$query = "
    SELECT 
        o.id_order,
        o.jumlah,
        o.total_harga,
        o.status,
        o.tanggal_order,
        p.nama_produk
    FROM orders AS o
    JOIN products AS p ON o.id_produk = p.id
    WHERE o.id_user = ?
    ORDER BY o.tanggal_order DESC
";

$stmt = $koneksi->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $orders[] = $row;
}

$stmt->close();
$koneksi->close();

echo json_encode($orders);
?> 