<?php
session_start();
include 'config.php';

// Untuk keamanan, idealnya kita cek apakah pengguna adalah admin
// if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
//     echo json_encode(['status' => 'error', 'message' => 'Akses ditolak.']);
//     exit;
// }

header('Content-Type: application/json');

// Validasi data yang diterima dari form
if (!isset($_POST['nama']) || !isset($_POST['harga']) || !isset($_POST['deskripsi']) || !isset($_FILES['foto'])) {
    echo json_encode(['status' => 'error', 'message' => 'Data yang dikirim tidak lengkap.']);
    exit;
}

$nama = $_POST['nama'];
$harga = $_POST['harga'];
$deskripsi = $_POST['deskripsi'];
$gambar_file = $_FILES['foto'];
$gambar_nama_final = '';

// Proses upload gambar
if ($gambar_file && $gambar_file['error'] === UPLOAD_ERR_OK) {
    $upload_dir = 'uploads/';
    // Buat direktori 'uploads' jika belum ada
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    
    // Buat nama file yang unik untuk menghindari tumpang tindih
    $file_extension = pathinfo($gambar_file['name'], PATHINFO_EXTENSION);
    $nama_file_unik = uniqid('produk_', true) . '.' . $file_extension;
    $target_file = $upload_dir . $nama_file_unik;

    // Pindahkan file yang diupload ke direktori tujuan
    if (move_uploaded_file($gambar_file['tmp_name'], $target_file)) {
        $gambar_nama_final = $nama_file_unik;
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal memindahkan file gambar.']);
        exit;
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Terjadi kesalahan saat upload gambar.']);
    exit;
}

// Jika semua data valid dan gambar berhasil diupload, masukkan ke database
if (empty($nama) || empty($harga) || empty($deskripsi) || empty($gambar_nama_final)) {
    echo json_encode(['status' => 'error', 'message' => 'Semua field wajib diisi, termasuk gambar.']);
    exit;
}

$stmt = $koneksi->prepare("INSERT INTO products (nama_produk, deskripsi, harga, gambar) VALUES (?, ?, ?, ?)");
// 'ssds' = string, string, double, string
$stmt->bind_param("ssds", $nama, $deskripsi, $harga, $gambar_nama_final);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Produk berhasil ditambahkan!']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan data ke database.']);
}

$stmt->close();
$koneksi->close();
?> 