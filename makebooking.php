<?php
include "checksession.php";
checkUser();
loginStatus(); 
?>

<!DOCTYPE HTML>
<html><head><title>Make a booking</title> </head>
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


//the data was sent using a form therefore we use the $_POST instead of $_GET
//check if we are saving data first by checking if the submit button exists in the array
    $error = 0; //clear our error flag
    $msg = 'Error: ';
    if (isset($_POST['room']) and !empty($_POST['room']) and is_string($_POST['room'])) {
      $rm = cleanInput($_POST['room']); 
      $room = (strlen($rm)>50)?substr($rm,1,50):$rm; //check length and clip if too big
      //we would also do context checking here for contents, etc       
    } else {
      $error++; //bump the error flag
      $msg .= 'Invalid roomname '; //append eror message
      $room = '';  
    } 

//checkindate    
    if (isset($_POST['checkindate']) and !empty($_POST['checkindate']) and is_string($_POST['checkindate'])) {
      $cid = cleanInput($_POST['room']); 
      $checkindate = checkdate ( $day, $month, $year ); //check length and clip if too big
      //we would also do context checking here for contents, etc       
    } else {
      $error++; //bump the error flag
      $msg .= 'Invalid checkin date '; //append eror message
      $checkindate = '';  
    } 

//checkoutdate
    if (isset($_POST['checkoutdate']) and !empty($_POST['checkoutdate']) and is_string($_POST['checkoutdate'])) {
      $cod = cleanInput($_POST['room']); 
      $checkoutdate = checkdate ( $day, $month, $year ); //check length and clip if too big
      //we would also do context checking here for contents, etc       
    } else {
      $error++; //bump the error flag
      $msg .= 'Invalid checkout date'; //append eror message
      $checkoutdate = '';  
    } 

//contactnumber
    if (isset($_POST['contactnumber']) and !empty($_POST['contactnumber']) and is_string($_POST['contactnumber'])) {
      $cn = cleanInput($_POST['contactnumber']); 
      $contactnumber = (strlen($cn)>50)?substr($cn,1,50):$cn; //check length and clip if too big
      //we would also do context checking here for contents, etc       
    } else {
      $error++; //bump the error flag
      $msg .= 'Invalid contact number '; //append eror message
      $contactnumber = '';  
    }   

//extras
    if (isset($_POST['extras']) and !empty($_POST['extras']) and is_string($_POST['extras'])) {
      $ex = cleanInput($_POST['extras']); 
      $extras = (strlen($ex)>200)?substr($ex,1,200):$ex; //check length and clip if too big
      //we would also do context checking here for contents, etc       
    } else {
      $error++; //bump the error flag
      $msg .= 'Invalid extras '; //append eror message
      $extras = '';  
    } 

//review
    if (isset($_POST['review']) and !empty($_POST['review']) and is_string($_POST['review'])) {
      $rv = cleanInput($_POST['review']); 
      $review = (strlen($rv)>200)?substr($rv,1,200):$rv; //check length and clip if too big
      //we would also do context checking here for contents, etc       
    } else {
      $error++; //bump the error flag
      $msg .= 'Invalid review '; //append eror message
      $review = '';  
    } 
      
    
//save the room data if the error flag is still clear and room id is > 0
    if ($error == 0) {
        $query = "INSERT INTO booking (room,checkindate,checkoutdate,contactnumber,extras,review) VALUES (?,?,?,?,?,?)";
        $stmt = mysqli_prepare($DBC,$query); //prepare the query
        mysqli_stmt_bind_param($stmt,'sssd', $room, $checkindate, $checkoutdate, $contactnumber, $extras, $review); 
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);    
        echo "<h2>Make a Booking.</h2>";          
    } else { 
      echo "<h2>$msg</h2>".PHP_EOL;
    }      
?>
<h1>Make a Booking</h1>
<h2><a href='listbookings.php'>[Return to the booking listing]</a><a href='/bnb/'>[Return to the main page]</a></h2>

<form method="POST" action="makebooking.php">
  <input type="hidden" name="id" >
   <p>
    <label for="room">Room name (name,type,beds): </label>
    <input type="text" id="room" name="room" minlength="5" maxlength="50" required> 
  </p> 
  <p>
    <label for="checkindate">Checkin Date: </label>
    <input type="date" id="checkindate" name="checkindate" minlength="10" maxlength="10"  required> 
  </p>  
  <p>
    <label for="checkoutdate">Checkout Date: </label>
    <input type="date" id="checkoutdate" name="checkindate" minlength="10" maxlength="10"  required> 
  </p>
  <p>
    <label for="contactnumber">Contact Number: </label>
    <input type="text" id="contactnumber" name="contactnumber" minlength="12" maxlength="12"  required> 
  </p>
  <p>
    <label for="extras">Booking Extras: </label>
    <textarea type="text" id="extras" name="extras" rows="10" cols="30" maxlength="200"></textarea>
  </p>
  <p>
    <label for="review">Room Review: </label>
    <textarea type="text" id="review" name="review" rows="10" cols="30" maxlength="200"></textarea>
  </p>
   <input type="submit" name="submit" value="ADD">
 </form>
<?php 
mysqli_close($DBC); //close the connection once done
?>
</body>
</html>
  