<?php
require_once 'config.php';

header('Content-Type: application/json');

if (isset($_GET['from']) && !empty($_GET['from'])) {
    $from = validateInput($_GET['from']);
    
    try {
        $stmt = $conn->prepare("SELECT DISTINCT arrival_location FROM bus_routes WHERE departure_location = ? ORDER BY arrival_location");
        $stmt->execute([$from]);
        $destinations = $stmt->fetchAll(PDO::FETCH_COLUMN);

        echo json_encode(['destinations' => $destinations]);
    } catch(PDOException $e) {
        error_log("Error fetching destinations: " . $e->getMessage());
        echo json_encode(['error' => 'Unable to fetch destinations']);
    }
} else {
    echo json_encode(['error' => 'Departure location not provided']);
}
?>