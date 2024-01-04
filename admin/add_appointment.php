<?php
// Include the database connection
include("../connection.php");

include("./login_check.php");

$today = date("Y-m-d");

// Retrieve data from the "chambers" table
$sql = "SELECT `id`, `name` FROM chambers WHERE `status` = 1";
$result = $conn->query($sql);
$chambers = [];

// Check if there are any records
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $chambers[] = array(
            "id" => $row['id'],
            "name" => $row['name'],
        );
    }
}

// Retrieve data from the "doctor_appointment_dates" table
$sql = "SELECT COUNT(`chamber_id`) AS `schedule`, `appointment_date`, GROUP_CONCAT(TIME_FORMAT (`appointment_time`, '%h:%i %p') ORDER BY `appointment_time` ASC SEPARATOR '\n') AS `appointment_times` FROM `doctor_appointment_dates` WHERE `appointment_date` >= '$today' AND `status` = 1 GROUP BY appointment_date";
//echo $sql;
$result = $conn->query($sql);
$all_schedules = [];

// Check if there are any records
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $formattedTime = $row['appointment_times'];

        $txt = $formattedTime;
        $all_schedules[$row['appointment_date']] = "<br> <span class='small'>$txt</span>";
    }
}


include_once("header.php");
?>

<main>
    <div class="container-fluid px-4 mt-5">
        <div class="mt-4">
            <h1 class="">Schedule Appointments</h1>
        </div>
        <div class="card mb-4">
            <div class="card-body">
                <div class="ap-calendar">
                    <?php
                    // Get the current date
                    $today = date("Y-m-d");

                    // Create an array to map day numbers to their corresponding names
                    $dayNames = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];

                    $monthNames = [];

                    // Create a loop to generate dates from today for a month
                    for ($i = 0; $i < 45; $i++) {
                        $day = date("j", strtotime($today . " +$i day"));
                        $date = date("Y-m-d", strtotime($today . " +$i day"));
                        $dayOfWeek = date("w", strtotime($today . " +$i day")); // Get the day of the week as a number (0 = Sun, 1 = Mon, etc.)
                        $dayName = $dayNames[$dayOfWeek]; // Get the day name from the array
                    
                        $class = strtolower(date("F", strtotime($date)));
                        $monthName = date("jS, F", strtotime($date));
                        if (!in_array($class, $monthNames)) {
                            $monthNames[] = $class;
                        }

                        $schedule = "";
                        $hasAppointment = "";
                        if (isset($all_schedules[$date])) {

                            $schedule = $all_schedules[$date];

                            $hasAppointment = "hasAppointment";
                        }

                        echo "<div class='day $class $hasAppointment' data-date='$date' data-day='$day' title='$monthName'>$dayName <br> $day $schedule</div>";
                    }
                    ?>
                </div>
                <div class="months">
                    <?php
                    if (!empty($monthNames)) {
                        foreach ($monthNames as $month) {
                            echo "<div class='month $month'>" . strtoupper($month) . "</div>";
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</main>
<!-- Add this HTML modal structure to your page -->
<div class="modal" id="chamberModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Choose Chambers</h5>
                <button type="button" class="close modal_close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="chamberModalBody">
                <form action="" id="appointment_frm">
                    <select name="chamber_id" id="chamber_id" required>
                        <option value="">Select a Chamber</option>
                        <?php
                        if (!empty($chambers)) {
                            foreach ($chambers as $chamber) {
                                echo "<option value='" . $chamber['id'] . "'>" . $chamber['name'] . "</option>";
                            }
                        }
                        ?>
                    </select>
                    <div class="time-select">
                        <select class="form-control" name="time_slot" id="time_slot" required>
                            <?php
                            for ($hour = 8; $hour <= 20; $hour++) {
                                $time = sprintf('%02d:00', $hour);
                                echo "<option value=\"$time\">$time</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <input type="hidden" name="date" id="selected_date">
                    <button type="button" class="btn btn-primary mt-3" id="saveAppointmentBtn">Save Appointment</button>
                </form>
                <div class="show_booked_appointments mt-3 d-none">
                    <h5>Booked Appointments</h5>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Time</th>
                                <th>Chamber</th>
                            </tr>
                        </thead>
                        <tbody id="appointmentsTableBody"></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary modal_close" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Function to retrieve and display booked appointments for the selected date
    function showBookedAppointments(selectedDate) {
        // Make an AJAX request to retrieve booked appointments for the selected date
        $.ajax({
            url: "appointment/retrieve_booked.php", // Adjust the actual path
            type: "POST",
            data: { selectedDate: selectedDate },
            dataType: "JSON",
            success: function (response) {
                if (response.success) {
                    // Appointments retrieved successfully
                    displayBookedAppointments(response.appointments);
                } else {
                    // Show an error message if the retrieval fails
                    console.error('Failed to retrieve booked appointments');
                }
            },
            error: function () {
                // Show an error message if there is an issue with the AJAX request
                console.error('Failed to retrieve booked appointments');
            }
        });
    }

    // Function to display booked appointments in the table
    function displayBookedAppointments(appointments) {

        let $show_booked_appointments = $('.show_booked_appointments');
        $show_booked_appointments.removeClass("d-none");
        const $appointmentsTableBody = $('#appointmentsTableBody');

        $appointmentsTableBody.empty();

        // Loop through the appointments and append rows to the table
        appointments.forEach(appointment => {
            $appointmentsTableBody.append(`
                <tr>
                    <td>${appointment.appointment_time}</td>
                    <td>${appointment.name}</td>
                </tr>
            `);
        });
    }

    $('body').on('click', '.modal_close', function () {
        $('#chamberModal').modal('hide');
        let $show_booked_appointments = $('.show_booked_appointments');
        $show_booked_appointments.addClass("d-none");
    })
    $('body').on('click', '.day', function () {
        $('#appointment_frm')[0].reset();

        const $day = $(this);
        const selectedDate = $day.data('date');
        const modalTitle = document.querySelector(".modal-title");
        const selected_date = document.querySelector("#selected_date");
        modalTitle.innerHTML = "Choose Chambers on " + selectedDate;
        selected_date.value = selectedDate;
        // Show the modal when a date is clicked
        $('#chamberModal').modal('show');

        // Retrieve and show booked appointments for the selected date
        showBookedAppointments(selectedDate);
    });

    $('#saveAppointmentBtn').on('click', function () {
        const selectedDate = $('#selected_date').val();
        const selectedTime = $('#time_slot').val();
        const selectedChamber = $('#chamber_id').val();
        if (selectedChamber == false) {
            alert("please choose a chamber first!");
            return false;
        }
        // Validate selectedChamber and selectedTime if needed

        saveDateToDatabase(selectedDate, selectedTime, selectedChamber);
    });

    function saveDateToDatabase(date, time, chamber_id) {
        // Make an AJAX request to save the date, time, and chamber ID to the database
        $.ajax({
            url: "appointment/add.php",
            type: "POST",
            data: { date: date, time_slot: time, chamberId: chamber_id },
            dataType: "JSON",
            success: function (response) {
                if (response.success) {
                    // Appointment saved successfully
                    console.log('Appointment saved successfully');
                    // Refresh appointments on the calendar for the selected day
                    retrieveAppointments(date);
                    // Close the modal
                    $('#chamberModal').modal('hide');
                } else {
                    // Show an error message if the save fails
                    console.error('Failed to save appointment');
                }
            },
            error: function () {
                // Show an error message if there is an issue with the AJAX request
                console.error('Failed to save appointment');
            }
        });
    }


    function retrieveAppointments(selectedDate) {
        // Make an AJAX request to retrieve appointments for the selected date
        $.ajax({
            url: "appointment/retrieve.php",
            type: "POST",
            data: { selectedDate: selectedDate },
            dataType: "JSON",
            success: function (response) {
                if (response.success) {
                    // Appointments retrieved successfully
                    displayAppointments(response.appointments);
                } else {
                    // Show an error message if the retrieval fails
                    console.error('Failed to retrieve appointments');
                }
            },
            error: function () {
                // Show an error message if there is an issue with the AJAX request
                console.error('Failed to retrieve appointments');
            }
        });
    }

    function displayAppointments(appointments) {
        // Loop through the appointments and display them on the calendar
        appointments.forEach(appointment => {
            const $day = $(`.day[data-date='${appointment.appointment_date}']`);
            $day.addClass("hasAppointment");
            $day.append(`<br> <span class='small'>${appointment.appointment_time}</small>`);
        });
    }
</script>

<?php include_once("footer.php"); ?>