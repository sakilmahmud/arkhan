<?php
include("../connection.php");
if (isset($_SESSION['user_id'])) {
	header('Location:Dashboard.php');
	exit;
}
$msg = "";
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$email = $_POST["email"];
	$password = $_POST["password"];

	// Query the database to check if the user exists
	$sql = "SELECT id, email, password FROM users WHERE email = ?";
	$stmt = $conn->prepare($sql);

	if ($stmt) {
		$stmt->bind_param("s", $email);
		$stmt->execute();
		$stmt->store_result();

		if ($stmt->num_rows == 1) {
			// User found, fetch the hashed password
			$stmt->bind_result($userId, $dbEmail, $hashedPassword);
			$stmt->fetch();

			// Verify the provided password against the hashed password
			if (password_verify($password, $hashedPassword)) {
				// Password is correct, create a session and log in
				$_SESSION["user_id"] = $userId;
				$_SESSION["email"] = $dbEmail;
				header("Location: Dashboard.php"); // Redirect to the user's dashboard
				exit;
			} else {
				// Password is incorrect
				$msg = "<div style='text-align:center;color:red'>Invalid password.</div>";
			}
		} else {
			// User not found
			$msg = "<div style='text-align:center;color:red'>User not found.</div>";
		}

		$stmt->close();
	} else {
		$msg = "Error: " . $conn->error;
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
									<h3 class="text-center font-weight-light my-4">Login</h3>
									<?php echo $msg; ?>
								</div>
								<div class="card-body">
									<form method="POST" action="">
										<div class="form-floating mb-3">
											<input class="form-control" id="inputEmail" type="text" name="email"
												placeholder="username or email" required />
											<label for="inputEmail">Email address</label>
										</div>
										<div class="form-floating mb-3">
											<input class="form-control" id="inputPassword" type="password"
												name="password" placeholder="Password" required />
											<label for="inputPassword">Password</label>
										</div>
										<div class="form-check mb-3">
											<input class="form-check-input" id="inputRememberPassword" type="checkbox"
												value="" />
											<label class="form-check-label" for="inputRememberPassword">Remember
												Password</label>
										</div>
										<div class=" align-items-center justify-content-between mt-4 mb-0">
											<button class="btn btn-primary" type="submit"
												style="float:right;">Login</button>
										</div>
									</form>
								</div>
								<div class="card-footer text-center py-3">
									<div class="small">
										<a href="forgot.php">Forgot Password? Click Here!</a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</main>
		</div>
	</div>
</body>

</html>