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
    $rubrique = "SELECT * from rubrique";

    $selectHomme = sql_get_array("SELECT * FROM homme WHERE htype = 'S' and active = 0");
    $result = mysqli_query ($con, $select);
    $resultRubri = mysqli_query($con,$rubrique);

  }else{

    $select = "SELECT * FROM primes";
    $rubrique = "SELECT * from rubrique";
    $selectHomme = sql_get_array("SELECT hommeId ,hommeLbl , prenom FROM acces_societe_hommes
     INNER JOIN homme on acces_societe_hommes.societeId = homme.societeId
     INNER JOIN societe on acces_societe_hommes.societeId = societe.societeId
     WHERE acces_societe_hommes.ID = $currentUser AND homme.htype = 'S' AND homme.active = 0");
    
    $result = mysqli_query ($con, $select);
    $resultRubri = mysqli_query($con,$rubrique);

  }
  
$message   =  '';
if(isset($_POST['submit']))
{
  $prime_id = $_POST['prime_id'];
  $commentaire    = $_POST['commentaire'];
  $hommeId        = $_POST['hommeId'];
  $ID = $_SESSION['ID'];
  $date = date('Y-m-d');
  $rubrique_id = $_POST['rubrique_id'];
  $dateRattachement = date($_POST['date']);
  
  // Attempt insert query execution
  $insert = "INSERT INTO `homme_prime` (`prime_id`, `hommeId`, `commentaire`,`ID`,`created_at`,`date`,`rubrique_id`) VALUES ('$prime_id', '$hommeId', '$commentaire','$ID','$date','$dateRattachement','$rubrique_id')";
  if(mysqli_query($con, $insert)){
    ini_set('display_errors',1);
    ini_set('display_startup_errors',1);
    error_reporting(E_ALL);
    $message =  "Prime ajouter avec succès";
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

   <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.1/css/bootstrap-select.css" /> 
   <div class="container">
     <div class="row justify-content-center align-items-center">
       <h1>Ajouter une prime à un salarié</h1>    
      </div>
      <hr/>
      <h5 class="text-success text-center" id="message"><?= $message; ?><h5>
        <form action="insert.php" method="POST">
                    <div class="form-group">
                        
                        <input type="hidden" class="form-control" name="ID" value="<?=$_SESSION['ID']?>">
                    </div>
                    <label for="date">Date de rattachement</label>
                    <input type="month" class="form-control" min="2021-01" value="2021-01" name="date">
                <div class="mb-3">
                  <select class="form-control" name="prime_id">
                    <option>Selectionner la prime</option>
                    <?php foreach($result as $key => $value){ ?>
                      <option  value="<?= $value['prime_id'];?>"><?= $value['libelle'].' '.$value['codeCL']; ?></option> 
                      <?php } ?>
                    </select>
                  </div>
                  <div class="mb-3">
                    <select class="form-control selectpicker" data-live-search="true" name="hommeId" id="myInput">
                      <option>Selectionner le salarie</option>
                      <?php foreach($selectHomme as $key => $value){ ?>
                        <option id="myTable" value="<?= $value['hommeId'];?>"><?= $value['hommeLbl'].' '.$value['prenom']; ?></option> 
                        <?php } ?>
                      </select>
                    </div>
                    <div class="mb-3">
                    <select class="form-control selectpicker" name="rubrique_id" id="rubrique">
                      <option>Selectionner la rubrique :</option>
                      <?php foreach($resultRubri as $key => $value){ ?>
                        <option id="rubrique" value="<?= $value['rubrique_id'];?>"><?= $value['rubriqueLbl']; ?></option> 
                        <?php } ?>
                      </select>
                    </div>
                    <div class="mb-3">
                        <input type="text" class="form-control" name="commentaire" placeholder="Commentaire ...">
                    </div>
                  
              <div class="mb-3">
                <div class="container">
                  <div class="row">
                    <div class="col"><button type="submit" name="submit" class="col-6 btn btn-primary btn-sm float-left">Submit</button></div>
                    <div class="col"><button type="submit" name="reset" class="col-6 btn btn-secondary btn-sm float-right">Reset</button></div>
                  </div>
                </div>
              </div>
            </form>
      </div>
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.1/js/bootstrap-select.js"></script>
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