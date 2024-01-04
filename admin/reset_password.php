<?php
include("../php_main/db_connection.php");
$msg = "";
$email = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["new_password"]) && isset($_POST["confirm_password"])) {
    // Step 5: Update Password
	
	$token = $_GET["token"];

    // Validate the token and check if it's not expired
    $currentTimestamp = time();
    $sql = "SELECT email FROM password_reset WHERE token = '$token' AND expiry_timestamp > '$currentTimestamp'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Token is valid
        $row = $result->fetch_assoc();
        $email = $row["email"];
    }

    $newPassword = $_POST["new_password"];
    $confirmPassword = $_POST["confirm_password"];

    if ($newPassword === $confirmPassword) {
		
        // Update the password in the database (Assuming you have a 'users' table with 'password' column)
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
        $updateSql = "UPDATE admin_login SET password = '$hashedPassword' WHERE email = '$email'";
        $conn->query($updateSql);
		//echo $updateSql; die;
        // Delete the used token
        $deleteSql = "DELETE FROM password_reset WHERE email = '$email'";
        $conn->query($deleteSql);

        $msg = "Password updated successfully. Please <a href='login.php'>Login</a> Now!";
        $conn->close();
        //exit;
    } else {
        $msg = "Passwords do not match.";
    }
} else if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["token"])) {
    // Step 4: User Clicks Reset Link
    $token = $_GET["token"];

    // Validate the token and check if it's not expired
    $currentTimestamp = time();
    $sql = "SELECT email FROM password_reset WHERE token = '$token' AND expiry_timestamp > '$currentTimestamp'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Token is valid
        $row = $result->fetch_assoc();
        $email = $row["email"];
        $conn->close();
    } else {
        // Token is invalid or expired
        $conn->close();
		$msg = "Invalid or expired token.";
    }
} else {
	$msg = "Invalid request.";
}

