<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

include "koneksi.php";

// Ambil data JSON dari JavaScript
$json = file_get_contents("php://input");

// Ubah JSON jadi array PHP
$data = json_decode($json, true);

// Cek apakah data dikirim lengkap
if (isset($data['nama_barang']) && isset($data['harga'])) {

    $nama_barang = $data['nama_barang'];
    $harga = $data['harga'];

    // Simpan ke database
    $query = "INSERT INTO barang (nama_barang, harga)
              VALUES ('$nama_barang', '$harga')";

    if (mysqli_query($koneksi, $query)) {

        echo json_encode([
            "status" => "success",
            "pesan" => "Data berhasil ditambahkan"
        ]);

    } else {

        echo json_encode([
            "status" => "error",
            "pesan" => "Gagal menambahkan data"
        ]);
    }

} else {

    echo json_encode([
        "status" => "error",
        "pesan" => "Data tidak lengkap"
    ]);
}

mysqli_close($koneksi);
?>