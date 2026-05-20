<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

include 'db.php'; 

$method = $_SERVER['REQUEST_METHOD'];

if ($method == "OPTIONS") {
    http_response_code(200);
    exit();
}

if ($method == "POST") {
    
    $id       = $_POST['id'] ?? null;
    $name     = $_POST['name'] ?? null;
    $age      = $_POST['age'] ?? null;
    $email    = $_POST['email'] ?? null;
    $class    = $_POST['class'] ?? null;
    $address  = $_POST['address'] ?? null;
    $phone    = $_POST['phone'] ?? null;
    $date     = $_POST['date'] ?? null; 
    $sex      = $_POST['sex'] ?? null;   

    if (!empty($id) && !empty($name)) {
        try {
   
            $query = "UPDATE students SET 
                        name    = :name, 
                        age     = :age, 
                        email   = :email, 
                        class   = :class, 
                        address = :address, 
                        phone   = :phone, 
                        date    = :date, 
                        sex     = :sex 
                      WHERE id  = :id";

            $stmt = $conn->prepare($query);

            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':age', $age);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':class', $class);
            $stmt->bindParam(':address', $address);
            $stmt->bindParam(':phone', $phone);
            $stmt->bindParam(':date', $date);
            $stmt->bindParam(':sex', $sex);

            $stmt->execute();

            http_response_code(200);
            echo json_encode(array("success" => true, "message" => "Cập nhật thành công"));
            exit();

        } catch (PDOException $e) {
            http_response_code(400);
            echo json_encode(array("success" => false, "message" => "Lỗi MySQL: " . $e->getMessage()));
            exit();
        }
    } else {
        http_response_code(400);
        echo json_encode(array("success" => false, "message" => "Dữ liệu không hợp lệ hoặc thiếu ID/Tên"));
        exit();
    }
} else {
    http_response_code(405);
    echo json_encode(array("success" => false, "message" => "Phương thức không hợp lệ"));
}