?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
		<meta name="description" content="" />
		<meta name="author" content="" />
		<title>Login - SB Admin</title>
		<link href="css/styles.css" rel="stylesheet" />
		<script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
		<link rel="stylesheet" type="text/css" href="style1.css">
		<link href="plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
		<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
		<link rel="stylesheet" type="text/css" href="../style2.css">
		<link href="plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
		<link rel="stylesheet" type="text/css" href="../ss.css">
	</head>
	<body>
		<?php require_once('nav2.php') ?> 
		<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
			<div class="container">
				<a class="navbar-brand" href="../index.php">
					<img src="../images/retreat.png">
				</a>
				<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>
				<div class="collapse navbar-collapse" id="navbarNav">
					<ul class="navbar-nav ms-auto">
						<li class="nav-item">
							<a class="nav-link" href="#">About</a>
						</li>
						<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle" href="#" id="homestaysDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Homestays </a>
							<div class="dropdown-menu" aria-labelledby="homestaysDropdown">
								<div class="dropdown-submenu">
									<a class="dropdown-item dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> West Bengal </a>
									<ul class="dropdown-menu sub">
										<li>
											<a class="nav-link dropdown-item" href="../homestays_by_state.php?country=Darjeeling">Darjeeling</a>
										</li>
										<li>
											<a class="nav-link dropdown-item" href="../homestays_by_state.php?country=Kalimpong">Kalimpong</a>
										</li>
										<li>
											<a class="nav-link dropdown-item" href="../homestays_by_state.php?country=Dooars">Dooars</a>
										</li>
										<li>
											<a class="nav-link dropdown-item" href="../homestays_by_state.php?country=Kurseong">Kurseong</a>
										</li>
										<li>
											<a class="nav-link dropdown-item" href="../homestays_by_state.php?country=Sikkim">Sikkim</a>
										</li>
									</ul>
								</div>
								<a class="nav-link dropdown-item" href="../homestays_by_state.php?country=Uttarakhand">Uttarakhand</a>
								<a class=" nav-link dropdown-item" href="../homestays_by_state.php?country=Himachal Pradesh">Himachal Pradesh</a>
								<a class="nav-link dropdown-item" href="../homestays_by_state.php?country=Meghalaya">Meghalaya</a>
								<a class="nav-link dropdown-item" href="../homestays_by_state.php?country=Assam">Assam</a>
								<a class="nav-link dropdown-item" href="../homestays_by_state.php?country=Arunachal Pradesh">Arunachal Pradesh</a>
								<a class="nav-link dropdown-item" href="../homestays_by_state.php?country=Ladakh">Ladakh</a>
							</div>
						</li>
						<style>
							.dropdown-submenu:hover>.dropdown-menu {
								display: block;
							}

							.sub {
								margin-left: 182px;
								margin-top: -38px;
							}

							.dropdown-item:hover {
								background-color: #83ca15;
							}

							.dropdown-item {
								color: black !important;
							}

							.sub[data-bs-popper] {
								top: 0;
								left: 0;
								margin-top: var(--bs-dropdown-spacer);
							}

							@media only screen and (max-width: 768px) {
								.dropdown-menu {
									background-color: #212529;
								}

								.sub {
									margin-left: 0px;
									margin-top: 0px;
								}

								.sub[data-bs-popper] {
									top: 100%;
									left: 0;
									margin-top: var(--bs-dropdown-spacer);
								}

								.dropdown-item {
									color: whitesmoke !important;
									text-align: left !important;
								}
							}
						</style>
						<li class="nav-item">
							<a class="nav-link" href="#">Contact</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="admin/login.php">Admin Login</a>
						</li>
					</ul>
				</div>
			</div>
		</nav>
		<div id="layoutAuthentication">
			<div id="layoutAuthentication_content">
				<main>
					<div class="container">
						<div class="row justify-content-center">
							<div class="col-lg-5">
								<div class="card shadow-lg border-0 rounded-lg mt-5">
									<div class="card-header">
										<h3 class="text-center font-weight-light my-4">Reset Password</h3>
									</div>
									<div class="card-body">
										<p><?php echo $msg; ?></p>
										<form method="POST" action="">
											<div class="form-floating mb-3">
												<input class="form-control" id="new_password" type="password" name="new_password" placeholder="New Password" required />
												<label for="new_password">New Password</label>
											</div>
											<div class="form-floating mb-3">
												<input class="form-control" id="confirm_password" type="password" name="confirm_password" placeholder="Confirm Password" required />
												<label for="confirm_password">Confirm Password</label>
											</div>
											<div class=" align-items-center justify-content-between mt-4 mb-0">
												<button class="btn btn-primary" type="submit" style="float:right;">Submit</button>
											</div>
										</form>
									</div>
									<!-- <div class="card-footer text-center py-3"><div class="small"><a href="register.php">Need an account? Sign up!</a></div></div> -->
									<div class="card-footer text-center py-3">
										<div class="small">
											<a href="login.php">Login</a>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</main>
			</div>
		</div>
		<!--footer starts from here-->
		<!--footer starts from here-->
		<footer class="footer">
			<div class="container bottom_border">
				<div class="row">
					<div class=" col-sm-4 col-md col-sm-4  col-12 col">
						<h5 class="headin5_amrc col_white_amrc pt2">Find us</h5>
						<!--headin5_amrc-->
						<p class="mb10">Address : 44/29 Shyamnagar Road Kolkata 700055 </p>
						<p>
							<i class="fa fa-phone"></i> +91-7890643956
						</p>
						<p>
							<i class="fa fa fa-envelope"></i> info@retrea8stay.com
						</p>
						<a href="https://www.facebook.com/retrea8stay?mibextid=ZbWKwL">
							<i class="fab fa-facebook" style="font-size: 21px;color: #CCC;padding: 7px;"></i>
						</a>
						<a href="https://www.instagram.com/retrea8stay/?igshid=bjQ4aHZ3YWN0YzBq">
							<i class="fab fa-instagram" style="font-size: 21px;color: #CCC;padding: 7px;"></i>
						</a>
						<a href="https://youtube.com/@retrea8stay?si=ETw9Y8Nmtnii0TAn">
							<i class="fab fa-youtube" style="font-size: 21px;color: #CCC;padding: 7px;"></i>
						</a>
					</div>
					<div class=" col-sm-4 col-md  col-6 col">
						<h5 class="headin5_amrc col_white_amrc pt2">Policy</h5>
						<!--headin5_amrc-->
						<ul class="footer_ul_amrc">
							<li>
								<a href="privacypolicy.php">Privacy Policy</a>
							</li>
							<li>
								<a href="cancellationpolicy.php">Cancellation Policy</a>
							</li>
						</ul>
						<!--footer_ul_amrc ends here-->
					</div>
					<div class=" col-sm-4 col-md  col-6 col">
						<h5 class="headin5_amrc col_white_amrc pt2">Host</h5>
						<!--headin5_amrc-->
						<ul class="footer_ul_amrc">
							<li>
								<a href="whylistwith.php">Why List with Retrea8stay</a>
							</li>
							<li>
								<a href="admin/login.php">Admin Login</a>
							</li>
							<li>
								<a href="listhomestayforhomstayowner.php">List Homestay</a>
							</li>
						</ul>
						<!--footer_ul_amrc ends here-->
					</div>
					<div class=" col-sm-4 col-md  col-6 col">
						<h5 class="headin5_amrc col_white_amrc pt2">Community</h5>
						<!--headin5_amrc-->
						<ul class="footer_ul_amrc">
							<li>
								<a href="why travel with retrea8stya.php">Why Travel with retrea8stay</a>
							</li>
						</ul>
						<!--footer_ul_amrc ends here-->
					</div>
					<div class=" col-sm-4 col-md  col-6 col">
						<h5 class="headin5_amrc col_white_amrc pt2">Company</h5>
						<!--headin5_amrc-->
						<ul class="footer_ul_amrc">
							<li>
								<a href="payment.php">Payment</a>
							</li>
						</ul>
						<!--footer_ul_amrc ends here-->
					</div>
				</div>
			</div>
		</footer>
		<style>
			li a {
				text-decoration: none;
			}
		</style>
		<script>
			var galleryTop = new Swiper('.gallery-top', {
				spaceBetween: 10,
				navigation: {
					nextEl: '.swiper-button-next',
					prevEl: '.swiper-button-prev',
				},
			});
			var galleryThumbs = new Swiper('.gallery-thumbs', {
				spaceBetween: 10,
				centeredSlides: true,
				slidesPerView: 'auto',
				touchRatio: 0.2,
				slideToClickedSlide: true,
			});
			galleryTop.controller.control = galleryThumbs;
			galleryThumbs.controller.control = galleryTop;
		</script>
		<script>
			$(document).ready(function() {
				$('.dropdown-item[data-country]').click(function(e) {
					e.preventDefault();
					var selectedCountry = $(this).data('country');
					fetchHomestays(selectedCountry);
				});

				function fetchHomestays(country) {
					$.ajax({
						type: 'POST', // You can use 'GET' or 'POST' depending on your setup
						url: 'homestays.php', // This is the PHP script to fetch homestays
						data: {
							country: country
						},
						success: function(data) {
							// Update the page with the received homestay data
							$('#homestays-results').html(data); // Assuming you have a div with this ID
						}
					});
				}
			});
		</script>
		<script>
			$(document).ready(function() {
				$(".background-slider").slick({
					autoplay: true,
					autoplaySpeed: 3000,
					arrows: false,
					dots: false,
					fade: true,
				});
			});
		</script>
		<script>
			var dropdownToggleList = [].slice.call(document.querySelectorAll('.dropdown-toggle'));
			dropdownToggleList.map(function(dropdownToggle) {
				return new bootstrap.Dropdown(dropdownToggle);
			});
		</script>
		<!-- Include jQuery (required by Bootstrap) -->
		<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
		<!-- Include Bootstrap JavaScript -->
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
		<!-- Include Bootstrap JS (change the path to your own file) -->
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/5.0.0-beta2/js/bootstrap.min.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
		<script src="js/scripts.js"></script>
	</body>
</html>
<style>
	body {
		background: url(../images/login.jpg) !important;
		background-repeat: no-repeat !important;
		background-size: cover !important;
		background-position: center center !important;
	}
</style>