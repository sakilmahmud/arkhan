<?php
// Include the database connection
include("../connection.php");

include("./login_check.php");

// Check if the delete action is triggered
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $idToDelete = $_GET['id'];

    // Perform the deletion
    $sqlDelete = "DELETE FROM chambers WHERE id = ?";
    $stmtDelete = $conn->prepare($sqlDelete);
    $stmtDelete->bind_param("i", $idToDelete);
    $stmtDelete->execute();

    // Close the statement
    $stmtDelete->close();

    // Redirect to the "All Chambers" page after deletion
    header("Location: chambers.php");
    exit();
}

// Retrieve data from the "chambers" table
$sql = "SELECT * FROM chambers";
$result = $conn->query($sql);
$chambers = [];
// Check if there are any records
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $chambers[] = $row;
    }
}

include_once("header.php");
?>

<main>
    <div class="container-fluid px-4 mt-5">
        <div class="mt-4 d-flex justify-content-between">
            <h1 class="">All Chambers</h1>
            <div class="">
                <a href="add_chamber.php" class="btn btn-outline-info ">Add
                    Chamber</a>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Address</th>
                            <th>Mobile</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($chambers as $chamber) {
                            echo "<tr>";
                            echo "<td>" . $chamber['id'] . "</td>";
                            echo "<td>" . $chamber['name'] . "</td>";
                            echo "<td>" . $chamber['address'] . "</td>";
                            echo "<td>" . $chamber['mobile'] . "</td>";
                            echo "<td>";
                            echo "<a href='add_chamber.php?id=" . $chamber['id'] . "' class='btn btn-warning'>Edit</a> ";
                            echo "<a href='chambers.php?action=delete&id=" . $chamber['id'] . "' class='btn btn-danger' onclick='return confirm(\"Are you sure?\")'>Delete</a>";
                            echo "</td>";
                            // Add more columns if needed
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<?php include_once("footer.php"); ?>