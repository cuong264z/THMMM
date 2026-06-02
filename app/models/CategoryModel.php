<?php

class CategoryModel
{
    private $conn;

    private $table_name = "categories";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // GET ALL
    public function getCategories()
    {
        $query = "SELECT *
                  FROM " . $this->table_name . "
                  ORDER BY id DESC";

        $stmt = $this->conn->prepare($query);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // GET ONE
    public function getCategoryById($id)
    {
        $query = "SELECT *
                  FROM " . $this->table_name . "
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':id', $id);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    // CREATE
    public function addCategory($name, $description)
    {
        $query = "INSERT INTO " . $this->table_name . "
                  (name, description)
                  VALUES
                  (:name, :description)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':name', $name);

        $stmt->bindParam(':description', $description);

        return $stmt->execute();
    }

    // UPDATE
    public function updateCategory($id, $name, $description)
    {
        $query = "UPDATE " . $this->table_name . "
                  SET
                    name = :name,
                    description = :description
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);

        return $stmt->execute();
    }

    // DELETE
    public function deleteCategory($id)
    {
        $query = "DELETE FROM " . $this->table_name . "
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':id', $id);

        return $stmt->execute();
    }
}
?>