<?php
session_start();
include 'koneksi.php';

// Cek apakah keranjang ada dan tidak kosong
if (!isset($_SESSION['keranjang']) || empty($_SESSION['keranjang'])) {
    echo '<div class="container mt-5">
            <div class="alert alert-warning text-center">
                <h4>Keranjang belanja Anda kosong. Silakan belanja terlebih dahulu.</h4>
                <a href="index.php?page=home" class="btn btn-primary mt-3">Mulai Belanja</a>
            </div>
          </div>';
    exit();
}

// Ambil data dari formulir
$nama = $_POST['nama'];
$alamat = $_POST['alamat'];
$telepon = $_POST['telepon'];
$metode_pembayaran = $_POST['metode_pembayaran'];

// Generate nomor invoice unik dengan waktu real-time (tahun, bulan, hari, jam, menit, detik)
$no_invoice = 'INV-' . date('YmdHis');

// Ambil data buku dari keranjang
$ids = implode(',', array_keys($_SESSION['keranjang']));
$sql = "SELECT b.id, b.judul, b.harga 
        FROM buku b 
        WHERE b.id IN ($ids)";
$result = $conn->query($sql);
$total = 0;

// Menyimpan detail pesanan
$pesanan = [];
while ($row = $result->fetch_assoc()) {
    $jumlah = $_SESSION['keranjang'][$row['id']];
    $subtotal = $row['harga'] * $jumlah;
    $total += $subtotal;
    $pesanan[] = [
        'judul' => $row['judul'],
        'harga' => $row['harga'],
        'jumlah' => $jumlah,
        'subtotal' => $subtotal
    ];
}

// Simpan transaksi ke database (opsional)
$tanggal = date('Y-m-d H:i:s');
$sql_transaksi = "INSERT INTO transaksi (no_invoice, nama, alamat, telepon, metode_pembayaran, total, tanggal) 
                  VALUES ('$no_invoice', '$nama', '$alamat', '$telepon', '$metode_pembayaran', $total, '$tanggal')";
$conn->query($sql_transaksi);
$id_transaksi = $conn->insert_id;

// Simpan detail transaksi
foreach ($pesanan as $item) {
    $sql_detail = "INSERT INTO detail_transaksi (id_transaksi, judul_buku, harga, jumlah, subtotal) 
                   VALUES ($id_transaksi, '".$item['judul']."', ".$item['harga'].", ".$item['jumlah'].", ".$item['subtotal'].")";
    $conn->query($sql_detail);
}

// Kosongkan keranjang setelah transaksi
unset($_SESSION['keranjang']);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Resi Pembayaran - TokoBuku</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" />
    <style>
        body {
            background-color: #f8f9fa;
        }
        .resi-container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            padding: 30px;
            margin-top: 30px;
            margin-bottom: 30px;
        }
        .header-resi {
            border-bottom: 2px solid #0d6efd;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .info-pembeli {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 25px;
        }
        .total-row {
            background-color: #e9ecef;
            font-weight: bold;
        }
        @media print {
            body {
                background-color: white;
            }
            .no-print {
                display: none !important;
            }
            .resi-container {
                box-shadow: none;
                padding: 0;
            }
        }
    </style>
</head>
<body>
<div class="container py-4">
    <div class="resi-container">
        <div class="header-resi text-center">
            <h2 class="text-primary"><i class="bi bi-receipt"></i> Resi Pembayaran</h2>
            <p class="text-muted">Toko Buku Online</p>
        </div>
        
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="info-pembeli">
                    <h5><i class="bi bi-person"></i> Informasi Pembeli</h5>
                    <hr />
                    <p><strong>Nama:</strong> <?= htmlspecialchars($nama) ?></p>
                    <p><strong>Alamat:</strong> <?= htmlspecialchars($alamat) ?></p>
                    <p><strong>Telepon:</strong> <?= htmlspecialchars($telepon) ?></p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="info-pembeli">
                    <h5><i class="bi bi-credit-card"></i> Informasi Transaksi</h5>
                    <hr />
                    <p><strong>No. Invoice:</strong> <?= $no_invoice ?></p>
                    <p><strong>Tanggal:</strong> <?= date('d F Y') ?></p>
                    <p><strong>Metode Pembayaran:</strong> <?= htmlspecialchars($metode_pembayaran) ?></p>
                    <p><strong>Status:</strong> <span class="badge bg-success">Berhasil</span></p>
                </div>
            </div>
        </div>
        
        <h4 class="mb-3"><i class="bi bi-cart"></i> Detail Pesanan</h4>
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-primary">
                    <tr>
                        <th>No</th>
                        <th>Judul Buku</th>
                        <th class="text-end">Harga Satuan</th>
                        <th class="text-center">Jumlah</th>
                        <th class="text-end">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pesanan as $index => $item): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= htmlspecialchars($item['judul']) ?></td>
                            <td class="text-end">Rp<?= number_format($item['harga'], 0, ',', '.') ?></td>
                            <td class="text-center"><?= $item['jumlah'] ?></td>
                            <td class="text-end">Rp<?= number_format($item['subtotal'], 0, ',', '.') ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <tr class="total-row">
                        <td colspan="4" class="text-end"><strong>Total Belanja</strong></td>
                        <td class="text-end"><strong>Rp<?= number_format($total, 0, ',', '.') ?></strong></td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <div class="mt-4 p-3 bg-light rounded text-center">
            <p class="mb-0">Terima kasih telah berbelanja di Toko Buku Online kami.</p>
            <p class="mb-0">Barang akan dikirim dalam 1-2 hari kerja setelah pembayaran dikonfirmasi.</p>
        </div>
        
        <div class="mt-4 no-print">
            <button onclick="window.print();" class="btn btn-success me-2">
                <i class="bi bi-printer"></i> Cetak Resi
            </button>
            <a href="index.php?page=home" class="btn btn-primary">
                <i class="bi bi-house"></i> Kembali ke Beranda
            </a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
</content>
</create_file>

