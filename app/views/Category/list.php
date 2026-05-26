<?php include 'app/views/shares/header.php'; ?>

<div class="page-header">

    <h1>

        <i class="fas fa-folder-open"></i>

        Danh mục sản phẩm

    </h1>

    <a href="/Category/add"
       class="btn-add">

        <i class="fas fa-folder-plus"></i>

        Thêm danh mục

    </a>

</div>

<?php if (empty($categories)): ?>

    <div class="empty-state">

        <div class="empty-icon">
            📂
        </div>

        <h3>
            Chưa có danh mục nào
        </h3>

    </div>

<?php else: ?>

    <div class="product-grid">

        <?php foreach ($categories as $category): ?>

            <div class="product-card">

                <div class="product-content">

                    <h3 class="product-name">

                        <i class="fas fa-folder text-primary"></i>

                        <?php echo htmlspecialchars($category->name); ?>

                    </h3>

                    <p class="product-description">

                        <?php echo htmlspecialchars($category->description); ?>

                    </p>

                    <div class="product-actions">

                        <!-- EDIT -->
                        <a href="/Category/edit/<?php echo $category->id; ?>"
                           class="btn-edit">

                            <i class="fas fa-edit"></i>

                            Sửa

                        </a>

                        <!-- DELETE -->
                        <a href="/Category/delete/<?php echo $category->id; ?>"
                           class="btn-delete"

                           onclick="return confirm(
                           'Bạn có chắc muốn xóa danh mục này?'
                           );">

                            <i class="fas fa-trash"></i>

                            Xóa

                        </a>

                    </div>

                </div>

            </div>

        <?php endforeach; ?>

    </div>

<?php endif; ?>

<?php include 'app/views/shares/footer.php'; ?>