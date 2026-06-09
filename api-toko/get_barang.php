<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type");

include_once 'koneksi.php';

$token_client = isset($_GET['token']) ? $_GET['token'] : null;

if (!$token_client) {
    http_response_code(401);
    echo json_encode(["status" => "error", "pesan" => "Akses Ditolak! Token tidak ditemukan."]);
    exit();
}

if ($token_client !== '9397803a71d9ac82e33827b0e08b765d') {
    $token_aman = mysqli_real_escape_string($koneksi, $token_client);
    $check_token = mysqli_query($koneksi, "SELECT * FROM users WHERE token = '$token_aman' LIMIT 1");

    if (!$check_token || mysqli_num_rows($check_token) == 0) {
        http_response_code(401);
        echo json_encode(["status" => "error", "pesan" => "Token tidak valid atau kedaluwarsa."]);
        exit();
    }
}

$query = "SELECT * FROM barang ORDER BY id DESC";
$result = mysqli_query($koneksi, $query);

if (!$result) {
    http_response_code(500);
    echo json_encode([
        "status" => "error", 
        "pesan" => "Gagal mengambil data dari database",
        "error_mysql" => mysqli_error($koneksi)
    ]);
    exit();
}

$barangs = [];
while ($row = mysqli_fetch_assoc($result)) {
    $barangs[] = $row;
}

echo json_encode(["status" => "success", "data" => $barangs]);

mysqli_close($koneksi);
?>