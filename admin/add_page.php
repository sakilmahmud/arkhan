<?php
// Include the database connection
include("../connection.php");

include("./login_check.php");

$msg = "";
$batch_no = uniqid(); // Generate a unique batch number
$upload_id = 0; // Array to store upload IDs
$file_type = 'image';

// Variables to store form input values
$id = "";
$title = "";
$content = "";
$featured_image = "";
$page_type = "";
$status = "";

if(isset($_GET['id']) && $_GET['id'] !=""){
    global $conn;

    $page_id = $_GET['id'];
    $row = [];
    
    // Retrieve data from the "pages" table
    $sql = "SELECT p.*, u.stored_filename FROM pages p INNER JOIN uploads u ON p.featured_image_id = u.id WHERE p.id = $page_id";
    $result = $conn->query($sql);
    $pages = [];
    // Check if there are any records
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        //print_r($row);
        if(!empty($row)){
            $title = $row['title'];
            $content = $row['content'];
            $featured_image = $row['stored_filename'];
            $page_type = $row['page_type'];
            $status = $row['status'];
        }
    }

}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $title = $_POST['title'];
    $content = $_POST['content'];
    $page_type = $_POST['page_type'];
    $status = $_POST['status'];


    //echo $page_slug; die;

    // Featured Image Upload

    $upload_id = handleFileUpload($_FILES['featured_image']['name'], $_FILES['featured_image']['tmp_name'], $batch_no);

    //echo $upload_id; die;
    //echo $featured_image; die;

    // Check if it's a new page or an edit (based on the presence of the 'id' parameter)
    if (isset($_POST['id'])) {
        // Edit existing page
        $id = $_POST['id'];
        $page_id = editPage($id, $title, $content, $upload_id, $page_type, $status);
    } else {
        
        // Generate slug from the title
        $page_slug = generateSlug($title);

        // Add new page
        $page_id = addPage($title, $page_slug, $content, $upload_id, $page_type, $status);
    }

    // Redirect back to the "All Pages" page
    header("Location: add_page.php?id=".$page_id);
    exit();
}

// Function to add a new page and return the last inserted ID
function addPage($title, $page_slug, $content, $featured_image, $page_type, $status)
{
    global $conn;

    // Get the logged-in user's ID
    $author = $_SESSION["user_id"];

    // Insert new page into the database
    $insertSql = "INSERT INTO pages (title, page_slug, content, featured_image_id, page_type, status, author)
                  VALUES (?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($insertSql);
    $stmt->bind_param("sssisii", $title, $page_slug, $content, $featured_image, $page_type, $status, $author);

    if ($stmt->execute()) {
        // Get the last inserted ID
        $lastInsertId = $conn->insert_id;
        $msg = "Page added successfully! Last Inserted ID: " . $lastInsertId;
        return $lastInsertId;
    } else {
        $msg = "Error adding page: " . $stmt->error;
        return false;
    }

    // Close the statement
    $stmt->close();
}


// Function to edit an existing page
function editPage($id, $title, $content, $featured_image, $page_type, $status)
{
    global $conn;

    // Update the existing page in the database
    $updateSql = "UPDATE pages
                  SET title = ?,
                      content = ?,
                      featured_image_id = ?,
                      page_type = ?,
                      status = ?
                  WHERE id = ?";
    
    $stmt = $conn->prepare($updateSql);
    $stmt->bind_param("ssisii", $title, $content, $featured_image, $page_type, $status, $id);

    if ($stmt->execute()) {
        $msg = "Page updated successfully!";
    } else {
        $msg = "Error updating page: " . $stmt->error;
    }

    //echo $msg; die;

    return $id;

    // Close the statement
    $stmt->close();
}

// Function to generate a unique slug from the title
function generateSlug($title)
{
    global $conn;

    $slug = strtolower(str_replace(' ', '-', $title));
    // Check if the slug is unique
    $sql = "SELECT id FROM pages WHERE page_slug = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $slug);
    $stmt->execute();
    $stmt->store_result();
    $counter = 1;

    while ($stmt->num_rows > 0) {
        $slug = $slug . '-' . $counter;
        $stmt->bind_param("s", $newSlug);
        $stmt->execute();
        $stmt->store_result();
        $counter++;
    }

    // Close the statement
    $stmt->close();

    return $slug;
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
            $msg = "Error adding file information: " . $conn->error;
            return 0;
        }
    } else {
        $msg = "Error moving uploaded file.";
        return 0;
    }
}

include_once("header.php");
?>

<main>
    <div class="container-fluid px-4 mt-5">
        <div class="mt-4 d-flex justify-content-between">
            <h1 class="">Add/Edit Page</h1>
            <div class="">
                <a href="pages.php" class="btn btn-outline-info ">All Pages</a>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-body">
                <form action="add_page.php" method="POST" enctype="multipart/form-data">
                    <!-- Add/Edit Page Form -->
                    <?php if (isset($_GET['id'])) {
                        // If editing, include the page ID as a hidden field
                        $id = $_GET['id'];
                        echo "<input type='hidden' name='id' value='$id'>";
                    } ?>

                    <p><?php echo $msg; ?></p>

                    <div class="mb-3">
                        <label for="title" class="form-label">Title:</label>
                        <input type="text" class="form-control" name="title" value="<?php echo $title; ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="content" class="form-label">Content:</label>
                        <!-- You can use any rich text editor here (e.g., TinyMCE) -->
                        <textarea class="form-control" name="content" rows="6"><?php echo $content; ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="featured_image" class="form-label">Featured Image:</label>
                        <input type="file" class="form-control" name="featured_image">
                        <?php echo ($featured_image) ? '<img src="../assets/uploads/' . $featured_image . '" alt="Featured Image" class="img-thumbnail" style="max-width: 100px;">' : ""; ?>
                    </div>

                    <div class="mb-3">
                        <label for="page_type" class="form-label">Page Type:</label>
                        <select class="form-control" name="page_type" required>
                            <option value="page" <?php echo $page_type === 'page' ? 'selected' : ''; ?>>Page</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Status:</label>
                        <select class="form-control" name="status" required>
                            <option value="0" <?php echo $status == 0 ? 'selected' : ''; ?>>Draft</option>
                            <option value="1" <?php echo $status == 1 ? 'selected' : ''; ?>>Publish</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
</main>

<script src="https://cdn.tiny.cloud/1/j0yxn0km3tmqppaw9mdtvei80g9jpkzh0ds1jtdl94okws87/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>

<script>
  tinymce.init({
    selector: 'textarea',
    plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
    toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
  });
</script>
<?php include_once("footer.php"); ?>
