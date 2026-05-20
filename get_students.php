<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

include 'db.php';

$method = $_SERVER['REQUEST_METHOD'];

if ($method == "GET") {
    // Tìm kiếm nếu có keyword
    $search = $_GET['search'] ?? '';

    if (!empty($search)) {
        $sql  = "SELECT * FROM students WHERE name LIKE ? OR id LIKE ? OR class LIKE ?";
        $stmt = $conn->prepare($sql);
        $keyword = "%$search%";
        $stmt->execute([$keyword, $keyword, $keyword]);
    } else {
        $sql  = "SELECT * FROM students ORDER BY name ASC";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
    }

    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($data);
}
?>