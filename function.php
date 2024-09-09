<?php
//Memulai Query
session_start();

// Membuat koneksi
$koneksi = mysqli_connect("localhost", "root", "", "kasirphp");

//Log In
if(isset($_POST['login'])){
    // Inisiasi variabel
    $username = $_POST['username'];
    $password = $_POST['password'];

    //Melihat apakah inputan username dan password ada di dalam database
    $check = mysqli_query($koneksi, "SELECT * FROM admin WHERE username='$username' and password='$password' ");
    //Menghitung berapa jumlah baris yang cocok dengan yang di input
    $hitung = mysqli_num_rows($check);

    if($hitung>0){
        //Jika jumlah baris lebih dari 0, berhasil log in
        $_SESSION['loginsuccess'] = 'True';
        header('location:index.php');
    } else {
        //Jumlah baris kurang dari 0, gagal log in
        echo '
        <script>
            alert("Username atau Password salah");
            window.location.href="login.php";
        </script>
        ';

    }
}

// Fungsi modal untuk halman stok.php
if(isset($_POST['tambahstok'])) {
    $namaproduk = $_POST['namaproduk'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];

    //Catatan penting: perhatikan penggunaan tanda kutip satu maupun dua
    //Saya error karena nama variabel tidak disertai tanda kutip
    $insert = mysqli_query($koneksi,
    "INSERT INTO produk (namaproduk,harga,stok) VALUES ('$namaproduk','$harga','$stok')"
    );

    if($insert) {
        header('location:stok.php');
    } else {
        echo '
        <script>
            alert("Gagal menambahkan stok");
            window.location.href="stok.php";
        </script>
        ';
    }
}

// Fungsi modal untuk halaman pelanggan.php
if(isset($_POST['tambahpelanggan'])) {
    $namapelanggan = $_POST['namapelanggan'];
    $notelp = $_POST['notelp'];
    $alamat = $_POST['alamat'];

    //Catatan penting: perhatikan penggunaan tanda kutip satu maupun dua
    //Saya error karena nama variabel tidak disertai tanda kutip
    $insert = mysqli_query($koneksi,
    "INSERT INTO pelanggan (namapelanggan,notelp,alamat) VALUES ('$namapelanggan','$notelp','$alamat')"
    );

    if($insert) {
        header('location:pelanggan.php');
    } else {
        echo '
        <script>
            alert("Gagal menambahkan pelanggan");
            window.location.href="pelanggan.php";
        </script>
        ';
    }
}

//Fungsi modal untuk halaman index.php
if(isset($_POST['tambahpesanan'])) {
    $idpelanggan = $_POST['idpelanggan'];

    //Catatan penting: perhatikan penggunaan tanda kutip satu maupun dua
    //Saya error karena nama variabel tidak disertai tanda kutip
    $insert = mysqli_query($koneksi,
    "INSERT INTO pesanan (idpelanggan) VALUES ('$idpelanggan')"
    );

    if($insert) {
        header('location:index.php');
    } else {
        echo '
        <script>
            alert("Gagal menambahkan pesanan");
            window.location.href="index.php";
        </script>
        ';
    }
}

//Fungsi modal untuk halaman view.php
if(isset($_POST['pilihdonat'])) {
    $idproduk = $_POST['idproduk'];
    $idp = $_POST['idp'];
    $qty = $_POST['qty'];

    //Catatan penting: perhatikan penggunaan tanda kutip satu maupun dua
    //Saya error karena nama variabel tidak disertai tanda kutip
    $insert = mysqli_query($koneksi,
    "INSERT INTO detailpesanan (idpesanan,idproduk,qty) VALUES ('$idp', '$idproduk', '$qty')"
    );

    if($insert) {
        header('location:view.php?idp='.$idp);
    } else {
        echo '
        <script>
            alert("Gagal menambahkan pesanan");
            window.location.href="view.php?idp="'.$idp.';
        </script>
        ';
    }
}
?>