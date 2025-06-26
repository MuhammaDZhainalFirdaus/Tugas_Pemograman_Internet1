<?php
include 'config.php';
header('Content-Type: application/json');

// Validasi request method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Metode request tidak valid.']);
    exit;
}

// Validasi data POST
if (!isset($_POST['id_produk']) || !isset($_POST['nama_produk']) || !isset($_POST['harga']) || !isset($_POST['deskripsi'])) {
    echo json_encode(['status' => 'error', 'message' => 'Data tidak lengkap.']);
    exit;
}

$id_produk = $_POST['id_produk'];
$nama_produk = $_POST['nama_produk'];
$harga = $_POST['harga'];
$deskripsi = $_POST['deskripsi'];
$gambar_baru = isset($_FILES['foto_baru']) ? $_FILES['foto_baru'] : null;

// Dapatkan nama gambar lama dari DB
$stmt = $koneksi->prepare("SELECT gambar FROM products WHERE id = ?");
$stmt->bind_param("i", $id_produk);
$stmt->execute();
$result = $stmt->get_result();
$produk = $result->fetch_assoc();
$gambar_lama = $produk['gambar'];
$stmt->close();

$nama_gambar_untuk_db = $gambar_lama;

// Jika ada gambar baru yang diupload
if ($gambar_baru && $gambar_baru['error'] === UPLOAD_ERR_OK) {
    $upload_dir = 'uploads/';
    // Hapus gambar lama jika ada
    if ($gambar_lama && file_exists($upload_dir . $gambar_lama)) {
        unlink($upload_dir . $gambar_lama);
    }
    
    // Proses upload gambar baru
    $file_extension = pathinfo($gambar_baru['name'], PATHINFO_EXTENSION);
    $nama_file_unik = uniqid('produk_', true) . '.' . $file_extension;
    $target_file = $upload_dir . $nama_file_unik;
    
    if (move_uploaded_file($gambar_baru['tmp_name'], $target_file)) {
        $nama_gambar_untuk_db = $nama_file_unik;
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal mengupload gambar baru.']);
        exit;
    }
}

// Update data di database
$stmt_update = $koneksi->prepare("UPDATE products SET nama_produk = ?, deskripsi = ?, harga = ?, gambar = ? WHERE id = ?");
$stmt_update->bind_param("ssdsi", $nama_produk, $deskripsi, $harga, $nama_gambar_untuk_db, $id_produk);

if ($stmt_update->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Produk berhasil diperbarui.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Gagal memperbarui produk di database.']);
}

$stmt_update->close();
$koneksi->close();
?> 