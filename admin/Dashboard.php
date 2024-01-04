<?php
// Include the database connection
include("../connection.php");

include("./login_check.php");
include_once("header.php");
?>
<main>
    <div class="container-fluid px-4 mt-5">
        <h1 class="mt-4">Dashboard</h1>
        <div class="row">
            <div class="col-xl-12 col-md-12">
                <h4>Welcome to Airport Ride Transfer Admin Panel</h4>
            </div>
        </div>
    </div>
</main>

<?php include_once("footer.php"); ?>