<?php
// Database connection settings
$servername = "your-rds-endpoint.amazonaws.com";
$username = "admin";
$password = "YourPassword";
$dbname = "bakery_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["message" => "Database connection failed"]);
    exit;
}

// Get JSON input from request
$data = json_decode(file_get_contents('php://input'), true);

// Validate input
if (!isset($data['item']) || empty($data['item'])) {
    http_response_code(400);
    echo json_encode(["message" => "Invalid order data"]);
    exit;
}

// Prepare SQL query
$itemName = $conn->real_escape_string($data['item']);
$orderTime = date('Y-m-d H:i:s', strtotime($data['timestamp']));

$sql = "INSERT INTO orders (item_name, order_time) VALUES ('$itemName', '$orderTime')";

if ($conn->query($sql) === TRUE) {
    http_response_code(200);
    echo json_encode(["message" => "Order placed successfully"]);
} else {
    http_response_code(500);
    echo json_encode(["message" => "Failed to place order"]);
}

// Close connection
$conn->close();
?>
