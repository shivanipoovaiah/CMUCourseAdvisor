<?php
	$page_title = 'Course suggestion';
	session_start( );
	include ('includes/loggedin_header.html');
	include ('nlisuggest.html');
	require ('../mysqli_connect.php'); // Connect to the db.
	$user = $_SESSION['user_id'];
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$errors = array( ); // Initialize an error array.
	
		// Check for an andrew ID
		if (empty($_POST['nli'])) {
			$errors[] = 'You forgot to enter';
		} else {
			$nli = trim($_POST['nli']);
		}


		if (empty($errors)) { // If everything's OK.
		// Make the query:
			$words = explode(' ',$nli);
			$q = "SELECT DISTINCT c.course_id AS ID, c.cname AS Course,c.prof_name AS Professor, coalesce(fc.fav, 0) as fav
			FROM course as c
			LEFT OUTER JOIN (SELECT * from favourite where andrew_id = '$user') as fc on c.course_id = fc.course_id
			WHERE";
			for ($i = 0; $i < count($words); $i++) {
				if($i == count($words)-1) {
					$q .= " Lower(c.cname) LIKE '%".$words[$i]."%'";
				}
				else {
					$q .= " Lower(c.cname) LIKE '%".$words[$i]."%' OR";
				}
			}
			$q .= " order by fav desc";
			$r = @mysqli_query ($dbc, $q); // Run the query.
			// Count the number of returned rows:
   			$num = mysqli_num_rows($r);
			echo '<div class="c_container">';
			echo '<div class="course_container"><h1 align="center">Courses</h1>';
			if ($num > 0) { // If it ran OK, display the records.
				// Table header.
				echo '<table class="course_list" align="center" cellspacing="5" cellpadding="5">
				<tr class="row"><td align="left"><b>ID</b></td><td align="left"><b>Course</b></td><td align="left"><b>Professor</b></td><td align="left"><b>Favourite Reviews</b></td></tr>';
			
				// Fetch and print all the records:
				while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
					if($row['fav']) $favourite = 'Yes';
					else $favourite = 'No';
					echo '<tr class="row"><td align="left">' . $row['ID'] . '</td><td align="left">' . $row['Course'] . '</td><td align="left">' . $row['Professor'] . '</td><td align="left">' . $favourite;

					echo '</td></tr>';
				}
		
				echo '</table>'; // Close the table.
			
				mysqli_free_result ($r); // Free up the resources.	
						// Print how many users there are:
				echo "<p align='center'>There are currently $num courses based on your needs.</p>\n </div></div>";
		
			} else { // If it did not run OK.
				
						// Public message:
						echo '<p class="error">There are currently no registered users.</p> </div><div>';
					
						// Debugging message:
						echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $q . '</p>';
					
			} // End of if ($r) IF.
  
		}   
	} else {
	// Make the query:
	$user = $_SESSION['user_id'];
	$q = "SELECT DISTINCT c.course_id AS ID, c.cname AS Course,c.prof_name AS Professor, coalesce(fc.fav, 0) as fav
	FROM course as c
	LEFT OUTER JOIN (SELECT * from favourite where andrew_id = '$user') as fc on c.course_id = fc.course_id
	order by fav desc";
	$r = @mysqli_query ($dbc, $q); // Run the query.
	// Count the number of returned rows:
	   $num = mysqli_num_rows($r);
	   
	echo '<div class="c_container">';
	echo '<div class="course_container"><h1 align="center">All Courses</h1>';
	$favourite = '';
	if ($num > 0) { // If it ran OK, display the records.
		// Table header.
		echo '<table class="course_list" align="center" cellspacing="5" cellpadding="5">
		<tr class="row"><td align="left"><b>ID</b></td><td align="left"><b>Course</b></td><td align="left"><b>Professor</b></td><td align="left"><b>Favourite Reviews</b></td></tr>';
	
		// Fetch and print all the records:
		while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
			if($row['fav']) $favourite = 'Yes';
			else $favourite = 'No';
			echo '<tr class="row"><td align="left">' . $row['ID'] . '</td><td align="left">' . $row['Course'] . '</td><td align="left">' . $row['Professor'] . '</td><td align="left">' . $favourite;
			echo '</td></tr>';
		}

		echo '</table>'; // Close the table.
	
		mysqli_free_result ($r); // Free up the resources.	
				// Print how many users there are:
		echo "<p align='center'>There are currently $num courses based on your needs.</p>\n </div></div>";

	} else { // If it did not run OK.
		
				// Public message:
				echo '<p class="error">There are currently no registered users.</p> </div><div>';
			
				// Debugging message:
				echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $q . '</p>';
			
	} // End of if ($r) IF.

	}  
	mysqli_close($dbc); // Close the database connection.
	include ('includes/footer.html');
?>