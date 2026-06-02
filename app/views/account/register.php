<?php include 'app/views/shares/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-lg-6">

        <div class="card shadow border-0">
            <div class="card-body p-5">

                <h2 class="text-center mb-4">
                    <i class="fas fa-user-plus"></i>
                    Đăng ký tài khoản
                </h2>

                <?php
                if (isset($errors) && count($errors) > 0) {
                    echo "<div class='alert alert-danger'>";
                    foreach ($errors as $err) {
                        echo "<div>$err</div>";
                    }
                    echo "</div>";
                }
                ?>

                <form action="/Account/save" method="post">

                    <div class="form-group mb-3">
                        <label>Tên đăng nhập</label>
                        <input
                            type="text"
                            class="form-control"
                            name="username"
                            placeholder="Nhập username"
                        >
                    </div>

                    <div class="form-group mb-3">
                        <label>Họ và tên</label>
                        <input
                            type="text"
                            class="form-control"
                            name="fullname"
                            placeholder="Nhập họ tên"
                        >
                    </div>

                    <div class="form-group mb-3">
                        <label>Mật khẩu</label>
                        <input
                            type="password"
                            class="form-control"
                            name="password"
                            placeholder="Nhập mật khẩu"
                        >
                    </div>

                    <div class="form-group mb-4">
                        <label>Xác nhận mật khẩu</label>
                        <input
                            type="password"
                            class="form-control"
                            name="confirmpassword"
                            placeholder="Nhập lại mật khẩu"
                        >
                    </div>

                    <button
                        type="submit"
                        class="btn btn-success btn-block"
                    >
                        <i class="fas fa-user-plus"></i>
                        Đăng ký
                    </button>

                </form>

                <div class="text-center mt-4">
                    Đã có tài khoản?
                    <a href="/Account/login">
                        Đăng nhập
                    </a>
                </div>

            </div>
        </div>

    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>