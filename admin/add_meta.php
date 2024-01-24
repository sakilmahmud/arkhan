<?php
// Include the database connection
include("../connection.php");

include("./login_check.php");

$msg = "";

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if it's an add or edit operation
    if (isset($_POST['add_meta'])) {
        // Retrieve form data for adding new meta
        $page_id = $_POST['page_id'];
        $meta_key = $_POST['meta_key'];
        $meta_value = $_POST['meta_value'];

        // Add new meta
        addMeta($page_id, $meta_key, $meta_value);
    } elseif (isset($_POST['edit_meta'])) {
        // Retrieve form data for editing meta
        $meta_id = $_POST['meta_id'];
        $meta_key = $_POST['meta_key'];
        $meta_value = $_POST['meta_value'];

        // Edit meta
        editMeta($meta_id, $meta_key, $meta_value);
    } elseif (isset($_POST['delete_meta'])) {
        // Retrieve form data for deleting meta
        $meta_id = $_POST['meta_id'];

        // Delete meta
        deleteMeta($meta_id);
    }
}

// Function to add new meta
function addMeta($page_id, $meta_key, $meta_value)
{
    global $conn;

    // Insert new meta into the database
    $insertSql = "INSERT INTO page_meta (page_id, meta_key, meta_value)
                  VALUES (?, ?, ?)";
    
    $stmt = $conn->prepare($insertSql);
    $stmt->bind_param("iss", $page_id, $meta_key, $meta_value);

    if ($stmt->execute()) {
        $msg =  "Meta added successfully!";
    } else {
        $msg = "Error adding meta: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
}

// Function to edit meta
function editMeta($meta_id, $meta_key, $meta_value)
{
    global $conn;

    // Update meta in the database
    $updateSql = "UPDATE page_meta
                    SET meta_key = ?,
                        meta_value = ?
                    WHERE id = ?";
    
    $stmt = $conn->prepare($updateSql);
    $stmt->bind_param("ssi", $meta_key, $meta_value, $meta_id);

    if ($stmt->execute()) {
        $msg =  "Meta updated successfully!";
    } else {
        $msg = "Error updating meta: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
}

// Function to delete meta
function deleteMeta($meta_id)
{
    global $conn;

    // Delete meta from the database
    $deleteSql = "DELETE FROM page_meta WHERE id = ?";
    
    $stmt = $conn->prepare($deleteSql);
    $stmt->bind_param("i", $meta_id);

    if ($stmt->execute()) {
        $msg =  "Meta deleted successfully!";
    } else {
        $msg = "Error deleting meta: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
}

include_once("header.php");
?>

<main>
    <div class="container-fluid px-4 mt-5">
        <div class="mt-4">
            <h1 class="">Add/Update Meta</h1>
        </div>
        <div class="card mb-4">
            <div class="card-body">
                <!-- Form to add or edit meta -->
                <form action="add_meta.php?id=<?php echo $_GET['id']; ?>" method="POST">
                    <input type="hidden" name="page_id" value="<?php echo $_GET['id']; ?>">
                    
                    <?php
                    // If editing, include the meta ID as a hidden field
                    if (isset($_GET['meta_id'])) {
                        $meta_id = $_GET['meta_id'];
                        echo "<input type='hidden' name='meta_id' value='$meta_id'>";
                        $existingMeta = getMetaById($meta_id);
                        echo "<input type='hidden' name='meta_key' value='" . $existingMeta['meta_key'] . "'>";
                        echo "<input type='hidden' name='meta_value' value='" . $existingMeta['meta_value'] . "'>";
                    }
                    ?>

                    <div class="mb-3">
                        <label for="meta_key" class="form-label">Meta Key:</label>
                        <input type="text" class="form-control" name="meta_key" value="<?php echo isset($existingMeta) ? $existingMeta['meta_key'] : ''; ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="meta_value" class="form-label">Meta Value:</label>
                        <input type="text" class="form-control" name="meta_value" value="<?php echo isset($existingMeta) ? $existingMeta['meta_value'] : ''; ?>" required>
                    </div>

                    <?php
                    // Determine if it's an add or edit operation
                    $submitButtonText = "Add Meta";
                    $submitButtonName = "add_meta";
                    if (isset($_GET['meta_id'])) {
                        $submitButtonText = "Update Meta";
                        $submitButtonName = "edit_meta";
                    }
                    ?>

                    <button type="submit" class="btn btn-primary" name="<?php echo $submitButtonName; ?>"><?php echo $submitButtonText; ?></button>
                </form>

                <hr>

                <!-- Display meta information for the selected page -->
                <h3>Meta Information</h3>
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Meta Key</th>
                            <th>Meta Value</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Fetch meta information for the selected page
                        if (isset($_GET['id'])) {
                            $selectedPageId = $_GET['id'];
                            $metaSql = "SELECT * FROM page_meta WHERE page_id = $selectedPageId";
                            $metaResult = $conn->query($metaSql);

                            if ($metaResult->num_rows > 0) {
                                while ($metaRow = $metaResult->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . $metaRow['id'] . "</td>";
                                    echo "<td>" . $metaRow['meta_key'] . "</td>";
                                    echo "<td>" . $metaRow['meta_value'] . "</td>";
                                    echo "<td>
                                            <a href='add_meta.php?id=$selectedPageId&meta_id=" . $metaRow['id'] . "' class='btn btn-dark'>Edit</a>
                                            <form action='add_meta.php?id=$selectedPageId' method='POST' style='display:inline;'>
                                                <input type='hidden' name='meta_id' value='" . $metaRow['id'] . "'>
                                                <button type='submit' class='btn btn-danger' name='delete_meta' onclick='return confirm(\"Are you sure?\")'>Delete</button>
                                            </form>
                                          </td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='4'>No meta information available for this page.</td></tr>";
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<?php include_once("footer.php"); ?>

<?php
// Function to get meta by ID
function getMetaById($meta_id)
{
    global $conn;

    $metaSql = "SELECT * FROM page_meta WHERE id = $meta_id";
    $metaResult = $conn->query($metaSql);

    if ($metaResult->num_rows > 0) {
        return $metaResult->fetch_assoc();
    } else {
        return null;
    }
}
?>
