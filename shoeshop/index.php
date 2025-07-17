<?php
include 'includes/db.php';
include 'includes/header.php';

$search = $_GET['search'] ?? '';
$sql = "SELECT * FROM products";
if ($search != '') {
  $sql .= " WHERE name LIKE '%" . $conn->real_escape_string($search) . "%'";
}
$sql .= " ORDER BY id DESC LIMIT 12";
$result = $conn->query($sql);
?>

<!-- Banner -->
<div class="container-fluid p-0 mb-5">
  <img src="assets/images/Banner/banner-top.jpg" class="img-fluid w-100" style="max-height: 450px; object-fit: cover;" alt="Banner">
</div>

<!-- Thanh tìm kiếm -->
<div class="container mb-5">
  <form class="d-flex justify-content-center" method="get" style="max-width: 600px; margin: auto;">
    <input type="text" name="search" class="form-control me-2 shadow-sm" placeholder="🔍 Tìm giày..." value="<?= htmlspecialchars($search) ?>">
    <button class="btn btn-primary px-4">Tìm</button>
  </form>
</div>

<div class="container">
  <h4 class="text-center mb-4">
    <?= $search ? 'Kết quả tìm kiếm cho: <em>"' . htmlspecialchars($search) . '"</em>' : '🌟 Sản phẩm mới nhất' ?>
  </h4>

  <!-- Danh sách sản phẩm -->
  <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
    <?php while ($row = $result->fetch_assoc()): ?>
      <div class="col">
        <div class="card h-100 shadow-sm border-0 hover-shadow transition">
          <img src="assets/images/Product/<?= $row['image'] ?>" class="card-img-top img-fluid" alt="<?= htmlspecialchars($row['name']) ?>" style="object-fit: cover; height: 250px;">
          <div class="card-body text-center">
            <h5 class="card-title"><?= htmlspecialchars($row['name']) ?></h5>
            <p class="card-text text-danger fw-bold"><?= number_format($row['price'], 0) ?> đ</p>
            <a href="product.php?id=<?= $row['id'] ?>" class="btn btn-outline-primary btn-sm mb-2">Xem chi tiết</a>

            <!-- Nút Thêm vào giỏ hàng (AJAX) -->
            <button class="btn btn-warning btn-sm add-to-cart-btn" data-id="<?= $row['id'] ?>">🛒 Thêm vào giỏ</button>

            <!-- Nút Mua ngay -->
            <form action="add_to_order.php" method="post" class="d-inline">
              <input type="hidden" name="product_id" value="<?= $row['id'] ?>">
              <input type="hidden" name="quantity" value="1">
              <input type="hidden" name="action" value="buy">
              <button type="submit" class="btn btn-success btn-sm">🛍 Mua ngay</button>
            </form>
          </div>
        </div>
      </div>
    <?php endwhile; ?>
  </div>
</div>

<!-- CSS hiệu ứng -->
<style>
  .hover-shadow:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
  }

  .transition {
    transition: all 0.3s ease;
  }
</style>


<script>
  document.querySelectorAll('.add-to-cart-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      const productId = btn.getAttribute('data-id');

      fetch('add_to_order.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          body: `product_id=${productId}&quantity=1&action=add`
        })
        .then(res => res.text())
        .then(text => {
          try {
            const data = JSON.parse(text);
            if (data.success) {
              // ✅ Thành công
              Swal.fire({
                icon: 'success',
                title: 'Đã thêm vào giỏ hàng!',
                text: data.message || 'Sản phẩm đã được thêm.',
                showConfirmButton: false,
                timer: 1500,
                toast: true,
                position: 'bottom-end'
              });

              // Cập nhật số lượng giỏ
              const cartIcon = document.querySelector('#cart-count');
              if (cartIcon) cartIcon.textContent = data.total;
            } else {
              // ❌ Thất bại có message
              Swal.fire({
                icon: 'error',
                title: 'Không thể thêm vào giỏ',
                text: data.message || 'Đã xảy ra lỗi không xác định.'
              });
            }
          } catch (e) {
            console.error("Lỗi JSON:", text);
            // ❌ Lỗi máy chủ
            Swal.fire({
              icon: 'error',
              title: 'Lỗi máy chủ!',
              text: 'Dữ liệu phản hồi không hợp lệ.'
            });
          }
        })
        .catch(err => {
          // ❌ Lỗi kết nối
          Swal.fire({
            icon: 'error',
            title: 'Lỗi mạng!',
            text: 'Không thể kết nối tới máy chủ. Hãy thử lại sau.'
          });
          console.error('Lỗi fetch:', err);
        });
    });
  });
</script>

<?php include 'includes/footer.php'; ?>