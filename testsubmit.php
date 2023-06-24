<?php

require 'includes/init.php';
$conn = require 'includes/db.php';
Auth::requireLogin();


$message = "";
//if(isset($_POST['SubmitButton'])){ //check if form was submitted

  
  
var_dump(  ReqSpSlideID::getBillandJobTableWithStart($conn, $start = '0'));
//die();
//}    
?>

<html>
<body>    
<form action="" method="post">
<?php echo $message; ?>
  <input type="text" name="inputText"/>
  <input type="submit" name="SubmitButton"/>
</form>    
</body>
</html>