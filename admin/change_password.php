<?php
// Include the database connection
include("../connection.php");
include("./login_check.php");
include("./header.php");
?>

<main>
    <div class="container-fluid px-4 mt-5">
        <h1 class="mt-4">Change Password</h1>
        <div class="row justify-content-center align-items-center">
            <?php
            // Initialize error messages
            $currentPasswordError = $newPasswordError = $confirmPasswordError = $updateError = '';

            // Initialize the password change flag
            $passwordChanged = false;

            // Check if the form is submitted
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $currentPassword = $_POST["inputCurrentPassword"];
                $newPassword = $_POST["inputNewPassword"];
                $confirmPassword = $_POST["inputConfirmPassword"];

            
                // Fetch the existing user data from the database
                $sql = "SELECT * FROM users WHERE id = ?";
                $stmt = $conn->prepare($sql);

                if ($stmt) {
                    $stmt->bind_param("i", $_SESSION['user_id']);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();

                        // Verify the current password
                        if (password_verify($currentPassword, $row['password'])) {
                            // Current password is correct
                            if ($newPassword === $confirmPassword) {
                                // New passwords match
                                if (strlen($newPassword) >= 4) {
                                    // Password is at least 4 characters, proceed with update
                                    $hashedNewPassword = password_hash($newPassword, PASSWORD_BCRYPT);

                                    // Update password in the database
                                    $updateSql = "UPDATE users SET password = ? WHERE id = ?";
                                    $updateStmt = $conn->prepare($updateSql);

                                    if ($updateStmt) {
                                        $updateStmt->bind_param("si", $hashedNewPassword, $_SESSION['user_id']);

                                        if ($updateStmt->execute()) {
                                            $passwordChanged = true; // Set the flag to true if password is updated successfully
                                        } else {
                                            $updateError = "Error updating password: " . $updateStmt->error;
                                        }

                                        $updateStmt->close();
                                    } else {
                                        $updateError = "Error: " . $conn->error;
                                    }
                                } else {
                                    // New password is too short
                                    $newPasswordError = "New password should be at least 4 characters.";
                                }
                            } else {
                                // New passwords don't match
                                $confirmPasswordError = "New passwords do not match.";
                            }
                        } else {
                            // Current password is incorrect
                            $currentPasswordError = "Current password is incorrect.";
                        }
                    } else {
                        // Handle the case where the user ID is not valid
                        $updateError = "Error: User not found.";
                    }

                    $stmt->close();
                } else {
                    $updateError = "Error: " . $conn->error;
                }

                $conn->close();
            }
            ?>
            <div class="col-md-6">
                <?php
                    if ($passwordChanged) {
                        echo '<div class="container d-flex align-items-center justify-content-center" style="min-height: 100vh;">';
                        echo '<div class="card text-center">';
                        echo '<div class="card-body">';
                        echo '<h5 class="card-title">Password changed successfully!</h5>';
                        echo '<p class="card-text">Go to Dashboard <a href="Dashboard.php">Login</a></p>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                    }
                ?>
            </div>

            <div class="col-md-7">
                <div class="card shadow-lg border-0 rounded-lg mt-5">
                    <div class="card-header"><h3 class="text-center font-weight-light my-4">Password Change</h3></div>
                    <div class="card-body">
                        <form method="post" action="">
                            <div class="form-check mb-3">
                                <label for="inputCurrentPassword">Current Password:</label>
                                <input class="form-control" type="password" name="inputCurrentPassword" placeholder="Current Password" required>
                                <span style="color: red;"><?php echo $currentPasswordError; ?></span><br>
                            </div>
                            <div class="form-check mb-3">
                            <label for="inputNewPassword">New Password:</label>
                                <input class="form-control" type="password" name="inputNewPassword" placeholder="New Password" required>
                                <span style="color: red;"><?php echo $newPasswordError; ?></span><br>
                            </div>

                            <div class="form-check mb-3">
                                <label for="inputConfirmPassword">Confirm Password:</label>
                                <input class="form-control" type="password" name="inputConfirmPassword" placeholder="Confirm Password" required>
                                <span style="color: red;"><?php echo $confirmPasswordError; ?></span><br>
                            </div>

                            <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                                <input class="btn btn-primary" type="submit" value="Change Password">
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<?php include_once("footer.php");?>