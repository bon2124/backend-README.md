<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

include 'db.php';

// Đọc dữ liệu POST form (FormUrlEncoded từ Retrofit)
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

// Nếu rỗng thì thử đọc JSON body
if (empty($username)) {
    $data     = json_decode(file_get_contents("php://input"), true);
    $username = $data['username'] ?? '';
    $password = $data['password'] ?? '';
}

if (empty($username) || empty($password)) {
    echo json_encode([
        "success" => false,
        "message" => "Vui lòng nhập đầy đủ thông tin"
    ]);
    exit();
}

try {
    $sql  = "SELECT * FROM users WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$username, $password]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        echo json_encode([
            "success"  => true,
            "message"  => "Đăng nhập thành công",
            "username" => $username
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Sai tài khoản hoặc mật khẩu"
        ]);
    }
} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => "Lỗi server: " . $e->getMessage()
    ]);
}
?>