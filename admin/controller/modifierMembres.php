<?php
include_once 'request/modifierMembres.php';
if(!isAdminMembres() || empty($_GET['modif']) || !is_numeric($_GET['modif']))
{
	header('location:index.php');
}

if(!empty($_POST['id']) && is_numeric($_POST['id']) && !empty($_POST['nom']) && !empty($_POST['prenom']))
{
	$id = $_POST['id'];
	$nom = $mysqli->real_escape_string($_POST['nom']);
	$prenom = $mysqli->real_escape_string($_POST['prenom']);
	$adresse = '';
	$dateNaissance = '';
	$telFixe = '';
	$telPortable = '';
	$password = '0';
	$isSuperAdmin = '0';
	if(!empty($_POST['adresse']))
	{
		$adresse = $mysqli->real_escape_string($_POST['adresse']);
	}
	if(!empty($_POST['dateNaissance']))
	{
		$date = $mysqli->real_escape_string($_POST['dateNaissance']);
		$date = explode('/', $date);
		$dateNaissance = $date[2].'-'.$date[1].'-'.$date[0];
	}
	if(!empty($_POST['telFixe']))
	{
		$telFixe = $mysqli->real_escape_string($_POST['telFixe']);
	}
	if(!empty($_POST['telPortable']))
	{
		$telPortable = $mysqli->real_escape_string($_POST['telPortable']);
	}
	if(!empty($_POST['password']))
	{
		$password = md5($_POST['password']);
	}
	if(!empty($_POST['isSuperAdmin']))
	{
		$isSuperAdmin = 1;
	}
	else {
		// Ceci permet de vérifier qu'il y aura toujours au moins un super admin dans la bdd
		// Tout d'abord il faut vérifier que le membre était super admin avant
		// Ensuite compter le nombre de super admin
		$tmp = run('SELECT isSuperAdmin FROM membre WHERE id='.$id)->fetch_object();
		if($tmp->isSuperAdmin == 1)
		{
			$nbre = run('SELECT COUNT(*) as nbre FROM membre WHERE isSuperAdmin=1')->fetch_object();
			if($nbre->nbre == 1)
			{
				$isSuperAdmin = 1;
				$message = 'Ce membre ne peut pas être rétrogradé tant qu\'il n\'y a pas un autre super administrateur';
			}
		}
	}
	updateMembre($id, $nom, $prenom, $adresse, $dateNaissance, $telFixe, $telPortable, $isSuperAdmin, $password);
}
$infoMembre = infoMembres($_GET['modif']);


include_once 'view/modifierMembres.php';
?>