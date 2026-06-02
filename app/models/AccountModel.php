<?php
class AccountModel {
    private $conn;
    private $table_name = "account";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAccountByUsername($username) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE username = :username LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":username", $username);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    // Kiểm tra tài khoản đã từng liên kết bằng Google chưa
    public function getAccountByProvider($provider, $provider_id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE provider = :provider AND provider_id = :provider_id LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":provider", $provider);
        $stmt->bindParam(":provider_id", $provider_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function save($username, $fullName, $password, $role = 'user') {
        if ($this->getAccountByUsername($username)) {
            return false;
        }

        $query = "INSERT INTO " . $this->table_name . " SET username=:username, fullname=:fullname, password=:password, role=:role";
        $stmt = $this->conn->prepare($query);

        $username = htmlspecialchars(strip_tags($username));
        $fullName = htmlspecialchars(strip_tags($fullName));
        $password = password_hash($password, PASSWORD_BCRYPT);
        $role = htmlspecialchars(strip_tags($role));

        $stmt->bindParam(":username", $username);
        $stmt->bindParam(":fullname", $fullName);
        $stmt->bindParam(":password", $password);
        $stmt->bindParam(":role", $role);

        return $stmt->execute();
    }

    // Tự động lưu tài khoản mới khi Đăng nhập Google thành công lần đầu
    public function saveSocialAccount($username, $fullName, $provider, $provider_id, $role = 'user') {
        $query = "INSERT INTO " . $this->table_name . " SET username=:username, fullname=:fullname, provider=:provider, provider_id=:provider_id, role=:role, password=NULL";
        $stmt = $this->conn->prepare($query);

        $username = htmlspecialchars(strip_tags($username));
        $fullName = htmlspecialchars(strip_tags($fullName));
        $provider = htmlspecialchars(strip_tags($provider));
        $provider_id = htmlspecialchars(strip_tags($provider_id));
        $role = htmlspecialchars(strip_tags($role));

        $stmt->bindParam(":username", $username);
        $stmt->bindParam(":fullname", $fullName);
        $stmt->bindParam(":provider", $provider);
        $stmt->bindParam(":provider_id", $provider_id);
        $stmt->bindParam(":role", $role);

        return $stmt->execute();
    }
}
?>