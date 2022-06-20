<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="statistics.css" />
	<title>Logs</title>
</head>
<body>
<div class="main">
<form method="post" action="report.php">
	<label for="s_date">From:</label><br>
	<input type="date" class="form_input" id="s_date" name="sdate"><br><br>
	<label for="e_date">Till:</label><br>
	<input type="date" class="form_input" id="e_date" name="edate"><br><br>
	<label for="designation">Designation:</label><br>
	<select class="form_input" id="designation" name="designation" size="2"><br><br>
		<option value="Student">Student</option>
    	<option value="Teacher">Teacher</option>
    </select>
	<input type="submit" value="Submit">

</form>
</div>

</body>
</html>