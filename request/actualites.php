<?php
function isAdminActualite()
// Fonction pour savoir si le membre est admin d'actualite
{
	$mysqli = connection();
	if(!empty($_SESSION['log']) && $_SESSION['log'] == 1 && !empty($_SESSION['mail']))
	{
		$mail 	= $mysqli->real_escape_string($_SESSION['mail']);
		$tmp	= run('SELECT isSuperAdmin FROM membre WHERE mail = "'.$mail.'"');
		$tmp 	= $tmp->fetch_object();
		if($tmp->isSuperAdmin == 1)
		// Si super admin, il a le pouvoir
		{
			return true;
		}
		$tmp 	= run('	SELECT COUNT(*) as nbre
						FROM membre,membrefonction,fonction 
						WHERE membre.id = membrefonction.id 
						AND membrefonction.id_fonction = fonction.id 
						AND mail = "'.$mail.'" 
						AND fonction.isAdminActualite = 1');
		$tmp = $tmp->fetch_object();
		if($tmp->nbre >= 1)
		// Ou si il a une des fonctions dont il fait parti qui est admin sur le livre d'or
		{
			return true;
		}
	}
	return false;
}
function nbrePage($nbreBilletParPage, $type=0)
// Compte le nombre de page qu'il doit y avoir, le nombre passé en paramètre est le nombre de billet par page.
{
	return ceil(nombreActualite($type)/$nbreBilletParPage);
}
function allTypeActualite()
// Retourne la liste de toutes les actualités (où il y a des actualités visibles)
{
	$tmp = run('SELECT id, nom FROM type_d_actualite ORDER BY id');
	$typeActualite = NULL;
	while ($donnees = $tmp->fetch_object())
	{
		if(!empty($_SESSION['mail']))
		{
			$superAdmin = run('SELECT COUNT(*) as admin FROM membre WHERE mail = "'.$_SESSION['mail'].'" AND isSuperAdmin = 1')->fetch_object();
			if($superAdmin->admin == 1)
			{
				$typeActualite[$donnees->id]['id'] = $donnees->id;
				$typeActualite[$donnees->id]['nom'] = $donnees->nom;
			}
			else
			{
				$actu = run('	SELECT COUNT(*) as nbre
						 		FROM news,fonction,newsfonction,membre,membrefonction 
						 		WHERE news.id 	= newsfonction.id 
						 		AND fonction.id = newsfonction.id_fonction 
						 		AND membre.id 	= membrefonction.id 
						 		AND fonction.id = membrefonction.id_fonction
						 		AND membre.mail = "'.$_SESSION["mail"].'"
						 		AND id_Type_d_actualite = '.$donnees->id.'
						 		ORDER BY news.timestampNews DESC')->fetch_object();	
				if($actu->nbre >= 1)
				{
					$typeActualite[$donnees->id]['id'] = $donnees->id;
					$typeActualite[$donnees->id]['nom'] = $donnees->nom;
				}
			}
		}
		else
		{
			$actu = run('	SELECT COUNT(*) as nbre
					 		FROM news,fonction,newsfonction
					 		WHERE news.id 	= newsfonction.id 
					 		AND fonction.id = newsfonction.id_fonction 
					 		AND newsfonction.id_fonction = 1
					 		AND id_Type_d_actualite = '.$donnees->id.'
					 		ORDER BY news.timestampNews DESC')->fetch_object();
			if($actu->nbre >= 1)
			{
				$typeActualite[$donnees->id]['id'] = $donnees->id;
				$typeActualite[$donnees->id]['nom'] = $donnees->nom;
			}		
		}
	}
	return $typeActualite;
}
function nombreActualite($type = 0)
// Nombre d'actualité en fonction de la fonction & superAdmin
{
	if($type == 0 || !is_numeric($type))
	{
		if(!empty($_SESSION['mail']))
		{
			$superAdmin = run('	SELECT COUNT(*) as admin 
								FROM membre 
								WHERE mail="'.$_SESSION['mail'].'" 
								AND isSuperAdmin = 1')->fetch_object();
			if($superAdmin->admin == 1)
			{	// Tout car admin
				$nbre = run('	SELECT DISTINCT COUNT(DISTINCT news.id) as nbre
						 		FROM news 
						 		ORDER BY news.timestampNews DESC')->fetch_object();
			}
			else
			{	// seulement ceux de ta fonction
				$nbre = run('	SELECT COUNT(DISTINCT news.id) as nbre
						 		FROM news,fonction,newsfonction,membre,membrefonction 
						 		WHERE news.id 	= newsfonction.id 
						 		AND fonction.id = newsfonction.id_fonction 
						 		AND membre.id 	= membrefonction.id 
						 		AND fonction.id = membrefonction.id_fonction
						 		AND membre.mail = "'.$_SESSION["mail"].'"
						 		ORDER BY news.timestampNews DESC')->fetch_object();
			}
		}
		else
		{	// Pour public
			$nbre = run('	SELECT COUNT(DISTINCT news.id) as nbre
					 		FROM news,fonction,newsfonction
					 		WHERE news.id 	= newsfonction.id 
					 		AND fonction.id = newsfonction.id_fonction 
					 		AND newsfonction.id_fonction = 1
					 		ORDER BY news.timestampNews DESC')->fetch_object();		
		}		
	}
	else
	{
		// L'utilisateur a choisi un type
		if(!empty($_SESSION['mail']))
		{
			$superAdmin = run('SELECT COUNT(*) as admin FROM membre WHERE mail="'.$_SESSION['mail'].'" AND isSuperAdmin = 1')->fetch_object();
			if($superAdmin->admin == 1)
			{	// Tout car admin
				$nbre = run('	SELECT COUNT(DISTINCT news.id) as nbre
						 		FROM news 
						 		WHERE id_Type_d_actualite = '.$type.'
						 		ORDER BY news.timestampNews DESC')->fetch_object();
			}
			else
			{	// seulement ta fonction
				$nbre = run('	SELECT COUNT(DISTINCT news.id) as nbre
						 		FROM news,fonction,newsfonction,membre,membrefonction 
						 		WHERE news.id 	= newsfonction.id 
						 		AND fonction.id = newsfonction.id_fonction 
						 		AND membre.id 	= membrefonction.id 
						 		AND fonction.id = membrefonction.id_fonction
						 		AND membre.mail = "'.$_SESSION["mail"].'"
						 		AND id_Type_d_actualite = '.$type.'
						 		ORDER BY news.timestampNews DESC')->fetch_object();
			}
		}
		else
		{	// Pour public
			$nbre = run('	SELECT COUNT(DISTINCT news.id) as nbre
					 		FROM news,fonction,newsfonction
					 		WHERE news.id 	= newsfonction.id 
					 		AND fonction.id = newsfonction.id_fonction 
					 		AND newsfonction.id_fonction = 1
						 	AND id_Type_d_actualite = '.$type.'
					 		ORDER BY news.timestampNews DESC')->fetch_object();		
		}
	}
	return $nbre->nbre;
}
function listeActualite($page, $nbreBilletParPage, $type = 0)
// Liste toute les actualités (tout si admin, sinon seulement ce à quoi ta fonction t'autorise, sinon seulement le public)
{
	// Si on est à la page n° $page, alors les entrés commence à partir de :
	$premierActualiteASortir = ($page-1)*$nbreBilletParPage;
	if($type == 0 || !is_numeric($type))
	{
		if(!empty($_SESSION['mail']))
		{
			$superAdmin = run('SELECT COUNT(*) as admin FROM membre WHERE mail="'.$_SESSION['mail'].'" AND isSuperAdmin = 1')->fetch_object();
			if($superAdmin->admin == 1)
			{	// Tout car admin
				$actu = run('	SELECT DISTINCT news.id AS idNews, news.titreNewsFR, news.contenuNewsFR, news.id_Type_d_actualite, DATE_FORMAT(news.timestampNews, "%d/%m/%y à %H:%i") AS timestampNews
						 		FROM news 
						 		ORDER BY news.timestampNews DESC
								LIMIT '.$premierActualiteASortir.','.$nbreBilletParPage);
			}
			else
			{	// seulement ta fonction
				$actu = run('	SELECT DISTINCT news.id AS idNews, news.titreNewsFR, news.contenuNewsFR, news.id_Type_d_actualite, DATE_FORMAT(news.timestampNews, "%d/%m/%y à %H:%i") AS timestampNews
						 		FROM news,fonction,newsfonction,membre,membrefonction 
						 		WHERE news.id 	= newsfonction.id 
						 		AND fonction.id = newsfonction.id_fonction 
						 		AND membre.id 	= membrefonction.id 
						 		AND fonction.id = membrefonction.id_fonction
						 		AND membre.mail = "'.$_SESSION["mail"].'"
						 		ORDER BY news.timestampNews DESC
								LIMIT '.$premierActualiteASortir.','.$nbreBilletParPage);
			}
		}
		else
		{	// Pour public
			$actu = run('	SELECT DISTINCT news.id AS idNews, news.titreNewsFR, news.contenuNewsFR, news.id_Type_d_actualite, DATE_FORMAT(news.timestampNews, "%d/%m/%y à %H:%i") AS timestampNews
					 		FROM news,fonction,newsfonction
					 		WHERE news.id 	= newsfonction.id 
					 		AND fonction.id = newsfonction.id_fonction 
					 		AND newsfonction.id_fonction = 1
					 		ORDER BY news.timestampNews DESC
							LIMIT '.$premierActualiteASortir.','.$nbreBilletParPage);		
		}
	}
	else
	{
		// L'utilisateur a choisi un type
		if(!empty($_SESSION['mail']))
		{
			$superAdmin = run('SELECT COUNT(*) as admin FROM membre WHERE mail="'.$_SESSION['mail'].'" AND isSuperAdmin = 1')->fetch_object();
			if($superAdmin->admin == 1)
			{	// Tout car admin
				$actu = run('	SELECT DISTINCT news.id AS idNews, news.titreNewsFR, news.contenuNewsFR, news.id_Type_d_actualite, DATE_FORMAT(news.timestampNews, "%d/%m/%y à %H:%i") AS timestampNews
						 		FROM news 
						 		WHERE id_Type_d_actualite = '.$type.'
						 		ORDER BY news.timestampNews DESC
								LIMIT '.$premierActualiteASortir.','.$nbreBilletParPage);
			}
			else
			{	// seulement ta fonction
				$actu = run('	SELECT DISTINCT news.id AS idNews, news.titreNewsFR, news.contenuNewsFR, news.id_Type_d_actualite, DATE_FORMAT(news.timestampNews, "%d/%m/%y à %H:%i") AS timestampNews
						 		FROM news,fonction,newsfonction,membre,membrefonction 
						 		WHERE news.id 	= newsfonction.id 
						 		AND fonction.id = newsfonction.id_fonction 
						 		AND membre.id 	= membrefonction.id 
						 		AND fonction.id = membrefonction.id_fonction
						 		AND membre.mail = "'.$_SESSION["mail"].'"
						 		AND id_Type_d_actualite = '.$type.'
						 		ORDER BY news.timestampNews DESC
								LIMIT '.$premierActualiteASortir.','.$nbreBilletParPage);
			}
		}
		else
		{	// Pour public
			$actu = run('	SELECT DISTINCT news.id AS idNews, news.titreNewsFR, news.contenuNewsFR, news.id_Type_d_actualite, DATE_FORMAT(news.timestampNews, "%d/%m/%y à %H:%i") AS timestampNews
					 		FROM news,fonction,newsfonction
					 		WHERE news.id 	= newsfonction.id 
					 		AND fonction.id = newsfonction.id_fonction 
					 		AND newsfonction.id_fonction = 1
						 	AND id_Type_d_actualite = '.$type.'
					 		ORDER BY news.timestampNews DESC
							LIMIT '.$premierActualiteASortir.','.$nbreBilletParPage);		
		}
	}
	while($tmp = $actu->fetch_object())
	{
		$liste[$tmp->idNews]['id'] = $tmp->idNews;
		$liste[$tmp->idNews]['titre'] = $tmp->titreNewsFR;
		$liste[$tmp->idNews]['contenu']	 = $tmp->contenuNewsFR;
		$liste[$tmp->idNews]['id_Type_d_actualite']	= $tmp->id_Type_d_actualite;
		$liste[$tmp->idNews]['timestamp'] = $tmp->timestampNews;
	}
	if(!empty($liste))	
		// Ceci est pour s'assurer de ne rien retourner si il n'y a aucune liste.
	{
		return $liste;
	}
}

function returnNombreBilletParPage()
	// Retourne le nombre de billet par page
{
	$tmp = run('SELECT nombreBilletActualiteParPage FROM infolivreoractualite WHERE id=1')->fetch_object();
	$tmp = $tmp->nombreBilletActualiteParPage;
	return $tmp;
}
function newNombreBilletParPage($nombreBilletParPage)
	// Modifie le nombre de billet par page
{
	run ('UPDATE infolivreoractualite SET nombreBilletActualiteParPage = '.$nombreBilletParPage.' WHERE id=1');
}
?>