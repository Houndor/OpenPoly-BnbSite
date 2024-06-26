<?php
include "checksession.php";
checkUser();
loginStatus(); 
?>

<!DOCTYPE HTML>
<html><head><title>Browse bookings</title> </head>
 <body>

<?php
include "config.php"; //load in any variables
$DBC = mysqli_connect("127.0.0.1", DBUSER, DBPASSWORD, DBDATABASE);

if (mysqli_connect_errno()) {
    echo "Error: Unable to connect to MySQL. ".mysqli_connect_error() ;
    exit; //stop processing the page further
}

//prepare a query and send it to the server
$query = 'SELECT bookingID,room,checkindate,checkoutdate,contactnumber,extras,review FROM booking ORDER BY room';
$result = mysqli_query($DBC,$query);
$rowcount = mysqli_num_rows($result); 
?>
<h1>Booking list</h1>
<h2><a href='makebooking.php'>[Make a Booking]</a><a href="/bnb/">[Return to main page]</a></h2>
<table border="1">
<thead><tr><th>Booking (room, date)</th><th>Customer</th><th>Action</th></tr></thead>
<?php

//makes sure we have bookings
if ($rowcount > 0) {  
    while ($row = mysqli_fetch_assoc($result)) {
	  $id = $row['bookingID'];	
    echo '<tr><td>'.$row['room'] .$row['checkindate'] .$row['checkoutdate'].'</td><td>'.$row['contactnumber'].'</td>';
	  echo     '<td><a href="viewbooking.php?id='.$id.'">[view]</a>';
      echo         '<a href="editbooking.php?id='.$id.'">[edit]</a>';
      echo         '<a href="reviewroom.php?id='.$id.'">[manage reviews]</a>';     
	  echo         '<a href="deletebooking.php?id='.$id.'">[delete]</a></td>';
      echo '</tr>'.PHP_EOL;
   }
} else echo "<h2>No bookings found!</h2>";

mysqli_free_result($result); //free any memory used by the query
mysqli_close($DBC); //close the connection once done
?>
</table>
</body>
</html>
  