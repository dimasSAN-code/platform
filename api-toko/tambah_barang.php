<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET");
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

    if (mysqli_num_rows($check_token) == 0) {
        http_response_code(401);
        echo json_encode(["status" => "error", "pesan" => "Akses Ditolak! Token kedaluwarsa atau tidak valid."]);
        exit();
    }
}

$nama_barang = "";
$harga = "";

if (isset($_GET['nama_barang']) && isset($_GET['harga'])) {
    $nama_barang = mysqli_real_escape_string($koneksi, $_GET['nama_barang']);
    $harga = mysqli_real_escape_string($koneksi, $_GET['harga']);
} else {
    $json = file_get_contents("php://input");
    $data = json_decode($json, true);
    if (isset($data['nama_barang']) && isset($data['harga'])) {
        $nama_barang = mysqli_real_escape_string($koneksi, $data['nama_barang']);
        $harga = mysqli_real_escape_string($koneksi, $data['harga']);
    }
}

if (!empty($nama_barang) && !empty($harga)) {
    $query = "INSERT INTO barang (nama_barang, harga) VALUES ('$nama_barang', '$harga')";

    if (mysqli_query($koneksi, $query)) {
        echo json_encode(["status" => "success", "pesan" => "Data berhasil ditambahkan"]);
    } else {
        echo json_encode(["status" => "error", "pesan" => "Gagal menambahkan data di database"]);
    }
} else {
    echo json_encode(["status" => "error", "pesan" => "Data tidak lengkap"]);
}

mysqli_close($koneksi);
?>