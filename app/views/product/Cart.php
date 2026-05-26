<?php include 'app/views/shares/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">

    <h1>
        <i class="fas fa-shopping-cart"></i>
        Giỏ hàng
    </h1>

    <a href="/Product"
       class="btn btn-outline-primary">

        <i class="fas fa-arrow-left"></i>

        Tiếp tục mua sắm

    </a>

</div>

<?php if (!empty($cart)): ?>

    <div class="row">

        <?php
        $total = 0;
        ?>

        <?php foreach ($cart as $id => $item): ?>

            <?php
            $subtotal =
                $item['price'] * $item['quantity'];

            $total += $subtotal;
            ?>

            <div class="col-md-6 mb-4">

                <div class="card shadow-sm border-0 h-100">

                    <div class="card-body">

                        <div class="row">

                            <!-- IMAGE -->
                            <div class="col-4">

                                <?php if (!empty($item['image'])): ?>

                                    <img
                                        src="http://localhost:8080/uploads/<?php echo $item['image']; ?>"
                                        class="img-fluid rounded"
                                        style="height:120px;object-fit:cover;width:100%;"
                                    >

                                <?php else: ?>

                                    <div class="bg-light d-flex align-items-center justify-content-center rounded"
                                         style="height:120px;">

                                        <i class="fas fa-image fa-2x text-muted"></i>

                                    </div>

                                <?php endif; ?>

                            </div>

                            <!-- INFO -->
                            <div class="col-8">

                                <h4>
                                    <?php
                                    echo htmlspecialchars(
                                        $item['name']
                                    );
                                    ?>
                                </h4>

                                <p class="mb-2 text-danger font-weight-bold">

                                    <?php
                                    echo number_format(
                                        $item['price'],
                                        0,
                                        ',',
                                        '.'
                                    );
                                    ?>

                                    VNĐ

                                </p>

                                <p class="mb-2">

                                    Số lượng:
                                    <strong>
                                        <?php
                                        echo $item['quantity'];
                                        ?>
                                    </strong>

                                </p>

                                <p class="font-weight-bold">

                                    Thành tiền:
                                    <?php
                                    echo number_format(
                                        $subtotal,
                                        0,
                                        ',',
                                        '.'
                                    );
                                    ?>

                                    VNĐ

                                </p>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        <?php endforeach; ?>

    </div>

    <!-- TOTAL -->
    <div class="card shadow-sm border-0 mt-4">

        <div class="card-body d-flex justify-content-between align-items-center">

            <h3 class="mb-0">
                Tổng cộng:
            </h3>

            <h3 class="text-danger mb-0">

                <?php
                echo number_format(
                    $total,
                    0,
                    ',',
                    '.'
                );
                ?>

                VNĐ

            </h3>

        </div>

    </div>

    <!-- ACTION -->
    <div class="mt-4">

        <a href="/Product/checkout"
           class="btn btn-success btn-lg">

            <i class="fas fa-credit-card"></i>

            Thanh toán

        </a>

    </div>

<?php else: ?>

    <div class="text-center py-5">

        <i class="fas fa-shopping-cart fa-4x text-muted mb-3"></i>

        <h3>Giỏ hàng trống</h3>

        <p>Hãy thêm sản phẩm vào giỏ hàng.</p>

        <a href="/Product"
           class="btn btn-primary">

            Mua sắm ngay

        </a>

    </div>

<?php endif; ?>

<?php include 'app/views/shares/footer.php'; ?>