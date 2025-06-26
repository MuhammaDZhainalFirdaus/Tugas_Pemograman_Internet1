<?php
session_start();
include 'config.php';

header('Content-Type: application/json');

// Pastikan user sudah login
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Akses ditolak. Silakan login terlebih dahulu.']);
    exit;
}

// Pastikan metode request adalah POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Metode request tidak valid.']);
    exit;
}

// Mengambil data JSON yang dikirim
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['id_order']) || !is_numeric($data['id_order'])) {
    echo json_encode(['status' => 'error', 'message' => 'ID Pesanan tidak valid.']);
    exit;
}

$id_order = $data['id_order'];
$id_user = $_SESSION['user_id'];

// Keamanan: Pastikan pesanan ini milik user yang sedang login sebelum menghapus
$stmt = $koneksi->prepare("DELETE FROM orders WHERE id_order = ? AND id_user = ?");
$stmt->bind_param("ii", $id_order, $id_user);

if ($stmt->execute()) {
    // Memeriksa apakah ada baris yang benar-benar terhapus
    if ($stmt->affected_rows > 0) {
        echo json_encode(['status' => 'success', 'message' => 'Pesanan berhasil dihapus.']);
    } else {
        // Ini terjadi jika ID pesanan tidak ada atau bukan milik user ini
        echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus pesanan. Pesanan tidak ditemukan atau Anda tidak memiliki hak akses.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Terjadi kesalahan pada server.']);
}

$stmt->close();
$koneksi->close();
?> 