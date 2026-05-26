<?php include 'app/views/shares/header.php'; ?>

<div class="form-container">

    <div class="card shadow border-0">

        <div class="card-body p-5">

            <h2 class="mb-4">

                <i class="fas fa-edit"></i>

                Sửa danh mục

            </h2>

            <form method="POST"
                  action="/Category/update">

                <input type="hidden"
                       name="id"
                       value="<?php echo $category->id; ?>">

                <!-- NAME -->
                <div class="form-group">

                    <label>Tên danh mục</label>

                    <input type="text"
                           name="name"
                           class="form-control"

                           value="<?php echo htmlspecialchars($category->name); ?>"

                           required>

                </div>

                <!-- DESCRIPTION -->
                <div class="form-group">

                    <label>Mô tả</label>

                    <textarea name="description"
                              rows="5"
                              class="form-control"><?php echo htmlspecialchars($category->description); ?></textarea>

                </div>

                <button type="submit"
                        class="btn btn-primary">

                    <i class="fas fa-save"></i>

                    Cập nhật

                </button>

            </form>

        </div>

    </div>

</div>

<?php include 'app/views/shares/footer.php'; ?>