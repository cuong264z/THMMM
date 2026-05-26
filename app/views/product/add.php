<?php include 'app/views/shares/header.php'; ?>

<h1>Thêm sản phẩm mới</h1>

<form
    method="POST"
    action="/Product/save"
    enctype="multipart/form-data"
>

    <div class="form-group">
        <label>Tên sản phẩm:</label>

        <input
            type="text"
            name="name"
            class="form-control"
            required
        >
    </div>

    <div class="form-group">
        <label>Mô tả:</label>

        <textarea
            name="description"
            class="form-control"
            required
        ></textarea>
    </div>

    <div class="form-group">
        <label>Giá:</label>

        <input
            type="number"
            name="price"
            class="form-control"
            required
        >
    </div>

    <!-- ẢNH CHÍNH -->
    <div class="form-group">
        <label>Ảnh chính:</label>

        <input
            type="file"
            name="image"
            class="form-control"
        >
    </div>

    <!-- ẢNH PHỤ -->
    <div class="form-group">
        <label>Ảnh phụ:</label>

        <input
            type="file"
            name="sub_images[]"
            class="form-control"
            multiple
        >
    </div>

    <div class="form-group">
        <label>Danh mục:</label>

        <select
            name="category_id"
            class="form-control"
            required
        >
            <option value="">
                -- Chọn danh mục --
            </option>

            <?php foreach ($categories as $category): ?>

                <option value="<?php echo $category->id; ?>">

                    <?php echo $category->name; ?>

                </option>

            <?php endforeach; ?>
        </select>
    </div>

    <button
        type="submit"
        class="btn btn-primary"
    >
        Thêm sản phẩm
    </button>

</form>

<?php include 'app/views/shares/footer.php'; ?>