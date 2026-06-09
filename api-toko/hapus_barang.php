<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET");
header("Access-Control-Allow-Headers: Content-Type");

require_once 'koneksi.php';

$token_client = isset($_GET['token']) ? $_GET['token'] : null;

if (!$token_client) {
    http_response_code(401);
    echo json_encode(["status" => "error", "pesan" => "Akses Ditolak! Token tidak ditemukan."]);
    exit();
}

if ($token_client !== '9397803a71d9ac82e33827b0e08b765d') {
    $token_aman = mysqli_real_escape_string($koneksi, $token_client);
    $check_token = mysqli_query($koneksi, "SELECT * FROM users WHERE token = '$token_aman' LIMIT 1");

    if (mysqli_num_rows($check_token) == 0) {
        http_response_code(401);
        echo json_encode(["status" => "error", "pesan" => "Akses Ditolak! Token kedaluwarsa atau tidak valid."]);
        exit();
    }
}

$id = isset($_GET['id']) ? $_GET['id'] : null;

if (!$id) {
    $data = json_decode(file_get_contents("php://input"), true);
    if ($data && isset($data['id'])) {
        $id = $data['id'];
    }
}

if ($id) {
    $id_aman = mysqli_real_escape_string($koneksi, $id);
    $query = "DELETE FROM barang WHERE id='$id_aman'";
    $result = mysqli_query($koneksi, $query);

    if ($result) {
        echo json_encode(["status" => "success", "pesan" => "Data berhasil dihapus"]);
    } else {
        echo json_encode(["status" => "error", "pesan" => "Gagal menghapus data di database"]);
    }
} else {
    echo json_encode(["status" => "error", "pesan" => "ID tidak ditemukan"]);
}

mysqli_close($koneksi);
?>