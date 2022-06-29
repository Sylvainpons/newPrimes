<?php
// session_start();
// If the user is not logged in redirect to the login page...
if (isset($_SESSION['loggedin'])) {
	header('Location: /primes/index.php');
	exit;
}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>DMAX Primes</title>
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
        <link href="style.css" rel="stylesheet" type="text/css">
	</head>
	<body>
		<div class="login">
			<h1>Login</h1>
			<form action="/login.php" method="post">
				<label for="EMAIL">
					<i class="fas fa-user"></i>
				</label>
				<input type="text" name="EMAIL" placeholder="Adresse e-mail" id="username" required>
				<label for="PASSWD">
					<i class="fas fa-lock"></i>
				</label>
				<input type="password" name="PASSWD" placeholder="Mot de passe" id="password" required>
				<input type="submit" value="Login">
			</form>
		</div>
	</body>
</html>