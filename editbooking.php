<?php
include "checksession.php";
checkUser();
loginStatus(); 
?>

<!DOCTYPE HTML>
<html><head><title>Edit a booking</title> </head>
 <body>

<?php
include "config.php"; //load in any variables
$DBC = mysqli_connect("127.0.0.1", DBUSER, DBPASSWORD, DBDATABASE);

if (mysqli_connect_errno()) {
  echo "Error: Unable to connect to MySQL. ".mysqli_connect_error() ;
  exit; //stop processing the page further
};

//function to clean input but not validate type and content
function cleanInput($data) {  
  return htmlspecialchars(stripslashes(trim($data)));
}

//retrieve the roomid from the URL
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $id = $_GET['id'];
    if (empty($id) or !is_numeric($id)) {
        echo "<h2>Invalid booking ID</h2>";
        exit;
    } 
}

//check if we are saving data first by checking if the submit button exists in the array
if (isset($_POST['submit']) and !empty($_POST['submit']) and ($_POST['submit'] == 'Update')) {     
  $error = 0; //clear our error flag
  $msg = 'Error: ';  
    
//bookingID (sent via a form it is a string not a number so we try a type conversion!)   
    if (isset($_POST['id']) and !empty($_POST['id']) and is_integer(intval($_POST['id']))) {
       $id = cleanInput($_POST['id']); 
    } else {
       $error++;
       $msg .= 'Invalid Booking ID ';
       $id = 0;  
    }  
//room
    if (isset($_POST['room']) and !empty($_POST['room'])) {
      $room = cleanInput($_POST['room']); 
    } else {
      $error++;
      $msg .= 'Invalid Room ';
      $room = 0;  
    }
//checkindate
    if (isset($_POST['checkindate']) and !empty($_POST['checkindate'])) {
      $checkindate = cleanInput($_POST['checkindate']); 
    } else {
      $error++;
      $msg .= 'Invalid Checkin Date ';
      $checkindate = 0;  
    } 
//checkoutdate
    if (isset($_POST['checkoutdate']) and !empty($_POST['checkoutdate'])) {
      $checkoutdate = cleanInput($_POST['checkoutdate']); 
    } else {
      $error++;
      $msg .= 'Invalid Checkin Date ';
      $checkoutdate = 0;  
    }
//contactnumber
    if (isset($_POST['contactnumber']) and !empty($_POST['contactnumber'])) {
      $contactnumber = cleanInput($_POST['contactnumber']); 
    } else {
      $error++;
      $msg .= 'Invalid Contact Number ';
      $contactnumber = 0;  
    }      
//extras
    if (isset($_POST['extras']) and !empty($_POST['extras'])) {
      $extras = cleanInput($_POST['extras']); 
    } else {
      $error++;
      $msg .= 'Invalid Booking Extras ';
      $extras = 0;  
    } 
      
    
//save the room data if the error flag is still clear and room id is > 0
    if ($error == 0 and $id > 0) {
        $query = "UPDATE booking SET room=?,checkindate=?,checkoutdate=?,contactnumber=?,extras=?,review=? WHERE bookingID=?";
        $stmt = mysqli_prepare($DBC,$query); //prepare the query
        mysqli_stmt_bind_param($stmt,'sssi', $room, $checkindate, $checkoutdate, $contactnumber, $extras, $id); 
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);    
        echo "<h2>Booking details updated.</h2>";       
    } else { 
      echo "<h2>$msg</h2>".PHP_EOL;
    }      
}
//locate the booking to edit by using the bookingID
$query = 'SELECT bookingID,room,checkindate,checkoutdate,contactnumber,extras,review FROM booking WHERE bookingid='.$id;
$result = mysqli_query($DBC,$query);
$rowcount = mysqli_num_rows($result);
if ($rowcount > 0) {
  $row = mysqli_fetch_assoc($result);
?>
<h1>Booking Details Update</h1>
<h2><a href='listbookings.php'>[Return to the booking listing]</a><a href='/bnb/'>[Return to the main page]</a></h2>

<form method="POST" action="editbooking.php">
  <input type="hidden" name="id" value="<?php echo $id;?>">
   <p>
    <label for="room">Room name (name,type,beds): </label>
    <input type="text" id="room" name="room" minlength="5" maxlength="50" value="<?php echo $row['room']; ?>" required> 
  </p> 
  <p>
    <label for="checkindate">Checkin Date: </label>
    <input type="date" id="checkindate" name="checkindate" value="<?php echo $row['checkindate']; ?>" required> 
  </p>  
  <p>
    <label for="checkoutdate">Checkout Date: </label>
    <input type="date" id="checkoutdate" name="checkindate" value="<?php echo $row['checkoutdate']; ?>" required> 
  </p>
  <p>
    <label for="contactnumber">Contact Number: </label>
    <input type="text" id="contactnumber" name="contactnumber" minlength="12" maxlength="12" value="<?php echo $row['contactnumber']; ?>" required> 
  </p>
  <p>
    <label for="extras">Booking Extras: </label>
    <textarea type="text" id="extras" name="extras" rows="10" cols="30" maxlength="200" readonly><?php echo $row['review']; ?></textarea>
  </p>
  <p>
    <label for="review">Room Review: </label>
    <textarea type="text" id="review" name="review" rows="10" cols="30" maxlength="200" readonly><?php echo $row['review']; ?></textarea>
  </p>
   <input type="submit" name="submit" value="Update">
 </form>
<?php 
} else { 
  echo "<h2>booking not found with that ID</h2>"; //simple error feedback
}
mysqli_close($DBC); //close the connection once done
?>
</body>
</html>
  