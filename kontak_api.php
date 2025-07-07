<?php
header('Content-Type: application/json');
$kontak_file = 'kontak.json';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    echo file_get_contents($kontak_file);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    if (!$data) {
        echo json_encode(['status' => 'error', 'message' => 'Data tidak valid']);
        exit;
    }
    file_put_contents($kontak_file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    echo json_encode(['status' => 'success', 'message' => 'Informasi kontak berhasil diupdate']);
    exit;
}
echo json_encode(['status' => 'error', 'message' => 'Metode tidak diizinkan']); 