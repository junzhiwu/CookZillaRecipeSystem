<!-- 
This is where we connect to the mysql database.
If you want to get access to database across multiple files, just include the file once and access it anywhere.
 -->

<?php
//connect to mysql database
$con = mysqli_connect("localhost", "username", "password", "try") or die("Error " . mysqli_error($con)); 
// if(isset($_SESSION['uname'])!="") {
//     echo "hello".$_SESSION['uname'];
// }
// die would stop script immediately
// if (mysqli_connect_error()) {
// 	die("Could not connect to database");
// }

// $query = "SELECT * FROM User";
// if($result = mysqli_query($con, $query)){ //return true if query is successful, name $result as that query
// 	echo "It worked!";
// 	$row = mysqli_fetch_array($result); //fetch an array from a query that just take in place
// 	// $row will be the array containing the results from the first query
// 	print_r($row);
// }else {
// 	echo "It failed";
// }
?>