<?php
include_once "header.php";
?>

<?php
session_start();
include 'koneksi.php';

// Jika keranjang kosong
if (!isset($_SESSION['keranjang']) || empty($_SESSION['keranjang'])) {
    echo '<div class="container mt-5">
            <div class="alert alert-info text-center">
                <i class="fas fa-shopping-cart fa-3x mb-3"></i>
                <h4>Keranjang belanja Anda kosong</h4>
                <a href="index.php?page=home" class="btn btn-primary mt-3">
                    <i class="fas fa-book me-2"></i>Mulai Belanja
                </a>
            </div>
          </div>';
    include 'footer.php';
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja - TokoBuku</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .quantity-input {
            width: 60px;
            text-align: center;
        }
        .book-cover {
            width: 80px;
            height: 120px;
            object-fit: cover;
            border-radius: 5px;
        }
        .total-row {
            font-weight: bold;
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>
   
    <div class="container py-5">
        <!-- Notifikasi -->
        <?php if (isset($_SESSION['pesan'])): ?>
            <div class="alert alert-info alert-dismissible fade show">
                <?= $_SESSION['pesan'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['pesan']); ?>
        <?php endif; ?>

        <h2 class="mb-4"><i class="fas fa-shopping-cart me-2"></i>Keranjang Belanja</h2>
        
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-primary">
                    <tr>
                        <th width="5%">#</th>
                        <th width="15%">Cover</th>
                        <th>Judul Buku</th>
                        <th width="12%">Harga</th>
                        <th width="15%">Jumlah</th>
                        <th width="15%">Subtotal</th>
                        <th width="10%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Ambil data buku dari database
                    $ids = implode(",", array_keys($_SESSION['keranjang']));
                    $sql = "SELECT b.*, k.nama_kategori 
                            FROM buku b 
                            LEFT JOIN kategori k ON b.kategori_id = k.id 
                            WHERE b.id IN ($ids)";
                    $result = $conn->query($sql);
                    $total = 0;
                    $no = 1;
                    ?>
                    
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <?php 
                        $jumlah = $_SESSION['keranjang'][$row['id']];
                        $subtotal = $row['harga'] * $jumlah;
                        $total += $subtotal;
                        ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td>
                                <img src="assets/img/<?= htmlspecialchars($row['gambar']) ?>" 
                                     class="book-cover" 
                                     alt="<?= htmlspecialchars($row['judul']) ?>">
                            </td>
                            <td>
                                <h6><?= htmlspecialchars($row['judul']) ?></h6>
                                <small class="text-muted"><?= htmlspecialchars($row['penulis']) ?></small><br>
                                <span class="badge bg-secondary"><?= htmlspecialchars($row['nama_kategori']) ?></span>
                            </td>
                            <td>Rp<?= number_format($row['harga'], 0, ',', '.') ?></td>
                            <td>
                                <form method="post" action="aksi_keranjang.php" class="d-flex">
                                    <input type="hidden" name="action" value="update">
                                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                    <input type="number" 
                                           name="jumlah" 
                                           value="<?= $jumlah ?>" 
                                           min="1" 
                                           max="<?= $row['stok'] ?>" 
                                           class="form-control quantity-input">
                                    <button type="submit" class="btn btn-sm btn-outline-primary ms-2">
                                        <i class="fas fa-sync"></i>
                                    </button>
                                </form>
                            </td>
                            <td>Rp<?= number_format($subtotal, 0, ',', '.') ?></td>
                            <td>
                                <a href="aksi_keranjang.php?action=remove&id=<?= $row['id'] ?>" 
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('Hapus buku dari keranjang?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                    
                    <tr class="total-row">
                        <td colspan="4" class="text-end">Total Belanja</td>
                        <td><?= array_sum($_SESSION['keranjang']) ?> item</td>
                        <td colspan="2">Rp<?= number_format($total, 0, ',', '.') ?></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-between mt-4">
    <a href="index.php?page=home" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i> Lanjut Belanja
    </a>
    <a href="checkout.php" class="btn btn-primary">
        <i class="fas fa-credit-card me-2"></i> Proses Checkout
    </a>
</div>

    </div>

    <?php include 'footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
include_once "footer.php";
?>