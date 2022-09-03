<?php 
// DEFAULT VIEW
	session_start();
	$page_title = 'Review Recommendations';
	include ('includes/loggedin_header.html');
	require ('../mysqli_connect.php'); // Connect to the db.
    $user = $_SESSION['user_id'];
    $result = exec("python index.py $user");
    echo $result;
    echo '<h1 style="padding:20px; background:yellow;">The following recommendations are based on the reviews you have liked: <h1/>';

    $q = "SELECT distinct r.review_id from review as r
		inner join favourite as f
		on r.review_id=f.review_id
		where f.andrew_id = '$user'";
	$r = @mysqli_query ($dbc, $q); // Run the query.
	// Count the number of returned rows:
	$num = mysqli_num_rows($r);
	$reviewids = array();
	if ($num > 0) { 
	// Fetch and print all the records:
		while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
			array_push($reviewids,$row['review_id']); 
		}
	}
	
	$users = array();
	for ($i = 0; $i < count($reviewids); $i++) {
		$q1 = "SELECT distinct f.andrew_id from review as r
		inner join favourite as f
		on r.review_id=f.review_id
		where f.review_id = '$reviewids[$i]' and f.andrew_id != '$user'";
		$r1 = @mysqli_query ($dbc, $q1); // Run the query.
			// Count the number of returned rows:
		$num1 = mysqli_num_rows($r1);
		if ($num1 > 0) { 
			while ($row1 = mysqli_fetch_array($r1, MYSQLI_ASSOC)) {
				array_push($users,$row1['andrew_id']); 
			}
		}
	}

	$q2 = "SELECT DISTINCT r.course_id AS Course, cname AS Name, r.andrew_id AS AndrewID, 
    fname, lname, email, feedback AS Feedback, rating AS Rating, timestamp AS Timestamp, 
    r.review_id as ID, f.fav as fav
	from review as r
		inner join favourite as f
		on r.review_id=f.review_id
		join course as c
		on c.course_id = r.course_id
		join user as u on r.andrew_id = u.andrew_id
		where (";
	for ($i = 0; $i < count($users); $i++) {
		if($i == count($users)-1) {
			$q2 .= "f.andrew_id = '".$users[$i]."'";
		} else {
			$q2 .= "f.andrew_id = '".$users[$i]."' OR ";	
		}
	}
	
	$q2 .= ") AND ( ";
	for ($i = 0; $i < count($reviewids); $i++) {
		if($i == count($reviewids)-1) {
			$q2 .= "f.review_id != ".$reviewids[$i];
		} else {
			$q2 .= "f.review_id != ".$reviewids[$i]." AND ";	
		}
	}
	$q2 .= ")";
	
	$r2 = @mysqli_query ($dbc, $q2); // Run the query.
	$num2 = mysqli_num_rows($r2);
	if ($num2 > 0) { // If it ran OK, display the records.

        echo '<div class="review_list"><h1 align="center">Recommended Reviews</h1>';
        // Fetch and print all the records:
        while ($row2 = mysqli_fetch_array($r2, MYSQLI_ASSOC)) {
            $id = $row2['ID'];
            $cid = $row2['Course'];
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
                <p align="right" class="timestamp">'.$row2['Timestamp'].'</p>
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
                <p align="left"><b>Course ID</b>: '.$row2['Course'].'</p>
                <p align="left"><b>Course Name</b>: '.$row2['Name'].'</p>
                <p align="left"><b>Reviewed by</b>: '.$row2['fname'].' '. $row2['lname'].' ('.$row2['email'].')</p>
                <p align="left"><b>Feedback</b>: '.$row2['Feedback'].'</p>
                <p align="left"><b>Rating</b>: '.$row2['Rating'].'</p>';
                $id = $row2['ID'];
                $user = $_SESSION['user_id'];
                $q3 = "SELECT tag_name from reviewtag where review_id = '$id' and andrew_id = '$user'";	
                $r3 = @mysqli_query ($dbc, $q3); // Run the query.
            
                // Count the number of returned rows:
                $num3 = mysqli_num_rows($r3);
                if($num3 > 0) {
                    echo '<p align="left"><b>Tags</b>: ';
                    while ($row3 = mysqli_fetch_array($r3, MYSQLI_ASSOC)) {
                        echo '#'.$row3['tag_name'].' ';
                    }
                    mysqli_free_result ($r3);
                    echo '</p>';
                }
                if($user != $row2['AndrewID']) {
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
        
        mysqli_free_result ($r2); // Free up the resources.	
        echo "<p align='center'>There are currently $num2 reviews.</p>\n </div>";
    } else { // If it did not run OK.

        // Public message:
        echo '<p class="error">There are currently no reviews.</p>';
    }
?>