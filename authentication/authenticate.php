<?php 
session_start();
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'fmts';

$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if ( mysqli_connect_errno() ) {
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}
if ( !isset($_POST['username'], $_POST['password']) ) {
	exit('Please fill both the username and password fields!');
}

// prevent SQL injection.
if ($stmt = $con->prepare('SELECT password FROM user_credentials WHERE username = ?')) {
	$stmt->bind_param('s', $_POST['username']);
	$stmt->execute();
	// Store the result so we can check if the account exists in the database.
	$stmt->store_result();
if ($stmt->num_rows > 0) {
	$stmt->bind_result($password);
	$stmt->fetch();
	// Account exists, now we verify the password.
	// use password_hash in your registration file to store the hashed passwords.
	if ($_POST['password'] === $password) {
		// Verification success! User has logged-in!
		// Create sessions, so we know the user is logged in, they basically act like cookies but remember the data on the server.
		session_regenerate_id();
		$_SESSION['loggedin'] = TRUE;
		$_SESSION['name'] = $_POST['username'];
		$_SESSION['id'] = $id;
        if ($stmt = $con->prepare('SELECT designation FROM user_credentials WHERE username = ?')) {
            $stmt->bind_param('s', $_POST['username']);
            $stmt->execute();
            $stmt->bind_result($designation);
            $stmt->fetch();
            $stmt->close();
        
            if ($designation === 'admin') {
                header('Location: ../admin/dashboard.php'); 
            } elseif ($designation === 'weeding room') {
                header('Location: ../weedingroom.html');

            } elseif ($designation === 'record room') {
                 header('Location: ../recordroom.php'); // Redirect to the manager page
            }elseif ($designation === 'scanning') {
				header('Location: ../scanning.html');} 
				elseif ($designation === 'verification1') {
					header('Location: ../verification1.html');} 
					elseif ($designation === 'verification2') {
						header('Location: ../verification2.html');} 
			else {
                header('Location: index.html'); // Redirect to the default user page
            }
            exit(); // Terminate the script after redirection
	} else {
		exit();
	}
} else {
	// Incorrect username
	echo 'Incorrect password';
}
	$stmt->close();
}
else{
	echo "Incorrect username";
}
}
?>
