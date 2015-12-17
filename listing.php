<?php

include_once("include.php");
include_once("header.php");

if(isset($_SESSION["login"]))
{
	echo"<font color=\"green\">Vous êtes connecté en tant que ".$_SESSION["login"]."</font>";
}
else
{
	header('Location: login.php');
}
echo "</br>";
echo "</br>";

?>		

<!-- DEBUT DU FORM -->
<form name='form' method='POST'>&nbsp;&nbsp;
<input type='submit' name='retour' value='Page précédente'>&nbsp;&nbsp;
&nbsp;&nbsp;<input type='submit' name='generer' value='Generer XML'>&nbsp;&nbsp;&nbsp;

<?php
	
//REDIRECTION VERS CONTACT.PHP -->
if(isset($_POST["retour"]))
{
	header('Location: contact.php');
}

//DEBUT GENERATION XML AVEC ID_CLIENT_MD5
if(isset($_POST["generer"]))
{	
	$requete_generer_xml = "SELECT * FROM client where id_client='".$_SESSION['id_client']."'";
	$resultat_xml_md5 = mysql_query($requete_generer_xml);

	while($recup_md5 = mysql_fetch_array($resultat_xml_md5))
	{
		$resultat_aff_md5=$recup_md5['id_client_md5'];

		header("Location:phonebook.php?id=$resultat_aff_md5");
	}	
}

//DEBUT DE LA RECHERCHE
if(isset($_POST['smt_recherche']))
{
	$req="select * from contact inner join client_contact ON contact.id_contact=client_contact.id_contact where client_contact.id_client='".$_SESSION['id_client']."' AND (nom LIKE '%" . $_POST['recherche'] . "%' OR prenom LIKE '%" . $_POST['recherche'] . "%' OR pays LIKE '%" . $_POST['recherche'] . "%'  OR num_domicile LIKE '%" . $_POST['recherche'] . "%' OR num_mobile LIKE '%" . $_POST['recherche'] . "%' OR num_travail LIKE '%" . $_POST['recherche'] . "%')";
}
else //REQUETE_AFFICHAGE_CONTACT
{
	$req="select * from contact inner join client_contact ON contact.id_contact=client_contact.id_contact where client_contact.id_client='".$_SESSION['id_client']."' ";
}
//FIN DE LA RECHERCHE

//DEBUT DU TABLEAU POUR AFFICHER LES CONTACTS
$res=mysql_query($req);
echo "<table border='3'BORDERCOLOR=black>";
echo"<tr>";
echo"<td style='background-color:grey'><b>Identifiant<b></td>";
echo"<td><b>Nom<b></td>";
echo"<td><b>Prenom<b></td>";
echo"<td><b>Pays<b></td>";
echo"<td><b>Favorite<b></td>";
echo"<td><b>Numero Travail<b></td>";
echo"<td><b>Numero Domicile<b></td>";
echo"<td><b>Numero Mobile<b></td>";
echo"</tr>";
echo "<input type='submit' value ='Supprimer' name='supprim'";
echo"</br>";
echo"</br>";

