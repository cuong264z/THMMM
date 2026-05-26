<?php include 'app/views/shares/header.php'; ?>

<?php if (isset($product)): ?>

<div class="container mt-5 mb-5">

    <div class="row">

        <!-- IMAGE -->
        <div class="col-md-6">

            <?php
                $mainImage = !empty($product->image)
                    ? '/uploads/' . $product->image
                    : '/uploads/no-image.png';
            ?>

            <!-- MAIN IMAGE -->
            <img
                id="mainImage"
                src="<?php echo $mainImage; ?>"
                class="img-fluid rounded shadow"
                style="
                    width:100%;
                    height:450px;
                    object-fit:cover;
                    border-radius:16px;
                "
            >

            <!-- SUB IMAGES -->
            <div class="d-flex flex-wrap mt-3">

                <!-- MAIN THUMB -->
                <img
                    src="<?php echo $mainImage; ?>"
                    width="100"
                    height="100"
                    onclick="changeImage(this)"
                    style="
                        object-fit:cover;
                        margin-right:10px;
                        margin-bottom:10px;
                        border-radius:10px;
                        border:2px solid #0d6efd;
                        cursor:pointer;
                        transition:0.3s;
                    "
                >

                <?php
                $subImages =
                    $this->productModel
                    ->getProductImages($product->id);
                ?>

                <?php if (!empty($subImages)): ?>

                    <?php foreach($subImages as $img): ?>

                        <img
                            src="/uploads/<?php echo $img->image; ?>"
                            width="100"
                            height="100"
                            onclick="changeImage(this)"
                            style="
                                object-fit:cover;
                                margin-right:10px;
                                margin-bottom:10px;
                                border-radius:10px;
                                border:2px solid #ddd;
                                cursor:pointer;
                                transition:0.3s;
                            "
                            onmouseover="this.style.transform='scale(1.05)'"
                            onmouseout="this.style.transform='scale(1)'"
                        >

                    <?php endforeach; ?>

                <?php endif; ?>

            </div>

        </div>

        <!-- PRODUCT INFO -->
        <div class="col-md-6">

            <h1
                style="
                    font-size:40px;
                    font-weight:800;
                    margin-bottom:20px;
                "
            >
                <?php echo htmlspecialchars($product->name); ?>
            </h1>

            <div
                style="
                    font-size:32px;
                    font-weight:bold;
                    color:#0d6efd;
                    margin-bottom:20px;
                "
            >
                <?php echo number_format($product->price, 0, ',', '.'); ?> VNĐ
            </div>

            <div class="mb-3">
                <strong>Danh mục:</strong>

                <?php echo htmlspecialchars($product->category_name ?? 'Chưa có'); ?>
            </div>

            <div
                style="
                    background:#fff;
                    padding:20px;
                    border-radius:14px;
                    box-shadow:0 4px 10px rgba(0,0,0,0.05);
                "
            >

                <h5 class="mb-3">
                    Mô tả sản phẩm
                </h5>

                <p>
                    <?php echo nl2br(htmlspecialchars($product->description)); ?>
                </p>

            </div>

            <div class="mt-4">

                <a
                    href="/Product/edit/<?php echo $product->id; ?>"
                    class="btn btn-warning"
                >
                    <i class="fas fa-edit"></i>
                    Sửa sản phẩm
                </a>

                <a
                    href="/Product"
                    class="btn btn-secondary"
                >
                    <i class="fas fa-arrow-left"></i>
                    Quay lại
                </a>

            </div>

        </div>

    </div>

</div>

<!-- SCRIPT -->
<script>

function changeImage(element)
{
    document.getElementById('mainImage').src =
        element.src;
}

</script>

<?php else: ?>

<div class="container mt-5">

    <div class="alert alert-danger">

        Không tìm thấy sản phẩm.

    </div>

</div>

<?php endif; ?>

<?php include 'app/views/shares/footer.php'; ?>