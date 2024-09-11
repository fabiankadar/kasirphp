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

// Fungsi modal untuk halaman stok.php
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
    $qty = $_POST['qty'];//Jumlah donat yang ingin dipesan

    // Hitung stok sekarang ada berapa
    $hitung1 = mysqli_query($koneksi, "SELECT * FROM produk WHERE idproduk='$idproduk'");
    $hitung2 = mysqli_fetch_array($hitung1);
    $stoksekarang = $hitung2['stok'];

    if($stoksekarang>=$qty) {
        // Kurangi stok dengan jumlah yang dipilih
        $selisih = $stoksekarang - $qty;


        //Stok cukup
        $insert = mysqli_query($koneksi,
        "INSERT INTO detailpesanan (idpesanan,idproduk,qty)
        VALUES ('$idp', '$idproduk', '$qty')");

        $updatejumlah = mysqli_query($koneksi,
        "UPDATE produk SET stok='$selisih'
        WHERE idproduk='$idproduk'");

        if($insert && $update) {
            header('location:view.php?idp='.$idp);
        } 
    } else {
        //Stok kurang
        echo '
            <script>
                alert("Stok barang tidak cukup");
                window.location.href="view.php?idp='.$idp.'";
            </script>
            ';
    }
}

//Fungsi modal untuk halaman view.php, hapus pilihan donat
if(isset($_POST['hapuspilihan'])) {
    $idp = $_POST['idp']; //Ini iddetailpesanan, bukan idpesanan lihat halaman view.php
    $idpr = $_POST['idpr'];
    $idpesanan = $_POST['idpesanan'];

    // Cek qty saat ini
    $cek1 = mysqli_query($koneksi, "SELECT * FROM detailpesanan WHERE iddetailpesanan='$idp'");
    $cek2 = mysqli_fetch_array($cek1);
    $qtysekarang = $cek2['qty'];

    //Cek stok saat ini
    $cek3 = mysqli_query($koneksi, "SELECT * FROM produk WHERE idproduk='$idpr'");
    $cek4 = mysqli_fetch_array($cek3);
    $stokdonat = $cek4['stok'];

    $hitungstok = $stokdonat + $qtysekarang;

    $updatestok = mysqli_query($koneksi, "UPDATE produk SET stok='$hitungstok' WHERE idproduk ='$idpr'");
    $hapuspilihan = mysqli_query($koneksi, "DELETE FROM detailpesanan WHERE idproduk='$idpr' and iddetailpesanan='$idp'");

    if($updatestok&&$hapuspilihan) {
        header('location:view.php?idp='.$idpesanan);
    } else {
        echo '
            <script>
                alert("Gagal menghapus pilihan donat");
                window.location.href="view.php?idp='.$idpesanan.'";
            </script>
            ';
    }

}

// Fungsi untuk modal editdonat di halaman stok.php
if(isset($_POST['editdonat'])) {
    $namaproduk = $_POST['namaproduk'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    $idpr = $_POST['idpr']; //idproduk

    $query = mysqli_query($koneksi, "UPDATE produk 
    SET namaproduk='$namaproduk', harga='$harga', stok='$stok'
    WHERE idproduk='$idpr'");

    if ($query) {
        header('location:stok.php');
    } else {
        echo '
        <script>
            alert("Gagal mengubah donat");
            window.location.href="stok.php";
        </script>
        ';
    }
    
}

// Fungsi untuk modal deletedonat di halaman stok.php
if (isset($_POST['deletedonat'])) {
    $idpr = $_POST['idpr'];

    $query = mysqli_query($koneksi, "DELETE FROM produk WHERE idproduk='$idpr'");

    if ($query) {
        header('location:stok.php');
    } else {
        echo '
        <script>
            alert("Gagal menghapus donat");
            window.location.href="stok.php";
        </script>
        ';
    }
}

// Fungsi untuk modal editpelanggan di halaman pelanggan.php
if(isset($_POST['editpelanggan'])) {
    $namapelanggan = $_POST['namapelanggan'];
    $notelp = $_POST['notelp'];
    $alamat = $_POST['alamat'];
    $idpelanggan = $_POST['idpelanggan']; //idproduk

    $query = mysqli_query($koneksi, "UPDATE pelanggan 
    SET namapelanggan='$namapelanggan', notelp='$notelp', alamat='$alamat'
    WHERE idpelanggan='$idpelanggan'");

    if ($query) {
        header('location:pelanggan.php');
    } else {
        echo '
        <script>
            alert("Gagal mengubah data pelanggan");
            window.location.href="pelanggan.php";
        </script>
        ';
    }
    
}

// Fungsi untuk modal deletepelanggan di halaman pelanggan.php
if (isset($_POST['deletepelanggan'])) {
    $idpelanggan = $_POST['idpelanggan'];

    $query = mysqli_query($koneksi, "DELETE FROM pelanggan WHERE idpelanggan='$idpelanggan'");

    if ($query) {
        header('location:pelanggan.php');
    } else {
        echo '
        <script>
            alert("Gagal menghapus pelanggan");
            window.location.href="pelanggan.php";
        </script>
        ';
    }
}

// Fungsi untuk modal deletepesanan di halaman index.php
if (isset($_POST['deletepesanan'])) {
    $idpesanan = $_POST['idpesanan'];

    $cekdetail = mysqli_query($koneksi, "SELECT * FROM detailpesanan dp WHERE idpesanan='$idpesanan'");

    while($cd=mysqli_fetch_array($cekdetail)) {
        // Balikkan stok donat
        $idproduk = $cd['idproduk'];
        $iddp = $cd['iddetailpesanan'];
        $qty = $cd['qty'];

        $cekstok1 = mysqli_query($koneksi, "SELECT * FROM produk WHERE idproduk='$idproduk'");
        $cekstok2 = mysqli_fetch_array($cekstok1);
        $stokdonat = $cekstok2['stok'];

        $stokbaru = $stokdonat + $qty;

        $updatestok = mysqli_query($koneksi, "UPDATE produk SET stok='$stokbaru' WHERE idproduk ='$idproduk'");

        // Hapus data detail pesanan
        $hapusdetailpesanan = mysqli_query($koneksi, "DELETE FROM detailpesanan WHERE iddetailpesanan='$iddp'");
    }
    // Redirect
    $query = mysqli_query($koneksi, "DELETE FROM pesanan WHERE idpesanan='$idpesanan'");

    if ($updatestok && $hapusdetailpesanan && $query) {
        header('location:index.php');
    } else {
        echo '
        <script>
            alert("Gagal menghapus pesanan");
            window.location.href="index.php";
        </script>
        ';
    }
}

//Fungsi modal untuk halaman view.php, ubah pilihan donat
if(isset($_POST['editpilihandonat'])) {
    $qty = $_POST['qty'];
    $idpr = $_POST['idpr'];
    $iddp = $_POST['iddp'];
    $idp = $_POST['idp'];

    // Cek qty saat ini
    $cek1 = mysqli_query($koneksi, "SELECT * FROM detailpesanan WHERE iddetailpesanan='$iddp'");
    $cek2 = mysqli_fetch_array($cek1);
    $qtysekarang = $cek2['qty'];

    //Cek stok saat ini
    $cek3 = mysqli_query($koneksi, "SELECT * FROM produk WHERE idproduk='$idpr'");
    $cek4 = mysqli_fetch_array($cek3);
    $stokdonatsekarang = $cek4['stok'];

    if($qty >= $qtysekarang){
        // Kalau inputan user lebih besar atau sama dengan qty yang sekarang
        // Hitung selisih
        $selisih = $qty - $qtysekarang;
        $stokbaru = $stokdonatsekarang - $selisih;

        $query1 = mysqli_query($koneksi, "UPDATE detailpesanan SET qty='$qty' WHERE iddetailpesanan='$iddp'");
        $query2 = mysqli_query($koneksi, "UPDATE produk set stok='$stokbaru' WHERE idproduk='$idpr'");

        if($query1 && $query2){
            header('location:view.php?idp='.$idp);
        } else {
            echo '
            <script>
                alert("Gagal memilih donat");
                window.location.href="view.php?idp='.$idp.'";
            </script>
            ';
        }

    } else {
        // Kalau inputan user lebih kecil
        // Hitung selisih
        $selisih = $qtysekarang - $qty ;
        $stokbaru = $stokdonatsekarang + $selisih;

        $query1 = mysqli_query($koneksi, "UPDATE detailpesanan SET qty='$qty' WHERE iddetailpesanan='$iddp'");
        $query2 = mysqli_query($koneksi, "UPDATE produk set stok='$stokbaru' WHERE idproduk='$idpr'");

        if($query1 && $query2){
            header('location:view.php?idp='.$idp);
        } else {
            echo '
            <script>
                alert("Gagal memilih donat");
                window.location.href="view.php?idp='.$idp.'";
            </script>
            ';
        }
    }
}
?>