<?php
// Include the database connection
include("../connection.php");

include("./login_check.php");

// Initialize variables for form inputs
$id = "";
$name = "";
$address = "";
$mobile = "";
// Add more fields if needed

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $address = $_POST['address'];
    $mobile = $_POST['mobile'];
    // Add more fields if needed

    if (empty($id)) {
        // Add mode
        // Insert the chamber into the database
        $sql = "INSERT INTO chambers (name, address, mobile) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $name, $address, $mobile);
    } else {
        // Edit mode
        // Update the chamber in the database
        $sql = "UPDATE chambers SET name=?, address=?, mobile=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $name, $address, $mobile, $id);
    }

    // Execute the statement
    $result = $stmt->execute();

    // Close the statement
    $stmt->close();

    // Redirect to the "All Chambers" page after adding or updating
    header("Location: chambers.php");
    exit();
}

// Check if it's in edit mode and fetch existing data
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM chambers WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $chamber = $result->fetch_assoc();
        $name = $chamber['name'];
        $address = $chamber['address'];
        $mobile = $chamber['mobile'];
        // Fetch more fields if needed
    }

    $stmt->close();
}

include_once("header.php");
?>

<main>
    <div class="container-fluid px-4 mt-5">
        <div class="mt-4">
            <?php if (empty($id)): ?>
                <h1 class="">Add Chamber</h1>
            <?php else: ?>
                <h1 class="">Edit Chamber</h1>
            <?php endif; ?>
        </div>
        <div class="card mb-4">
            <div class="card-body">
                <form action="" method="POST">
                    <input type="hidden" name="id" value="<?= $id ?>">
                    <div class="mb-3">
                        <label for="name" class="form-label">Chamber Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?= $name ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Chamber Address</label>
                        <textarea class="form-control" id="address" name="address" rows="3"
                            required><?= $address ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="mobile" class="form-label">Chamber Mobile</label>
                        <input type="text" class="form-control" id="mobile" name="mobile" value="<?= $mobile ?>"
                            required>
                    </div>
                    <!-- Add more fields if needed -->
                    <button type="submit" class="btn btn-primary">
                        <?= empty($id) ? 'Add Chamber' : 'Update Chamber' ?>
                    </button>
                </form>
            </div>
        </div>
    </div>
</main>

<?php include_once("footer.php"); ?>