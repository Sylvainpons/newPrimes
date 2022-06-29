<?php
session_start();
require_once './header.php';
// Preparing the query

$stmt = $con->prepare("SELECT ID, PASSWD,LASTNAME FROM acces WHERE EMAIL = ? AND PASSWD = ?");

// D'abord crypter le mot de passe et ensuite passer la var dans le param
$cryptedPassword = sha1($_POST['PASSWD']);
// Binding the Parameters/ s veut dire que c'est un string
$stmt->bind_param('ss',$_POST['EMAIL'],$cryptedPassword);



// Execute Query
$stmt->execute();
// Le bind résult le faire dans l'ordre de la requete et le bind récupere la requête
// Store Result
$stmt->store_result();
$stmt->bind_result($id,$password,$lastname);
$stmt->fetch();
if($stmt->num_rows > 0) {
  //Déclarer les variables de session
  $_SESSION['loggedin'] = TRUE;
  $_SESSION['ID'] = $id;
  $_SESSION['name'] = $lastname;
 header('/primes/index.php');
}else{
  echo "<div class='alert alert-danger'><strong>Failed to Login!</strong>Username or Password is not correct !</div>";
}
// Function to encrypt password from username:password to sha1 encryption
// function encryptsha($lastname,$pass) {
//   $lastname = strtoupper($lastname);
//   $pass = strtoupper($pass);
//   return sha1($lastname.':'.$pass);
// }

// Setting up variables with a POST method from html form.
// $Lastname = $_POST['EMAIL'];
// $password = encryptsha($Lastname, $_POST['PASSWD']);



// Check if row exist
?>

