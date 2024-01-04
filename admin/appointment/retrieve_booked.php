<?php
// Retrieve data from the "doctor_appointment_dates" table
include("../../connection.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selectedDate'])) {
    $selectedDate = $_POST['selectedDate'];
    $sql = "SELECT TIME_FORMAT(`appointment_time`, '%h:%i %p') AS `appointment_time`, `name` FROM `doctor_appointment_dates`  JOIN `chambers` ON `doctor_appointment_dates`.`chamber_id` = `chambers`.`id`
            WHERE `doctor_appointment_dates`.`appointment_date` = '$selectedDate' AND `doctor_appointment_dates`.`status` = 1 ORDER BY `doctor_appointment_dates`.`appointment_time` ASC";
    //echo $sql;
    $result = $conn->query($sql);
    $appointments = [];

    // Check if there are any records
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $appointments[] = $row;
        }
        echo json_encode(['success' => true, 'appointments' => $appointments]);
        exit;
    }
}

echo json_encode(['success' => false]);
