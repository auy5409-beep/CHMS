<?php
require "db.php";

$body = json_decode(file_get_contents("php://input"), true);
$id = $body["id"];

if (!$id) {
    echo json_encode(["success" => false, "error" => "Student ID is required"]);
    exit;
}

$stmt = $conn->prepare("DELETE FROM students WHERE id = ?");
$stmt->bind_param("s", $id);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "error" => $stmt->error]);
}

$stmt->close();
$conn->close();
?>
