<?php
// Ne pas mettre de header car ça rajouter tout ce qu'il y a dedans
require_once './database.php';

if (!isset($_SESSION['loggedin'])) {
	header('Location: /index.php');
	exit;
}

 $currentUser = $_SESSION['ID'];
 $name =$_SESSION['name'];
	  // $sql_query = "SELECT * FROM inventaires LIMIT 10";
	  // dans le $Get_['id'] c'est ici qu'on met le parametre dans le href de inventaire id export_data/id=? 
	  // CHangement possible avec prime_id mais le remplacer dans le Get
	  $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
	  // Dans le where les {} Ne sont pas obliger et peuvent être supprimer
	  // $sql_query = "SELECT `libelle`,`rubrique`,`codeCL` FROM primes";
	  $sql_query = "SELECT `hommeLbl` as Nom,`paye_matricule` as Matricule,prenom,libelle,`valeur` as valeur_prime,commentaire,  
	  `societeLbl` as Etablissement, `LASTNAME` as Exploitant,`created_at` as date_de_saisie,`date` as date_rattachement  FROM `homme`
	INNER JOIN homme_prime on homme.hommeId = homme_prime.hommeId
	INNER JOIN primes on primes.prime_id = homme_prime.prime_id
	INNER JOIN acces on homme_prime.ID = acces.ID
	LEFT JOIN societe on homme.societeId = societe.societeId";

	function cleanData(&$str)
	{
	  $str = preg_replace("/\t/", "\\t", $str);
	  $str = preg_replace("/\r?\n/", "\\n", $str);
	  if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
	}

	$resultset = mysqli_query($con, $sql_query) or die("database error:". mysqli_error($con));
	$developer_records = array();
	while( $rows = mysqli_fetch_assoc($resultset) ) {
	$developer_records[] = $rows;
}	
if(isset($_GET["export_data"])) {	

	// $filename = "exportPrime.xls";
	$filename = "export_prime_".date('Ymd') . ".xls";			

	header("Content-Type: application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename=\"$filename\"");	
	$show_coloumn = false;
	
	if(!empty($developer_records)) {
		
	  foreach($developer_records as $record) {
		if(!$show_coloumn) {
		  // display field/column names in first row pour séparer les columns metre un ; 
		  echo implode("\t", array_keys($record)) . "\n";
		  $show_coloumn = true;
		}
		array_walk($record,__NAMESPACE__.'\cleanData');
		echo implode("\t", array_values($record)) . "\n";
	  }
    
	}
	exit;  
}
?>
<div class="container">	
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
	<?php require('./header.php') ?>
	<h2>Export les primes en Excel</h2>
	<!-- <a class="btn btn-primary" href="/primes/index.php">Retour liste des primes</a> -->
	<div class="well-sm col-sm-12">
		<div class="btn-group pull-right">	
			<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="get">	
			<!-- Reajouter le input hidden dans le formulaire avec le bon paramètre id que l'on a parametrer avant dans l'edit et aussi au dessus	 -->
				<input type="hidden" value="<?php echo $_GET['id']; ?>" name="id">			
				<button type="submit" id="export_data" name='export_data' value="Export to excel" class="btn btn-info">Export excel</button>
			</form>
		</div>
	</div>				  
	<table id="" class="table table-striped table-bordered">
		<tr>
			<th>Libelle prime</th>
			<th>Matricule</th>
			<th>Valeur prime</th>
			<th>prenom</th>
			<th>Nom</th>
			<th>Commentaire</th>
			<th>Etablissement</th>
			<th>Exploitant</th>
			<th>Date de saisie</th>
			<th>Date de Rattachement</th>
		</tr>
		<tbody>
			<?php foreach($developer_records as $developer) { 

			echo "<tr>";

				?>
			   <tr>
			   <?php
				echo "<td>".  utf8_encode($developer ['libelle'])."</td>";
				echo "<td>". $developer ['Matricule']."</td>";  
				echo "<td>". $developer ['valeur_prime']."</td>";  
				echo "<td> ". utf8_encode($developer ['prenom'])."</td>";   
				echo "<td>". utf8_encode($developer ['Nom'])."</td>";
			    echo "<td>". utf8_encode($developer['commentaire'])."</td>";
				echo "<td>".  $developer['Etablissement']."</td>";   
			    echo "<td>". utf8_encode($developer['Exploitant'])."</td>";
			    echo "<td>" . $developer['date_de_saisie']."</td>";   
			    echo "<td>" . $developer['date_rattachement']."</td>";
				
			  echo "</tr>";
			}
			   ?>
		</tbody>
    </table>
</div>

<?php
