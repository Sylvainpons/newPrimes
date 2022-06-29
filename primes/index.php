<?php 
// If the user is not logged in redirect to the login page...
require_once '../header.php';
if (!isset($_SESSION['loggedin'])) {
	header('Location: /index.php');
	exit;
}
$date = date('M Y');

// $date = new \DateTime();

echo "<div class='container'>";
$currentUser = $_SESSION['ID'];
// Function demande prime
$privileg = sql_get_one("SELECT * from acces_priv INNER JOIN priv on acces_priv.privId = priv.privId 
INNER JOIN acces ON acces_priv.ID = acces.ID WHERE acces.ID =$currentUser AND priv.privId = '26'");
 
  if (($privileg['privId'] === '26') ? : NULL) {
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
// Fin function demande de prime
	?>
		<!-- Fin demande de prime -->
		<h2 class="mt-3">Listes des primes :</h2>
	<div class="container mb-3 mt-3 exporter">
		<?php
		  $currentUser = $_SESSION['ID'];
	
		  $privileges = sql_get_array("SELECT * from acces_priv INNER JOIN priv on acces_priv.privId = priv.privId 
		  INNER JOIN acces ON acces_priv.ID = acces.ID WHERE acces.ID = $currentUser");
			foreach ($privileges as $privilege) {
			if ($privilege['privId'] === '26' OR $privilege['privId'] === '27') {
			//   echo"<a class='btn btn-info exportTitle mt-2' href='/primes/insert.php'>"."Créer une nouvelle prime";
			  echo"</a>";
			  echo "<a href='../export_data.php' class='btn btn-info exportTitle mt-2'>Exporter les primes : <i class='fas fa-file-download fa-2x'></i></a>";
			}
		 }
		
		?>

	</div>
	<div class="container insertPrime">
		<!-- Demande de prime -->
		<form action="/hommePrime/insert.php" method="POST" name="signupform">
		<input type="hidden" class="form-control" name="ID" value="<?=$_SESSION['ID']?>">
		<div class="login-box">
			<h2>Demande de prime</h2>
		
		<div class="form-row">
			
			<label class="middle" for="date">Date de rattachement: </label>
			<div class="user-box">
				<input type="date" class="inputFields" max='2021-11-1'  min="2021-01-01" value="<?php date("Y-m-d"); ?>" name="date" required/>
			</div>
			
			<div class="form-group">
				<select class="form-control" name="prime_id">
					<option class="form-control">Selectionner la prime</option>
					<?php foreach($result as $key => $value){ ?>
				<option class="form-control" value="<?= $value['prime_id'];?>"><?= $value['libelle']; ?></option> 
				<?php } ?>
			</select>
			
		</div>
		
		<label class="middle" for="valeur">Valeur de la prime:</label>
		<div class="user-box">
			<input type="number" class="form-control" name="valeur" required>	
		</div>
		
		<div class="form-group">
			<select class="form-control" data-live-search="true" name="hommeId" >
				<option class="form-control">Selectionner le salarie</option>
				<?php foreach($selectHomme as $key => $value){ ?>
					<option class="form-control" id="myTable" value="<?= $value['hommeId'];?>"><?= $value['hommeLbl'].' '.$value['prenom']; ?></option> 
				<?php } ?>
			</select>	
		</div>
			
			<label class="middle" for="commentaire">Ajouter un commentaire :</label>
			<div class="user-box">
				<input type="text" class="form-control" name="commentaire">
			</div>
			
			<div class="form-group">
				<input class="form-control" type="submit" id="join-btn" name="submit" alt="Join" value="Créer">
			</div>	
			
		</div>
		
	</div>
</form>
</div>
<ul class="noBullet container">
<table class="table table-bordered table-striped ">
	
	<tr class='inputFields'>
		<td class='inputFields'>Libelle</td>
		<td class='inputFields'>Prenom Salarie</td>
		<td class='inputFields'>Nom Salarie</td>
		<td class='inputFields'>Valeur </td>
		<td>Commentaire</td>
		<td class='inputFields'>Date de création</td>
		<?php

if($privilege['privId'] === '26' OR $privilege['privId'] === '27'){
	$getHommes = sql_get_array("SELECT * FROM `homme_prime`
	INNER JOIN homme on homme_prime.hommeId = homme.hommeId
	INNER JOIN primes on primes.prime_id = homme_prime.prime_id");

echo "<td>Date de rattachement</td>";
echo "<td>Supprimer</td>";
echo"</tr>";

foreach ($getHommes as $homme) {
	
	// $timestamp = strtotime($homme['created_at']);
	// $newDate = date('d-m-Y');
	
	// $rattachement = strtotime($homme['date']);
	$dateRattachement = date('M Y',strtotime($homme['date']));
	// var_dump($dateRattachement);
	echo "<form action='index.php' method='POST'>";	//added
	// echo "<input type='hidden' value='". $homme['prime_id']."' name='primeid' />"; //added
	echo "<tr class='inputFields'>";
	echo "<td class='inputFields'>".$homme['libelle'] . "</td>";
	echo "<td class='inputFields'>".$homme['prenom'] . "</td>";
	echo "<td class='inputFields'>".$homme['hommeLbl'] . "</td>";
	echo "<td class='inputFields'>".$homme['valeur'] . '€'."</td>";
	echo "<td class='inputFields'>".$homme['commentaire'] . "</td>";
	echo "<td class='inputFields'>".$homme['created_at'] . "</td>";
	echo "<td class='inputFields'>".$dateRattachement . "</td>";
	// var_dump($dateRattachement);
	?>
	<?php
	if(date("M Y", strtotime($date)) === $dateRattachement  )
	{
	?>
	<td onclick="return confirm('Delete this record?')"><?php echo '<a class="btn btn-danger" href="delete.php?homme_prime_id=' . $homme['homme_prime_id'] . ' ">' ?>Supprimer</td>
	
	<?php
	}
	
	elseif(date("M Y", strtotime($date)) > $dateRattachement)
	{
		?>
			<td onclick="return confirm('Delete this record?')"><?php echo '<a class="btn btn-danger" href="delete.php?homme_prime_id=' . $homme['homme_prime_id'] . ' ">' ?>Supprimer</td>
		
		<?php
	}else{
		echo"<td>"."<button type='button' class='btn btn-danger' disabled data-bs-toggle='button' autocomplete='off'>Suppression impossible</button>"."</td>";

	}
	echo "</tr>";
	echo "</form>"; //added 
	
}

}elseif($privilege['privId']=== '28') {
	
	$getHommeRattachements = $getPrimeHommes = sql_get_array("SELECT * FROM `homme_prime`
	INNER JOIN homme on homme_prime.hommeId = homme.hommeId
	INNER JOIN primes on primes.prime_id = homme_prime.prime_id
	INNER JOIN acces on acces.ID = homme_prime.ID
	INNER JOIN rattachement on homme_prime.ID = rattachement.acces_ID
	WHERE acces.ID = $currentUser");

	$getPrimeHommes = sql_get_array("SELECT * FROM `homme_prime`
	INNER JOIN homme on homme_prime.hommeId = homme.hommeId
	INNER JOIN primes on primes.prime_id = homme_prime.prime_id
	INNER JOIN acces on acces.ID = homme_prime.ID
	LEFT JOIN rattachement on homme_prime.ID = rattachement.acces_ID
	WHERE acces.ID = $currentUser OR rattachement.ID = $currentUser");


foreach ($getPrimeHommes as $prime) {
	// $timestamp = strtotime($prime['created_at']);
	// $newDate = date('d-m-Y');
	
	$rattachement = strtotime($prime['date']);
	$dateRattachement = date('m-Y');
	
	echo "<form action='' method='POST'>";	
	echo "<input type='hidden' value='". $prime['prime_id']."' name='primeid' />"; 
	
	echo "<tr>";
	echo "<td>".$prime['libelle'] . "</td>";
	echo "<td>".$prime['prenom'] . "</td>";
	echo "<td>".$prime['hommeLbl'] . "</td>";
	echo "<td>".$prime['valeur'] . "</td>";
	echo "<td>".$prime['commentaire'] . "</td>";
	echo "<td>".$prime['created_at'] . "</td>";
	
	echo "</tr>";
	echo "</form>"; //added 
	
}

}else{
	header('location: ../index.php');
}

	?>
	</table>

</div>

<?php 

 require_once '../footer.php';