<?php
session_start();

// On définit la configuration :
$nbr_chiffres = 6;
// Nombre de chiffres qui formeront le nombre
header ("Content-type: image/png");
//On définit le header de la page pour la transformer en image
$_img = imagecreatefrompng('fond_verif_img.png');
//On crée notre image
$arriere_plan = imagecolorallocate($_img, 0, 0, 0);
$avant_plan = imagecolorallocate($_img, 240, 240, 240); 
// Couleur des chiffres
$grey = imagecolorallocate($_img, 186, 186, 186);

$i = 0;
while($i < $nbr_chiffres) {
        $chiffre = mt_rand(0, 9);
        // On génère le nombre aléatoire
        $chiffres[$i] = $chiffre;
        $i++;
}
$nombre = null;
foreach ($chiffres as $caractere) {
// On explore le tableau $chiffres afin d'y afficher toutes les entrées qui s'y trouvent
        $nombre .= $caractere;
}
//On a fini de créer le nombre aléatoire, on le rentre maintenant dans une variable de session
$_SESSION['aleat_nbr'] = $nombre;
// On détruit les variables inutiles :
unset($chiffre);
unset($i);
unset($caractere);
unset($chiffres);

$rand=mt_rand(10,24);
imagestring($_img, 5, 18, 8, $nombre, $avant_plan);
imageline($_img, 2,$rand, 90 - 2, (2*17-$rand), $grey);
imageline($_img, 2,mt_rand(0,9), 90 - 2, mt_rand(0,10), $avant_plan); 
imageline($_img, 2,mt_rand(25,32), 90 - 2, mt_rand(24,32), $avant_plan); 

imagepng($_img);
?>