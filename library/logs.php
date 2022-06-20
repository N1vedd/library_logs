<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Logs</title>
  <link rel="stylesheet" href="logs.css">
</head>
<body>

  <div class="header">DATABASE LOGS</div>
<div id="tb">
<table class="content-table">
  <thead>
    <tr>
      <th>Date</th>
      <th>Roll No</th>
      <th>Name</th>
      <th>Department</th>
      <th>Year</th>
      <th>Designation</th>
      <th>Check-In</th>
      <th>Check-Out</th>
      <th>Time Spent</th>

    </tr>
  </thead>
  <tbody>

 

<?php

$servername = "localhost";
$username = "root";
$password = "";
$database = "db_library";
$conn = mysqli_connect($servername,$username,$password,$database);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
   }

$sdate = $_POST['sdate'];
$edate = $_POST['edate'];



$sql    = "SELECT * FROM tbl_checkinout WHERE date >= '" . $sdate . "' AND date <= '" . $edate . "' ORDER by id";



$result =  $conn->query($sql);
mysqli_close($conn);

while($row = $result->fetch_assoc()){
  echo "<tr>
      <td>" . $row['date']. "</td> 
      <td>" . $row["regno"] . "</td>
      <td>" . $row["name"] . "</td>
      <td>" . $row["dept"] . "</td>
      <td>" . $row["year"] . "</td>
      <td>" . $row["design"] . "</td>
      <td>" . $row["checkintime"] . "</td>
      <td>" . $row["checkouttime"] . "</td>
      <td>" . $row["used"] . "</td>
      </tr>";} 
?>





  </tbody>
</table>
</div>
<div id="download_div">
  <button id="download" onclick="exportTableToCSV('logs.csv')">DOWNLOAD AS CSV</button>
</div>
<script type="text/javascript">
  function downloadCSV(csv, filename) {
    var csvFile;
    var downloadLink;
    csvFile = new Blob([csv], {type: "text/csv"});
    downloadLink = document.createElement("a");
    downloadLink.download = filename;
    downloadLink.href = window.URL.createObjectURL(csvFile);
    downloadLink.style.display = "none";
    document.body.appendChild(downloadLink);
    downloadLink.click();
    }
  function exportTableToCSV(filename) {
    var csv = [];
    var rows = document.querySelectorAll("table tr");
    for (var i = 0; i < rows.length; i++) {
        var row = [], cols = rows[i].querySelectorAll("td, th");
        
        for (var j = 0; j < cols.length; j++) 
            row.push(cols[j].innerText);
        
        csv.push(row.join(","));        
    }
    downloadCSV(csv.join("\n"), filename);
  }
</script>
</body>
</html>