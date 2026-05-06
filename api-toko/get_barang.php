<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

require_once 'koneksi.php';

$query = "SELECT * FROM barang";
$result = mysqli_query($koneksi, $query);

$response = array();

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $response[] = $row;
    }
    
    echo json_encode([
        'status' => 'success',
        'data' => $response
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => mysqli_error($koneksi)
    ]);
}

mysqli_close($koneksi);
?>
