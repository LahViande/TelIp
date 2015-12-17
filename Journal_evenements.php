<?php
include_once("include.php");
include_once("header.php");

echo"</br>";
//AFFICHAGE DU LOGIN DE L'UTILISATEUR CO
if(isset($_SESSION["login"]))
{
	echo"<font color=\"green\">Vous êtes connecté en tant que ".$_SESSION["login"]."</font>";
}
else
{
	header('Location: login.php');
}

?>

<!-- DEBUT DU FORMULAIRE -->
<!-- JOURNAL E-MAIL -->
<form name='form' method='POST'>
<input type='submit' name='retour_gestion' value='Page précédente'>&nbsp;&nbsp;

<?php

//REDIRECTION BOUTON RETOUR 
if(isset($_POST["retour_gestion"]) )
{
	header('Location: gestion_user.php');
}

//DEBUT TABLEAU DES MAILS
$requete_recup_mail="select * from log_email";
$resultat_mail=mysql_query($requete_recup_mail);
echo"</br>";
echo"</br>";
echo"<b><span style='border:1px solid black;padding:0%'><font color='black'>Journal d'événements  E-mail</font></span></b>";
echo"</br>";
echo"</br>";
echo "<table border='3'BORDERCOLOR=black>";
echo"<tr>";
echo"<td style='background-color:grey'><b>Identifiant Mail<b></td>";
echo"<td style='background-color:grey'><b>Destinataire<b></td>";
echo"<td style='background-color:grey'><b>Objet<b></td>";
echo"<td style='background-color:grey'><b>Contenu<b></td>";
echo"<td style='background-color:grey'><b>Horodatage<b></td>";
echo"</tr>";

//BOUCLE AFFICHAGE DES ELEMENTS DANS LE TABLEAU			
while ($ligne = mysql_fetch_array($resultat_mail))
{ 
	echo"<tr>";
	echo"<td style='background-color:grey'>";
	echo $ligne['id_mail'];
	echo"</td>"; 
	echo"<td style='background-color:#EFEECA'>";
	echo $ligne['destinataire'];
	echo"</td>";
	echo"<td style='background-color:#EFEECA'>";
	echo $ligne['objet'];
	echo"</td>"; 
	echo"<td style='background-color:#EFEECA'>";
	echo $ligne['contenu'];
	echo"</td>";
	echo"<td style='background-color:#EFEECA'>";
	echo $ligne['horodatage'] ;
	echo"</td>";
	echo "</tr>";
}
//FIN JOURNAL E-MAIL

//FIN DE LA TABLE
echo"</table>";

//JOURNAL DE CONNEXION LOGIN
echo"</br>";
echo"<b><span style='border:1px solid black;padding:0%'><font color='black'>Journal d'événements  Login</font></span></b>";
echo "<table border='3'BORDERCOLOR=black>";
echo"<tr>";
echo"<td style='background-color:grey'><b>id<b></td>";
echo"<td style='background-color:grey'><b>Login<b></td>";
echo"<td style='background-color:grey'><b>Heure<b></td>";
echo"</tr>";
echo"</br>";
echo"</br>";

//REQUETE LOG_CONNEXION
$requete_recup_log="select * from log_connexion";
$resultat_log=mysql_query($requete_recup_log);

//BOUCLE QUI PERMET D'AFFICHER LES ELEMENTS DE LA BASE DE DONNEES DANS UN TABLEAU
while ($ligne = mysql_fetch_array($resultat_log))
{ 
	echo"<tr>";
	echo"<td style='background-color:#EFEECA'>";
	echo $ligne['id_log_connexion'];
	echo"</td>"; 
	echo"<td style='background-color:#EFEECA'>";
	echo $ligne['log_login'];
	echo"</td>"; 
	echo"<td style='background-color:#EFEECA'>";
	echo $ligne['log_date'];
	echo"</td>"; 
	echo "</tr>";
}

?>
</form>
<!--FOOTER-->
<?php
//FERMETURE DE LA BASE DE DONNEES
mysql_close($mysql);

?>