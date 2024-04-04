<?php
include "checksession.php";
checkUser();
loginStatus(); 
?>

<!DOCTYPE HTML>

<html><head><title>Booking Details</title> </head>
 <body>

<?php
include "config.php"; //load in any variables
$DBC = mysqli_connect("127.0.0.1", DBUSER, DBPASSWORD, DBDATABASE);

//check if the connection was good
if (mysqli_connect_errno()) {
    echo "Error: Unable to connect to MySQL. ".mysqli_connect_error() ;
    exit; //stop processing the page further
}

//do some simple validation to check if id exists
$id = $_GET['id'];
if (empty($id) or !is_numeric($id)) {
 echo "<h2>Invalid Booking ID</h2>";
 exit;
} 

//prepare a query and send it to the server
$query = 'SELECT * FROM booking WHERE bookingID='.$id;
$result = mysqli_query($DBC,$query);
$rowcount = mysqli_num_rows($result); 
?>
<h1>Booking Details View</h1>
<h2><a href='listbookings.php'>[Return to the Booking listing]</a><a href='/bnb/'>[Return to the main page]</a></h2>
<?php

//makes sure we have the Room
if ($rowcount > 0) {  
   echo "<fieldset><legend>Booking detail #$id</legend><dl>"; 
   $row = mysqli_fetch_assoc($result);
   echo "<dt>Room name:</dt><dd>".$row['room']."</dd>".PHP_EOL;
   echo "<dt>Checkin Date:</dt><dd>".$row['checkindate']."</dd>".PHP_EOL;
   echo "<dt>Checkout Date:</dt><dd>".$row['checkoutdate']."</dd>".PHP_EOL;
   echo "<dt>Contact Number:</dt><dd>".$row['contactnumber']."</dd>".PHP_EOL;
   echo "<dt>Extras:</dt><dd>".$row['extras']."</dd>".PHP_EOL;
   echo "<dt>Room Review:</dt><dd>".$row['review']."</dd>".PHP_EOL; 
   echo '</dl></fieldset>'.PHP_EOL;  
} else echo "<h2>No Room found!</h2>";

mysqli_free_result($result); //free any memory used by the query
mysqli_close($DBC); //close the connection once done
?>
</table>
</body>
</html>
  