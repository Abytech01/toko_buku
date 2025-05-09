<?php
session_start();
include_once "koneksi.php";
include_once "header.php"; // pastikan file ini memuat <html>, <head>, navbar

// Cek apakah keranjang kosong
if (!isset($_SESSION['keranjang']) || empty($_SESSION['keranjang'])) {
    echo '<div class="container mt-5">
            <div class="alert alert-warning text-center">
                <h4>Keranjang belanja Anda kosong. Silakan belanja terlebih dahulu.</h4>
                <a href="index.php?page=home" class="btn btn-primary mt-3">Mulai Belanja</a>
            </div>
          </div>';
    include_once "footer.php";
    exit();
}

// Ambil data buku dari keranjang
$ids = implode(",", array_keys($_SESSION['keranjang']));
$sql = "SELECT b.*, k.nama_kategori 
        FROM buku b 
        LEFT JOIN kategori k ON b.kategori_id = k.id 
        WHERE b.id IN ($ids)";
$result = $conn->query($sql);
$total = 0;
?>

<div class="container py-5">
    <h2 class="mb-4">Checkout</h2>

    <div class="table-responsive mb-4">
        <table class="table table-bordered">
            <thead class="table-primary">
                <tr>
                    <th>Judul Buku</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <?php 
                        $jumlah = $_SESSION['keranjang'][$row['id']];
                        $subtotal = $row['harga'] * $jumlah;
                        $total += $subtotal;
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($row['judul']) ?></td>
                        <td>Rp<?= number_format($row['harga'], 0, ',', '.') ?></td>
                        <td><?= $jumlah ?></td>
                        <td>Rp<?= number_format($subtotal, 0, ',', '.') ?></td>
                    </tr>
                <?php endwhile; ?>
                <tr>
                    <td colspan="3" class="text-end"><strong>Total Belanja</strong></td>
                    <td><strong>Rp<?= number_format($total, 0, ',', '.') ?></strong></td>
                </tr>
            </tbody>
        </table>
    </div>

    <h4>Informasi Pembayaran</h4>
    <form id="checkoutForm" method="post" action="proses_checkout.php">
        <div class="mb-3">
            <label for="nama" class="form-label">Nama Lengkap</label>
            <input type="text" class="form-control" id="nama" name="nama" required>
        </div>
        <div class="mb-3">
            <label for="alamat" class="form-label">Alamat Pengiriman</label>
            <textarea class="form-control" id="alamat" name="alamat" required></textarea>
        </div>
        <div class="mb-3">
            <label for="telepon" class="form-label">Nomor Telepon</label>
            <input type="text" class="form-control" id="telepon" name="telepon" required>
        </div>

        <h5 class="mt-4">Pilih Metode Pembayaran</h5>
        <div class="mb-3">
            <select class="form-select" name="metode_pembayaran" required>
                <option value="" disabled selected>Pilih Metode Pembayaran</option>
                <option value="Dana">Dana</option>
                <option value="QRIS">QRIS</option>
                <option value="BCA">BCA</option>
                <option value="Mandiri">Mandiri</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Buat Pesanan</button>
    </form>

    <div id="resi" class="mt-4 d-none">
        <h4>Resi Pembayaran</h4>
        <!-- Resi akan ditampilkan di sini setelah proses checkout -->
    </div>
</div>

<?php include_once "footer.php"; ?>
