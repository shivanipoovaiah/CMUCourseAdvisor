<?php # Script 9.3 - submit_review.php
 // This script performs an INSERT query to add a record to the users table.
 	session_start( );
	$page_title = 'Submit Review';
	 	include ('includes/loggedin_header.html');
	 	include ('includes/review_form.php');
	
	 // Check for form submission:
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	
	 	$errors = array( ); // Initialize an error array.
	
		// Check for an andrew ID
		$aid = trim($_SESSION['user_id']);

	 	// Check for course ID:
		if (empty($_POST['course_id'])) {
			$errors[] = 'You forgot to enter course ID.';
		} else {
			$cid = trim($_POST['course_id']);
		}
	
		// Check for Feedback:
		if (empty($_POST['feedback'])) {
			$errors[] = 'You forgot to enter feedback.';
		} else {
			$fb = trim($_POST['feedback']);
		}
		$rt = trim($_POST['rating']);
	
		if (empty($errors)) { // If everything's OK.
	
		// Add the review in the database...
		
		require ('../mysqli_connect.php'); // Connect to the db.

		// Make the query:
		$q = "INSERT INTO review (course_id, andrew_id, feedback, rating) VALUES ('$cid', '$aid', '$fb', '$rt')";
		$r = @mysqli_query ($dbc, $q); // Run the query.
		$qr = "SELECT review_id FROM review WHERE review_id=(SELECT max(review_id) FROM review)";
		$rr =  @mysqli_query ($dbc, $qr);
		$numr = mysqli_num_rows($rr);
                
		if ($numr > 0) { 
			while ($rowr = mysqli_fetch_array($rr, MYSQLI_ASSOC)) {
				$newreview = $rowr['review_id'];
				$qi = "INSERT INTO favourite (fav, review_id, andrew_id,course_id) VALUES (0, $newreview, '$aid', '$cid')";
				$ri =  @mysqli_query ($dbc, $qi);
			}
		}
		if ($r) { // If it ran OK.

			// Print a message:
			echo '<h1>Thank you!</h1>
		<p>Your review is now added.</p><p>
		<br /></p>';

		} else { // If it did not run OK.

			// Public message:
			echo '<h1>System Error</h1>
			<p class="error">Your review could not be added due to a system error. We apologize for
			any inconvenience.</p>';

				// Debugging message:
				echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $q . '</p>';
				
			} // End of if ($r) IF.
		
			mysqli_close($dbc); // Close the database connection.
		
			// Include the footer and quit the script:
			include ('includes/footer.html');
			exit( );
	 	 	
	 	} else { // Report the errors.
	 	
			echo '<h1>Error!</h1>
			<p class="error">The following error(s) occurred:<br />';
			foreach ($errors as $msg) { // Print each error.
				echo " - $msg<br />\n";
			}
			echo '</p><p>Please try again.</p><p><br /></p>';
		
		} // End of if (empty($errors)) IF.
	
	} // End of the main Submit conditional.
?>
<?php include ('includes/footer.html'); ?>