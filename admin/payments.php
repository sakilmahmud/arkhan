<?php
// Include the database connection
include("../connection.php");

include("./login_check.php");
include_once("header.php");
?>

<main>
    <div class="container-fluid px-4 mt-5">
        <h1 class="mt-4">All Payments</h1>
        <div class="card mb-4">
            <div class="card-body">
                <?php
                $sql = "SELECT * FROM cardbookings";
                $result = $conn->query($sql);

                // Check if there are any records
                if ($result->num_rows > 0) {
                    echo '<table class="table table-bordered" id="dataTable">
                            <thead>
                                <tr>
                                    <th>Ticket ID</th>
                                    <th>Transaction ID</th>
                                    <th>Name</th>
                                    <th>User Details</th>
                                    <th>Price</th>
                                    <th>Total Fear</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>';

                    while ($row = $result->fetch_assoc()) {
                        echo '<tr>
                                <td>' . $row["ticketId"] . '</td>
                                <td>' . $row["transId"] . '</td>
                                <td>' . $row["fullName"] . '</td>
                                <td>
                                    Phone: ' . $row["contactNumber"] . ' <br>
                                    Email: ' . $row["email"] . ' <br>
                                    Address: ' . $row["address"] . '
                                </td>
                                <td>£' . $row["price"] . '</td>
                                <td>£' . $row["totalFear"] . '</td>
                                <td>' . $row["pDate"] . '</td>
                            </tr>';
                    }

                    echo '</tbody></table>';
                } else {
                    echo "No payments found.";
                }
                ?>
            </div>
        </div>
    </div>
</main>

<?php include_once("footer.php"); ?>