<?php # Script 9.4 - view_courses.php
	 // This script retrieves all the records from the courses table.
	
	 $page_title = 'Courses';
	 include ('includes/loggedin_header.html');
	
	 include ('includes/search_course.php');
	 // Page header:
	 echo '<h1 align="center">Courses</h1>';

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    $errors = array(); // Initialize an error array.

    if (empty($errors)) { // If everything's OK.
         require ('../mysqli_connect.php'); // Connect to the db.

          if (empty($_GET['course_id'])) {
                $sid = "";
          } else {
                $sid = trim($_GET['course_id']);
          }
        
         // Make the query:
        $q = "SELECT course_id AS ID, cname AS Course,prof_name AS Professor
	    FROM course WHERE course_id = '$sid' ";


        
        $r = @mysqli_query ($dbc, $q); // Run the query.
        $num = mysqli_num_rows($r);


        if ($num > 0) { 

               echo '<table align="center" cellspacing="3" cellpadding="3" width="75%">
              <tr><td align="left"><b>ID</b></td><td align="left"><b>Course</b></td><td align="left"><b>Professor</b></td></tr>';
             
              // Fetch and print all the records:
              while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
                   echo '<tr><td align="left">' . $row['ID'] . '</td><td align="left">' . $row['Course'] . '</td><td align="left">' . $row['Professor'] . '</td></tr>';
              }
        
              echo '</table>'; // Close the table.
             
              mysqli_free_result ($r); // Free up the resources.	
                       // Print how many users there are:
            echo "<p align='center'>There are currently $num courses for your search.</p>\n";
        
         } else { // If it did not run OK.
        
              // Public message:
              echo '<p class="error">There are currently no courses for your search.</p>';
             
              // Debugging message:
              echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $q . '</p>';
             
         } // End of if ($r) IF.  
    }

}    
	 mysqli_close($dbc); // Close the database connection.
	
	 include ('includes/footer.html');


?>