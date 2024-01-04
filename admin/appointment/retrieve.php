<?php
include("../../connection.php");

$selectedDate = $_POST['selectedDate'];

$sqlAppointments = "SELECT * FROM doctor_appointment_dates WHERE appointment_date = '$selectedDate'";
$resultAppointments = $conn->query($sqlAppointments);
$appointments = [];

if ($resultAppointments->num_rows > 0) {
    while ($rowAppointment = $resultAppointments->fetch_assoc()) {
        $formattedTime = date("h:i A", strtotime($rowAppointment['appointment_time']));
        $appointments[] = array(
            "id" => $rowAppointment['id'],
            "chamber_id" => $rowAppointment['chamber_id'],
            "appointment_time" => $formattedTime,
            "appointment_date" => $rowAppointment['appointment_date'],
        );
    }
}

$response = [
    'success' => true,
    'appointments' => $appointments,
];

echo json_encode($response);
?>