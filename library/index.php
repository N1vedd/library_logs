<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
	<link rel="stylesheet" href="statistics.css">

	<title>Logs</title>
</head>
<body>
<div class="main">

<form method="post" action="index.php">
  <label for="password">ENTER YOUR PASSWORD</label><br>
  <input type="password" class="form_input" id="password" name="password"><br><br>
  <input type="submit" value="Submit">
</form>
</div>
 <?php
session_start();
$_SESSION["LOGIN"] = "LOGGED_IN";
if (!empty($_POST["password"]))
    {
	$entry_details = "amma";
	if($_POST["password"]=='amma')
	{
		$_SESSION["LOGIN"] = "LOGGED_IN";
		return header('location: http://localhost/library/home.php');
	}
	else
	{
		
		echo '<script>alert("INCORRECT PASSWORD!!")</script>';
	}
    }
?>
</body>
</html>