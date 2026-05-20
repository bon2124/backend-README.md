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
    $id = isset($_POST['id']) ? $_POST['id'] : null;
    if (empty($id)) {
        $json_data = file_get_contents("php://input");
        $data = json_decode($json_data, true);
        $id = isset($data['id']) ? $data['id'] : null;
    }

    if (!empty($id)) {
        try {
            $query = "DELETE FROM students WHERE id = :id";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            http_response_code(200);
            echo json_encode(array("success" => true, "message" => "Xóa thành công"));
            exit();

        } catch (PDOException $e) {
            http_response_code(400);
            echo json_encode(array("success" => false, "message" => "Lỗi MySQL: " . $e->getMessage()));
            exit();
        }
    } else {
        http_response_code(400);
        echo json_encode(array("success" => false, "message" => "Thiếu tham số ID sinh viên"));
        exit();
    }
} else {
    http_response_code(405);
    echo json_encode(array("success" => false, "message" => "Phương thức không hợp lệ"));
}