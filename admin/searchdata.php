<?php
include("../connection.php");

$sv = $_GET['name'];

$sql = "SELECT * FROM postal_codes WHERE from_postcode LIKE '%$sv%' OR to_postcode LIKE '%$sv%'";

$result = mysqli_query($conn, $sql);
$data = [];
while ($fetch = mysqli_fetch_assoc($result)) {
    $data[] = $fetch;
}
print_r(json_encode($data));
?>