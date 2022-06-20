<?php

	$roll_no   =  $_POST['roll_no'];
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
    
    $namequery = "SELECT s_name FROM tbl_students WHERE s_regno = '" . $roll_no . "'";
	$result =  $conn->query($namequery);

	while($row = $result->fetch_assoc()){
		$name = $row['s_name'];
	}

	foreach($dates as $date)
		{
		$sql    = "SELECT * FROM tbl_checkinout WHERE date = '" . $date . "' AND regno = '" . $roll_no . "' ORDER by date";
		$result =  $conn->query($sql);

		$total_time_spent = 0;
		$entries = 0;

		while($row = $result->fetch_assoc())
			{
		  		$total_time_spent += $row['used'] ;
		  		$entries += 1;
		  		
			}
		array_push($datas,$total_time_spent);
		array_push($entry_data,$entries);
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
		let entry_data= <?php echo json_encode($entry_data) ?>;

</script>
</head>
<body>
<div id="main">
	<div class="header1">STATISTICS</div><br>
	<div class="info">
	Roll no = <?php echo $roll_no ?>&nbsp;&nbsp;&nbsp;&nbsp;Name = <?php echo $name ?><br>
	Total visits = <?php echo array_sum($entry_data) ?> times&nbsp;&nbsp;&nbsp;&nbsp;Total time spent = <?php echo array_sum($datas) ?> minutes&nbsp;&nbsp;&nbsp;&nbsp;Average Daily Visits = <?php echo round(array_sum($entry_data)/count($dates),2) ?> visits
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


<script src="stats.js"></script>

</body>
</html>
