<?php
session_start();
?>

<!DOCTYPE html>
<html lang="vi">

<head>
  <meta charset="UTF-8">
  <title>Sản phẩm - Giỏ hàng AJAX</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

  <div class="container mt-4">
    <h4>Tìm kiếm sản phẩm</h4>
    <input type="text" id="search-box" class="form-control" placeholder="Nhập tên sản phẩm...">
    <div id="search-results" class="row row-cols-1 row-cols-md-3 g-4 mt-3">
      <!-- Kết quả tìm kiếm sẽ được hiển thị ở đây -->
    </div>
  </div>

  <div class="container mt-4">
    <h5>Chọn ngành hàng và thương hiệu</h5>
    <div class="row g-3">
      <div class="col-md-6">
        <label for="category-select" class="form-label">Ngành hàng</label>
        <select class="form-select" id="category-select" onchange="loadBrands()">
          <option value="">-- Chọn ngành hàng --</option>
          <option value="Điện tử">Điện tử</option>
          <option value="Thời trang">Thời trang</option>
          <option value="Gia dụng">Gia dụng</option>
        </select>
      </div>

      <div class="col-md-6">
        <label for="brand-select" class="form-label">Thương hiệu</label>
        <select class="form-select" id="brand-select">
          <option value="">-- Chọn thương hiệu --</option>
        </select>
      </div>
    </div>
  </div>


  <div class="container py-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2>Danh sách sản phẩm</h2>
      <button class="btn btn-primary">
        🛒 Giỏ hàng <span id="cart-count" class="badge bg-warning text-dark">
          <?= isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0 ?>
        </span>
      </button>
    </div>

    <div class="row" id="product-list">
      <!-- Sản phẩm giả lập -->
      <?php for ($i = 1; $i <= 3; $i++): ?>
        <div class="col-md-4 mb-3">
          <div class="card shadow-sm h-100">
            <div class="card-body">
              <h5 class="card-title text-primary" role="button" onclick="getProductDetail(<?= $i ?>)">
                Sản phẩm <?= $i ?>
              </h5>
              <button class="btn btn-success mt-3" onclick="addToCart(<?= $i ?>)">+ Thêm vào giỏ</button>
            </div>
          </div>
        </div>
      <?php endfor; ?>
    </div>

    <hr class="my-5">

    <h3 class="text-center mb-3">Chi tiết sản phẩm</h3>
    <div id="product-detail" class="p-4 bg-white shadow-sm rounded">
      <p class="text-muted text-center">Chọn một sản phẩm để xem chi tiết...</p>
    </div>

    <div class="container mt-5">
      <h5>📊 Bạn mong muốn cải thiện thêm điều gì ở trên website?</h5>
      <form id="pollForm">
        <div class="form-check">
          <input class="form-check-input" type="radio" name="poll" value="Giao diện" id="option1" required>
          <label class="form-check-label" for="option1">Giao diện</label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="radio" name="poll" value="Tốc độ" id="option2">
          <label class="form-check-label" for="option2">Tốc độ</label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="radio" name="poll" value="Dịch vụ khách hàng" id="option3">
          <label class="form-check-label" for="option3">Dịch vụ khách hàng</label>
        </div>
        <button type="submit" class="btn btn-primary mt-2">Gửi</button>
      </form>

      <div id="pollResult" class="mt-4">
        <!-- Kết quả sẽ được hiển thị ở đây -->
      </div>
    </div>

  </div>

  <script>
    let searchTimeout = null;

    document.getElementById('search-box').addEventListener('input', function() {
      clearTimeout(searchTimeout);
      const keyword = this.value.trim();

      searchTimeout = setTimeout(() => {
        if (keyword.length === 0) {
          document.getElementById('search-results').innerHTML = '';
          return;
        }

        fetch('search.php?q=' + encodeURIComponent(keyword))
          .then(response => response.text())
          .then(data => {
            document.getElementById('search-results').innerHTML = data;
          })
          .catch(error => {
            console.error('Lỗi tìm kiếm:', error);
          });
      }, 300); // debounce 300ms
    });

    function getProductDetail(productId) {
      fetch('get_product.php?id=' + productId)
        .then(response => response.text())
        .then(data => {
          document.getElementById('product-detail').innerHTML = data;
        });
    }

    // Hiển thị tab chi tiết hoặc đánh giá
    function showTab(event, tabId) {
      event.preventDefault();
      document.getElementById('detail-tab').style.display = 'block';
      document.getElementById('review-tab').style.display = 'none';

      document.querySelectorAll('#productTabs .nav-link').forEach(el => el.classList.remove('active'));
      event.target.classList.add('active');
    }

    function loadReviews(productId) {
      // Bật tab đánh giá
      document.getElementById('detail-tab').style.display = 'none';
      document.getElementById('review-tab').style.display = 'block';
      document.getElementById('review-tab').innerHTML = '<p class="text-muted">Đang tải đánh giá...</p>';

      // Đánh dấu tab đang active
      document.querySelectorAll('#productTabs .nav-link').forEach(el => el.classList.remove('active'));
      event.target.classList.add('active');

      fetch('reviews.php?product_id=' + productId)
        .then(response => response.text())
        .then(data => {
          document.getElementById('review-tab').innerHTML = data;
        });
    }

    function loadBrands() {

      const category = document.getElementById('category-select').value;
      const brandSelect = document.getElementById('brand-select');

      if (!category) {
        brandSelect.innerHTML = '<option value="">-- Chọn thương hiệu --</option>';
        return;
      }

      fetch('get_brands.php?category=' + encodeURIComponent(category))
        .then(response => response.text())
        .then(data => {
          brandSelect.innerHTML = data;
        })
        .catch(err => {
          brandSelect.innerHTML = '<option value="">Lỗi tải dữ liệu</option>';
          console.error(err);
        });
    }

    document.getElementById('pollForm').addEventListener('submit', function(e) {
      e.preventDefault();
      const selected = document.querySelector('input[name="poll"]:checked');
      if (!selected) return;

      fetch('poll.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          body: 'vote=' + encodeURIComponent(selected.value)
        })
        .then(response => response.json())
        .then(data => {
          const total = data.total;
          const results = data.results;

          let output = '<h6>Kết quả bình chọn:</h6>';
          for (const [key, value] of Object.entries(results)) {
            const percent = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
            output += `
        <p>${key}: ${percent}% (${value} lượt)</p>
        <div class="progress mb-2">
          <div class="progress-bar" style="width: ${percent}%">${percent}%</div>
        </div>
      `;
          }

          document.getElementById('pollResult').innerHTML = output;
          document.getElementById('pollForm').reset();
        })
        .catch(error => {
          console.error('Lỗi khi gửi bình chọn:', error);
        });
    });
  </script>



</body>

</html>