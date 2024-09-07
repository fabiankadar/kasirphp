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

?>