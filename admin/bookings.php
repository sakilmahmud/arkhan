<?php
// Include the database connection
include("../connection.php");

include("./login_check.php");

// Check if the action parameter is set
if (isset($_GET['action'])) {
    $action = $_GET['action'];

    // Check if the ID parameter is set
    if (isset($_GET['id'])) {
        $booking_id = $_GET['id'];

        // Confirm Action
        if ($action === 'confirm') {
            updateBookingStatus($booking_id, 1); // 1 represents "Confirm"
        }
        // Reject Action
        elseif ($action === 'reject') {
            updateBookingStatus($booking_id, 2); // 2 represents "Reject"
        }
        // Remove Action (Delete record)
        elseif ($action === 'remove') {
            deleteBooking($booking_id);
        }
        // Add more actions if needed

        // Redirect back to the bookings page
        header("Location: bookings.php");
        exit();
    }
}

function updateBookingStatus($booking_id, $status)
{
    global $conn;

    // Update the status in the database
    $updateSql = "UPDATE bookings SET status = $status WHERE id = $booking_id";
    $result = $conn->query($updateSql);

    if (!$result) {
        $msg = "Error updating status: " . $conn->error;
    }
}

function deleteBooking($booking_id)
{
    global $conn;

    // Delete the record from the database
    $deleteSql = "DELETE FROM bookings WHERE id = $booking_id";
    $result = $conn->query($deleteSql);

    if (!$result) {
        $msg = "Error deleting record: " . $conn->error;
    }
}

function getStatusLabel($status)
{
    switch ($status) {
        case 0:
            return 'Pending';
        case 1:
            return 'Confirmed';
        case 2:
            return 'Rejected';
        case 3:
            return 'Appointment Done';
        default:
            return 'Unknown';
    }
}

include_once("header.php");

?>

<main>
    <div class="container-fluid px-4 mt-5">

        <div class="mt-4 d-flex justify-content-between">
            <h1 class="">All Bookings</h1>
            <div class="">
                <a href="bookings.php?action=past" class="btn btn-outline-dark">Past
                    Bookings</a>
                <a href="add_booking.php" class="btn btn-outline-info ">Add
                    Booking</a>
            </div>

        </div>
        <div class="card mb-4">
            <div class="card-body">
                <?php

                // Get the current date
                $today = date('Y-m-d');
                if (isset($_GET['action']) && $_GET['action'] == "past") {
                    $where = "WHERE dad.appointment_date < '$today'";
                } else {
                    $where = "WHERE dad.appointment_date >= '$today'";
                }
                // Retrieve data from the "bookings" table
                $sql = "SELECT b.id, b.patient_name, b.patient_mobile, b.created_at, b.status, dad.appointment_date, TIME_FORMAT(dad.appointment_time, '%h:%i %p') AS appointment_time, c.name as chamber_name FROM bookings b JOIN doctor_appointment_dates dad ON b.appointment_id = dad.id JOIN chambers c ON dad.chamber_id = c.id $where";
                //$result = $conn->query($sql);";
                //echo $sql;
                $result = $conn->query($sql);

                // Check if there are any records
                if ($result->num_rows > 0) {
                    echo '<table class="table table-bordered" id="dataTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Mobile</th>
                                    <th>Chamber</th>
                                    <th>Appointment Date</th>
                                    <th>Booking Date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>';

                    while ($row = $result->fetch_assoc()) {
                        $actionButtons = '';

                        // Display action buttons only for future appointments
                        if ($row["appointment_date"] >= $today && $row["status"] == 0) {
                            $actionButtons = '
                                        <hr>
                                        <a href="bookings.php?action=confirm&id=' . $row["id"] . '" class="btn btn-success" onclick="return confirm(\'Are you sure you want to confirm?\')">Confirm</a>
                                        <a href="bookings.php?action=reject&id=' . $row["id"] . '" class="btn btn-warning" onclick="return confirm(\'Are you sure you want to reject?\')">Reject</a>';
                        }

                        echo '<tr>
                                    <td>' . $row["id"] . '</td>
                                    <td>' . $row["patient_name"] . '</td>
                                    <td>' . $row["patient_mobile"] . '</td>
                                    <td>' . $row["chamber_name"] . '</td>
                                    <td>' . $row["appointment_date"] . '<br>' . $row["appointment_time"] . '</td>
                                    <td>' . $row["created_at"] . '</td>
                                    <td>' . getStatusLabel($row["status"]) . ' ' . $actionButtons . '</td>
                                    <td>
                                        <a href="add_booking.php?id=' . $row["id"] . '" class="btn btn-primary">Edit</a>
                                        <a href="bookings.php?action=remove&id=' . $row["id"] . '" class="btn btn-danger" onclick="return confirm(\'Are you sure you want to delete?\')">Delete</a>
                                    </td>
                                </tr>';
                    }

                    echo '</tbody></table>';
                } else {
                    echo "No bookings found.";
                }
                ?>
            </div>
        </div>
    </div>
</main>

<?php include_once("footer.php"); ?>