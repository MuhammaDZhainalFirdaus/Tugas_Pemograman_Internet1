<?php
session_start();
include 'config.php';

header('Content-Type: application/json');

// Periksa koneksi setelah include config
if (!$koneksi) {
    echo json_encode(['status' => 'error', 'message' => 'Koneksi database gagal: ' . mysqli_connect_error()]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['login_role'];

    // Validasi input dasar
    if (empty($username) || empty($password) || empty($role)) {
        echo json_encode(['status' => 'error', 'message' => 'Username, password, dan role harus diisi.']);
        exit;
    }

    // Menggunakan prepared statements untuk keamanan
    $stmt = $koneksi->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verifikasi password
        if (password_verify($password, $user['password'])) {
            // Verifikasi role
            if ($user['role'] == $role) {
                // Login berhasil
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                // Tentukan halaman redirect berdasarkan role
                $redirect_url = ($role == 'admin') ? 'dashboard.html' : 'dashboard-user.html';
                
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Login berhasil!',
                    'redirect' => $redirect_url
                ]);
            } else {
                // Role tidak sesuai
                echo json_encode(['status' => 'error', 'message' => 'Login gagal. Role tidak sesuai.']);
            }
        } else {
            // Password salah
            echo json_encode(['status' => 'error', 'message' => 'Login gagal. Password salah.']);
        }
    } else {
        // Username tidak ditemukan
        echo json_encode(['status' => 'error', 'message' => 'Login gagal. Username tidak ditemukan.']);
    }

    $stmt->close();
    $koneksi->close();
} else {
    // Metode request bukan POST
    echo json_encode(['status' => 'error', 'message' => 'Metode request tidak valid.']);
}
?>