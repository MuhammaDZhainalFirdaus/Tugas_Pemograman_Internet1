<?php
include 'config.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Metode tidak diizinkan']);
    exit;
}

$order_id = isset($_POST['order_id']) ? intval($_POST['order_id']) : 0;
$bank_name = isset($_POST['bank_name']) ? trim($_POST['bank_name']) : '';
$amount = isset($_POST['amount']) ? floatval($_POST['amount']) : 0;
$sender_name = isset($_POST['sender_name']) ? trim($_POST['sender_name']) : '';

if (!$order_id || !$bank_name || !$amount || !$sender_name || !isset($_FILES['bukti'])) {
    echo json_encode(['status' => 'error', 'message' => 'Data tidak lengkap']);
    exit;
}

// Upload file
$target_dir = 'uploads/';
if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
$ext = pathinfo($_FILES['bukti']['name'], PATHINFO_EXTENSION);
$filename = 'bukti_' . time() . '_' . rand(1000,9999) . '.' . $ext;
$target_file = $target_dir . $filename;
if (!move_uploaded_file($_FILES['bukti']['tmp_name'], $target_file)) {
    echo json_encode(['status' => 'error', 'message' => 'Gagal upload bukti pembayaran']);
    exit;
}

// Simpan ke database (tabel orders, update kolom pembayaran)
$stmt = $koneksi->prepare("UPDATE orders SET bank_name=?, jumlah_transfer=?, nama_pengirim=?, bukti_pembayaran=?, status='proses' WHERE id_order=?");
$stmt->bind_param('sdssi', $bank_name, $amount, $sender_name, $filename, $order_id);
if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Pembayaran berhasil dikirim']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan pembayaran: ' . $koneksi->error]);
}
$stmt->close();
$koneksi->close(); 