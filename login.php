<?php # Script 12.12 - login.php #4
	 // This page processes the login form submission.
	 // The script now stores the HTTP_USER_AGENT value for added security.
	
	 // Check if the form has been submitted:
	 if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	
	 	 // Need two helper files:
	 	 require ('includes/login_function.php');
	 	 require ('../mysqli_connect.php');
	 	 	
	 	 // Check the login:
	 	 list ($check, $data) = check_login($dbc,$_POST['andrew_id'], $_POST['pass']);
	 	
	 	 if ($check) { // OK!
	 	 	
			// Set the session data:
			session_start( );
			$_SESSION['user_id'] =$data['andrew_id'];
			$user = $_SESSION['user_id'];
			$_SESSION['first_name'] =$data['fname'];
			// Store the HTTP_USER_AGENT:
			$_SESSION['agent'] = md5($_SERVER['HTTP_USER_AGENT']);

			// Redirect:
			redirect_user('reviews.php');
	 	 	 	
	 	 } else { // Unsuccessful!
	
	 	 	 // Assign $data to $errors for
	 	 	 // login_page.php:
	 	 	 $errors = $data;
	
	 	 }
	 	 	
	 	 mysqli_close($dbc); // Close the database connection.
	
	 } // End of the main submit conditional.
	
	 // Create the page:
	 include ('includes/login_page.php');
	 ?>
