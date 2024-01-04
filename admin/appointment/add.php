<?php
// Include the database connection
include("../../connection.php");

include("../login_check.php");

// Check if the necessary data is provided in the POST request
if (isset($_POST['date']) && isset($_POST['time_slot']) && isset($_POST['chamberId'])) {
    $selectedDate = $_POST['date'];
    $selectedTime = $_POST['time_slot'];
    $chamberId = $_POST['chamberId'];

    // Add your validation logic here if needed

    // Insert the appointment into the database
    $sql = "INSERT INTO doctor_appointment_dates (chamber_id, appointment_date, appointment_time) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);

    // Assuming you have a doctor_id value, replace it with the actual doctor_id
    $doctorId = 1; // Replace with the actual doctor_id

    // Bind parameters
    $stmt->bind_param("iss", $chamberId, $selectedDate, $selectedTime);

    // Execute the statement
    $result = $stmt->execute();

    // Check if the insertion was successful
    if ($result) {
        $response = array('success' => true);
    } else {
        $response = array('success' => false);
    }

    // Close the statement
    $stmt->close();

    // Return the response as JSON
    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    // If required data is not provided, return an error response
    $response = array('success' => false);
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>