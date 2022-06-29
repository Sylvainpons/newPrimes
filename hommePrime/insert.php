<?php 
// If the user is not logged in redirect to the login page...
require_once '../header.php';
if (!isset($_SESSION['loggedin'])) {
	header('Location: index.php');
	exit;
}
$currentUser = $_SESSION['ID'];
// session_start();
$privileges = sql_get_one("SELECT * from acces_priv INNER JOIN priv on acces_priv.privId = priv.privId 
INNER JOIN acces ON acces_priv.ID = acces.ID WHERE acces.ID =$currentUser AND priv.privId = '26'");
 
  if (($privileges['privId'] === '26') ? : NULL) {
    $select = "SELECT * FROM primes";

    $selectHomme = sql_get_array("SELECT * FROM homme WHERE htype = 'S' and active = 0");
    $result = mysqli_query ($con, $select);

  }else{

    $select = "SELECT * FROM primes";
    $selectHomme = sql_get_array("SELECT hommeId ,hommeLbl , prenom FROM acces_societe_hommes
     INNER JOIN homme on acces_societe_hommes.societeId = homme.societeId
     INNER JOIN societe on acces_societe_hommes.societeId = societe.societeId
     WHERE acces_societe_hommes.ID = $currentUser AND homme.htype = 'S' AND homme.active = 0");
    
    $result = mysqli_query ($con, $select);

  }
  
$message   =  '';
if(isset($_POST['submit']))
{
  $prime_id = $_POST['prime_id'];
  $commentaire    = $_POST['commentaire'];
  $hommeId        = $_POST['hommeId'];
  $valeur         = $_POST['valeur'];
  $ID = $_SESSION['ID'];
  $date = date('Y-m-d');
  $dateRattachement = date($_POST['date']);
  
  // Attempt insert query execution
  $insert = "INSERT INTO `homme_prime` (`prime_id`, `hommeId`, `commentaire`,`ID`,`created_at`,`date`,`valeur`) VALUES ('$prime_id', '$hommeId', '$commentaire','$ID','$date','$dateRattachement','$valeur')";
  if(mysqli_query($con, $insert)){
    ini_set('display_errors',1);
    ini_set('display_startup_errors',1);
    error_reporting(E_ALL);
    header('Location: ../primes/index.php');
  } 
  else
  {
    ini_set('display_errors',1);
    ini_set('display_startup_errors',1);
    error_reporting(E_ALL);
    $message = "ERROR: Could not able to execute $insert. " . mysqli_error($con);
  }
  // Close con
  mysqli_close($con);
} 
?>

