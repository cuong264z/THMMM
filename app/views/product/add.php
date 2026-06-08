<?php include 'app/views/shares/header.php'; ?>

<h1>Thêm sản phẩm mới</h1>

<form id="add-product-form">

    <div class="form-group">
        <label>Tên sản phẩm:</label>
        <input
            type="text"
            id="name"
            name="name"
            class="form-control"
            required
        >
    </div>

    <div class="form-group">
        <label>Mô tả:</label>
        <textarea
            id="description"
            name="description"
            class="form-control"
            required
        ></textarea>
    </div>

    <div class="form-group">
        <label>Giá:</label>
        <input
            type="number"
            id="price"
            name="price"
            class="form-control"
            required
        >
    </div>

    <div class="form-group">
        <label>Danh mục:</label>

        <select
            id="category_id"
            name="category_id"
            class="form-control"
            required
        >
            <option value="">
                -- Chọn danh mục --
            </option>
        </select>
    </div>

    <button
        type="submit"
        class="btn btn-primary"
    >
        Thêm sản phẩm
    </button>

    <a href="/Product"
       class="btn btn-secondary">
        Quay lại
    </a>

</form>

<?php include 'app/views/shares/footer.php'; ?>

<script>

document.addEventListener("DOMContentLoaded", function () {

    fetch('/Api/Category')

    .then(response => response.json())

    .then(data => {

        const categorySelect =
            document.getElementById('category_id');

        data.forEach(category => {

            const option =
                document.createElement('option');

            option.value = category.id;

            option.textContent = category.name;

            categorySelect.appendChild(option);

        });

    });

    document
    .getElementById('add-product-form')

    .addEventListener('submit', function(event){

        event.preventDefault();

        const formData = new FormData(this);

        const jsonData = {};

        formData.forEach((value,key)=>{

            jsonData[key] = value;

        });

        fetch('/Api/Product', {

            method:'POST',

            headers:{
                'Content-Type':'application/json'
            },

            body: JSON.stringify(jsonData)

        })

        .then(response => response.json())

        .then(data => {

            if(data.message ===
                'Product created successfully')
            {
                alert('Thêm thành công');

                location.href='/Product';
            }
            else
            {
                alert('Thêm thất bại');
            }

        })

        .catch(error => {

            console.error(error);

            alert('Có lỗi xảy ra');

        });

    });

});

</script>