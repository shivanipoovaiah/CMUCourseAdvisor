<?php # Script 9.4
	// This script retrieves all the records from the courses table.
	session_start( );
	$page_title = 'Reviews';
	include ('includes/loggedin_header.html');
	include ('includes/tagsearch.html');
	require ('../mysqli_connect.php'); 
    $user = $_SESSION['user_id'];

	// IF SEARCH FORM IS SUBMITTED - FILTERED VIEW
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $errors = array( );
        if (!empty($_POST['reviewsearch'])) {
            $search = $_POST['reviewsearch'];
            $type = $_POST['searchtype'];
        
            if($type == 'course_id') {
                $q = "SELECT r.course_id AS Course, cname AS Name, r.andrew_id AS AndrewID, 
                fname, lname, email, feedback AS Feedback, rating AS Rating, timestamp AS Timestamp, 
                r.review_id as ID, f.fav as fav
                FROM review as r   LEFT OUTER JOIN course as c
                    on r.course_id = c.course_id
                    INNER JOIN user as u
                        on r.andrew_id = u.andrew_id 
                        LEFT OUTER JOIN (SELECT * from Favourite where andrew_id = '$user') as f
                            ON r.review_id = f.review_id
                WHERE r.course_id = '$search'
                ORDER BY fav DESC, Name";
            } else {
                $q = "SELECT r.course_id AS Course, cname AS Name, r.andrew_id AS AndrewID, 
                fname, lname, email, feedback AS Feedback, rating AS Rating, timestamp AS Timestamp, 
                r.review_id as ID, f.fav as fav
                FROM review as r   LEFT OUTER JOIN course as c
                    on r.course_id = c.course_id
                    INNER JOIN user as u
                        on r.andrew_id = u.andrew_id 
                        LEFT OUTER JOIN (SELECT * from Favourite where andrew_id = '$user') as f
                            ON r.review_id = f.review_id
                WHERE cname LIKE '%$search%'
                ORDER BY fav DESC, Name";
            }
    
            $r = @mysqli_query ($dbc, $q); // Run the query.

            // Count the number of returned rows:
            $num = mysqli_num_rows($r);
                
            if ($num > 0) { // If it ran OK, display the records.

                echo '<div class="review_list"><h1 align="center">Reviews</h1>';
                // Fetch and print all the records:
                while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
                    $id = $row['ID'];
                    $cid = $row['Course'];
                    $qf = "SELECT fav from favourite 
                        WHERE andrew_id='".$user."' AND review_id='".$id."';";
                    $rf = @mysqli_query ($dbc, $qf);
                    $numf = mysqli_num_rows($rf);
                    $checked = 0;
                    if($numf > 0) {
                        while ($rowf = mysqli_fetch_array($rf, MYSQLI_ASSOC)) {
                            $checked = $rowf['fav'];
                        }
                    } 
                    
                    if($checked == 1) $addCheckStyle = 'checked';
                    else  $addCheckStyle = '';
                    echo '
                    <div class="review">
                        <p align="right" class="timestamp">'.$row['Timestamp'].'</p>
                        <p align="right" class="favourite">
                            <!DOCTYPE html>
                                <html>
                                    <head>
                                        <link rel="stylesheet/less" type="text/css" href="../dashboard/stylesheets/less/fav.less" />
                                        <script type="text/javascript" src="../dashboard/javascripts/script.js"></script>
                                        <script src="https://cdn.jsdelivr.net/npm/less@4" type="text/javascript"></script>
                                    </head>
                                    <body>';
                                echo '
                                    <div id="main-content">
                                        <div>
                                        <form id="fav_form-'.$id.'" action="reviews.php" method="post">
                                        <input type="text" style="display:none;" id="fav-'.$id.'" name="fav" value='.$checked.' />
                                        <input type="text" style="display:none;" name="favreview_id" value="'.$id.'" />
                                        <input type="text" style="display:none;" name="favcourse_id" value="'.$cid.'" />
                                        <input  style="display:none;" type="checkbox" id="checkbox-'.$id.'" onclick="favSubmit(event, this,'.$checked.')" '.$addCheckStyle.'/>
                                            <label for="checkbox-'.$id.'">
                                                <svg id="heart-svg" viewBox="467 392 58 57" xmlns="http://www.w3.org/2000/svg">
                                                <g id="Group" fill="none" fill-rule="evenodd" transform="translate(467 392)">
                                                    <path d="M29.144 20.773c-.063-.13-4.227-8.67-11.44-2.59C7.63 28.795 28.94 43.256 29.143 43.394c.204-.138 21.513-14.6 11.44-25.213-7.214-6.08-11.377 2.46-11.44 2.59z" id="heart" fill="#AAB8C2"/>
                                                    <circle id="main-circ" fill="#E2264D" opacity="0" cx="29.5" cy="29.5" r="1.5"/>
                                        
                                                    <g id="grp7" opacity="0" transform="translate(7 6)">
                                                    <circle id="oval1" fill="#9CD8C3" cx="2" cy="6" r="2"/>
                                                    <circle id="oval2" fill="#8CE8C3" cx="5" cy="2" r="2"/>
                                                    </g>
                                        
                                                    <g id="grp6" opacity="0" transform="translate(0 28)">
                                                    <circle id="oval1" fill="#CC8EF5" cx="2" cy="7" r="2"/>
                                                    <circle id="oval2" fill="#91D2FA" cx="3" cy="2" r="2"/>
                                                    </g>
                                        
                                                    <g id="grp3" opacity="0" transform="translate(52 28)">
                                                    <circle id="oval2" fill="#9CD8C3" cx="2" cy="7" r="2"/>
                                                    <circle id="oval1" fill="#8CE8C3" cx="4" cy="2" r="2"/>
                                                    </g>
                                        
                                                    <g id="grp2" opacity="0" transform="translate(44 6)">
                                                    <circle id="oval2" fill="#CC8EF5" cx="5" cy="6" r="2"/>
                                                    <circle id="oval1" fill="#CC8EF5" cx="2" cy="2" r="2"/>
                                                    </g>
                                        
                                                    <g id="grp5" opacity="0" transform="translate(14 50)">
                                                    <circle id="oval1" fill="#91D2FA" cx="6" cy="5" r="2"/>
                                                    <circle id="oval2" fill="#91D2FA" cx="2" cy="2" r="2"/>
                                                    </g>
                                        
                                                    <g id="grp4" opacity="0" transform="translate(35 50)">
                                                    <circle id="oval1" fill="#F48EA7" cx="6" cy="5" r="2"/>
                                                    <circle id="oval2" fill="#F48EA7" cx="2" cy="2" r="2"/>
                                                    </g>
                                        
                                                    <g id="grp1" opacity="0" transform="translate(24)">
                                                    <circle id="oval1" fill="#9FC7FA" cx="2.5" cy="3" r="2"/>
                                                    <circle id="oval2" fill="#9FC7FA" cx="7.5" cy="2" r="2"/>
                                                    </g>
                                                </g>
                                                </svg>
                                            </label>
                                        </form>
                                        </div>
                                    </div>';
                                echo '
                                    </body>
                            </html>
                        </p>
                        <p align="left"><b>Course ID</b>: '.$row['Course'].'</p>
                        <p align="left"><b>Course Name</b>: '.$row['Name'].'</p>
                        <p align="left"><b>Reviewed by</b>: '.$row['fname'].' '. $row['lname'].' ('.$row['email'].')</p>
                        <p align="left"><b>Feedback</b>: '.$row['Feedback'].'</p>
                        <p align="left"><b>Rating</b>: '.$row['Rating'].'</p>';
                        $id = $row['ID'];
                        $user = $_SESSION['user_id'];
                        $q2 = "SELECT tag_name from reviewtag where review_id = '$id' and andrew_id = '$user'";	
                        $r2 = @mysqli_query ($dbc, $q2); // Run the query.
                    
                        // Count the number of returned rows:
                        $num2 = mysqli_num_rows($r2);
                        if($num2 > 0) {
                            echo '<p align="left"><b>Tags</b>: ';
                            while ($row2 = mysqli_fetch_array($r2, MYSQLI_ASSOC)) {
                                echo '#'.$row2['tag_name'].' ';
                            }
                            mysqli_free_result ($r2);
                            echo '</p>';
                        }
                        if($user != $row['AndrewID']) {
                            echo '<!DOCTYPE html>
                            <html>
                                <head>
                                    <script type="text/javascript" src="../dashboard/javascripts/script.js"></script>
                                </head>
                                <body>
                                    <form id="tag_form" class="form" action="reviews.php" method="post">
                                        <p><b>Tag: </b><input id="tag" type="text" name="tag" size="15" maxlength="15"/>
                                        <p class="error" id="error_msg" style="display:none">Please enter tag</p>
                                        <input type="text" style="display:none;" name="cid" value="'.$cid.'" />
                                        <input id="reviewid" type="text" name="reviewid" style="display:none" value="'.$id.'"/>
                                        <button id="tag_btn" type="submit" onClick="tagSubmit(event)" value="tag">Add tag</button></p>
                                    </form>
                                </body>
                            </html>';
                        }
                    echo '</div>';
                }
                
                mysqli_free_result ($r); // Free up the resources.	
                echo "<p align='center'>There are currently $num reviews.</p>\n </div>";
            } else { // If it did not run OK.

                // Public message:
                echo '<p class="error">There are currently no reviews.</p>';
            }
        
        } else if (!empty($_POST['tag'])) {
            $tag = trim($_POST['tag']);
            $reviewid = trim($_POST['reviewid']);
            $cid = trim($_POST['cid']);
            $q3 = "INSERT INTO reviewtag (review_id, tag_name, andrew_id, course_id) VALUES ('$reviewid','$tag','$user', '$cid')";
            $r3 = @mysqli_query ($dbc, $q3); // Run the query.
            include('includes/allreviews.php');
        } else if(!empty($_POST['favreview_id']) && isset($_POST['favreview_id'])){
            $fav = $_POST['fav'];
            $favreview_id = $_POST['favreview_id'];
            $favcourse_id = $_POST['favcourse_id'];
            $q4 = "SELECT fav from favourite WHERE review_id = '".$favreview_id."' AND andrew_id='$user'";
            $r4 =  @mysqli_query ($dbc, $q4);
            $num4 = mysqli_num_rows($r4);
                    
            if ($num4 > 0) { 
                $q5 = "UPDATE favourite set fav='$fav' WHERE review_id = '$favreview_id' AND andrew_id='$user'  AND course_id='$favcourse_id'";
            } else {
                $q5 = "INSERT INTO favourite (fav, review_id, andrew_id, course_id) VALUES ('$fav','$favreview_id','$user','$favcourse_id')";
            }
            $r5 = @mysqli_query ($dbc, $q5); // Run the query.
            mysqli_free_result ($r4);
            include('includes/allreviews.php');
        } else {
            include('includes/allreviews.php');
        }

        
        if (empty($errors)) { // If everything's OK.

        } else { // If it did not run OK.
            echo '<h1>System Error</h1>
            <p class="error">Your request could not be processed. We apologize for any inconvenience.</p>';
        }
        exit( );
    } else { // DEFAULT VIEW
        include('includes/allreviews.php');
    }
    mysqli_close($dbc); // Close the database connection.    
    include('includes/footer.html');
?>
