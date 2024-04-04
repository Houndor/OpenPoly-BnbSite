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
        echo "<h2>Invalid booking ID</h2>"; //simple error feedback
        exit;
    } 
}
//the data was sent using a form therefore we use the $_POST instead of $_GET
//check if we are saving data first by checking if the submit button exists in the array
if (isset($_POST['submit']) and !empty($_POST['submit']) and ($_POST['submit'] == 'Update')) {     

    
//bookingID (sent via a form it is a string not a number so we try a type conversion!)   
    if (isset($_POST['id']) and !empty($_POST['id']) and is_integer(intval($_POST['id']))) {
       $id = cleanInput($_POST['id']); 
    } else {
       $error++; //bump the error flag
       $msg .= 'Invalid Booking ID '; //append error message
       $id = 0;  
    }        
//review
    if (isset($_POST['review']) and !empty($_POST['review'])) {
      $extras = cleanInput($_POST['review']); 
    } else {
      $error++; //bump the error flag
      $msg .= 'Invalid Review '; //append error message
      $extras = 0;  
    } 
      
    
//save the room data if the error flag is still clear and room id is > 0
    if ($error == 0 and $id > 0) {
        $query = "UPDATE booking SET review=? WHERE bookingID=?";
        $stmt = mysqli_prepare($DBC,$query); //prepare the query
        mysqli_stmt_bind_param($stmt,'sssi', $review, $id); 
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);    
        echo "<h2>Booking details updated.</h2>";     
//        header('Location: http://localhost/bnb/reviewroom.php', true, 303);      
    } else { 
      echo "<h2>$msg</h2>".PHP_EOL;
    }      
}
//locate the booking to edit by using the bookingID
//we also include the booking ID in our form for sending it back for saving the data
$query = 'SELECT bookingID,review FROM booking WHERE bookingid='.$id;
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
    <label for="review">Checkin Date: </label>
    <textarea type="text" id="review" name="review" rows="10" cols="30" maxlength="200" require><?php echo $row['review']; ?></textarea>
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
  