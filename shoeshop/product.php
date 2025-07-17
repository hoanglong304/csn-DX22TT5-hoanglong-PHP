<?php
include 'includes/db.php';
include 'includes/header.php';

if (isset($_GET['id'])) {
  $id = intval($_GET['id']);
  $result = $conn->query("SELECT * FROM products WHERE id = $id");
  if ($row = $result->fetch_assoc()) {
?>
    <div class="row">
      <div class="col-md-5">
        <img src="assets/images/product/<?= $row['image'] ?>" class="img-fluid rounded" alt="<?= $row['name'] ?>">
      </div>
      <div class="col-md-7">
        <h2><?= $row['name'] ?></h2>
        <h4 class="text-danger"><?= number_format($row['price'], 0) ?> đ</h4>
        <p><?= nl2br($row['description']) ?></p>

        <!-- Nút thêm vào giỏ hàng bằng AJAX -->
        <button class="btn btn-success add-to-cart"
          data-id="<?= $row['id'] ?>"
          data-name="<?= htmlspecialchars($row['name']) ?>">
          🛒 Thêm vào giỏ hàng
        </button>
      </div>
    </div>
<?php
  } else {
    echo "<div class='alert alert-warning'>Sản phẩm không tồn tại.</div>";
  }
} else {
  echo "<div class='alert alert-danger'>Không có sản phẩm được chọn.</div>";
}

include 'includes/footer.php';
?>

<!-- Thêm thư viện jQuery và SweetAlert2 -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Xử lý sự kiện thêm vào giỏ hàng bằng AJAX -->
<script>
  $(document).on('click', '.add-to-cart', function() {
    const productId = $(this).data('id');
    const productName = $(this).data('name');

    $.post('add_to_order.php', {
      product_id: productId,
      quantity: 1,
      action: 'add'
    }, function(res) {
      if (res.success) {
        $('#cart-count').text(res.total); // ✅ Cập nhật số lượng trên icon

        Swal.fire({
          icon: 'success',
          title: 'Đã thêm vào giỏ hàng',
          text: `${productName} (Tổng: ${res.total})`,
          showConfirmButton: false,
          timer: 1500
        });
      } else {
        Swal.fire('Lỗi', res.message || 'Không thể thêm sản phẩm.', 'error');
      }
    }, 'json').fail(function(xhr) {
      if (xhr.status === 401) {
        Swal.fire({
          icon: 'warning',
          title: 'Bạn chưa đăng nhập',
          text: 'Vui lòng đăng nhập để thêm sản phẩm vào giỏ.',
          showCancelButton: true,
          confirmButtonText: 'Đăng nhập',
          cancelButtonText: 'Huỷ'
        }).then((result) => {
          if (result.isConfirmed) {
            window.location.href = 'login.php';
          }
        });
      } else {
        Swal.fire('Lỗi', 'Có lỗi xảy ra khi thêm sản phẩm.', 'error');
      }
    });
  });
</script>