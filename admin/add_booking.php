<?php
// Include the database connection
include("../connection.php");

include("./login_check.php");

// Retrieve data from the "chambers" table
$sql = "SELECT * FROM chambers WHERE `status` = 1";
$result = $conn->query($sql);
$chambers = [];
// Check if there are any records
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $chambers[] = $row;
    }
}

// Variables to store form input values
$patient_name = "";
$patient_mobile = "";
$chamber_id = ""; // Add chamber_id variable
$appointment_id = "";
$status = "";

if (isset($_GET['id'])) {
    $booking_id = $_GET['id'];


    // Retrieve data from the "chambers" table
    $sql = "SELECT bookings.*, doctor_appointment_dates.chamber_id, doctor_appointment_dates.appointment_date, doctor_appointment_dates.appointment_time FROM bookings
    INNER JOIN doctor_appointment_dates ON bookings.appointment_id = doctor_appointment_dates.id
    WHERE bookings.id = $booking_id";

    $result = $conn->query($sql);
    // Check if there are any records
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $patient_name = $row['patient_name'];
        $patient_mobile = $row['patient_mobile'];
        $chamber_id = $row['chamber_id'];
        $appointment_id = $row['appointment_id'];
        $status = $row['status'];
    }



}
// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $patient_name = $_POST['patient_name'];
    $patient_mobile = $_POST['patient_mobile'];
    $chamber_id = $_POST['chamber_id']; // Add this line
    $appointment_id = $_POST['appointment_id'];
    $status = $_POST['status'];

    // Check if it's a new booking or an edit (based on the presence of the 'id' parameter)
    if (isset($_POST['id'])) {
        // Edit existing booking
        $booking_id = $_POST['id'];
        editBooking($booking_id, $patient_name, $patient_mobile, $appointment_id, $status); // Modify this line
    } else {
        // Add new booking
        addBooking($patient_name, $patient_mobile, $appointment_id, $status); // Modify this line
    }

    // Redirect back to the bookings page
    header("Location: bookings.php");
    exit();
}

// Function to add a new booking
function addBooking($patient_name, $patient_mobile, $appointment_id, $status) // Modify this line
{
    global $conn;

    // Insert new booking into the database
    $insertSql = "INSERT INTO bookings (patient_name, patient_mobile, appointment_id, status) 
                  VALUES ('$patient_name', '$patient_mobile', '$appointment_id', $status)";

    if ($conn->query($insertSql)) {
        echo "Booking added successfully!";
    } else {
        echo "Error adding booking: " . $conn->error;
    }
}

// Function to edit an existing booking
function editBooking($booking_id, $patient_name, $patient_mobile, $appointment_id, $status) // Modify this line
{
    global $conn;

    // Update the existing booking in the database
    $updateSql = "UPDATE bookings
                  SET patient_name = '$patient_name',
                      patient_mobile = '$patient_mobile',
                      appointment_id = '$appointment_id',
                      status = $status
                  WHERE id = $booking_id";

    if ($conn->query($updateSql)) {
        echo "Booking updated successfully!";
    } else {
        echo "Error updating booking: " . $conn->error;
    }
}


include_once("header.php");
?>

<main>
    <div class="container-fluid px-4 mt-5">
        <div class="mt-4">
            <h1 class="">Add/Edit Booking</h1>
        </div>
        <div class="card mb-4">
            <div class="card-body">
                <form action="add_booking.php" method="POST">
                    <!-- Add/Edit Booking Form -->
                    <?php if (isset($_GET['id'])) { ?>
                        <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
                    <?php } ?>
                    <input type="hidden" id="selected_appointment_id" value="<?php echo $appointment_id; ?>">

                    <label for="patient_name">Patient Name:</label>
                    <input type="text" class="form-control mb-2" name="patient_name"
                        value="<?php echo $patient_name; ?>" required>

                    <label for="patient_mobile">Patient Mobile:</label>
                    <input type="text" class="form-control mb-2" name="patient_mobile"
                        value="<?php echo $patient_mobile; ?>" required>

                    <label for="chamber_id">Chamber:</label>
                    <select required="required" class="form-control mb-2" name="chamber_id" id="chamber_id">
                        <option value="">Select Chamber</option>
                        <?php
                        // Populate the dropdown with chambers
                        foreach ($chambers as $chamber) {
                            $selected = ($chamber['id'] == $chamber_id) ? 'selected' : '';
                            echo "<option value='{$chamber['id']}' $selected>{$chamber['name']}</option>";
                        }
                        ?>
                    </select>

                    <label for="appointment_id">Appointment Date:</label>
                    <select required="required" class="form-control mb-2" name="appointment_id" id="appointment_date">
                        <option value="">Select Date</option>
                        <?php
                        // Fetch and populate the dropdown with available dates (modify as needed)
                        // Note: You may need to fetch and assign values for $appointment_dates
                        // For demonstration purposes, I'm assuming an empty array
                        $appointment_dates = [];
                        foreach ($appointment_dates as $date) {
                            $selected = ($date['id'] == $appointment_id) ? 'selected' : '';
                            echo "<option value='{$date['id']}' $selected>{$date['appointment_date']}</option>";
                        }
                        ?>
                    </select>

                    <label for="status">Status:</label>
                    <select class="form-control mb-2" name="status" required>
                        <option value="0" <?php echo $status == 0 ? 'selected' : ''; ?>>Pending</option>
                        <option value="1" <?php echo $status == 1 ? 'selected' : ''; ?>>Confirm</option>
                        <option value="2" <?php echo $status == 2 ? 'selected' : ''; ?>>Reject</option>
                        <option value="3" <?php echo $status == 3 ? 'selected' : ''; ?>>Appointment Done</option>
                    </select>

                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
</main>

<script>
    $(document).ready(function () {
        // Function to load available dates based on selected chamber
        function loadDates() {
            var chamberId = $("#chamber_id").val();
            var appointmentId = $("#selected_appointment_id").val();

            // Check if a chamber is selected
            if (chamberId) {
                // Use AJAX to fetch available dates for the selected chamber
                $.ajax({
                    type: "GET",
                    url: "../get_available_dates.php",
                    data: { chamber_id: chamberId },
                    dataType: "json",
                    success: function (dates) {
                        var dateDropdown = $("#appointment_date");
                        console.log(appointmentId);
                        // Clear existing options
                        dateDropdown.empty().append("<option value=''>Select Date</option>");

                        // Populate the dropdown with available dates
                        $.each(dates, function (index, date) {
                            var option = $("<option></option>")
                                .attr("value", date.id)
                                .text(date.appointment_date);

                            if (date.id == appointmentId) {
                                option.attr("selected", "selected");
                            }

                            dateDropdown.append(option);
                        });

                    }
                });
            }
        }


        // Attach event listener to the chamber dropdown
        $("#chamber_id").change(loadDates);

        // Call the loadDates function on page load to populate dates if chamber is selected
        loadDates();
    });
</script>
<?php include_once("footer.php"); ?>