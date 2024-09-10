<?php
    require 'ceklogin.php';
    if(isset($_GET['idp'])) {
        $idp = $_GET['idp'];

        $ambilnamapelanggan = mysqli_query($koneksi,
        "SELECT * FROM pesanan p, pelanggan pl 
        WHERE p.idpelanggan=pl.idpelanggan
        AND p.idpesanan='$idp'");

        $anp = mysqli_fetch_array($ambilnamapelanggan);
        $namapelanggan = $anp['namapelanggan'];
    } else {
        header('location:index.php');
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Data Pesanan</title>
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
        <link href="css/styles.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    </head>
    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <!-- Navbar Brand-->
            <a class="navbar-brand ps-3" href="index.php">Tukang Donat</a>
            <!-- Sidebar Toggle-->
            <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>           
        </nav>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="nav">
                            <div class="sb-sidenav-menu-heading">Menu</div>
                            <a class="nav-link" href="index.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-utensils"></i></div>
                                Pesan
                            </a>
                            <a class="nav-link" href="stok.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-box"></i></div>
                                Stok Donat
                            </a>
                            <a class="nav-link" href="pelanggan.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-user-edit"></i></div>
                                Kelola Pelanggan
                            </a>
                            <a class="nav-link" href="logout.php">                                
                                Log Out
                            </a>
                        </div>                            
                    </div>
                    <div class="sb-sidenav-footer">
                        <div class="small">Logged in as:</div>
                        Start Bootstrap
                    </div>
                </nav>
            </div>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">Data Pesanan: <?=$idp;?></h1>
                        <h3 class="mt-4">Nama Pelanggan: <?=$namapelanggan;?></h3>
                         <!-- Tombol untuk membuka modal, kode modal ada di bawah -->
                         <button type="button" class="btn btn-info mb-3" data-bs-toggle="modal" data-bs-target="#myModal">
                            Pesan Donat
                        </button>
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                Detail Pesanan
                            </div>
                            <div class="card-body">
                                <table id="datatablesSimple">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Produk</th>
                                            <th>Harga Satuan</th>
                                            <th>Jumlah</th>
                                            <th>Sub-Total</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>                                    
                                    <tbody>
                                    <?php
                                        //Memasukkan semua data dari tabel produk ke variabel ambil
                                            $ambil = mysqli_query($koneksi,
                                            "SELECT * FROM detailpesanan p, produk pr 
                                            WHERE p.idproduk = pr.idproduk
                                            AND idpesanan='$idp'");
                                        $i=1;
                                        //Selama variabel produk memiliki nilai, tampilkan data ke website
                                        //Start of while
                                            while($pesanan = mysqli_fetch_array($ambil)) {
                                                $idpr = $pesanan['idproduk'];
                                                $iddp = $pesanan['iddetailpesanan'];
                                                $namaproduk = $pesanan['namaproduk'];
                                                $harga = $pesanan['harga'];
                                                $qty = $pesanan['qty'];
                                                $subtotal = $harga * $qty;
                                        ?>
                                        <tr>
                                            <td><?=$i++;?></td>
                                            <td><?=$namaproduk;?></td>
                                            <td>Rp. <?=number_format($harga);?></td>
                                            <td><?=number_format($qty);?></td>
                                            <td>Rp. <?=number_format($subtotal);?></td>
                                            <td>Edit
                                            <button type="button" class="btn btn-danger mb-3" data-bs-toggle="modal" data-bs-target="#delete<?=$idpr;?>">
                                            Hapus
                                            </button>
                                            </td>
                                        </tr>

                                                    <!-- Modal untuk menghapus pilihan donat -->
                                        <div class="modal fade" id="delete<?=$idpr;?>">
                                        <div class="modal-dialog">
                                            <div class="modal-content">

                                            <!-- Modal Header -->
                                            <div class="modal-header">
                                                <h4 class="modal-title">Konfirmasi Penghapusan</h4>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>

                                            <form method="post">

                                            <!-- Modal body -->
                                            <div class="modal-body">
                                                Konfirmasi ulang penghapusan
                                                <input type="hidden" name="idp" value="<?=$iddp;?>">
                                                <input type="hidden" name="idpr" value="<?=$idpr;?>">
                                                <input type="hidden" name="idpesanan" value="<?=$idp;?>">
                                                <!-- idp apa? Lihat bagian paling atas -->
                                            </div>

                                            <!-- Modal footer -->
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-success" name="hapuspilihan">Hapus</button>
                                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batalkan</button>
                                            </div>

                                            </form>

                                            </div>    
                                        </div>
                                        </div>

                                        <?php
                                            } //End of while
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </main>
                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid px-4">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">Copyright &copy; Your Website 2023</div>
                            <div>
                                <a href="#">Privacy Policy</a>
                                &middot;
                                <a href="#">Terms &amp; Conditions</a>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="assets/demo/chart-area-demo.js"></script>
        <script src="assets/demo/chart-bar-demo.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
        <script src="js/datatables-simple-demo.js"></script>
    </body>

    <!-- The Modal -->
<div class="modal fade" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Pilih Donat</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <form method="post">

      <!-- Modal body -->
      <div class="modal-body">
        <select name="idproduk" class="form-control">
            <?php 
            $ambilproduk = mysqli_query($koneksi, 
                "SELECT * FROM produk
                WHERE idproduk
                NOT IN (SELECT idproduk FROM detailpesanan WHERE idpesanan='$idp')");

            while($produk = mysqli_fetch_array($ambilproduk)) {
                $idproduk = $produk['idproduk'];
                $namaproduk = $produk['namaproduk'];
                $harga = $produk['harga'];
                $stok = $produk['stok'];
            
            ?>

            <option value="<?=$idproduk;?>">
            <?=$namaproduk;?> - Rp.<?=$harga;?> - Stok:<?=$stok;?>
            </option>

            <?php
            }
            ?>
        </select>
        <input type="number" name="qty" class="form-control mt-2" placeholder="Jumlah" min="1" required>
        <input type="hidden" name="idp" value="<?=$idp;?>">
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="submit" class="btn btn-success" name="pilihdonat">Tambahkan</button>
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batalkan</button>
      </div>

      </form>

    </div>    
  </div>
</div>
</html>
