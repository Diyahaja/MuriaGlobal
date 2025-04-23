<?php
session_start();
//buat koneksi
$conn = mysqli_connect("localhost","root","","stockbarang");

// tambah barang baru
if(isset($_POST['addnewbarang'])){
    $namabarang = $_POST['namabarang'];
    $deskripsi = $_POST['deskripsi'];
    $stock = $_POST['stock'];

    //soal gambar
    $allowed_extension = array('png', 'jpg');
    $nama = $_FILES['file']['name']; //ngambil nama gambar
    $dot = explode('.',$nama);
    $ekstensi = strtolower(end($dot));//ngambil ekstensi
    $ukuran = $_FILES['file']['size'];//ngambil size filenya
    $file_tmp = $_FILES['file']['tmp_name'];//ngambil lokasi file

    //penamaan file = enkripsi
    $image = md5(uniqid($nama,true) . time()).'.'.$ekstensi; //menggabungkan nama file yg dienkripsi dgn ekstensi

    $cek = mysqli_query($conn, "select * from stock where namabarang='$namabarang'");
    $hitung = mysqli_num_rows($cek);

    if($hitung<1){
    //jika belum ada

        //proses upload gambar
        if(in_array($ekstensi, $allowed_extension) === true){
            //validasi ukkuran file
            if($ukuran < 15000000){
                move_uploaded_file($file_tmp, 'images/'.$image);

                $addtotable = mysqli_query($conn, "INSERT into  stock (namabarang, deskripsi, stock, image) values ('$namabarang','$deskripsi','$stock', '$image')");
                if($addtotable){
                    header('location:index.php');
                } else {
                    echo 'Gagal';
                    header('location:index.php');
                }
            } else {
                //kalau file lebih dari 15mb
                echo '
                <script>
                    alert("Ukuran terlalu besar");
                    window.location.href="index.php";
                </script>
                ';
            }
        } else {
            //kalau file tidak png/jpg
            echo '
            <script>
                alert("File harus berekstensi PNG/JPG");
                window.location.href="index.php";
            </script>
            ';
        }

    } else {
        //jika sudah ada
        echo '
        <script>
            alert("Nama barang sudah terdaftar");
            window.location.href="index.php";
        </script>
        ';
    }
};
// menambah barang masuk
if(isset($_POST['barangmasuk'])){
    $barangnya = $_POST['barangnya'];
    $penerima = $_POST['penerima'];
    $qty = $_POST['qty'];
    
    $cekstocksekarang = mysqli_query($conn, "SELECT * FROM stock WHERE idbarang='$barangnya'");
    $ambildatanya = mysqli_fetch_array($cekstocksekarang);

    $stocksekarang = $ambildatanya['stock'];
    $tambahstocksekarangdenganquantity = $stocksekarang+$qty;
    
    $addtomasuk = mysqli_query($conn, "INSERT into masuk (idbarang, keterangan, qty) values('$barangnya','$penerima', '$qty')");
    $updatestockmasuk = mysqli_query($conn, "UPDATE stock set stock='$tambahstocksekarangdenganquantity' where idbarang='$barangnya'");
    if($addtomasuk&&$updatestockmasuk){
        header('location:masuk.php');
    } else {
        echo 'Gagal menambahkan';
        header('location:masuk.php');
    }
}
//menambah barang keluar
if(isset($_POST['addbarangkeluar'])){
    $barangnya = $_POST['barangnya'];
    $penerima = $_POST['penerima'];
    $qty = $_POST['qty'];
    
    $cekstocksekarang = mysqli_query($conn, "SELECT * FROM stock WHERE idbarang='$barangnya'");
    $ambildatanya = mysqli_fetch_array($cekstocksekarang);

    $stocksekarang = $ambildatanya['stock'];

    if($stocksekarang >= $qty){
        //kalau barang cukup
        $tambahstocksekarangdenganquantity = $stocksekarang-$qty;
        
        $addtokeluar = mysqli_query($conn, "INSERT into keluar (idbarang, penerima, qty) values('$barangnya','$penerima', '$qty')");
        $updatestockmasuk = mysqli_query($conn, "UPDATE stock set stock='$tambahstocksekarangdenganquantity' where idbarang='$barangnya'");
        if($addtokeluar&&$updatestockmasuk){
            header('location:keluar.php');
        } else {
            echo 'Gagal menambahkan';
            header('location:keluar.php');
        }
    } else {
        //kalau barang tidak cukup
        echo '
        <script>
            alert("Stock saat ini tidak mencukupi");
            window.location.href="keluar.php";
        </script>
        ';
    }
}

