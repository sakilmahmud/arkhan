<?php
include 'connection.php';

// Check if the chamber_id parameter is set in the GET request
if (isset($_GET['chamber_id'])) {
    $chamberId = $_GET['chamber_id'];

    $today = date("Y-m-d");

    // Use prepared statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT DISTINCT appointment_date, id FROM doctor_appointment_dates WHERE appointment_date >= '$today' AND chamber_id = ? ORDER BY appointment_date");
    $stmt->bind_param("i", $chamberId);
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch dates and create an array
    $dates = array();
    while ($row = $result->fetch_assoc()) {
        $dates[] = array(
            "id" => $row['id'],
            "appointment_date" => $row['appointment_date'],
        );
    }

    // Return dates as JSON
    echo json_encode($dates);

    // Close the database connection
    $stmt->close();
    $conn->close();
} else {
    // Invalid request, return an empty array
    echo json_encode(array());
}
?>