//DEBUT DE LA MODIFICATION DES CONTACTS
if($_GET["act"] == "modif")
{	
	echo"</br>";
	echo"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i><u><b><font color='black'>Modifier un contact :</font><b></u></i>";
	echo"</br>";
	echo"</br>";
	$req1="select * from contact where id_contact=".$_GET['id_contact'];
	$res1=mysql_query($req1)or die( "ERREUR SQL!! ");
	$val1 = mysql_fetch_array($res1);
	//REQUETE_AFFICHAGE_GROUPE
	$req1="select * from groupe";
	$res2=mysql_query($req2);

	//DEBUT DU FORMULAIRE DE MODIFICATION
	echo"<b><label for='nom'>Nom</label><input type='text' name='nom' value='". $val1['nom'] . "'>";
	echo"</br>";
	echo"<label for='prenom'>Prenom</label><input type='text' name='prenom' value='". $val1['prenom'] . "'>";
	echo"</br>";
	echo"<label for='pays'>Pays</label><input type='text' name='pays' value='". $val1['pays'] . "'>";
	echo"</br>";
	echo"Favorite: oui<input type='radio' name='favori' value='1'><b>non<input type='radio' name='favori' value='0' checked><b><br/>";
	echo"</br>";
	echo"<label for='num_travail'>Numero travail</label><input type='text' name='num_travail' value='". $val1['num_travail'] . "'>";
	echo"</br>";
	echo "<label for='num_domicile'>Numéro domicile</label><input type='text' name='num_domicile' value='". $val1['num_domicile'] . "'>";
	echo"</br>";
	echo"<label for='num_mobile'>Numero mobile</label><input type='text' name='num_mobile' value='". $val1['num_mobile'] . "'>";
	echo"</br>";
	//REQUETE_AFFICHAGE_GROUPE
	$requete_aff_groupe=$req1="select * from groupe inner join client_groupe ON groupe.id_groupe=client_groupe.id_groupe where client_groupe.id_client='".$_SESSION['id_client']."'";
	$resultat_aff_groupe=mysql_query($requete_aff_groupe);
	$ress=mysql_query($req1);
	$req3="SELECT groupe.id_groupe FROM groupe INNER JOIN contact_groupe ON contact_groupe.id_groupe=groupe.id_groupe WHERE contact_groupe.id_contact='".$_GET['id_contact']."'";
	$resu=mysql_query($req3);
	
	while($val = mysql_fetch_array($resu))
	{
		$groupe[] = $val['id_groupe'];
	}
	echo"</br>";
	
	//AFFICHAGE DE L'ID GROUPE ET DU NOM DU GROUPE
	while($ligne= mysql_fetch_array($ress))
	{
		if(in_array($ligne['id_groupe'], $groupe))
		{
			echo"<input type='checkbox' name='checkb[]' value='".$ligne['id_groupe']."' checked>".$ligne['nom_groupe']."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		}
		else
		{
			echo"<input type='checkbox' name='checkb[]' value='".$ligne['id_groupe']."'>".$ligne['nom_groupe']."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		}
	}
	echo"<label for='id_contact'></label><input type='hidden' name='id_contact' value='".$_GET['id_contact']."'>";
	echo"</br>";
	echo"</br>";
	echo"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type='submit' value='Valider' name='valid'>";
	echo"</br>";
}
//FIN DE LA MODIFICATION

//MISE A JOUR DES ELEMENTS MODIFIER
if(isset($_POST["valid"]))
{	 
	$req3="UPDATE contact SET nom ='".$_POST['nom']."', prenom ='".$_POST['prenom']."', pays ='".$_POST['pays']."', favori ='".$_POST['favori']."', num_travail = '".$_POST['num_travail']."', num_domicile = '".$_POST['num_domicile']."', num_mobile = '".$_POST['num_mobile']."' WHERE contact.id_contact ='".$_GET['id_contact']."'";
	$res3=mysql_query($req3)or die( "ERREUR SQL!! ");
	$requette_suppression = "DELETE FROM contact_groupe WHERE id_contact=" . $_POST['id_contact'];
	$resultat_suppression = mysql_query($requette_suppression);

	//INSERTION DES VALEURS DES CHECKBOX
	foreach($_POST['checkb'] AS $value)
	{
		$insertion_id_contact_idgroupe="INSERT INTO contact_groupe (id_contact,id_groupe) VALUES('".$_POST['id_contact']."','".$value."')";
		$resultat_insert=mysql_query($insertion_id_contact_idgroupe);
	}
	header('Location: listing.php');
}	
//FIN UPDATE DES CONTACTS
	
echo "</br>";
echo "</br>";

//AFFFICHAGE DES CONTACTS DANS UN TABLEAU
while ($ligne = mysql_fetch_array($res))
{ 
	echo"<tr>";
	echo"<td style='background-color:grey'>";
	echo $ligne['id_contact'];
	echo"</td>"; 
	echo"<td>";
	echo $ligne['nom'];
	echo"</td>";
	echo"<td>";
	echo $ligne['prenom'];
	echo"</td>"; 
	echo"<td>";
	echo $ligne['pays'];
	echo"</td>"; 
	echo"<td>";
	echo $ligne['favori'];
	echo"</td>";
	echo"<td>";
	echo $ligne['num_travail'];
	echo"</td>"; 
	echo"<td>"; 
	echo $ligne['num_domicile'];
	echo"</td>";
	echo"<td>";
	echo $ligne['num_mobile'];
	echo"</td>";
	echo"<td>";
	echo"<a href='listing.php?act=modif&id_contact=". $ligne["id_contact"] . "'><font color='black'>Modifier</a>";
	echo"</td>";
	echo"<td>";
	echo '<input type="checkbox" name="check[]" value=' . $ligne["id_contact"] . '/>';
	echo"</td>";
	echo "</tr>";
}

//DEBUT SUPPRESSION AVEC CHECKBOX
$check= isset($_POST['check']) ? $_POST['check'] : "";
if (isset($_POST['supprim']))
{
	foreach($check as $check)
	{ 
		if(!empty($check))
		{	
			$requete_suppr_contact_groupe="DELETE FROM contact_groupe WHERE contact_groupe.id_contact ='$check'";
			$requete_suppr_contact_client="DELETE FROM client_contact WHERE client_contact.id_contact ='$check'";
			$requete_suppr_contact="DELETE FROM contact WHERE contact.id_contact ='$check'";
			echo"<script>confirm('Le contact va etre supprimer ? ');</script>";
			header ("Refresh: 1;URL=listing.php");
			$requete_dl_contact_groupe= mysql_query($requete_suppr_contact_groupe); 
			$requete_dl_contact_client= mysql_query($requete_suppr_contact_client);
			$requete_dl_contact= mysql_query($requete_suppr_contact)or die( "ERREUR SQL!! "); 
		}
	}
}
//FIN DES SUPPRESSION AVEC CHECKBOX

//FIN DU FORM
//FIN TABLE
echo "</form>";
echo "</table>";

?>

<?php

include_once("footer.php");

?>
