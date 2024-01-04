<?php
include 'connection.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $patientName = $_POST['patient_name'];
    $patientMobile = $_POST['patient_mobile'];
    $appointment_id = $_POST['appointment_id'];

    // Validate and sanitize the data (you can add more validation if needed)

    // Insert data into the bookings table
    $stmt = $conn->prepare("INSERT INTO bookings (appointment_id, patient_name, patient_mobile) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $appointment_id, $patientName, $patientMobile);

    if ($stmt->execute()) {
        echo "Appointment booked successfully!";
    } else {
        echo "Error booking appointment. Please try again.";
    }

    // Close the database connection
    $stmt->close();
    $conn->close();
} else {
    // Invalid request
    echo "Invalid request!";
}
?>