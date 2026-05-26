<?php include 'app/views/shares/header.php'; ?>

<h1>Sửa sản phẩm</h1>

<?php if (!empty($errors)): ?>
<div class="alert alert-danger">
    <ul>
        <?php foreach ($errors as $error): ?>
        <li><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
        <?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>

<!-- 🔥 SỬA: action="/Product/update" -->
<form method="POST" action="/Product/update" onsubmit="return validateForm();" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?php echo $product->id; ?>">
    
    <div class="form-group">
        <label for="name">Tên sản phẩm:</label>
        <input type="text" id="name" name="name" class="form-control" value="<?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>" required>
    </div>
    
    <div class="form-group">
        <label for="description">Mô tả:</label>
        <textarea id="description" name="description" class="form-control" required><?php echo htmlspecialchars($product->description, ENT_QUOTES, 'UTF-8'); ?></textarea>
    </div>
    
    <div class="form-group">
        <label for="price">Giá:</label>
        <input type="number" id="price" name="price" class="form-control" step="0.01" value="<?php echo htmlspecialchars($product->price, ENT_QUOTES, 'UTF-8'); ?>" required>
    </div>
    
    <div class="form-group">
        <label for="image">Hình ảnh sản phẩm:</label>
        
        <?php if (!empty($product->image) && file_exists('uploads/' . $product->image)): ?>
        <div class="mb-3">
            <img src="/uploads/<?php echo htmlspecialchars($product->image); ?>" 
                 alt="Product Image" 
                 class="img-thumbnail" 
                 style="max-width: 200px; max-height: 200px;">
            <p class="text-muted mt-2">Hình ảnh hiện tại</p>
        </div>
        <?php endif; ?>
        
        <input type="file" id="image" name="image" class="form-control" accept="image/*">
        <small class="form-text text-muted">
            Để giữ hình cũ, không chọn file mới. 
            <br>Chỉ chấp nhận file: JPG, JPEG, PNG, GIF (tối đa 2MB)
        </small>
    </div>
    
    <div class="form-group">
        <label for="category_id">Danh mục:</label>
        <select id="category_id" name="category_id" class="form-control" required>
            <option value="">-- Chọn danh mục --</option>
            <?php foreach ($categories as $category): ?>
            <option value="<?php echo $category->id; ?>" 
                    <?php echo ($product->category_id == $category->id) ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($category->name, ENT_QUOTES, 'UTF-8'); ?>
            </option>
            <?php endforeach; ?>
        </select>
    </div>
    
    <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
    <!-- 🔥 SỬA: href="/Product" -->
    <a href="/Product" class="btn btn-secondary mt-2">Quay lại danh sách sản phẩm</a>
</form>

<?php include 'app/views/shares/footer.php'; ?>