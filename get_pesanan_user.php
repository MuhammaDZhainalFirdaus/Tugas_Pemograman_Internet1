<?php
session_start();
include 'config.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode([]);
    exit;
}

$id_user = $_SESSION['user_id'];
$query = "SELECT o.id_order, p.nama_produk, o.total_harga, o.status FROM orders o JOIN products p ON o.id_produk = p.id WHERE o.id_user = ? AND (o.status = 'pending' OR o.status = 'proses') ORDER BY o.id_order DESC";
$stmt = $koneksi->prepare($query);
$stmt->bind_param('i', $id_user);
$stmt->execute();
$result = $stmt->get_result();

$pesanan = [];
while ($row = $result->fetch_assoc()) {
    $pesanan[] = $row;
}
echo json_encode($pesanan);
$stmt->close();
$koneksi->close();
?> 