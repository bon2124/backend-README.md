<?php
header("Content-Type: application/json");
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
    $json_data = file_get_contents("php://input");
    $data = json_decode($json_data, true);

    if (!empty($data)) {
        // Nhận dữ liệu từ Android gửi qua (Thẻ @SerializedName)
        // Vì cột id của bạn là INT, nếu bạn nhập "SV01" nó sẽ bị biến thành 0. 
        // Tốt nhất khi test trên App, ô Mã sinh viên Khoa hãy nhập dạng SỐ (Ví dụ: 10221001)
        $id       = !empty($data['id']) ? (int)$data['id'] : null; 
        $name     = $data['name'] ?? null;
        $age      = !empty($data['age']) ? (int)$data['age'] : null;
        $email    = $data['email'] ?? null;
        $class    = $data['class'] ?? null;
        $address  = $data['address'] ?? null;
        $phone    = $data['phone'] ?? null; // Cột phone trong DB là int(11) nên không cần để chuỗi rỗng
        
        // Đổi từ khóa để khớp với database: lấy từ 'birthday' gán vào cột 'date'
        $date     = !empty($data['birthday']) ? $data['birthday'] : null;
        
        // Đổi từ khóa để khớp với database: lấy từ 'gender' gán vào cột 'sex'
        $sex      = $data['gender'] ?? 'Nam';

        if (empty($name)) {
            http_response_code(400);
            echo json_encode(["success" => false, "message" => "Thiếu họ và tên sinh viên"]);
            exit();
        }

        try {
            // Câu lệnh SQL chỉnh sửa tên cột khớp 100% với ảnh phpMyAdmin của bạn: class, date, sex
            $sql = "INSERT INTO students (id, name, age, email, class, address, phone, date, sex) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $conn->prepare($sql);
            $result = $stmt->execute([$id, $name, $age, $email, $class, $address, $phone, $date, $sex]);

            if ($result) {
                http_response_code(201);
                echo json_encode(["success" => true, "message" => "Thêm sinh viên thành công!"]);
            } else {
                http_response_code(500);
                echo json_encode(["success" => false, "message" => "Lỗi không xác định khi lưu"]);
            }
        } catch (PDOException $e) {
            http_response_code(400);
            echo json_encode(["success" => false, "message" => "Lỗi MySQL: " . $e->getMessage()]);
        }
    } else {
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "Dữ liệu trống"]);
    }
} else {
    http_response_code(405);
    echo json_encode(["success" => false, "message" => "Phương thức không hợp lệ"]);
}
?>