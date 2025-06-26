<?php
session_start();
include 'config.php';

// Idealnya ada pengecekan role admin di sini
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Metode request tidak valid.']);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
if (!isset($data['id']) || !is_numeric($data['id'])) {
    echo json_encode(['status' => 'error', 'message' => 'ID Produk tidak valid.']);
    exit;
}

$id_produk = $data['id'];

// 1. Dapatkan nama file gambar sebelum menghapus data dari DB
$stmt_select = $koneksi->prepare("SELECT gambar FROM products WHERE id = ?");
$stmt_select->bind_param("i", $id_produk);
$stmt_select->execute();
$result = $stmt_select->get_result();
if ($row = $result->fetch_assoc()) {
    $gambar_untuk_dihapus = 'uploads/' . $row['gambar'];
} else {
    // Produk tidak ditemukan
    echo json_encode(['status' => 'error', 'message' => 'Produk tidak ditemukan di database.']);
    $stmt_select->close();
    $koneksi->close();
    exit;
}
$stmt_select->close();


// 2. Hapus data produk dari database
$stmt_delete = $koneksi->prepare("DELETE FROM products WHERE id = ?");
$stmt_delete->bind_param("i", $id_produk);

if ($stmt_delete->execute()) {
    if ($stmt_delete->affected_rows > 0) {
        // 3. Jika data di DB berhasil dihapus, hapus file gambarnya
        if (isset($gambar_untuk_dihapus) && file_exists($gambar_untuk_dihapus)) {
            unlink($gambar_untuk_dihapus); // Hapus file dari server
        }
        echo json_encode(['status' => 'success', 'message' => 'Produk berhasil dihapus.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus produk, ID tidak ditemukan.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus produk dari database.']);
}

$stmt_delete->close();
$koneksi->close();
?> 