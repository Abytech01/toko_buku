<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toko Buku XYZ</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome untuk ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .footer {
            background-color: #343a40;
            color: #fff;
            padding: 40px 0;
        }
        .footer a {
            color: #f8f9fa;
            text-decoration: none;
        }
        .footer a:hover {
            color: #17a2b8;
        }
        .social-icons a {
            font-size: 1.5rem;
            margin-right: 15px;
        }
        .payment-icons img {
            height: 30px;
            margin-right: 10px;
        }
        
    </style>
</head>
<body>

    <!-- Konten utama website di sini -->

    <!-- Footer -->
    <footer class="footer mt-5">
        <div class="container">
            <div class="row">
                <!-- Kolom 1: Tentang Toko -->
                <div class="col-md-4 mb-4">
                    <h5>Toko Buku VANZkt</h5>
                    <p class="text-white">
                        Menyediakan berbagai macam buku berkualitas dengan harga terjangkau sejak lahir.
                    </p>
                    <div class="social-icons">
                        <a href="#"><i class="fab fa-facebook"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-whatsapp"></i></a>
                    </div>
                </div>

                <!-- Kolom 2: Kontak -->
                <div class="col-md-4 mb-4">
                    <h5>Kontak Kami</h5>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-map-marker-alt me-2"></i> Jl. tipes No. 69, Surakarta</li>
                        <li><i class="fas fa-phone me-2"></i> 08812764279 </li>
                        <li><i class="fas fa-envelope me-2"></i> vanzkt69@gmail.com</li>
                    </ul>
                    <h5 class="mt-3">Jam Operasional</h5>
                    <ul class="list-unstyled">
                        <li>Senin-Jumat: 09.00 - 18.00</li>
                        <li>Sabtu-Minggu: 10.00 - 15.00</li>
                    </ul>
                </div>

                <!-- Kolom 3: Pembayaran & Navigasi -->
                <div class="col-md-4 mb-4">
                    <h5>Metode Pembayaran</h5>
                    <div class="payment-icons mb-3">
                        <img src="assets/img/dana.jpg" alt="Dana">
                        <img src="assets/img/qris.jpg" alt="QRIS">
                        <img src="assets/img/bca.jpg" alt="BCA">
                        <img src="assets/img/mandiri.jpg" alt="Mandiri">
                    </div>
                    <h5 class="mt-3">Tautan Cepat</h5>
                    <ul class="list-unstyled">
                        <li><a href="tentang.php">Tentang Kami</a></li>
                        <li><a href="katalog.php">Katalog Buku</a></li>
                        <li><a href="promo.php">Promo</a></li>
                        <li><a href="kebijakan.php">Kebijakan Privasi</a></li>
                    </ul>
                </div>
            </div>

            <hr class="bg-light">

            <div class="row">
                <div class="col-md-12 text-center">
                    <p class="mb-0">&copy; <?php echo date("Y"); ?> Toko Buku VANZkt. Hak Cipta Dilindungi.</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>