<?php
if(isset($_GET['section']) && empty($_GET['subSection']))
{
	header('location:index.php?section=plateformeLogement&subSection=main');
}
elseif ($_GET['subSection'] == 'main')
{
	include_once 'tinymcetxt.php';
	include_once '/view/association/plateformeLogement.php';
}
elseif ($_GET['subSection'] == 'accueillir')
{
	include_once 'tinymcetxt.php';
	include_once '/view/association/accueillir.php';
}
elseif ($_GET['subSection'] == 'informer')
{
	include_once 'tinymcetxt.php';
	include_once '/view/association/informer.php';
}
elseif ($_GET['subSection'] == 'atelier')
{
	include_once 'tinymcetxt.php';
	include_once '/view/association/atelier.php';
}
elseif ($_GET['subSection'] == 'accompagner')
{
	include_once 'tinymcetxt.php';
	include_once '/view/association/accompagner.php';
}
elseif ($_GET['subSection'] == 'documenter')
{
	include_once 'tinymcetxt.php';
	include_once '/view/association/documenter.php';
}
elseif ($_GET['subSection'] == 'contact')
{
	include_once 'tinymcetxt.php';
	include_once 'request/contact.php';
	if(!empty($_POST['subject']) && !empty($_POST['email']) && !empty($_POST['contenu']) && !empty($_POST['verif_code']) && !empty($_POST['choix_forme']) && empty($_POST['nickname']))
	{
		if (($_POST['verif_code']==$_SESSION['aleat_nbr']) && ($_POST['choix_forme']==$_SESSION['aleat_nbr_forme']))
		{
			if(preg_match("#^[a-zA-Z0-9.+/=!\#%&'*/?^`{|}~_-]+@[a-zA-Z0-9.+/=!\#%&'*/?^`.{|}~_-]+\.[a-z]+$#", $_POST['email']))
			{
				sendMailContact(2, $_POST['email'], $_POST['subject'], $_POST['contenu']);	
				$confirmationContact2= "Message envoyé en attente de validation";
			}
		}
		else
		{
			$confirmationContact2= "Erreur aux questions de securité";
		}
	}
	//Selection aléatoire nombre pour forme
	$chiffreForme = mt_rand(1,3);
	$_SESSION['aleat_nbr_forme'] = $chiffreForme;
	
	include_once '/view/association/contact.php';
}
?>