<?php include 'app/views/shares/header.php'; ?>

<div class="page-header">
    <h1>
        <i class="fas fa-boxes"></i>
        Danh sách sản phẩm
    </h1>
    <a href="/Product/add" class="btn-add">
        <i class="fas fa-plus-circle"></i>
        Thêm sản phẩm mới
    </a>
</div>

<div id="product-list" class="product-grid"></div>

<script>
document.addEventListener("DOMContentLoaded", function () {

    const token = localStorage.getItem('jwtToken');

    if (!token) {
        alert('Vui lòng đăng nhập!');
        location.href = '/Account/login';
        return;
    }

    fetch('/Api/Product', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + token
        }
    })
    .then(response => {
        if (response.status === 401) {
            localStorage.removeItem('jwtToken');
            alert('Phiên đăng nhập hết hạn, vui lòng đăng nhập lại!');
            location.href = '/Account/login';
            return;
        }
        return response.json();
    })
    .then(products => {
        if (!products) return;

        const productList = document.getElementById('product-list');

        if (products.length === 0) {
            productList.innerHTML = `
                <div class="empty-state">
                    <div class="empty-icon">📦</div>
                    <h3>Chưa có sản phẩm nào</h3>
                    <p>Hãy bắt đầu bằng cách thêm sản phẩm đầu tiên!</p>
                </div>
            `;
            return;
        }

        products.forEach(product => {
            const imageHtml = product.image
                ? `<img src="/uploads/${product.image}" alt="${product.name}">`
                : `<div class="no-image"><i class="fas fa-image"></i><span>No Image</span></div>`;

            const card = document.createElement('div');
            card.className = 'product-card';
            card.innerHTML = `
                <div class="product-image">${imageHtml}</div>
                <div class="product-content">
                    <h3 class="product-name">${product.name}</h3>
                    <p class="product-description">${product.description}</p>
                    <div class="product-info">
                        <div class="product-price">
                            <i class="fas fa-tag"></i>
                            ${Number(product.price).toLocaleString('vi-VN')} VNĐ
                        </div>
                        <div class="product-category">
                            <i class="fas fa-folder"></i>
                            ${product.category_name ?? 'Chưa phân loại'}
                        </div>
                    </div>
                    <div class="product-actions">
                        <a href="/Product/show/${product.id}" class="btn-view">
                            <i class="fas fa-eye"></i> Xem
                        </a>
                        <a href="/Product/edit/${product.id}" class="btn-edit">
                            <i class="fas fa-edit"></i> Sửa
                        </a>
                        <a href="/Product/addToCart/${product.id}" class="btn-cart">
                            <i class="fas fa-cart-plus"></i> Giỏ hàng
                        </a>
                        <button class="btn-delete" onclick="deleteProduct(${product.id})">
                            <i class="fas fa-trash"></i> Xóa
                        </button>
                    </div>
                </div>
            `;
            productList.appendChild(card);
        });
    })
    .catch(error => {
        console.error(error);
        document.getElementById('product-list').innerHTML =
            `<div class="alert alert-danger">Không thể tải dữ liệu từ API.</div>`;
    });
});

function deleteProduct(id) {
    if (confirm('Bạn có chắc muốn xóa sản phẩm này?')) {
        const token = localStorage.getItem('jwtToken');
        fetch('/Api/Product/' + id, {
            method: 'DELETE',
            headers: { 'Authorization': 'Bearer ' + token }
        })
        .then(response => response.json())
        .then(data => {
            if (data.message === 'Product deleted successfully') {
                location.reload();
            } else {
                alert('Xóa thất bại');
            }
        })
        .catch(error => {
            console.error(error);
            alert('Có lỗi xảy ra');
        });
    }
}
</script>

<style>
.btn-cart {
    background: #2563eb; color: white; padding: 10px 14px;
    border-radius: 10px; text-decoration: none; font-size: 14px;
    font-weight: 600; transition: 0.3s; border: none;
}
.btn-cart:hover { background: #1d4ed8; color: white; }
.btn-delete {
    background: #ef4444; color: white; padding: 10px 14px;
    border: none; border-radius: 10px; cursor: pointer;
    font-size: 14px; font-weight: 600;
}
.btn-delete:hover { background: #dc2626; }
</style>

<?php include 'app/views/shares/footer.php'; ?>