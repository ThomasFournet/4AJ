<?php
function sendMailContact($plateformeLogementOu4AJ, $mail, $sujet, $contenu)
// plateformeLogementOu4AJ = 2 si plateformelogement, 1 si 4AJ
{
	if($plateformeLogementOu4AJ == 1)
	{
		$tmp = run('SELECT mailMain as mail FROM mail WHERE id=1')->fetch_object();
		// Pour 4AJ
	}
	else
	{
		$tmp = run('SELECT mailPlateformeLogement as mail FROM mail WHERE id=1')->fetch_object();
		// Pour plateforme logement
	}
	$email = $tmp->mail;
	if (!preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $email)) // On filtre les serveurs qui rencontrent des bogues.
	{
	    $passage_ligne = "\r\n";
	}
	else
	{
	   $passage_ligne = "\n";
	}
	//=====Déclaration des messages au format texte et au format HTML.
	$message_txt = "Mail de : ".$mail."
	".utf8_encode($contenu);	
	$message_html = "Mail de : ".$mail." <br />".nl2br(utf8_encode($contenu));

	//==========
	 
	//=====Création de la boundary
	$boundary = "-----=".md5(rand());
	//==========
	 
	//=====Définition du sujet.
	$sujet = "Formulaire de contact 4AJ || ".utf8_encode($sujet);
	//=========
	 
	//=====Création du header de l'e-mail.
	$header = "From: \"4AJ\"<noreply@4AJ.fr>".$passage_ligne;
	$header.= "Reply-to: \"noreply-4AJ\" <noreply@4AJ.fr>".$passage_ligne;
	$header.= "MIME-Version: 1.0".$passage_ligne;
	$header.= "Content-Type: multipart/alternative;".$passage_ligne." boundary=\"$boundary\"".$passage_ligne;
	//==========
	 
	//=====Création du message.
	$message = $passage_ligne."--".$boundary.$passage_ligne;
	//=====Ajout du message au format texte.
	$message.= "Content-Type: text/plain; charset=\"UTF-8\"".$passage_ligne;
	$message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;
	$message.= $passage_ligne.$message_txt.$passage_ligne;
	//==========
	$message.= $passage_ligne."--".$boundary.$passage_ligne;
	//=====Ajout du message au format HTML
	$message.= "Content-Type: text/html; charset=\"UTF-8\"".$passage_ligne;
	$message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;
	$message.= $passage_ligne.$message_html.$passage_ligne;
	//==========
	$message.= $passage_ligne."--".$boundary."--".$passage_ligne;
	$message.= $passage_ligne."--".$boundary."--".$passage_ligne;
	
	 
	//=====Envoi de l'e-mail.
	mail($email,$sujet,$message,$header);
	//==========
}
?>