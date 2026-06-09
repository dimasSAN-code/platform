<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

error_reporting(0);
ini_set('display_errors', 0);

require_once 'koneksi.php';

if (!$koneksi) {
    echo json_encode(["status" => "error", "pesan" => "Gagal terhubung ke database server."]);
    exit();
}

$json = file_get_contents("php://input");
$data = json_decode($json, true);

$username_client = isset($data['username']) ? trim($data['username']) : '';
$password_client = isset($data['password']) ? trim($data['password']) : '';

if (empty($username_client) || empty($password_client)) {
    echo json_encode(["status" => "error", "pesan" => "Username atau Password tidak boleh kosong!"]);
    exit();
}

$username_aman = mysqli_real_escape_string($koneksi, $username_client);
$query = "SELECT * FROM users WHERE username = '$username_aman' LIMIT 1";
$result = mysqli_query($koneksi, $query);

if (!$result || mysqli_num_rows($result) === 0) {
    echo json_encode(["status" => "error", "pesan" => "Username tidak terdaftar di database!"]);
    exit();
}

$user = mysqli_fetch_assoc($result);

$password_cocok = false;
if ($password_client === $user['password']) {
    $password_cocok = true;
} elseif (password_verify($password_client, $user['password'])) {
    $password_cocok = true;
}

if (!$password_cocok) {
    echo json_encode(["status" => "error", "pesan" => "Password yang Anda masukkan salah!"]);
    exit();
}

$id_user = $user['id'];

$token_baru = md5(uniqid($username_client, true) . time());

$query_update = "UPDATE users SET token = '$token_baru' WHERE id = '$id_user'";
$update_token = mysqli_query($koneksi, $query_update);

if ($update_token) {
    echo json_encode([
        "status" => "success",
        "pesan" => "Login Berhasil!",
        "token" => $token_baru
    ]);
} else {
    echo json_encode(["status" => "error", "pesan" => "Gagal menyimpan session token ke database."]);
}

mysqli_close($koneksi);
?>