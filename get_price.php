<?php
require_once 'config.php';

header('Content-Type: application/json');

if (isset($_GET['from']) && isset($_GET['to']) && !empty($_GET['from']) && !empty($_GET['to'])) {
    $from = validateInput($_GET['from']);
    $to = validateInput($_GET['to']);
    
    try {
        $stmt = $conn->prepare("SELECT price FROM bus_routes WHERE departure_location = ? AND arrival_location = ?");
        $stmt->execute([$from, $to]);
        $route = $stmt->fetch();

        if ($route) {
            echo json_encode(['price' => $route['price']]);
        } else {
            echo json_encode(['error' => 'Route not found']);
        }
    } catch(PDOException $e) {
        error_log("Error fetching price: " . $e->getMessage());
        echo json_encode(['error' => 'Unable to fetch price']);
    }
} else {
    echo json_encode(['error' => 'Departure or arrival location not provided']);
}
?>