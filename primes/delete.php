<?php require '../header.php'; $id=0; 
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
if (isset($_GET['homme_prime_id'])) {
	$id = $_GET['homme_prime_id'];
	$insert = mysqli_query($con, "DELETE FROM homme_prime WHERE homme_prime_id=$id");
    
	echo "prime deleted!"; 
	header('location: index.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    	<link href="css/bootstrap.min.css" rel="stylesheet">
    <img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" data-wp-preserve="%3Cscript%20src%3D%22js%2Fbootstrap.min.js%22%3E%3C%2Fscript%3E" data-mce-resize="false" data-mce-placeholder="1" class="mce-object" width="20" height="20" alt="<script>" title="<script>" />
</head>
 
<body>

<br />
<div class="container">
     

<br />
<div class="span10 offset1">

<br />
<div class="row">

<br />
<h3>Delete a user</h3>
<p>

</div>
<p>

                     
<br />
<form class="form-horizontal" action="delete.php" method="post">
                      <input type="hidden" name="homme_prime_id" value="<?php echo $id;?>"/>
                      
Are you sure to delete ?

<br />
<div class="form-actions">
   
                          <button type="submit" class="btn btn-danger">Yes</button>
                          <a class="btn" href="index.php">No</a>
</div>
<p>

                    </form>
<p>
</div>
<p>

                 
</div>
<p>
  </body>
</html>