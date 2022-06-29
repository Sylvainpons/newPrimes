<?php 
require_once '../header.php';
if (isset($_SESSION['loggedin'])) {
	header('Location: /index.php');
	exit;
}
if (!isset($_SESSION['loggedin'])) {
	header('Location: /index.php');
	exit;
}
$currentUser = $_SESSION['ID'];

$privileges = sql_get_array("SELECT * from acces_priv INNER JOIN priv on acces_priv.privId = priv.privId 
INNER JOIN acces ON acces_priv.ID = acces.ID WHERE acces.ID =$currentUser AND priv.privId = '26' OR priv.privId= '27'");
  foreach ($privileges as $privilege => $value ) {
}
  if (($value['privId'] === '26' OR $value['privId'] === '27')) {

      $message   =  '';
      if(isset($_POST['submit']))
      {
          $libelle = $_POST['libelle'];
          $codeCL    = $_POST['codeCL'];
          $rubrique = $_POST['rubrique'];
          
          // Attempt insert query execution
          $insert = "INSERT INTO `primes` (`libelle`,`codeCL`) VALUES ('$libelle','$codeCL')";
          //  $insert = "INSERT INTO homme_prime (prime_id, hommeId,commentaire) VALUES ($prime_id, '3',$commentaire)";
          if(mysqli_query($con, $insert)){
              $message =  "Prime crée avec succes.";
            } 
            else
            {
                ini_set('display_errors',1);
                ini_set('display_startup_errors',1);
                error_reporting(E_ALL);
                $message = "ERROR: Could not able to execute $insert. " . mysqli_error($con);            }
            // Close con
            mysqli_close($con);
        } 
   
        ?>

<div class="container">
    <div class="title">
        <h1>Création d'une prime :</h1>    
    </div>
    <hr/>
      
    <h5 class="text-success text-center" id="message"><?= $message; ?><h5>
        <form action="insert.php"  method="POST">
            
            <div class="mb-3">
                <label for="libelle">Nom de la prime :</label>
                <input type="text" class="form-control" name="libelle" placeholder="Nom de l'inventaire ..." required>
            </div>
            <div class="mb-3">
                <label for="codeCL">codeCL :</label>
                <input type="text" class="form-control" name="codeCL" placeholder="codeCL ..." required>
            </div>
            
            <div class="form-group">
                <div class="container">   
                    <button type="submit" name="submit" class="col-4 btn btn-primary float-left">Créer la prime</button>
                </div>
            </div>
        </form>      
    </div>
    
    <script>
        $(document).ready(function()
        {
            setTimeout(function()
            {
                $('#message').hide();
            },10000);
        });
    </script>
<?php

require_once '../footer.php';
}else{
    header('Location: ./primes/index.php');
}

?>

