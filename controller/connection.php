<?php
include_once 'request/connection.php';
if(!empty($_POST['mail']) && !empty($_POST['password']))	// Connection
{
	$mail = $mysqli->real_escape_string($_POST['mail']); 
	$password = md5($_POST['password']);
	$nbreMembre = countMember($mail, $password);	// Count membre retourne 1 si valide
	if($nbreMembre == 1)							// 1,5 si le mail est valide mais pas le password
	{
		$message = "Vous êtes connecté.";
		$_SESSION['log'] = 1;
		$_SESSION['mail'] = $mail;
	}
	elseif($nbreMembre == 1.5)
	{
		$message = "Le mot de passe est invalide.";
	}
	else
	{
		$message = "L'utilisateur n'existe pas.";
	}
}
include_once 'view/connection.php';
?>