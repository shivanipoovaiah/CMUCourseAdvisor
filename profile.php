<?php

$page_title = 'Profile';
include('includes/loggedin_header.html');
session_start(); // Access the existing session.
	
// If no session variable exists,redirect the user:
if (!isset($_SESSION['user_id'])) {
    // Need the functions:
    require ('includes/login_functions.php');
} else {
    require ('../mysqli_connect.php'); // Connect to the db.

    echo '
    <div class="profile">
        <p><h1>Profile</h1></p>';
        $name = $_SESSION['user_id'];
        // Make the query:
        $q = "SELECT fname AS First, lname AS Last, bname AS Level, badge.badge_id AS ID, addr, phone, email FROM user JOIN badge WHERE andrew_id='$name' AND user.badge_id = badge.badge_id";		 	
        $r = @mysqli_query ($dbc, $q); // Run the query.
        $num = mysqli_num_rows($r);
        if ($num > 0) {
        
	 	 // Fetch and print all the records:
	 	while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
            $badge = $row['ID'];
            $image = $row['Level'];
            $addr = $row['addr'] == NULL? '-' : $row['addr'];
            $phone = $row['phone'] == NULL? '-' : $row['phone'];
            $email = $row['email'] == NULL? '-' : $row['email'];

            echo '
            <div style="display: flex">
                <div style="width:50%;">
                    <p align="left"><b>First Name</b></p>
                    <p align="left" >' . $row['First'] . '</p>
                    <p align="left"><b>Member Level</b></p>
                    <p align="left" >
                        <h2>'.$badge.'-'.$image.'</h2>
                        <img src="img/'.$image.'.jpg"/>
                    </p>
                </div>
                <div style="width:50%;">
                    <p align="left"><b>Last Name</b></p>
                    <p align="left" >' . $row['Last'] . '</p>
                    <p align="left"><b>Address</b></p>
                    <p align="left" >'.$addr . '</p>
                    <p align="left"><b>Phone</b></p>
                    <p align="left" >'.$phone . '</p>
                    <p align="left"><b>Email</b></p>
                    <p align="left" >'.$email . '</p>
                </div>
            </div>
        </div>';
            if($row['ID'] < 4) {
                echo '<p> <h2>Add details to upgrade to Level '.($badge+1).'</h2> </p>';
                if($badge == 1) {
                    include('includes/address.html');
                    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                        $errors = array( ); // Initialize an error array.
                    
                        // Check for an andrew ID
                        if (empty($_POST['addr'])) {
                            $errors[] = 'You forgot to enter your address.';
                        } else {
                            $addr = trim($_POST['addr']);
                        }
                        
                        if (empty($errors)) { // If everything's OK.
                            $badge += 1;
                    
                        // Make the query:
                            $q = "UPDATE user set addr='$addr',badge_id=$badge WHERE andrew_id='$name'";
                            $r = @mysqli_query ($dbc, $q); // Run the query.
                            if ($r) { // If it ran OK.
                                // Print a message:
                                echo '<h1>Thank you!</h1>
                                <p>Your address is added. Please refresh the page</p><p>	
                                <br /></p>';
                            } 
                        } else { // If it did not run OK.
                                
                            // Public message:
                            echo '<h1>System Error</h1>
                            <p class="error">Your address could not be added. We apologize for any inconvenience.</p>';
                            
                            // Debugging message:
                            echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $q . '</p>';
                        } // End of if ($r) IF.
                        exit( );
                    } // End of the main Submit conditional.
                }
                if($row['ID'] == 2) {
                    include('includes/phone.html');
                    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                        $errors = array( ); // Initialize an error array.
                    
                        // Check for an andrew ID
                        if (empty($_POST['phone'])) {
                            $errors[] = 'You forgot to enter your phone.';
                        } else {
                            $phone = trim($_POST['phone']);
                        }
                        
                        if (empty($errors)) { // If everything's OK.
                            $badge += 1;
                    
                        // Make the query:
                            $q = "UPDATE user set phone='$phone',badge_id=$badge WHERE andrew_id='$name'";
                            $r = @mysqli_query ($dbc, $q); // Run the query.
                            if ($r) { // If it ran OK.
                                // Print a message:
                                echo '<h1>Thank you!</h1>
                                <p>Your phone is added. Please refresh the page</p><p>	
                                <br /></p>';
                            } 
                        } else { // If it did not run OK.
                                
                            // Public message:
                            echo '<h1>System Error</h1>
                            <p class="error">Your phone could not be added. We apologize for any inconvenience.</p>';
                            
                            // Debugging message:
                            echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $q . '</p>';
                        } // End of if ($r) IF.
                        exit( );
                    } // End of the main Submit conditional.
                }
                if($row['ID'] == 3) {
                    include('includes/email.html');
                    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                        $errors = array( ); // Initialize an error array.
                    
                        // Check for an andrew ID
                        if (empty($_POST['email'])) {
                            $errors[] = 'You forgot to enter your email.';
                        } else {
                            $email = trim($_POST['email']);
                        }
                         
                        if (empty($errors)) { // If everything's OK.
                        $badge += 1;
                    
                         // Make the query:
                        $q = "UPDATE user set email='$email',badge_id=$badge WHERE andrew_id='$name'";
                        $r = @mysqli_query ($dbc, $q); // Run the query.
                         if ($r) { // If it ran OK.
                            // Print a message:
                            echo '<h1>Thank you!</h1>
                               <p>Your email is added. Please refresh the page</p><p>	
                               <br /></p>';
                        } else { // If it did not run OK.
                                
                            // Public message:
                            echo '<h1>System Error</h1>
                              <p class="error">Your email could not be added. We apologize for any inconvenience.</p>';
                            
                            // Debugging message:
                            echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $q . '</p>';
                        } // End of if ($r) IF.
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
                }
            }
        }
    }

    mysqli_free_result ($r); // Free up the resources.	
                // Print how many users there are:  
    mysqli_close($dbc);
    echo '</div>';
}

// Include the footer and quit the script:
include ('includes/footer.html');
?>
