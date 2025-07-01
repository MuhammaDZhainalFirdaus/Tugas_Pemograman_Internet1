<?php
include 'config.php';
header('Content-Type: application/json');

$orders = [];

$query = "
    SELECT o.id_order, o.tanggal_order, o.total_harga, o.status, o.jumlah,
           u.username, u.nama AS nama_user, p.nama_produk
    FROM orders o
    JOIN users u ON o.id_user = u.id
    JOIN products p ON o.id_produk = p.id
    ORDER BY o.tanggal_order DESC
";

$result = $koneksi->query($query);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }
    $result->free();
    echo json_encode($orders);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Gagal mengambil data pesanan: ' . $koneksi->error]);
}

$koneksi->close();
?> 