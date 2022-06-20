<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>e-gate register</title>
    <link rel="stylesheet" href="style.css">

</style>
    </style>
</head>
<body>

<div class="heading">
    <div class="heading  heading--1">
        <p>LIBRARY E-GATE REGISTER</p>
    </div>    
    <div class="heading  heading--2">
        <form method="post" action="">
        <label for="ID">ID :</label>
        <input type="text" id="ID" name="ID">
        <input type="submit" value="submit" id="sub">
        </form>
    </div>
</div>
<script src="https://code.jquery.com/jquery-2.2.4.js"></script>

<script>
  $('#ID').keyup(function(){
      if(this.value.length == 16 || this.value.length==8){
      $('#sub').click();
      }
  });
</script>
<?php

$servername = "localhost";
$username = "root";
$password = "";
$database = "db_library";
$conn = mysqli_connect($servername,$username,$password,$database);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
   }


$sql = "SELECT * FROM tbl_checkinout WHERE used is null";

$result =  $conn->query($sql);

$sl=0;

if (!empty($_POST["ID"]))
    {

        $user_id=$_POST["ID"];

        if($user_id[11]=='I')
        {
            $user_id[11]='1';
        }
        $dataquery = "SELECT * FROM tbl_students WHERE s_regno = '" . $user_id . "'";
        $result2 =  $conn->query($dataquery);
        $datacheck=0;
        while($row = $result2->fetch_assoc())
        {
            $name = $row['s_name'];
            $roll_no = $row['s_regno'];
            $dept = $row['s_dept'];
            $year = $row['s_year'];
            $desig = $row['s_desg'];
            $datacheck=1;
        }

        $checkquery = "SELECT * FROM tbl_checkinout WHERE regno = '" . $user_id . "' and used is null";
        $result3 =  $conn->query($checkquery);
        $check = 0;
        while($row = $result3->fetch_assoc())
        {
            $check=1;

        }

        if ($datacheck==0) 
        {
            echo "DATA NOT FOUND";
        }

        elseif($check==1)
        {
            $startquery = "SELECT checkintime FROM tbl_checkinout WHERE regno = '" . $roll_no . "' and used is null";
            $result3 =  $conn->query($checkquery);
            $check = 0;
            while($row = $result3->fetch_assoc())
            {
            $start = $row['checkintime'];
            }
            date_default_timezone_set("Asia/Kolkata");
            $end = date("H:i:s");
            $to_time = strtotime($start);
            $from_time = strtotime($end);
            $mins = intdiv(abs($to_time - $from_time), 60);


            $updatequery = "update tbl_checkinout set checkouttime='".$end."' ,used=".$mins." WHERE (regno = '" . $roll_no . "' and used is null)";
            if(mysqli_query($conn, $updatequery))
            {
                echo "successfully updated";
                echo "<meta http-equiv='refresh' content='0'>";
            }
            else
            {
            echo("Error description: " . $conn -> error);
            }
        }
        else
        {
            date_default_timezone_set("Asia/Kolkata");
            $time= date("H:i:s");
            $date= date("Y-m-d");
            $updatequery = "insert into tbl_checkinout(regno,name,dept,year,design,date,checkintime) values('".$roll_no."','".$name."','".$dept."','".$year."','".$desig."','".$date."','".$time."')";
            if(mysqli_query($conn, $updatequery))
            {
                echo "<meta http-equiv='refresh' content='0'>";
            }
            else
            {
            echo("Error description: " . $conn -> error);
            }
        }        
    }


echo '<div class="table">
        <table>
            <thead>
                <tr>                    
                    <th>S No.</th>
                    <th>Register No.</th>
                    <th>Name</th>
                    <th>Department</th>
                    <th>Year</th>
                    <th>Designation</th>
                    <th>Check-in Time</th>
                    <th>Remove</th>
                    
                </tr>
            </thead>
            <tbody>';
                    
while($row = $result->fetch_assoc()){ 
  $sl=$sl+1;
  $id= $row['id'];
  echo "
  <tr>
    <form action='' method='post'>

      <td>" . $sl . "</td>
      <td>" . $row["regno"] . "</td>
      <td>" . $row["name"] . "</td>
      <td>" . $row["dept"] . "</td>
      <td>" . $row["year"] . "</td>
      <td>" . $row["design"] . "</td>
      <td>" . $row["checkintime"] . "</td>
      <td> <input  id='s_id' type='submit' value='Delete'></input> </td>
      <input type='hidden' name='r_id' id='r_id' value=".$row['id'].">
    </form>
      </tr>";} 
    
    
    if (!empty($_POST["r_id"]))
    {
        $sql2    = "delete FROM tbl_checkinout where id='" . $_POST['r_id']. "' and used is null";   
        mysqli_query($conn, $sql2);
        echo "<meta http-equiv='refresh' content='0'>";
    }

?>
    </tbody>
    </table>




</body>
</html>
