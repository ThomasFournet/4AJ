<?php
include_once 'request/lostPassword.php';
if(!empty($_POST['email']))
{
	$result = resetPassword($_POST['email']);	/* Envoie le mail si il y a un membre avec ce mail et retourne true, sinon return false */
	if(!$result)
	{
		$message = "Le mail ne correspond a aucun membre.";
	}
	else
	{
		$message = "Le mot de passe a été réinitialisé, vous retrouverez le nouveau dans votre boite mail.";
	}
}

include_once 'view/lostPassword.php';
;?>