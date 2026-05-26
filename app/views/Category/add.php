<?php include 'app/views/shares/header.php'; ?>

<div class="form-container">

    <div class="card shadow border-0">

        <div class="card-body p-5">

            <h2 class="mb-4">

                <i class="fas fa-folder-plus"></i>

                Thêm danh mục

            </h2>

            <form method="POST"
                  action="/Category/save">

                <!-- NAME -->
                <div class="form-group">

                    <label>Tên danh mục</label>

                    <input type="text"
                           name="name"
                           class="form-control"
                           required>

                </div>

                <!-- DESCRIPTION -->
                <div class="form-group">

                    <label>Mô tả</label>

                    <textarea name="description"
                              rows="5"
                              class="form-control"></textarea>

                </div>

                <button type="submit"
                        class="btn btn-success">

                    <i class="fas fa-save"></i>

                    Lưu danh mục

                </button>

            </form>

        </div>

    </div>

</div>

<?php include 'app/views/shares/footer.php'; ?>