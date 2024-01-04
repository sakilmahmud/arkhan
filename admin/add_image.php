<?php
// Include the database connection
include("../connection.php");

include("./login_check.php");
include_once("header.php");

// Variables to store form input values
$batch_no = uniqid(); // Generate a unique batch number
$upload_id = ""; // Array to store upload IDs
$file_type = 'image';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Handle file uploads
    if (isset($_FILES['images']) && count($_FILES['images']['error']) > 0) {
        foreach ($_FILES['images']['error'] as $key => $error) {
            if ($error == 0) {
                $title = $_FILES['images']['name'][$key];
                // Handle file upload for each image
                $upload_id = handleFileUpload($title, $_FILES['images']['tmp_name'][$key], $batch_no);

                // Add new galleries
                addGalleries($title, $upload_id, $file_type);
            }
        }
    }
    // Redirect back to the images page
    header("Location: images.php");
    exit();

}

// Function to handle file upload
function handleFileUpload($original_filename, $tmp_name, $batch_no)
{
    global $conn;

    // Handle file upload logic here (move_uploaded_file, generate unique filename, etc.)
    $uploadDir = "../assets/uploads/"; // Adjust the path based on your actual image storage location

    $file_type = pathinfo($original_filename, PATHINFO_EXTENSION);
    $stored_filename = uniqid() . "." . $file_type; // Generate a unique filename

    $targetPath = $uploadDir . $stored_filename;

    // Move the uploaded file to the specified directory
    if (move_uploaded_file($tmp_name, $targetPath)) {
        // Insert file information into the uploads table
        $insertSql = "INSERT INTO uploads (original_filename, stored_filename, file_type, file_size, batch_no) 
                      VALUES ('$original_filename', '$stored_filename', '$file_type', " . filesize($targetPath) . ", '$batch_no')";

        if ($conn->query($insertSql)) {
            return $conn->insert_id;
        } else {
            echo "Error adding file information: " . $conn->error;
            return 0;
        }
    } else {
        echo "Error moving uploaded file.";
        return 0;
    }
}

// Function to add new galleries
function addGalleries($title, $upload_id, $file_type)
{
    global $conn;

    // Remove file extension from the title
    $titleWithoutExtension = pathinfo($title, PATHINFO_FILENAME);

    // Insert new gallery into the galleries table
    $insertSql = "INSERT INTO galleries (title, upload_id, file_type)
                      VALUES ('$titleWithoutExtension', $upload_id, '$file_type')";

    if ($conn->query($insertSql)) {
        echo "added";
    } else {
        echo "Error adding gallery: " . $conn->error;
    }
}


?>

<main>
    <div class="container-fluid px-4 mt-5">
        <div class="mt-4">
            <h1 class="">Add Image</h1>
        </div>
        <div class="card mb-4">
            <div class="card-body">
                <form action="add_image.php" method="POST" enctype="multipart/form-data">
                    <!-- Add Image Form -->
                    <label for="images">Images:</label>
                    <input type="file" class="form-control-file mb-2" name="images[]" accept="image/*" multiple
                        required>

                    <button type="submit" class="btn btn-primary">Upload</button>
                </form>
            </div>
        </div>
    </div>
</main>

<?php include_once("footer.php"); ?>