<?php

	$days  =  $_POST['w_days'];
	$sdate     = $_POST['sdate'];
	$edate     = $_POST['edate'];
	$period = new DatePeriod(new DateTime($sdate), new DateInterval('P1D'), new DateTime( $edate.'+1 day'));
    foreach ($period as $date) {
        $dates[] = $date->format("Y-m-d");
    }

    $servername = "localhost";
	$username = "root";
	$password = "";
	$database = "db_library";
	$conn = mysqli_connect($servername,$username,$password,$database);
	if (!$conn) {
	    die("Connection failed: " . mysqli_connect_error());
	   }

	$datas = array();
	$entry_data = array();
    
	foreach($dates as $date)
		{
		$sql    = "SELECT count(*) as entries FROM tbl_checkinout WHERE date = '" . $date . "'";
		$result =  $conn->query($sql);

		$total_entries = 0;
		while($row = $result->fetch_assoc())
			{
		  		$total_entries += $row['entries'] ;
		  		
			}
		array_push($datas,$total_entries);
		}

	mysqli_close($conn);
?>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Statistics</title>
	<link rel="stylesheet" href="stats.css" />
	<script type="text/javascript">
		let datas= <?php echo json_encode($datas) ?>;
		let dates= <?php echo json_encode($dates) ?>;

</script>
</head>
<body>
<div id="main">
	<div class="header1">STATISTICS</div><br>
	<div class="info">
	Average visits = <?php echo round(array_sum($datas)/$days,2) ?> visits
	</div>

</div>
<div id=graph>
	<canvas id="myChart"></canvas>
</div>

<script 
src="https://cdn.jsdelivr.net/npm/chart.js"
crossorigin="anonymous"
referrerpolicy="no-refferer"
></script>


<script src="lib_stats.js"></script>

</body>
</html>