<?php

require_once('app/config/database.php');
require_once('app/models/ProductModel.php');
require_once('app/models/CategoryModel.php');
require_once('app/helpers/RedisHelper.php');
require_once('app/helpers/SessionHelper.php');

class ProductController
{
    private $productModel;
    private $db;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();

        $this->productModel =
            new ProductModel($this->db);
    }

    // =========================
    // LIST
    // =========================
    public function index()
    {
        $products =
            $this->productModel->getProducts();

        include 'app/views/product/list.php';
    }

    // =========================
    // SHOW
    // =========================
    public function show($id)
    {
        $product =
            $this->productModel->getProductById($id);

        $subImages =
            $this->productModel
            ->getProductImages($id);

        if ($product)
        {
            include 'app/views/product/show.php';
        }
        else
        {
            echo "Không thấy sản phẩm.";
        }
    }

    // =========================
    // ADD FORM
    // =========================
    public function add()
    {
        if (!SessionHelper::isAdmin())
        {
            die('Bạn không có quyền truy cập!');
        }

        $categories =
            (new CategoryModel($this->db))
            ->getCategories();

        include 'app/views/product/add.php';
    }

    // =========================
    // SAVE
    // =========================
    public function save()
    {
        if (!SessionHelper::isAdmin())
        {
        die('Bạn không có quyền thực hiện chức năng này!');
        }
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $name = $_POST['name'] ?? '';

            $description =
                $_POST['description'] ?? '';

            $price =
                $_POST['price'] ?? '';

            $category_id =
                $_POST['category_id'] ?? '';

            $imageName = null;

            // MAIN IMAGE
            if (
                isset($_FILES['image']) &&
                $_FILES['image']['error'] == 0
            )
            {
                $uploadDir = 'uploads/';

                if (!is_dir($uploadDir))
                {
                    mkdir($uploadDir, 0777, true);
                }

                $extension =
                    strtolower(
                        pathinfo(
                            $_FILES['image']['name'],
                            PATHINFO_EXTENSION
                        )
                    );

                $imageName =
                    uniqid() . '.' . $extension;

                move_uploaded_file(
                    $_FILES['image']['tmp_name'],
                    $uploadDir . $imageName
                );
            }

            // SAVE PRODUCT
            $this->productModel->addProduct(
                $name,
                $description,
                $price,
                $category_id,
                $imageName
            );

            // GET PRODUCT ID
            $product_id =
                $this->db->lastInsertId();

            // SAVE SUB IMAGES
            if (!empty($_FILES['sub_images']['name'][0]))
            {
                foreach (
                    $_FILES['sub_images']['tmp_name']
                    as $key => $tmp_name
                )
                {
                    $fileName =
                        $_FILES['sub_images']['name'][$key];

                    $extension =
                        strtolower(
                            pathinfo(
                                $fileName,
                                PATHINFO_EXTENSION
                            )
                        );

                    $newImage =
                        uniqid() . '.' . $extension;

                    move_uploaded_file(
                        $tmp_name,
                        'uploads/' . $newImage
                    );

                    $this->productModel
                        ->addProductImage(
                            $product_id,
                            $newImage
                        );
                }
            }

            header('Location: /Product');

            exit();
        }
    }

    // =========================
    // EDIT FORM
    // =========================
    public function edit($id)
    {
        if (!SessionHelper::isAdmin())
        {
            die('Bạn không có quyền chỉnh sửa!');
        }

        $product =
            $this->productModel->getProductById($id);

        $categories =
            (new CategoryModel($this->db))
            ->getCategories();

        include 'app/views/product/edit.php';
    }

    // =========================
    // UPDATE
    // =========================
    public function update()
    {
        if (!SessionHelper::isAdmin())
        {
            die('Bạn không có quyền cập nhật!');
        }
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $id = $_POST['id'];

            $name = $_POST['name'];

            $description = $_POST['description'];

            $price = $_POST['price'];

            $category_id = $_POST['category_id'];

            $imageName = null;

            // UPDATE IMAGE
            if (
                isset($_FILES['image']) &&
                $_FILES['image']['error'] == 0
            )
            {
                $uploadDir = 'uploads/';

                $extension =
                    strtolower(
                        pathinfo(
                            $_FILES['image']['name'],
                            PATHINFO_EXTENSION
                        )
                    );

                $imageName =
                    uniqid() . '.' . $extension;

                $targetFile =
                    $uploadDir . $imageName;

                move_uploaded_file(
                    $_FILES['image']['tmp_name'],
                    $targetFile
                );
            }

            $edit =
                $this->productModel->updateProduct(
                    $id,
                    $name,
                    $description,
                    $price,
                    $category_id,
                    $imageName
                );

            // SAVE SUB IMAGES
            if (!empty($_FILES['sub_images']['name'][0]))
            {
                foreach (
                    $_FILES['sub_images']['tmp_name']
                    as $key => $tmp_name
                )
                {
                    $fileName =
                        $_FILES['sub_images']['name'][$key];

                    $extension =
                        strtolower(
                            pathinfo(
                                $fileName,
                                PATHINFO_EXTENSION
                            )
                        );

                    $newImage =
                        uniqid() . '.' . $extension;

                    move_uploaded_file(
                        $tmp_name,
                        'uploads/' . $newImage
                    );

                    $this->productModel
                        ->addProductImage(
                            $id,
                            $newImage
                        );
                }
            }

            if ($edit)
            {
                header('Location: /Product');

                exit();
            }
            else
            {
                echo "Lỗi cập nhật sản phẩm";
            }
        }
    }

    // =========================
    // DELETE
    // =========================
    public function delete($id)
    {
        if (!SessionHelper::isAdmin())
        {
            die('Bạn không có quyền xóa!');
        }
        $product =
            $this->productModel->getProductById($id);

        if (
            !empty($product->image) &&
            file_exists(
                'uploads/' . $product->image
            )
        )
        {
            unlink(
                'uploads/' . $product->image
            );
        }

        $subImages =
            $this->productModel
            ->getProductImages($id);

        foreach ($subImages as $img)
        {
            if (
                file_exists(
                    'uploads/' . $img->image
                )
            )
            {
                unlink(
                    'uploads/' . $img->image
                );
            }
        }

        $this->productModel->deleteProduct($id);

        header('Location: /Product');

        exit();
    }

    // =========================
    // ADD TO CART - REDIS
    // =========================
    public function addToCart($id)
    {
        $product =
            $this->productModel
            ->getProductById($id);

        if (!$product)
        {
            echo "Không tìm thấy sản phẩm.";
            return;
        }

        $redis =
            RedisHelper::connect();

        $cartKey = "cart";

        $cart =
            $redis->get($cartKey);

        $cart =
            $cart
            ? json_decode($cart, true)
            : [];

        if (isset($cart[$id]))
        {
            $cart[$id]['quantity']++;
        }
        else
        {
            $cart[$id] = [
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => 1,
                'image' => $product->image
            ];
        }

        $redis->set(
            $cartKey,
            json_encode($cart)
        );

        header('Location: /Product/cart');

        exit();
    }

    // =========================
    // CART - REDIS
    // =========================
    public function cart()
    {
        $redis =
            RedisHelper::connect();

        $cart =
            $redis->get("cart");

        $cart =
            $cart
            ? json_decode($cart, true)
            : [];

        include 'app/views/product/cart.php';
    }

    // =========================
    // CHECKOUT
    // =========================
    public function checkout()
    {
        include 'app/views/product/checkout.php';
    }

    // =========================
    // PROCESS CHECKOUT
    // =========================
    public function processCheckout()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $name = $_POST['name'];

            $phone = $_POST['phone'];

            $address = $_POST['address'];

            $redis =
                RedisHelper::connect();

            $cart =
                json_decode(
                    $redis->get("cart"),
                    true
                );

            if (empty($cart))
            {
                echo "Giỏ hàng trống.";
                return;
            }

            $this->db->beginTransaction();

            try
            {
                // INSERT ORDER
                $query =
                    "INSERT INTO orders
                    (name, phone, address)
                    VALUES
                    (:name, :phone, :address)";

                $stmt =
                    $this->db->prepare($query);

                $stmt->bindParam(':name', $name);

                $stmt->bindParam(':phone', $phone);

                $stmt->bindParam(':address', $address);

                $stmt->execute();

                $order_id =
                    $this->db->lastInsertId();

                // INSERT DETAILS
                foreach ($cart as $product_id => $item)
                {
                    $query =
                        "INSERT INTO order_details
                        (
                            order_id,
                            product_id,
                            quantity,
                            price
                        )
                        VALUES
                        (
                            :order_id,
                            :product_id,
                            :quantity,
                            :price
                        )";

                    $stmt =
                        $this->db->prepare($query);

                    $stmt->bindParam(
                        ':order_id',
                        $order_id
                    );

                    $stmt->bindParam(
                        ':product_id',
                        $product_id
                    );

                    $stmt->bindParam(
                        ':quantity',
                        $item['quantity']
                    );

                    $stmt->bindParam(
                        ':price',
                        $item['price']
                    );

                    $stmt->execute();
                }

                // CLEAR REDIS CART
                $redis->del("cart");

                $this->db->commit();

                header(
                    'Location: /Product/orderConfirmation'
                );

                exit();
            }
            catch (Exception $e)
            {
                $this->db->rollBack();

                echo "Lỗi xử lý đơn hàng: "
                    . $e->getMessage();
            }
        }
    }
    public function test()
    {
    echo "Session save handler: ";
    echo session_module_name();

    echo "<br>";

    echo "Session save path: ";
    echo ini_get('session.save_path');
    }
    // =========================
    // ORDER SUCCESS
    // =========================
    public function orderConfirmation()
    {
        include
            'app/views/product/orderConfirmation.php';
    }
}
?>