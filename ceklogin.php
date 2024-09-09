<?php
require 'function.php';

//Cek apakah sudah melakukan log in
if(isset($_SESSION['loginsuccess'])) {

} else {
//Jika belum, tidak akan ke halaman index.php
    header('location:login.php');
}
?>