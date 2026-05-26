<?php include 'app/views/shares/header.php'; ?>

<div class="page-header">

    <h1>

        <i class="fas fa-boxes"></i>

        Danh sách sản phẩm

    </h1>

    <a href="/Product/add"
       class="btn-add">

        <i class="fas fa-plus-circle"></i>

        Thêm sản phẩm mới

    </a>

</div>

<?php if (empty($products)): ?>

    <div class="empty-state">

        <div class="empty-icon">
            📦
        </div>

        <h3>
            Chưa có sản phẩm nào
        </h3>

        <p>
            Hãy bắt đầu bằng cách thêm sản phẩm đầu tiên!
        </p>

        <a href="/Product/add"
           class="btn-add">

            <i class="fas fa-plus"></i>

            Thêm sản phẩm ngay

        </a>

    </div>

<?php else: ?>

    <div class="product-grid">

        <?php foreach ($products as $product): ?>

            <div class="product-card">

                <!-- IMAGE -->
                <div class="product-image">

                    <?php if (!empty($product->image)): ?>

                        <img
                            src="/uploads/<?php echo htmlspecialchars($product->image); ?>"
                            alt="<?php echo htmlspecialchars($product->name); ?>"
                        >

                    <?php else: ?>

                        <div class="no-image">

                            <i class="fas fa-image"></i>

                            <span>No Image</span>

                        </div>

                    <?php endif; ?>

                </div>

                <!-- CONTENT -->
                <div class="product-content">

                    <h3 class="product-name">

                        <?php echo htmlspecialchars($product->name); ?>

                    </h3>

                    <p class="product-description">

                        <?php echo htmlspecialchars($product->description); ?>

                    </p>

                    <div class="product-info">

                        <!-- PRICE -->
                        <div class="product-price">

                            <i class="fas fa-tag"></i>

                            <?php echo number_format($product->price, 0, ',', '.'); ?>

                            VNĐ

                        </div>

                        <!-- CATEGORY -->
                        <div class="product-category">

                            <i class="fas fa-folder"></i>

                            <?php
                            echo htmlspecialchars(
                                $product->category_name ?? 'Chưa phân loại'
                            );
                            ?>

                        </div>

                    </div>

                    <!-- ACTION -->
                    <div class="product-actions">

                        <!-- VIEW -->
                        <a href="/Product/show/<?php echo $product->id; ?>"
                           class="btn-view">

                            <i class="fas fa-eye"></i>

                            Xem

                        </a>

                        <!-- EDIT -->
                        <a href="/Product/edit/<?php echo $product->id; ?>"
                           class="btn-edit">

                            <i class="fas fa-edit"></i>

                            Sửa

                        </a>

                        <!-- DELETE -->
                        <a href="/Product/delete/<?php echo $product->id; ?>"
                           class="btn-delete"

                           onclick="return confirm(
                           'Bạn có chắc muốn xóa sản phẩm này?'
                           );">

                            <i class="fas fa-trash"></i>

                            Xóa

                        </a>

                        <!-- CART -->
                        <a href="/Product/addToCart/<?php echo $product->id; ?>"
                           class="btn-cart">

                            <i class="fas fa-cart-plus"></i>

                            Thêm giỏ hàng

                        </a>

                    </div>

                </div>

            </div>

        <?php endforeach; ?>

    </div>

<?php endif; ?>

<style>

.btn-cart{
    background:#2563eb;
    color:white;
    padding:10px 14px;
    border-radius:10px;
    text-decoration:none;
    font-size:14px;
    font-weight:600;
    transition:0.3s;
}

.btn-cart:hover{
    background:#1d4ed8;
    color:white;
}

</style>

<?php include 'app/views/shares/footer.php'; ?>