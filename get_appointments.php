<?php
include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $booking_date = $_POST['booking_date'];

    // Perform the database query to get booked appointments for the specified date
    $query = "SELECT patient_name FROM bookings WHERE DATE(appointment_date) = '$booking_date' ORDER BY created_at";
    $result = mysqli_query($conn, $query);

    // Check if the query was successful
    if ($result) {
        echo '<ul>';
        $order = 0;
        while ($row = mysqli_fetch_assoc($result)) {
            $order++;
            // Customize this part based on your actual column names
            $patient_name = $row['patient_name'];

            // Display booked appointments data
            echo '<ol>' . $order . ' - ' . $patient_name . '</ol>';
        }
        echo '</ul>';
    } else {
        // Handle the error if the query fails
        echo 'Error: ' . mysqli_error($conn);
    }
} else {
    // Return an error for invalid requests
    echo 'Invalid request';
}

// Close the database connection
mysqli_close($conn);
?>