<?php
include_once 'request/contact.php';
$_SESSION['backgroundBody']='#dec32c';
include_once 'tinymcetxt.php';
if(!empty($_POST['subject']) && !empty($_POST['email']) && !empty($_POST['contenu']) && !empty($_POST['verif_code']) && !empty($_POST['choix_forme']) && empty($_POST['name']))
{
	if (($_POST['verif_code']==$_SESSION['aleat_nbr']) && ($_POST['choix_forme']==$_SESSION['aleat_nbr_forme']))
	{
		if(preg_match("#^[a-zA-Z0-9.+/=!\#%&'*/?^`{|}~_-]+@[a-zA-Z0-9.+/=!\#%&'*/?^`.{|}~_-]+\.[a-z]+$#", $_POST['email']))
		{
			sendMailContact(3, $mysqli->real_escape_string($_POST['email']), $mysqli->real_escape_string($_POST['subject']), $mysqli->real_escape_string($_POST['contenu']));
			$confirmationContact= "Message envoyé";
		}
	}
	else
	{
		$confirmationContact= "Erreur aux questions de securité";
	}
}
//Selection aléatoire nombre pour forme
$chiffreForme = mt_rand(1,3);
$_SESSION['aleat_nbr_forme'] = $chiffreForme;

include_once '/view/nosResidences/residenceAnneFrank.php';
?>