//update info barang
if(isset($_POST['updatebarang'])){
    $idb = $_POST['idb'];
    $namabarang = $_POST['namabarang'];
    $deskripsi = $_POST['deskripsi'];

    //soal gambar
    $allowed_extension = array('png', 'jpg');
    $nama = $_FILES['file']['name']; //ngambil nama gambar
    $dot = explode('.',$nama);
    $ekstensi = strtolower(end($dot));//ngambil ekstensi
    $ukuran = $_FILES['file']['size'];//ngambil size filenya
    $file_tmp = $_FILES['file']['tmp_name'];//ngambil lokasi file

    //penamaan file = enkripsi
    $image = md5(uniqid($nama,true) . time()).'.'.$ekstensi; //menggabungkan nama file yg dienkripsi dgn ekstensi

    if($ukuran==0){
        //jika tidak ingin upload
        $update = mysqli_query($conn,"UPDATE stock set namabarang='$namabarang', deskripsi='$deskripsi' where idbarang='$idb' " );
        if($update){
            header('location:index.php');
        } else {
            echo 'Gagal menambahkan';
            header('location:index.php');
        }
    } else {
        //jika ingin
        move_uploaded_file($file_tmp, 'images/'.$image);
        $update = mysqli_query($conn,"UPDATE stock set namabarang='$namabarang', deskripsi='$deskripsi', image='$image' where idbarang='$idb'");
        if($update){
            header('location:index.php');
        } else {
            echo 'Gagal';
            header('location:index.php');
        }
    }
}

//hapus barang dari stock
if(isset($_POST['hapusbarang'])){
    $idb = $_POST['idb'];

    $gambar = mysqli_query($conn,"SELECT *FROM stock where idbarang='$idb'");
    $get = mysqli_fetch_array($gambar);
    $img = 'images/'.$get['image'];
    unlink($img);

    $hapus = mysqli_query($conn,"DELETE from stock where idbarang='$idb'" );
    if($hapus){
        header('location:index.php');
    } else {
        echo 'Gagal menambahkan';
        header('location:index.php');
    }
};

//mengubah data barang masuk 
if(isset($_POST['updatebarangmasuk'])){
    $idb = $_POST['idb'];
    $idm = $_POST['idm'];
    $deskripsi = $_POST['keterangan'];
    $qty = $_POST['qty'];

    $lihatstock = mysqli_query($conn, "SELECT * FROM stock where idbarang='$idb'");
    $stocknya = mysqli_fetch_array($lihatstock);
    $stockskrg = $stocknya['stock'];

    $qtyskrg = mysqli_query($conn, "SELECT * FROM masuk where idmasuk='$idm'");
    $qtynya = mysqli_fetch_array($qtyskrg);
    $qtyskrg = $qtynya['qty'];

    if($qty>$qtyskrg){
        $selisih = $qty-$qtyskrg;
        $kurangin = $stockskrg+$selisih;
        $kuranginstocknya = mysqli_query($conn, "UPDATE stock set stock='$kurangin' where idbarang='$idb' " );
        $updatenya = mysqli_query($conn, "UPDATE masuk set qty='$qty', keterangan='$deskripsi' where idmasuk='$idm'");
            if($kuranginstocknya&&$updatenya){
                header('location:masuk.php');
            } else {
                echo 'Gagal menambahkan';
                header('location:masuk.php');
            }
    } else {
        $selisih = $qtyskrg-$qty;
        $kurangin = $stockskrg-$selisih;
        $kuranginstocknya = mysqli_query($conn, "UPDATE stock set stock='$kurangin' where idbarang='$idb' " );
        $updatenya = mysqli_query($conn, "UPDATE masuk set qty='$qty', keterangan='$deskripsi' where idmasuk='$idm'");
            if($kuranginstocknya&&$updatenya){
                header('location:masuk.php');
            } else {
                echo 'Gagal menambahkan';
                header('location:masuk.php');
            }
    }
}

