<?php
// Include the database connection
include("../connection.php");

include("./login_check.php");
include_once("header.php");
// Check if the action parameter is set
if (isset($_GET['action']) && $_GET['action'] === 'remove') {
    // Check if the ID parameter is set
    if (isset($_GET['id'])) {
        $image_id = $_GET['id'];

        // Remove Action (Delete record)
        deleteImage($image_id);

        // Redirect back to the images page
        header("Location: images.php");
        exit();
    }
}

// Function to delete an image
function deleteImage($image_id)
{
    global $conn;

    // Retrieve stored filename
    $filenameQuery = "SELECT stored_filename FROM uploads WHERE id = (SELECT upload_id FROM galleries WHERE id = $image_id)";
    $filenameResult = $conn->query($filenameQuery);

    if ($filenameResult->num_rows > 0) {
        $filenameRow = $filenameResult->fetch_assoc();
        $stored_filename = $filenameRow['stored_filename'];

        // Remove the file from the uploads directory
        $uploadDir = "../assets/uploads/"; // Adjust the path based on your actual image storage location
        $filePath = $uploadDir . $stored_filename;

        if (file_exists($filePath)) {
            unlink($filePath); // Remove the file from the server
        }

        // Delete the record from the galleries table
        $deleteSql = "DELETE FROM galleries WHERE id = $image_id";
        $result = $conn->query($deleteSql);

        if (!$result) {
            echo "Error deleting image record: " . $conn->error;
        }
    } else {
        echo "Error retrieving stored filename: " . $conn->error;
    }
}
// Retrieve images from galleries table where file_type is "image"
$query = "SELECT g.id, g.title, u.stored_filename FROM galleries g
          INNER JOIN uploads u ON g.upload_id = u.id
          WHERE g.file_type = 'image'";
$result = $conn->query($query);

?>

<main>
    <div class="container-fluid px-4 mt-5">
        <div class="mt-4 d-flex justify-content-between">
            <h1 class="">Images Gallery</h1>
            <a href="add_image.php" class="btn btn-outline-info ">Add Images</a>
        </div>
        <div class="card mb-4">
            <div class="card-body">
                <table class="table table-bordered" id="dataTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Image</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Display images in a table
                        while ($row = $result->fetch_assoc()) {
                            echo '<tr>
                                    <td>' . $row["id"] . '</td>
                                    <td>' . $row["title"] . '</td>
                                    <td><img src="../assets/uploads/' . $row["stored_filename"] . '" alt="Image" class="img-thumbnail" style="max-width: 100px;"></td>
                                    <td><a href="images.php?action=remove&id=' . $row["id"] . '" class="btn btn-danger" onclick="return confirm(\'Are you sure you want to delete?\')">Delete</a></td>
                                  </tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<?php include_once("footer.php"); ?>