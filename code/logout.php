<?php
session_start();
// if(isset($_COOKIE["uname"])){
//     setcookie("uname", "", time()-3600);
// }
// if(isset($_COOKIE["upassword"])){
//     setcookie("upassword", "", time()-3600);
// }
if(isset($_SESSION['uname'])) {
    session_destroy();
    unset($_SESSION['uname']);
    unset($_SESSION['upassword']);
    unset($_SESSION['rid']);
    unset($_SESSION['gid']);
    header("Location: index.php");
} else {
    header("Location: index.php");
}

// if(isset($_COOKIE["uname"])&&isset($_COOKIE["upassword"])){ 
// 	$name=$_COOKIE['uname'];
// 	$password=$_COOKIE["upassword"];
//  	setcookie("uname", $name, time()-3600*24*365);  
//  	setcookie("upassword", $password, time()-3600*24*365);  
//  }  
?>