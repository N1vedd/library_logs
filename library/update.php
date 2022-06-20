<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Update</title>
	<link rel="stylesheet" href="update.css" />
</head>
<body>
	<div id=forms>
  <div class= "forms  form1">
	<div class="header">ADD</div>
	  <form method="post" action="csv.php" enctype="multipart/form-data">
		<input type="file" class="form_input" id="csvfile" name="csvfile"><br><br>
		<input type="submit" value="Submit">
	  </form>
  </div>
	<div class= "forms  form2">
		<div class="header">REMOVE</div>
			<form method="post" action="remove.php">
				<label for="roll_no">Roll No:</label><br>
				<input type="text" class="form_input" id="roll_no" name="roll_no"><br><br>
				<input type="submit" value="Submit">
			</form>
	</div>


</div>
</body>
</html>