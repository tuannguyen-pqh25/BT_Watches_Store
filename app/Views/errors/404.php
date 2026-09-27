<?php
/**
 * app/Views/errors/404.php — Trang 404 Not Found
 * [C1] Giao diện độc lập, không kế thừa layout chung để bỏ Header/Footer
 */
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>404 — Không Tìm Thấy Trang</title>
  
  <!-- Google Fonts: Outfit -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  
  <!-- Bootstrap 5 CDN -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- FontAwesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  
  <style>
    body {
      font-family: 'Outfit', sans-serif;
      background-color: #f8f9fa;
    }
  </style>
</head>
<body class="d-flex align-items-center justify-content-center min-vh-100">
  
  <div class="container text-center">
    <div class="display-1 fw-bold text-warning mb-3" style="font-size: 8rem;">404</div>
    <h2 class="fw-bold text-dark mb-3 text-uppercase" style="letter-spacing: 2px;">Trang Không Tồn Tại</h2>
    <p class="text-secondary mb-5 fs-5">Đường dẫn bạn yêu cầu không được tìm thấy trên hệ thống.</p>
    <a href="/" class="btn btn-dark btn-lg fw-bold px-5 py-3 text-uppercase shadow" style="letter-spacing: 1px; border-radius: 50px;">
      <i class="fas fa-arrow-left me-2"></i>Về Trang Chủ
    </a>
  </div>

</body>
</html>
