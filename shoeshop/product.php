<?php
include 'includes/db.php';
include 'includes/header.php';

if (isset($_GET['id'])) {
  $id = intval($_GET['id']);
  $result = $conn->query("SELECT * FROM products WHERE id = $id");

  if ($row = $result->fetch_assoc()) {
    $productName = htmlspecialchars($row['name']);
    $productDesc = nl2br(htmlspecialchars($row['description']));
    $productImage = $row['image'] ?: 'default.png';
    $productPrice = number_format($row['price'], 0);
?>
    <div class="row">
      <div class="col-md-5">
        <img src="assets/images/product/<?= $productImage ?>" 
             class="img-fluid rounded" 
             alt="<?= $productName ?>" 
             title="<?= $productName ?>">
      </div>
      <div class="col-md-7">
        <h2><?= $productName ?></h2>
        <h4 class="text-danger"><?= $productPrice ?> đ</h4>
        <p><?= $productDesc ?></p>

        <!-- Nút thêm vào giỏ hàng bằng AJAX -->
        <button class="btn btn-success add-to-cart"
          data-id="<?= $row['id'] ?>"
          data-name="<?= $productName ?>">
          🛒 Thêm vào giỏ hàng
        </button>

        <!-- Nút mua ngay -->
        <button class="btn btn-primary buy-now ms-2"
          data-id="<?= $row['id'] ?>"
          data-name="<?= $productName ?>">
          💳 Mua ngay
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

<!-- Xử lý AJAX cho giỏ hàng và mua ngay -->
<script>
  function handleCartAction(productId, productName, action) {
    $.post('add_to_order.php', {
      product_id: productId,
      quantity: 1,
      action: action
    }, function(res) {
      if (res.success) {
        if (action === 'add') {
          $('#cart-count').text(res.total);
          Swal.fire({
            icon: 'success',
            title: 'Đã thêm vào giỏ hàng',
            text: `${productName} (Tổng: ${res.total})`,
            showConfirmButton: false,
            timer: 1500
          });
        } else if (action === 'buy') {
          window.location.href = res.redirect;
        }
      } else {
        Swal.fire('Lỗi', res.message || 'Thao tác thất bại.', 'error');
      }
    }, 'json').fail(function(xhr) {
      if (xhr.status === 401 || (res && res.redirect)) {
        Swal.fire({
          icon: 'warning',
          title: 'Bạn chưa đăng nhập',
          text: 'Vui lòng đăng nhập để tiếp tục.',
          showCancelButton: true,
          confirmButtonText: 'Đăng nhập',
          cancelButtonText: 'Huỷ'
        }).then((result) => {
          if (result.isConfirmed) {
            window.location.href = 'login.php';
          }
        });
      } else {
        Swal.fire('Lỗi', 'Có lỗi xảy ra khi xử lý.', 'error');
      }
    });
  }

  $(document).on('click', '.add-to-cart', function () {
    const id = $(this).data('id');
    const name = $(this).data('name');
    handleCartAction(id, name, 'add');
  });

  $(document).on('click', '.buy-now', function () {
    const id = $(this).data('id');
    const name = $(this).data('name');
    handleCartAction(id, name, 'buy');
  });
</script>
