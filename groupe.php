<?php

//INCLUDE
include_once("include.php");
include_once("header.php");

echo"</br>";

//AFFICHAGE DU NOM DE L'UTILISATEUR CONNECTE
if(isset($_SESSION["login"]))
{
	echo"<font color=\"green\">Vous êtes connecté en tant que ".$_SESSION["login"]."</font>";
}
else
{
	//REDIRECTION VERS LOGIN.PHP SI LES IDENTIFIANTS SONT INCORRECTES
	header('Location: login.php');
} 
?>

<!-- DEBUT DU FORMULAIRE --> 
<form name='form' method='POST'>
		
<!--BOUTON REDIRECTION VERS CONTACT.PHP -->
</br> 
<input type='submit' name='retour' value='Page précédente'>
		
<!-- BOUTON VALIDATION SUPPRESSION -->
&nbsp;&nbsp;&nbsp;<input type='submit' value ='Supprimer' name='supprim'/>
</br>
</br>
<table border='3' BORDERCOLOR=black>
<tr>
<td style='background-color:grey'><b>Identifiant<b></td>
<td><b>Nom<b></td>
</tr>

<?php
						
//REQUETE POUR SELECTIONNER LE CONTENU DE LA TABLE GROUPE
$requete_affichage_grp="select * from groupe inner join client_groupe ON groupe.id_groupe=client_groupe.id_groupe where client_groupe.id_client='".$_SESSION['id_client']."'";
$resultat_aff_groupe=mysql_query($requete_affichage_grp);
						
//AFFICHAGE DES GROUPES DANS UN TABLEAU
while ($ligne = mysql_fetch_array($resultat_aff_groupe))
{ 
	echo"<tr>";
	echo"<td style='background-color:grey'>";
	echo $ligne['id_groupe'];
	echo"</td>"; 
	echo"<td>";
	echo $ligne['nom_groupe'];
	echo"<td>";
	echo"<a href='groupe.php?act=modif&id_groupe=". $ligne["id_groupe"] . "'><font color='black'>Modifier</a>";
	echo"<td>";
	echo '<input type="checkbox" name="chec[]" value='. $ligne["id_groupe"] .'/>';
	echo"</td>";
	echo "</tr>";
}
//FIN DE L'AFFICHAGE DES GROUPES

//SI ON APPUIE SUR LE BOUTON DE MODIFICATION
//AFFICHAGE DU FORMULAIRE DE MODIFICATION
if($_GET["act"] == "modif")
{
	//REQUETE SELECT GROUPE
	echo"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i><u><b><font color='black'>Modifier un groupe :</font><b></u></i>";
	echo"</br>";
	echo"</br>";
	$requete_groupe="select * from groupe where id_groupe=".$_GET['id_groupe'];
	$resultat_groupe=mysql_query($requete_groupe)or die( "ERREUR SQL!! ");
	$val1 = mysql_fetch_array($resultat_groupe);
			
	//RECUPERATION DES DONNEES DU GROUPE SELECTIONE
	echo"<b><label for='nom'>Nom du groupe</label><input type='text' name='nom_groupe' value='". $val1['nom_groupe'] . "'>";
	echo"</br>";
	echo"<label for='id_groupe'></label><input type='hidden' name='id_groupe' value='".$_GET['id_groupe']."'>";
	echo"</br>";
				
	//BOUTON DE VALIDATION DE LA MODIFICATION
	echo"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type='submit' value='Valider' name='valid'>";
	echo"</br>";
	echo"</br>";
}

//SI ON APPUIE SUR LE BOUTON DE VALIDATION DE MISE A JOUR
//MISE A JOUR DE LA MODIFICATION DES GROUPES
if(isset($_POST["valid"]))
{
	//REQUETE MISE A JOUR DU NOM DU GROUPE
	$requete_maj_grp="UPDATE groupe SET nom_groupe = '".$_POST['nom_groupe']."' WHERE groupe.id_groupe = '".$_GET['id_groupe']."'";
	$resultat_maj_grp=mysql_query($requete_maj_grp)or die( "ERREUR SQL!! ");
							
	//RAFRAICHISSEMENT DE LA PAGE
	header('Location: groupe.php');
}	
//FIN DE LA MODIFICATION DES GROUPES

//CHECKBOX POUR SUPPRIMER DES GROUPES
$chec= isset($_POST['chec']) ? $_POST['chec'] : "";
//SI ON APPUIE SUR LE BOUTON DE VALIDATION DE SUPPRESSION
//ON SUPPRIME LES GROUPE SELECTIONNER (VIA CHECKBOX)
if (isset($_POST['supprim']))
{
	foreach($chec as $chec)
	{ 
		if(!empty($chec))
		{	
			//REQUETE_DL_GROUPE
			$requete_suppr_contact_groupe="DELETE FROM contact_groupe WHERE contact_groupe.id_groupe = '$chec'";
			$requete_suppr_clientgroupe="DELETE FROM client_groupe WHERE client_groupe.id_groupe = '$chec'";
			$requete_suppr_groupe="DELETE FROM groupe WHERE groupe.id_groupe = '$chec'";

			//ALERTE : ETES VOUS SUR DE VOULOIR DL LE GROUPE
			echo"<script>confirm('Le groupe va etre supprimer ? ');</script>";
						
			//RAFRAICHISSEMENT DE LA PAGE
			header ("Refresh: 1;URL=groupe.php");
									
			//EXECUTION DES REQUETTE_DL_GROUPE
			$requete_dl_contactgroupe= mysql_query($requete_suppr_contact_groupe); 
			$requete_dl_clientgroupe= mysql_query($requete_suppr_clientgroupe);
			$requete_dl_groupe= mysql_query($requete_suppr_groupe) or die( "ERREUR SQL!! ");
		}
	}	
}
//FIN SUPPRESSION DES GROUPES AVEC LES CHECKBOX
//SI ON APPUIE SUR LE BOUTON DE REDIRECTION
//REDIRECTION CONTACT.PHP

if(isset($_POST["retour"]))
{
	header('Location: contact.php');
}				
?>
</form>
