<?php
include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $your_name = $_POST['your_name'];
    $your_phone = $_POST['your_phone'];
    $appointment_date = $_POST['appointment_date'];

    // Validate and sanitize your input data

    // Perform the database query
    $query = "INSERT INTO bookings (patient_name, patient_mobile, appointment_date) VALUES ('$your_name', '$your_phone', '$appointment_date')";
    $result = mysqli_query($conn, $query);

    if ($result) {
        echo 'Booking successful!';
    } else {
        echo 'Error: ' . mysqli_error($conn);
    }
} else {
    echo 'Invalid request';
}
?>