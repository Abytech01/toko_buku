<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $judul = $_POST['judul'];
    $penulis = $_POST['penulis'];
    $penerbit = $_POST['penerbit'];
    $tahun_terbit = $_POST['tahun_terbit'];
    $kategori_id = $_POST['kategori_id'];
    $stok = $_POST['stok'];
    $harga = $_POST['harga'];
    $deskripsi = $_POST['deskripsi'];

    // Proses upload gambar
    $gambar = $_FILES['gambar']['name']; // Gunakan nama asli file
    $target_dir = "assets/img";
    $target_file = $target_dir . basename($gambar);

    // Pastikan folder uploads ada
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    // Pindahkan file ke folder uploads
    if (move_uploaded_file($_FILES['gambar']['tmp_name'], $target_file)) {
        // Simpan data ke database
        $sql = "INSERT INTO buku (judul, penulis, penerbit, tahun_terbit, kategori_id, stok, harga, deskripsi, gambar) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssiidss", $judul, $penulis, $penerbit, $tahun_terbit, $kategori_id, $stok, $harga, $deskripsi, $gambar);

        if ($stmt->execute()) {
            header("Location: index.php?status=sukses");
            exit();
        } else {
            header("Location: tambah_buku.php?status=gagal");
            exit();
        }
    } else {
        header("Location: tambah_buku.php?status=upload_gagal");
        exit();
    }
} else {
    header("Location: tambah_buku.php");
    exit();
}
?>