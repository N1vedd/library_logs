<?php

//Define the query
$query = "DELETE FROM tbl_checkinout WHERE id={$_POST['id']} LIMIT 1";

//sends the query to delete the entry
mysql_query ($query);

if (mysql_affected_rows() == 1) { 

echo "deleted"

}?>
    
 