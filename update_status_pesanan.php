<?php
include 'config.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Metode tidak diizinkan']);
    exit;
}

$id_order = isset($_POST['id_order']) ? intval($_POST['id_order']) : 0;
$status = isset($_POST['status']) ? strtolower(trim($_POST['status'])) : '';

$allowed = ['selesai', 'proses', 'batal'];
if (!$id_order || !in_array($status, $allowed)) {
    echo json_encode(['status' => 'error', 'message' => 'Data tidak valid']);
    exit;
}

$stmt = $koneksi->prepare("UPDATE orders SET status=? WHERE id_order=?");
$stmt->bind_param('si', $status, $id_order);
if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Status pesanan berhasil diupdate']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Gagal update status: ' . $koneksi->error]);
}
$stmt->close();
$koneksi->close(); 