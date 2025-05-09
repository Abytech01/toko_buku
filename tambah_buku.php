<?php
include_once "header.php";
include 'koneksi.php';

// Ambil kategori dari parameter URL jika ada
$kategori_id_terpilih = isset($_GET['kategori_id']) ? (int)$_GET['kategori_id'] : 0;

// Ambil data kategori dari database
$kategori_result = $conn->query("SELECT * FROM kategori");
?>

<div class="container my-5">
  <h2 class="mb-4">Tambah Buku Baru</h2>

  <form action="proses_tambah_buku.php" method="post" enctype="multipart/form-data">
    <div class="mb-3">
      <label for="judul" class="form-label">Judul Buku</label>
      <input type="text" name="judul" class="form-control" required>
    </div>

    <div class="mb-3">
      <label for="penulis" class="form-label">Penulis</label>
      <input type="text" name="penulis" class="form-control" required>
    </div>

    <div class="mb-3">
      <label for="penerbit" class="form-label">Penerbit</label>
      <input type="text" name="penerbit" class="form-control" required>
    </div>

    <div class="mb-3">
      <label for="tahun_terbit" class="form-label">Tahun Terbit</label>
      <input type="number" name="tahun_terbit" class="form-control" required>
    </div>

    <div class="mb-3">
      <label for="kategori_id" class="form-label">Kategori</label>
      <select name="kategori_id" class="form-select" required>
        <option value="">-- Pilih Kategori --</option>
        <?php while ($row = $kategori_result->fetch_assoc()): ?>
          <option value="<?= $row['id'] ?>" <?= ($row['id'] == $kategori_id_terpilih ? 'selected' : '') ?>>
            <?= htmlspecialchars($row['nama_kategori']) ?>
          </option>
        <?php endwhile; ?>
      </select>
    </div>

    <div class="mb-3">
      <label for="stok" class="form-label">Stok</label>
      <input type="number" name="stok" class="form-control" required>
    </div>

    <div class="mb-3">
      <label for="harga" class="form-label">Harga (Rp)</label>
      <input type="number" name="harga" class="form-control" required>
    </div>

    <div class="mb-3">
      <label for="deskripsi" class="form-label">Deskripsi</label>
      <textarea name="deskripsi" class="form-control" rows="4" required></textarea>
    </div>

    <div class="mb-3">
      <label for="gambar" class="form-label">Gambar Buku</label>
      <input type="file" name="gambar" class="form-control" required>
    </div>

    <button type="submit" class="btn btn-primary">Simpan</button>
    <a href="index.php" class="btn btn-secondary">Kembali</a>
  </form>
</div>

<?php include_once "footer.php"; ?>
