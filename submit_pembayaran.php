<?php
include 'config.php';
session_start();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Metode tidak valid']);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
$id_user = $_SESSION['user_id'] ?? 0;
$id_produk = $data['id_produk'] ?? 0;
$rating = $data['rating'] ?? 0;
$komentar = trim($data['komentar'] ?? '');

if (!$id_user || !$id_produk || !$rating || !$komentar) {
    echo json_encode(['status' => 'error', 'message' => 'Data tidak lengkap']);
    exit;
}

$stmt = $koneksi->prepare("INSERT INTO reviews (id_user, id_produk, rating, komentar) VALUES (?, ?, ?, ?)");
$stmt->bind_param("iiis", $id_user, $id_produk, $rating, $komentar);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Review berhasil dikirim, menunggu persetujuan admin.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan review.']);
}
$stmt->close();
$koneksi->close();
?> 