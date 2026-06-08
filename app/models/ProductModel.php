<?php

class ProductModel
{
    private $conn;
    private $table_name = "products";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // GET ALL PRODUCTS
    public function getProducts()
    {
        $query = "SELECT
                    p.id,
                    p.name,
                    p.description,
                    p.price,
                    p.image,
                    c.name AS category_name
                  FROM products p
                  LEFT JOIN categories c
                  ON p.category_id = c.id
                  ORDER BY p.id DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // GET PRODUCT BY ID
    public function getProductById($id)
    {
        $query = "SELECT
                    p.*,
                    c.name AS category_name
                  FROM products p
                  LEFT JOIN categories c
                  ON p.category_id = c.id
                  WHERE p.id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    // ADD PRODUCT
    public function addProduct(
        $name,
        $description,
        $price,
        $category_id,
        $image = null
    )
    {
        $errors = [];

        if (empty($name)) {
            $errors['name'] = 'Tên sản phẩm không được để trống';
        }

        if (empty($description)) {
            $errors['description'] = 'Mô tả không được để trống';
        }

        if (!is_numeric($price) || $price < 0) {
            $errors['price'] = 'Giá sản phẩm không hợp lệ';
        }

        if (count($errors) > 0) {
            return $errors;
        }

        $name = htmlspecialchars(strip_tags($name));
        $description = htmlspecialchars(strip_tags($description));
        $price = htmlspecialchars(strip_tags($price));
        $category_id = htmlspecialchars(strip_tags($category_id));

        $query = "INSERT INTO products
                  (
                    name,
                    description,
                    price,
                    category_id,
                    image
                  )
                  VALUES
                  (
                    :name,
                    :description,
                    :price,
                    :category_id,
                    :image
                  )";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':category_id', $category_id);
        $stmt->bindParam(':image', $image);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // UPDATE PRODUCT
    public function updateProduct(
        $id,
        $name,
        $description,
        $price,
        $category_id,
        $image = null
    )
    {
        $name = htmlspecialchars(strip_tags($name));
        $description = htmlspecialchars(strip_tags($description));
        $price = htmlspecialchars(strip_tags($price));
        $category_id = htmlspecialchars(strip_tags($category_id));

        if ($image === null) {

            $query = "UPDATE products
                      SET
                        name = :name,
                        description = :description,
                        price = :price,
                        category_id = :category_id
                      WHERE id = :id";

            $stmt = $this->conn->prepare($query);

        } else {

            $query = "UPDATE products
                      SET
                        name = :name,
                        description = :description,
                        price = :price,
                        category_id = :category_id,
                        image = :image
                      WHERE id = :id";

            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(':image', $image);
        }

        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':category_id', $category_id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // DELETE PRODUCT
    public function deleteProduct($id)
    {
        $query = "DELETE FROM products
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // ADD SUB IMAGE
    public function addProductImage($product_id, $image)
    {
        $query = "INSERT INTO product_images
                  (product_id, image)
                  VALUES
                  (:product_id, :image)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':product_id', $product_id);
        $stmt->bindParam(':image', $image);

        return $stmt->execute();
    }

    // GET SUB IMAGES
    public function getProductImages($product_id)
    {
        $query = "SELECT *
                  FROM product_images
                  WHERE product_id = :product_id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':product_id', $product_id);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}
?>