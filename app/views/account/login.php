<?php include 'app/views/shares/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-lg-5">
        <div class="card shadow border-0">
            <div class="card-body p-5">

                <h2 class="text-center mb-4">
                    <i class="fas fa-sign-in-alt"></i>
                    Đăng nhập
                </h2>

                <?php if(isset($error)): ?>
                    <div class="alert alert-danger">
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>

                <form action="/Account/checkLogin" method="post">
                    <div class="form-group mb-3">
                        <label>Tên đăng nhập</label>
                        <input type="text" name="username" class="form-control form-control-lg" required>
                    </div>

                    <div class="form-group mb-4">
                        <label>Mật khẩu</label>
                        <input type="password" name="password" class="form-control form-control-lg" required>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg btn-block w-100">
                        <i class="fas fa-sign-in-alt"></i>
                        Đăng nhập
                    </button>
                </form>

                <div class="text-center mt-3">
                    <div class="position-relative d-flex align-items-center justify-content-center my-3">
                        <hr class="w-100">
                        <span class="position-absolute bg-white px-2 text-muted" style="font-size: 0.9rem;">Hoặc</span>
                    </div>
                    <a href="/Account/googleLogin" class="btn btn-danger btn-lg btn-block w-100" style="background-color: #db4437; border-color: #db4437;">
                        <i class="fab fa-google me-2"></i> Đăng nhập bằng Google
                    </a>
                    <a href="/Account/githubLogin" class="btn btn-dark btn-lg btn-block w-100 mt-2" style="background-color: #24292e; border-color: #24292e;">
                        <i class="fab fa-github me-2"></i> Đăng nhập bằng GitHub
                    </a>
                </div>
                <div class="text-center mt-4">
                    Chưa có tài khoản?
                    <a href="/Account/register">Đăng ký ngay</a>
                </div>

            </div>
        </div>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>