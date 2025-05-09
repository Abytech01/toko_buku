<?php
include_once "header.php";
include 'koneksi.php';
$query = "SELECT * FROM buku";
$result = $conn->query($query);

// Cek koneksi
if (mysqli_connect_errno()) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>TokuBuku - Beranda</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    .card-img-top {
      height: 300px;
      object-fit: cover;
    }
    .modal-img {
      max-height: 400px;
      object-fit: contain;
    }
    .badge-category {
      position: absolute;
      top: 10px;
      right: 10px;
      font-size: 0.8rem;
    }
  </style>
</head>
<body>

<!-- Hero Section -->
<div class="bg-light text-center p-5">
  <h1 class="display-5">Selamat Datang di TokuBuku</h1>
  <p class="lead">Marketplace Buku Online Sederhana</p>
</div>

<!-- Daftar Buku -->
<div class="container my-5">

  <!-- Bagian atas: judul dan tombol tambah -->
<div class="d-flex justify-content-between align-items-center mb-4">
  <h2 class="mb-0">Katalog Buku</h2>

  <?php
  $kategori_result = $conn->query("SELECT * FROM kategori");
  ?>
  <div class="d-flex gap-2">
    <a href="tambah_buku.php" class="btn btn-success">
      <i class="fas fa-plus me-1"></i> Tambah Produk Langsung
    </a>
    <div class="btn-group">
      </button>
      <ul class="dropdown-menu">
        <?php while ($kategori = $kategori_result->fetch_assoc()): ?>
          <li>
            <a class="dropdown-item" href="tambah_buku.php?kategori_id=<?= $kategori['id'] ?>">
              <?= htmlspecialchars($kategori['nama_kategori']) ?>
            </a>
          </li>
        <?php endwhile; ?>
      </ul>
    </div>
  </div>
</div>


  <div class="row">
    <?php
    $sql = "SELECT buku.*, kategori.nama_kategori 
            FROM buku 
            LEFT JOIN kategori ON buku.kategori_id = kategori.id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()):
    ?>
    <div class="col-md-4 mb-4">
      <div class="card h-100 shadow-sm">
        <div class="position-relative">
          <img src="assets/img/<?= htmlspecialchars($row['gambar']) ?>" class="card-img-top" alt="<?= htmlspecialchars($row['judul']) ?>">
          <span class="badge bg-primary badge-category"><?= htmlspecialchars($row['nama_kategori']) ?></span>
        </div>
        <div class="card-body d-flex flex-column">
          <h5 class="card-title"><?= htmlspecialchars($row['judul']) ?></h5>
          <p class="text-muted">Rp<?= number_format($row['harga'], 0, ',', '.') ?></p>
          <p class="card-text text-truncate"><?= htmlspecialchars(substr($row['deskripsi'], 0, 100)) ?>...</p>
          <div class="mt-auto">
            <button type="button" class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#detailModal<?= $row['id'] ?>">
              <i class="fas fa-eye me-1"></i> Lihat Detail
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal Detail Buku -->
    <div class="modal fade" id="detailModal<?= $row['id'] ?>" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title"><?= htmlspecialchars($row['judul']) ?></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-md-5">
                <img src="assets/img/<?= htmlspecialchars($row['gambar']) ?>" class="img-fluid rounded mb-3" alt="<?= htmlspecialchars($row['judul']) ?>">

                <!-- Tombol Tambah ke Keranjang -->
                <form action="aksi_keranjang.php" method="post" class="d-grid">
                  <input type="hidden" name="action" value="add">
                  <input type="hidden" name="id" value="<?= $row['id'] ?>">
                  <button type="submit" class="btn btn-success btn-lg">
                    <i class="fas fa-cart-plus me-2"></i> Tambahkan ke Keranjang
                  </button>
                </form>
              </div>
              <div class="col-md-7">
                <table class="table table-bordered">
                  <tr><th width="30%">Penulis</th><td><?= htmlspecialchars($row['penulis']) ?></td></tr>
                  <tr><th>Penerbit</th><td><?= htmlspecialchars($row['penerbit']) ?></td></tr>
                  <tr><th>Tahun Terbit</th><td><?= htmlspecialchars($row['tahun_terbit']) ?></td></tr>
                  <tr><th>Kategori</th><td><?= htmlspecialchars($row['nama_kategori']) ?></td></tr>
                  <tr><th>Stok</th><td><?= htmlspecialchars($row['stok']) ?> buku tersedia</td></tr>
                  <tr class="table-primary fw-bold">
                    <th>Harga</th><td>Rp<?= number_format($row['harga'], 0, ',', '.') ?></td>
                  </tr>
                </table>
              </div>
            </div>
            <div class="mt-3">
              <h5>Deskripsi Buku:</h5>
              <p class="text-justify"><?= nl2br(htmlspecialchars($row['deskripsi'])) ?></p>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
          </div>
        </div>
      </div>
    </div>
    <?php 
      endwhile;
    } else {
      echo '<div class="col-12"><div class="alert alert-info">Tidak ada buku tersedia.</div></div>';
    }
    ?>
  </div>
</div>

<!-- Bootstrap JS Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<?php
include_once "footer.php";
$conn->close();
?>
</body>
</html>
