 <?php # Script 9.4 - view_courses.php
	 // This script retrieves all the records from the courses table.
	
	 $page_title = 'Courses';
	 include ('includes/loggedin_header.html');
	
	 echo '<div class="c_container">';
	 include ('includes/search_course.php');
	 // Page header:
	 echo '<div class="course_container"><h1 align="center">Courses</h1>';
	
	 require ('../mysqli_connect.php'); // Connect to the db.

	 // Make the query:
	 $q = "SELECT course_id AS ID, cname AS Course,prof_name AS Professor
	FROM course ORDER BY cname ASC";	 	
	 $r = @mysqli_query ($dbc, $q); // Run the query.
	 	 // Count the number of returned rows:
 $num = mysqli_num_rows($r);



 if ($num > 0) { // If it ran OK, display the records.
	 	 // Table header.
	 	 echo '<table class="course_list" align="center" cellspacing="5" cellpadding="5">
	 	 <tr class="row"><td align="left"><b>ID</b></td><td align="left"><b>Course</b></td><td align="left"><b>Professor</b></td></tr>';
	 	
	 	 // Fetch and print all the records:
	 	 while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
	 	 	 echo '<tr class="row"><td align="left">' . $row['ID'] . '</td><td align="left">' . $row['Course'] . '</td><td align="left">' . $row['Professor'] . '</td></tr>';
	 	 }
	
	 	 echo '</table>'; // Close the table.
	 	
	 	 mysqli_free_result ($r); // Free up the resources.	
		 	 	 // Print how many users there are:
		echo "<p align='center'>There are currently $num courses.</p>\n </div></div>";
	
	 } else { // If it did not run OK.
	
	 	 // Public message:
	 	 echo '<p class="error">There are currently no registered users.</p> </div><div>';
	 	
	 	 // Debugging message:
	 	 echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $q . '</p>';
	 	
	 } // End of if ($r) IF.

	 mysqli_close($dbc); // Close the database connection.
	
	 include ('includes/footer.html');
	 ?>
