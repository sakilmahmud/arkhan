<?php
include("../connection.php");
$msg = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
	// Step 1: User Requests Password Reset
	$email = $_POST["email"];

	$sql = "SELECT email FROM password_reset WHERE email = '" . $email . "'";
	$result = $conn->query($sql);

	if ($result->num_rows > 0) {

		// Step 2: Generate a Unique Token
		$token = bin2hex(random_bytes(32)); // Generate a random token
		$expiryTimestamp = time() + 3600; // Token expires in 1 hour

		// Step 3: Store the Token in the Database
		$sql = "INSERT INTO password_reset (email, token, expiry_timestamp) VALUES ('$email', '$token', '$expiryTimestamp')";
		$conn->query($sql);

		// Step 4: Send Reset Email
		$resetLink = "https://retrea8stay.com/admin/reset_password.php?token=$token";
		$subject = "Retrea8Stay :: Password Reset";
		$message = "<h4>Retrea8Stay</h4><p>Click the following link to reset your password: $resetLink </p>";
		if (mail($email, $subject, $message)) {
			$msg = '<div class="alert alert-success" role="alert">Reset email sent. Check your email for instructions.</div>';
		} else {
			$msg = '<div class="alert alert-danger" role="alert">Something went wrong please try again.</div>';
		}
	} else {
		$msg = '<div class="alert alert-danger" role="alert">This email is not registred with us</div>';
	}

	$conn->close();
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
	<link href="assets/css/styles.css" rel="stylesheet" />
</head>

<body>

	<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
		<div class="container">
			<a class="navbar-brand" href="../index.php">
				<img src="../assets/images/logo_white.png">
			</a>
			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
				aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarNav">
				<ul class="navbar-nav ms-auto">
					<li class="nav-item">
						<a class="nav-link" href="../index.php">Home</a>
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
									<h3 class="text-center font-weight-light my-4">Forgot Password</h3>
								</div>
								<div class="card-body">
									<p>
										<?php echo $msg; ?>
									</p>
									<form method="POST" action="">
										<div class="form-floating mb-3">
											<input class="form-control" id="inputEmail" type="email" name="email"
												placeholder="name@example.com" required />
											<label for="inputEmail">Email address</label>
										</div>
										<div class=" align-items-center justify-content-between mt-4 mb-0">
											<button class="btn btn-primary" type="submit"
												style="float:right;">Submit</button>
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
		$(document).ready(function () {
			$('.dropdown-item[data-country]').click(function (e) {
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
					success: function (data) {
						// Update the page with the received homestay data
						$('#homestays-results').html(data); // Assuming you have a div with this ID
					}
				});
			}
		});
	</script>
	<script>
		$(document).ready(function () {
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
		dropdownToggleList.map(function (dropdownToggle) {
			return new bootstrap.Dropdown(dropdownToggle);
		});
	</script>
	<!-- Include jQuery (required by Bootstrap) -->
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<!-- Include Bootstrap JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
		integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
		crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"
		integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p"
		crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"
		integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF"
		crossorigin="anonymous"></script>
	<!-- Include Bootstrap JS (change the path to your own file) -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/5.0.0-beta2/js/bootstrap.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
		crossorigin="anonymous"></script>
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