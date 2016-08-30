<?
$domaine="legacytelecom.co.uk";
$tab=@file("http://www.amen.fr/cobalt/index.php?domaine=legacytelecom.co.uk");
print(str_replace("votre_nom_de_domaine",$domaine,@implode("",$tab)));
?>
