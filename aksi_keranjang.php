<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['keranjang'])) {
    $_SESSION['keranjang'] = [];
}

$action = $_GET['action'] ?? $_POST['action'] ?? '';
$id = $_GET['id'] ?? $_POST['id'] ?? 0;

switch ($action) {
    case 'add':
        if ($id > 0) {
            // Check stock availability
            $stmt = $conn->prepare("SELECT stok FROM buku WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $buku = $result->fetch_assoc();
            
            if ($buku && $buku['stok'] > 0) {
                if (isset($_SESSION['keranjang'][$id])) {
                    if ($_SESSION['keranjang'][$id] < $buku['stok']) {
                        $_SESSION['keranjang'][$id]++;
                        $_SESSION['pesan'] = "Jumlah buku di keranjang diperbarui";
                    } else {
                        $_SESSION['pesan'] = "Stok tidak mencukupi";
                    }
                } else {
                    $_SESSION['keranjang'][$id] = 1;
                    $_SESSION['pesan'] = "Buku ditambahkan ke keranjang";
                }
            } else {
                $_SESSION['pesan'] = "Stok buku habis";
            }
        }
        break;

    case 'remove':
        if (isset($_SESSION['keranjang'][$id])) {
            unset($_SESSION['keranjang'][$id]);
            $_SESSION['pesan'] = "Buku dihapus dari keranjang";
        }
        break;

    case 'update':
        if (isset($_POST['id']) && isset($_POST['jumlah'])) {
            $id = $_POST['id'];
            $jumlah = (int)$_POST['jumlah'];

            // Check stock availability
            $stmt = $conn->prepare("SELECT stok FROM buku WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $buku = $result->fetch_assoc();

            if ($buku && $jumlah > 0 && $jumlah <= $buku['stok']) {
                $_SESSION['keranjang'][$id] = $jumlah;
                $_SESSION['pesan'] = "Keranjang diperbarui";
            } else {
                $_SESSION['pesan'] = "Jumlah tidak valid atau stok tidak mencukupi";
            }
        }
        break;
}


header("Location: keranjang.php");
exit();
?>
