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
$nama = isset($_GET['nama_barang']) ? $_GET['nama_barang'] : null;
$harga = isset($_GET['harga']) ? $_GET['harga'] : null;

if (!$id || !$nama || !$harga) {
    $data = json_decode(file_get_contents("php://input"), true);
    if ($data) {
        $id = isset($data['id']) ? $data['id'] : $id;
        $nama = isset($data['nama_barang']) ? $data['nama_barang'] : $nama;
        $harga = isset($data['harga']) ? $data['harga'] : $harga;
    }
}

if ($id && $nama && $harga) {
    $id_aman = mysqli_real_escape_string($koneksi, $id);
    $nama_aman = mysqli_real_escape_string($koneksi, $nama);
    $harga_aman = mysqli_real_escape_string($koneksi, $harga);

    $query = "UPDATE barang SET nama_barang='$nama_aman', harga='$harga_aman' WHERE id='$id_aman'";
    $result = mysqli_query($koneksi, $query);

    if ($result) {
        echo json_encode(["status" => "success", "pesan" => "Data berhasil diupdate"]);
    } else {
        echo json_encode(["status" => "error", "pesan" => "Gagal update data di database"]);
    }
} else {
    echo json_encode(["status" => "error", "pesan" => "Data tidak lengkap"]);
}

mysqli_close($koneksi);
?>