//menghapus barang masuk
if(isset($_POST['hapusbarangmasuk'])){
    $idb = $_POST['idb'];
    $qty = $_POST['kty'];
    $idm = $_POST['idm'];

    $getdatastock = mysqli_query($conn, "SELECT * from stock where idbarang='$idb'");
    $data = mysqli_fetch_array($getdatastock);
    $stok = $data['stock'];

    $selisih = $stok-$qty;

    $update = mysqli_query($conn, "UPDATE stock set stock='$selisih' where idbarang='$idb'");
    $hapusdata = mysqli_query($conn, "DELETE from masuk where idmasuk='$idm'");
    if($update&&$hapusdata){
        header('location:masuk.php');
            } else {
                echo 'Gagal menambahkan';
                header('location:masuk.php');
            }
    }

//mengubah data barang keluar 
if(isset($_POST['updatebarangkeluar'])){
    $idb = $_POST['idb'];
    $idm = $_POST['idk'];
    $penerima = $_POST['penerima'];
    $qty = $_POST['qty'];

    $lihatstock = mysqli_query($conn, "SELECT * FROM stock where idbarang='$idb'");
    $stocknya = mysqli_fetch_array($lihatstock);
    $stockskrg = $stocknya['stock'];

    $qtyskrg = mysqli_query($conn, "SELECT * FROM keluar where idkeluar='$idk'");
    $qtynya = mysqli_fetch_array($qtyskrg);
    $qtyskrg = $qtynya['qty'];

    if($qty>$qtyskrg){
        $selisih = $qty-$qtyskrg;
        $kurangin = $stockskrg-$selisih;
        $kuranginstocknya = mysqli_query($conn, "UPDATE stock set stock='$kurangin' where idbarang='$idb' " );
        $updatenya = mysqli_query($conn, "UPDATE keluar set qty='$qty', penerima='$penerima' where idkeluar='$idk'");
            if($kuranginstocknya&&$updatenya){
                header('location:keluar.php');
            } else {
                echo 'Gagal menambahkan';
                header('location:keluar.php');
            }
    } else {
        $selisih = $qtyskrg-$qty;
        $kurangin = $stockskrg+$selisih;
        $kuranginstocknya = mysqli_query($conn, "UPDATE stock set stock='$kurangin' where idbarang='$idb' " );
        $updatenya = mysqli_query($conn, "UPDATE keluar set qty='$qty', penerima='$penerima' where idkeluar='$idk'");
            if($kuranginstocknya&&$updatenya){
                header('location:keluar.php');
            } else {
                echo 'Gagal menambahkan';
                header('location:keluar.php');
            }
    }
}

//menghapus barang keluar
if(isset($_POST['hapusbarangkeluar'])){
    $idb = $_POST['idb'];
    $qty = $_POST['kty'];
    $idk = $_POST['idk'];

    $getdatastock = mysqli_query($conn, "SELECT * from stock where idbarang='$idb'");
    $data = mysqli_fetch_array($getdatastock);
    $stok = $data['stock'];

    $selisih = $stok+$qty;

    $update = mysqli_query($conn, "UPDATE stock set stock='$selisih' where idbarang='$idb'");
    $hapusdata = mysqli_query($conn, "DELETE from keluar where idkeluar='$idk'");
    if($update&&$hapusdata){
        header('location:keluar.php');
            } else {
                echo 'Gagal menambahkan';
                header('location:keluar.php');
            }
    }

//menambah admin baru
if(isset($_POST['addadmin'])){
    $email = $_POST['email'];
    $password = $_POST['password'];

    $queryinsert = mysqli_query($conn, "INSERT INTO login (email, password) values('$email','$password')");
    if($queryinsert){
        //berhasil
        header('location:admin.php');
    } else {
        //gagal
        header('location:admin.php');
    }
}

//edit data admin
if(isset($_POST['updateadmin'])){
    $emailbaru = $_POST['emailadmin'];
    $passwordbaru = $_POST['passwordbaru'];
    $idnya = $_POST['id'];

    $queryupdate = mysqli_query($conn,"UPDATE login set email='$emailbaru' , password='$passwordbaru' where iduser='$idnya' ");
    if($queryupdate){
        header('location:admin.php');
    } else {
        //gagal
        header('location:admin.php');
    }
}

//hapus admin
if(isset($_POST['hapusadmin'])){
    $id = $_POST['id'];

    $querydelete = mysqli_query($conn, "DELETE from login where iduser='$id'");

    if($querydelete){
        header('location:admin.php');
    } else {
        header('location:admin.php');
    }
}

?>