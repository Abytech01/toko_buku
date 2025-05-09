<?php
  $current_page = basename($_SERVER['PHP_SELF']);
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container">
    <img src="assets/img/logo.png" alt="Logo TokuBuku" width="70" height="70" class="me-2">
    <a class="navbar-brand fw-bold" href="index.php">Toku Buku VANZkt </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link <?= ($current_page == 'index.php') ? 'active' : '' ?>" href="index.php">Beranda</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= ($current_page == 'keranjang.php') ? 'active' : '' ?>" href="keranjang.php">Keranjang</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= ($current_page == 'checkout.php') ? 'active' : '' ?>" href="checkout.php">Checkout</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

