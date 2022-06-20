<?php

$servername = "localhost";
$username = "root";
$password = "";
$database = "db_library";
$conn = mysqli_connect($servername,$username,$password,$database);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
   }


$sql    = "delete FROM tbl_students where s_regno='" . $_POST['roll_no']."'";    
if ($conn->query($sql) === TRUE) {
  echo "data removed successfully";
} else {
  echo "Error: " . $sql . "<br>" . $conn->error;
}

mysqli_close($conn); 
 
 ?>