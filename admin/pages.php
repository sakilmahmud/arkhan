<?php
// Include the database connection
include("../connection.php");

include("./login_check.php");

// Check if the delete action is triggered
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $idToDelete = $_GET['id'];

    // Perform the deletion
    $sqlDelete = "DELETE FROM pages WHERE id = ?";
    $stmtDelete = $conn->prepare($sqlDelete);
    $stmtDelete->bind_param("i", $idToDelete);
    $stmtDelete->execute();

    // Close the statement
    $stmtDelete->close();

    // Redirect to the "All Pages" page after deletion
    header("Location: pages.php");
    exit();
}

// Retrieve data from the "pages" table
$sql = "SELECT p.*, u.stored_filename FROM pages p LEFT JOIN uploads u ON p.featured_image_id = u.id";
$result = $conn->query($sql);
$pages = [];
// Check if there are any records
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $pages[] = $row;
    }
}

include_once("header.php");
?>

<main>
    <div class="container-fluid px-4 mt-5">
        <div class="mt-4 d-flex justify-content-between">
            <h1 class="">All Pages</h1>
            <div class="">
                <a href="add_page.php" class="btn btn-outline-info ">Add Page</a>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Featured Image</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Meta</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($pages as $page) {
                            $status = ($page['status'] == 1) ? 'Publish' : 'Draft';
                            echo "<tr>";
                            echo "<td>" . $page['id'] . "</td>";
                            echo "<td>" . $page['title'] . "</td>";
                            echo '<td><img src="../assets/uploads/' . $page["stored_filename"] . '" alt="Image" class="img-thumbnail" style="max-width: 100px;"></td>';
                            echo "<td>" . $page['created_at'] . "</td>";
                            echo "<td>" . $status . "</td>";
                            echo "<td><a href='add_meta.php?id=" . $page['id'] . "' class='btn btn-dark'>Add/Update Meta</a> </td>";
                            echo "<td>";
                            echo "<a href='add_page.php?id=" . $page['id'] . "' class='btn btn-warning'>Edit</a> ";
                            echo "<a href='pages.php?action=delete&id=" . $page['id'] . "' class='btn btn-danger' onclick='return confirm(\"Are you sure?\")'>Delete</a>";
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
