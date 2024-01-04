<?php
// Include the database connection
include("../connection.php");

include("./login_check.php");

$msg = "";

// Check if the action parameter is set
if (isset($_GET['action'])) {
    $action = $_GET['action'];

    // Check if the ID parameter is set
    if (isset($_GET['id'])) {
        $booking_id = $_GET['id'];

        if ($action === 'remove') {
            deleteBooking($booking_id);
        }
        // Add more actions if needed

        // Redirect back to the bookings page
        header("Location: appointments.php");
        exit();
    }
}

function deleteBooking($booking_id)
{
    global $conn;

    // Delete the record from the database
    $deleteSql = "DELETE FROM `doctor_appointment_dates` WHERE id = $booking_id";
    $result = $conn->query($deleteSql);

    if (!$result) {
        $msg = "Error deleting record: " . $conn->error;
    }
}

include_once("header.php");

?>

<main>
    <div class="container-fluid px-4 mt-5">

        <div class="mt-4 d-flex justify-content-between">
            <h1 class="">Upcoming Appointments</h1>
            <div class="">
                <a href="appointments.php?action=past" class="btn btn-outline-dark">Past
                    Appointments</a>
                <a href="add_appointment.php" class="btn btn-outline-info ">Add
                    Appointments</a>
            </div>

        </div>
        <div class="card mb-4">
            <div class="card-body">
                <?php
                echo $msg;
                // Get the current date
                $today = date('Y-m-d');
                if (isset($_GET['action']) && $_GET['action'] == "past") {
                    $where = "WHERE DAD.appointment_date < '$today'";
                } else {
                    $where = "WHERE DAD.appointment_date >= '$today'";
                }
                // Retrieve data from the "doctor_appointment_dates" table
                $sql = "SELECT DAD.*, C.name, C.address FROM `doctor_appointment_dates` AS DAD INNER JOIN `chambers` AS C ON DAD.chamber_id = C.id $where";
                //echo $sql;
                $result = $conn->query($sql);

                // Check if there are any records
                if ($result->num_rows > 0) {
                    echo '<table class="table table-bordered" id="dataTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Address</th>
                                    <th>Appointment Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>';

                    while ($row = $result->fetch_assoc()) {
                        $actionButtons = '';


                        echo '<tr>
                                        <td>' . $row["id"] . '</td>
                                        <td>' . $row["name"] . '</td>
                                        <td>' . $row["address"] . '</td>
                                        <td>' . $row["appointment_date"] . '</td>
                                        <td>
                                            <a href="add_appointment.php" class="btn btn-primary">Update</a>
                                            <a href="appointments.php?action=remove&id=' . $row["id"] . '" class="btn btn-danger" onclick="return confirm(\'Are you sure you want to delete?\')">Delete</a>
                                        </td>
                                    </tr>';
                    }

                    echo '</tbody></table>';
                } else {
                    echo "No appointments found.";
                }
                ?>
            </div>
        </div>
    </div>
</main>

<?php include_once("footer.php"); ?>