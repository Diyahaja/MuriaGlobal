<?php
require 'function.php';
require 'cek.php';

//dapatkan id barang yang dipassing di halaman sebelumnya
$idbarang = $_GET['id'];
//get info detail berdasarkan database
$get = mysqli_query($conn, "SELECT * from stock where idbarang='$idbarang'");
$fetch = mysqli_fetch_assoc($get);
//set variable
$namabarang = $fetch['namabarang'];
$deskripsi = $fetch['deskripsi'];
$stock = $fetch['stock'];

//cek ada gambar atau tidak
$gambar = $fetch['image']; //ambil gambar
if($gambar==null){
    //jika tidak ada gambar
    $img = 'No file choosen';
} else {
    //jika ada gambar
    $img = '<img class="card-img-top" src="../images/'.$gambar.'" alt="Card image" style="width:100%">';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <title>Menampilkan Barang</title>
</head>
<body>
    <div class="container">
        <h3>Detail Barang:</h3>
        <div class="card mt-4" style="width:400px">
            <?=$img;?>
            <div class="card-body">
            <h4 class="card-title"><?=$namabarang;?></h4>
            <p class="card-text"><?=$deskripsi;?></p>
            <p class="card-text"><?=$stock;?></p>
            </div>
        </div>
        <br>
    </div>
</body>
</html>