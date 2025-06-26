<?php
include 'config.php';

header('Content-Type: application/json');

// Memeriksa koneksi ke database
if (!$koneksi) {
    echo json_encode(['status' => 'error', 'message' => 'Koneksi database gagal: ' . mysqli_connect_error()]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Mengambil data dari form
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = $_POST['role'];

    // Validasi dasar
    if (empty($nama) || empty($email) || empty($username) || empty($password) || empty($role)) {
        echo json_encode(['status' => 'error', 'message' => 'Semua field harus diisi.']);
        exit;
    }

    if ($password !== $confirm_password) {
        echo json_encode(['status' => 'error', 'message' => 'Password dan konfirmasi password tidak cocok.']);
        exit;
    }

    // Periksa apakah username sudah ada
    $stmt = $koneksi->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        echo json_encode(['status' => 'error', 'message' => 'Username sudah terdaftar. Silakan gunakan username lain.']);
        $stmt->close();
        exit;
    }
    $stmt->close();

    // Periksa apakah email sudah ada
    $stmt = $koneksi->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        echo json_encode(['status' => 'error', 'message' => 'Email sudah terdaftar. Silakan gunakan email lain.']);
        $stmt->close();
        exit;
    }
    $stmt->close();

    // Enkripsi password sebelum disimpan
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Menyimpan data ke database
    $stmt = $koneksi->prepare("INSERT INTO users (nama, email, username, password, role) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $nama, $email, $username, $hashed_password, $role);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Registrasi berhasil! Silakan pindah ke tab Login untuk masuk.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Terjadi kesalahan saat menyimpan data.']);
    }

    $stmt->close();
    $koneksi->close();
}
?> 